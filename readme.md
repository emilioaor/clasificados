Clasificados
=============================================

Página web de clasificados. Con 2 tipos de publicaciones (Gratis y pagas). Control de imágenes, ubicación por google maps y pagos por paypal.

Niveles de usuarios:
--------------------------------------------------------------------------------

#### Admin:

* Administrar categorías, sub-categorías y opciones
* Administrar publicaciones de todos los usuarios

#### Normal:
* Registrar publicaciones
* Pagos por paypal
* Agregar lista de deseos
* Comentarios

Instalación
------------------------------------------------------------------------------
1. Renombrar el archivo **.env.example** por **.env** en la raíz del proyecto

2. En la raíz del proyecto ejecuta el siguiente comando para instalar dependencias de backend

        composer install


3. Configurar los datos de conexion a la base de datos

4. Ejecutar el siguiente comando para generar la base de datos:

        php artisan migrate --seed

    Con esto se generan los usuarios por default del sistema. Estos usuarios estan definidos en **database/seeders/UserSeeder.php**. Sientete libre de modificar y agregar los usuarios que necesites

5. Puedes probar fácilmente la aplicación con el siguiente comando:

        php artisan serve

    Con esto te puedes conectar por la siguiente url: **http://localhost:8000**
