<?php

/**
 * SimpleCaptcha class
 */
class SimpleCaptcha {

	private $captchaPath;

	/** Width of the image */
	public $width = 200;

	/** Height of the image */
	public $height = 70;

	/** Min word length (for non-dictionary random text generation) */
	public $minWordLength = 4;

	/**
	 * Max word length (for non-dictionary random text generation)
	 */
	public $maxWordLength = 6;

	/** Background color in RGB-array */
	public $backgroundColor = array(255, 255, 255);

	/** Shadow color in RGB-array or null */
	public $shadowColor = null; //array(0, 0, 0);

	/** Horizontal line through the text */
	public $lineWidth = 2;

	/**
	 * Font configuration
	 *
	 * - font: TTF file
	 * - spacing: relative pixel space between character
	 * - minSize: min font size
	 * - maxSize: max font size
	 */
	public $fonts = array(
		'Antykwa' => array('spacing' => -3, 'minSize' => 27, 'maxSize' => 30, 'font' => 'AntykwaBold.ttf')
	);

	/** Wave configuracion in X and Y axes */
	public $Yperiod = 11;
	public $Yamplitude = 5;
	public $Xperiod = 11;
	public $Xamplitude = 5;

	/** letter rotation clockwise */
	public $maxRotation = 8;

	/**
	 * Internal image size factor (for better image quality)
	 * 1: low, 2: medium, 3: high
	 */
	public $scale = 3;

	/** Debug? */
	public $debug = false;

	/** Image format: jpeg or png */
	public $imageFormat = 'png';

	/** GD image */
	public $im;

	public function __construct() {
		$this->captchaPath = dirname(__FILE__);
	}

	public function createImage() {
		$ini = microtime(true);

		/** Initialization */
		$this->imageAllocate();

		/** Text insertion */
		$text = $this->getRandomCaptchaText();

		$fontcfg = $this->fonts[array_rand($this->fonts)];

		$this->writeText($text, $fontcfg);

		/** Transformations */
		if (!empty($this->lineWidth)) {
			$this->writeLine();
		}
		$this->waveImage();

		$this->reduceImage();


		if ($this->debug) {
			imagestring($this->im, 1, 1, $this->height - 8, "$text {$fontcfg['font']} " . round((microtime(true) - $ini) * 1000) . "ms", $this->gdFgColor
			);
		}

		/** Output */
		$this->writeImage();
		$this->cleanup();

		return $text;
	}

	/**
	 * Creates the image resources
	 */
	protected function imageAllocate() {
		// Cleanup
		if (!empty($this->im)) {
			imagedestroy($this->im);
		}

		$this->im = imagecreatetruecolor($this->width * $this->scale, $this->height * $this->scale);

		// Background color
		$this->gdBgColor = imagecolorallocate($this->im, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]
		);
		imagefilledrectangle($this->im, 0, 0, $this->width * $this->scale, $this->height * $this->scale, $this->gdBgColor);

		// Foreground color
		$this->gdFgColor = imagecolorallocate($this->im, rand(0, 155), rand(0, 155), rand(0, 155));

		// Shadow color
		if (!empty($this->shadowColor) && is_array($this->shadowColor) && sizeof($this->shadowColor) >= 3) {
			$this->GdShadowColor = imagecolorallocate($this->im, $this->shadowColor[0], $this->shadowColor[1], $this->shadowColor[2]
			);
		}
	}

	/**
	 * Random text generation
	 *
	 * @return string Text
	 */
	protected function getRandomCaptchaText($length = null) {
		if (empty($length)) {
			$length = rand($this->minWordLength, $this->maxWordLength);
		}
		$words = "1234567890";
		$text = "";
		for ($i = 0; $i < $length; $i++) {
			$text .= substr($words, mt_rand(0, 9), 1);
		}
		return $text;
	}

	/**
	 * Horizontal line insertion
	 */
	protected function writeLine() {
		$x1 = $this->width * $this->scale * .15;
		$x2 = $this->textFinalX;
		$y1 = rand($this->height * $this->scale * .40, $this->height * $this->scale * .65);
		$y2 = rand($this->height * $this->scale * .40, $this->height * $this->scale * .65);
		$width = $this->lineWidth / 2 * $this->scale;

		for ($i = $width * -1; $i <= $width; $i++) {
			imageline($this->im, $x1, $y1 + $i, $x2, $y2 + $i, $this->gdFgColor);
		}
	}

	/**
	 * Text insertion
	 */
	protected function writeText($text, $fontcfg = array()) {
		if (empty($fontcfg)) {
			// Select the font configuration
			$fontcfg = $this->fonts[array_rand($this->fonts)];
		}

		// Full path of font file
		$fontfile = $this->captchaPath . '/fonts/' . $fontcfg['font'];

		/** Increase font-size for shortest words: 9% for each glyp missing */
		$lettersMissing = $this->maxWordLength - strlen($text);
		$fontSizefactor = 1 + ($lettersMissing * 0.09);

		// Text generation (char by char)
		$x = 20 * $this->scale;
		$y = round(($this->height * 27 / 40) * $this->scale);
		$length = strlen($text);
		for ($i = 0; $i < $length; $i++) {
			$degree = rand($this->maxRotation * -1, $this->maxRotation);
			$fontsize = rand($fontcfg['minSize'], $fontcfg['maxSize']) * $this->scale * $fontSizefactor;
			$letter = substr($text, $i, 1);

			if ($this->shadowColor) {
				$coords = imagettftext($this->im, $fontsize, $degree, $x + $this->scale, $y + $this->scale, $this->GdShadowColor, $fontfile, $letter);
			}
			$coords = imagettftext($this->im, $fontsize, $degree, $x, $y, $this->gdFgColor, $fontfile, $letter);
			$x += ($coords[2] - $x) + ($fontcfg['spacing'] * $this->scale);
		}

		$this->textFinalX = $x;
	}

	/**
	 * Wave filter
	 */
	protected function waveImage() {
		// X-axis wave generation
		$xp = $this->scale * $this->Xperiod * rand(1, 3);
		$k = rand(0, 100);
		for ($i = 0; $i < ($this->width * $this->scale); $i++) {
			imagecopy($this->im, $this->im, $i - 1, sin($k + $i / $xp) * ($this->scale * $this->Xamplitude), $i, 0, 1, $this->height * $this->scale);
		}

		// Y-axis wave generation
		$k = rand(0, 100);
		$yp = $this->scale * $this->Yperiod * rand(1, 2);
		for ($i = 0; $i < ($this->height * $this->scale); $i++) {
			imagecopy($this->im, $this->im, sin($k + $i / $yp) * ($this->scale * $this->Yamplitude), $i - 1, 0, $i, $this->width * $this->scale, 1);
		}
	}

	/**
	 * Reduce the image to the final size
	 */
	protected function reduceImage() {
		// Reduzco el tama�o de la imagen
		$imResampled = imagecreatetruecolor($this->width, $this->height);
		imagecopyresampled($imResampled, $this->im, 0, 0, 0, 0, $this->width, $this->height, $this->width * $this->scale, $this->height * $this->scale
		);
		imagedestroy($this->im);
		$this->im = $imResampled;
	}

	/**
	 * File generation
	 */
	protected function writeImage() {
		if ($this->imageFormat == 'png' && function_exists('imagepng')) {
			header("Content-type: image/png");
			imagepng($this->im);
		} else {
			header("Content-type: image/jpeg");
			imagejpeg($this->im, null, 80);
		}
	}

	/**
	 * Cleanup
	 */
	protected function cleanup() {
		imagedestroy($this->im);
	}

}

?>
