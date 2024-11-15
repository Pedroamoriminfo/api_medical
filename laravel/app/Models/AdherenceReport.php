<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdherenceReport extends Model
{
    use HasFactory;

    protected $table = 'adherence_reports';

    protected $fillable = [
        'generation_date',
        'status',
        'medication_id'
    ];
}
