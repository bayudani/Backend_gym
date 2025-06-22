<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class itemRewards extends Model
{
    //
    protected $table = 'item_rewards';
    protected $fillable = [
        'name',
        'points',
        'image',
    ];
}
