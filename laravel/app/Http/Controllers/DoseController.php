<?php

namespace App\Http\Controllers;

use App\Models\Dose;
use Illuminate\Http\Request;

class DoseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($medicationId)
    {
        try {
            $doses = Dose::select('*')->where('medication_id', $medicationId)->get();

            if ($doses->count() >  0) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Doses enconntradas.',
                        'version' => 'V1',
                    ],
                    'data' => $doses,
                ], 201);
            }

            // Caso o salvamento falhe
            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não existem doses cadastradas.',
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        // Validação dos dados de entrada
        $status = $request->input('status');
        $justification = $request->input('justification');



        try {
            // Buscando o paciente no banco de dados
            $dose = Dose::find($id);

            if (!$dose) {
                return response()->json([
                    'metadata' => [
                        'result' => 0,
                        'output' => ['raw' => 'erro.'],
                        'reason' => 'Dose não Alterada.',
                        'version' => 'V1',
                    ],
                ], 404);
            }


            $dose->status = $status;
            $dose->justification = $justification;

            if ($dose->save()) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Dose confirmada com sucesso.',
                        'version' => 'V1',
                    ],
                    'data' => $dose,
                ], 200);
            }

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não foi possível atualizar o paciente.',
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
