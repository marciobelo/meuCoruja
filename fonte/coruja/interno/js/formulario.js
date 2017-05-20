/*
*****************************************************
*****************Formulario.js***********************
***V.200804.1***************************29/03/2008***
*/

var GLOBAL_DESMARCOU_CHECKS = false;
var GLOBAL_APAGOU_HIDDENS = false;


/*
function trocaClasseBotao(){
  lista = document.getElementsByClassName('inputBotao');
  lista.each(function(e){
    Event.observe(e,'mouseover',function(ev){
		ev.setAttribute('class','inputBotaoHover')
	});
    Event.observe(e,'mouseout',function(ev){
		ev.setAttribute('class','inputBotao')
	});	
  });
  
  
}
*/


function desmarcaChecks(f)
{
      for( x=0; x<f.elements.length; x++ )
      {
          if( f.elements[x].type != null )
          if( f.elements[x].type.toUpperCase() == 'CHECKBOX' )
	  {
                if( f.elements[x].checked )
                {
                        if( f.elements[x].id != null && f.elements[x].id.indexOf('check')==0 )
                        {
                                f.elements[x].checked = false;
                        }
                }

	  }
      }
}
function updateGrid(f)
{
      if ( GLOBAL_DESMARCOU_CHECKS ) return;
      desmarcaChecks(f);
      GLOBAL_DESMARCOU_CHECKS = true;
}
function updateForm(f)
{
      if ( GLOBAL_APAGOU_HIDDENS ) return;
      f.reset();
      GLOBAL_APAGOU_HIDDENS = true;
}
function ajuste(janela,Wwin,Hwin)
{
        xmax=screen.width;
        ymax=screen.height;
        topo=(xmax-Wwin)/2;
        esq=(ymax-Hwin)/2;
        janela.moveTo(topo,esq);
        janela.resizeTo(Wwin,Hwin);
        janela.focus();
}

function desabilita_botao(el)
{
  el.onclick = function(){};
}


function mudaValorCampoHidden(id,valor)
{
   $(id).value = valor;
}

function mudaAcao(url,formulario)
{
   formulario.action = url;
   formulario.submit();
   return true;
}

function mudaAction(url,qtaCodigos,formulario,checkbox,excluir,confirmacao)
{
        var quantidade = quantosChecksSelecionados( formulario, checkbox );
        if ( qtaCodigos==0 )
        {
                desmarcaChecks(formulario);
        }
        if ( qtaCodigos==1 )
        {
                if( quantidade==0 )
                {
                        alert('É necessário selecionar um dos itens');
                        return false;
                }
                else if (quantidade>1)
                {
                        alert('Selecione somente um item');
                        return false;
                }
        }
        else if( qtaCodigos==2 )
        {
                if( quantidade==0 )
                {
                        alert('É necessário selecionar pelo menos um dos itens');
                        return false;
                }
        }

        if ( excluir )
        {
                var texto = 'Tem certeza que deseja excluir ';
                if ( quantidade > 1 )
                {
                        texto += 'os itens selecionados?';
                }
                else
                {
                        texto += 'o item selecionado?';
                }
                if ( confirm(texto) )
                {
                        formulario.action = url;
                        formulario.submit();
                        return true;
                }
                else
                {
                   return false;
                }


        }

        if ( confirmacao.length > 0)
        {
             if ( confirm(confirmacao) )
             {
                     formulario.action = url;
                     formulario.submit();
                     return true;
             }

        }
        else
        {
                formulario.action = url;
                formulario.submit();
                return true;
        }
        return false;

}

function getObjeto( strId )
{
    if( document.all )
	return eval('document.all.'+strId);
    else
    	return document.getElementById(strId);
}

function esconde( id )
{
	showBox = getObjeto( id );
	showBox.style.display = 'none';
}

function mostra( id )
{
	showBox = getObjeto( id );
	showBox.style.display = 'block';
}

function mostraEsconde( id )
{
   showBox = getObjeto( id );
   if( showBox.style.display == 'block' )
       showBox.style.display = 'none';
   else
       showBox.style.display = 'block';
}


