<?php
	#define("BASE_URL", "http://localhost/orquin/"); ESTE METODO SE UTILIZA PARA DEFINIR CONSTANTES
	const BASE_URL = "http://localhost/orquin/";


	//Zona horaria
	date_default_timezone_set('America/Bogota');

	//para la conexion con la bd
	const DB_HOST="localhost";
	const DB_NAME="panaderia_online";
	const DB_USER="postgres";
	const DB_PASSWORD="carrillo20";
	const DB_CHARSET = "utf8";

	//Delimitadores decimal y millar ej. 24,1989.00
	const SPD=",";
	const SPM=".";

	//Simbolo de la moneda

	const SMONEY="$";

	const CAT_SLIDER = "1,2,3";
	const CAT_BANNER = "4,5,6";

	const KEY = 'cjcs';
	const METHODENCRIPT = "AES-128-ECB";

	const COSTOENVIO=5000;
		
?>