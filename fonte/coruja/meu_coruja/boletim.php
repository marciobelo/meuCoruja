<?php 
    session_start();
    if (!isset($_SESSION['usuario'])) {
        header('location:index.php');
    }
?>
<!DOCTYPE html>
<html>
<head>
        
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Boletim</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/tabela.css">
        <link rel="stylesheet" type="text/css" href="css/estilo.css">
        
        <script>
        
            $(document).ready(function (){
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        boletim = JSON.parse(this.responseText);
                        var tr;
                        for (var i = 0; i < boletim.length; i++) {
                            tr = $('<tr/>');
                            tr.append("<td data-label='Disciplina'>" + boletim[i].nome + "</td>");
                            tr.append("<td data-label='AV1'>" + boletim[i].av1 + "</td>");
                            tr.append("<td data-label='AV2'>" + boletim[i].av2 + "</td>");
                            tr.append("<td data-label='Média'>" + boletim[i].media + "</td>");
                            tr.append("<td data-label='AVF'>" + boletim[i].avf + "</td>");
                            tr.append("<td data-label='Média Final'>" + boletim[i].mediaFinal + "</td>");
                            tr.append("<td data-label='Professor'>" + boletim[i].professor + "</td>");
                            tr.append("<td data-label='Faltas'><span>"+boletim[i].faltas+"</span>/<span>"+boletim[i].faltasMax+"</span><input type='image'  class='informacao' src='img/information-circular-button.png' data-toggle='modal' data-target='#myModal'></td>");

                            $("#boletim").append(tr);
                        }                                              
                    }
                };

                xmlhttp.open("GET", "endpoint_boletim.php", true);
                xmlhttp.send();

            });
       
        </script>

</head>
<body>
	
	<?php include "sidebar.html"; ?>
    
    <div class="site-wrap">
	
	<h1>Boletim</h1>
            <table id="boletim">
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