<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;



class TareaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/tareas",
     *     tags={"Tareas"},
     *     summary="Listar tareas del usuario autenticado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tareas obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Lista de tareas obtenida exitosamente"),
     *             @OA\Property(property="tareas", type="array", @OA\Items(ref="#/components/schemas/Tarea"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al obtener las tareas"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error")
     *         )
     *     )
     * )
     */

    public function index()
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $tareas = $user->tareas()->get();

            return response()->json([
                'status' => 200,
                'msg' => 'Lista de tareas obtenida exitosamente',
                'tareas' => $tareas
            ], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al obtener las tareas',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/tareas",
     *     tags={"Tareas"},
     *     summary="Crear nueva tarea",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titulo", "fecha_vencimiento"},
     *             @OA\Property(property="titulo", type="string", example="Aprender Swagger"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción de la tarea"),
     *             @OA\Property(property="fecha_vencimiento", type="string", format="date", example="2025-08-15"),
     *             @OA\Property(property="estado", type="string", example="Pendiente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea creada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Tarea creada correctamente"),
     *             @OA\Property(property="tarea", ref="#/components/schemas/Tarea")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="Error de validación. Por favor revisa los datos ingresados."),
     *             @OA\Property(property="error", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al crear tarea"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error interno")
     *         )
     *     )
     * )
     */

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'fecha_vencimiento' => 'required|date',
                'estado' => 'nullable|in:Pendiente,En progreso,Completada',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Error de validación. Por favor revisa los datos ingresados.',
                    'error' => $validator->errors(),
                ], 400);
            }

            $data = $validator->validated();
            $data['user_id'] = Auth::id();

            $tarea = Tarea::create($data);

            return response()->json([
                'status' => 200,
                'msg' => 'Tarea creada correctamente',
                'tarea' => $tarea
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al crear tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/tareas/{id}",
     *     tags={"Tareas"},
     *     summary="Obtener una tarea específica",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Tarea encontrada"),
     *             @OA\Property(property="tarea", ref="#/components/schemas/Tarea")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="Tarea no encontrada")
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {

            /** @var \App\Models\User $user */
            $user = Auth::user();
            $tarea = $user->tareas()->findOrFail($id);

            return response()->json([
                'status' => 200,
                'msg' => 'Tarea encontrada',
                'tarea' => $tarea
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'Tarea no encontrada',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/api/tareas/{id}",
     *     tags={"Tareas"},
     *     summary="Actualizar una tarea",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titulo", type="string", example="Nuevo título"),
     *             @OA\Property(property="descripcion", type="string", example="Descripción actualizada"),
     *             @OA\Property(property="fecha_vencimiento", type="string", format="date", example="2025-08-22"),
     *             @OA\Property(property="estado", type="string", example="En progreso")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea actualizada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Tarea actualizada correctamente"),
     *             @OA\Property(property="tarea", ref="#/components/schemas/Tarea")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="Error de validación"),
     *             @OA\Property(property="error", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="Tarea no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al actualizar tarea"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error")
     *         )
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $tarea = $user->tareas()->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'titulo' => 'sometimes|required|string|max:255',
                'descripcion' => 'nullable|string',
                'fecha_vencimiento' => 'sometimes|required|date',
                'estado' => 'nullable|in:Pendiente,En progreso,Completada',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Error de validación. Por favor revisa los datos ingresados.',
                    'error' => $validator->errors(),
                ], 400);
            }

            // $data = $validator->validated();
            // $tarea->update($data);

            $tarea->update($validator->validated());

            return response()->json([
                'status' => 200,
                'msg' => 'Tarea actualizada correctamente',
                'tarea' => $tarea
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'Tarea no encontrada'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al actualizar tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/tareas/{id}",
     *     tags={"Tareas"},
     *     summary="Eliminar una tarea",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tarea eliminada correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Tarea eliminada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="Tarea no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al eliminar tarea"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $tarea = $user->tareas()->findOrFail($id);
            $tarea->delete();

            return response()->json([
                'status' => 200,
                'msg' => 'Tarea eliminada correctamente'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'Tarea no encontrada'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al eliminar tarea',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/tareas/{id}/personaje",
     *     tags={"Tareas"},
     *     summary="Asociar personaje de Rick & Morty a una tarea",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la tarea",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rick_morty_personaje_id"},
     *             @OA\Property(property="rick_morty_personaje_id", type="integer", example=45)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Personaje asociado correctamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="msg", type="string", example="Personaje asociado a la tarea correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=400),
     *             @OA\Property(property="msg", type="string", example="Error de validación"),
     *             @OA\Property(property="error", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tarea no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=404),
     *             @OA\Property(property="msg", type="string", example="Tarea no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="integer", example=500),
     *             @OA\Property(property="msg", type="string", example="Error al asociar personaje"),
     *             @OA\Property(property="error", type="string", example="Mensaje del error")
     *         )
     *     )
     * )
     */

    public function asociarPersonaje(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'rick_morty_personaje_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'msg' => 'Error de validación. Por favor revisa los datos ingresados.',
                    'error' => $validator->errors(),
                ], 400);
            }

            $tarea = Tarea::findOrFail($id);
            $tarea->rick_morty_personaje_id = $validator->validated()['rick_morty_personaje_id'];
            $tarea->save();

            return response()->json([
                'status' => 200,
                'msg' => 'Personaje asociado a la tarea correctamente',
                // 'tarea' => $tarea
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'msg' => 'Tarea no encontrada',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'msg' => 'Error al asociar personaje',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
