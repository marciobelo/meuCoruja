<!DOCTYPE html>
<html>
<head>
	<title>Boletim</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/tabela.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
	
	<?php include "sidebar.html"; ?>
	<script>
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					boletim = JSON.parse(this.responseText);
					document.getElementById("nomeDisciplina").innerHTML = boletim.disciplinas[0].nome;
					document.getElementById("av1").innerHTML = boletim.disciplinas[0].av1;
					document.getElementById("av2").innerHTML = boletim.disciplinas[0].av2;
					document.getElementById("media").innerHTML = boletim.disciplinas[0].media;
					document.getElementById("avf").innerHTML = boletim.disciplinas[0].avf;
					document.getElementById("mediaFinal").innerHTML = boletim.disciplinas[0].mediaFinal;
					document.getElementById("professor").innerHTML = boletim.disciplinas[0].professor;
					document.getElementById("faltas").innerHTML = boletim.disciplinas[0].faltas;
					document.getElementById("faltasMax").innerHTML=boletim.disciplinas[0].faltasMax;
				}
			};
			xmlhttp.open("GET", "endpoint_boletim.php", true);
			xmlhttp.send();
	</script>
	
	<div class="site-wrap">
	
	<h1 class="titulo-pagina">Boletim</h1>
		<table>
		  <thead>
		    <tr>
		      <th>Disciplina</th>
		      <th>AV1</th>
		      <th>AV2</th>
		      <th>Média</th>
		      <th>AVF</th>
		      <th>Média Final</th>
		      <th>Professor</th>
		      <th>Faltas</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<tr>
		      <td data-label="Disciplina"><span id="nomeDisciplina"></td>
		      <td data-label="AV1"> <span id="av1"></td>
		      <td data-label="AV2">  <span id="av2"> </td>
		      <td data-label="Média">  <span id="media"> </td>
		      <td data-label="AVF">  <span id="avf"> </td>
		      <td data-label="Média Final">  <span id="mediaFinal"></td>
		      <td data-label="Professor">  <span id="professor"></td>
		      <td data-label="Faltas">
				<span id="faltas"></span>/<span id="faltasMax"></span>
				<input type="image" src="img/information-circular-button.png" data-toggle="modal" data-target="#myModal">
			  </td>
		    </tr>
			
		    <tr>
		      <td data-label="Disciplina" id="nomeDisciplina">ALG</td>
		      <td data-label="AV1">7.0</td>
		      <td data-label="AV2">5.0</td>
		      <td data-label="Média">8.0</td>
		      <td data-label="AVF">6.0</td>
		      <td data-label="Média Final">8.0</td>
		      <td data-label="Professor">Bispo</td>
		      <td data-label="Faltas">10/30<input type="image" src="img/information-circular-button.png" data-toggle="modal" data-target="#myModal"></td>
		    </tr>
		    <tr>
		      <td data-label="Disciplina">RD1</td>
		      <td data-label="AV1">7.0</td>
		      <td data-label="AV2">5.0</td>
		      <td data-label="Média">8.0</td>
		      <td data-label="AVF">6.0</td>
		      <td data-label="Média Final">8.0</td>
		      <td data-label="Professor">M. Cláudia</td>
		      <td data-label="Faltas">10/30<input type="image" src="img/information-circular-button.png" data-toggle="modal" data-target="#myModal"></td>
		    </tr>
		    <tr>
		      <td data-label="Disciplina">AC1</td>
		      <td data-label="AV1">7.0</td>
		      <td data-label="AV2">5.0</td>
		      <td data-label="Média">8.0</td>
		      <td data-label="AVF">6.0</td>
		      <td data-label="Média Final">8.0</td>
		      <td data-label="Professor">Massillon</td>
		      <td data-label="Faltas">10/30<input type="image" src="img/information-circular-button.png" data-toggle="modal" data-target="#myModal"></td>
		    </tr>
		   </tbody>
		</table>
	</div>
	<!-- Modal -->
		<div id="myModal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

				<!-- Modal content-->
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Detalhamento de faltas</h4>
				  </div>
				  <div class="modal-body">
					<table>
						<thead>
							<tr>
							  <th>Data</th>
							  <th>Quantidade</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td data-label="Data">03/04/17</td>
								<td data-label="Quantidade">3</td>
							</tr>
							<tr>
								<td data-label="Data">15/05/17</td>
								<td data-label="Quantidade">6</td>
							</tr>
							<tr>
								<td data-label="Data">17/05/17</td>
								<td data-label="Quantidade">3</td>
							</tr>

						</tbody>
					</table>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
				  </div>
				</div>

		  </div>
		</div>
</body>
</html>