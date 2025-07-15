-- Elimina tablas si existen (orden correcto por claves foráneas)
IF OBJECT_ID('dbo.tareas', 'U') IS NOT NULL DROP TABLE dbo.tareas;
IF OBJECT_ID('dbo.failed_jobs', 'U') IS NOT NULL DROP TABLE dbo.failed_jobs;
IF OBJECT_ID('dbo.password_resets', 'U') IS NOT NULL DROP TABLE dbo.password_resets;
IF OBJECT_ID('dbo.users', 'U') IS NOT NULL DROP TABLE dbo.users;

-- Tabla: users
CREATE TABLE users (
    id INT IDENTITY(1,1) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    role VARCHAR(30) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    email_verified_at DATETIME NULL,
    password VARCHAR(255) NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE()
);

-- Tabla: password_resets
CREATE TABLE password_resets (
    email VARCHAR(100) NOT NULL,
    token VARCHAR(255) NOT NULL,
    created_at DATETIME NULL
);

-- Tabla: failed_jobs
CREATE TABLE failed_jobs (
    id INT IDENTITY(1,1) PRIMARY KEY,
    uuid UNIQUEIDENTIFIER NOT NULL,
    connection TEXT NOT NULL,
    queue TEXT NOT NULL,
    payload TEXT NOT NULL,
    exception TEXT NOT NULL,
    failed_at DATETIME DEFAULT GETDATE()
);

-- Tabla: tareas
CREATE TABLE tareas (
    id INT IDENTITY(1,1) PRIMARY KEY,
    user_id INT NOT NULL,
    titulo VARCHAR(150) NOT NULL,
    descripcion TEXT,
    fecha_vencimiento DATE NOT NULL,
    estado VARCHAR(20) CHECK (estado IN ('Pendiente', 'En progreso', 'Completada')) DEFAULT 'Pendiente',
    rick_morty_personaje_id INT NULL,
    created_at DATETIME DEFAULT GETDATE(),
    updated_at DATETIME DEFAULT GETDATE(),
    CONSTRAINT FK_tareas_users FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Inserción de usuarios (6 registros)
INSERT INTO users (name, role, email, email_verified_at, password, remember_token, created_at, updated_at)
VALUES
(N'David', N'admin', N'david@example.com', NULL, N'$2y$10$NDat7zhoI512rZBa6md8cuW.KbxTVMR7KYwboY02JIsTjxAVXaNAa', NULL, GETDATE(), GETDATE()),
(N'David1', N'user', N'david1@example.com', NULL, N'$2y$10$ncVo5.glVPVS5df7412WcO.Ke4dGJDeKjLcmwFw9drAEQ7aqu4aFG', NULL, GETDATE(), GETDATE()),
(N'David Cartagena', N'user', N'david2@example.com', NULL, N'$2y$10$vlDsffkN7OpwBb/O5Fa4teD/krS99sTbEUms8CGmsmHpQYB9U/Ysy', NULL, GETDATE(), GETDATE()),
(N'demo', N'user', N'demo@example.com', NULL, N'$2y$10$gYIs7rAP068kXPaFeTXGLO5zvBJ5kC/A7K9NhFX.OIDPwoXt173/.', NULL, GETDATE(), GETDATE()),
(N'david', N'user', N'juan@example.com', NULL, N'$2y$10$ieXHVGOAPh6G6cj1chAumOGGFYBBmOfpYvYT7bWNDmwZbMCQU5Jne', NULL, GETDATE(), GETDATE()),
(N'pepe', N'user', N'pepe@example', NULL, N'$2y$10$me4hP/u83n2YIJJVPuoqR..jRsR7mw2XIcc0HmWY5vJi3S5WdY4L.', NULL, GETDATE(), GETDATE());

-- Inserción de tareas (10 tareas variadas)
INSERT INTO tareas (user_id, titulo, descripcion, fecha_vencimiento, estado, rick_morty_personaje_id, created_at, updated_at) VALUES
(1, N'Estudiar Laravel', N'Revisar relaciones y JWT', '2025-07-15', N'Pendiente', 22, GETDATE(), GETDATE()),
(1, N'Demo', N'Pruebas iniciales', '2025-07-09', N'Pendiente', 464, GETDATE(), GETDATE()),
(2, N'Diseñar frontend', N'Usar Bootstrap y JS para login', '2025-07-20', N'En progreso', 101, GETDATE(), GETDATE()),
(2, N'Documentar API', N'Revisar Swagger y corregir errores', '2025-07-21', N'Pendiente', NULL, GETDATE(), GETDATE()),
(3, N'Integrar SQL Server', N'Configurar conexión desde Laravel', '2025-07-25', N'Completada', 310, GETDATE(), GETDATE()),
(3, N'Crear usuarios demo', N'Agregar usuarios para pruebas', '2025-07-10', N'Completada', NULL, GETDATE(), GETDATE()),
(4, N'Validar formulario', N'Agregar mostrar contraseña', '2025-07-18', N'En progreso', 98, GETDATE(), GETDATE()),
(4, N'Mejorar README', N'Agregar instrucciones claras', '2025-07-22', N'Pendiente', NULL, GETDATE(), GETDATE()),
(5, N'Token JWT', N'Corregir error 401 en Swagger', '2025-07-13', N'Pendiente', 73, GETDATE(), GETDATE()),
(6, N'Refactorizar código', N'Simplificar rutas api.php', '2025-07-28', N'Pendiente', NULL, GETDATE(), GETDATE());
