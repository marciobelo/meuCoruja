<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<!-- Mensagens de erro, se houver -->
<?php
if(count($msgsErro)>0) {
?>
<ul class="erro">
<?php
    foreach($msgsErro as $msgErro) {
?>
    <li>
        <?php echo $msgErro; ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
<script type="text/javascript">
    function excluir() {
        document.formCurso.acao.value="excluir";
        document.formCurso.submit();
    }
    function cancelar() {
        document.formCurso.acao.value="listar";
        document.formCurso.submit();
    }

</script>
<form name="formCurso" action="/coruja/interno/manter_curso/manterCurso_controle.php" method="post">
    <input type="hidden" name="acao" />
<fieldset id="coruja">
    <legend>Excluir Curso</legend>
    <input type="hidden" name="siglaCursoAntes" value="<?php echo $formCurso->getSiglaCursoAntes(); ?>" />
  
    <table align="center" width="600">
      
         <tr align="center">
             <td align="center" width="100"><b>Sigla :   </b><?php echo $formCurso->getSiglaCursoAntes();?></td>
             
        </tr>
          <tr>
             <td align="center"><b>Curso :   </b><?php echo $formCurso->getNomeCurso();?></td>

        </tr>
          <tr>
             <td align="center"><b>Tipo do Curso :   </b><?php echo $formCurso->getDescricao();  ?></td>

        </tr>
        <tr>
            <td align="center"><input type="button" value="Excluir" onclick="javascript:excluir();"/>    <input type="button" value="Cancelar" onclick="javascript:cancelar();" /></td>
            
        </tr>

     </table>
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>