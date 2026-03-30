<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Import;

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
        $import->load('errors');

        return view('imports.show', [
            'import' => $import,
        ]);
    }
}
