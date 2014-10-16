<?
class ChartHtmlBlock {
	public static function chartOut($chart, $sizeX, $sizeY) {
		echo '<img src="data:image/png;base64,'.$chart->toImage64($sizeX, $sizeY).'" />';
	}
}

