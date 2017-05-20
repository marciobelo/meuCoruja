#!/bin/sh

if [ -f ./script/esquema-versao.sql ]
then
	echo "Carregando o banco de dados com estado ZERO..."
else
	echo "coloque como diretório atual a raiz do projeto"
	exit 1
fi

mysql --default-character-set=utf8 --user=root --password=vertrigo --database=mysql < script/esquema-versao.sql 
mysql --default-character-set=utf8 --user=root --password=vertrigo --database=mysql < script/carga-dados-basicos.sql
mysql --default-character-set=utf8 --user=root --password=vertrigo --database=mysql < script/carga-dados-massa-teste.sql
mysql --default-character-set=utf8 --user=root --password=vertrigo --database=mysql < script/esquema-trigger-validacao.sql
exit 0
