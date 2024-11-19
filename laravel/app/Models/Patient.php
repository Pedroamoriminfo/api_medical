<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Patient extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'patients';

    protected $fillable = [
        'name',
        'age',
        'contact'
    ];

    public function routeNotificationForMail()
{
    return $this->contact; // Certifique-se de que o atributo 'contact' existe no modelo e contém o endereço correto.
}
}
