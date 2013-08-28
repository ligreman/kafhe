Pasos para subirlo a un servidor:
- Cambiar config/main.php
	.- Quitar los logs en pantalla dejar sólo los de ficheros
	.- Quitar el gii
	.- Configurar base de datos y parámetros de correo
- Cambiar config/console.php
	.- Configurar base de datos y parámetros de correo
- Configurar mail en MailSingleton

Una vez subido el código, ejecutar las migraciones con yiic.