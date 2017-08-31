<?php
	session_start();
	if (isset($_SESSION['usuario'])) {
		header('location:boletim.php');
                
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
    <title>Meu Coruja</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
        

</head>
<body>
	
	<div class="wrapper">
            <div id="result"></div>
            <form class="form-signin" action="autenticar_controle.php" method="post">       
		  <h2 class="form-signin-heading">Meu Coruja</h2>
		  <input type="text" class="form-control" name="nomeAcesso" placeholder="Matrícula" required="" autofocus="" />
		  <input type="password" class="form-control" name="senha" placeholder="Senha" required=""/>      
                  <br>
		  <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>   
		</form>
	</div>
	<script>
// Check browser support
if (typeof(Storage) !== "undefined") {
    // Store
    //localStorage.setItem("lastname", "Smith");
    // Retrieve
    document.getElementById("result").innerHTML = localStorage.getItem("nomeUsuario");
} else {
    document.getElementById("result").innerHTML = "Sorry, your browser does not support Web Storage...";
}
</script>
</body>
</html>