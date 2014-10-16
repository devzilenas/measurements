<?

class HtmlBlock {

	public static function peopleList() {
		$filter = Person::newFilter(array("Person" => "*"));
		$people = new ObjSet("Person", $filter, Request::get0('page'));
		echo ObjSetHtml::makeListHeader($people, '?people&list');
		echo HtmlBlock::people($people);
	}

	private static function people($people) {
		$people->loadNextPage();
		$ps = array();
		while($person = $people->getNextObj()) 
			$ps[] = '<li><a href="?person='.$person->id.'&view">'.so($person->to_s())."</a></li>"; 
		
		echo '<ul>'.join('', $ps).'</ul>';
	}

	public static function simpleList($cl, $person) {
		$filter = call_user_func(array($person,pluralize(lcfirst($cl))."Filter"));
		$ts = new ObjSet($cl, $filter, Request::get0('page'));
		echo ObjSetHtml::makeListHeader($ts, "?".pluralize(strtolower(c2u($cl)))."&list&person_id=".$person->id);
		echo call_user_func("HtmlBlock::".pluralize(c2u($cl)), $ts);
	}

	private static function temperatures($temperatures) {
		$temperatures->loadNextPage();
		$out = array();
		while($t = $temperatures->getNextObj()) 
			$out[] = '<tr><td>'.$t->wh.'<td>'.$t->value;
		echo '<table>'.join('', $out)."</table>";
	}

	private static function simpleValues($objs) {
		$objs->loadNextPage();
		$out = array();
		while($o = $objs->getNextObj())
			$out[] = '<tr><td>'.$o->wh.'<td>'.$o->to_s();
		echo '<table>'.join('',$out)."</table>";
	}

	private static function heartrates($heartrates) {
		self::simpleValues($heartrates);
	}

	private static function weights($weights) {
		self::simpleValues($weights);
	}

	public static function altTitle($str) {
		return "alt=\"$str\" title=\"$str\"";
	}
}

