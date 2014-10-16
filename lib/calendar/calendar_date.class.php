<?

class CalendarDate  {
	public static function monthBegin($year, $month) {
		return mktime(0, 0, 0, $month, 1, $year);
	}

	public static function monthEnd($year, $month) {
		return mktime(0, 0, 0, $month, date("t", self::monthBegin($year, $month)), $year);
	}

	//takes "Y-m-d"; returns array: arr[0] = month begin date; arr[1] = month end date;
	public static function beginEnd($data) {
		$tmp   = explode('-', $data, 3);
		$year  = $tmp[0];
		$month = $tmp[1]; 
		$begin = mktime(0, 0, 0, $month, 1, $year);
		$end   = mktime(0, 0, 0, $month, date("t", $begin), $year);
		return array($begin, $end);
	}

	public static function f($data) {
		return date("Y-m-d", $data);
	}

	public static function e($date) {
		return explode('-', $date, 3);
	}

	public static function d($data) {
		$tmp = self::e($data);
		return count($tmp)==3 ? mktime(0, 0, 0, $tmp[1], $tmp[2], $tmp[0]) : FALSE;
	}

	public static function isValid($date) { 
		$tmp = self::e($date);
		return count($tmp)==3 ? checkdate($tmp[1], $tmp[2], $tmp[0]) : FALSE;
	}

	public static function sanitize($str) {
		return self::isValid($str) ? $str : FALSE;
	}

}
