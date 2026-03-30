<?php

namespace App\Jobs;

use App\Models\Import;
use App\Models\ImportError;
use App\Models\Sale;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ProcessImportJob implements ShouldQueue
{
    use Queueable;

    /**
     * The file path for the import.
     *
     * @var string
     */
    protected $filePath;
    private $importId;

    /**
     * Create a new job instance.
     *
     * @param string $filePath
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if(!Storage::disk('local')->exists($this->filePath)) {
                Log::error('Archivo no encontrado: ' . $this->filePath);
                return;
            }

            $filePath = Storage::disk('local')->path($this->filePath);

            $handle = fopen($filePath, 'r');

            if($handle === false) {
                Log::error('No se pudo abrir el archivo: ' . $filePath);
                return;
            }

            $header = fgetcsv($handle);

            if($header === false) {
                Log::error('No se pudieron leer los encabezados del archivo: ' . $filePath);
                fclose($handle);
                return;
            }            

            $this->importId = Import::create([
                'file_name' => basename($filePath),
                'file_path' => $this->filePath,
                'status' => 'processing'                
            ])->id;

            $counter = 0;            
            $batchSize = 500;
            $sales = [];
            $errors = [];
            $errorsCounter = 0;
                
            while(($row = fgetcsv($handle)) !== false) {
                $counter++;

                if($counter % 1000 === 0) {
                    gc_collect_cycles();
                }

                $data = array_combine($header, $row);
                $validatedData = $this->dataValidate($data);

                if($validatedData['hasErrors']) {
                    foreach($validatedData['errors'] as $error) {
                        $errors[] = [
                            'import_id' => $this->importId,
                            'row_number' => $counter,
                            'error_message' => $error,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    $errorsCounter++;
                } else {
                    $sales[] = $validatedData['data'];
                }
                
                if(count($sales) >= $batchSize) {
                    Sale::insert($sales);
                    $sales = [];
                } elseif(count($errors) >= $batchSize) {
                    ImportError::insert($errors);
                    $errors = [];
                }
            }

            if(count($sales) > 0) {
                Sale::insert($sales);
                $sales = [];
            } 

            if(count($errors) > 0) {
                ImportError::insert($errors);
                $errors = [];
            }

            Import::where('id', $this->importId)->update([
                'status' => $errorsCounter > 0 ? 'completed-errors' : 'completed',
                'total_records' => $counter,
                'processed_records' => $counter - $errorsCounter,
            ]);
        } catch (Exception $e) {
            Log::error('Error al procesar el archivo: ' . $e->getMessage());
        } finally {
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
        }
    }

    private function dataValidate(array $row)
    {
        $orderId = $row['order_id'] ?? null;
        $date = $row['date'] ?? null;
        $customerId = $row['customer_id'] ?? null;
        $customerName = $row['customer_name'] ?? null;
        $productId = $row['product_id'] ?? null;
        $productName = $row['product_name'] ?? null;
        $category = $row['category'] ?? null;
        $quantity = isset($row['quantity']) ? (int)$row['quantity'] : null;
        $unitPrice = isset($row['unit_price']) ? (float)$row['unit_price'] : null;
        $discount = isset($row['discount']) ? (float)$row['discount'] : 0;
        $country = $row['country'] ?? null;

        $errors = [];
        
        if (
            empty($orderId) || 
            empty($date) || 
            empty($customerId) || 
            empty($customerName) || 
            empty($productId) || 
            empty($productName) || 
            empty($category) || 
            empty($quantity) || 
            empty($unitPrice) || 
            empty($country)
        ) 
        {
            $errors[] = 'Falta uno o más campos obligatorios';
        }

        if (!$date || !strtotime($date)) {
            $errors[] = 'Fecha inválida';
        }

        if ($quantity <= 0) {
            $errors[] = 'Cantidad inválida';
        }

        if ($unitPrice < 0) {
            $errors[] = 'Precio negativo';
        }

        if(count($errors) > 0){
            return [
                'hasErrors' => true,
                'errors' => $errors
            ];
        }else{
            $total = $quantity * $unitPrice * (1 - $discount);
    
            return [
                'hasErrors' => false,
                'data' => [
                    'import_id' => $this->importId,
                    'order_id' => $orderId,
                    'sale_date' => $date,
                    'customer_id' => $customerId,
                    'customer_name' => $customerName,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'category' => $category,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $total,
                    'discount' => $discount,
                    'country' => $country,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            ];
        }
    }
}
