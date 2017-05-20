/*
*****************************************************
***********           Menu.js         ***************
***V.200804.1***************************29/03/2008***
*/
var urlant="";
window.CMenus=[];
var BLANK_IMAGE="/bnweb/anhembi/Imagens/transparente.png";
function popup_center(a,b,w,h,scroll)
// a=endereco ; b=titulo
{
	var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
	var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+wint+',';
      settings +='left='+winl+',';
      settings +='scrollbars='+scroll+',';
      settings +='resizable=no,';
			settings +='titlebar=no,';
      settings +='toolbar=no,';
      settings +='location=no,';
      settings +='directories=no,';
      settings +='status=yes,';
			settings +='menubar=no';
  NewWin2 = window.open(a,b,settings);
  if(parseInt(navigator.appVersion) >= 4){NewWin2.window.focus();}
}
function bw_check(){var is_major=parseInt(navigator.appVersion);this.nver=is_major;this.ver=navigator.appVersion;this.agent=navigator.userAgent;this.dom=document.getElementById?1:0;this.opera=window.opera?1:0;this.ie5=(this.ver.indexOf("MSIE 5")>-1&&this.dom&&!this.opera)?1:0;this.ie6=(this.ver.indexOf("MSIE 6")>-1&&this.dom&&!this.opera)?1:0;this.ie4=(document.all&&!this.dom&&!this.opera)?1:0;this.ie=this.ie4||this.ie5||this.ie6;this.mac=this.agent.indexOf("Mac")>-1;this.ns6=(this.dom&&parseInt(this.ver)>=5)?1:0;this.ie3=(this.ver.indexOf("MSIE")&&(is_major<4));this.hotjava=(this.agent.toLowerCase().indexOf('hotjava')!=-1)?1:0;this.ns4=(document.layers&&!this.dom&&!this.hotjava)?1:0;this.bw=(this.ie6||this.ie5||this.ie4||this.ns4||this.ns6||this.opera);this.ver3=(this.hotjava||this.ie3);this.opera7=((this.agent.toLowerCase().indexOf('opera 7')>-1) || (this.agent.toLowerCase().indexOf('opera/7')>-1));this.operaOld=this.opera&&!this.opera7;return this;};
function nn(val){return val != null;}
function und(val){return typeof(val) == 'undefined';}
function COOLjsMenu(name, items){
	this.REGISTERED=0;
        this.jaClick = false;
	this.bw=new bw_check();this.bi=new Image();this.bi.src=BLANK_IMAGE;
	if (!window.CMenus) window.CMenus=[];
	window.CMenus[name]=this;
	if (!window.CMenuHideTimers) window.CMenuHideTimers=[];
	window.CMenuHideTimers[name]=null;this.name=name;this.root=[];this.root.par=null;
	this.root.cd=[];this.root.fmt=items[0];this.items=[];
	this.root.frameoff = items[0].pos?items[0].pos:[0,0];
	this.root.lvl=new CMenuLevel(this, this.root);
	for (var i=1;i<items.length;i++) if (!und(items[i])) new CMenuItem(this, this.root, items[i], und(items[i].format)?items[0]:items[i].format);
	this.wm_get_pos=function(){if(this.bw.ns4) return; var ml=99999; var mt=0; var c=this.root.cd;for (var i=0;i<c.length;i++){if (c[i].pos[0]<ml) ml = c[i].pos[0];if (c[i].pos[1]>mt) mt = c[i].pos[1];}var fn=this.root.cd[0];return [parseInt(ml),parseInt(mt+fn.size[0]+fn.style.shadow)];}
	this.wm_show=function(){if(this.bw.ns4) return; var div = get_div(this.name+'_wm');div.style.visibility='visible';}
	this.wm_move=function(){if(this.bw.ns4) return; var p = this.wm_get_pos();var div = get_div(this.name+'_wm');if (this.bw.ns4) div.moveTo(p[0],p[1]); else{div.style.left=p[0];div.style.top=p[1];}}
	this.wm_draw=function(x,y){if(this.bw.ns4) return; var p = this.wm_get_pos();document.write(adiv(this.bw, this.name+'_wm', 0,p[0],p[1] ,30,10,'',unescape(''),'font-size:7px;color:#d0d0d0;visibility:show',''));}
	this.draw=function (){ for (var i=0;i<this.items.length;i++) document.write(this.items[i].draw()); this.wm_draw();}
	this.hide=function(){
		if (this.root.fmt.popup) 
			this.root.lvl.vis(0);
		else {
			for (var i=0;i<this.root.cd.length;i++) if (this.root.cd[i].lvl) this.root.cd[i].lvl.vis(0);
			this.root.lvl.a=null;
			this.root.lvl.draw();
			if (this.root.fmt.hidden_top) this.root.lvl.vis(0);
		}
	}
	this.mpopup=function(ev,offX,offY){
		var x=ev.pageX?ev.pageX:(this.bw.opera?ev.clientX:this.bw.ie4?ev.clientX+document.body.scrollLeft:ev.x+document.body.scrollLeft);
		var y=ev.pageY?ev.pageY:(this.bw.opera?ev.clientY:this.bw.ie4?ev.clientY+document.body.scrollTop:ev.y+document.body.scrollTop);
		var po=this.root.fmt.popupoff;
		y += offY?offY:po?po[0]:0;
		x += offX?offX:po?po[1]:0;
		this.popup(x, y);
	}
	this.popup=function(x,y){
		this.move(x,y);
		this.root.lvl.a=null;
		this.root.lvl.vis(1);
		mEvent(this.name,0,'t');
		mEvent(this.name,0,'0');
	}
	this.move=function(x,y){
		if (!this.root.pos || this.root.pos[0] != x || this.root.pos[1] != y) {
			this.root.pos=[x,y];
			this.root.loff=[0,0];
			this.root.ioff=[0,0];
			for (var i=0;i<this.items.length;i++){
				this.items[i].setPosFromParent();
				this.items[i].move(this.items[i].pos[0],this.items[i].pos[1]);
			}
			this.wm_move();
		}
	}
	this.draw();
	this.wm_show();
	if (!this.root.fmt.popup && !this.root.fmt.hidden_top) 
		this.root.lvl.vis(1)
	else
		this.root.lvl.vis(0)
}

