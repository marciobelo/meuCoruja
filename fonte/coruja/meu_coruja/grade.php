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
	<title>Minha Grade Hor�ria</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/tabela.css">
	<link rel="stylesheet" type="text/css" href="css/estilo.css">
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
	<?php include 'sidebar.html'; ?>
	<div class="site-wrap">
		<h1 class="titulo-pagina">Grade Hor�ria</h1>
		<ul class="accordion css-accordion">
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item1" />
		    <label for="item1" class="accordion-item-hd">Segunda<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd">
		    <table>
		  		<thead>
				    <tr>
				      <th>Hor�rio</th>
				      <th>Disciplina</th>
				      <th>Sala</th>
				      <th>Professor</th>
				    </tr>
			  	</thead>
				<tbody>
		  			<tr>
		      			<td data-label="Hor�rio">7:00</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cl�udia Ferlin</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Hor�rio">7:50</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cl�udia Ferlin</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Hor�rio">8:40</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cl�udia Ferlin</td>
		      		</tr>
		      		<tr>
		      			<td data-label="Hor�rio">9:30</td>
		      			<td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
		      			<td data-label="Sala">H1</td>
		      			<td data-label="Professor">Cl�udia Ferlin</td>
		      		</tr>
		      	</tbody>
		    </table>

		    </div>
		  </li>
		  <li class="accordion-item">
		    <input class="accordion-item-input" type="checkbox" name="accordion" id="item2" />
		    <label for="item2" class="accordion-item-hd">Ter�a<span class="accordion-item-hd-cta">&#9650;</span></label>
                    <div class="accordion-item-bd">
                        <table>
		  		<thead>
				    <tr>
				      <th>Hor�rio</th>
				      <th>Disciplina</th>
				      <th>Sala</th>
				      <th>Professor</th>
				    </tr>
			  	</thead>
				<tbody>
                                    <tr>
                                        <td data-label="Hor�rio">7:00</td>
                                        <td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
                                        <td data-label="Sala">H1</td>
                                        <td data-label="Professor">Cl�udia Ferlin</td>
                                    </tr>
                                    <tr>
                                        <td data-label="Hor�rio">7:50</td>
                                        <td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
                                        <td data-label="Sala">H1</td>
                                        <td data-label="Professor">Cl�udia Ferlin</td>
                                    </tr>
                                    <tr>
                                        <td data-label="Hor�rio">8:40</td>
                                        <td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
                                        <td data-label="Sala">H1</td>
                                        <td data-label="Professor">Cl�udia Ferlin</td>
                                    </tr>
                                    <tr>
                                        <td data-label="Hor�rio">9:30</td>
                                        <td data-label="Disciplina"><span data-tooltip="Algoritmo e Linguagem de Programa��o 1">AL1</span></td>
                                        <td data-label="Sala">H1</td>
                                        <td data-label="Professor">Cl�udia Ferlin</td>
                                    </tr>
                                </tbody>
                        </table>
                    </div>
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
		    <label for="item3" class="accordion-item-hd">S�bado<span class="accordion-item-hd-cta">&#9650;</span></label>
		    <div class="accordion-item-bd"></div>
		  </li>
		</ul>
	</div>
</body>
</html>