function validar(formulario){

	for(i=0;i<=formulario.length-1;i++){
		if ((formulario[i].value=="")&&(formulario[i].title!=undefined)){
			if ((formulario[i].type!="button")&&(formulario[i].type!="submit")&&(formulario[i].type!="hidden")){
				alert(formulario[i].title);
				formulario[i].focus();
				return false;
			}
		}
	}


}


function Limpar(formulario){
	
	for(i=0;i<=formulario.length-1;i++){
		
		if ((formulario[i].type!="button")&&(formulario[i].type!="submit")){
			formulario[i].value='';
			
		}
	}
	
}



