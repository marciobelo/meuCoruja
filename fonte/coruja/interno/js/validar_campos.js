

function validar_campos(){
var d=document;

   if(d.form.tipo.value==1)
  {  if(d.form.usuario.value=='')
     {
       alert("informe o mês para a realização da consulta");
       d.form.usuario.focus();
	  
	 }
	  
       if(d.form.dtlog.value=='')
    {
      alert("informe a data de log para a realização da consulta");
      d.form.dtlog.focus();
	  d.form.mesLog.value='';
    }else
	 {
	  d.form.mesLog.value=''	 
	  d.form.submit();
	 }
  }

     
    if(d.form.tipo.value==2)
   {   if(d.form.usuario.value=='')
     {
       alert("informe o mês para a realização da consulta");
       d.form.usuario.focus();
	  
	 }
	   
      if(d.form.mesLog.value=='')
     {
       alert("informe o mês para a realização da consulta");
       d.form.mesLog.focus();
	   d.form.dtlog.value='';
	 }else
	  {
	   d.form.dtlog.value='';	  
	   d.form.submit();
	  }
   }	 
 
 
 }
 



