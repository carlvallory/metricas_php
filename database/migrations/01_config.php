<?php

// Incluir configuración de la base de datos
require_once '../../config.php';

// Crear conexión
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Verificar conexión
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}

// Crear tablas
$sql = [
    // Tabla: analytics_talentos_sessions
    "CREATE TABLE IF NOT EXISTS analytics_talentos_sessions (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        code VARCHAR(255) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES analytics_redes_users(id) ON DELETE CASCADE
    )",

    // Tabla: analytics_redes_users
    "CREATE TABLE IF NOT EXISTS analytics_redes_users (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    // Tabla: analytics_talentos_programs
    "CREATE TABLE IF NOT EXISTS analytics_talentos_programs (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        type VARCHAR(100) NOT NULL,
        hashtag VARCHAR(100),
        color VARCHAR(50),
        enabled BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    // Tabla: analytics_talentos_programs_changelog
    "CREATE TABLE IF NOT EXISTS analytics_talentos_programs_changelog (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id BIGINT UNSIGNED NOT NULL,
        program_id BIGINT UNSIGNED NOT NULL,
        old_hashtag VARCHAR(100),
        new_hashtag VARCHAR(100),
        old_enabled BOOLEAN NOT NULL,
        new_enabled BOOLEAN NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES analytics_redes_users(id) ON DELETE CASCADE,
        FOREIGN KEY (program_id) REFERENCES analytics_talentos_programs(id) ON DELETE CASCADE
    )",

    // Tabla: excel
    "CREATE TABLE IF NOT EXISTS excel (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        impressions INT DEFAULT 0,
        likes INT DEFAULT 0,
        comments INT DEFAULT 0,
        interactions INT DEFAULT 0,
        reach INT DEFAULT 0,
        saved INT DEFAULT 0,
        video_views INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )"
];

// Ejecutar queries
foreach ($sql as $query) {
    if ($mysqli->query($query) === TRUE) {
        echo "Tabla creada exitosamente.\n";
    } else {
        echo "Error al crear tabla: " . $mysqli->error . "\n";
    }
}

// Cerrar conexión
$mysqli->close();
