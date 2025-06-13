<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    protected $table = 'member_profiles';
    protected $fillable = [
        'user_id', 'membership_id', 'addres', 'phone_number',
        'is_active', 'start_date', 'end_date','full_name'
    ];

    public function user() {
    return $this->belongsTo(User::class);
}



public function attendances() {
    return $this->hasMany(absen::class);
}

}
