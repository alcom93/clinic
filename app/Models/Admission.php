<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;
     protected $fillable = [
        'patient_id',
        'room_id',
        'admitted_at',
        'discharged_at',
        'motif',
        'payment_mode',
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}


