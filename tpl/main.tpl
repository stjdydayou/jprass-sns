<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title><{jprass_config name="site.title"}>-首页-Powered by JPrass</title>
        <link type="text/css" href="/css/style.css" rel="stylesheet">
    </head>
    <body>
		<div class="wrapper header">
			<a href="/" class="logo">LOGO</a>
			<ul>
				<li><a href="menu">设为首页</a></li>
				<li><a href="menu">注册</a></li>
				<li><a href="menu">反馈</a></li>
			</ul>
		</div>
		<div class="clear"></div>
		<div class="wrapper">
			<form action="" name="login-form" id="login-form" method="post">
				<div class="login-box">
					<div class="radiusimg">
						<div class="shadow"></div>
						<div class="pic"><img src="/images/person.jpg" id="personhead" width="100" height="100" style="margin-top: 0px;"></div>
					</div>
					<dl>
						<dd><input type="text" name="email" class="input-text" placeholder="请输入登录名" tabindex="1"></dd>
						<dd><input type="password" name="password" class="input-text" placeholder="请输入密码" tabindex="2"></dd>
					</dl>
					<div class="clear"></div>
					<label title="为了确保您的信息安全，请不要在网吧或者公共机房勾选此项！" class="autoLogin">
						<input type="checkbox" name="autoLogin" id="autoLogin" value="true" tabindex="4">
						下次自动登录
					</label>
					<a href="javascript:;" class="getpassword">忘记密码？</a>
					<div class="clear"></div>
					<input type="submit" id="login" class="input-submit login-btn" stats="loginPage_login_button" value="登录" tabindex="5">
					<input type="button" onclick="window.location = '/'" id="regnow" class="login-btn regbutton" value="注册" tabindex="6">
				</div>
			</form>
			<div class="main-column">

			</div>
		</div>
		<div class="clear"></div>
		<div class="wrapper footer">
			<p>
				<a href="#">关于我们</a> | 
				<a href="#">联系我们</a> | 
				<a href="#">用户条款</a> | 
				<a href="#">隐私申明</a> | 
				<a href="#">加入我们</a>
			</p>
			<p>
				Powered by <a target="_blank" class="softname" href="http://www.jprass.com/">JPrass SNS</a> 1.0-beta & 
				<a target="_blank" href="http://www.jprass.com/" title="JPrass开源社区">JPrass.com</a>  
				Copyright &COPY; 2015 <a target="_blank" href="#">JPrassSNS</a> 
				京ICP备09050100号
			</p>
			<p>
				<span>Processed in 0.039002 second(s)</span>
			</p>
		</div>
    </body>
</html>