function mudaCorLinha(c,l,nr,con,atr)
{


        var obj = getObjeto('row'+ nr + l);

        if( c == null || !c.checked )
        {
           if( l%2==0 )
           {
                obj.className = 'gridLinhaPar';
                if (con == 1)
                {
                    obj.className = 'gridLinhaConsultaPar';  
                } 
                if (atr == 1)
                {
                    obj.className = 'gridLinhaAtrasoPar';  
                } 
           }
           else
           {
                obj.className = 'gridLinhaImpar';
                if (con == 1)
                {
                    obj.className = 'gridLinhaConsultaImpar';  
                } 
                if (atr == 1)
                {
                    obj.className = 'gridLinhaAtrasoImpar';  
                } 
           }
        }
        else
        {
           obj.className = 'gridLinhaSelecionada';
        }
}

function localizaEMudaCorLinha(f,l,nc,nr,con,atr)
{
        var id = 'check' + nc + l;
        var obj = null;
        for( x=0; x<f.elements.length; x++ )
        {
                if( f.elements[x].id == id )
                {
                        obj = f.elements[x];
                }
        }
        mudaCorLinha(obj,l,nr,con,atr);
}


function checaEMudaCorLinha(f,l,nc,nr,con,atr)
{
        var id = 'check' + nc + l;
        var obj = null;
        for( x=0; x<f.elements.length; x++ )
        {
                if( f.elements[x].id == id )
                {
                        f.elements[x].checked = !f.elements[x].checked;
                        obj = f.elements[x];
                }
        }
        mudaCorLinha(obj,l,nr,con,atr);
}

function selectDate ( nForm, nInput )
{
  ctyPopCalendario.fPopCalendar(document.forms[nForm][nInput]);
}

/*function selectDate ( nForm, nInput )
{
	var url = "<#logico_cgi>bnmcalendario.exe/mostra?id=<#id_operador>&form=" + nForm + "&input=" + nInput;
	var left, top;
	if(window.event)
	{
		left = event.clientX;
		top = event.clientY;
	}
	else
	{
		left = 100;
		top = 100;
	}
	var c = window.open( url, "DateSelection", "width=210,height=220,top=" + top + ",left=" + left + ",resizable=no,scrollbars=no" );
	c.focus();
}*/
function confirma(texto,url)
{
   if( confirm(texto) )
   {
     window.location.href = url;
     return true;
   }
}

function ehFloat(campo)
{
        qtvirgula = 0;
	carac = '0123456789';
	b = true;
	for( x=0; x<campo.value.length; x++)
	{
		flo = false;
		letra =	campo.value.substring(x,x+1);
		for( y=0; y<carac.length; y++)
		{
     		        if (carac.substring(y,y+1)==letra)
     		        {
        		        flo=true;
        		        break;
        	        }
                        else if( ( letra==',') && qtvirgula==0 )
                        {
                                qtvirgula++;
                                flo=true;
                                break;
                        }
		}
		if (!flo)
		{
			b = false;
			break;
		}
	}
	if(!b)
	{
		alert('Formato de numero invalido');
		campo.focus();
		return false;
	}
	else
		return true;
}