function CMenuLevel(menu, par){
	this.menu=menu;
	this.par=par;
	this.v=0;
	this.vis=function(s){
		var ss=this.v;
		this.v=s;
		var l=this.par.cd.length;
		for (var i=0;i<l;i++){
			var n=this.par.cd[i];
			if ( n.hc() && n.lvl.v && !s ) n.lvl.vis(s);
			n.vis(s);
		}
		if (!s) this.a=null;
		if (this.v!=ss&&this.menu.onlevelshow) this.menu.onlevelshow(this);
	}
	this.setA=function(idx,s){
        var n=this.menu.items[idx];
		if (nn(this.a)&&n.par.lvl!=this.a.par.lvl) return;
		if(s&&n.hc())n.lvl.vis(1);
		if( s && n!= this.a && nn(this.a) && this.a.hc() && this.a.lvl.v ) this.a.lvl.vis(0);
		this.a=n;
		this.draw();
	}
	this.draw=function(){
		if (this.menu.root.lvl==this&&this.menu.root.fmt.hidden_top) return;
		for (var i=0;i<this.par.cd.length;i++)
			if (this.par.cd[i]==this.a)
				this.par.cd[i].setVis('o');
			else
				this.par.cd[i].setVis('n');
	}
}

function CMenuItem(menu, par, item, format){
	if (und(item)) return;
	this.lvl=null;this.par=par;
	this.code=item.code;
	this.ocode=item.ocode?item.ocode:item.code;
	this.targ=und(item.target)?"":'target="'+item.target+'" ';
	this.url=und(item.url)?"javascript:none()":item.url;
	if (this.url.indexOf("trocar_matricula") > -1){  // Alterado por Alcir - 16/01/2006
           urlant= '&urlant=' + window.location.href; 
        
           if (urlant.indexOf("balcao.exe") > -1){
              var sAux="?"; 
              if (urlant.indexOf("?") > -1){ 
                  sAux="&";
              }  
              try{            
                 urlant=urlant + sAux + 'cod_matricula=' + document.getElementById("cod_matricula").value;
                 sAux="&";
                 urlant=urlant + sAux + 'cod_recibo=' + document.getElementById("cod_recibo").value; 
                 urlant=urlant + sAux + 'malote=' + document.getElementById("malote").value;
                 urlant=urlant + sAux + 'biometria=' + document.getElementById("biometria").value;   
              }catch(e){}
           }
        }  
	this.fmt=format;this.menu=menu;this.bw=menu.bw;this.cd=[];
	this.divs=[];this.index=menu.items.length;
	menu.items[menu.items.length]=this;
	this.pindex=par.cd.length;
	par.cd[par.cd.length]=this;
	this.id="cmi"+this.menu.name+"_"+this.index;
	this.v=0;this.state='n';this.diva=["b","s","o","n","e"];
	this.hc=function(){return this.cd.length > 0};
	this.div=function(n){return und(this.divs[n])?this.divs[n]=get_div(this.id+n):this.divs[n]};
	this.draw=function (){
		var b=this.style.border;
		var s=this.style.shadow;

		if (this.url.indexOf("trocar_matricula") > -1){  // Alterado por Alcir - 16/01/2006
		return (!this.style.shadow?"":adiv(this.menu.bw, this.id+"s", parseInt(this.z)+1, this.pos[0]+s, this.pos[1]+s, this.size[1], this.size[0], this.style.color.shadow, "", ""))+
				(!this.style.border?"":adiv(this.menu.bw, this.id+"b", parseInt(this.z)+2, this.pos[0], this.pos[1], this.size[1], this.size[0], this.style.color.border, "", ""))+
				adiv(this.menu.bw, this.id+"o", parseInt(this.z)+3, this.pos[0]+b, this.pos[1]+b, this.size[1]-b*2, this.size[0]-b*2, this.style.color.bgOVER, '<div class="'+this.style.css.OVER+'">'+this.ocode+'</div>', "")+
				adiv(this.menu.bw, this.id+"n", parseInt(this.z)+4, this.pos[0]+b, this.pos[1]+b, this.size[1]-b*2, this.size[0]-b*2, this.style.color.bgON, '<div class="'+this.style.css.ON+'">'+this.code+'</div>', "")+
                                adiv(this.menu.bw, this.id+"e", parseInt(this.z)+5, this.pos[0]+b, this.pos[1]+b, this.size[1]-b*2, this.size[0]-b*2, "", '<a href="'+this.url + urlant +'" '+this.targ+'onclick="mEvent(\''+this.menu.name+'\','+this.index+',\'c\');"  onmouseover="mEvent(\''+this.menu.name+'\','+this.index+',\'o\');" onmouseout="mEvent(\''+this.menu.name+'\','+this.index+',\'t\');">'+'<img src="'+this.menu.bi.src+'" width="'+this.size[1]+'" height="'+this.size[0]+'" border="0"></a>', "", '' );
                }else{
		return (!this.style.shadow?"":adiv(this.menu.bw, this.id+"s", parseInt(this.z)+1, this.pos[0]+s, this.pos[1]+s, this.size[1], this.size[0], this.style.color.shadow, "", ""))+
				(!this.style.border?"":adiv(this.menu.bw, this.id+"b", parseInt(this.z)+2, this.pos[0], this.pos[1], this.size[1], this.size[0], this.style.color.border, "", ""))+
				adiv(this.menu.bw, this.id+"o", parseInt(this.z)+3, this.pos[0]+b, this.pos[1]+b, this.size[1]-b*2, this.size[0]-b*2, this.style.color.bgOVER, '<div class="'+this.style.css.OVER+'">'+this.ocode+'</div>', "")+
				adiv(this.menu.bw, this.id+"n", parseInt(this.z)+4, this.pos[0]+b, this.pos[1]+b, this.size[1]-b*2, this.size[0]-b*2, this.style.color.bgON, '<div class="'+this.style.css.ON+'">'+this.code+'</div>', "")+
                                adiv(this.menu.bw, this.id+"e", parseInt(this.z)+5, this.pos[0]+b, this.pos[1]+b, this.size[1]-b*2, this.size[0]-b*2, "", '<a href="'+this.url +'" '+this.targ+'onclick="mEvent(\''+this.menu.name+'\','+this.index+',\'c\');"  onmouseover="mEvent(\''+this.menu.name+'\','+this.index+',\'o\');" onmouseout="mEvent(\''+this.menu.name+'\','+this.index+',\'t\');">'+'<img src="'+this.menu.bi.src+'" width="'+this.size[1]+'" height="'+this.size[0]+'" border="0"></a>', "", '' );
                }
	}
	this.vis=function(s){
			if (this.style.shadow) this.visDiv("s",s);
			if (this.style.border) this.visDiv("b",s);
			if (!s) {this.visDiv("o",0);this.visDiv("n",0);this.state="n";}
			else if (this.state=="n")
				this.visDiv("n",1);
			else
				this.visDiv("o",1);
			this.visDiv("e",s);
	}
	this.setVis=function (n){
		if (this.state!=n)
			switch (n){
				case "n":this.visDiv("n",1);this.visDiv("o",0);break;
				case "o":this.visDiv("n",0);this.visDiv("o",1);break;
			}
		this.state=n;
	}
	this.visDiv=this.bw.ns4? visDivNS:visDivDom;
	this.getf=function(obj, name){
		if (!und(obj) && nn(obj) && !und(obj.fmt)) {
			if (!und(obj.fmt[name]))
				return obj.fmt[name]; 
			if (obj.par!=this.menu.root && obj.par && obj.par.sub && obj.par.sub[0][name]) 
				return obj.par.sub[0][name]; 
			return this.getf(obj.par, name);}
		return;
	}
	this.ioff=this.getf(this, "itemoff");
	this.loff=this.getf(this, "leveloff");
	this.style=this.getf(this, "style");
	this.size=this.getf(this, "size");
	this.prev=this.pindex==0? null : this.par.cd[this.pindex-1];
	this.setPos=function(){
		if (this.prev==null){
			this.z=this.par == this.menu.root? 0: parseInt(this.par.z)+10;
			this.pos=und(this.fmt.pos)?(this.par == this.menu.root? this.menu.root.fmt.pos : this.pos=[this.par.pos[0]+this.loff[1], this.par.pos[1]+this.loff[0]]):this.fmt.pos;
		}else{
			this.prev.next=this;
			this.z=this.prev.z;
			this.pos=[this.prev.pos[0]+this.ioff[1], this.prev.pos[1]+this.ioff[0]];
		}
	}
	this.setPos();
	this.sub=item.sub;
	if (!und(this.sub) && !und(this.sub.length)&& this.sub.length>0){
		this.lvl=new CMenuLevel(menu, this);
		for (var i=1;i<this.sub.length;i++)
			if (!und(this.sub[i])) new CMenuItem(this.menu, this, this.sub[i], und(this.sub[i].format)?this.sub[0]: this.sub[i].format);
	}
	this.setPosFromParent=function(){
		if (this.index == 0) {
			this.pos=[this.menu.root.pos[0], this.menu.root.pos[1]]
		} else 
		if (this.prev==null){
			this.pos=[this.par.pos[0]+this.loff[1], this.par.pos[1]+this.loff[0]];
		}else{
			this.pos=[this.prev.pos[0]+this.ioff[1], this.prev.pos[1]+this.ioff[0]];
		}
	}
	this.move=function( x, y ){
		var bl=bt=this.style.border;
		if (this.style.shadow) this.moveTo(x+parseInt(this.style.shadow),y+parseInt(this.style.shadow),"s");
		if (this.style.border) this.moveTo(x,y,"b");
		this.moveTo(x+bl,y+bt,"o");
		this.moveTo(x+bl,y+bt,"n");
		this.moveTo(x+bl,y+bt,"e");
	}
	this.moveTo=function( x, y, b ){
		if (this.bw.ns4){
			this.div(b).moveTo(x,y);
		}else{
			this.div(b).style.left=x;
			this.div(b).style.top=y;
		}
	}
	return this;
}
function adiv(bw,name,z,left,top,width,height,bgc,code,otherCSS, otherDIV){return bw.ns4?'<layer id="'+name+'" z-index="'+z+'" left="'+left+'" top='+top+'" width="'+width+'" height="'+height+'"'+(bgc!=""?' bgcolor="'+bgc+'"':'')+' style="'+otherCSS+'" visibility="hidden" '+otherDIV+'>'+code+'</layer>\n':'<div id="'+name+'" style="position:absolute;z-index:'+z+';left:'+left+'px;top:'+top+'px;width:'+width+'px;height:'+height+'px;visibility:hidden'+(bgc!=""?';background-color:'+bgc+'':'')+';'+otherCSS+';" '+otherDIV+'>'+code+'</div>';}
function get_div(name){return new bw_check().ns4?document.layers[name]:document.all?document.all[name]:document.getElementById(name);}
function visDivNS(d,s){this.div(d).visibility=s?'show':'hide';}
function visDivDom(d,s){this.div(d).style.visibility=s?'visible':'hidden';}
function mEvent(m,node_index,e) {
	if (nn(window.CMenuHideTimers[m])) {
		window.clearTimeout(window.CMenuHideTimers[m]);
		window.CMenuHideTimers[m]=null;
	}
	switch (e){
		case "o":
			//window.CMenus[m].items[node_index].par.lvl.setA(node_index,1);
 			window.CMenus[m].items[node_index].par.lvl.setA(node_index,window.CMenus[m].jaClick);
			if (window.CMenus[m].onmouseover) window.CMenus[m].onmouseover(window.CMenus[m].items[node_index]);
 			if (window.CMenus[m].jaClick) listboxvisible(false); //Alterado por marcos
			else window.CMenuHideTimers[m]=setTimeout('listboxvisible(false);window.CMenus["'+m+'"].jaClick = true;window.CMenus["'+m+'"].items["'+node_index+'"].par.lvl.setA("'+node_index+'",1);', und(window.CMenus[m].root.fmt.delay)?300:window.CMenus[m].root.fmt.delay); 
			break;
		case "c":
		        window.CMenus[m].jaClick = true;
			if (window.CMenus[m].items[node_index].hc()){
				window.CMenus[m].items[node_index].lvl.vis(!window.CMenus[m].items[node_index].lvl.v);
				listboxvisible(!window.CMenus[m].items[node_index].lvl.v); //aqui
			}else
				for (var i=0;i<window.CMenus[m].root.cd.length;i++) if (nn(window.CMenus[m].root.cd[i].lvl)) window.CMenus[m].root.cd[i].lvl.vis(0);
			if (window.CMenus[m].onclick) window.CMenus[m].onclick(window.CMenus[m].items[node_index]);
			break;
		case "t": 
			//window.CMenuHideTimers[m]=setTimeout('window.CMenus["'+m+'"].hide()', und(window.CMenus[m].root.fmt.delay)?600:window.CMenus[m].root.fmt.delay);
			window.CMenuHideTimers[m]=setTimeout('window.CMenus["'+m+'"].jaClick = false;window.CMenus["'+m+'"].hide();listboxvisible(true);', und(window.CMenus[m].root.fmt.delay)?600:window.CMenus[m].root.fmt.delay);
			if (window.CMenus[m].onmouseout) window.CMenus[m].onmouseout(window.CMenus[m].items[node_index]);
			break;
	}
	return true;
}
window.oldCMOnLoad=window.onload;
function CMOnLoad(){
	var bw=new bw_check();
	if (bw.operaOld)window.operaResizeTimer=setTimeout('resizeHandler()',1000);
	if (typeof(window.oldCMOnLoad)=='function') window.oldCMOnLoad();
	if (bw.ns4) window.onresize=resizeHandler;
}
window.onload=new CMOnLoad();
function resizeHandler() {
	if (window.reloading) return;
	if (!window.origWidth){
		window.origWidth=window.innerWidth;
		window.origHeight=window.innerHeight;
	}
	var reload=window.innerWidth != window.origWidth || window.innerHeight != window.origHeight;
	window.origWidth=window.innerWidth;window.origHeight=window.innerHeight;
	if (window.operaResizeTimer)clearTimeout(window.operaResizeTimer);
	if (reload) {window.reloading=1;document.location.reload();return};
	if (new bw_check().operaOld){window.operaResizeTimer=setTimeout('resizeHandler()',500)};
}
function CMenuPopUp(menu, evn, offX, offY){window.CMenus[menu].mpopup(evn, offX, offY);}
function CMenuPopUpXY(menu,x,y){window.CMenus[menu].popup(x,y);}

//Funcao para desabilitar os listbox - Marcos
function listboxvisible(v)
{
  var bw=new bw_check();
  if (bw.ie){
     var tags= document.getElementsByTagName("select") ;
     for(var i=0;i<tags.length;i++) if (tags[i].noHidden!='noHidden') tags[i].style.visibility=(!v?'hidden':'visible');
  }
}
