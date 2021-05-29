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

	//PAYPAL
	const URLPAYPAL="https://api-m.sandbox.paypal.com";
	const IDCLIENTE="Ab52iYQ0cA_s_AzdCmFjHC5X-4jzdVXlyPJKMnWiu3BPBpszfbVCh-o5UbqZHiXDmff-TUhWpjaWpdWe";
	const SECRET="EGOG0vpBRMPfOti_JZ_3iDdWVGQommXZxFR4mOOIzkvR-LwErKLYPVOBBm3dPfE0i8S5-nb84XBGDizN";

	//PANADERIA
	 const NOMBRE_EMPRESA='Panaderia Orquin';
	 const DIRECCION='cra. 9 N°.12-43';
	 const TELEMPRESA='321 9893409 ';
	 const EMAIL_EMPRESA='orquin1920@gmail.com';
	 const WEB_EMPRESA='http://localhost/orquin';

	//Simbolo de la moneda

	const SMONEY="$";
	const CURRENCY = "USD";

	//PANADERIA BANNER

	const CAT_SLIDER = "1,2,3";
	const CAT_BANNER = "4,5,6";

	//ENCRIPTAR
	const KEY = 'cjcs';
	const METHODENCRIPT = "AES-128-ECB";

	const COSTOENVIO=5;

	//Módulos
	const MCLIENTES = 3;
	const MPEDIDOS = 5;

	//roles
	const RADMINISTRADOR = 1;
	const RCLIENTES = 7;


	const STATUS = array('Completo','Aprobado','Cancelado','Reembolsado','Pendiente','Entregado');
		
?>