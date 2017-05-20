<?php
if($msgErro!="") {
     echo "<form name='msg' id='msg'><fieldset id='fieldsetMsg'>";
     echo "<font>". htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1") ."</font>";
     echo "</fieldset></form>";
}
?>
<script type="text/javascript">
function preencheDescricaoEvento( elemento ) {
	if( elemento.value !== '' ) {
		document.getElementById('descricaoEvento').value = elemento.options[elemento.selectedIndex].text;
		document.getElementById('descricao').style.visibility = 'hidden';
	} else {
		document.getElementById('descricaoEvento').value='';
		document.getElementById('descricao').style.visibility='visible';
	}
}    
</script>
<form method="post" name="cadastrarEvento" action="EventoAdministrativo_controle.php?action=gravar" onsubmit="return validar(this);">
<input type="hidden" id="idPeriodoLetivo" name="idPeriodoLetivo" value='<?php echo $idPeriodo?>'>
<input type="hidden" id="difDatas" name="difDatas">

    <fieldset id="fieldsetGeral">
        <legend>Cadastrar Evento</legend>
        <font size="-1" color="#FF0000">Campos com * são obrigatórios.</font><br />
        <table cellspacing="2" cellpadding="2">
        <tr>
        	<td>
        	  <label>Curso:</label>
        	</td>
        	<td>
        	  <span id="cinza"><?php echo $classeCurso->getSiglaCurso()." - ".$classeCurso->getNomeCurso()?></span>
        	</td>
        </tr>
         <tr>
        	<td>
                    <label>Per&iacute;odo:</label>
        	</td>
        	<td>
                    <span id="cinza"><?php echo $periodoLetivo->getSiglaPeriodoLetivo()." (" . 
                            Util::dataSQLParaBr( $periodoLetivo->getDataInicio()) . " - " . 
                            Util::dataSQLParaBr( $periodoLetivo->getDataFim() ) . ")"?></span>
        	</td>
        </tr>
         <tr>
        	<td>
        	  <label>Data Inicial:*</label>
        	</td>
        	<td>
        	  <input type="text" name="dtIni" size="10"  title="O campo data inicial é obrigatório." value="<?php echo ($evento!="") ?  $evento->getData() :  ""?>"  maxlength="10" onblur="return isValidDate(this);"><button class="calendar" type="button" onclick="displayCalendar(document.cadastrarEvento.dtIni,'dd/mm/yyyy',this)"></button> 
        	</td>
        </tr>
         <tr>
        	<td>
        	  <label>Data Final:</label>
        	</td>
        	<td>
        	  <input type="text" name="dtFim"  size="10" value="<?php echo ($dataFim!="") ?  $dataFim :  ""?>"   maxlength="10" onblur="return isValidDate(this);"><button class="calendar" type="button" onclick="displayCalendar(document.cadastrarEvento.dtFim,'dd/mm/yyyy',this)"></button> 
        	</td>
        </tr>
          <tr>
        	<td>
        	  <label>Tipo do Evento:</label>
        	</td>
        	<td>
        	    <select id="tipoEvento" name="tipoEvento" onChange="preencheDescricaoEvento(this);">
             		<option value=''>Selecione o evento</option>
                        <option value='FIM_SOLIC_INSCR_TURMA' <?php if($evento!="") { if($evento->getTipoEvento()=='FIM_SOLIC_INSCR_TURMA'){?> selected="selected" <?php }}?>>Fim de solicita&ccedil;&atilde;o de inscri&ccedil;&atilde;o em turmas</option>
             		<option value='FIM_INCL_EXCL_DISCIPLINA' <?php if($evento!="") { if($evento->getTipoEvento()=='FIM_INCL_EXCL_DISCIPLINA'){?> selected="selected" <?php }}?>>Fim de inclus&atilde;o e exclus&atilde;o de disciplina</option>
                        <option value='FIM_TRANC_MATRICULA' <?php if($evento!="") { if($evento->getTipoEvento()=='FIM_TRANC_MATRICULA'){?> selected="selected" <?php }}?>>Fim de Trancamento de Matr&iacute;cula</option>
        		</select> 
        	</td>
        </tr>
          <tr id="descricao" style="visibility:visible">
        	<td>
        	  <label>Descricao:*</label>
        	</td>
        	<td>
        	  <input type="text" name="descricaoEvento" value="<?php echo ($evento!="") ?  $evento->getDescricao() :  ""?>" id="descricaoEvento" size="50" title="O campo descrição é obrigatório."> 
        	</td>
        </tr>
        <tr>
        	<td>
        	  <input type='submit' id='button1' name='salvar' value='Salvar' onclick="return difData(document.cadastrarEvento.dtIni.value,document.cadastrarEvento.dtFim.value);" /> 
        	</td>
        	<td>
        	  <input type='button' id='button1' name='voltar' value='Voltar' onclick="document.voltarAcao.submit();" /> 
        	</td>
        </tr>
        
        </table>
    </fieldset>
</form>
<form action="EventoAdministrativo_controle.php?action=listar" method="post" name="voltarAcao">
<input type="hidden" id="idPeriodoLetivo" name="idPeriodoLetivo" value='<?php echo $idPeriodo?>'>
</form>
