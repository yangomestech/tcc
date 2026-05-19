#!/bin/bash


echo "--- Baixando atualizações do Git ---"
git pull

echo "--- Atualizando Banco de Dados ---"
docker exec -i tcc_db_1 mysql -u root -proot -e "DROP DATABASE IF EXISTS tcc; CREATE DATABASE tcc;"
docker exec -i tcc_db_1 mysql -u root -proot tcc < ./tcc.sql

echo "--- Pronto! Sistema e Banco atualizados ---"