function ehFloatVP(campo,nome)
{
	carac = '0123456789,.';
	b = true;
	for( x=0; x<campo.value.length; x++)
	{
		flo = false;
		letra =	campo.value.substring(x,x+1);
		for( y=0; y<carac.length; y++)
		{
     		if (carac.substring(y,y+1)==letra)
     		{
        		flo=true;
        		break;
        	}
		}
		if (!flo)
		{
			b = false;
			break;
		}
	}
	if(b)
	{
       posp = campo.value.indexOf('.');
       posv = campo.value.indexOf(',');
       if( posv!=-1 )
       {
           if( posv+4 > campo.value.length )

               b = false;
           else if( posp!=-1 )
               if( !((posv+4)==posp) )
                   b = false;
       }
	}
	if(!b)
	{
		alert('Formato de numero inválido no campo "' + nome + '"');
		campo.focus();
		return false;
	}
	else
		return true;
}
function ehInt(campo,nome)
{
	carac = '0123456789';
	b = true;
	for( x=0; x<campo.value.length; x++)
	{
		flo = false;
		letra =	campo.value.substring(x,x+1);
		for( y=0; y<carac.length; y++)
		{
     		if (carac.substring(y,y+1)==letra)
     		{
        		flo=true;
        		break;
        	}
		}
		if (!flo)
		{
			b = false;
			break;
		}
	}
	if(!b)
	{
                alert('Formato de numero inválido no campo "' + nome + '"');
		campo.focus();
		return false;
	}
	else
		return true;
}
function campoVazio( campo )
{
	return ( campo.value=='' );
}
function dataEstaCerta( data )
{
	if( data.length != 10 ) return false;
	if( data.charAt(2) != '/' ) return false;
	if( data.charAt(5) != '/' ) return false;
	return true;
}
function alertaData( campo, nome )
{
	if( !campoVazio( campo ) )
	{
		if( !dataEstaCerta( campo.value ) )
		{
			alert( "O campo \"" + nome + "\" deve ser preenchido no formato DD/MM/AAAA" ); 
			campo.focus();
			return true;
		}		
	}
	return false;
}
function alertaCampoFile( campo, nome, ext )
{
  var nachou = true;

	if( !campoVazio( campo ) )
	{
           extensao = campo.value.substring(campo.value.length-4,campo.value.length);

		       for( x=0; x<ext.length; x++ )
					 {
                if( extensao.toUpperCase() == ext[x].toUpperCase())
                {
					           nachou = false;
      							 return nachou; 
                }
           }
				
        alert('O campo "' + nome + '" não aceita arquivos de extensão "' + extensao + '"');
        return true;
   }
}
function alertaCampo( campo, nome )
{
	if( campoVazio( campo ) )
	{
		alert('O campo "' + nome + '" é obrigatório ');
		if( campo.type!='hidden' && !campo.disabled)
			campo.focus();
		return true;
	}
	else
		return false;
}
function alertaEmail( campo )
{
	if( !ehEmail( campo ) )
	{
		alert('O e-mail digitado está incorreto ');
		if( campo.type!='hidden' )
			campo.focus();
		return true;
	}
	else
		return false;
}
function estaSelecionado( campo, nome )
{
	if( campo.selectedIndex<1 )
	{
		alert('O campo "' + nome + '" é obrigatório ');
		campo.focus();
		return false;

	}
	else
		return true;
}
function ehEmail( campo )
{
	if( campo.value==null || campo.value=='' )
		return false;
	else
		return (campo.value.length>4 && campo.value.indexOf('@')!=-1 && campo.value.indexOf('.')!=-1);
}
function marcaSeEmail( campo, radio )
{
	if ( ehEmail( campo ) )
		radio[0].checked = true;
	else
		radio[1].checked = true;
}
function getRadioByValue( radio, valor )
{
	for (x=0; x<radio.length; x++ )
	{
		if( radio[x].value==valor )
			return x;
	}
	return -1;

}
function getValorRadio( radio )
{
	for (x=0; x<radio.length; x++ )
	{
		if( radio[x].checked )
			return radio[x].value;
	}
	return null;
}
function radioMarcado( radio )
{
	x = getValorRadio( radio );
	return ( x != null );
}
function alertaRadio( radio, nome_radio )
{
	if ( !radioMarcado( radio ) )
	{
		alert( 'A opção "' + nome_radio + '" deve estar marcada' );
		radio[0].focus;
		return true;	
	}
	else
		return false;
}
function textoSoComRadio( campo, radio, nome_radio )
{
	if( alertaRadio( radio, nome_radio ) )
		campo.value = '';
}
function alertaSeDiferente( prim, sec, stPrim, stSec )
{
	if( prim.value != sec.value )
	{
		alert( 'Os campos "' + stPrim + '" e "' + stSec + '" devem ser iguais' );
		prim.focus;
		return true;	
	}
}
function alertaSeIgual( prim, sec, stPrim, stSec )
{
	if( prim.value == sec.value )
	{
		alert( 'Os campos "' + stPrim + '" e "' + stSec + '" devem ser diferentes' );
		return true;
	}
        return false;
}
function carregaNovamente( sel, nome )
{
	if( sel.selectedIndex==0 )
		alert( "Selecione um " + nome );
	else
	{
		if( window.location.href.indexOf('?')==-1 )
			x = window.location.href + "?" + sel.name + "=" + sel.options[ sel.selectedIndex ].value;
		else
			x = window.location.href + "&" + sel.name + "=" + sel.options[ sel.selectedIndex ].value;
				
		document.write( "" );
		window.location.href = x;
	}
}


function temCheckSelecionado( form, nome )
{
    for( x=0; x<form.elements.length; x++)
  	     if( form.elements[x].type=='checkbox' )
  	         if( form.elements[x].name==nome )
 		         if( form.elements[x].checked )
                            return true;
    return false;
}

