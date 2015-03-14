# Despliegue de Kafhe 3.0 en producción #

El despliegue o pase a producción se realiza mediante un script que se encuentra en el servidor.

Éste script se encarga de hacer una copia de seguridad de los datos actuales en el servidor, desplegar los datos nuevos que le subimos en un archivo zip, modificar todos los valores de configuración necesarios para que funcione la aplicación y hacer un backup de la base de datos también.

## Preparación de los archivos ##
Lo primero es hacer un zip con toda la carpeta trunk del entorno de desarrollo. Para ello:

  1. Borrar el contenido "basura" de las carpetas **assets** (vaciarla) y **logs** (borrar los archivos de log y los correos almacenados .eml).
  1. Empaquetar en un zip toda la carpeta trunk. Pinchamos sobre **trunk** y le damos a comprimir en un zip. La estructura de este zip puede crearse así conteniendo la carpeta trunk, o bien sin ella con todo el contenido directamente. Funciona de ambas maneras.
  1. Por último nos conectamos al FTP y subimos el zip en el directorio en donde se encuentra el script desplegar.sh.

## Despliegue de la nueva versión ##
Para desplegar la nueva versión ejecutaremos el script. Para ello, hay que acceder al servidor con el Putty. Vamos al directorio donde hemos dejado el zip y donde está el script y ejecutamos:
> `sh desplegar.sh <archivo.zip>`

Esto comenzará el proceso y nos irá informando del progreso.

## Carga/Actualización de la base de datos ##

Por último, hay que comprobar si hay actualizaciones en la base de datos. Para ello desde el Putty:
  1. Desde el directorio del script desplegar.sh vamos al directorio **protected** de Yii.
  1. Ejecutamos `php yiic migrate` para ver si existen migraciones que actualicen la base de datos.
  1. Si existen, le damos a _yes_ para cargarlas.