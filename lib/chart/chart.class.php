<?

class Chart {

	public $data = array(); //x,y

	public $ySpace           = 10  ; //in pixels
	public $axisThickness    = 3   ;
	public $axisXEnd         = 0.05; //percent
	public $axisYEnd         = 0.05; //percent
	public $fontSize         = 3   ;
	public $ySteps           = 10  ;
	public $yStep            = 0.5 ;

	public static function colorWhite($im) {
		return imagecolorallocate($im, 0xFF, 0xFF, 0xFF); //white
	}

	public static function colorGray($im) {
		return imagecolorallocate($im, 0xCC, 0xCC, 0xCC); //gray
	}

	public static function colorRed($im) {
		return imagecolorallocate($im, 0xFF, 0x00, 0x00); //red
	}

	public static function colorBlue($im) {
		return imagecolorallocate($im, 0x00, 0x00, 0xFF); //blue
	}

	public static function colorGreen($im) {
		return imagecolorallocate($im, 0x00, 0xFF, 0x00); //green
	}

	public static function colorBlack($im) {
		$im = imagecolorallocate($im, 0x00, 0x00, 0x00); //black
	}

	public function setyMin($yMin) {
		$this->yMin = $yMin;
	}

	public function setyMax($yMax) {
		$this->yMax = $yMax;
	}

	private function stepsY() {
		return ($this->yMax - $this->yMin)/$this->yStep;
	}

}

