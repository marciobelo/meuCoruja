<html>
	<head>
	<link rel="stylesheet" type="text/css" href="css/tabela.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script>
			/*var myList=[{"name" : "abc", "age" : 50},
            {"age" : "25", "hobby" : "swimming"},
            {"name" : "xyz", "hobby" : "programming"}];
*/
		var myList=[{"nomeDisciplina":"AL1", "av1":7, "av2":5, "media":8, "avf":6, "mediaFinal":8, "professor":"Leonardo", "descricaoDisciplina":"Algoritmo e Linguagem de Programação", "faltas":10, "faltasMax":30},
		{"nomeDisciplina":"AL2", "av1":7, "av2":5, "media":8, "avf":6, "mediaFinal":8, "professor":"Leonardo", "descricaoDisciplina":"Algoritmo e Linguagem de Programação", "faltas":10, "faltasMax":30}];
// Builds the HTML Table out of myList json data from Ivy restful service.
 function buildHtmlTable() {
     var columns = addAllColumnHeaders(myList);
 
     for (var i = 0 ; i < myList.length ; i++) {
         var row$ = $('<tr/>');
         for (var colIndex = 0 ; colIndex < columns.length ; colIndex++) {
             var cellValue = myList[i][columns[colIndex]];
 
             if (cellValue == null) { cellValue = ""; }
 
             row$.append($('<td/>').html(cellValue));
         }
         $("#excelDataTable").append(row$);
     }
 }
 
 // Adds a header row to the table and returns the set of columns.
 // Need to do union of keys from all records as some records may not contain
 // all records
 function addAllColumnHeaders(myList)
 {
     var columnSet = [];
     var headerTr$ = $('<tr/>');
 
     for (var i = 0 ; i < myList.length ; i++) {
         var rowHash = myList[i];
         for (var key in rowHash) {
             if ($.inArray(key, columnSet) == -1){
                 columnSet.push(key);
                 headerTr$.append($('<th/>').html(key));
             }
         }
     }
     $("#excelDataTable").append(headerTr$);
 
     return columnSet;
 }
 </script>
	</head>
	<body onLoad="buildHtmlTable()">
		<table id="excelDataTable" border="1">
		</table>
	</body>
</html>