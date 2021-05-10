<?php 
	require_once("Libraries/Core/Pgsql.php");
	trait TCategoria{
		private $con;

		public function getCategoriasT(string $categorias){
			$this->con = new Pgsql();
			$sql = "SELECT idcategoria, nombre, descripcion, portada, ruta FROM categoria WHERE status != 0 AND idcategoria IN ($categorias)";
			$request = $this->con->selec_all($sql);
			if(count($request)>0){
				for($c=0; $c<count($request);$c++){
					$request[$c]['portada'] = BASE_URL.'Assets/images/uploads/'.$request[$c]['portada'];
				}
			}
			return $request;
		}
	}


?>