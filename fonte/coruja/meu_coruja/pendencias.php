<?php 
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('location:index.php');
    }
    header( "Content-Type: text/html; charset=ISO-8859-1");
?>
<!DOCTYPE html>
<html>
<head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Disciplinas Pendentes</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/tabela.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body class="link4">
	<?php include 'sidebar.html'; ?>
	<div class="site-wrap">

		<h1 class="titulo-pagina">Pendências</h1>
		<ul class="accordion css-accordion">
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item1" />
		    <label for="item1" class="accordion-item-hd">1º Período<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd">
		    <table>
		  		<thead>
				    <tr>
				      <th>Disciplina</th>
				      <th>Carga Horária</th>
				    </tr>
			  	</thead>
				<tbody>
		  			<tr>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de ProgramaÃ§Ã£o 1">AL1</span></td>
		      			<td data-label="Carga HorÃ¡ria">120 Hrs</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Disciplina">RD1</td>
		      			<td data-label="Carga HorÃ¡ria">80 Hrs</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Disciplina">ALG</td>
		      			<td data-label="Carga HorÃ¡ria">100 Hrs</td>
		      		</tr>
		      	</tbody>
		    </table>

		    </div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item2" />
		    <label for="item2" class="accordion-item-hd">2º Período<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item3" />
		    <label for="item3" class="accordion-item-hd">3º Período<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item2" />
		    <label for="item2" class="accordion-item-hd">4º Período<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item2" />
		    <label for="item2" class="accordion-item-hd">5º Período<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		</ul>
	</div>
</body>
</html>