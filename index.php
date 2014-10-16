<?

include 'includes.php';

DB::connect(); 
Login::CheckLogin();
Req::process();
$language = UserSession::language();

?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Measurements</title>
	</head>
<body>

<? #----------- LOGGER ------------------- ?>
<?= LoggerHtmlBlock::messages() ?>

<? #----------- DEBUG ------------------- ?>
<? print_session_debug() ?>

<?
if (!Login::isLoggedIn()) {
	LoginHtmlBlock::login();
} else {
?>
<a href="?logout">Logout</a>

<p class="meniu">
<a href="?people&list">People<img src="media/img/zmogelis.png" /></a> <a href="?person&new">New person<img src="media/img/edit.png" /></a>
</p>

<? include_once 'sub/person.sub.php' ?>

<?
# -----------------------------------------
# -------------- REGISTER MEASUREMENTS ----
# ----------------------------------------- 

# -------------- TEMPERATURE --------------
if (Req::isPersonNewMeasurementRegister('Temperature')) {
	$m = Req::prepareMeasurement();
	$t = new Temperature();
?>

<p><b>Register person temperature</b></p> 
<form method="post" action="?measurements">
	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="measurement[measurement_type]" value="Temperature" />
	<input type="hidden" name="measurement[person_id]" value="<?= $m->person_id ?>" />
	<label for="measurement_date">Date</label> <input id="measurement_date" name="measurement[date]" type="text" value="<?= so($m->gDate()) ?>" /><br />
	<label for="measurement_time">Time</label> <input id="measurement_time" name="measurement[time]" type="text" value="<?= so($m->gTime()) ?>" /><br />

	<?= Form::validation('temperature_validation', 'value') ?>
	<label for="temperature">Temperature (&deg;C)</label><input id="temperature" name="temperature[value]" type="text" value="<?= so(s('temperature', 'value', $t->value)) ?>"/><br />

	<input type="submit" /><a href="?measurements&view&person_id=<?=$m->person_id ?>&measurement_type=temperature">Cancel</a>
</form>

<? } ?>

<?
# -------------- HEARTRATE ----------------
	
if (Req::isPersonNewMeasurementRegister('Heartrate')) {
	$m  = Req::prepareMeasurement();
	$hr = new Heartrate();
?>

<p><b>Register person heartrate</b></p> 
<form method="post" action="?measurements">

	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="measurement[measurement_type]" value="Heartrate" />
	<input type="hidden" name="measurement[person_id]" value="<?= $m->person_id ?>" />

	<label for="measurement_date">Date</label> <input id="measurement_date" name="measurement[date]" type="text" value="<?= so($m->gDate()) ?>" /><br />

	<label for="measurement_time">Time</label> <input id="measurement_time" name="measurement[time]" type="text" value="<?= so($m->gTime()) ?>" /><br />

	<?= Form::validation('heartrate_validation', 'bpm') ?>
	<label for="heartrate">Heartrate (bpm)</label><input id="heartrate" name="heartrate[bpm]" type="text" value="<?= so($hr->bpm) ?>"/><br />

	<input type="submit" /> <a href="?measurements&view&person_id=<?=$m->person_id ?>&measurement_type=heartrate">Cancel</a>
</form>

<? } ?>

<?
# -------------- WEIGHT -------------------

if (Req::isPersonNewMeasurementRegister('Weight')) {
	$m = Req::prepareMeasurement();
	$w = new Weight();
?>

<p><b>Register person weight</b></p> 

<form method="post" action="?measurements">

	<input type="hidden" name="action" value="add" />
	<input type="hidden" name="measurement[measurement_type]" value="Weight" />
	<input type="hidden" name="measurement[person_id]" value="<?= $m->person_id ?>" />

	<label for="measurement_date">Date</label> <input id="measurement_date" name="measurement[date]" type="text" value="<?= so($m->gDate()) ?>" /><br />

	<label for="measurement_time">Time</label> <input id="measurement_time" name="measurement[time]" type="text" value="<?= so($m->gTime()) ?>" /><br />

	<?= Form::validation('weight_validation', 'value') ?>
	<label for="weight">Weight (kg)</label><input id="weight" name="weight[value]" type="text" value="<?= so($w->value) ?>"/><br />

	<input type="submit" /><a href="?measurements&view&person_id=<?=$m->person_id ?>&measurement_type=weight">Cancel</a>
</form>

<? } ?>

<?
	# -----------------------------------------
	# ------ VIEW TEMPERATURE MEASUREMENTS ----
	# ----------------------------------------- 
if (Req::isPersonMeasurementsView('Temperature') && Person::exists(Request::get0('person_id'))) {
	$person  = Person::load(Request::get0('person_id'));
?>

<p><a href="?person=<?= $person->id ?>&view">Back to person<img src="media/img/zmogelis.png" /></a> <a href="?measurement&new&measurement_type=temperature&person_id=<?= $person->id ?>" >Register temperature<img src="media/img/edit.png" /></a></p>

<ul>
	<li><a href="?temperatures&list&person_id=<?= $person->id ?>">Temperatures all<img src="media/img/akiniai.png" /></a></li>
	<li><a href="?measurement&view&measurement_type=temperature&person_id=<?= $person->id ?>&period=hours">24 hours period temperature</a></li>
	<li><a href="?measurement&view&measurement_type=temperature&person_id=<?= $person->id ?>&period=days">30 days period temperature</a></li>
</ul>


<? } ?>

<?
if(Req::isPersonMeasurementsList("Temperature")) {
	$person = Person::load($_GET['person_id']);
?> 
<p>Person's <b><a href="?person=<?= so($person->id) ?>&view"><?= so($person->fullName()) ?></a></b> temperatures</p>

<a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=temperature">Back to temperatures</a>

<?
	HtmlBlock::simpleList("Temperature", $person); 
}

# -----------------------------------------
# -------------- TEMPERATURE PERIOD -------
# ----------------------------------------- 
if (Req::isMeasurementPeriodView('Temperature')) {
	//default values
	$now = $start_time = time();

	$person = Person::load(Request::get0('person_id'));
	$temperatures = array();
	$filter = NULL ;
	$phours = FALSE;
	$pdays  = FALSE;

	// 24 hours 
	if(Req::isPeriodHours()) {
		$phours = TRUE;
		if(isset($_POST['date']) && isset($_POST['time'])) {
			$start_time = Req::extractDateTime($_POST['date'], $_POST['time']);
		} else {
			$start_time = $now - 24*60*60;//24 h back
		}
		$filter = $person->temperaturesHoursFilter($start_time);
	}

	// 30 days
	if(Req::isPeriodDays()) {
		$pdays  = TRUE;
		if(isset($_POST['date'])) {
			$start_time = Req::extractDate($_POST['date']);
		} else {
			$start_time = $now-30*24*60*60;// 30 days back
		}
		$filter = $person->temperaturesDaysFilter($start_time);
	}
	$temperatures = Temperature::find($filter);

	$chart = new ChartBar(arrayV($temperatures, 'value'), 35, 42);
?>

<p><a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=temperature">Back to temperatures</a></p>

<? if(!empty($temperatures)) {
	ChartHtmlBlock::chartOut($chart,500, 500) ;
	$during = $phours ? "24 hours": "30 days";
?>


<table summary="This table shows average temperature of the person during <?= $during ?>.">
<caption>Person average temperature during <?= $during ?></caption>
	<thead>
		<tr><th>Time<th>Temperature
	</thead>
<tbody>
<?
	$str = array(); $i = 1;
	foreach($temperatures as $temp) {
		$str[] = '<tr><td>'.$temp->gr.'<td>'.$temp->value;
		$i++;
	}
	echo join('', $str);
?>
</tbody>
</table>

<? } ?>

<? if ($phours) { ?>
Choose starting time
<form method="post" action="">
	<?= Form::dateSel($start_time) ?>
	<?= Form::timeSel($start_time) ?>
	<input type="submit" />
</form>
<? } ?>

<? if ($pdays) { ?>
Choose starting date
<form method="post" action="">
	<?= Form::dateSel($start_time) ?>
	<input type="submit" />
</form>
<? } ?>

<? } ?>

<?
	# -----------------------------------------
	# ------ VIEW HEARTRATE MEASUREMENTS ------
	# ----------------------------------------- 
if (Req::isPersonMeasurementsView('Heartrate') && Person::exists(Request::get0('person_id'))) {
	$person = Person::load(Request::get0('person_id'));
?>

<p><a href="?person=<?= $person->id ?>&view">Back to person<img src="media/img/zmogelis.png" /></a> <a href="?measurement&new&measurement_type=heartrate&person_id=<?= $person->id ?>" >Register heartrate<img src="media/img/edit.png" /></a></p>

<ul>
	<li><a href="?heartrates&list&person_id=<?= $person->id ?>">Heartrates all<img src="media/img/akiniai.png" /></a></li>
	<li><a href="?measurement&view&measurement_type=heartrate&person_id=<?= $person->id ?>&period=hours">24 hours period heartrate</a></li>
	<li><a href="?measurement&view&measurement_type=heartrate&person_id=<?= $person->id ?>&period=days">30 days period heartrate</a></li>
</ul>

<? } ?>

<? 
if(Req::isPersonMeasurementsList("Heartrate")) {
	$person = Person::load($_GET['person_id']);
?>
<p>Person's <b><a href="?person=<?= so($person->id) ?>&view"><?= so($person->fullName()) ?></a></b> heartrates</p>

<a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=heartrate">Back to heartrates</a>

<?
HtmlBlock::simpleList("Heartrate", $person); 

}

# -----------------------------------------
# -------------- HEARTRATE PERIOD ---------
# ----------------------------------------- 
if (Req::isMeasurementPeriodView('Heartrate')) {

	$now = $start_time = time();
	$person = Person::load(Request::get0('person_id'));
	$filter  = NULL;
	$hrs     = array();
	$phours  = FALSE;
	$pdays   = FALSE;

	// 24 hours 
	if(Req::isPeriodHours()) {
		$phours = TRUE;
		if(isset($_POST['date']) && isset($_POST['time'])) {
			$start_time = Req::extractDateTime($_POST['date'], $_POST['time']);
		} else {
			$start_time = $now - 24*60*60;//24 h back
		}
		$filter = $person->heartRatesHoursFilter($start_time);
	}

	// 30 days
	if(Req::isPeriodDays()) {
		$pdays  = TRUE;
		if(isset($_POST['time'])) {
			$start_time = Req::extractDate($_POST['date']);
		} else {
			$start_time = $now-30*24*60*60;// 30 days back
		}
		$filter = $person->heartRatesDaysFilter($start_time);
	}

	$hrs   = Heartrate::find($filter);
	$chart = new ChartBar(arrayV($hrs, 'bpm'), 50, 170);
?>
<? if(!empty($hrs)) {
	ChartHtmlBlock::chartOut($chart, 500, 500);
	$during = ($phours ? "24 hours": "30 days");
?>

<p><a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=heartrate">Back to heartrates</a></p>

<table summary="This table shows average heartrate of the person during <?= $during ?>.">
<caption>Person's average heartrate during <?= $during ?></caption>
	<thead>
		<tr><th>Time<th>Heartrate
	</thead>
<tbody>
<?
	$str = array(); $i = 1;
	foreach($hrs as $hr) {
		$str[] = '<tr><td>'.$hr->gr.'<td>'.$hr->bpm;
		$i++;
	}
	echo join('', $str);
?>
</tbody>
</table>
<? } ?>

<? if ($phours) { ?>

Choose starting time
<form method="post" action="">
	<?= Form::dateSel($start_time) ?>
	<?= Form::timeSel($start_time) ?>
	<input type="submit" />
</form>
<? } ?>

<? if ($pdays) { ?>
Choose starting date
<form method="post" action="">
	<?= Form::dateSel($start_time) ?>
	<input type="submit" />
</form>
<? } ?>

<? } ?>


<?
	# -----------------------------------------
	# ------ VIEW WEIGHT MEASUREMENTS ---------
	# ----------------------------------------- 
if (Req::isPersonMeasurementsView('Weight') && Person::exists(Request::get0('person_id'))) {
	$person  = Person::load(Request::get0('person_id'));
?>

<p><a href="?person=<?= $person->id ?>&view">Back to person<img src="media/img/zmogelis.png" /></a> <a href="?measurement&new&measurement_type=weight&person_id=<?= $person->id ?>" >Register weight<img src="media/img/edit.png" /></a></p>

<ul>
	<li><a href="?weights&list&person_id=<?= $person->id ?>">Weights all<img src="media/img/akiniai.png" /></a></li>
	<li><a href="?measurement&view&measurement_type=weight&person_id=<?= $person->id ?>&period=months">Weights each month</a></li>
</ul>

<? } ?>

<? 
if(Req::isPersonMeasurementsList("Weight")) {
	$person = Person::load($_GET['person_id']);
?>

<p>Person's <b><a href="?person=<?= so($person->id) ?>&view"><?= so($person->fullName()) ?></a></b> weights</p>

<a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=weight">Back to weights</a>
<?

HtmlBlock::simpleList("Weight", $person); 

}
?>

<?
# -----------------------------------------
# -------------- WEIGHT PERIOD ------------
# ----------------------------------------- 
if (Req::isMeasurementPeriodView('Weight')) {
	$now = $start_time = time();
	$person = Person::load(Request::get0('person_id'));
	$weights = array();
	$filter  = NULL;

	if(isset($_POST['date'])) {
		$start_time = Req::extractDate($_POST['date']);
	} else {
		$start_time = $now - 365*24*60*60;
	}

	$filter = $person->weightsMonthsFilter($start_time);
	$ws = Weight::find($filter);

	$chart = new ChartBar(arrayV($ws, 'value'), 45, 240);
?>

<p><a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=weight">Back to weights</a></p>

<? if(!empty($ws)) {
   ChartHtmlBlock::chartOut($chart, 500, 500) ?>

<table summary="This table shows person's average weight during 30 days">
<caption>Person's average weight during months</caption>
	<thead>
		<tr><th>Time<th>Weight
	</thead>
<tbody>
<?
	$str = array(); $i = 1;
	foreach($ws as $w) {
		$str[] = '<tr><td>'.$w->gr.'<td>'.$w->value;
		$i++;
	}
	echo join('', $str);
?>
</tbody>
</table>

<? } ?>

Choose starting date
<form method="post" action="">
	<?= Form::dateSel($start_time) ?>
	<input type="submit" />
</form>

<? } ?>

<? include_once 'sub/configure.sub.php'; ?>

<? if (FALSE) {
	
if(Login::is_admin()) {
	include_once 'sub/user.sub.php';
}
	
}
?>

<? } ?>

<p>2013 <a href="mailto:mzilenas@gmail.com">Marius Žilėnas</a></p>
</body>
</html>
