<!DOCTYPE html>
<html>
<head>
	<title>Minha Grade Horária</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/tabela.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
	<?php include 'sidebar.html'; ?>
	<div class="site-wrap">
		<h1 class="titulo-pagina">Grade Horária</h1>
		<ul class="accordion css-accordion">
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item1" />
		    <label for="item1" class="accordion-item-hd">Segunda<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd">
		    <table>
		  		<thead>
				    <tr>
				      <th>Horário</th>
				      <th>Disciplina</th>
				      <th>Sala</th>
				      <th>Professor</th>
				    </tr>
			  	</thead>
				<tbody>
		  			<tr>
		      			<td data-label="Horário">7:00</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programação 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cláudia Ferlin</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Horário">7:50</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programação 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cláudia Ferlin</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Horário">8:40</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programação 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cláudia Ferlin</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Horário">9:30</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programação 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cláudia Ferlin</td>
		      		</tr>
		      	</tbody>
		    </table>

		    </div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item2" />
		    <label for="item2" class="accordion-item-hd">Terça<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item3" />
		    <label for="item3" class="accordion-item-hd">Quarta<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item3" />
		    <label for="item3" class="accordion-item-hd">Quinta<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item3" />
		    <label for="item3" class="accordion-item-hd">Sexta<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item3" />
		    <label for="item3" class="accordion-item-hd">Sábado<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		</ul>
	</div>
</body>
</html>