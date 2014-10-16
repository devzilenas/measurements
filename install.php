<?

include 'includes.php';

class Install {

	private static function dieTNC($name) {
		return die(t("Table").' '.t($name).' '.t('not created'). mysql_error());
	}

	private static function createTablePeople() {
		$table = Person::tableName();
		mysql_query("
			CREATE TABLE IF NOT EXISTS $table (
				id      INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
				name    VARCHAR(30),
				surname VARCHAR(30),
				born    VARCHAR(10),
				user_id INTEGER)")
	   	or self::dieTNC($table);
	}

	private static function createTableMeasurements() {
		$table = Measurement::tableName();
		mysql_query("
			CREATE TABLE IF NOT EXISTS $table (
				id		         INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
				person_id        INTEGER,
				on_date_time     DATETIME,
				measurement_type VARCHAR(30),
				measurement_id   INTEGER)") 
		or self::dieTNC($table);
	}

	private static function createTableTemperatures() {
		$table = Temperature::tableName();
		mysql_query("
			CREATE TABLE IF NOT EXISTS $table (
				id         INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
				value      DECIMAL(5,3),
				unit       VARCHAR(5))")
		or self::dieTNC($table);
	} 

	private static function createTableWeights() {
		$table = Weight::tableName();
		mysql_query("
			CREATE TABLE IF NOT EXISTS $table (
				id	       INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
				value      DECIMAL(5,2),
				unit       VARCHAR(5))")
		or self::dieTNC($table);
	}

	private static function createTableHeartrates() {
		$table = Heartrate::tableName();
		mysql_query("
			CREATE TABLE IF NOT EXISTS $table (
				id	INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
				bpm INTEGER)")
		or self::dieTNC($table);
	}

# -------------- USER ---------------------
	private static function createTableUsers() {
		$table = 'users';
		mysql_query("
			CREATE TABLE IF NOT EXISTS $table (
				id      INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
				login   VARCHAR(255),
				phash   VARCHAR(32),
				sid     VARCHAR(32),
				email   VARCHAR(255),
				aid     VARCHAR(32),
				active  TINYINT(1))") or self::dieTNC($table);
	}

	private static function generateUser($login) {
		return User::fromForm( array(
					'login' => $login,
					'email' => $login.'@example.com',
					'phash' => Crypt::genPhash($login),
					'aid'   => Crypt::genAid()));
	}

	private static function afterCreateTableUsers() { 
		if (!self::userOk('demo')) {
			$user = self::generateUser('demo');
			if ($user_id = $user->insert()) {
				Logger::info('Created user - login: demo, password: demo!');
				User::activate($user_id, $user->aid);
			}
		}
	}

	public static function createTables() {

		self::createTableUsers();
		self::afterCreateTableUsers();

		self::createTablePeople();

		self::createTableMeasurements();

		self::createTableTemperatures(); 
		self::createTableWeights();
		self::createTableHeartrates();
	}

	public static function loadData() {

	}

    public static function userOk($login) {
		$filter = User::newFilter();
		$filter->setWhere(array('User.login' => $login));
		$filter->setLimit(1);
		return count(User::find($filter)) > 0;
	}

}

?>

<h1><?= t("Installation"); ?></h1>
	<p>Connection with database <b><?= Config::$DB_NAME ?></b>
<? 
	if (DB::connect()) echo 'WORKS';
	else die("DOESN'T WORK");
?>
	</p>

	<? Install::createTables(); ?>

	<p>
	<? if (Install::userOk('demo')) { ?>
		Demo user account <b>name</b>- demo, <b>password</b>- demo</b>
	<? } else { ?>
		<b>No demo user exists!</b>
	<? } ?>
	</p>

<a href="./">Start using</a>

