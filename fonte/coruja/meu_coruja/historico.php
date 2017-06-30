<?php 
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('location:index.php');
    }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Histórico</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/tabela.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<?php include "sidebar.html"; ?>
	<div class="site-wrap">
			<h1 class="titulo-pagina">Histórico</h1>
		<h2 class="cr">CR: 8.5</h2>
		<ul class="accordion css-accordion">
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item1" />
		    <label for="item1" class="accordion-item-hd">2016.2<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd">
		    <table>
		  		<thead>
				    <tr>
				      <th>Disciplina</th>
				      <th>Nota</th>
				      <th>Situação</th>
				    </tr>
			  	</thead>
				<tbody>
		  			<tr>
                        <td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programação 1">AL1</span></td>
		      			<td data-label="Nota">6.0</td>
		      			<td data-label="Situação">Aprovado</td>
		      		</tr>
		      		<tr>
                        <td data-label="Disciplina"><span data-tooltip="Arquitetura de Computadores 1">AC1</span></td>
		      			<td data-label="Nota">3.0</td>
		      			<td data-label="Situação">Reprovado por Nota</td>
		      		</tr>
		      		<tr>
                        <td data-label="Disciplina"><span data-tooltip="Álgebra Linear">ALG</span></td>
		      			<td data-label="Nota">7.0</td>
		      			<td data-label="Situação">Reprovado por Falta</td>
		      		</tr>
		      		<tr>
                        <td data-label="Disciplina"><span data-tooltip="Redes 1">RD1</span></td>
		      			<td data-label="Nota">8.0</td>
		      			<td data-label="Situação">Aprovado</td>
		      		</tr>
		      		<tr>
                        <td data-label="Disciplina"><span data-tooltip="Matemática">MAT</span></td>
		      			<td data-label="Nota">9.0</td>
		      			<td data-label="Situação">Aprovado</td>
		      		</tr>
		      	</tbody>
		    </table>

		    </div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item2" />
		    <label for="item2" class="accordion-item-hd">2016.1<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item3" />
		    <label for="item3" class="accordion-item-hd">2015.2<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		</ul>
	</div>
</body>
</html>
	
