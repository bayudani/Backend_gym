<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    protected $fillable = [
        'userId', 'membership_package_id', 'amount', 'status','proof_image','status'
    ];

    public function user()
{
    return $this->belongsTo(User::class, 'userId');
}

    public function membershipPackage()
{
    return $this->belongsTo(membership::class, 'membership_package_id');
}
}
