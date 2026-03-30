<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportStoreRequest;
use App\Http\Resources\Api\V1\ImportErrorResource;
use App\Http\Resources\Api\V1\ImportIndexCollection;
use App\Http\Resources\Api\V1\ImportResource;
use App\Models\Import;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\JsonResponse;
use App\Services\ImportService;
use Exception;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function __construct(protected ImportService $importService)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imports = Import::select('id', 'file_name', 'status', 'total_records', 'processed_records', 'created_at')
            ->orderBy('created_at', 'desc')
            ->withCount('errors')
            ->latest()
            ->paginate(20);
        
        if($imports->isEmpty()) {
            return response()->json([
                'message' => 'No se han encontrado importaciones.'
            ], Response::HTTP_OK);
        }

        return new ImportIndexCollection($imports);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ImportStoreRequest $request): JsonResponse
    {
        $file = $request->file('csv_file');
        $filename = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('imports', $filename);


        // Procesar archivo CSV
        $this->importService->processImport($filePath);

        return response()->json([
            'message' => 'El archivo se ha subido y se está procesando.', 
            'file_path' => $filePath
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Import $import)
    {
        $import->load('errors');

        return new ImportResource($import);
    }

    public function errors(Import $import)
    {
        $errors = $import->errors()->select('id', 'import_id', 'row_number', 'error_message', 'created_at')->latest()->paginate(20);

        return ImportErrorResource::collection($errors);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Import $import)
    {
        try{
            $import->delete();
            return response()->json([
                'message' => 'Importación eliminada exitosamente.'
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error('Error al eliminar importación: ' . $e->getMessage());
            return response()->json([
                'message' => 'Ocurrió un error al eliminar la importación.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
