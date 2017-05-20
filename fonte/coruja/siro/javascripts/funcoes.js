function excluir(id,acao,formulario,value) {
    document.getElementById(id).value = value;
    document.getElementById(formulario).action = acao;
    document.getElementById(formulario).submit();
}
function alterar(id,acao,formulario,value) {
    document.getElementById(id).value = value;
    document.getElementById(formulario).action = acao;
    document.getElementById(formulario).submit();
}
function cadastrar(formulario,acao){
	document.getElementById(formulario).action = acao;
	document.getElementById(formulario).submit();
}
function evento(idPeriodoLetivo,siglaPeriodo){
	document.getElementById('idPeriodoLetivo').value = idPeriodoLetivo;
	document.getElementById('siglaPeriodoLetivo').value = siglaPeriodo;
	document.periodo.action="EventoAdministrativo_controle.php?action=listar";
	document.periodo.submit();
}
function calendario(idPeriodoLetivo,siglaPeriodo){
	document.getElementById('idPeriodoLetivo').value = idPeriodoLetivo;
	document.getElementById('siglaPeriodoLetivo').value = siglaPeriodo;
	document.periodo.action="CalendarioLetivo_controle.php?action=listar";
	document.periodo.submit();
}
function emitirCalendario2(){
	document.calendario.action="/coruja/siro/classes/EmitirCalendarioLetivoPDF.php";
        document.calendario.target="_new";
	document.calendario.submit();
}
function validar(form){
	
	for(i=0;i < form.length;i++){
		if(form[i].value=="" && form[i].type!="button" && form[i].type!="hidden" && form[i].type!="submit" && form[i].title!=""){
			alert(form[i].title);
			return false;
		}
	}
	return true;
}

function mudaData(data){
	array =  data.split('/');
	data = array[1]+'/'+array[0]+'/'+array[2];
	return data;
}

function difData(dataIni,dataFim){
  if(dataFim!=""){	
	dtIni = mudaData(dataIni);
	dtFim = mudaData(dataFim);
	
	date1 = new Date();
	date2 = new Date();
	diff  = new Date();
	
	
	date1temp = new Date(dtIni);
	date1.setTime(date1temp.getTime());
	
	date2temp = new Date(dtFim);
	date2.setTime(date2temp.getTime());
	
	if(date2.getTime() - date1.getTime() < 0){
		   alert('A data final deve ser maior ou igual do que a inicial!!!');
		   return false;
		}else{	
			//sets difference date to difference of first date and second date
			
			diff.setTime(Math.abs(date2.getTime()- date1.getTime() ));
			
			timediff = diff.getTime();
			
			days = Math.floor(timediff / (1000 * 60 * 60 * 24)); 
			
			document.getElementById('difDatas').value = days;
			
		}
  }else{
	  days = "0";
	  document.getElementById('difDatas').value = days;
	  
  }
  return true;
}

function isValidDate(campo) {

	// Date validation function courtesty of 
	// Sandeep V. Tamhankar (stamhankar@hotmail.com) -->

	// Checks for the following valid date formats:
	// MM/DD/YY   MM/DD/YYYY   MM-DD-YY   MM-DD-YYYY
	
	dateStr = campo.value;

	if(dateStr!=""){ 
	var datePat = /^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/;
        //var datePat = /^(\d{1,2})(\/|-)(\d{1,2})\2(\d{4})$/; // requires 4 digit year

	var matchArray = dateStr.match(datePat); // is the format ok?
	if (matchArray == null) {
	alert(dateStr + " Não é um formato válido.")
	campo.value = "";
	campo.focus();	
	return false;
	}
	day = matchArray[1];
	month = matchArray[3]; // parse date into variables
	year = matchArray[4];
	if (month < 1 || month > 12) { // check month range
	alert("Mês precisa estar entre 1 e 12 inclusive.");
	campo.value = "";
	campo.focus();
	return false;
	}
	if (day < 1 || day > 31) {
	alert("Dia precisa estar entre 1 e 31 inclusive.");
	campo.value = "";
	campo.focus();
	return false;
	}
	if ((month==4 || month==6 || month==9 || month==11) && day==31) {
	alert("Mês "+month+" não tem 31 dias!")
	campo.value = "";
	campo.focus();
	return false;
	}
	if (month == 2) { // check for february 29th
	var isleap = (year % 4 == 0 && (year % 100 != 0 || year % 400 == 0));
	if (day>29 || (day==29 && !isleap)) {
	alert("Fevereiro " + year + " não tem " + day + " dias!");
	campo.value = "";
	campo.focus();
	return false;
	   }
	}
	
	}
	return true;
}



function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",1)
}

function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}

function data(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que não é dígito
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca uma barra depois de dois digitos
    v=v.replace(/(\d{2})(\d)/,"$1/$2")       //Coloca uma barra depois de dois digitos
                                             //de novo (para o segundo bloco de números)
    return v
}
function semestreSigla(v){
    v=v.replace(/D/g,"")                //Remove tudo o que não é dígito
    v=v.replace(/^(\d{4})(\d)/,"$1.$2") //Esse é tão fácil que não merece explicações
    return v
}

function soNumeros(v){
    return v.replace(/\D/g,"")
}