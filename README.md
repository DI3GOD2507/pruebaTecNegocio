# pruebaTecNegocio

Requisitos previos
PHP >= 8.x

Composer

SQL Server (con una base de datos creada)

Extensión pdo_sqlsrv habilitada en PHP

(Opcional) XAMPP, Laragon, o cualquier stack local

Instalar las dependencias de composer

modificar las variables de entorno par ala coneccion a la base
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=ClientesPedidosDB //ejemplo del script de la base
DB_USERNAME=laravel_user
DB_PASSWORD=Laravel123!

generar la clave
php artisan key:generate

Levantar el proyeto con artisan
php artisan serve


SE DEBE EJECUTAR EL SCRIPT SQL ADJUNTO PARA GENERAR LA BASE CON LAS TABLAS CORRESPONDIENTES y si se desea usar las credenciales de ejemplo