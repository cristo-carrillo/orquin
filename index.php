<?php
	require_once('config/Config.php');
	require_once('Helpers/Helpers.php');
	$url=!empty($_GET['url']) ? $_GET['url'] : 'home/home';
	$arrUrl=explode("/", $url);
	$controller=$arrUrl[0];
	$method=$arrUrl[0];
	$params="";

	if (!empty($arrUrl[1])) 
	{
		if ($arrUrl[1]!="")
		 {
			$method=$arrUrl[1];
		}
	}

	if (!empty($arrUrl[2])) 
		{	if ($arrUrl[2]!="") {
			for ($i=2; $i <count($arrUrl) ; $i++) { 
				$params.=$arrUrl[$i].',';	
			}
			$params=trim($params,','); #el metodo trim se utiliza para eliminar la ultima ,
					
		}	
	}

	require_once("Libraries/Core/Autoload.php");


	//load

	require_once("Libraries/Core/Load.php");

	
?>