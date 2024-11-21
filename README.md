
#La Despensa de Don Rod

Descripción
Este proyecto implementa un sistema de supermercado en línea diseñado para ejecutarse en una instancia AWS EC2 con una base de datos RDS. El sistema utiliza Ubuntu Server como SO, MySQL como base de datos y PHP bajo la arquitectura MVC, gestionado por el servidor web NGINX.

Requisitos Previos
Infraestructura AWS:

Instancia EC2 configurada con:
Sistema operativo: Ubuntu Server.
Dependencias instaladas: PHP, MySQL y NGINX.
Acceso al puerto 80 para tráfico HTTP.
Base de datos RDS configurada con:
Motor de base de datos: MySQL.
Endpoint accesible desde EC2.

Dependencias en el proyecto:
Librería TCPDF para la generación de documentos PDF.
Bootstrap 5

1. Subir Archivos al Servidor EC2
Transferir los archivos del proyecto al directorio /var/www/html en la instancia EC2 mediante SCP o SFTP.

2. Configuración de la Base de Datos
Crear una base de datos en RDS e importar el script SQL de inicialización (si existe).
Configurar las credenciales en el archivo de conexión:

Ubicación: conexion/conexion.php.
Parámetros a utilizar:
$host = '<endpoint de RDS>';
$db_name = '<nombre de la base de datos>';
$user = '<usuario>';
$password = '<contraseña>';

3. Configuración de NGINX
Editar el archivo de configuración de NGINX (ubicado, por ejemplo, en /etc/nginx/sites-available/default) para apuntar al proyecto:

server {
    listen 80;
    server_name _;

    root /var/www/html;
    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

Estructura del Proyecto: 

admin.php: Gestión administrativa del sistema.
compra_realizada.php: Confirmación y finalización de compras.
footer.php: Pie de página común.
generar_pdf_venta.php: Generación de comprobantes de venta en PDF.
header.php: Encabezado común.
index.php: Página de inicio.
login.php: Control de acceso al supermercado.
productos.php: Visualización de productos.
registrarse.php: Registro de cuenta de cliente.
vistas: Contiene las vistas utilizadas en el sistema.
tcpdf: Librería para generación de PDFs.
img: Carpeta de imágenes estáticas.
controller: Lógica del controlador en la arquitectura MVC.
conexion: Conexión a la base de datos.
Bootstrap 5: Framework CSS para diseño.

Funcionalidades

Administrador

Administración de Empleados:
Agregar, editar, eliminar y listar empleados.
Generar reportes en PDF.

Administración de Categorías:
Agregar, editar, eliminar y listar categorías de productos.
Generar reportes en PDF.

Administración de Productos:
Agregar, editar, eliminar y listar productos.
Generar reportes en PDF.

Administración de Compras:
Consultar, listar y administrar el historial de compras realizadas.
Generar reportes en PDF.

Cliente

Carrito de Compras:
Agregar y quitar productos del carrito.

Compra de Productos:
Procesar el pago y confirmar la compra.

Comprobantes PDF:
Generar un recibo o comprobante en PDF tras la compra.
