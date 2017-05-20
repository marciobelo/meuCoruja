/*
******************************************************
********************** popup.js **********************
***V.200804.1***************************29/03/2008***
*/

var fader;
var popUp;
var h;
var iTimeoutID;
var top;

// Retorna o tamanho da tela disponivel do browser


function getScreenSize() {

    var myWidth = 0, myHeight = 0;

    if( typeof( window.innerWidth ) == 'number' ) {
        //Non-IE
        myWidth = window.innerWidth;
        myHeight = window.innerHeight;
    } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
        //IE 6+ in 'standards compliant mode'
        myWidth = document.documentElement.clientWidth;
        myHeight = document.documentElement.clientHeight;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
        //IE 4 compatible
        myWidth = document.body.clientWidth;
        myHeight = document.body.clientHeight;
    }

    return new Array(myWidth, myHeight);
}


// defina o tamanho de um elemento igual o tamanho disponível do browser

function setFullScreenSize(el) {
    var screen = getScreenSize();
    el.style.width = screen[0] + 'px';
    if (window.scrollMaxY) {
      el.style.height = screen[1] + window.scrollMaxY +  'px';
    }
    else {  // Internet Explorer
      el.style.height = screen[1] + document.body.scrollTop + 'px';
    }
}

function verticalCenterOffset(el) {
   if (window.pageYOffset){
      var vScroll=window.pageYOffset;
   }
   else { // Internet Explorer
      var vScroll=document.body.scrollTop;
   }

   el.style.top= vScroll + 'px';

   return vScroll;
}
// escurece a tela
function fadeAway() {
    fader = document.createElement("div");
    fader.id = 'fader';
    fader.height = document.height;
    setFullScreenSize(fader);
    //verticalCenterOffset(fader);
    document.body.appendChild(fader);
    window.onresize = resizeFade;
}

function resizeFade() {
    setFullScreenSize(fader);
}

function unfadeAway(){
    window.onresize = null;
    document.body.removeChild(fader);
}

function centraliza(){

    if (window.pageYOffset){
       var vScroll=window.pageYOffset;
    }
    else { // Internet Explorer
       var vScroll=document.body.scrollTop;
    }
   var topo=popUp.style.top;
   var posicao=topo.indexOf("px");
   topo=topo.slice(0,posicao);
   var screen = getScreenSize();
   var t = topo;

   if (topo > vScroll + (screen[1] / 2) - (h / 2) + 10)  {
        t = eval(topo + " - 10");
   } else if (topo < vScroll + (screen[1] / 2) - (h / 2) -10)  {
        t = eval(topo + " + 10");
   }

   popUp.style.top = t + 'px';

   //alert(vScroll + (screen[1] / 2) - (h / 2));
   //alert(t);
   //alert(topo);


   iTimerID = setTimeout("centraliza()",10);
}



function ajustePopup(Wwin,Hwin, altIfhame)
{
    if (altIfhame+'' == 'undefined')
    {
       Hiframe = Hwin;
       Wiframe = Wwin;
    }
    else
    {
       if (navigator.appName == 'Netscape')
       { 
          Hiframe = Hwin - 30;
          Wiframe = Wwin;
       }
       else
       {
          Hiframe = Hwin - 30;
          Wiframe = Wwin + 10;
       }
    }
     
    popUp.style.width   = Wwin + 'px';
    popUp.style.height  = Hwin + 'px';




    iframe = document.getElementById('iframePopup');
    iframe.style.width   = Wiframe + 'px';
    iframe.style.height  = Hiframe + 'px';
 
    h = Hwin;
    var screen = getScreenSize();
    top = verticalCenterOffset(popUp);
    popUp.style.top = top + (screen[1] / 2) - (h / 2) + 'px';
    centraliza();
}

/* Abre popup interno
 * @url     url que sera carregada no iframe
 * @w       largura
 * @h       altura
 * @titulo  tutulo da janela
 */
function openInnerPopUp(url, w, altura, titulo){
    h = altura;
    fadeAway();
     
    
    popUp = document.createElement("div");
    popUp.id = 'popUp';
    popUp.style.width   = w + 'px';
    popUp.style.height  = h + 'px';
    popUp.innerHTML = '<div class="topoJanela">' +
                      '<label class="titulo">' + titulo + '</label>' +
                      '<label class="btnFechar" onclick="closeInnerPopUp()">x</label>' +
                      '</div>' +
                      '<iframe id="iframePopup" src="' + url + '" width="' + w + '" height="' + h + '" frameborder="0"></iframe>';

    popUp.style.marginLeft = '-' + w / 2 + 'px';
    //popUp.style.marginTop = '-' + h / 2 + 'px';

    var screen = getScreenSize();
    top = verticalCenterOffset(popUp);
    popUp.style.top = top + (screen[1] / 2) - (h / 2) + 'px';
    centraliza();
/*
    var screen = getScreenSize();
    top = verticalCenterOffset(popUp);
    popUp.style.top = top + (screen[1] / 2) - (h / 2) + 'px';
    window.onscroll = function(){
                          var screen = getScreenSize();
                          var top = verticalCenterOffset(popUp);
                          popUp.style.top = top + (screen[1] / 2) - (h / 2) + 'px';
                          //verticalCenterOffset(fader);
                      };
*/

/*    listboxvisible(false); */
    document.body.appendChild(popUp);

}

function closeInnerPopUp() {
    document.body.removeChild(popUp);
    unfadeAway();
    listboxvisible(true);
    clearTimeout(iTimeoutID);
}