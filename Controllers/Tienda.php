<?php  
	require_once("Models/TCategoria.php");
	require_once("Models/TProducto.php");
	require_once("Models/TCliente.php");
	require_once("Models/loginModel.php");
	class Tienda extends Controllers{
		use TCategoria, TProducto, TCliente;
		public $login;
		public function __construct()
		{
			parent::__construct();
			session_start();
			$this->login = new loginModel();
		}

		public function tienda()
		{	
			$data['page_tag']="Panaderia Orquin";
			$data['page_title']="Panaderia Orquin";
			$data['page_name']="orquin";
			$data['productos'] = $this->getProductosT();
			$this->views->getView($this,"tienda",$data);
		}

		public function categoria($params){
			if(empty($params)){
				header("Location:".base_url());
			}else{
				$arrParams = explode(",",$params);
				$idcategoria = intval($arrParams[0]);
				$ruta=strClean($arrParams[1]);
				$infoCategoria = $this->getProductosCategoriaT($idcategoria,$ruta);
				$categoria=strClean($params);
				$data['page_tag']="Panaderia Orquin"." - ".$infoCategoria['categoria'];
				$data['page_title']=$infoCategoria['categoria'];
				$data['page_name']="categoria";
				$data['productos'] =$infoCategoria['productos'];
				$this->views->getView($this,"categoria",$data);
				
			}
		}

		public function producto($params){
			if(empty($params)){
				header("Location:".base_url());
			}else{
				$arrParams = explode(",", $params);
				$idproducto = intval($arrParams[0]);
				$ruta=strClean($arrParams[1]);
				$infoProducto = $this->getProductoT($idproducto,$ruta);
				if(empty($infoProducto)){
					header("Location:".base_url());
				}
				
				$data['page_tag']="Panaderia Orquin"." - ".$infoProducto['nombre'];
				$data['page_title']=$infoProducto['nombre'];
				$data['page_name']="producto";
				$data['producto'] = $infoProducto;
				$data['productos'] = $this->getProductosRandom($infoProducto['categoriaid'],8,"r");
				$this->views->getView($this,"producto",$data);
				
			}
		}

		public function addCarrito(){
			if($_POST){
				//unset($_SESSION['arrCarrito']);exit;
				$arrCarrito = array();
				$cantCarrito=0;
				$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
				$cantidad= $_POST['cant'];
				if(is_numeric($idproducto) and is_numeric($cantidad)){
					$arrInfoProducto = $this->getProductoIDT($idproducto);
					
					if(!empty($arrInfoProducto)){
						$arrProducto = array('idproducto' => $idproducto,
											'producto' => $arrInfoProducto['nombre'],
											'cantidad' => $cantidad,
											'precio' => $arrInfoProducto['precio'],
											'imagen' => $arrInfoProducto['images'][0]['url_image']
										);
						if(isset($_SESSION['arrCarrito'])){
							$on= true;
							$arrCarrito = $_SESSION['arrCarrito'];
							for ($pr=0; $pr < count($arrCarrito) ; $pr++) { 
								if($arrCarrito[$pr]['idproducto']==$idproducto){
									$arrCarrito[$pr]['cantidad']+=$cantidad;
									$on = false;
								}
							}
							if($on){
								array_push($arrCarrito, $arrProducto);
							}
							$_SESSION['arrCarrito'] = $arrCarrito;
						}else{
							array_push($arrCarrito, $arrProducto);
							$_SESSION['arrCarrito'] = $arrCarrito;
						}
						foreach ($_SESSION['arrCarrito'] as $pro) {
							$cantCarrito += $pro['cantidad'];
						}
						$htmlCarrito = getFile('Template/Modals/modalCarrito',$_SESSION['arrCarrito']);
						$arrResponse = array("status" => true, "msg" => '¡se agrego al acrrito!',"cantCarrito" => $cantCarrito, "htmlCarrito" => $htmlCarrito);
					}else{
						$arrResponse = array("status"=>false, "msg"=>'Producto inexistente');
					}
				}else{
					$arrResponse = array("status"=> false, "msg"=>'dato incorrecto');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
				
			}
			die();
		}

		public function delCarrito(){
			if($_POST){
				$arrCarrito = array();
				$cantCarrito = 0;
				$subtotal = 0;
				$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
				$option = $_POST['option'];
				if(is_numeric($idproducto) and ($option == 1 or $option == 2)){
					$arrCarrito = $_SESSION['arrCarrito'];
					for ($pr=0; $pr < count($arrCarrito); $pr++) {
						if($arrCarrito[$pr]['idproducto'] == $idproducto){
							unset($arrCarrito[$pr]);//unset para eliminar
						}
					}
					sort($arrCarrito);//ordenar el arreglo
					$_SESSION['arrCarrito'] = $arrCarrito;
					foreach ($_SESSION['arrCarrito'] as $pro) {
						$cantCarrito += $pro['cantidad'];
						$subtotal += $pro['cantidad'] * $pro['precio'];
					}
					$htmlCarrito = "";
					if($option == 1){
						$htmlCarrito = getFile('Template/Modals/modalCarrito',$_SESSION['arrCarrito']);
					}
					$arrResponse = array("status" => true, 
											"msg" => '¡Producto eliminado!',
											"cantCarrito" => $cantCarrito,
											"htmlCarrito" => $htmlCarrito,
											"subtotal" => SMONEY.formatMoney($subtotal),
											"total" => SMONEY.formatMoney($subtotal+COSTOENVIO)
										);
				}else{
					$arrResponse = array("status" => false, "msg" => 'Dato incorrecto.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function updCarrito(){
			if($_POST){
				$arrCarrito = array();
				$totalProducto = 0;
				$subtotal = 0;
				$total = 0;

				$idproducto = openssl_decrypt($_POST['id'], METHODENCRIPT, KEY);
				$cantidad = intval($_POST['cantidad']);
				if(is_numeric($idproducto) and $cantidad > 0){
					$arrCarrito = $_SESSION['arrCarrito'];
					for ($p=0; $p < count($arrCarrito); $p++) { 
						if($arrCarrito[$p]['idproducto'] == $idproducto){
							$arrCarrito[$p]['cantidad'] = $cantidad;
							$totalProducto = $arrCarrito[$p]['precio'] * $cantidad;
							break;
						}
					}
					$_SESSION['arrCarrito'] = $arrCarrito;
					foreach ($_SESSION['arrCarrito'] as $pro) {
						$subtotal += $pro['cantidad'] * $pro['precio']; 
					}
					$arrResponse = array("status" => true, 
										"msg" => '¡Producto actualizado!',
										"totalProducto" => SMONEY.formatMoney($totalProducto),
										"subTotal" => SMONEY.formatMoney($subtotal),
										"total" => SMONEY.formatMoney($subtotal + COSTOENVIO)
									);

				}else{
					$arrResponse = array("status" => false, "msg" => 'Dato incorrecto.');
				}
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();
		}

		public function registro(){
			if($_POST){


				if(empty($_POST['txtNombre']) || empty($_POST['txtApellido']) || empty($_POST['txtTelefono']) || empty($_POST['txtEmailCliente']) || empty($_POST['txtPassword']) )
				{
					$arrResponse = array("status" => false, "msg" => 'Datos incorrectos.');
				}else{ 
					$strNombre = ucwords(strClean($_POST['txtNombre']));
					$strApellido = ucwords(strClean($_POST['txtApellido']));
					$intTelefono = intval(strClean($_POST['txtTelefono']));
					$strEmail = strtolower(strClean($_POST['txtEmailCliente']));
					$request_user = "";
					$intTipoId = 7;//passwor agregar campo
					$strPassword = $_POST['txtPassword'];
					$strPasswordEncript = hash("SHA256", $strPassword);
												
					$request_user = $this->insertCliente(
														$strNombre, 
														$strApellido, 
														$intTelefono, 
														$strEmail,
														$strPasswordEncript,
														$intTipoId);
												

					
					
						if( $request_user>0){
							$arrResponse = array('status'=>true, 'msg'=>'Datos Guardados exitosamente');
							$_SESSION['idUser'] = $request_user;
							$_SESSION['login'] = true;
							$this->login->sessionLogin($request_user);
							
						}else if($request_user==-2){
						$arrResponse = array('status' => false, 'msg' => '¡Atención! el email ya existe, ingrese otro.');
						
						}else{
						$arrResponse = array("status" => false, "msg" => 'No es posible almacenar datos.');
					}

				}
				
				echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			}
			die();	

		}

		public function procesarVenta(){

			if($_POST){
				$idtransaccionpaypal = NULL;
				$datospaypal = NULL;
				$personaid = $_SESSION['idUser'];
				$monto = 0;
				$tipopagoid = intval($_POST['inttipopago']);
				$direccionenvio = strClean($_POST['direccion']).', '.strClean($_POST['ciudad']);
				$status = "Pendiente";
				$subtotal = 0;
				$costo_envio = COSTOENVIO;

				if(!empty($_SESSION['arrCarrito'])){
					foreach ($_SESSION['arrCarrito'] as $pro) {
						$subtotal += $pro['cantidad'] * $pro['precio']; 
					}
					$monto = $subtotal + COSTOENVIO;

					if(empty($_POST['datapay'])){
						$request_pedido = $this->insertPedido($idtransaccionpaypal, 
																	$datospaypal, 
																	$personaid,
																	$costo_envio,
																	$monto, 
																	$tipopagoid,
																	$direccionenvio, 
																	$status);


						if($request_pedido > 0 ){
							foreach ($_SESSION['arrCarrito'] as $producto) {
								$productoid = $producto['idproducto'];
								$precio = $producto['precio'];
								$cantidad = $producto['cantidad'];
								$this->insertDetalle($request_pedido,$productoid,$precio,$cantidad);
							}

							$orden = openssl_encrypt($request_pedido, METHODENCRIPT, KEY);
							$transaccion = openssl_encrypt($idtransaccionpaypal, METHODENCRIPT, KEY);
							$arrResponse = array("status" => true, 
											"orden" => $orden, 
											"transaccion" =>$transaccion,
											"msg" => 'Pedido realizado'
										);

							$_SESSION['dataorden'] = $arrResponse;
							unset($_SESSION['arrCarrito']);
							session_regenerate_id(true);
						}
					}else{
						$jsonPaypal = $_POST['datapay'];
						$objPaypal = json_decode($jsonPaypal);
						$status = "Aprobado";
						if(is_object($objPaypal)){
							$datospaypal = $jsonPaypal;
							$idtransaccionpaypal = $objPaypal->purchase_units[0]->payments->captures[0]->id;
							
							if($objPaypal->status == "COMPLETED"){
								$totalPaypal = formatMoney($objPaypal->purchase_units[0]->amount->value);
								if($monto == $totalPaypal){
									$status = "Completo";
								}
								$request_pedido = $this->insertPedido($idtransaccionpaypal, 
																	$datospaypal, 
																	$personaid,
																	$costo_envio,
																	$monto, 
																	$tipopagoid,
																	$direccionenvio, 
																	$status);


								if($request_pedido > 0 ){
									foreach ($_SESSION['arrCarrito'] as $producto) {
										$productoid = $producto['idproducto'];
										$precio = $producto['precio'];
										$cantidad = $producto['cantidad'];
										$this->insertDetalle($request_pedido,$productoid,$precio,$cantidad);
									}

									$orden = openssl_encrypt($request_pedido, METHODENCRIPT, KEY);
									$transaccion = openssl_encrypt($idtransaccionpaypal, METHODENCRIPT, KEY);
									$arrResponse = array("status" => true, 
													"orden" => $orden, 
													"transaccion" =>$transaccion,
													"msg" => 'Pedido realizado'
												);

									$_SESSION['dataorden'] = $arrResponse;
									unset($_SESSION['arrCarrito']);
									session_regenerate_id(true);

								}else{
									$arrResponse = array("status" => false, "msg" => 'no es posible procesar el pedido');
								}

							}else{
								$arrResponse = array("status" => false, "msg" => 'no es posible el pago con PayPal');
							}

						}else{
							$arrResponse = array("status" => false, "msg" => 'no es posible procesar el pedido');
						}
					}


				}else{
					$arrResponse = array("status" => false, "msg" => 'no es posible procesar el pedido');
				}

			}else{
				$arrResponse = array("status" => false, "msg" => 'no es posible procesar el pedido');
			}
			echo json_encode($arrResponse,JSON_UNESCAPED_UNICODE);
			die();

			
		}

		public function confirmarpedido(){
			if(empty($_SESSION['dataorden'])){
				header("Location: ".base_url());
			}else{
				$dataorden = $_SESSION['dataorden'];
				$idpedido = openssl_decrypt($dataorden['orden'], METHODENCRIPT, KEY);
				$transaccion = openssl_decrypt($dataorden['transaccion'], METHODENCRIPT, KEY);
				$data['page_tag'] = "Confirmar Pedido";
				$data['page_title'] = "Confirmar Pedido";
				$data['page_name'] = "confirmarpedido";
				$data['orden'] = $idpedido;
				$data['transaccion'] = $transaccion;
				$this->views->getView($this,"confirmarpedido",$data);
			}
			unset($_SESSION['dataorden']);
		}
		
		
	}


?>