<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class reward extends Model
{
    protected $table = 'rewardss';
    protected $fillable = [
        'member_profile_id',
        'item_reward_id',
        'reward_status',
    ];

    // App\Models\Reward.php
public function memberProfile()
{
    return $this->belongsTo(member::class, 'member_profile_id');
}
    public function itemReward()
    {
        return $this->belongsTo(itemRewards::class, 'item_reward_id');
    }


    
}
