<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use App\Models\Admission;
use App\Models\Room;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient.firstname'=>'required|string|max:255',
            'patient.lastname'=>'required|string|max:255',
            'patient.doctor'=>'nullable|string|max:255',
            'room_id'=>'required|exists:rooms,id',
            'admission.motif'=>'nullable|string|max:255',
            'admission.payment_mode'=>'nullable|string|max:255',
        ]);

        $patient = Patient::create([
            'firstname'=>$validated['patient']['firstname'],
            'lastname'=>$validated['patient']['lastname'],
            'doctor'=>$validated['patient']['doctor'] ?? null,
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $room->update(['status'=>'occupee']);

        Admission::create([
            'patient_id'=>$patient->id,
            'room_id'=>$room->id,
            'admitted_at'=>now(),
            'motif'=>$validated['admission']['motif'] ?? null,
            'payment_mode'=>$validated['admission']['payment_mode'] ?? null,
        ]);

        return redirect()->route('dashboard')->with('success','Patient admis !');
    }

    public function discharge(Admission $admission)
    {
        $admission->update(['discharged_at'=>now()]);
        $admission->room->update(['status'=>'libre']);
        return redirect()->route('dashboard')->with('success','Chambre libérée');
    }
}
