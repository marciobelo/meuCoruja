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
        <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
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
                            detalheFaltas = JSON.parse(this.responseText);
                            var tr;
                            for (var i = 0; i < detalheFaltas.length; i++) {
                                tr = $('<tr/>');
                                tr.append("<td data-label='Disciplina'>" + detalheFaltas[i].disciplina + "</td>");
                                tr.append("<td data-label='Quantidade'>" + detalheFaltas[i].quantidade + "</td>");
                                tr.append("<td data-label='Data'>" + detalheFaltas[i].data + "</td>");
                                tr.append("<td data-label='Periodo'>" + detalheFaltas[i].periodo + "</td>");
                               
                                $("#faltas").append(tr);
                            }                                              
                        }
                        
                    }

                    xmlhttp.open("GET", "endpoint_faltas.php", true);
                    xmlhttp.send();
            });
        </script>
		
        
        

</head>
<body>
	
	<?php include "sidebar.html"; ?>
    
    <div class="site-wrap">
	
	<h1>Faltas</h1>
            <table id="faltas">
                <thead>
		    <tr>
                        <th>Disciplina</th>
                        <th>Quantidade</th>
                        <th>Data</th>
                        <th>Período</th>
                      
		    </tr>
		</thead>
                <tbody>
                </tbody>
            </table>
	</div>
</body>
</html>