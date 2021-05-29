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
		private $intIdTransaccion;
		

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

		public function insertPedido(string $idtransaccionpaypal = NULL, string $datospaypal = NULL, int $personaid, float $costo_envio, string $monto, int $tipopagoid, string $direccionenvio, string $status){
		$this->con = new Pgsql();
		$query_insert  = "INSERT INTO pedido(idtransaccionpaypal,datospaypal,personaid,costo_envio,monto,tipopagoid,direccion_envio,status) 
							  VALUES(?,?,?,?,?,?,?,?)";
		$arrData = array($idtransaccionpaypal,
    						$datospaypal,
    						$personaid,
    						$costo_envio,
    						$monto,
    						$tipopagoid,
    						$direccionenvio,
    						$status
    					);
		$request_insert = $this->con->insert($query_insert,$arrData);
	    $return = $request_insert;
	    return $return;
	}

	public function insertDetalle(int $idpedido, int $productoid, float $precio, int $cantidad){
		$this->con = new Pgsql();
		$query_insert  = "INSERT INTO detalle_pedido(pedidoid,productoid,precio,cantidad) 
							  VALUES(?,?,?,?)";
		$arrData = array($idpedido,
    					$productoid,
						$precio,
						$cantidad
					);
		$request_insert = $this->con->insert($query_insert,$arrData);
	    $return = $request_insert;
	    return $return;
	}



		public function insertDetalleTemp(array $pedido){
			$this->con = new Pgsql();
			$this->intIdUsuario= $pedido['idcliente'];
			$this->intIdTransaccion = $pedido['idtransaccion'];
			$productos = $pedido['productos'];
			$sql = "SELECT * FROM detalle_temp WHERE 
					transaccionid = '{$this->intIdTransaccion}' AND 
					personaid = $this->intIdUsuario";
			$request = $this->con->selec_all($sql);
			if(empty($request)){
				foreach ($productos as $producto) {
					$query_insert  = "INSERT INTO detalle_temp(personaid,productoid,precio,cantidad,transaccionid) 
									  VALUES(?,?,?,?,?)";
		        	$arrData = array($this->intIdUsuario,
		        					$producto['idproducto'],
		    						$producto['precio'],
		    						$producto['cantidad'],
		    						$this->intIdTransaccion
		    					);
		        	$request_insert = $this->con->insert($query_insert,$arrData);
				}
			}else{
				$sqlDel = "DELETE FROM detalle_temp WHERE 
					transaccionid = '{$this->intIdTransaccion}' AND 
					personaid = $this->intIdUsuario";
				$request = $this->con->delete($sqlDel);
				foreach ($productos as $producto) {
					$query_insert  = "INSERT INTO detalle_temp(personaid,productoid,precio,cantidad,transaccionid) 
									  VALUES(?,?,?,?,?)";
		        	$arrData = array($this->intIdUsuario,
		        					$producto['idproducto'],
		    						$producto['precio'],
		    						$producto['cantidad'],
		    						$this->intIdTransaccion
		    					);
		        	$request_insert = $this->con->insert($query_insert,$arrData);
				}
			}
		}
	}


?>