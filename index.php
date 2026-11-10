<?php
	include("config/connection.php");
	include('scripts/login.php');
?>

<!doctype html>
<html style='background-color:#0F3057;'>
	<head>
		<title>Pricon - EPRPO</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">

	</head>

	<body style='background-color:#0F3057;'>
		<div class="container">
			<div class="row">
				<div class="col-sm-offset-4 col-sm-4">
					<!-- <img class='img-responsive' src="resources/logo.png?v=2" style='margin:auto'> Orig code commented by JD as of 02-05-2025-->
					<div class="image-container" style="width: 100%; height: 120px; position: relative;">
						<img class='img-responsive' src="resources/img/eprpo_v3.png" style='position: absolute;top: -140px;left: 50px;'>
					</div>

					<br>
					<form action='index.php' method='post' class='form-horizontal'>
						<div class="form-group">
							<label for='username-input' class='control-label col-sm-3' style='color:#fff'>Username</label>
							<div class="col-sm-9">
								<input type='text' name='username' class='form-control' value="<?php if(isset($_SESSION['username'])){echo $_SESSION['username'];}?>">
							</div>
						</div>
						<div class="form-group">
							<label for='password-input' class='control-label col-sm-3' style='color:#fff'>Password</label>
							<div class="col-sm-9">
								<input type='password' name='password' id='password-input' class='form-control'>
							</div>
						</div>
						<input type='hidden' name='sometext' value="<?php echo $salt; ?>">
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<button class='btn btn-default col-xs-12 mybtn btn-blue1' name='submitbtn' type='submit'>SIGN IN</button>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<div id='errormsg' class='text-center'><?php echo $errormsg; ?></div>
							</div>
						</div>

					</form>

				</div>
			</div>
		</div>
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script type="text/javascript">
			var height = $(document).height()+'px';
			$('html, body').css({height:'90%'});
			$('.container').css({ height:'100%', width:'100%',display:'table'});
			$('.row').css({display: 'table-cell',height:'100%',verticalAlign:'middle'});
		</script>
	</body>
</html>