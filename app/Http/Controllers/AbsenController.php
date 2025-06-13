<?php

namespace App\Http\Controllers;

use App\Models\absen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        $memberId = $request->input('member_profile_id');

        // Lakukan pengecekan dan simpan absen
        Absen::create([
            'member_profile_id' => $memberId,
            'scan_time' => now(),
        ]);

        return redirect()->route('filament.admin.resources.absens.index')
            ->with('success', 'Absen berhasil dilakukan!');
    }

    // delete absen user

}
