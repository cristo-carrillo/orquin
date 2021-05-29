<?php 
	require_once("Libraries/Core/Pgsql.php");
	trait TTipoPago{
		private $con;

		public function getTiposPago(){
			$this->con= new Pgsql();
			$sql= "SELECT * FROM tipopago WHERE status != 0";
			$request = $this->con->selec_all($sql);
			return $request;
		}
	}


?>