<?

function print_session_debug() {
	if (Config::$SESSION_SHOW) {
		echo '<p>Sesija:</p>'; print_r($_SESSION);
		if(isset($_REQUEST['clear_session'])) session_destroy();
			else echo '<a href="?clear_session">'.t("clear session").'</a>';
	}
}

function hasV($name, $field) {
	if (isset($_SESSION[$name]) && is_array($_SESSION[$name]) && isset($_SESSION[$name][$field])) return $_SESSION[$name][$field];
	else return NULL;
}

function s($data, $field, $alternative='') {
	if(isset($_SESSION[$data]) && isset($_SESSION[$data][$field])) {
		$val = $_SESSION[$data][$field];
		unset($_SESSION[$data][$field]);
		return $val;
	}
	else return $alternative;
}
