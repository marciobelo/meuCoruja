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
    function excluir(idTipoCurso) {
        document.formTpcurso.idTipoCurso.value=idTipoCurso;
        document.formTpcurso.acao.value="excluir";
        document.formTpcurso.submit();
    }
    function cancelar() {
        document.formTpcurso.acao.value="listar";
        document.formTpcurso.submit();
    }

</script>
<form name="formTpcurso" action="/coruja/interno/manter_tipocurso/manterTpcurso_controle.php" method="post">
    <input type="hidden" name="acao" />
<fieldset id="coruja">
    <legend>Excluir Tipo de Curso</legend>
    <input type="hidden" name="idTipoCurso" value="<?php echo $formTipoCurso->getIdTipoCurso(); ?>" />
  
    <table align="center">
        <tr>
            <td><b>Tipo de Curso</b></td>
            
        </tr>
         <tr>
             <td align="center"><?php echo $formTipoCurso->getDescricao();?></td>
             
        </tr>
        <tr>
            <td><input type="button" value="Excluir" onclick="javascript:excluir(<?php echo $formTipoCurso->getIdTipoCurso(); ?>);"/></td>
            <td><input type="button" value="Cancelar" onclick="javascript:cancelar();" /></td>
        </tr>

     </table>
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>