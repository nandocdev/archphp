
<div align="center">

  <h1>ArchPHP - Framework MVC en PHP</h1>
  
  <p>
    ArchPHP es un framework MVC (Modelo-Vista-Controlador) desarrollado en PHP que proporciona una estructura sólida y organizada para construir aplicaciones web robustas y escalables.
  </p>
  
  

   
<h4>
    <a href="https://arch-php.000webhostapp.com/" target="_blank">View Demo</a>
  </h4>
</div>

<br />

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
   El archivo `database.php` en la carpeta `srv/dev/config` contiene la configuración de la base de datos para el entorno de desarrollo de la aplicación. Este archivo es crucial para establecer la conexión con la base de datos y definir sus parámetros clave.

- **Driver**: Especifica el controlador de la base de datos que se utilizará.
- **Host**: Indica la dirección IP del servidor de la base de datos.
- **Usuario y contraseña**: Son las credenciales necesarias para acceder a la base de datos.
- **Nombre de la base de datos**: Es el nombre de la base de datos a la que se conectará la aplicación.
- **Conjunto de caracteres**: Define el juego de caracteres que se utilizará para la comunicación con la base de datos.
- **Puerto**: Especifica el puerto al que se conectará la aplicación para acceder a la base de datos.
- **Prefijo de tabla**: Si se utiliza algún prefijo para las tablas de la base de datos, se puede definir aquí.
- **Opciones de PDO**: Establece diferentes opciones para la conexión PDO, como el modo de error, la emulación de preparaciones y el modo de recuperación de datos.

Esta configuración es esencial para garantizar que la aplicación pueda conectarse correctamente a la base de datos y realizar operaciones de manera segura y eficiente durante el desarrollo.


1. ¡Listo! Puedes empezar a desarrollar tu aplicación utilizando la estructura proporcionada por ArchPHP.

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
