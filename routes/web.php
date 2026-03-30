<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImportController;

Route::resource('imports', ImportController::class)->only(['index', 'show', 'create'])->names([
    'index' => 'imports.index.web',
    'create' => 'imports.create',
    'show' => 'imports.show.web',
]);

Route::get('/', function () {
    return redirect()->route('imports.index.web');
});
