Pasos para subirlo a un servidor:
- Cambiar config/main.php
	.- Quitar los logs en pantalla dejar s�lo los de ficheros
	.- Quitar el gii
	.- Configurar base de datos y par�metros de correo
- Cambiar config/console.php
	.- Configurar base de datos y par�metros de correo
- Configurar mail en MailSingleton


** Para subirlo a Chequerestaurante
1.- Crear un zip con los contenidos de la carpeta trunk (no incluir carpeta trunk, hacerlo dentro)
2.- Subir el zip a /home/kafhe en el servidor
3.- Ejecutar ". desplegar.sh trunk.zip" en el servidor via Putty.

Este script se encarga de hacer un backup, desplegar todo el zip y modificar los archivos de configuraci�n.

Una vez subido el c�digo, ejecutar las migraciones con yiic.