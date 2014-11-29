
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>登录到-<{jprass_config name="site.title"}>-Powered by JPrass</title>
        <link type="text/css" href="Css/admin.css" rel="stylesheet">
        <script type="text/javascript" src="../Script/jquery.min.js"></script>
        <script type="text/javascript" src="../Script/jquery.form.js"></script>
		<script src="../Script/jquery.form-validator.js"></script>
        <style type="text/css">
            .form-login {
                position:absolute;top: 20%;left:45%;
                background: #f7fbe9;
                color:#333;
                width:450px;
                margin: 0 0 0 -167px;
                padding-bottom:30px;
                padding: 19px 29px 29px;
                background-color: #fff;
                border: 1px solid #e5e5e5;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-login h2{
                text-align: center;
            }
        </style>
        <script type="text/javascript">
			$(document).ready(function() {
				$.validate();
				$('#loginName').focus();
				$("#ajax-submit-form").ajaxForm({
					beforeSubmit: function() {
						$("#validator-msg").hide();
					},
					dataType: "json",
					success: function(json) {
						if (json.state) {
							window.location.href = json.location;
						} else {
							$("#loginPwd").val("");
							$("#captcha").val("");
							captcha();
							$("#validator-msg").html(json.message).show().addClass("validator-error");
						}
					}
				});
			});
			function captcha() {
				$('#captchaImage').attr('src', '../captcha.php?_=' + Math.random());
			}
        </script>
    </head>
    <body>
        <div class="container">
            <form class="form-login form form-aligned" id="ajax-submit-form" action="index.php?c=login" method="post">
                <h2><image src="Css/logo.png" /></h2>
                <div class="control-group">
                    <label>用户名</label>
                    <div class="controls">
						<input type="text" name="loginName" id="loginName" data-rule="用户名:username" class="large" />
					</div>
                </div>
                <div class="control-group">
                    <label>密码</label>
                    <div class="controls">
						<input type="password" name="loginPwd" id="loginPwd" data-rule="密码:password" class="large" />
					</div>
				</div>
                <div class="control-group">
                    <label>验证码</label>
                    <div class="controls"><input type="text" name="captcha" data-rule="验证码:captcha" data-rule-regexp="^([0-9]{4,6})$" id="captcha" class="mini" />
						<a href="javascript:captcha();">看不清？换一张</a>
					</div>
				</div>
                <div class="control-group">
                    <label>&nbsp;</label><img id="captchaImage" src="../captcha.php" />	
                </div>
                <div class="control-group">
                    <label>&nbsp;</label>
                    <button class="btn" id="submit-btn" type="submit">登 录</button>
                    <a href="#">忘记密码？</a>
                </div>
				<div id="validator-msg" style="display: none"></div>
            </form>
        </div>
    </body>
</html>