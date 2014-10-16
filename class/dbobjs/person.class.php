<?

class Person extends Dbobj {
	protected static $table  = "people";
	protected static $FIELDS = array(
		'id'         => '%d',
		'name'       => '%s',
		'surname'    => '%s',
		'born'       => '%s',
		'user_id'    => '%d');

	public function hasValidationErrors() {
		$validation = array();
		if ($v = self::validateNotEmpty('name')) $validation['name'] = $v;
		if ($v = self::validateNotEmpty('surname')) $validation['surname'] = $v;
		if ($v = self::validateNotEmpty('born')) { 
			$validation['born'] = $v;
		} else {
			if($v = self::validateIsDate('born'))
				$validation['born'] = $v; 
		}

		return $validation;
	}

	public function fullName() {
		return trim(join(' ', array($this->name, $this->surname)));
	}

	public static function byUserId($user_id) {
		$filter = self::newFilter(array("Person" => "*"));
		$filter->setFrom(array("Person" => "p"));
		$filter->setWhere(array("Person.user_id" => $user_id));
		$filter->setLimit(1);
		return current(self::find($filter));
	}

	public function to_s() {
		$fn = $this->fullName();
		return ('' === $fn) ? $this->id : $fn;
	}

	public function temperaturesFilter() {
		$filter = Temperature::newFilter("t.*, m.on_date_time as wh");
		$filter->setFrom(array('Temperature' => 't'));
		$filter->setWhere(array('Measurement.measurement_type' => 'Temperature', 'Measurement.person_id' => $this->id));
		$filter->setJoinTables(array('Measurement' => 'm'));
		$filter->setJoinOn(array("Measurement.measurement_id" => "Temperature.id"));
		$filter->setOrderBy('m.on_date_time ASC ');
		return $filter;

		/*
			SELECT t.value
			FROM temperatures t JOIN measurements m
			ON m.measurement_id = t.id
			WHERE m.measurement_type = 'temperature' AND m.person_id = '1'
		 */
	}

	public function heartratesFilter() {
		$filter = Heartrate::newFilter("hr.*, m.on_date_time as wh");
		$filter->setFrom(array('Heartrate' => 'hr'));
		$filter->setWhere(array('Measurement.measurement_type' => 'Heartrate', 'Measurement.person_id' => $this->id));
		$filter->setJoinTables(array('Measurement' => 'm'));
		$filter->setJoinOn(array("Measurement.measurement_id" => "Heartrate.id"));
		$filter->setOrderBy('m.on_date_time ASC ');
		return $filter;
	}

	public function weightsFilter() {
		$filter = Weight::newFilter("w.*, m.on_date_time as wh");
		$filter->setFrom(array('Weight' => 'w'));
		$filter->setWhere(array('Measurement.measurement_type' => 'Weight', 'Measurement.person_id' => $this->id));
		$filter->setJoinTables(array('Measurement' => 'm'));
		$filter->setJoinOn(array("Measurement.measurement_id" => "Weight.id"));
		$filter->setOrderBy('m.on_date_time ASC ');
		return $filter;
	}

	public function temperaturesHoursFilter($start_time) {
		$wheres = array();

		// make 24 hours period
		$s = self::toDateTime($start_time);
		$e = self::toDateTime($start_time + 24*60*60 - 1);

		$dateWhere = "m.on_date_time >= '$s' AND m.on_date_time <= '$e'";
		$wheres[] = $dateWhere;

		$filter = new SqlFilter("AVG(t.value) as value, DATE_FORMAT(m.on_date_time, '%Y-%m-%d %H') as gr");
		$filter->setFrom(Temperature::tableName()." t");
		$filter->setJoin("JOIN ".Measurement::tableName()." m");
		$filter->setOn("ON m.measurement_id = t.id");

		$wheres[] = "m.person_id = ".Dbobj::eq($this->id)." AND m.measurement_type = ".Dbobj::q('Temperature');
		$filter->setWhere(join(' AND ',$wheres));
		$filter->setGroupBy("gr");
		$filter->setOrderBy("m.on_date_time");
		return $filter;
	}

