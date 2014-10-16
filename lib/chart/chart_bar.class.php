<?

class Bar {
	public $m_value ;
	public $m_ratio ;

	public function value() {
		return $this->m_value;
	}

	public function ratio() {
		return $this->m_ratio;
	}

	public function __construct($value) {
		$this->m_value = $value;
	}

	public function setRatio($min, $max) {
		$this->m_ratio = ($this->value()-$min)/($max-$min);
	}

}

class ChartBar extends Chart {
	public $bars = array();

	public $barBorderWidth          = 1  ;     // bar border size
	public $barSpaceWidth           = 5  ;    // space between bars
	public $barSpaceForXaxisPercent = 0.1; // Percent from image size
	public $barSpaceForYaxisPercent = 0.1; // Percent from image size

	public $yMin  ; 
	public $yMax  ;

	public function textColor($im) {
		return $this->colorGreen($im);
	}

	public function barColor($im) {
		return $this->colorBlue($im);
	}

	public function __construct(array $data, $yMin, $yMax) {
		$this->setyMin($yMin);
		$this->setyMax($yMax);
		$this->makeBars($data, $yMin, $yMax);
		$this->yMin = $yMin;
		$this->yMax = $yMax;
	}

	private static function barHeight($maxHeight, $bar) {
		return $maxHeight * $bar->ratio();
	}

	public function makeBars($data, $min, $max) {
		$this->bars = array();
		foreach($data as $value) {
			$b = new Bar($value);
			$b->setRatio($min, $max);
			$this->bars[] = $b;
		}
	}

	// TODO: simplify
	public function toImage64($sizeX, $sizeY) { //size
		$im = imagecreatetruecolor($sizeX, $sizeY) or die("Can not create image");

		$xAxisSpace    = $sizeX * $this->barSpaceForXaxisPercent;
		$barSpaceSizeY = $sizeY - $xAxisSpace - $this->ySpace;

		$baseY = $sizeY - $xAxisSpace;
		$baseX = $sizeX * $this->barSpaceForYaxisPercent; // space left for labels on y axis
		$x0 = $baseX;
		$y0 = $baseY;

		$xAxisLength = $sizeX - $sizeX*$this->axisXEnd - $baseX ;
		$barWidth    = $xAxisLength/count($this->bars) - $this->barSpaceWidth*(count($this->bars)-1); //in pixels
		$i = 0; 
		foreach($this->bars as $bar) {
			$barStartX = $baseX + ($barWidth+$this->barSpaceWidth*($i == 0 ? 0 : 1))*$i;
			$barEndX   = $barStartX + $barWidth;
			$barStartY = $y0 - $barSpaceSizeY * $bar->ratio(); 
			$barEndY   = $y0 ;

			//draw bar
			imagefilledrectangle($im, $barStartX, $barStartY, $barEndX, $barEndY, $this->barColor($im));

			//draw border
			imagerectangle($im, $barStartX+$this->barBorderWidth, $barStartY+$this->barBorderWidth, $barEndX-$this->barBorderWidth, $barEndY-$this->barBorderWidth, $this->colorGray($im));
			
			// draw bar values labels
			imagestring($im, $this->fontSize, $barStartX+1, $barStartY-5*$this->fontSize, sprintf("%.3f", $bar->value()), $this->textColor($im));

			// draw x axis labels
			imagestring($im, $this->fontSize, $barStartX, $barEndY+$this->axisThickness, $i+1, $this->textColor($im));

			$i++;
		}

		//draw axis
		$axisColor = self::colorWhite($im);
		imagesetthickness($im, $this->axisThickness);

		//draw x
		imageline($im, $x0, $y0, $sizeX*(1-$this->axisXEnd), $y0, $axisColor); 

		//draw y
		imageline($im, $x0, $y0, $x0, $sizeY*$this->axisYEnd, $axisColor); 

		// draw y axis values grad
		$stepHeight = $barSpaceSizeY / $this->ySteps;
		$stepSize   = ($this->yMax - $this->yMin)/$this->ySteps;
		
		for($i=0; $i < $this->ySteps ; $i++) {
			$val = $this->yMin + $i*$stepSize;
			$h = self::barHeight($barSpaceSizeY, $bar);
			imagestring($im, $this->fontSize, $x0-10*$this->fontSize, $y0-$stepHeight*$i, sprintf("%.1f",$val), $this->textColor($im));
			imageline($im, $x0, $y0-$stepHeight*$i, $x0+5, $y0-$stepHeight*$i, $axisColor);
		}

		ob_start();
		imagepng($im);
		$image_data = ob_get_contents();
		ob_end_clean();
		imagedestroy($im);

		return base64_encode($image_data); 
	}

}

