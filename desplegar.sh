#!/bin/bash

FECHA=`date +%d%m%Y`

#if [ "$1" == "0" ]
#  then
#    echo "Falta un parámetro, el archivo a desplegar. Formato: desplegar <archivo.zip>"
if [ -f "$1" ]
  then
    echo "Creando backup..."

    #if [ -f "backup-${FECHA}.zip" ]
    #  then
    #    rm backup-${FECHA}.zip
    #fi
    
    tar -zcf backups/backup-${FECHA}.tar.gz kafhe/*

    echo "Creando backup de base de datos..."
    sh mysql_backup.sh

    echo "Borrando contenidos actuales..."
    rm -rf kafhe/*

    echo "Desplegando $1..."
    unzip -opp "$1" -d kafhe/

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
        sed -i 's/@gmail.com/@kafhe.chequerestaurante.com/g' kafhe/protected/config/*.php
        #sed -i "s/'',\/\/#mysqlUsername/'kafhe',\/\/#mysqlUsername/g" kafhe/protected/config/*.php
        #sed -i "s/'',\/\/#mysqlPassword/'F1tsjA5V',\/\/#mysqlPassword/g" kafhe/protected/config/*.php


    echo "Terminado"
    
else
  echo "Indica un archivo válido a desplegar. Formato: desplegar <archivo.zip>"
fi

