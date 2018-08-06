<?php 

class Route {
	
	private static $validRoutes = array();
	
	public static function set($route, $function) {
		
		self::$validRoutes[] = $route;
		
		// Now we are checking the controller set in POST
		$controller = $_POST['controller'];
		$operation = $_POST['operation'];
		
		if ($route === $controller) {
			$controllerObj = $function();
			try {
				$resp = $controllerObj->$operation();
				echo json_encode($resp);
			} catch (Exception $e) {
				echo json_encode(array(
					'error' => array(
						'msg' => $e->getMessage()
					)
				));
			}
		}
		
	}	
}

?>