<?php

namespace App\Services;

use App\Jobs\ProcessImportJob;

class ImportService
{
    /**
     * Process the uploaded CSV file.
     *
     * @param string $filePath The path to the uploaded CSV file.
     */
    public function processImport(string $filePath): void
    {
        ProcessImportJob::dispatch($filePath);
    }
}