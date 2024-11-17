<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $patients =  Patient::select('*')->get();
            if ($patients->count() >  0) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Pacientes enconntrados.',
                        'version' => 'V1',
                    ],
                    'data' => $patients,
                ], 201);
            }

            // Caso o salvamento falhe
            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não existem pacentes cadastrados.',
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
            'age' =>  'required',
            'contact' => 'required',
        ]);
        try {
            // Criação do novo usuário
            $patients = new Patient();
            $patients->name = $validated['name'];
            $patients->age = $validated['age'];
            $patients->contact = $validated['contact'];


            // Salvando o usuário e verificando o sucesso
            if ($patients->save()) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Paciente cadastrado com sucesso',
                        'version' => 'V1',
                    ],
                    'data' => $patients,
                ], 201);
            }

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não foi possível cadastrar o Paciente.',
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

            $patients =  Patient::select('*')
                ->where('id', $id)
                ->first();



            // Salvando o usuário e verificando o sucesso
            if ($patients) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Paciente encontrado com sucesso',
                        'version' => 'V1',
                    ],
                    'data' => $patients,
                ], 201);
            } else {

                return response()->json([
                    'metadata' => [
                        'result' => 0,
                        'output' => ['raw' => 'erro.'],
                        'reason' => 'Não foi possível encontrar o Paciente.',
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
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'contact' => 'required|string|max:255',
        ]);

        try {
            // Buscando o paciente no banco de dados
            $patient = Patient::find($id);

            if (!$patient) {
                return response()->json([
                    'metadata' => [
                        'result' => 0,
                        'output' => ['raw' => 'erro.'],
                        'reason' => 'Paciente não encontrado.',
                        'version' => 'V1',
                    ],
                ], 404);
            }


            $patient->name = $validated['name'];
            $patient->age = $validated['age'];
            $patient->contact = $validated['contact'];

            if ($patient->save()) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Paciente atualizado com sucesso.',
                        'version' => 'V1',
                    ],
                    'data' => $patient,
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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Buscando o paciente pelo ID
            $patient = Patient::find($id);

            if (!$patient) {
                return response()->json([
                    'metadata' => [
                        'result' => 0,
                        'output' => ['raw' => 'erro.'],
                        'reason' => 'Paciente não encontrado.',
                        'version' => 'V1',
                    ],
                ], 404);
            }

            if ($patient->delete()) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Paciente deletado com sucesso.',
                        'version' => 'V1',
                    ],
                ], 200);
            }

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não foi possível deletar o paciente.',
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
