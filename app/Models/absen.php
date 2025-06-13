<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class absen extends Model
{
        protected $table = 'attends';

    protected $fillable = [
        'member_profile_id', 'scan_time',
    ];

    public function memberProfile()
    {
        return $this->belongsTo(member::class, 'member_profile_id');
    }
}
