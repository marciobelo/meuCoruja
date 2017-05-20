<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script>
    $(function() {
        $( "#desbloquearLogin" ).bind("click", function() {
            $( "#dialog-confirm" ).dialog({
                resizable: false,
                height:220,
                width: 500,
                modal: true,
                buttons: {
                    "Desbloquear": function() {
                        $( "#formDesbloquearLogin" ).submit();
                    },
                    "Cancelar": function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
    });    
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

<fieldset id="coruja">
    <legend>Exibir Login</legend>

        <table>
            <tr>
                <td>Nome:</td>
                <td>
                    <span><?php echo $formLogin->getNome(); ?></span>
                </td>
            </tr>
            <tr>
                <td>Nome Acesso:</td>
                <td>
                    <span><?php echo $formLogin->getNomeAcesso(); ?></span>
                </td>
            </tr>
            <tr>
                <td>Foto Atual:</td>
                <td>
                    <img src="/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=<?php echo $formLogin->getIdPessoa(); ?>" width="100" height="90" />
                </td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>
                    <span>
                    <?php 
                    if( $formLogin->isBloqueado() ) 
                    {
                        echo "BLOQUEADO";
                    }
                    else
                    {
                        echo "ATIVO";
                    }
                    ?></span>
                </td>
            </tr>
            <?php if( $formLogin->isBloqueado() ) { ?>
            <tr>
                <td>Motivo:</td>
                <td>
                    <span><?php echo $formLogin->getMotivoBloqueio(); ?></span>
                </td>
            </tr>
            <?php } ?>
            <tr>
                <td colspan="2">
                    <form id="formDesbloquearLogin" action="/coruja/interno/manter_login/manterLogin_controle.php" method="post">
                        <input type="hidden" name="acao" value="desbloquearLogin" />
                        <input type="hidden" name="idPessoa" value="<?php echo $formLogin->getIdPessoa(); ?>" />
                        <input id="desbloquearLogin" type="button" value="Desbloquear" />
                    </form>
            </tr>
        </table>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
<div id="dialog-confirm" title="Desbloquear Login?" style="display: none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>O Login será desbloqueado para uso do sistema. Confirma?</p>
</div>