<?php if (isset($_SESSION['usuario'])) {
    header('location:endpoint_side_bar.php');
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Boletim</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/login.css">

</head>
<body>
	
	<div class="wrapper">
            <form class="form-signin" action="autenticar_controle.php" method="post">       
		  <h2 class="form-signin-heading">Meu Coruja</h2>
		  <input type="text" class="form-control" name="nomeAcesso" placeholder="Matrícula" required="" autofocus="" />
		  <input type="password" class="form-control" name="senha" placeholder="Senha" required=""/>      
                  <br>
		  <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>   
		</form>
	</div>
	
</body>
</html>