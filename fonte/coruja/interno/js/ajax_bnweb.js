/*
*****************************************************
*****************DROP*DOWN*AJAX**********************
***V.200804.1***************************29/03/2008***
*/

window.onresize = resizeLista;

var ESC = 27;
var KEYUP = 38;
var KEYDN = 40;
var ENTER = 13;
var lista;
var debugs;
var oInputBox;
var listWidth = false;
var iTimerID;
var inputVelho;

/* Inibe o Enter */
  function stopRKey(evt) {
    var evt = (evt) ? evt : ((event) ? event : null);
    var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
    if ((evt.keyCode == 13) && (node.type=="text")) {return false;}
}


function cty_detalhe(el) {
  l_detalhe = document.getElementById("lista_detalhe");
  l_detalhe.style.display = "none";
  tam = lista.style.width;
  tam = parseInt(tam.replace("px",""));
  x = findPos(lista)[0] + tam + 4;


  l_detalhe.style.left = x + "px";
  l_detalhe.style.top  = findPos(lista)[1] + 'px';
  l_detalhe.innerHTML =  last_child(el).innerHTML;
  l_detalhe.style.display = "block";
}


/*
    As funcaoes abaixo servem para lidar com o DOM do Firefox,
    que adiciona espacos em branco(#text) entre os elementos
*/

function is_all_ws( nod )
{
// Use ECMA-262 Edition 3 String and RegExp features
    return !(/[^\t\n\r ]/.test(nod.data));
}

function is_ignorable( nod )
{
    return ( nod.nodeType == 8) || // A comment node
            ( (nod.nodeType == 3) && is_all_ws(nod) ); // a text node, all ws
}

function node_before( sib )
{
    while ((sib = sib.previousSibling)) {
        if (!is_ignorable(sib)) return sib;
    }
    return null;
}

function node_after( sib )
{
    while ((sib = sib.nextSibling)) {
        if (!is_ignorable(sib)) return sib;
    }
    return null;
}

function last_child( par )
{
    var res=par.lastChild;
    while (res) {
        if (!is_ignorable(res)) return res;
        res = res.previousSibling;
    }
    return null;
}

function first_child( par )
{
    var res=par.firstChild;
    while (res) {
        if (!is_ignorable(res)) return res;
        res = res.nextSibling;
    }
    return null;
}

/* Encontra posicao de um elemento na tela em pixels */
function findPos(obj) {
    var curleft = curtop = 0;
    if (obj.offsetParent) {
        curleft = obj.offsetLeft
                curtop = obj.offsetTop
                while (obj = obj.offsetParent) {
            curleft += obj.offsetLeft
                    curtop += obj.offsetTop
                }
    }
    return [curleft,curtop];
}

/*
   funcao inicial que inicializa a lista,
   deve ser executada logo apos a criacao da lista
*/
function initJs(list, input)
{
    lista = document.getElementById(list);
    oInputBox = input;

    listWidth = false; //define para que a lista se ajuste a largura do oInputBox
    resizeLista();

    //variavel com o element do iten selecionado
    listEl = first_child(lista);

    try
    {
       cty_detalhe(listEl);
    }
    catch(e)
    {}
  
    try
    {
    listEl.className = 'highlight';
    }
    catch(e)
    {}
}

/*
    posiciona a lista de acordo com o elemento textbox
    @param true   A largura da lista se ajustara ao tamano do oInputBox
           false  No define tamanho da lista
*/
function resizeLista(){
    try
    {
       lista.style.left = findPos(oInputBox)[0] + 'px';
       lista.style.top = findPos(oInputBox)[1] + oInputBox.clientHeight + 2 + 'px';

       if (listWidth) {
          lista.style.width = oInputBox.clientWidth + 'px';
       }
    }
    catch(e)
    {}

    //d123f.innerHTML += 'clientHeight: ' + oInputBox.clientHeight;
}

/*
    funcao principal, trata o evento keyup do textbox
*/
function rowDown(oTextbox, oEvent) {
    switch(oEvent.keyCode)
    {
        case ENTER: executaOnClick();
        return false;
        break;

        case ESC: escondeLista();
                return false;
        break;

        case KEYUP:
                mudaFoco('up', oTextbox);
        return false;
        break;

        case KEYDN:
                mudaFoco('down', oTextbox);
        return false;
        break;
    }
}


function mouseFoco(el)
{
    listEl.className = 'auto_complete';
    listEl = el;
    listEl.className = 'highlight';
    //d123f.innerHTML += el;
		try
		{
      cty_detalhe(listEl);
    }
		catch(e)
		{}
}

function escondeLista()
{
    lista.style.display = 'none';
    try
    {
       document.getElementById("lista_detalhe").style.display = "none";
    }
    catch(e)
    {}
    oInputBox.blur();
}
/*
    funcao que percorre elementos do DOM da lista
*/
function mudaFoco(way, el){
    switch(way){
        case 'up':
                if (node_before(listEl))
        {

            listEl.className = 'auto_complete';

            if (listEl.previousSibling.nodeName == '#text'){
                listEl = listEl.previousSibling;
            }
            listEl.previousSibling.className = 'highlight';
            listEl = listEl.previousSibling;
						try
						{
            cty_detalhe(listEl);
						}
						catch(e)
						{}

       /*
            //Carrega valor no textbox
            if (listEl.firstChild.nodeName == '#text'){
                el.value = listEl.firstChild.nextSibling.innerHTML;
            } else {
                el.value = listEl.firstChild.innerHTML;
            }

            //DEBUG
            //d123f.innerHTML += 'up ';
            //d123f.innerHTML += listEl.nodeName + ' ';
       */
        }

        break;
        case 'down':
                if (node_after(listEl))
        {

            listEl.className = 'auto_complete';
            if (listEl.nextSibling.nodeName == '#text'){
                listEl = listEl.nextSibling;
            }
            listEl.nextSibling.className = 'highlight';
            listEl = listEl.nextSibling;
            try
            {
               cty_detalhe(listEl);
            }
               catch(e)
            {}
						
						
       /*
            //Carrega valor no textbox
            if (listEl.firstChild.nodeName == '#text'){
                el.value = listEl.firstChild.nextSibling.innerHTML;
            } else {
                el.value = listEl.firstChild.innerHTML;
            }

            //DEBUG
            //d123f.innerHTML += 'down ';
            //d123f.innerHTML += listEl.nodeName + ' ';
        */
        }
        break;
    }
}

