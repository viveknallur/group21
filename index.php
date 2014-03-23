
<?php

// some supporting files
define("ALGO_FILE", "swap_files/algorithms.txt");

include 'swap_files/current.php';
$current = getCurrentAlgo();

// an array of every LB_ALGO available
$algo_file = explode("\n", trim(file_get_contents(ALGO_FILE)));

?>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="shortcut icon" href="../../assets/ico/favicon.ico">

		<title>Haproxy hotswap framework</title>

		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="dashboard.css" rel="stylesheet">
		
		<!-- Just for debugging purposes. Don't actually copy this line! -->
		<!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		
		<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
		<script src="actions.js"></script>
		
	</head>

	<body>
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<a class="navbar-brand">HAProxy Hotswap Framework</a>
				</div>
				<!--<form method="POST" action="load_generator.php">-->
				<form id="load_generator_form">
					<ul class="nav navbar-nav navbar-right" style="padding-top:10px">
						<li id="load_progress" style="padding-right:5px; display:none">
							<div id="pbar_outerdiv" style="width: 200px; height: 35px; border: 1px solid grey; z-index: 1; position: relative; border-radius: 5px; -moz-border-radius: 5px">
								<div id="pbar_innerdiv" style="background-color:rgb(80,136,203); z-index: 2; height: 100%; width: 0%;" />
							</div>
						</li>
						<li style="padding-right:5px"><input type="number" name="rate" class="form-control" placeholder="rate (packets/s)"></li>
						<li style="padding-right:5px"><input type="number" name="duration" class="form-control" placeholder="duration (s)"></li>
						<li style="padding-right:5px"><input type="submit" class="btn btn-primary" value="Generate"></li>
					</ul>
				</form>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li class="active"><a href="#">Manual Switcher</a></li>
						<li><a href="#">Trigger Switcher</a></li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
					<h1 class="page-header">Manual Switcher</h1>
					<div id="container">
						<div id="algo_switcher" style="text-align:left; vertical-align:top; display:inline-block; padding:0 10px 0 10px">
							<form id="algo_switch_form">
								<select name="LB_ALGO" class="selectpicker">
								<?
									foreach ($algo_file as $algo) {
										echo "<option value=\"$algo\">$algo</option>";
									}
								?>
								</select>
								<input type="submit" class="submit" value="Submit" />
								<a id="algo_status">current: <? echo $current ?></a>
							</form>
						</div>
					</div>
					<a id="load_status"></a>
				</div>
			</div>
		</div>

		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>
