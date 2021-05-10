<?php 
	require_once("Libraries/Core/Pgsql.php");
	trait TProducto{
		private $con;
		private  $strCategoria;
		private $intIdcategoria;
		private $intIdProducto;
		private $strProducto;
		private $cant;
		private $option;
		private $strRuta;
		public function getProductosT(){
			$this->con = new pgsql();
			$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							c.nombre as categoria,
							p.precio,
							p.ruta,
							p.stock
					FROM producto p 
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE p.status != 0 ";
					$request = $this->con->selec_all($sql);
					if(count($request)>0){
						for ($c=0; $c < count($request); $c++) { 
							$intIdProducto = $request[$c]['idproducto'];
							$sqlImg = "SELECT img
							FROM imagen
							WHERE productoid = $intIdProducto";
							$arrImg = $this->con->selec_all($sqlImg);
							if(count($arrImg)>0){
								for ($i=0; $i < count($arrImg); $i++) { 
									$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
								}
							}
							$request[$c]['images'] = $arrImg;
						}
						
					}
			return $request;
		}

		public function getProductosCategoriaT(int $idcategoria,string $ruta){
			$this->intIdcategoria = $idcategoria;
			$this->strRuta=$ruta;
			$this->con = new pgsql();

			$sql_cat = "SELECT idcategoria,nombre FROM categoria WHERE idcategoria = '{$this->intIdcategoria}'";
			$request = $this->con->select($sql_cat);

			if(!empty($request)){
				$this->strCategoria = $request['nombre'];
				$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							c.nombre as categoria,
							p.precio,
							p.ruta,
							p.stock
					FROM producto p 
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE p.status != 0 AND p.categoriaid = $this->intIdcategoria AND c.ruta = '{$this->strRuta}'";
					$request = $this->con->selec_all($sql);
					if(count($request)>0){
						for ($c=0; $c < count($request); $c++) { 
							$intIdProducto = $request[$c]['idproducto'];
							$sqlImg = "SELECT img
							FROM imagen
							WHERE productoid = $intIdProducto";
							$arrImg = $this->con->selec_all($sqlImg);
							if(count($arrImg)>0){
								for ($i=0; $i < count($arrImg); $i++) { 
									$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
								}
							}
							$request[$c]['images'] = $arrImg;
						}
						
					}
					$request = array('idcategoria' => $this->intIdcategoria,
								'categoria' => $this->strCategoria,
								'productos' => $request
							);
			}
			
			return $request;
		}

		public function getProductoT(int $idproducto,string $ruta){
			$this->con = new pgsql();
			$this->intIdProducto=$idproducto;
			$this->strRuta=$ruta;
			
			$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							c.nombre as categoria,
							p.precio,
							c.ruta as rutaca,
							p.ruta,
							p.stock
					FROM producto p 
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE p.status != 0 AND p.idproducto = '{$this->intIdProducto}' AND p.ruta = '{$this->strRuta}'";
					$request = $this->con->select($sql);
					if(!empty($request)){
						
							$intIdProducto = $request['idproducto'];
							$sqlImg = "SELECT img
							FROM imagen
							WHERE productoid = $intIdProducto";
							$arrImg = $this->con->selec_all($sqlImg);
							if(count($arrImg)>0){
								for ($i=0; $i < count($arrImg); $i++) { 
									$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
									
								}
							}else{
								$arrImg[0]['url_image'] = media().'/images/uploads/product.jpg';
							}
							$request['images'] = $arrImg;
						}
						
					
			return $request;
		}

		public function getProductosRandom(int $idcategoria, int $cant, string $option){
			$this->intIdcategoria = $idcategoria;
			$this->cant = $cant;
			$this->option = $option;

			if($option == "r"){
				$this->option = " random() ";
			}else if($option == "a"){
				$this->option = " idproducto ASC ";
			}else{
				$this->option = " idproducto DESC ";
			}

			$this->con = new pgsql();
			
			$sql = "SELECT p.idproducto,
						p.codigo,
						p.nombre,
						p.descripcion,
						p.categoriaid,
						c.nombre as categoria,
						p.precio,
						p.ruta,
						p.stock
				FROM producto p 
				INNER JOIN categoria c
				ON p.categoriaid = c.idcategoria
				WHERE p.status != 0 AND p.categoriaid = $this->intIdcategoria ORDER BY $this->option LIMIT $this->cant ";
				$request = $this->con->selec_all($sql);
				if(count($request)>0){
					for ($c=0; $c < count($request); $c++) { 
						$intIdProducto = $request[$c]['idproducto'];
						$sqlImg = "SELECT img
						FROM imagen
						WHERE productoid = $intIdProducto";
						$arrImg = $this->con->selec_all($sqlImg);
						if(count($arrImg)>0){
							for ($i=0; $i < count($arrImg); $i++) { 
								$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
							}
						}
						$request[$c]['images'] = $arrImg;
					}
					
				}
		
		
			return $request;

		
		}

		public function getProductoIDT(int $idproducto){
			$this->con = new pgsql();
			$this->intIdProducto=$idproducto;
			
			$sql = "SELECT p.idproducto,
							p.codigo,
							p.nombre,
							p.descripcion,
							p.categoriaid,
							c.nombre as categoria,
							p.precio,
							c.ruta as rutaca,
							p.ruta,
							p.stock
					FROM producto p 
					INNER JOIN categoria c
					ON p.categoriaid = c.idcategoria
					WHERE p.status != 0 AND p.idproducto = '{$this->intIdProducto}'";
					$request = $this->con->select($sql);
					if(!empty($request)){
						
							$intIdProducto = $request['idproducto'];
							$sqlImg = "SELECT img
							FROM imagen
							WHERE productoid = $intIdProducto";
							$arrImg = $this->con->selec_all($sqlImg);
							if(count($arrImg)>0){
								for ($i=0; $i < count($arrImg); $i++) { 
									$arrImg[$i]['url_image'] = media().'/images/uploads/'.$arrImg[$i]['img'];
									
								}
							}else{
								$arrImg[0]['url_image'] = media().'/images/uploads/product.jpg';
							}
							$request['images'] = $arrImg;
						}
						
					
			return $request;
		}
	}
?>