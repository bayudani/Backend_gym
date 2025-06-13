<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class membership extends Model
{
    protected $table = 'memberships';
    protected $fillable = [
        'name', 'price', 'duration_months',
    ];

    public function members()
    {
        return $this->hasMany(member::class, 'membership_id');
    }
    public function transactions()
    {
        return $this->hasMany(transaction::class, 'membership_package_id');
    }
}
