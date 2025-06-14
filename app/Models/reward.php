<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reward extends Model
{
    protected $table = 'rewards';
    protected $fillable = [
        'member_profile_id',
        'reward_type',
        'reward_status',
    ];

    // App\Models\Reward.php
public function memberProfile()
{
    return $this->belongsTo(member::class, 'member_profile_id');
}


    
}
