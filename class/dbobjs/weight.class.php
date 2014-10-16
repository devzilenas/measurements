<?

class WeightConverter {

	private static function gLb() {
		return 453.59237 ;
	} 

	public static function lbToG($lb) {
		return self::gLb()*$lb;
	}

	public static function lbToKg($lb) {
		return self::lbtoG($lb)*1000;
	}

	public static function gToLb($g) {
		return $g / $this->gLb();
	}

	public static function kgToLb($kg) {
		return self::gToLb($kg*1000);
	}

}

class WeightUnit {

	public static function isValid($str) {
		return in_array($str, array(self::g(), self::kg(), self::lb()));
	}

	public static function g() { 
		return 'g'; 
	}

	public static function kg() {
		return 'kg'; 
	}

	public static function lb() {
		return 'lb';
	}

	public static function pounds() {
		return $this->lb();
	}

}

class Weight extends Dbobj {
	protected static $table = 'weights';
	protected static $FIELDS = array(
		'id'	     => '%d',
		'value'      => '%d',
		'unit'       => '%s');	

	private static function defaultUnit() {
		return WeightUnit::kg();
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

	public function __construct($val = NULL, $unit = NULL) {
		if(NULL === $unit) $unit = self::defaultUnit();
		if(NULL !== $val) $this->setValue($val, $unit);
	}

	public function to_s() {
		return $this->value." ".$this->unit;
	}

	public function setValue($val, $unit) {
		if (WeightUnit::isValid($unit)) {
			$this->value = $val;
			$this->unit  = $unit;
			return TRUE;
		} else return FALSE;
	}

	public function setValueKg($val) {
		$this->setValue($val, WeightUnit::kg());
	}
	
	public function setValueG($val) {
		$this->setValue($val, WeightUnit::g());
	}

	public function setValueLb() {
		$this->setValue($val, WeightUnit::lb());
	}

}

