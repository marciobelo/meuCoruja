<?php
include('../../includes/topo.php');
include('../../includes/menu_horizontal.php');
?>
<script>
function atualizarListaPeriodoLetivo() {
    document.form.action="/coruja/interno/alocacao_professor/emitirAlocacao_professor_controle.php";
    document.form.action.value="AlocacaoProfessor";
    document.form.target="_self";
    document.form.submit();
}
</script>
<form name="form" method="post" id="form" action="/coruja/interno/alocacao_professor/emitirAlocacao_professor_controle.php" onsubmit="return validarRel(this);" target="_blank">
    <p>&nbsp;</p>

 <table width="666" border="0" align="center">

  <tr class="tabela_form2">
    <td width="350" class="label_obrigatorio">Escolha o Curso:</td>
    <td width="245"><input type="hidden" name="TpCriterio" value="0"/>
      <select name="curso" class="form_obrigatorio" id="curso" title="O campo curso &eacute; obrigat&oacute;rio" onchange="atualizarListaPeriodoLetivo()">
        <option value="" class="textos">Escolha o curso</option>
		<?php

		foreach($collection1 as $curso){?>
        <option value="<?php echo $curso->getSiglaCurso();?>" class="textos"><?php echo $curso->getSiglaCurso(). ' - '.$curso->getNomeCurso(); ?></option>
        <?php  }?>
      </select>
      
      <input name="action" type="hidden" id="action" value="exibirPDF" />
    
    </tr>
	<?Php if( $siglaCurso !== "") {?>
  <tr class="tabela_form2">
    <td class="label_obrigatorio">Escolha o Periodo:</td>
    <td><select name="periodo" class="form_obrigatorio" id="periodo" title="O campo período &eacute; obrigat&oacute;rio" />
      <option value="" class="textos">Escolha o período</option>
      <?php

		foreach($collection2 as $periodo){?>
      <option value="<?php echo $periodo->getIdPeriodoLetivo(); ?>" class="textos"><?php echo $periodo->getSiglaPeriodoLetivo(); ?></option>
      <?php }?>
    </select></td>
  </tr>

  <tr class="tabela_form2">
    <td class="label_obrigatorio">Escolha o Professor:</td>
    <td><select name="professor" class="form_obrigatorio" id="professor" title="O campo professor &eacute; obrigat&oacute;rio" />
      <option value="" class="textos">Escolha o professor</option>
      <?php

		foreach($collection3 as $professor){?>
      <option value="<?php echo $professor-> getIdPessoa();?>" class="textos"><?php echo $professor->getNome(); ?></option>
      <?php }?>
    </select></td>
  </tr>
  
  <tr class="tabela_form2">
    <td class="label_obrigatorio">&nbsp;</td>
    <td><input  type="submit" value="Emitir Alocação do Professor" />
      <input name="ok" type="hidden" id="ok" /></td>
  </tr>
  <?php }?>
</table>


<script>
document.form.curso.value="<?php echo $siglaCurso; ?>";
</script>
</form>
<?php
    include_once "$BASE_DIR/includes/rodape.php"; ?>