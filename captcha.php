<?php

session_start();

require_once("./core/misc/Captcha.class.php");

$captcha = new SimpleCaptcha();

$text = $captcha->createImage();

$_SESSION["captcha_session_key"] = $text;

?>