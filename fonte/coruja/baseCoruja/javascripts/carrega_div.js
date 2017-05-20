function abrirPagina(qualUrl)
{

	var url = qualUrl;
	xmlRequest.onreadystatechange = carregarDIV;
	xmlRequest.open("GET",url,true);
	xmlRequest.send(null);
	
	if (xmlRequest.readyState == 1) 
	{
		document.getElementById("resultado_pesquisa").innerHTML = "Carregando...";
	}
	return url;
}
	
function carregarDIV()
{
	if (xmlRequest.readyState == 4)
	{
		document.getElementById("resultado_pesquisa").innerHTML = xmlRequest.responseText;
	}
}


//USADO EM - CADASTRO ALUNO- NOVO_CADASTRO_ALUNO

function Reveal (it, box) 
{
	var vis = (box.checked) ? "block" : "none";
	document.getElementById(it).style.display = vis;
}

function Hide (it, box) 
{
	var vis = (box.checked) ? "none" : "none";
	document.getElementById(it).style.display = vis;
}



function showP(num)
{
	if (document.getElementById('p' + num).style.display == 'none')
	{
		eval("document.getElementById('p" + num +"').style.display = 'block'");
		if (document.getElementById('ico' + num).className == 'pmais') eval("document.getElementById('ico" + num +"').className = 'pmenos'");
	}
	else
	{
		eval("document.getElementById('p" + num +"').style.display = 'none'");
		if (document.getElementById('ico' + num).className == 'pmenos') eval("document.getElementById('ico" + num +"').className = 'pmais'");
	}
}