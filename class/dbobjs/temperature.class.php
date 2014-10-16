<?

# -----------------------------------------
# -------------- UNITS --------------------
# -----------------------------------------
# C : celsius, F : farengheit, K : kelvin

class TemperatureUnit {

	public static function isValid($str) {
		return in_array($str, array(self::C(), self::F(), self::K()));
	}

	public static function C() { return 'c'; }	
	public static function F() { return 'f'; } 
	public static function K() { return 'k'; } 
}

class TemperatureConverter {
	public static function ftoc($f) {
		return 5/9*(f-32) ;
	}
	public static function ctof($c) {
		return 9/5*c+32;
	}
}

class Temperature extends Dbobj {
	protected static $table  = "temperatures";
	protected static $FIELDS = array(
		'id'         => '%d',
		'value'      => '%f',
		'unit'       => '%s');

	public static function defaultUnit() {
		return TemperatureUnit::C();
	}

	public function hasValidationErrors() {
		$validation = array();
		if($v = self::validateNotEmpty('value')) {
		   	$validation['value'] = $v;
		} else {
			if($v = self::validateNumeric('value')) $validation['value'] = $v;
		}
		return $validation;
	}

	protected function beforeInsert() {
		if(!TemperatureUnit::isValid($this->unit))
			$this->unit = self::defaultUnit(); 
	}

	public function setValue($value, $unit) {
		if(TemperatureUnit::isValid($unit)) {
			$this->value = $value;
			$this->unit  = $unit ;
			return TRUE;
		} else return FALSE;
	}

	public function setValueC($value) {
		$this->setValue($value, TemperatureUnit::C());
	}

	public function setValueF($value) {
		$this->setValue($value, TemperatureUnit::F());
	}

	public function setValueK($value) {
		$this->setValue($value, TemperatureUnit::K());
	}

	public function __construct($value = NULL) {
		if(NULL !== $value)
			$this->setValue($value, $this->defaultUnit());
	}

}

