# ArchPHP - Framework MVC en PHP

ArchPHP es un framework MVC (Modelo-Vista-Controlador) desarrollado en PHP que proporciona una estructura sólida y organizada para construir aplicaciones web robustas y escalables.

## Características principales

- **Organización MVC**: Sigue el patrón Modelo-Vista-Controlador para separar la lógica de negocio, la presentación y el control de la aplicación.
- **Configuración flexible**: Define constantes y variables de configuración para personalizar fácilmente la aplicación según tus necesidades.
- **Seguridad integrada**: Incluye configuraciones de seguridad, como claves de hash y algoritmos, para garantizar la protección de los datos sensibles.
- **Gestión de sesiones**: Configura la gestión de sesiones para controlar la autenticación y la persistencia de datos del usuario.
- **Manejo de errores**: Controla la visualización de errores según el entorno de desarrollo o producción para mejorar la seguridad.
- **Soporte de correo electrónico**: Permite configurar el envío de correos electrónicos mediante SMTP con opciones de encriptación y autenticación.
- **Archivos temporales y almacenamiento**: Define rutas para el almacenamiento de archivos temporales, logs y archivos de sesión.
- **Base de datos**: Incluye soporte para la conexión y ejecución de consultas a bases de datos MySQL, PostgreSQL, SQLite y otros mediante PDO.
- **Fácil extensibilidad**: Ofrece una arquitectura modular que permite agregar nuevas funcionalidades de manera sencilla.

## Requisitos del sistema

- Servidor web (Apache, Nginx, etc.) con soporte para PHP
- PHP 7.0 o superior
- Extensión PDO habilitada
- Extensiones PHP necesarias para la base de datos específica que se esté utilizando (por ejemplo, php-mysql para MySQL)

## Instalación

1. Clona el repositorio de ArchPHP en tu sistema local:

```bash
git clone https://github.com/nandocdev/archphp.git
```

2. Configura tu servidor web para que apunte al directorio `public` dentro del directorio de ArchPHP.

3. Copia el archivo `config/database.example.php` y renómbralo como `database.php`. Configura los parámetros de conexión a tu base de datos en este archivo.

4. ¡Listo! Puedes empezar a desarrollar tu aplicación utilizando la estructura proporcionada por ArchPHP.

## Estructura de carpetas
- **Modules/**: Contiene los diferentes módulos de la aplicación, cada uno con su propia estructura de controladores, modelos, vistas y rutas.
```
Modules/
├── BlogManagement/
│   ├── Controllers/
│   │   ├── PostController.php
│   ├── Models/
│   │   ├── Post.php
│   │   ├── Category.php
│   │   ├── Tag.php
│   ├── Views/
│   │   ├── index.view.phtml
│   │   ├── create.view.phtml
│   │   ├── edit.view.phtml
```

## Contribuciones

Las contribuciones son bienvenidas. Si encuentras un error o tienes una idea para mejorar ArchPHP, no dudes en crear un problema o enviar un pull request en GitHub.

## Licencia

Este proyecto está bajo la [Licencia MIT](LICENSE).

---
