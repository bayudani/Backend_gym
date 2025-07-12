<?php

namespace App\Http\Controllers;

use App\Models\training_schedule;
use Illuminate\Http\Request;

class TrainingScheduleController extends Controller
{
    
    protected $fillable = [
        'userID',
        'title',
        'day',
        'is_active',
    ];

    public function index()
    {
        // Logic to retrieve and display training schedules
    }

    // public function User(){
    //     // relationship with User model
    //     return $this->belongsTo(User::class, 'userID');
    // }

    public function store(Request $request)
    {
        // Logic to store a new training schedule
        $data = $request->validate([
            'userID' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'day' => 'required|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'is_active' => 'boolean',
        ]);

        // Create the training schedule
        $schedule = training_schedule::create($data);

        return response()->json($schedule, 201);
    }  


}
