<?

class Measurement extends Dbobj {
	protected static $table  = 'measurements';
	protected static $FIELDS = array(
		'id'               => '%d',
		'person_id'        => '%d',
		'on_date_time'     => '%s',
		'measurement_type' => '%s',
		'measurement_id'   => '%d');

	public $m;

	public $date;
	public $time;

	public function save() {
		if($this->m->isNew()) {
			$id = $this->m->insert();
			if(NULL !== $id) { 
				$this->measurement_id = $id;
				$this->insert();
				return TRUE;
			} else return FALSE;
		}
	}

	public function getMeasurement() {
		return $this->m;
	}

	public function gDate() {
		return current(explode(' ', $this->on_date_time));
	}

	public function gTime() {
		$tmp = explode(' ', $this->on_date_time);
		return $tmp[1];
	}

	public function setOnDateTime($date, $time) {
		$this->on_date_time = $date." ".$time;//"YYYY-MM-DD HH:MM:SS"
	}

	public function setOnDateTimeDT($str) {
		$this->on_date_time = $str;
	}

	public function setNow() {
		$tm = time();
		$this->setOnDateTime(self::toDate($tm), self::toTime($tm));
	}

	private function recalcOnDateTime() {
		if(NULL == $this->time || $this->time == '') $time = '00:00:00';
		else $time = $this->time;

		$this->on_date_time = $this->date.' '.$time;

	}

	public function setDate($year, $month, $day) {
		if($month<10) $month = '0'.$month;
		if($day<10) $day = '0'.$day;
		$this->date = "$year-$month-$day";

		$this->recalcOnDateTime(); 
	}

	public function setTime($h,$m,$s) {
		if($h<10) $h .= '0'.$h;
		if($h==0) $h .= '0'.$h;
		if($m<10) $m .= '0'.$m;
		if($m==0) $m .= '0'.$m;
		if($s<10) $s .= '0'.$s;
		if($s==0) $s .= '0'.$s;
		$this->time = "$h:$m:$s";

		$this->recalcOnDateTime();

	}

	public function setMeasurement($m) {

		$cl = get_class($m);
		$this->measurement_type = $cl   ;
		if(!$m->isNew())
			$this->measurement_id   = $m->id;

		$this->m = $m;
	}

}

