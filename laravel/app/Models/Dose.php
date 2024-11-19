<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dose extends Model
{
    use HasFactory;


    protected $table = 'doses';
    protected $fillable = [
        'timestamp',
        'status',
        'justification',
        'medication_id',
    ];

       // Relacionamento com Medication
       public function medication()
       {
           return $this->belongsTo(Medication::class);
       }
}
