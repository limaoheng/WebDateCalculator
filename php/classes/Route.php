<?php 

class Route {
	
	private static $validRoutes = array();
	
	public static function set($route, $function) {
		
		self::$validRoutes[] = $route;
		
		// Now we are checking the controllerset in POST
		$controller = $_POST['controller'];
		$operation = $_POST['operation'];
		
		if ($route === $controller) {
			$controllerObj = $function();
			$resp = $controllerObj->$operation();
			echo json_encode($resp);
		}
		
	}	
}

?>