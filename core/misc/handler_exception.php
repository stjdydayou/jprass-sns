<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title><?php echo $exception->getMessage(); ?></title>
		<style type="text/css">
			body {
				font-family: "Lucida Grande","Lucida Sans Unicode",Tahoma,Verdana;
			}

			#error {
				background: #000;
				width: 360px;
				margin: 0 auto;
				margin-top: 100px;
				color: #efefef;
				padding: 10px;
			}

			h1 {
				padding: 10px;
				margin: 0;
				font-size: 36px;
			}

			p {
				padding: 0 20px 20px 20px;
				margin: 0;
				font-size: 12px;
			}
		</style>
	</head>
	<body>
		<div id="error">
			<h1><?php echo $exception->getCode()==0?"ERROR":$exception->getCode(); ?></h1>
			<p><?php echo $exception->getMessage();?></p>
		</div>
	</body>
</html>