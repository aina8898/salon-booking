<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable=['service_name','duration_minutes','price'];
    public function appointments(){
    return $this->belongsToMany(Appointment::class)->withPivot('quantity');
    }
}
