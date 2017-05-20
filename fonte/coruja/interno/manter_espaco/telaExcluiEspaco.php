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
    function excluir(idEspaco) {
        document.formEspaco.idEspaco.value=idEspaco;
        document.formEspaco.acao.value="excluir";
        document.formEspaco.submit();
    }
    function cancelar() {
        document.formEspaco.acao.value="listar";
        document.formEspaco.submit();
    }

</script>
<form name="formEspaco" action="/coruja/interno/manter_espaco/manterEspaco_controle.php" method="post">
    <input type="hidden" name="acao" />
<fieldset id="coruja">
    <legend>Excluir espa&ccedil;o</legend>
    <input type="hidden" name="idEspaco" value="<?php echo $formEspaco->getIdEspaco(); ?>" />
  
    <table align="center">
        <tr>
            <td><b>Espaco</b></td>
            <td><b>Capacidade</b></td>
        </tr>
         <tr>
             <td align="center"><?php echo $formEspaco->getNome();?></td>
             <td align="center"><?php echo $formEspaco->getCapacidade();?></td>
        </tr>
        <tr>
            <td><input type="button" value="Excluir" onclick="javascript:excluir(<?php echo $espaco->getIdEspaco(); ?>);"/></td>
            <td><input type="button" value="Cancelar" onclick="javascript:cancelar();" /></td>
        </tr>

     </table>
</fieldset>
</form>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>