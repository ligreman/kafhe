<!doctype html>
<html lang="es">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">

	<link href="main.css" type="text/css" rel="stylesheet" media="screen,projection"/>

    <title>Los Juegos del Kafhe</title>
  </head>
  <body>
<p class="text-right">
	<a href="about.php" role="button" style="margin: 10px;" class="btn btn-outline-dark">Acerca de...</a>
</p>


<div class="container">
<div class="row">
	<div class="col-sm text-right">
		<img src="k256.png" />
	</div>

	<div class="col-sm">
		<form action="login.php">
			<div class="alert alert-danger" role="alert">
			  Usuario o contraseña incorrectos
			</div>
		  <div class="form-group">
			<label for="user">Usuario</label>
			<input type="text" class="form-control" id="user">
		  </div>
		  <div class="form-group">
			<label for="pass">Password</label>
			<input type="password" class="form-control" id="pass">
		  </div>
		  <div class="text-center">
			  <button type="submit" class="btn btn-success btn-lg btn-block">Entrar</button>
		  </div>
		</form>
	</div>
	<div class="col-sm"></div>
</div>
<div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
  </body>
</html>