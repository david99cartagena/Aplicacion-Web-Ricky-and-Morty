<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API de Gestión de Tareas con Rick and Morty",
 *     description="Esta API permite registrar usuarios, gestionar tareas personales y asociarlas con personajes de la Rick and Morty API.",
 *     @OA\Contact(
 *         email="soporte@tuempresa.com"
 *     )
 * )
 *
 * @OA\Tag(
 *     name="Autenticación",
 *     description="Registro e inicio de sesión de usuarios"
 * )
 *
 * @OA\Tag(
 *     name="Tareas",
 *     description="CRUD de tareas personales"
 * )
 *
 * @OA\Tag(
 *     name="RickAndMorty",
 *     description="Consumo de API externa de personajes Rick and Morty"
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Servidor local"
 * )
 * 
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Autenticación JWT usando el esquema Bearer. Ejemplo: 'Bearer {token}'"
 * )
 */
class OASInfo {}
