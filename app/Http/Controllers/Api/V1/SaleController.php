<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function summary(Request $request): JsonResponse
    {
        $importId = $request->query('import_id');

        if(!$importId) {
            return response()->json([
                'message' => 'El id de la importación es requerido.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // Total de ingresos
        $totalAmount = Sale::where('import_id', $importId)
            ->sum('total_price');

        // Top 5 productos
        $topProducts = Sale::where('import_id', $importId)
            ->select('product_name', DB::raw('SUM(total_price) as total_amount'))
            ->groupBy('product_name')
            ->orderBy('total_amount', 'DESC')
            ->limit(5)
            ->get();

        // Distribución por Categoría
        $categoryDistribution = Sale::where('import_id', $importId)
            ->select('category', DB::raw('SUM(total_price) as total_amount'))
            ->groupBy('category')
            ->get();

        // Distribución Geográfica
        $geoDistribution = Sale::where('import_id', $importId)
            ->select('country', DB::raw('SUM(total_price) as total_amount'))
            ->groupBy('country')
            ->get();

        return response()->json([
            'total_amount' => $totalAmount,
            'top_products' => $topProducts,
            'category_distribution' => $categoryDistribution,
            'geographical_distribution' => $geoDistribution,
        ], Response::HTTP_OK);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
    }
}
