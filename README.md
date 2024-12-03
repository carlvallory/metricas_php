# Proyecto PHP Puro con Dotenv

Este es un proyecto básico en PHP puro que utiliza el paquete `vlucas/phpdotenv` para gestionar las variables de entorno. Está diseñado para ser minimalista y seguro, siguiendo buenas prácticas como el uso de un archivo `.env` para datos sensibles.

## Características

- Gestión de variables de entorno con [`vlucas/phpdotenv`](https://github.com/vlucas/phpdotenv).
- Estructura limpia y fácil de entender para proyectos PHP.
- Configuración segura para evitar exponer credenciales en repositorios públicos.
- Preparado para trabajar con Composer.

## Requisitos

- **PHP** >= 7.4
- **Composer** para manejar dependencias.
- Servidor web local como Apache o Nginx.
- Acceso a una base de datos (MySQL o MariaDB recomendado).

## Instalación

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/tu-usuario/tu-repositorio.git
   cd tu-repositorio
   ```

2. **Instalar dependencias** Asegúrate de que Composer esté instalado y ejecuta:
    ```bash
    composer install
    ```

3. **Configurar variables de entorno** Crea un archivo .env en la raíz del proyecto con el siguiente contenido:
    ```env
    DB_SERVER=localhost
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña
    DB_NAME=tu_base_de_datos
    ```

4. **Configurar la base de datos** Asegúrate de que tu base de datos esté configurada correctamente. Puedes usar el archivo create_tables.php para crear las tablas necesarias:

    ```bash
    Copy code
    php create_tables.php
    ```

5. **Iniciar el servidor** Si estás trabajando en un entorno local, usa el servidor integrado de PHP:

    ```bash
    Copy code
    php -S localhost:8000
    ```

6. **Probar el proyecto** Abre tu navegador y accede a http://localhost:8000.


## Estructura del Proyecto
    ```bash
    /
    ├── config.php           # Configuración y conexión a la base de datos.
    ├── create_tables.php    # Script para crear las tablas en la base de datos.
    ├── .env                 # Archivo de variables de entorno (ignorado por Git).
    ├── .gitignore           # Archivos y carpetas que Git debe ignorar.
    ├── composer.json        # Archivo de dependencias manejado por Composer.
    ├── vendor/              # Dependencias instaladas (ignorado por Git).
    └── README.md            # Documentación del proyecto.
    ```


## Cómo Contribuir
1. Haz un fork del proyecto.
    
2. Crea una rama para tus cambios:
    ```bash
    git checkout -b mi-rama
    ```

3. Realiza los cambios y haz commit:
    ```bash
    git commit -m "Descripción de los cambios"
    ```

4. Envía los cambios a tu fork:
    ```bash
    git push origin mi-rama
    ```

5. Abre un Pull Request en el repositorio original.

## Licencia
Este proyecto está bajo la Licencia MIT. Consulta el archivo LICENSE para más detalles.