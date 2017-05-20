<html>
    <script type="text/javascript ">
        function validar() {
            if(document.getElementById("acao").value=="cancelar") return true;

            /*
             * Nome pelo qual um usuário-ator de identifica ao sistema.
             * Esse nome deve ser único entre os logins.
             * Até 20 caracteres. Mínimo de 4 caracteres.
             * Obrigatório.
             */
            nomeAcesso = document.getElementById("nomeAcesso");
            if( nomeAcesso.value.length<4 ) {
                alert("Preencha o campo Nome de Acesso (ao menos 4 caracteres)");
                nomeAcesso.focus();
                return false; // impede a submissão do formulário
            }
            /*
             * Senha de acesso criptografada, armazenada com o código hash MD5
             * representando em dígitos hexadecimais.
             * Obrigatório.
             * Restrição: a senha real do usuário (não a armazenada),
             * deve ter até 12 caracteres, e no mínimo de 4 caracteres.
             */
             /*
            senha = document.getElementById("senha");
            if( senha.value.length<4 ) {
                alert("Preencha o campo Senha (ao menos 4 caracteres)");
                senha.focus();
                return false; // impede a submissão do formulário
            }
            */
            return true; // permite a submissão do formulário
        }

        function cancelar() {
            document.getElementById("acao").value = "cancelar";
            document.getElementById("formLogin").submit();
        }
    </script>
    <head>
        <title>Coruja - Autenticar</title>
        <link href="/coruja/estilos/estilo.css" rel="stylesheet" type="text/css" />
    </head>
    <body onload="nomeAcesso.focus();">
        <table>
            <tr>
                <td>
                    <img src="/coruja/imagens/coruja.png" />
                </td>
                <td>
                    <img src="/coruja/imagens/logorj.jpg" />
                </td>
                <td>
                    <form method="post" id="formLogin" action="/coruja/autenticar/login_controle.php" onsubmit="return validar();">
                        <input type="hidden" name="acao" id="acao" value="recuperarSenhaConfirmarEmail" />
                        <table>
                            <?php
                            // Exibe mensagem de erro quando não conseguir conectar e/ou autenticar
                            if( isset($erro) ) {
                                ?>
                            <tr>
                                <td colspan="2">
                                    <span class="erro"><?php echo htmlspecialchars($erro, ENT_QUOTES, "iso-8859-1"); ?></span>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td colspan="2"><span class="destaque">Preencha os dados a seguir para recuperar sua senha</span></td>
                            </tr>
                            <tr>
                                <td width="20%">Nome de Acesso:</td>
                                <td><input type="text" id="nomeAcesso" name="nomeAcesso" maxlength="20" autocomplete="off" tabindex="1" /></td>
                            </tr>
                            <tr>
                                <td  width="20%">Data de Nascimento:</td>
                                <td>
                                    <input type="dataNascimento" id="dataNascimento" name="dataNascimento" maxlength="10" tabindex="2" />
                                    <br/>
                                    <span style="font-size: xx-small">Ex. 31/05/2012</span>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" value="Continuar" tabindex="3" />
                                    &nbsp;
                                    <input type="button" value="Cancelar" tabindex="4" onclick="cancelar();" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
    </body>
</html>