	public function temperaturesDaysFilter($start_time) {
		$wheres = array();

		// make 30 days period
		$s = self::toDateTime($start_time);
		$e = self::toDateTime($start_time + 30*24*60*60 - 1);

		$dateWhere = "m.on_date_time >= '$s' AND m.on_date_time <= '$e'";
		$wheres[] = $dateWhere;

		$filter = new SqlFilter("AVG(t.value) as value, DATE_FORMAT(m.on_date_time, '%Y-%m-%d') as gr");
		$filter->setFrom(Temperature::tableName()." t");
		$filter->setJoin("JOIN ".Measurement::tableName()." m");
		$filter->setOn("ON m.measurement_id = t.id");

		$wheres[] = "m.person_id = ".Dbobj::eq($this->id)." AND m.measurement_type = ".Dbobj::q('Temperature');
		$filter->setWhere(join(' AND ',$wheres));
		$filter->setGroupBy("gr");
		$filter->setOrderBy("m.on_date_time");
		return $filter;
	}

	public function heartRatesHoursFilter($start_time) {
		$wheres = array();

		// make 24 hours period
		$s = self::toDateTime($start_time);
		$e = self::toDateTime($start_time + 24*60*60 - 1);

		$dateWhere = "m.on_date_time >= '$s' AND m.on_date_time <= '$e'";
		$wheres[] = $dateWhere;

		$filter = new SqlFilter("AVG(hr.bpm) as bpm, DATE_FORMAT(m.on_date_time, '%Y-%m-%d %H') as gr");
		$filter->setFrom(Heartrate::tableName()." hr");
		$filter->setJoin("JOIN ".Measurement::tableName()." m");
		$filter->setOn("ON m.measurement_id = hr.id");

		$wheres[] = "m.person_id = ".Dbobj::eq($this->id)." AND m.measurement_type = ".Dbobj::q('Heartrate');
		$filter->setWhere(join(' AND ',$wheres));
		$filter->setGroupBy("gr");
		$filter->setOrderBy("m.on_date_time");
		return $filter;
	}

	public function heartRatesDaysFilter($start_time) {
		$wheres = array();

		// make 30 days period
		$s = self::toDateTime($start_time);
		$e = self::toDateTime($start_time + 30*24*60*60 - 1);

		$dateWhere = "m.on_date_time >= '$s' AND m.on_date_time <= '$e'";
		$wheres[] = $dateWhere;

		$filter = new SqlFilter("AVG(hr.bpm) as bpm, DATE_FORMAT(m.on_date_time, '%Y-%m-%d') as gr");
		$filter->setFrom(Heartrate::tableName()." hr");
		$filter->setJoin("JOIN ".Measurement::tableName()." m");
		$filter->setOn("ON m.measurement_id = hr.id");

		$wheres[] = "m.person_id = ".Dbobj::eq($this->id)." AND m.measurement_type = ".Dbobj::q('Heartrate');
		$filter->setWhere(join(' AND ',$wheres));
		$filter->setGroupBy("gr");
		$filter->setOrderBy("m.on_date_time");
		return $filter;
	}

	public function heightsYearsFilter($start_time, $years = 10) {
		$wheres = array();

		// make $years period
		$s = self::toDateTime($start_time);
		$e = self::toDateTime($start_time + $years*365*24*60*60);

		$dateWhere = "m.on_date_time >= '$s' AND m.on_date_time <= '$e'";
		$wheres[] = $dateWhere;

		$filter = new SqlFilter("AVG(h.value) as value, DATE_FORMAT(m.on_date_time, '%Y') as gr");
		$filter->setFrom(Height::tableName()." h");
		$filter->setJoin("JOIN ".Measurement::tableName()." m");
		$filter->setOn("ON m.measurement_id = h.id");

		$wheres[] = "m.person_id = ".Dbobj::eq($this->id)." AND m.measurement_type = ".Dbobj::q('Height');
		$filter->setWhere(join(' AND ',$wheres));
		$filter->setGroupBy("gr");
		$filter->setOrderBy("m.on_date_time");
		return $filter;
	}

	public function weightsMonthsFilter($start_time) {
		$wheres = array();

		// make 24 hours period
		$s = self::toDateTime($start_time);
		$e = self::toDateTime($start_time + 365*24*60*60);

		$dateWhere = "m.on_date_time >= '$s' AND m.on_date_time <= '$e'";
		$wheres[] = $dateWhere;

		$filter = new SqlFilter("AVG(w.value) as value, DATE_FORMAT(m.on_date_time, '%Y-%m') as gr");
		$filter->setFrom(Weight::tableName()." w");
		$filter->setJoin("JOIN ".Measurement::tableName()." m");
		$filter->setOn("ON m.measurement_id = w.id");

		$wheres[] = "m.person_id = ".Dbobj::eq($this->id)." AND m.measurement_type = ".Dbobj::q('Weight');
		$filter->setWhere(join(' AND ',$wheres));
		$filter->setGroupBy("gr");
		$filter->setOrderBy("m.on_date_time");
		return $filter;
	}

}