/*
    Funcao que executa a acao do form ao pressionar enter
*/
function executaOnClick()
{
    clickEl = first_child(listEl);
	
    if (document.createEvent){
    	var evt = document.createEvent("MouseEvents");
        evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
        clickEl.dispatchEvent(evt);
    }

    if (clickEl.fireEvent) {
	   clickEl.fireEvent('onclick');
    }

}








function pegaData(){
  dt=new Date();
  t=dt.getMinutes() +""+ dt.getSeconds();
  return t;
}



function Acento(linha)
{

var LinhaNova="";
var Acento="";
var Letra="";
var ComAcento="‡·„‰‚¿¡√ƒ¬ËÈÎÍ»…À ÏÌÔÓÃÕœŒÚÛıˆÙ“”’÷‘˘˙¸˚Ÿ⁄‹€Á«";
var SemAcento="aaaaaAAAAAeeeeEEEEiiiiIIIIoooooOOOOOuuuuUUUUcC";

for (var i=0;i<=linha.length-1;i++) {
  Letra=linha.substring(i,i+1);
  for (var j=0;j<=ComAcento.length-1;j++)
     {
     if (ComAcento.substring(j,j+1)==Letra)
        Letra=SemAcento.substring(j,j+1);
     }
  LinhaNova=LinhaNova+Letra;
}
Acento=LinhaNova;
return Acento;
}

function ajaxInit() {
var req;

try {
 req = new ActiveXObject("Microsoft.XMLHTTP");
} catch(e) {
 try {
  req = new ActiveXObject("Msxml2.XMLHTTP");
 } catch(ex) {
  try {
   req = new XMLHttpRequest();
  } catch(exc) {
   alert("Esse browser n„o tem recursos para uso do Ajax");
   req = null;
  }
 }
}

return req;
}
function conta_caractere(txt,caminho,view){
        if (txt.value.length > 1){
		auto_completar(txt,caminho,view);
	}
        else
        {
				  escondeLista;
          //document.getElementById("resultado").style.display = "none";
        }
}



function cty_ajax_auto_completar(cp, caminho, view, lista, id, eventoClick) {
  clearTimeout(iTimerID);
	if(eventoClick+'' == 'undefined'){eventoClick = 1};

  iTimerID = setTimeout(function() {cty_ajax_auto_completar2(cp, caminho, view, lista, id, eventoClick)} ,500);
  //cty_ajax_auto_completar2(cp, caminho, view, lista, id);
}


