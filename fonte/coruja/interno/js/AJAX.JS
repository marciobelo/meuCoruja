// Função responsável de conectar a uma página externa e retornar os resultados, no nosso caso a busca_nome.php
function ajax(url,campo){
req = null;
tp=campo;
// Procura por um objeto nativo (Mozilla/Safari)
if (window.XMLHttpRequest){
	req = new XMLHttpRequest();
	req.onreadystatechange = processReqChange;
	req.open("GET",url,true);
	req.send(null);
}
// Procura por uma versão ActiveX (IE)
else if (window.ActiveXObject){
	req = new ActiveXObject("Microsoft.XMLHTTP");
	tp=campo;
	
	if (req){

	req.onreadystatechange = processReqChange;
	req.open("GET",url,true);
	req.send();
	}
	
}
}

function processReqChange(tipo){
	// apenas quando o estado for "completado"
	if (req.readyState == 4){
		// apenas se o servidor retornar "OK"
		if (req.status ==200){
			// procura pela div id="pagina" e insere o conteudo
			// retornado nela, como texto HTML
						
			switch (req.responseText){
	  
	  
			case "O CNPJ digitado ja foi cadastrado":
				document.getElementById('cnpj').value="";
				document.getElementById('cnpj').focus();
				alert(req.responseText);
							
			break;
			
			case "A inscricao estadual digitada ja foi cadastrada":
				document.getElementById('estadual').value="";
				document.getElementById('estadual').focus();
				alert(req.responseText);
			break;
			
			case "A inscricao municipal digitada ja foi cadastrada":
				document.getElementById('municipal').value="";
				document.getElementById('municipal').focus();
				alert(req.responseText);
			break;
		  }

			
		}else{
		alert("Houve um problema ao obter os dados:n" + req.statusText);
		}
	}
}

