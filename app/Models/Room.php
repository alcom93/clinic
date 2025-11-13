<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['number', 'status'];

    public function activeAdmission()
    {
        return $this->hasOne(Admission::class)->whereNull('discharged_at')->latest();
    }
}

