<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    protected $fillable = [
        'file_name',
        'status',
        'total_records',
        'processed_records',
        'file_path'
    ];

    public function errors()
    {
        return $this->hasMany(ImportError::class);
    }
}