function quantosChecksSelecionados( form, nome )
{
    var cont = 0;
    for( x=0; x<form.elements.length; x++)
    {
        if( form.elements[x].type != null )
        {
  	     if( form.elements[x].type=='checkbox' )
             {
  	         if( form.elements[x].name==nome )
 		         if( form.elements[x].checked )
                            cont++;
             }
        }
    }
    return cont;
}


function qtdChecados( form, nome, valor )
{
   Alert('oi');
    i = 0;
    for( x=0; x<form.elements.length; x++)
  	     if( form.elements[x].type=='checkbox' )
  	         if( form.elements[x].name==nome )
                    if( form.elements[x].checked ) i++;
    if (i > valor)
    {
       Alert('Selecione no máximo ' + valor + '.');
       return false;
    }
       Alert('ok ');
       return true;
}

function checkTodos( form, nome, atual )
{
    for( x=0; x<form.elements.length; x++)
  	     if( form.elements[x].type=='checkbox' )
  	         if( form.elements[x].name==nome )
                        form.elements[x].checked = atual.checked;
}

function alertaCheckBox( form, nome, txtNome )
{
    if( !temCheckSelecionado(form,nome) )
    {
		alert( 'É necessário selecionar alguma opção do campo "' + txtNome + '". ' );
		return true;
    }
    else
        return false;
}

function osDoisNaMesma( campo1, campo2, nome )
{
	if( !campoVazio(campo1) || !campoVazio(campo2) )
	if( campoVazio(campo1) || campoVazio(campo2) )
	{
		alert( 'Não pode preencher somente um item de "' +nome+ '" ');
		return false;		
	} 
	return true;	
}


var objTimeOut = null;
var overElement = false;

function carregaHint( IdHint ){
	
	var pageX = 0;
	var pageY = 0;
	
	var li = $(IdHint); 
		
	//document.getElementsByClassName(nameClassHint).each(function(li) {

		/*Por cima do Elemento*/
		Event.observe(li, 'mousemove', function(e) {
			if (overElement) {
				if (objTimeOut == null) {
					Element.show('load'+li.id);
					objTimeOut = setTimeout( function(){ Element.hide('load'+li.id); new Effect.Appear('det' + li.id, { duration: 0.5 }); }, 200);
				}
			}

			if (navigator.appName == 'Netscape') {
				pageX = e.pageX;
				pageY = e.pageY;
			}else{
				pageX = e.clientX + document.body.scrollLeft;
				pageY = e.clientY + document.body.scrollTop;
			}

			$('det' + li.id).setStyle({
				top: (pageY+22)+'px',
				left: (pageX-3)+'px'
			});

			$('load' + li.id).setStyle({
				position: 'absolute',
				top: (pageY+22)+'px',
				left: (pageX-3)+'px'
			});
			
		});
		
		/*Entrou do Elemento*/		
		Event.observe(li, 'mouseover', function(e) {
			overElement = true;
		});
		
		/*Saiu do Elemento*/
		Event.observe(li, 'mouseout', function(e) {

			clearTimeout(objTimeOut);
			objTimeOut = null;
			
			Element.hide('load'+li.id);
			new Effect.DropOut('det' + li.id);

			overElement = false;
		});
		
		/*Saiu do Elemento*/
		Event.observe(li, 'click', function(e) {

			clearTimeout(objTimeOut);
			objTimeOut = null;

			Element.hide('load'+li.id);
			new Effect.DropOut('det' + li.id);
			
			overElement = false;
		});		
		
	//});
	
}

function inicializaCamposData(data_inicio, data_fim){
    Event.observe(window, 'load', function(){
        //var data_inicio = '';
        //var data_fim = '';
        
        //new Ajax.Request(caminho + 'bnmcalendario.exe/now?tempo=' + pegaData(), {
        //    onSuccess: function(transport){
        //        data_inicio = transport.responseText;
        //        new Ajax.Request(caminho + 'bnmcalendario.exe/now?dias=-30&tempo=' + pegaData(), {
        //            onSuccess: function(transport){
        //                data_fim = transport.responseText;
        $$('input[type="text"]').each(function(el, index){
            if (el.next()) {
                if (el.next().value == 'Procurar') {
                    if (el.up().previous().innerHTML.toUpperCase().indexOf('INÍCI')>=0) {
                        el.value = data_inicio;
                    }
                    else {
                        if (el.up().previous().innerHTML.toUpperCase().indexOf('FI')>=0) {
                            el.value = data_fim;
                        }
                    }
                }
            }
        });
        //            }
        //        });
        //   }
        //});
    });
}