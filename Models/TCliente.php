<?php 
	require_once("Libraries/Core/Pgsql.php");
	trait TCliente{
		private $con;
		private $intIdUsuario;
		private $strNombre;
		private $strApellido;
		private $intTelefono;
		private $strEmail;
		private $strPassword;
		private $strToken;
		private $intTipoId;

		

		public function insertCliente(string $nombre, string $apellido, int $telefono, string $email, string $password, int $tipoid){
			$this->con = new Pgsql();
			$this->strNombre = $nombre;
			$this->strApellido = $apellido;
			$this->intTelefono = $telefono;
			$this->strEmail = $email;
			$this->strPassword = $password;
			$this->intTipoId = $tipoid;

			$return = 0;
			$sql = "SELECT * FROM persona WHERE 
					email_user = '{$this->strEmail}' ";
			$request = $this->con->selec_all($sql);

			if(empty($request))
			{
				$query_insert  = "INSERT INTO persona(nombres,apellidos,telefono,email_user,password,rolid) 
								  VALUES(?,?,?,?,?,?)";
	        	$arrData = array(
	    						$this->strNombre,
	    						$this->strApellido,
	    						$this->intTelefono,
	    						$this->strEmail,
	    						$this->strPassword,
	    						$this->intTipoId
	    						);
	        	$request_insert = $this->con->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = -2;
			}
	        return $return;
		}
	}


?>