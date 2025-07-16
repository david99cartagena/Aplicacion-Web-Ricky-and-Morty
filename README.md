# 📘 Proyecto API Laravel 8 + JWT + SQL Server

Este proyecto es una API RESTful construida con **Laravel 8.83**, **PHP 8.1**, y autenticación mediante **JWT (tymon/jwt-auth)**. Utiliza **SQL Server 2019** como base de datos. El frontend se ha realizado con **HTML**, **JavaScript** y **Bootstrap**.

---

## 🚀 Tecnologías utilizadas

### Backend

-   **PHP** 8.1
-   **Laravel** 8.83
-   **JWT Auth** (`tymon/jwt-auth`)
-   **SQL Server 2019**
-   **L5-Swagger** para documentación de API

### Frontend

-   **HTML5**
-   **Bootstrap**
-   **JavaScript**

---

## 🛠️ Instrucciones de instalación

### 1. Clona el repositorio

```bash
git clone https://github.com/usuario/mi-proyecto-api.git
cd mi-proyecto-api
```

### 2. Copia el archivo de entorno

```bash
cp .env.example .env
```

### 3. Instala las dependencias

```bash
composer install
```

### 4. Genera la clave de aplicación

```bash
php artisan key:generate
```

### 5. Configura tu conexión a SQL Server

Edita el archivo `.env` con los siguientes valores:

```
DB_CONNECTION=sqlsrv
DB_HOST=DESKTOP-Prueba
DB_PORT=1433
DB_DATABASE=laravel_db1
DB_USERNAME=sa
DB_PASSWORD=tu_password
```

### 6. Ejecuta las migraciones

```bash
php artisan migrate
```

### 7. Sirve la aplicación

```bash
php artisan serve
```

Accede en: [http://localhost:8000](http://localhost:8000) o [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔐 Usuario demo para login

Puedes usar este usuario para probar la autenticación:

```
Email: david@example.com
Password: 123456
```

---

## 📄 Documentación Swagger

Una vez corras el servidor, accede a la documentación Swagger aquí:

> [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

---

## 📂 Estructura de carpetas destacada

-   `/app/Http/Controllers/AuthController.php` - Controlador de autenticación
-   `/routes/api.php` - Rutas de la API protegidas por JWT
-   `/resources/views/` - Vistas HTML para login y registro
-   `/public/js/` - Scripts de login y registro (frontend)

---

## 📦 Dependencias principales

-   `laravel/framework: ^8.83`
-   `tymon/jwt-auth: ^1.0@dev`
-   `darkaonline/l5-swagger: ^8.6`

---

## 🧪 Frontend disponible

Puedes usar los archivos:

-   `index.html` → formulario de login
-   `register.html` → formulario de registro

Estos se comunican con los endpoints `/api/login` y `/api/register` usando fetch API y muestran alertas con los resultados.

---

## 🗒️ Notas adicionales

-   Usa `php artisan view:clear` y `php artisan optimize` si hay errores de caché de vista.
-   Asegúrate de que tu SQL Server permite autenticación SQL (modo mixto).
-   Ejecuta `php artisan jwt:secret` si usas JWT por primera vez.

---

## 📷 Imagenes de la Aplicacion

**Usuario**

-   Inicio y registro de usuario

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_1.png)

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_2.png)

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_3.png)

-   Opciones de role Administardor

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_4.png)

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_5.png)

**Modulo de Tareas**

-   Crear y editar tareas

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_6.png)

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_7.png)

-   Uso de Api Rick and Morty

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_8.png)

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_9.png)

**Rol de User**

-   Opciones de rol Usuario

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_10.png)

    ![](https://raw.githubusercontent.com/david99cartagena/Aplicacion-Web-Ricky-and-Morty/refs/heads/master/media/Screenshot_11.png)
