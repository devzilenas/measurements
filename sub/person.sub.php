<?
	# -----------------------------------------
	# ------------- LIST PEOPLE -------------
	# -----------------------------------------
if (Req::isPeopleList()) { ?>
	<p><b>People</b></p>
<?= HtmlBlock::peopleList(Login::loggedId()); ?>
<? } ?>

<?
	# -----------------------------------------
	# ------------- PERSON NEW ---------------
	# -----------------------------------------
if(Req::isPersonNew()) {
	$person = new Person();
?>

<form method="post" action="?people&new">
	<input type="hidden" name="action" value="add" />

	<?= Form::validation('person_validation', 'name') ?>
	<label for="person_name">Name</label><input type="text" name="person[name]" id="person_name" value="<?= so(s('person', 'name')) ?>" /><br /> 

	<?= Form::validation('person_validation', 'surname') ?>
	<label for="person_born">Surname</label><input type="text" name="person[surname]" id="person_surname" value="<?= so(s('person', 'surname')) ?>" /><br />

	<?= Form::validation('person_validation', 'born') ?>
	<label for="person_born">Born</label><input type="text" name="person[born]" id="person_born" value="<?= so(s('person','born')) ?>" /><br />

	<input type="submit" /> <a href="?people&list">Cancel</a>
</form> 

<? } ?>

<?
# -----------------------------------------
# ------------- PERSON VIEW --------------
# -----------------------------------------
if (Req::isPersonView()) {
	if (Person::exists($_REQUEST['person'])) { 
		$person = Person::load($_REQUEST['person']);
?>

<h1><?= so($person->to_s()) ?><a href="?person=<?= so($person->id) ?>&edit"><img src="media/img/edit.png" /></a></h1>
<p><b>Name:</b> <?= so($person->name) ?></p>
<p><b>Surname:</b> <?= so($person->surname) ?></p>
<p><b>Born:</b> <?= so($person->born) ?></p>

<p><b>Person measurements</b></p>
<ol>
	<li><a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=temperature">Temperature<img src="media/img/thermometer.png" /></a></li>
	<li><a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=heartrate">Heart rate<img src="media/img/heart.png" /></a></li>
	<li><a href="?measurements&view&person_id=<?= $person->id ?>&measurement_type=weight">Weight<img src="media/img/scales.png" /></a></li>
</ol>

<? } else { // person data doesn't exist ?>
	<a href="?person&new">Register your person data</a>
<? } ?>

<? } ?>

<?

# -----------------------------------------
# ------------- PERSON EDIT --------------
# -----------------------------------------
if(Req::isPersonEdit() && Person::exists(Request::get0('person'))) { 
	$person = Person::load(Request::get0('person'));
?>

<form method="post" action="?person=<?= $person->id ?>">
	<input type="hidden" name="action" value="update" />
	<?= Form::validation('person_validation', 'name') ?>
	<label for="person_name">Name</label><input type="text" name="person[name]" id="person_name" value="<?= so(s('person', 'name', $person->name)) ?>" /><br /> 

	<?= Form::validation('person_validation', 'surname') ?>
	<label for="person_born">Surname</label><input type="text" name="person[surname]" id="person_surname" value="<?= so(s('person', 'surname', $person->surname)) ?>" /><br />

	<?= Form::validation('person_validation', 'born') ?>
	<label for="person_born">Born</label><input type="text" name="person[born]" id="person_born" value="<?= so(s('person', 'born', $person->born)) ?>" /><br />

	<input type="submit" /> <a href="?person=<?= so($person->id) ?>&view">Cancel</a>
</form>

<? } ?> 

