<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Docent extends Model
{
    protected $table = 'docenten';
    protected $fillable = ['docentnummer'];
}
