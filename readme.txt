Pasos para subirlo a un servidor:
- Cambiar config/main.php
	.- Quitar los logs en pantalla dejar s�lo los de ficheros
	.- Quitar el gii
	.- Configurar base de datos y par�metros de correo
- Cambiar config/console.php
	.- Configurar base de datos y par�metros de correo
- Configurar mail en MailSingleton

Una vez subido el c�digo, ejecutar las migraciones con yiic.