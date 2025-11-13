<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::with(['activeAdmission.patient'])->get();
        return view('rooms.dashboard', compact('rooms'));
    }

    public function updateStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => 'required|in:libre,en_preparation,en_nettoyage'
        ]);

        $room->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut mis à jour !');
    }
}
