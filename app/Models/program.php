<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class program extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
    ];
}
