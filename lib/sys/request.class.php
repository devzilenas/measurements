<?

class Request {

	public static function get0($name) {
		return (!empty($_REQUEST[$name])) ? (int)$_REQUEST[$name] : 0;
	}

	public static function getNull($name) {
		return (!empty($_REQUEST[$name])) ? $_REQUEST[$name] : NULL;
	}

	public static function gPostArray($name) {
		return isset($_POST[$name]) && is_array($_POST[$name]) ? $_POST[$name] : array();
	}

	public static function hlexit($header) {
		header("Location: ".$header);
		exit;
	}

}

