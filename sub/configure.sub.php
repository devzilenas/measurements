<?
if (Req::isConfigure()) { ?>

<p><b>Configure units</b></p>

<form method="post" action="?choose_units">
	<label for="unit_system">Unit system</label>
	<select id="unit_system">
		<option value="us">U.S. units (lb, ft, F)</option>
		<option value="si">Metric units (kg, m, C)</option>
	</select>
</form>

<form method="post" action="?set_units">
	<label for="temperature_unit_config">Temperature units</label>
		<select id="temperature_unit_config" name="temperature">
			<option value="c">&deg;C</option>
			<option value="f">&deg;F</option>
			<option value="k">K</option>
		</select><br />

	<label for="weight_unit_config">Weight units</label>
		<select id="weight_unit_config" name="weight">
			<option value="kg">kilograms</option>
			<option value="lb">pounds</option>
		</select>
	<input type="submit" />
</form>
<? } ?>

