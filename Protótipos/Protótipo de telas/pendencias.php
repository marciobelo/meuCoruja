<!DOCTYPE html>
<html>
<head>
	<title>Disciplinas Pendentes</title>
	<link rel="stylesheet" type="text/css" href="css/tabela.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">

</head>
<body>
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
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programação 1">AL1</span></td>
		      			<td data-label="Carga Horária">120 Hrs</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Disciplina">RD1</td>
		      			<td data-label="Carga Horária">80 Hrs</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Disciplina">ALG</td>
		      			<td data-label="Carga Horária">100 Hrs</td>
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