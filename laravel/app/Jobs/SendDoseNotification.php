<?php

namespace App\Jobs;

use App\Models\Dose;
use App\Notifications\DoseNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDoseNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $dose;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Dose $dose)
    {
        $this->dose = $dose;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $patient = $this->dose->medication->patient; // Busca o paciente relacionado

        if ($patient && $patient->email) {
            $message = "Olá, {$patient->name}. Está na hora de tomar sua medicação: {$this->dose->medication->name}.
            Dose: {$this->dose->medication->dosage}.";

            // Enviar notificação
            $patient->notify(new DoseNotification($message));

            // Atualiza o status da dose para "notificado"
            $this->dose->status = 'notificado';
            $this->dose->save();
        }
    }
}
