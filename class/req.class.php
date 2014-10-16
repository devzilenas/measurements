<?

class Req implements iReq {

	public static function isPeriodHours() {
		return isset($_GET['period']) && 'hours'===$_GET['period'];
	}

	public static function isPeriodDays() {
		return isset($_GET['period']) && 'days'===$_GET['period'];
	}

	public static function isMeasurementPeriodView($what) {
		return isset($_GET['measurement']) && isset($_GET['view']) && isset($_GET['measurement_type']) && strtolower(c2u($what)) == $_GET['measurement_type']; 
	}

	private static function isMeasurementsByType($name) {
		return isset($_GET['measurements']) && isset($_GET['measurement_type']) && c2u($name) === $_GET['measurement_type'];
	}

	public static function isPersonNew() {
		return isset($_GET['person']) && isset($_GET['new']);
	}

	public static function isPersonAdd() {
		return isset($_GET['people']) && isset($_POST['action']) && 'add' === $_POST['action'];
	}

	public static function isPersonEdit() {
		return isset($_GET['person']) && isset($_GET['edit']);
	}

	public static function isPersonUpdate() {
		return isset($_GET['person']) && isset($_POST['action']) && 'update' === $_POST['action'];
	}

	public static function isPeopleList() {
		return isset($_GET['people']) && isset($_GET['list']);
	}

	public static function isPersonView() {
		return isset($_GET['person']) && isset($_GET['view']);
	}

	public static function isPersonMeasurementsView($what) {
		return isset($_GET['person_id']) && isset($_GET['view']) && self::isMeasurementsByType($what);
	}

	public static function isPersonNewMeasurementRegister($what) {
		return isset($_GET['person_id']) && isset($_GET['measurement']) && isset($_GET['measurement_type']) && c2u($what) == $_GET['measurement_type']  && isset($_GET['new']);
	}

	public static function isNewMeasurements() {
		return isset($_GET['measurements']) && isset($_GET['new']) && isset($_GET['person_id']);
	}

	public static function isPersonMeasurementsList($type) {
		return isset($_GET['list']) && isset($_GET[pluralize(strtolower(c2u($type)))]) && isset($_GET['person_id']);
	}

	public static function isConfigure() {
		return isset($_GET['configure']);
	}

	public static function isTemperatureDaily() {
		return isset($_GET['measurement']) && isset($_GET['view']) && isset($_GET['measurement_type']) && 'temperature' == $_GET['measurement_type'] && isset($_GET['period']) && 'daily' == $_GET['period'];
	}

	public static function isTemperatureMonthly() {
		return isset($_GET['measurements']) && isset($_GET['view']) && isset($_GET['measurement_type']) && 'temperature' == $_GET['measurement_type'] && isset($_GET['period']) && 'monthly' == $_GET['period'];
	}

# -----------------------------------------
# -------------- FORM PROCESSING ----------
# -----------------------------------------

	public static function isPersonMeasurementAdd($what) {
		return isset($_GET['measurements']) && isset($_POST['action']) && 'add' === $_POST['action'] && isset($_POST['measurement']['measurement_type']) && $what === $_POST['measurement']['measurement_type'] && isset($_POST[c2u($what)]);
	}

