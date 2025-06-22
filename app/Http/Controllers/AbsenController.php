<?php

namespace App\Http\Controllers;

use App\Models\absen;
use App\Models\member;
use App\Models\reward;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_profile_id' => 'required|exists:member_profiles,id',
        ]);

        // Simpan absen
        $absen = new absen();
        $absen->member_profile_id = $validated['member_profile_id'];
        $absen->scan_time = Carbon::now();
        $absen->save();

        // Tambah poin ke member
        $member = member::find($validated['member_profile_id']);
        $member->point += 10;

        // Cek apakah point sudah mencapai 1000 atau lebih


        $member->save();

        return redirect()->route('filament.admin.resources.absens.index')
            ->with('success', 'Absen berhasil & point diperbarui!');
    }
}
