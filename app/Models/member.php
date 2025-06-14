<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class member extends Model
{
    protected $table = 'member_profiles';
    protected $fillable = [
        'user_id',
        'membership_id',
        'addres',
        'phone_number',
        'is_active',
        'start_date',
        'end_date',
        'full_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }



    public function attendances()
    {
        return $this->hasMany(absen::class);
    }

    protected static function booted()
    {
        static::updated(function ($transaction) {
            // Cek apakah status berubah dan jadi approved
            if ($transaction->isDirty('status') && $transaction->status === 'Confirmed') {
                // Cari member berdasarkan user_id dari transaksi
                $member = Member::where('user_id', $transaction->userId)->first();

                if ($member) {
                    // Ambil durasi dari paket membership
                    $duration = $transaction->membershipPackage->duration_months ?? 1;

                    $member->is_active = true;
                    $member->start_date = now();
                    $member->end_date = now()->addMonths($duration);
                    $member->save();
                }
            }
        });
    }
    public function rewards()
    {
        return $this->hasMany(reward::class, 'member_profile_id');
    }
}
