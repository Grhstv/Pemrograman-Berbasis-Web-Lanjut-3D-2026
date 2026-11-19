<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class penerbit extends Model
{
    /** @use HasFactory<\Database\Factories\PenerbitFactory> */
    use HasFactory;

    protected $table = 'penerbits';
    protected $fillable = ['nama'];
}
