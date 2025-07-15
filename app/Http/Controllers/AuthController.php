<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /* public function index()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $tareas = Tarea::with('usuario')->get(); // todas las tareas
        } else {
            $tareas = Tarea::where('user_id', $user->id)->get(); // solo sus tareas
        }

        return view('tareas.index', compact('tareas'));
    } */

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Autenticación"},
     *     summary="Registrar nuevo usuario",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="David Cartagena"),
     *             @OA\Property(property="email", type="string", format="email", example="david@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Se guardó correctamente el Usuario"),
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error interno al guardar Usuario"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:6'
            ]);

            $user = User::create([
                'name' => $request->name,
                'role' => 'user',
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);

            return response()->json([
                'status' => 200,
                'msg' => 'Se guardó correctamente el Usuario',
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error interno al guardar Usuario',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Autenticación"},
     *     summary="Iniciar sesión y obtener token JWT",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="david@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="12345678")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Token generado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Token generado existosamente"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJK...etc"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="David"),
     *                 @OA\Property(property="email", type="string", example="david@example.com"),
     *                 @OA\Property(property="role", type="string", example="user")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciales inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="Valida de nuevo correo o contrasena"),
     *             @OA\Property(property="error", type="string", example="Credenciales Invalidas")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al generar token",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="No se pudo crear el token"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'status' => 401,
                    'msg' => 'Valida de nuevo correo o contrasena',
                    'error' => 'Credenciales Invalidas'
                ], 401);
            }

            $user = Auth::user();

            return response()->json([
                'status' => 200,
                'msg' => 'Token generado existosamente',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ]
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'No se pudo crear el token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     tags={"Autenticación"},
     *     summary="Obtener datos del usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuario autenticado obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Usuario obtenido exitosamente"),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="David"),
     *                 @OA\Property(property="email", type="string", example="david@example.com"),
     *                 @OA\Property(property="role", type="string", example="admin")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=401),
     *             @OA\Property(property="msg", type="string", example="No autorizado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al obtener usuario"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function me()
    {
        try {
            $user = Auth::user();

            return response()->json([
                'status' => 200,
                'msg' => 'Usuario obtenido exitosamente',
                'user' => $user
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al obtener usuario',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Autenticación"},
     *     summary="Cerrar sesión del usuario",
     *     description="Cierra la sesión del usuario autenticado y redirige al login.",
     *     @OA\Response(
     *         response=302,
     *         description="Redirección al login tras cerrar sesión exitosamente",
     *         @OA\Header(
     *             header="Location",
     *             description="URL de redirección",
     *             @OA\Schema(type="string", example="/login")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al cerrar sesión",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al cerrar sesión"),
     *             @OA\Property(property="error", type="string", example="Mensaje de excepción")
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login');
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al cerrar sesión',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
