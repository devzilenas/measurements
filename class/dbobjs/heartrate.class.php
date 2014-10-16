<?

class Heartrate extends Dbobj {
	protected static $table = "heartrate";
	protected static $FIELDS = array(
		'id'         => '%d',
		'bpm'        => '%d');

	public function setBpm($value) {
		$this->bpm = $value;
	}

	public function set15($value) {
		$this->setBpm($value * 4);
	}

	public function to_s() {
		return $this->bpm;
	}

	public function hasValidationErrors() {
		$validation = array();

		if($v = self::validateNotEmpty('bpm')) {
		   	$validation['bpm'] = $v;
		} else {
			if ($v = self::validateNumeric('bpm')) 
				$validation['bpm'] = $v;
		}

		return $validation;
	}

}

