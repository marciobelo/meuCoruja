<html>
    <script type="text/javascript ">
        function validar() {
            if(document.getElementById("acao").value=="cancelar") return true;

            /*
             * Nome pelo qual um usu�rio-ator de identifica ao sistema.
             * Esse nome deve ser �nico entre os logins.
             * At� 20 caracteres. M�nimo de 4 caracteres.
             * Obrigat�rio.
             */
            nomeAcesso = document.getElementById("nomeAcesso");
            if( nomeAcesso.value.length<4 ) {
                alert("Preencha o campo Nome de Acesso (ao menos 4 caracteres)");
                nomeAcesso.focus();
                return false; // impede a submiss�o do formul�rio
            }
            /*
             * Senha de acesso criptografada, armazenada com o c�digo hash MD5
             * representando em d�gitos hexadecimais.
             * Obrigat�rio.
             * Restri��o: a senha real do usu�rio (n�o a armazenada),
             * deve ter at� 12 caracteres, e no m�nimo de 4 caracteres.
             */
             /*
            senha = document.getElementById("senha");
            if( senha.value.length<4 ) {
                alert("Preencha o campo Senha (ao menos 4 caracteres)");
                senha.focus();
                return false; // impede a submiss�o do formul�rio
            }
            */
            return true; // permite a submiss�o do formul�rio
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
                        <input type="hidden" name="acao" id="acao" value="recuperarSenha" />
                        <input type="hidden" name="idPessoa" value="<?php echo $idPessoa; ?>" />
                        <table>
                            <?php
                            // Exibe mensagem de erro quando n�o conseguir conectar e/ou autenticar
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
                                <td>Nome de Acesso:</td>
                                <td>
                                    <input type="text" id="nomeAcesso" name="nomeAcesso" readonly="true" style="background-color: lightgray;" tabindex="1" value="<?php echo $nomeAcesso; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>Data de Nascimento:</td>
                                <td>
                                    <input type="text" id="dataNascimento" name="dataNascimento" readonly="true" style="background-color: lightgray;" tabindex="2" value="<?php echo $dataNascimento; ?>" />
                                </td>
                            </tr>
                            <tr>
                                <td>E-Mail:</td>
                                <td>
                                    <input type="text" id="email" name="email" readonly="true" style="background-color: lightgray;" tabindex="2" value="<?php echo $email; ?>" size="40" />
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    Se seu e-mail n�o for este, voc� dever� procurar a
                                    secretaria do curso e assinar um requerimento
                                    de corre��o. Nesse caso, n�o se pode
                                    solicitar pela Central de Atendimento Virtual.
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="submit" value="Recuperar Senha" tabindex="3" />
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