	public static function process() {

# ------------- PEOPLE ------------------
		# ADD PERSON
		if(self::isPersonAdd()) {
			$person = Person::fromForm($_POST['person'], array('name', 'surname', 'born'));
			$user = Login::user();

			if($validation = $person->hasValidationErrors()) {
				$_SESSION['person_validation'] = $validation;
				self::savePersonToSession($_POST['person']);
				Logger::undefErr(array_values($validation));
				Request::hlexit("?person&new");
			} else {
				# VALID
				if($user->isPerson()) {
					$person->user_id = Login::loggedId();
				} else if($user->isDoctor()) {
					//Nothing
				}
				$person->insert();
				Logger::info("Person created!");
				Request::hlexit("?person=$person->id&edit");
			}
		}

		# UPDATE PERSON
		if(self::isPersonUpdate()) {
			$values  = $_POST['person'];
			$person = Person::load($_GET['person']);
			$ptmp    = Person::fromForm($_POST['person']);

			if($validation = $ptmp->hasValidationErrors()) {
				$_SESSION['person_validation'] = $validation;
				self::savePersonToSession($_POST['person']);
				Logger::undefErr(array_values($validation));
				Request::hlexit("?person=$person->id&edit");
			} else {
				# VALID
				if($person->updateFromForm(
					$_POST['person'], array('name', 'surname', 'born'))) {
						Logger::info("Person updated!");
						Request::hlexit("?person=$person->id&edit");
					}
			}
		}

# -------------- ADD MEASUREMENT ----------
		# TEMPERATURE
		if(self::isPersonMeasurementAdd('Temperature')) {
			$m = self::getMeasurementFromPost();

			$temp = Temperature::fromForm($_POST['temperature'], array('value'));
			if($validation = $temp->hasValidationErrors()) {
				$_SESSION['temperature_validation'] = $validation;
				self::saveTemperatureToSession($_POST['temperature']);
				self::saveMeasurementToSession($_POST['measurement']);
				Logger::undefErr(array_values($validation));
				Request::hlexit("?measurement&new&person_id=$m->person_id&measurement_type=temperature");
			} else {
				$m->setMeasurement($temp);
				self::saveMeasurement($m);
				Request::hlexit("?measurements&view&person_id=$m->person_id&measurement_type=temperature");
			}
		}

		# HEARTRATE
		if(self::isPersonMeasurementAdd('Heartrate')) {
			$m = self::getMeasurementFromPost();
			$hr = Heartrate::fromForm($_POST['heartrate'], array('bpm'));
			if($validation = $hr->hasValidationErrors()) {
				$_SESSION['heartrate_validation'] = $validation;
				self::saveHeartRateToSession($_POST['heartrate']);
				self::saveMeasurementToSession($_POST['measurement']);
				Logger::undefErr(array_values($validation));
				Request::hlexit("?measurement&new&person_id=$m->person_id&measurement_type=heartrate");
			} else {
				$m->setMeasurement($hr);
				self::saveMeasurement($m);
				Request::hlexit("?measurements&view&person_id=$m->person_id&measurement_type=heartrate");
			}
		}

		# WEIGHT
		if(self::isPersonMeasurementAdd('Weight')) {
			$m = self::getMeasurementFromPost();
			$w = Weight::fromForm($_POST['weight'], array('value'));
			if($validation = $w->hasValidationErrors()) {
				$_SESSION['weight_validation'] = $validation;
				self::saveWeightToSession($_POST['weight']);
				self::saveMeasurementToSession($_POST['measurement']);
				Logger::undefErr(array_values($validation));
				Request::hlexit("?measurement&edit&person_id=$m->person_id&measurement_type=weight");
			} else {
				$m->setMeasurement($w);
				self::saveMeasurement($m);
				Request::hlexit("?measurements&view&person_id=$m->person_id&measurement_type=weight");
			}
		}

# -----------------------------------------
# -------------- USER ---------------------
# -----------------------------------------

# -------------- NEW ----------------------
		if (isset($_REQUEST['newuser']) && isset($_POST['user'])) {
			$user = $_POST['user'];
			$_SESSION['user'] = $user;
			if ($user['password'] == $user['password_confirm']) {
				if (Login::createUser($user['login'], $user['password'], $user['email'], $user['user_type_id'])) {
					Request::hlexit("./");
				} else {
					Logger::err("UNDEF", t("User not created. Error!"));
					Request::hlexit("?registration");
				}
			} else {
				Logger::err('PASS_MATCH', t("Passwords don't match!"));
				Request::hlexit("?registration");
			}
		}

# -------------- ACTIVATE -----------------
		if (isset($_REQUEST['activate']) && isset($_REQUEST['id']) && isset($_REQUEST['aid'])) {
			if(User::activate($_REQUEST['id'], urldecode($_REQUEST['aid']))) {
				Logger::info(t("User activated!"));
			} else {
				Logger::undefErr(t("User not activated!"));
			}

			Request::hlexit("./");
		} 

	}

	private static function saveTemperatureToSession($temp) {
		$_SESSION['temperature'] = array('value' => $temp['value']);
	}

	private static function saveHeartRateToSession($hr) {
		$_SESSION['heartrate'] = $hr;
	}

	private static function saveWeightToSession($h) {
		$_SESSION['weight'] = $h;
	}

	private static function saveMeasurementToSession($m) {
		$_SESSION['measurement'] = $m;
	}

	private static function getMeasurementFromPost() {
		$m = Measurement::fromForm($_POST['measurement'], array('person_id', 'measurement_type'));
		$m->setOnDateTime($_POST['measurement']['date'], $_POST['measurement']['time']);
		return $m;
	}

	private static function saveMeasurement($m) {
			if($m->save())
				Logger::info($m->measurement_type." saved");
			else Logger::error('Could not save '.$m->measurement_type);
	}

	public static function loadFromSession($what) {
		if (isset($_SESSION[$what])) return $_SESSION[$what];
	}

	public static function prepareMeasurement() {
		$m = new Measurement();
		$m->person_id = $_GET['person_id'];
		$m->setNow();
		return $m;
	}

	private static function savePersonToSession($p) {
		$_SESSION['person'] = $p;
	}

	public static function extractDate($values) {
		return mktime(0, 0, 0, $values['m'], $values['d'], $values['Y']);
	}

	public static function extractDatetime($date, $time) {
		return mktime($time['H'], $time['i'], 0, $date['m'], $date['d'], $date['Y']);
	}

}

