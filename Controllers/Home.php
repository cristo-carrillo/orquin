<?php  
	require_once("Models/TCategoria.php");
	require_once("Models/TProducto.php");
	class Home extends Controllers{
		use TCategoria, TProducto;
		public function __construct()
		{
			parent::__construct();
			session_start();

		}

		public function home()
		{	
			$data['page_tag']="Panaderia Orquin";
			$data['page_title']="Panaderia Orquin";
			$data['page_name']="orquin";
			$data['slider']=$this->getCategoriasT(CAT_SLIDER);
			$data['banner']=$this->getCategoriasT(CAT_BANNER);
			$data['productos'] = $this->getProductosT();
			$this->views->getView($this,"home",$data);
		}

		
		
	}


?>