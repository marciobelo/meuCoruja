<?php
if($msgErro!="") {
     echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
     echo "<font>". htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1") ."</font>";
     echo "</fieldset></form>";
}
?>
<script type="text/javascript">

function validaSigla(campo) {
	   validador = false;

	   if(campo.value != ""){
	   
		   var regex = /\b[1-9]{1}[0-9]{3}\.[1-2]{1}\b/;
		     if (campo.value.match(regex)) {
		       validador = true;
		     }else{
		    	 alert('A sigla do período letivo digitada não está no formato válido.');
		    	 campo.value = "";
		    	 campo.focus();
		     }
	   }
	        
	   return validador;
}


</script>

<form method="post" name="alterarPeriodo" action="PeriodoLetivo_controle.php?action=atualizar" onsubmit="return validar(this);">
<input type="hidden" id="siglaCurso" name="siglaCurso" value=<?php echo $perLetivo->getSiglaCurso()?>>
<input type="hidden" id="idPeriodoLetivo" name="idPeriodoLetivo" value=<?php echo $perLetivo->getIdPeriodoLetivo()?>>
    <fieldset id="fieldsetGeral">
        <legend>Alterar Período Letivo</legend>
        <font size="-1" color="#FF0000">Todos os campos são obrigatórios.</font><br />
        <table cellspacing="2" cellpadding="2">
        <tr>
        	<td>
        	  <label>Curso:</label>
        	</td>
        	<td>
        	  <span id="cinza"><?php echo $classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso() ?></span>
        	</td>
        </tr>
        <tr>
        	<td>
        	  <label>Sigla:</label>
        	</td>
        	<td>
        	  <input type="text" name="sigla" size="10" onblur="return validaSigla(this);" maxlength="6" value=<?php echo $perLetivo->getSiglaPeriodoLetivo()?>> 
        	Ex.: AAAA.S 
                </td>
        </tr>
         <tr>
        	<td>
        	  <label>Data Inicial:</label>
        	</td>
        	<td>
                    <input type="text" name="dtIni"  size="10"  value=<?php echo Util::formataData( $perLetivo->getDataInicio() ); ?>  maxlength="10" onblur="return isValidDate(this);"><button class="calendar" type="button" onclick="displayCalendar(document.alterarPeriodo.dtIni,'dd/mm/yyyy',this)"></button> 
        	</td>
        </tr>
         <tr>
        	<td>
        	  <label>Data Final:</label>
        	</td>
        	<td>
        	  <input type="text" name="dtFim"  size="10"  value=<?php echo Util::formataData( $perLetivo->getDataFim() ); ?> maxlength="10" onblur="return isValidDate(this);"><button class="calendar" type="button" onclick="displayCalendar(document.alterarPeriodo.dtFim,'dd/mm/yyyy',this)"></button> 
        	</td>
        </tr>
        <tr>
        	<td>
        	  <input type='submit' id='button1' name='alterar' value='Salvar' onclick="return difData(document.alterarPeriodo.dtIni.value,document.alterarPeriodo.dtFim.value);" /> 
        	</td>
        	<td>
        	  <input type='button' id='button1' name='voltar' value='Voltar' onclick="document.voltarAcao.submit();" /> 
        	</td>
        </tr>
        
        </table>
    </fieldset>
</form>
<form action="PeriodoLetivo_controle.php?action=listar" method="post" name="voltarAcao">
<input type="hidden" id="siglaCurso" name="siglaCurso" value=<?php echo $perLetivo->getSiglaCurso()?>>
</form>