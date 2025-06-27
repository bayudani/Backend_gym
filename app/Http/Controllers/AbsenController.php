<?php

namespace App\Http\Controllers;

use App\Models\absen;
use App\Models\member;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_profile_id' => 'required|exists:member_profiles,id',
        ]);

        // Ambil data member
        $member = member::find($validated['member_profile_id']);

        // Cek apakah member aktif
        if (!$member->is_active) {
            return redirect()->back()->with('error', 'Member tidak aktif. Silakan hubungi admin.');
        }

        // Cek apakah tanggal hari ini berada dalam masa aktif member
        $today = Carbon::today();
        if ($member->start_date && $member->end_date) {
            if ($today->lt(Carbon::parse($member->start_date)) || $today->gt(Carbon::parse($member->end_date))) {
                return redirect()->back()->with('error', 'Masa aktif member telah berakhir atau belum dimulai.');
            }
        }

        // Simpan absen
        $absen = new absen();
        $absen->member_profile_id = $member->id;
        $absen->scan_time = now();
        $absen->save();

        // Tambah poin ke member
        $member->point += 10;
        $member->save();

        return redirect()->route('filament.admin.resources.absens.index')
            ->with('success', 'Absen berhasil & poin berhasil ditambahkan!');
    }
}
