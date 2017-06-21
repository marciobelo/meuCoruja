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
<?php include "sidebar.php"; ?>
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
		      <th>Professores</th>
		      <th>Faltas</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<tr>
		      <td data-label="Disciplina">AL1</td>
		      <td data-label="AV1">7.0</td>
		      <td data-label="AV2">5.0</td>
		      <td data-label="Média">8.0</td>
		      <td data-label="AVF">6.0</td>
		      <td data-label="Média Final">8.0</td>
		      <td data-label="Professor">Leonardo</td>
		      <td data-label="Faltas">10/30<input type="image" src="img/information-circular-button.png" data-toggle="modal" data-target="#myModal"></td>
		    </tr>
		    <tr>
		      <td data-label="Disciplina">ALG</td>
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
								<td>03/04/17</td>
								<td>3</td>
							</tr>
							<tr>
								<td>15/05/17</td>
								<td>6</td>
							</tr>
							<tr>
								<td>17/05/17</td>
								<td>3</td>
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