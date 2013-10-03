#!/bin/bash

FECHA=`date +%A`

echo "Iniciando backup..."

rm backups/bbdd/bbdd-${FECHA}.tar.gz
mysqldump --opt -u XXXXX -pXXXXX -a kafhe > backups/bbdd/bbdd-${FECHA}.sql
tar -zcf backups/bbdd/bbdd-${FECHA}.tar.gz backups/bbdd/bbdd-${FECHA}.sql
rm backups/bbdd/bbdd-${FECHA}.sql

echo "Finalizado"

