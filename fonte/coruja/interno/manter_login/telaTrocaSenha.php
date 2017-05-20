<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
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

<script language="JavaScript">
    function validar() {
        /*
         * Senha de acesso criptografada, armazenada com o c�digo hash MD5
         * representando em d�gitos hexadecimais.
         * Obrigat�rio.
         * Restri��o: a senha real do usu�rio (n�o a armazenada),
         * deve ter at� 12 caracteres, e no m�nimo de 4 caracteres.
         */
        novaSenha = document.getElementById("novaSenha");
        if( novaSenha.value.length<4 ) {
            alert("Preencha o campo Nova Senha (ao menos 4 caracteres)");
            novaSenha.focus();
            return false; // impede a submiss�o do formul�rio
        }
        
        confirmaSenha=document.getElementById("confirmaSenha");
        if( novaSenha.value != confirmaSenha.value ) {
            alert("A senha n�o confere!");
            novaSenha.focus();
            return false; // impede a submiss�o do formul�rio
        }

        return true; // permite a submiss�o do formul�rio
    }
</script>

<fieldset id="coruja">
    <legend>Trocar Senha</legend>
    <form method="post" name="trocaSenha"
          action="/coruja/interno/manter_login/manterLogin_controle.php"
          onsubmit="return validar();" >
        <input type="hidden" name="acao" value="trocarSenha" />

        <table>
            <tr>
                <td>Senha Atual:</td>
                <td>
                    <input type="password" name="senhaAtual" size="12" maxlength="12" autocomplete="off" tabindex="1" />
                </td>
            </tr>
            <tr>
                <td>Nova Senha:</td>
                <td>
                    <input type="password" id="novaSenha" name="novaSenha" size="12" maxlength="12" autocomplete="off" tabindex="2" />
                </td>
            </tr>
            <tr>
                <td>Confirma Senha:</td>
                <td>
                    <input type="password" id="confirmaSenha" name="confirmaSenha" size="12" maxlength="12" autocomplete="off" tabindex="3" />
                </td>
            </tr>
                
        </table>
        <input type="submit" value="Trocar Senha" tabindex="4" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
<script type="text/javascript">
    document.trocaSenha.senhaAtual.focus();
</script>

