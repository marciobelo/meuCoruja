<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    function cancelar(idTipoCurso) {
        document.formTpcurso.idTipoCurso.value=idTipoCurso;
        document.formTpcurso.acao.value="listar";
        document.formTpcurso.submit();
    }
</script>
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
<form name="formTpcurso" action="/coruja/interno/manter_tipocurso/manterTpcurso_controle.php" method="post">
    <input type="hidden" name="acao" value="incluir" />
<fieldset id="coruja">
    <legend>Incluir Tipo de Curso</legend>
    <input type="hidden" name="idTipoCurso" value="" />
    <table width="646">
        <tr>
            <td width="137"><b>Descri&ccedil;&atilde;o : </b> </td>
            <td width="499"><input type="text" name="descricao" value="<?php echo $_POST['descricao'];?>" maxlength="45" size="40"/></td>
        </tr>
                <tr>
            <td colspan="2">
                <input type="submit" value="Salvar" />
                &nbsp;
                <input type="button" value="Cancelar" onclick="javascript:cancelar();"/>
            </td>
        </tr>
    </table>
    
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>