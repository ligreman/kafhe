#!/bin/bash

FECHA=`date +%d%m%Y`
DIR=kafhe/trunk

if [ -f "$1" ]
  then
    echo "Creando backup..."
    tar -zcf backups/backup-${FECHA}.tar.gz kafhe/*

    echo "Creando backup de base de datos..."
    sh mysql_backup.sh

    echo "Borrando contenidos actuales..."
    rm -rf kafhe/*

    echo "Desplegando $1..."
    unzip -o "$1" -d kafhe/

    #compruebo si el zip tenia una carpeta trunk o no
    if [ -d $DIR ]
      then
        echo "Moviendo contenido de la carpeta trunk a raíz"
	mv kafhe/trunk/* kafhe/
	rm -r kafhe/trunk
    fi

    echo "Modificando archivos de configuración..."
      #Gii
        sed -i 's/\/\/#iniGii/\/*\/\/#iniGii/g' kafhe/protected/config/*.php
        sed -i 's/\/\/#finGii/\/\/#finGii*\//g' kafhe/protected/config/*.php

      #MySQL
        sed -i 's/mysql:host=localhost;dbname=kafhe_refactor/mysql:host=localhost;dbname=kafhe/g' kafhe/protected/config/*.php
        #sed -i "s/'kafhe',\/\/#mysqlUsername/'kafhe',\/\/#mysqlUsername/g" kafhe/protected/config/*.php
        sed -i "s/'',\/\/#mysqlPassword/'F1tsjA5V',\/\/#mysqlPassword/g" kafhe/protected/config/*.php

      #Log
        sed -i 's/\/\/#iniLog/\/*\/\/#iniLog/g' kafhe/protected/config/*.php
        sed -i 's/\/\/#finLog/\/\/#finLog*\//g' kafhe/protected/config/*.php

      #Email
        sed -i 's/true,\/\/#testMode/false,\/\/#testMode/g' kafhe/protected/config/mail.php
        sed -i 's/@gmail.com/@kafhe.chequerestaurante.com/g' kafhe/protected/config/*.php
        #sed -i "s/'',\/\/#mysqlUsername/'kafhe',\/\/#mysqlUsername/g" kafhe/protected/config/*.php
        #sed -i "s/'',\/\/#mysqlPassword/'F1tsjA5V',\/\/#mysqlPassword/g" kafhe/protected/config/*.php


      #Es producción o test
	read -p "Estas desplegando una version de prueba? [y o n] " 
        if [[ $REPLY =~ ^[Yy]$ ]]
          then
	    echo "Configurando correos de cuentas dummy"
	    #Email usuarios test
	        sed -i 's/mod@mail.com/crystaltales@gmail.com/g' kafhe/protected/migrations/m130814_195553_dummy_data.php
	        sed -i 's/test1@mail.com/cgoo85@gmail.com/g' kafhe/protected/migrations/m130814_195553_dummy_data.php
	        sed -i 's/test2@mail.com/mazzzta.gmail.com/g' kafhe/protected/migrations/m130814_195553_dummy_data.php
	        sed -i 's/test3@mail.com/almavic@gmail.com/g' kafhe/protected/migrations/m130814_195553_dummy_data.php
	        sed -i 's/test4@mail.com/migcampo@gmail.com/g' kafhe/protected/migrations/m130814_195553_dummy_data.php
	        sed -i 's/test5@mail.com/crystaltales@gmail.com/g' kafhe/protected/migrations/m130814_195553_dummy_data.php
	  else
            echo "Borrando archivos dummy..."
	    rm kafhe/protected/migrations/m130814_195553_dummy_data.php 
        fi

    #Lanzo las migraciones
    read -p "Deseas buscar migraciones nuevas? [y o n] " -n 1 -r
	if [[ $REPLY =~ ^[Yy]$ ]]
	  then
	    echo "Buscando migraciones..."
	    cd kafhe/protected/
	    php yiic migrate
	fi

    echo "Terminado"
    
else
  echo "Indica un archivo válido a desplegar. Formato: desplegar <archivo.zip>"
fi

