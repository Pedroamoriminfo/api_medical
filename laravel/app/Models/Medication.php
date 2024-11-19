<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;
    protected $table = 'medications';
    protected $fillable = [
        'name',
        'dosage',
        'frequency', // in hours, e.g. 8
        'duration', // in days, e.g. 7
        'patient_id',
    ];

    public function doses()
    {
        return $this->hasMany(Dose::class);
    }

    public function patient()
{
    return $this->belongsTo(Patient::class);
}
}
