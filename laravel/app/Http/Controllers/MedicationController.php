<?php

namespace App\Http\Controllers;

use App\Models\Dose;
use App\Models\Medication;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $medications =  Medication::select('*')->get();
            if ($medications->count() >  0) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Medicação enconntrada.',
                        'version' => 'V1',
                    ],
                    'data' => $medications,
                ], 201);
            }

            // Caso o salvamento falhe
            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não existem Medicação cadastrada.',
                    'version' => 'V1',
                ],
            ], 500);
        } catch (\Exception $e) {

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Ocorreu um erro inesperado: ' . $e->getMessage(),
                    'version' => 'V1',
                ],
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required',
            'dosage' => 'required',
            'frequency' => 'required',
            'duration' => 'required',
            'patient_id' => 'required',
        ]);
        try {
            // Criação do novo usuário
            $medications = new Medication();
            $medications->name = $validated['name'];
            $medications->dosage = $validated['dosage'];
            $medications->frequency = $validated['frequency'];
            $medications->duration = $validated['duration'];
            $medications->patient_id = $validated['patient_id'];


            // Salvando o usuário e verificando o sucesso
            if ($medications->save()) {
                      // Criar as doses associadas
            foreach ($validated['doses'] as $doseData) {
                $dose = new Dose();
                $dose->medication_id = $medications->id;
                $dose->time = $doseData['time'];
                $dose->date = $doseData['date'];
                $dose->save();
            }
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Medicação cadastrada com sucesso',
                        'version' => 'V1',
                    ],
                    'data' => $medications,
                ], 201);
            }

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não foi possível cadastrar a Meddicação.',
                    'version' => 'V1',
                ],
            ], 500);
        } catch (\Exception $e) {

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Ocorreu um erro inesperado: ' . $e->getMessage(),
                    'version' => 'V1',
                ],
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $medications =  Medication::select('*')
                ->where('id', $id)
                ->first();



            // Salvando o usuário e verificando o sucesso
            if ($medications) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Medicaçao encontrada com sucesso',
                        'version' => 'V1',
                    ],
                    'data' => $medications,
                ], 201);
            } else {

                return response()->json([
                    'metadata' => [
                        'result' => 0,
                        'output' => ['raw' => 'erro.'],
                        'reason' => 'Não foi possível encontrar o Medicaçao.',
                        'version' => 'V1',
                    ],
                ], 500);
            }
        } catch (\Exception $e) {

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Ocorreu um erro inesperado: ' . $e->getMessage(),
                    'version' => 'V1',
                ],
            ], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required',
            'dosage' => 'required',
            'frequency' => 'required',
            'duration' => 'required',
        ]);

        try {
            // Buscando o Medicaçao no banco de dados
            $medications = Medication::find($id);
            // dd( $medications);

            if (!$medications) {
                return response()->json([
                    'metadata' => [
                        'result' => 0,
                        'output' => ['raw' => 'erro.'],
                        'reason' => 'Medicaçao não encontrada.',
                        'version' => 'V1',
                    ],
                ], 404);
            }

            $medications->name = $validated['name'];
            $medications->dosage = $validated['dosage'];
            $medications->frequency = $validated['frequency'];
            $medications->duration = $validated['duration'];
            if ($medications->save()) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Medicaçao atualizado com sucesso.',
                        'version' => 'V1',
                    ],
                    'data' => $medications,
                ], 200);
            }

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não foi possível atualizar o Medicaçao.',
                    'version' => 'V1',
                ],
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Ocorreu um erro inesperado: ' . $e->getMessage(),
                    'version' => 'V1',
                ],
            ], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Buscando o Medicaçao pelo ID
            $medications = Medication::find($id);

            if (!$medications) {
                return response()->json([
                    'metadata' => [
                        'result' => 0,
                        'output' => ['raw' => 'erro.'],
                        'reason' => 'Medicaçao não encontrada.',
                        'version' => 'V1',
                    ],
                ], 404);
            }

            if ($medications->delete()) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Medicaçao deletado com sucesso.',
                        'version' => 'V1',
                    ],
                ], 200);
            }

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não foi possível deletar o Medicaçao.',
                    'version' => 'V1',
                ],
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Ocorreu um erro inesperado: ' . $e->getMessage(),
                    'version' => 'V1',
                ],
            ], 500);
        }
    }
}
