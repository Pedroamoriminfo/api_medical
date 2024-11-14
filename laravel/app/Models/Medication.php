<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;
    protected $table = 'medication';
    protected $fillable = [
        'name',
        'dosage',
        'frequency', // in hours, e.g. 8
        'duration', // in days, e.g. 7
        'patient_id',
    ];
}
