<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportError extends Model
{
    protected $fillable = [
        'import_id',
        'row_number',
        'error_message'
    ];

    public function import()
    {
        return $this->belongsTo(Import::class);
    }
}
