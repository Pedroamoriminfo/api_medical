<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoseController;
use App\Http\Controllers\MedicationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::any('/', function () {
    $array['status'] = false;
    $array['error'] = '404';
    return $array;
});

Route::get('/check', function () {
    return response()->json(['serverResponse' => true], 200);
});

// /* AUTH */
Route::get('/401', [AuthController::class, 'unauthorized'])->name('login');
Route::prefix('/v1')->group(function () {

    // Autenticação
    Route::post('login', [AuthController::class, 'login']);
    Route::post('create', [AuthController::class, 'create']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('user', [AuthController::class, 'me'])->middleware('auth:api');

    // Pacientes
    Route::middleware('auth:api')->prefix('/patients')->group(function () {
        Route::get('/', [PatientController::class, 'index']);         // Listar pacientes
        Route::post('/', [PatientController::class, 'store']);       // Criar paciente
        Route::get('/{id}', [PatientController::class, 'show']);     // Exibir paciente
        Route::put('/{id}', [PatientController::class, 'update']);   // Atualizar paciente
        Route::delete('/{id}', [PatientController::class, 'destroy']); // Deletar paciente
    });

    // Medicações
    Route::middleware('auth:api')->prefix('/medications')->group(function () {
        Route::get('/', [MedicationController::class, 'index']);         // Listar medicações
        Route::post('/', [MedicationController::class, 'store']);       // Criar medicação
        Route::get('/{id}', [MedicationController::class, 'show']);     // Exibir medicação
        Route::put('/{id}', [MedicationController::class, 'update']);   // Atualizar medicação
        Route::delete('/{id}', [MedicationController::class, 'destroy']); // Deletar medicação
    });

    // Doses
    Route::middleware('auth:api')->prefix('/doses')->group(function () {
        Route::get('/{medicationId}', [DoseController::class, 'index']);     // Listar doses de uma medicação
        Route::put('/{id}', [DoseController::class, 'updateStatus']);        // Atualizar status de uma dose (Tomada, Perdida)
    });

    // Relatórios de Aderência
    Route::middleware('auth:api')->prefix('/reports')->group(function () {
        Route::get('/{medicationId}', [ReportController::class, 'generate']); // Gerar relatório de aderência
    });
});

    /* NOT FOUND */
    Route::fallback(function () {
        $array['status'] = false;
        $array['error'] = 'endpoint inválido';
        return $array;
    });
