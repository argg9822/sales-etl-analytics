<?php

namespace App\Http\Controllers;
use App\Models\Import;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('imports.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('imports.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Import $import)
    {
        $import->loadMissing(['errors']);
        
        $errors = $import->errors()
            ->latest()
            ->paginate(50);
        
        return view('imports.show', [
            'import' => $import,
            'errors' => $errors,
        ]);
    }
}
