<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RukoModel extends Model
{
    use HasFactory;
    protected $table = 'tb_ruko';
    protected $fillable = [
        'id', 'nama_ruko', 'gambar_ruko', 'created_at', 'updated_at'
    ];
}
