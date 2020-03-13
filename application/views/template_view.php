<?php header("X-XSS-Protection: 1"); ?>

<!DOCTYPE html>

<html>

<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>phpToDo</title>
	<link href="http://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Kreon" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="/css/style.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
</head>

<body>
	<div class="container col-s4">

		<nav class="navbar navbar-light bg-light">
			<a href="/" class="navbar-brand">phpToDo</a>

			<?php
			if (!empty($_SESSION['login_status'])) {
				if ($_SESSION['login_status'] === "access_denied") {
					echo '<label class="warning">Проверьте данные учетной записи</label>';
				}
			}

			if (!empty($_SESSION['admin']) && $_SESSION['admin'] === "true") {
				echo '<form class="form-inline" action="/admin/logout" method="POST">';
				echo '<button class="btn btn-outline-success my-2 my-sm-0" id="logout">Выйти</button>';
				echo '</form>';
			} else {
				echo '<form class="form-inline" action="/login" method="POST">';
				echo '<div class="input-group">';
				echo '<div class="input-group-prepend">';
				echo '<span class="input-group-text" id="basic-addon1">@</span>';
				echo '</div>';
				echo '<input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" name="login">';
				echo '<input type="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" name="password">';
				echo '</div>';
				echo '<button class="btn btn-outline-success my-2 my-sm-0" id="login">Войти</button>';
				echo '</form>';
			}
			?>
		</nav>
	</div>

	<div class="container col-s4">
		<?php include 'application/views/' . $content_view; ?>
	</div>

</body>

</html>