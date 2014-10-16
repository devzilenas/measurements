<?

class HeightConverter {

	public static function inToCm($in) {
		return $in*2.54;               // 1inch equals 2.54 centimeters
	}

	public static function cmtoIn($cm) {
		return $cm/self::inToCm(1);
	}

	public static function ftToCm($ft) {
		return $ft*12*self::inToCm(1); // 1ft equals 12 inches
	}

	public static function cmtoFtin($cm) {
		$ft = floor(self::cmtoIn($cm));
		$in = self::cmToIn($cm - self::ftToCm($ft));
		return array($ft, $in);
	}

	public static function ftInToCm($ft, $in) {
		return self::ftToCm($ft)+self::inToCm($in);
	}

}

class HeightUnit {

	public static function isValid($str) {
		return in_array($str, self::cm(), self::ft());
	}

	public static function cm() {
		return 'cm';
	}

	public static function ft() {
		return 'ft';
	}

} 

class Height extends Dbobj {
	protected static $table  = 'heights';
	protected static $FIELDS = array(
		'id'         => '%d',
		'value'      => '%d',
		'unit'       => '%s');

	public function hasValidationErrors() {
		$validation = array();
		if($v = self::validateNotEmpty('value')) {
			$validation['value'] = $v;
		} else {
			if($v = self::validateNumeric('value')) $validation['value'] = $v;
		}
		return $validation;
	}

	private static function defaultUnit() {
		return HeightUnit::cm();
	}	

	public function __construct($val = NULL, $unit = NULL) {
		if (NULL === $unit) $unit = self::defaultUnit();
		if (NULL !== $val) $this->setValue($val, $unit);
	}

	public function setValue($val, $unit) {
		if(HeightUnit::isValid($unit)) {
			$this->value = $val ;
			$this->unit  = $unit;
			return TRUE;
		} else return FALSE;
	}

	public function setValueCm($val) {
		$this->setValue($val, HeightUnit::cm());
	}

	public function setValueFt($val) {
		$this->setValue($val, HeightUnit::ft());
	}

}

