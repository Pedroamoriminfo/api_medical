<?php



namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['create', 'login', 'check', 'validateToken', 'unauthorized']]);
    }
    public function login(Request $request)
    {
        // User::create([
        //     'name' => 'Usuário Teste',
        //     'email' => 'usuario@exemplo.com',
        //     'password' => bcrypt('senha123'),
        // ]);
        $credentials = $request->only(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user(),
        ]);;
    }

    // Método para retornar os dados do usuário autenticado
    public function me()
    {
        return response()->json(Auth::user());
    }

    // Método para logout
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    // Método auxiliar para resposta com o token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    public function unauthorized()
    {

        $array['metadata'] = [
            'result' => 0,
            'output' => [
                'raw' => ''
            ],
            'reason' => 'Não Autorizado',
            'version' => 'v1'
        ];

        return response()->json($array, 401);
    }

    public function create(Request $request)
    {
        // Validação dos dados de entrada
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            // Criação do novo usuário
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = bcrypt($validated['password']);

            // Salvando o usuário e verificando o sucesso
            if ($user->save()) {
                return response()->json([
                    'metadata' => [
                        'result' => 1,
                        'output' => ['raw' => 'sucesso.'],
                        'reason' => 'Usuário cadastrado com sucesso',
                        'version' => 'V1',
                    ],
                    'data' => $user,
                ], 201);
            }

            return response()->json([
                'metadata' => [
                    'result' => 0,
                    'output' => ['raw' => 'erro.'],
                    'reason' => 'Não foi possível cadastrar o usuário.',
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
