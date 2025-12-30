# p03-gestion-transfers-laravel
 Producto 3 - Desarrollo de una aplicación gestión de transfers con Laravel


🚀 Instalación y Despliegue

Para levantar el proyecto por primera vez, asegúrate de tener instalado Docker y Docker Compose, luego ejecuta el siguiente comando en tu terminal:
Bash

    docker-compose up -d --build

Este comando descargará las imágenes necesarias, construirá los contenedores y levantará los servicios en segundo plano.


🔗 Accesos al Sistema

Una vez que los contenedores estén activos, puedes acceder a las distintas herramientas a través de las siguientes URLs:

    Página Principal (Web): http://localhost:8080

    Gestor de Base de Datos (phpMyAdmin): http://localhost:8081


👥 Usuarios de Prueba (Roles)

Para probar las distintas funcionalidades según el rol de usuario, puedes utilizar las siguientes credenciales preconfiguradas:
Rol	            Email	                    Contraseña

Administrador	admin@islatransfers.test	admin1234
Hotel	        hotel@islatransfers.test	hotel1234
Viajero	        viajero@islatransfers.test	viajero1234


📊 REST WebService (Información Agregada)

El sistema incluye un servicio web que genera información estadística sobre las reservas realizadas por zonas geográficas. El resultado se entrega en formato JSON, detallando el nombre de la zona, el número de traslados y el porcentaje que representa sobre el total.

    Endpoint del JSON: http://localhost:8080/reservas/por-zona

    Nota: Para visualizar este endpoint, es necesario estar autenticado en la plataforma.


🛠️ Tecnologías utilizadas

    Laravel: Framework backend.

    Docker: Virtualización y contenedores.

    MySQL: Base de datos.

    phpMyAdmin: Administración visual de la base de datos.