function cty_ajax_auto_completar2(cp, caminho, view, lista, id, eventoClick) {
 tempo = null;
 tempo = pegaData();

cp.onkeypress = stopRKey;

if ( cp.value.length < 1)
{
  try
  {
     document.getElementById("lista_detalhe").style.display = "none";
  }
  catch(e)
  {}
  document.getElementById(lista).style.display = "none";
  inputVelho = cp.value;
  }
else
{
  if (inputVelho == cp.value)
     return false;

//alert(cp+ ' ' + caminho+ ' ' + view + ' ' + lista);
   valIni = Acento(cp.value);
 //alert( valIni );
 inputVelho = cp.value;
 ajax = ajaxInit();
 if(ajax) {
   if (view == "vbibjcur0")
   {
      ajax.open("GET",caminho+"?ajax_acao=lista&ajax_view="+view+"&ajax_exp="+valIni + "&autentica=1" + "&id=" + id + "&tempo=" + tempo,true);
   }
   else
   {
     ajax.open("GET", caminho+"?ajax_acao=lista&ajax_view=" + view + "&ajax_exp=" + valIni + "&id=" + id + "&eventoClick=" + eventoClick + "&tempo=" + tempo , true);
   }
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById(lista).innerHTML = ajax.responseText;
		 document.getElementById(lista).style.display = "block";
         initJs(lista,cp);
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}
}
function insere_sobe(valor,meudiv,campo){
document.getElementById(campo).value = valor;
document.getElementById(meudiv).style.display = "none";
sobe();
}

//function insere(valor,codass){
//document.getElementById(campo).value = valor;
//document.getElementById("resultado").style.display = "none";
//sobe('http://pial/scripts/senacnew/');
//}

function apaga(lista){
document.getElementById(lista).style.display = "none";
}


function cty_desce_circulacao(caminho, id) {
 codcir = document.getElementById("rel_vbibjcir0").value;
 obra = document.getElementById("cod_financeiro").value;
 tempo = null;
 tempo = pegaData();
 ajaxcir = ajaxInit();
 if(ajaxcir) {
   ajaxcir.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=desce_circulacao&ajax_view=vbibjcir0&cod_financeiro=" + obra + "&cod_circulacao=" +codcir + "&id=" + id + "&tempo=" + tempo, true);
   ajaxcir.onreadystatechange = function() {
      if(ajaxcir.readyState == 4) {
       if(ajaxcir.status == 200) {
         document.getElementById("circula_rel").innerHTML = ajaxcir.responseText;
       } else {
         alert(ajaxcir.statusText);
       }
     }
   }
   ajaxcir.send(null);
 }
}


// Funo alterara a posio de uma lista no relacionada
function cty_altera_posicao_lista(caminho, id, view, div, sobe ) {
 cod_relacao = document.getElementById("rel_" + view).value;
 if (cod_relacao != null)
 {
   tempo = null; 
   tempo = pegaData();
   ajax_posicao = ajaxInit();
   if(ajax_posicao) {
     ajax_posicao.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=altera_posicao&sobe=" + sobe + "&ajax_view=" + view + "&id=" + id +"&cod_relacao=" + cod_relacao + "&tempo=" + tempo, true);
     ajax_posicao.onreadystatechange = function() {
        if(ajax_posicao.readyState == 4) {
         if(ajax_posicao.status == 200) {
           document.getElementById(div).innerHTML = ajax_posicao.responseText;
         } else {
           alert(ajax_posicao.statusText);
         }
       }
     }
     ajax_posicao.send(null);
   }
 }
} 


function cty_altera_posicao(caminho, id, view, div, sobe ) {
 cod_relacao = document.getElementById("rel_" + view).value;

 if (cod_relacao != '')
 {
   obra = document.form1.cod_acervo.value;
   tempo = null;
   tempo = pegaData();
   ajax_posicao = ajaxInit();
   if(ajax_posicao) {
     ajax_posicao.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=altera_posicao&sobe=" + sobe + "&ajax_view=" + view +"&cod_acervo=" + obra + "&id=" + id +"&cod_relacao=" + cod_relacao + "&tempo=" + tempo, true);
     ajax_posicao.onreadystatechange = function() {
        if(ajax_posicao.readyState == 4) {
         if(ajax_posicao.status == 200) {
           document.getElementById(div).innerHTML = ajax_posicao.responseText;
         } else {
           alert(ajax_posicao.statusText);
         }
       }
     }
     ajax_posicao.send(null);
   }
 } 
} 


function cty_sobe_circulacao(caminho, id) {
 codcir = document.getElementById("rel_vbibjcir0").value;
 obra = document.getElementById("cod_financeiro").value;
 tempo = null;
 tempo = pegaData();
 ajaxcir = ajaxInit();
 if(ajaxcir) {
   ajaxcir.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=sobe_circulacao&ajax_view=vbibjcir0&cod_financeiro=" + obra + "&cod_circulacao=" +codcir+ "&id=" + id + "&tempo=" + tempo, true);
   ajaxcir.onreadystatechange = function() {
      if(ajaxcir.readyState == 4) {
       if(ajaxcir.status == 200) {
         document.getElementById("circula_rel").innerHTML = ajaxcir.responseText;
       } else {
         alert(ajaxcir.statusText);
       }
     }
   }
   ajaxcir.send(null);
 }
}


function cty_onload_circulacao(caminho, id) {
 obra = document.getElementById("cod_financeiro").value;
 tempo = null;
 tempo = pegaData();
 ajaxcir = ajaxInit();
 if(ajaxcir) {
   ajaxcir.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=vbibjcir0&cod_financeiro=" + obra + "&id=" + id + "&tempo=" + tempo , true);
   ajaxcir.onreadystatechange = function() {
      if(ajaxcir.readyState == 4) {
       if(ajaxcir.status == 200) {
         document.getElementById("circula_rel").innerHTML = ajaxcir.responseText;
       } else {
         alert(ajaxcir.statusText);
       }
     }
   }
   ajaxcir.send(null);
 }
}



function cty_ajax_carrega_itens(caminho,id) {
 cod_acervo = document.formRes.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 volume = document.formRes.volume.value;
 cod_grupo_unidade = document.formRes.cod_grupo_unidade.value;
 check = document.getElementById("checkRegistro").checked;
 if (check){
    icheck = 1
    } else {
    icheck = 0
    }
   ajaxitens = ajaxInit();
   if(ajaxitens) {
     ajaxitens.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=carrega_itens&cod_acervo=" + cod_acervo + "&volume=" + volume + "&cod_grupo_unidade=" + cod_grupo_unidade + "&check=" + icheck + "&tempo=" + tempo + "&id=" + id, true);
     ajaxitens.onreadystatechange = function() {
       if(ajaxitens.readyState == 4) {
         if(ajaxitens.status == 200) {
              document.getElementById("divComboRegistros").innerHTML = ajaxitens.responseText;
         } else {
           alert(ajaxitens.statusText);
         }
       }
     }
     ajaxitens.send(null);
   }
}

function cty_ajax_carrega_check(caminho,id){
str1 = '<input type="checkbox" name="checkRegistro" id="checkRegistro" value="1" onClick="cty_ajax_carrega_itens';
str2 = "('" + caminho + "','" + id + "')";  
str3 = '">Registro:';
str4 = str1.concat(str2);
str5 = str4.concat(str3); 

  document.getElementById("divCheckRegistro").innerHTML = str5;
}


function cty_onload_aquisicao(caminho,id) {
   cty_ajax_curso_aqui_lista(caminho,"vbibjaqs3",id);
}

function cty_ajax_curso_aqui_lista(caminho,view,id) {
 obra = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajaxcurso = ajaxInit();
 if(ajaxcurso) {
   ajaxcurso.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_acervo=" + obra + "&tempo=" + tempo + "&id=" + id, true);
   ajaxcurso.onreadystatechange = function() {
     if(ajaxcurso.readyState == 4) {
       if(ajaxcurso.status == 200) {
         document.getElementById("curso_rel").innerHTML = ajaxcurso.responseText;
       } else {
         alert(ajaxcurso.statusText);
       }
     }
   }
   ajaxcurso.send(null);
 }
 cty_ajax_solicitante_aqui_lista(caminho,"vbibjaqs2",id);
}

function cty_ajax_solicitante_aqui_lista(caminho,view,id) {
 obra = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajaxsolicitante = ajaxInit();
 if(ajaxsolicitante) {
   ajaxsolicitante.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_acervo=" +obra+ "&tempo=" +tempo+ "&id=" +id,true);
   ajaxsolicitante.onreadystatechange = function() {
     if(ajaxsolicitante.readyState == 4) {
       if(ajaxsolicitante.status == 200) {
         document.getElementById("solicitante_rel").innerHTML = ajaxsolicitante.responseText;
       } else {
         alert(ajaxsolicitante.statusText);
       }
     }
   }
   ajaxsolicitante.send(null);
 }
 cty_ajax_pedido_aqui_lista(caminho,"vbibjaqs1",id,1);
}


function cty_ajax_pedido_aqui_lista(caminho,view,id,check) {
 obra = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajaxpedido = ajaxInit();
 if(ajaxpedido) {
   ajaxpedido.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_aqui=" +obra+ "&tempo=" +tempo+ "&id="+id+"&check="+check,true);
   ajaxpedido.onreadystatechange = function() {
     if(ajaxpedido.readyState == 4) {
       if(ajaxpedido.status == 200) {
         document.getElementById("pedidos_rel").innerHTML = ajaxpedido.responseText;
       } else {
         alert(ajaxpedido.statusText);
       }
     }
   }
   ajaxpedido.send(null);
 }
}

function cty_ajax_recebido_aqui_lista(caminho,view,id) {
 obra = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajaxrecebido = ajaxInit();
 if(ajaxrecebido) {
   ajaxrecebido.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_aqui=" +obra+ "&tempo=" +tempo+ "&id=" +id,true);
   ajaxrecebido.onreadystatechange = function() {
     if(ajaxrecebido.readyState == 4) {
       if(ajaxrecebido.status == 200) {
         document.getElementById("recebidos_rel").innerHTML = ajaxrecebido.responseText;
       } else {
         alert(ajaxrecebido.statusText);
       }
     }
   }
   ajaxrecebido.send(null);
 }
 cty_ajax_pedido_aqui_lista(caminho,"vbibjaqs1",id,0);
}


function cty_ajax_nfiscal_aqui_lista(caminho,view,id) {
 obra = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajaxrecebido = ajaxInit();
 if(ajaxrecebido) {
   ajaxrecebido.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_aqui=" +obra+ "&tempo=" +tempo+ "&id=" +id,true);
   ajaxrecebido.onreadystatechange = function() {
     if(ajaxrecebido.readyState == 4) {
       if(ajaxrecebido.status == 200) {
         document.getElementById("nfiscal_rel").innerHTML = ajaxrecebido.responseText;
       } else {
         alert(ajaxrecebido.statusText);
       }
     }
   }
   ajaxrecebido.send(null);
 }
 cty_ajax_pedido_aqui_lista(caminho,"vbibjaqs1",id,0);
}



function cty_onload_assinatura(caminho,id) {
   cty_ajax_acon_fin_lista(caminho,"vbibjfac0",id);
}

function cty_ajax_acon_fin_lista(caminho,view,id) {
 obra = document.form1.cod_financeiro.value;
 tempo = null;
 tempo = pegaData();
 ajaxacon = ajaxInit();
 if(ajaxacon) {
   ajaxacon.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_financeiro=" + obra + "&tempo=" + tempo + "&id=" + id, true);
   ajaxacon.onreadystatechange = function() {
     if(ajaxacon.readyState == 4) {
       if(ajaxacon.status == 200) {
         document.getElementById("acon_rel").innerHTML = ajaxacon.responseText;
       } else {
         alert(ajaxacon.statusText);
       }
     }
   }
   ajaxacon.send(null);
 }
 cty_ajax_curso_fin_lista(caminho,"vbibjfcu0",id);
}


function cty_ajax_curso_fin_lista(caminho,view,id) {
 obra = document.form1.cod_financeiro.value;
 tempo = null;
 tempo = pegaData();
 ajaxcurso = ajaxInit();
 if(ajaxcurso) {
   ajaxcurso.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_financeiro=" + obra + "&tempo=" + tempo + "&id=" + id, true);
   ajaxcurso.onreadystatechange = function() {
     if(ajaxcurso.readyState == 4) {
       if(ajaxcurso.status == 200) {
         document.getElementById("curso_rel").innerHTML = ajaxcurso.responseText;
       } else {
         alert(ajaxcurso.statusText);
       }
     }
   }
   ajaxcurso.send(null);
 }
}



function cty_onload(caminho, id) {
    cty_ajax_ass_lista(caminho,"vbibjxas0", id );
    // A Funo do Assunto Chama a do Autor que Chama a do Editor que Chama a do Idioma 
    // cty_ajax_aut_lista(caminho,"vbibjxau0", id);  N„o descomentar - Alcir - 27/04/2006
    // cty_ajax_edi_lista(caminho,"vbibjxed0", id);  N„o descomentar - Alcir - 27/04/2006
}



function cty_ajax_fasc_ordem_lista(caminho,view, id) {
 tempo = null;
 tempo = pegaData();
 ajaxfasc = ajaxInit();
 if(ajaxfasc) {
   ajaxfasc.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=monta_ordem_fasciculo&ajax_view=" + view + "&id=" + id + "&tempo=" + tempo, true);
   ajaxfasc.onreadystatechange = function() {
     if(ajaxfasc.readyState == 4) {
       if(ajaxfasc.status == 200) {
         document.getElementById("fasc_rel").innerHTML = ajaxfasc.responseText;
       } else {
         alert(ajaxfasc.statusText);
       }
     }
   }
   ajaxfasc.send(null);
 }
}



function cty_ajax_ass_lista(caminho,view, id) {
 obra = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajaxass = ajaxInit();
 if(ajaxass) {
   ajaxass.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_acervo=" + obra + "&id=" + id + "&tempo=" + tempo, true);
   ajaxass.onreadystatechange = function() {
     if(ajaxass.readyState == 4) {
       if(ajaxass.status == 200) {
         document.getElementById("ass_rel").innerHTML = ajaxass.responseText;
       } else {
         alert(ajaxass.statusText);
       }
     }
   }
   ajaxass.send(null);
 }
 cty_ajax_aut_lista(caminho,"vbibjxau0", id);
}


function cty_ajax_aut_lista(caminho,view, id) {
 obra = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajaxaut = ajaxInit();
 if(ajaxaut) {
   ajaxaut.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_acervo=" + obra + "&id=" + id + "&tempo=" + tempo, true);
   ajaxaut.onreadystatechange = function() {
     if(ajaxaut.readyState == 4) {
       if(ajaxaut.status == 200) {
         document.getElementById("aut_rel").innerHTML = ajaxaut.responseText;
       } else {
         alert(ajaxaut.statusText);
       }
     }
   }
   ajaxaut.send(null);
 }
 cty_ajax_edi_lista(caminho,"vbibjxed0", id);
}


function cty_ajax_edi_lista(caminho,view, id) {
 obra = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajaxedi = ajaxInit();
 if(ajaxedi) {
   ajaxedi.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_acervo=" + obra + "&id=" + id + "&tempo=" + tempo, true);
   ajaxedi.onreadystatechange = function() {
     if(ajaxedi.readyState == 4) {
       if(ajaxedi.status == 200) {
         document.getElementById("edi_rel").innerHTML = ajaxedi.responseText;
       } else {
         alert(ajaxedi.statusText);
       }
     }
   }
   ajaxedi.send(null);
 }
 cty_ajax_idi_lista(caminho,"vbibjxid0", id);
}

function cty_ajax_idi_lista(caminho,view, id) {
 obra = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajaxidi = ajaxInit();
 if(ajaxidi) {
   ajaxidi.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=relacao&ajax_view=" + view + "&cod_acervo=" + obra + "&id=" + id + "&tempo=" + tempo, true);
   ajaxidi.onreadystatechange = function() {
     if(ajaxidi.readyState == 4) {
       if(ajaxidi.status == 200) {
         document.getElementById("idi_rel").innerHTML = ajaxidi.responseText;
       } else {
         alert(ajaxidi.statusText);
       }
     }
   }
   ajaxidi.send(null);
 }
}


function cty_ajax_vbibjass0(codass,caminho,id) {
 cod_qualif = document.getElementById("cod_qualif_ass").value;
 cod_acervo = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajaxincluiass = ajaxInit();
 if(ajaxincluiass) {
   ajaxincluiass.open("GET", caminho+"?ajax_acao=ass_inc&cod_rel=" + codass + "&cod_acervo=" + cod_acervo + "&cod_qualif=" + cod_qualif + "&id=" + id + "&tempo=" + tempo , true);
   ajaxincluiass.onreadystatechange = function() {
     if(ajaxincluiass.readyState == 4) {
       if(ajaxincluiass.status == 200) {
         document.getElementById("ass_rel").innerHTML = ajaxincluiass.responseText;
       } else {
         alert(ajaxincluiass.statusText);
       }
     }
   }
   ajaxincluiass.send(null);
 }
}


function cty_ajax_vbibjbio0(mat, param1, param2, nome, eventoClick  ) {
	
	// param1 e param2 so apenas para manter compatibilidade na hora da chamada da funÁ„o pelo aplicativo do AJAX

	document.getElementById("input_biometria0").value = mat;
	document.getElementById("matricula").value = mat;

	if(eventoClick+'' == 'undefined'){eventoClick = 1};
    
	if (eventoClick == 1){ 
		document.form2.submit();
	}

}

function cty_ajax_vbibjbio01(mat, param1, param2, nome, eventoClick  ) {
   cty_ajax_vbibjbio0(mat, param1, param2, nome, eventoClick  );
}

function cty_ajax_vbibjbio02(mat, param1, param2, nome, eventoClick  ) {
   cty_ajax_vbibjbio0(mat, param1, param2, nome, eventoClick  );
}


function cty_ajax_vbibjbio1(mat, param1, param2,nome ) {
  // param1 e param2 so apenas para manter compatibilidade na hora da chamada da funÁ„o pelo aplicativo do AJAX
  document.getElementById("input_biometria1").value = nome;
  document.getElementById("matricula").value = mat;
  document.form2.submit();
}

function cty_ajax_vbibjbio11(mat, param1, param2, nome, eventoClick  ) {
   cty_ajax_vbibjbio1(mat, param1, param2, nome, eventoClick  );
}

function cty_ajax_vbibjbio12(mat, param1, param2, nome, eventoClick  ) {
   cty_ajax_vbibjbio1(mat, param1, param2, nome, eventoClick  );
}


function cty_ajax_vbibjbio2(mat, param1, param2,nome ) {
  // param1 e param2 so apenas para manter compatibilidade na hora da chamada da funÁ„o pelo aplicativo do AJAX
  document.getElementById("input_biometria2").value = nome;
  document.getElementById("matricula").value = mat;
  document.form2.submit();
}

function cty_ajax_vbibjbio21(mat, param1, param2,nome ) {
   cty_ajax_vbibjbio2(mat, param1, param2,nome );
}

function cty_ajax_vbibjbio22(mat, param1, param2,nome ) {
   cty_ajax_vbibjbio2(mat, param1, param2,nome );
}

function cty_ajax_vbibjpes0(cod_mat, param1, param2,nome ) {
  // param1 e param2 so apenas para manter compatibilidade na hora da chamada da funÁ„o pelo aplicativo do AJAX
  document.getElementById("nome_pessoa").value = nome;
  document.getElementById("cod_matricula").value = cod_mat;
}




function cty_ajax_vbibjaut0(codaut,caminho,id) {
 cod_qualif = document.getElementById("cod_qualif_aut").value;
 cod_acervo = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajaxincluiaut = ajaxInit();
 if(ajaxincluiaut) {
   ajaxincluiaut.open("GET", caminho+"?ajax_acao=aut_inc&cod_rel=" + codaut + "&cod_acervo=" + cod_acervo + "&cod_qualif=" + cod_qualif + "&id=" + id + "&tempo=" + tempo, true);
   ajaxincluiaut.onreadystatechange = function() {
     if(ajaxincluiaut.readyState == 4) {
       if(ajaxincluiaut.status == 200) {
         document.getElementById("aut_rel").innerHTML = ajaxincluiaut.responseText;
       } else {
         alert(ajaxincluiaut.statusText);
       }
     }
   }
   ajaxincluiaut.send(null);
 }
}


function cty_ajax_vbibjedi0(codedi,caminho,id) {
 cod_acervo = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajaxincluiedi = ajaxInit();
 if(ajaxincluiedi) {
   ajaxincluiedi.open("GET", caminho+"?ajax_acao=edi_inc&cod_rel=" + codedi + "&cod_acervo=" + cod_acervo + "&id=" + id + "&tempo=" + tempo, true);
   ajaxincluiedi.onreadystatechange = function() {
     if(ajaxincluiedi.readyState == 4) {
       if(ajaxincluiedi.status == 200) {
         document.getElementById("edi_rel").innerHTML = ajaxincluiedi.responseText;
       } else {
         alert(ajaxincluiedi.statusText);
       }
     }
   }
   ajaxincluiedi.send(null);
 }
}

function cty_ajax_vbibjidi0(codidi,caminho,id) {
 cod_acervo = document.form1.cod_acervo.value;
 tag = document.getElementById("tag").value;
 tempo = null;
 tempo = pegaData();
 ajaxincluiidi = ajaxInit();
 if(ajaxincluiidi) {
	   ajaxincluiidi.open("GET", caminho+"?ajax_acao=idi_inc&cod_rel=" + codidi + "&cod_acervo=" + cod_acervo + "&tag=" + tag + "&id=" + id + "&tempo=" + tempo, true);
   ajaxincluiidi.onreadystatechange = function() {
     if(ajaxincluiidi.readyState == 4) {
       if(ajaxincluiidi.status == 200) {
         document.getElementById("idi_rel").innerHTML = ajaxincluiidi.responseText;
       } else {
         alert(ajaxincluiidi.statusText);
       }
     }
   }
   ajaxincluiidi.send(null);
 }
}



function cty_ajax_vbibjcur0(codcur,caminho, id) {
 cod_financeiro = document.form1.cod_financeiro.value;
 tempo = null;
 tempo = pegaData();
 ajaxincluicur = ajaxInit();
 if(ajaxincluicur) {
   ajaxincluicur.open("GET", caminho+"?ajax_acao=cur_fin_inc&cod_rel=" + codcur + "&cod_financeiro=" + cod_financeiro + "&tempo=" + tempo + "&id=" + id, true);
   ajaxincluicur.onreadystatechange = function() {
     if(ajaxincluicur.readyState == 4) {
       if(ajaxincluicur.status == 200) {
         document.getElementById("curso_rel").innerHTML = ajaxincluicur.responseText;
       } else {
         alert(ajaxincluicur.statusText);
       }
     }
   }
   ajaxincluicur.send(null);
 }
}



// Exclus„o de Pedidos por unidade - AQUISI«√O
function ajax_excluir_pedidos_aqui(caminho,id) {
 codpedido='';
 tempo = null;
 tempo = pegaData();

 for(var i=0;i<document.form1.codigo.length;i++)
 {
   if(document.form1.codigo[i].checked==true)
   {
     codpedido=codpedido+document.form1.codigo[i].value+'-';
   }
 }

 if (codpedido == '')
 {
   codpedido=document.form1.codigo.value;
 }

 cod_aqui = document.form1.cod_aqui.value;
 ajaxPedidoExc = ajaxInit();
 if(ajaxPedidoExc) {
   ajaxPedidoExc.open("GET",caminho+"cty_ajax.exe/index?ajax_acao=pedido_aqui_exc&cod_rel=" +codpedido+ "&cod_aqui=" +cod_aqui+ "&tempo="+tempo+"&id=" +id,true);
   ajaxPedidoExc.onreadystatechange = function() {
     if(ajaxPedidoExc.readyState == 4) {
       if(ajaxPedidoExc.status == 200) {
         document.getElementById("pedidos_rel").innerHTML = ajaxPedidoExc.responseText;
       } else {
         alert(ajaxPedidoExc.statusText);
       }
     }
   }
   ajaxPedidoExc.send(null);
 }
}


// Exclus„o itens Recebidos por unidade - AQUISI«√O
function ajax_excluir_recebidos_aqui(caminho,id) {
 codrecebido='';
 tempo = null;
 tempo = pegaData();

 for(var i=0;i<document.form1.codigo.length;i++)
 {
   if(document.form1.codigo[i].checked==true)
   {
     codrecebido=codrecebido+document.form1.codigo[i].value+'-';
   }
 }

 if (codrecebido == '')
 {
   codrecebido=document.form1.codigo.value;
 }

 cod_aqui = document.form1.cod_aqui.value;
 ajaxRecebidoExc = ajaxInit();
 if(ajaxRecebidoExc) {
   ajaxRecebidoExc.open("GET",caminho+"cty_ajax.exe/index?ajax_acao=Recebido_aqui_exc&cod_rel=" +codrecebido+ "&cod_aqui=" +cod_aqui+ "&tempo="+tempo+"&id=" +id,true);
   ajaxRecebidoExc.onreadystatechange = function() {
     if(ajaxRecebidoExc.readyState == 4) {
       if(ajaxRecebidoExc.status == 200) {
         document.getElementById("recebidos_rel").innerHTML = ajaxRecebidoExc.responseText;
       } else {
         alert(ajaxRecebidoExc.statusText);
       }
     }
   }
   ajaxRecebidoExc.send(null);
 }
}


// Exclus„o Nota Fiscal - AQUISI«√O
function ajax_excluir_nfiscal_aqui(caminho,id) {
 cod_nfiscal='';
 tempo = null;
 tempo = pegaData();

 for(var i=0;i<document.form1.codigo.length;i++)
 {
   if(document.form1.codigo[i].checked==true)
   {
     cod_nfiscal=cod_nfiscal+document.form1.codigo[i].value+'-';
   }
 }

 if (cod_nfiscal == '')
 {
   cod_nfiscal=document.form1.codigo.value;
 }

 cod_aqui = document.form1.cod_aqui.value;
 ajaxRecebidoExc = ajaxInit();
 if(ajaxRecebidoExc) {
   ajaxRecebidoExc.open("GET",caminho+"cty_ajax.exe/index?ajax_acao=nfiscal_aqui_exc&cod_rel=" +cod_nfiscal+ "&cod_aqui=" +cod_aqui+ "&tempo="+tempo+"&id=" +id,true);
   ajaxRecebidoExc.onreadystatechange = function() {
     if(ajaxRecebidoExc.readyState == 4) {
       if(ajaxRecebidoExc.status == 200) {
         document.getElementById("nfiscal_rel").innerHTML = ajaxRecebidoExc.responseText;
       } else {
         alert(ajaxRecebidoExc.statusText);
       }
     }
   }
   ajaxRecebidoExc.send(null);
 }
}


// Inclus„o de Pedidos por unidade - AQUISI«√O
function cty_ajax_vbibjuni0(caminho, id) {
 cod_unidade = document.form1.cod_unidade.value;
 tempo = null;
 tempo = pegaData();
 cod_orcamentario = document.form1.cod_orcamentario.value;
 qta = document.form1.qta.value;
 cod_aqui    = document.form1.cod_aqui.value;
 ajaxincluiped = ajaxInit();
 if(ajaxincluiped) {
ajaxincluiped.open("GET",caminho+"cty_ajax.exe/index?ajax_acao=pedido_aqui_inc&cod_rel="+cod_unidade+ "&cod_aqui="+cod_aqui+"&qta="+qta+"&cod_orcamentario="+cod_orcamentario+ "&tempo="+tempo+"&id="+id,true);
   ajaxincluiped.onreadystatechange = function() {
     if(ajaxincluiped.readyState == 4) {
       if(ajaxincluiped.status == 200) {
         document.getElementById("pedidos_rel").innerHTML = ajaxincluiped.responseText;
       } else {
         alert(ajaxincluiped.statusText);
       }
     }
   }
   ajaxincluiped.send(null);
 }
}



// Inclus„o de Recebimentos por unidade - AQUISI«√O
function cty_ajax_vbibjuni1(caminho, id) {
 ajaxincluiped = ajaxInit();
 cod_unidade = document.form1.cod_unidade.value;
 tempo = null;
 tempo = pegaData();
 cod_orcamentario = document.form1.cod_orcamentario.value;
 qta = document.form1.qta.value;
 cod_aqui    = document.form1.cod_aqui.value;
 dt_rec = document.form1.dt_rec.value;
 if(ajaxincluiped) {
ajaxincluiped.open("GET",caminho+"cty_ajax.exe/index?ajax_acao=recebido_aqui_inc&cod_rel="+cod_unidade+ "&cod_aqui="+cod_aqui+"&qta="+qta+"&cod_orcamentario="+cod_orcamentario+ "&dt_rec="+dt_rec+"&tempo="+tempo+"&id="+id,true);
   ajaxincluiped.onreadystatechange = function() {
     if(ajaxincluiped.readyState == 4) {
       if(ajaxincluiped.status == 200) {
         document.getElementById("recebidos_rel").innerHTML = ajaxincluiped.responseText;
       } else {
         alert(ajaxincluiped.statusText);
       }
     }
   }
   ajaxincluiped.send(null);
 }
}


// Inclus„o de Notas Fiscais - AQUISI«√O
function cty_ajax_vbibjaqs5(caminho, id) {
 ajaxincluiped = ajaxInit();
 tempo = null;
 tempo = pegaData();
 nota_fiscal = document.form1.nota_fiscal.value;
 data_nota_fiscal = document.form1.data_nota_fiscal.value;
 fatura = document.form1.fatura.value;
 data_fatura = document.form1.data_fatura.value;
 qta = document.form1.qta.value;
 cod_aqui = document.form1.cod_aqui.value;
 valor = document.form1.valor.value;
 if(ajaxincluiped) {
ajaxincluiped.open("GET",caminho+"cty_ajax.exe/index?ajax_acao=nfiscal_aqui_inc&cod_aqui="+cod_aqui+"&qta="+qta+"&nota_fiscal="+nota_fiscal+ "&data_nota_fiscal="+data_nota_fiscal+ "&fatura="+fatura+ "&data_fatura="+data_fatura+ "&valor="+valor+"&tempo="+tempo+"&id="+id,true);
   ajaxincluiped.onreadystatechange = function() {
     if(ajaxincluiped.readyState == 4) {
       if(ajaxincluiped.status == 200) {
         document.getElementById("nfiscal_rel").innerHTML = ajaxincluiped.responseText;
       } else {
         alert(ajaxincluiped.statusText);
       }
     }
   }
   ajaxincluiped.send(null);
 }
}



// Inclus„o de Cursos na AquisiÁ„o
function cty_ajax_vbibjcur1(codcur,caminho, id) {
 cod_aqui = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajaxincluicur = ajaxInit();
 if(ajaxincluicur) {
   ajaxincluicur.open("GET", caminho+"?ajax_acao=cur_aqui_inc&cod_rel=" + codcur + "&cod_aqui=" + cod_aqui + "&tempo=" + tempo + "&id=" + id, true);
   ajaxincluicur.onreadystatechange = function() {
     if(ajaxincluicur.readyState == 4) {
       if(ajaxincluicur.status == 200) {
         document.getElementById("curso_rel").innerHTML = ajaxincluicur.responseText;
       } else {
         alert(ajaxincluicur.statusText);
       }
     }
   }
   ajaxincluicur.send(null);
 }
}


function cty_ajax_vbibjaco0(codacon,caminho, id) {
 cod_financeiro = document.form1.cod_financeiro.value;
 tempo = null;
 tempo = pegaData();
 ajaxincluiacon = ajaxInit();
 if(ajaxincluiacon) {
   ajaxincluiacon.open("GET", caminho+"?ajax_acao=acon_fin_inc&cod_rel=" + codacon + "&cod_financeiro=" + cod_financeiro + "&tempo=" + tempo + "&id=" + id, true);
   ajaxincluiacon.onreadystatechange = function() {
     if(ajaxincluiacon.readyState == 4) {
       if(ajaxincluiacon.status == 200) {
         document.getElementById("acon_rel").innerHTML = ajaxincluiacon.responseText;
       } else {
         alert(ajaxincluiacon.statusText);
       }
     }
   }
   ajaxincluiacon.send(null);
 }
}

function cty_ajax_vbibjusu0(cod_matricula,caminho,id) {

 cod_financeiro = document.formcir.cod_financeiro.value 
 tempo = null;
 tempo = pegaData();
 ajaxincluiusu = ajaxInit();
 if(ajaxincluiusu) {
   ajaxincluiusu.open("GET", caminho+"?ajax_acao=usu_cir_inc&cod_rel=" + cod_matricula + "&cod_financeiro=" + cod_financeiro + "&id=" + id + "&tempo=" + tempo, true);
   ajaxincluiusu.onreadystatechange = function() {
     if(ajaxincluiusu.readyState == 4) {
       if(ajaxincluiusu.status == 200) {
         document.getElementById("circula_rel").innerHTML = ajaxincluiusu.responseText;
       } else {
         alert(ajaxincluiusu.statusText);
       }
     }
   }
   ajaxincluiusu.send(null);
 }
}


function cty_ajax_vbibjusu1(cod_matricula,caminho,id) {
 cod_aqui = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajaxincluiusu = ajaxInit();
 if(ajaxincluiusu) {
   ajaxincluiusu.open("GET", caminho+"?ajax_acao=usu_aqui_inc&cod_rel=" + cod_matricula + "&cod_aqui=" + cod_aqui + "&id=" + id + "&tempo=" + tempo, true);
   ajaxincluiusu.onreadystatechange = function() {
     if(ajaxincluiusu.readyState == 4) {
       if(ajaxincluiusu.status == 200) {
         document.getElementById("solicitante_rel").innerHTML = ajaxincluiusu.responseText;
       } else {
         alert(ajaxincluiusu.statusText);
       }
     }
   }
   ajaxincluiusu.send(null);
 }
}



function sobe(caminho) {
 codass = document.getElementById("relacionados").value;
 cod_acervo = document.getElementById("cod_acervo").value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=ass_inc&codass=" + codass + "&cod_acervo=" + cod_acervo + "&tempo=" + tempo, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("relacionado").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}


function ajax_excluir_ass(caminho,id) {
 codass = document.getElementById("rel_vbibjxas0").value;
 cod_acervo = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=ass_exc&cod_rel=" + codass + "&cod_acervo=" + cod_acervo + "&tempo=" + tempo + "&id=" + id, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("ass_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}


function ajax_excluir_aut(caminho,id) {
 codaut = document.getElementById("rel_vbibjxau0").value;
 cod_acervo = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=aut_exc&cod_rel=" + codaut + "&cod_acervo=" + cod_acervo + "&tempo=" + tempo + "&id=" + id, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("aut_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}


function ajax_excluir_acon_fin(caminho,id) {
 codacon = document.getElementById("rel_vbibjfac0").value;
 cod_financeiro = document.form1.cod_financeiro.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=acon_fin_exc&cod_rel=" + codacon + "&cod_financeiro=" + cod_financeiro + "&tempo=" + tempo + "&id=" + id, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("acon_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}


function ajax_excluir_curso_aqui(caminho,id) {
 codcurso = document.getElementById("rel_vbibjaqs3").value;
 cod_aqui = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=curso_aqui_exc&cod_rel=" + codcurso + "&cod_aqui=" + cod_aqui + "&tempo=" + tempo + "&id=" + id, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("curso_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}


function ajax_excluir_sol_aqui(caminho,id) {
 codsolicitante = document.getElementById("rel_vbibjaqs2").value;
 cod_aqui = document.form1.cod_aqui.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET",caminho+"cty_ajax.exe/index?ajax_acao=usu_aqui_exc&cod_rel=" +codsolicitante+ "&cod_aqui=" +cod_aqui+ "&tempo="+tempo+"&id=" +id,true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("solicitante_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}


function ajax_excluir_curso_fin(caminho,id) {
 codcurso = document.getElementById("rel_vbibjfcu0").value;
 cod_financeiro = document.form1.cod_financeiro.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=curso_fin_exc&cod_rel=" + codcurso + "&cod_financeiro=" + cod_financeiro + "&tempo=" + tempo + "&id=" + id, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("curso_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}

function ajax_excluir_edi(caminho,id) {
 codedi = document.getElementById("rel_vbibjxed0").value;
 cod_acervo = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=edi_exc&cod_rel=" + codedi + "&cod_acervo=" + cod_acervo + "&tempo=" + tempo + "&id=" + id, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("edi_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}


function ajax_excluir_idi(caminho,id) {
 codidi = document.getElementById("rel_vbibjxid0").value;
 cod_acervo = document.form1.cod_acervo.value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=idi_exc&cod_rel=" + codidi + "&cod_acervo=" + cod_acervo + "&tempo=" + tempo + "&id=" + id, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("idi_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}



function ajax_excluir_circulacao(caminho,id) {
 cod_cir = document.getElementById("rel_vbibjcir0").value;
 cod_financeiro = document.getElementById("cod_financeiro").value;
 tempo = null;
 tempo = pegaData();
 ajax = ajaxInit();
 if(ajax) {
   ajax.open("GET", caminho+"cty_ajax.exe/index?ajax_acao=cir_exc&cod_rel=" + cod_cir + "&id=" + id + "&cod_financeiro=" + cod_financeiro + "&tempo=" + tempo, true);
   ajax.onreadystatechange = function() {
     if(ajax.readyState == 4) {
       if(ajax.status == 200) {
         document.getElementById("circula_rel").innerHTML = ajax.responseText;
       } else {
         alert(ajax.statusText);
       }
     }
   }
   ajax.send(null);
 }
}