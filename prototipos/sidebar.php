	<ul class="navigation">
	<li class="nav-item">
		<img class="img-circle" src="img/alunoTeste.png">
		<span class="span-info-usuario" id="nomeUsuario"></span>
		<span class="span-info-usuario" id="curso" ></span>
		<span class="span-info-usuario" id="matricula"></span>
		</li>
	<li class="nav-item"><a href="index.php">Boletim</a></li>
	<li class="nav-item"><a href="grade.php">Minha Grade</a></li>
	<li class="nav-item"><a href="historico.php">Histórico</a></li>
	<li class="nav-item"><a href="pendencias.php">Pendências</a></li>
	<li class="nav-item"><a href="#">Atividades Complementares</a></li>
</ul>

<input type="checkbox" id="nav-trigger" class="nav-trigger" />
<label for="nav-trigger"></label>

		
		<script>

			var xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					myObj = JSON.parse(this.responseText);
					document.getElementById("nomeUsuario").innerHTML = myObj.nome;
					document.getElementById("curso").innerHTML = myObj.curso;
					document.getElementById("matricula").innerHTML = myObj.matricula;
				}
			};
			xmlhttp.open("GET", "autenticacao.php", true);
			xmlhttp.send();

		</script>
		
		