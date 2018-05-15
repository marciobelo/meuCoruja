<fieldset id="fieldsetGeral">
<?php
    // tipo hidden 
    echo $formExibeAluno->inputHidden('tipo',$tipo);

    echo "<legend>DADOS PESSOAIS<br/>" . $infoPessoa->getNome() ." </legend>";
?>
    <table border="0"><tr><td width="80%">

<?php
    // idPessoa hidden
    echo $formExibeAluno->inputHidden('idPessoa',$infoPessoa->getIdPessoa());

    // nome
    echo $formExibeAluno->inputLabel('Nome',$infoPessoa->getNome());
    echo "<br />";

    // sexo
    echo $formExibeAluno->inputLabel('Sexo',$infoPessoa->getSexo());
    echo "<br />";

    // data nascimento
    echo $formExibeAluno->inputLabel('Data Nasc.',Util::formataData($infoPessoa->getDataNascimento()));
    echo "<br />";

    // nacionalidade
    echo $formExibeAluno->inputLabel('Nacionalidade',$infoPessoa->getNacionalidade());
    echo "<br />";

    // naturalidade
    echo $formExibeAluno->inputLabel('Naturalidade',$infoPessoa->getNaturalidade());
    echo "<br />";
    echo "<br />";

    echo "<p align='center'><input id='button1' name='escolhe_aba' type='button' value='Editar Dados Pessoais' onclick='javascript:editar(1);' /></p>";

    ?>
    </td>
    <td>&nbsp;</td>
    <td align="right" width="20%">
        <img src="/coruja/baseCoruja/controle/obterFoto_controle.php?idPessoa=<?php echo $infoPessoa->getIdPessoa(); ?>"
             width="100" height="90" />
        <p>
<?php
        if( $loginAluno == null) 
        {
            echo "Sem Login<br /><a href='/coruja/interno/manter_login/manterLogin_controle.php?acao=prepararCriarLogin&idPessoa=" . $infoPessoa->getIdPessoa() . "'>Criar Login</a>";
        } 
        else 
        {
            echo $loginAluno->getNomeAcesso();
            if( $loginAluno->isBloqueado() )
            {
                echo "<br />Conta <span style='color: red; font-weight: bold;'>BLOQUEADA</span>: <a href='/coruja/interno/manter_login/manterLogin_controle.php?acao=exibirLogin&idPessoa=" . $infoPessoa->getIdPessoa() . "'>Ver Detalhes</a>";
            }
            else
            {
                echo "<br /><a href='/coruja/interno/manter_login/manterLogin_controle.php?acao=resetarSenha&idPessoa=" . $infoPessoa->getIdPessoa() . "'>Resetar Senha</a>";
            }
            echo "<br /><a href='/coruja/interno/manter_login/manterLogin_controle.php?acao=prepararAlterarFotoLogin&idPessoa=" . $infoPessoa->getIdPessoa() . "'>Alterar Foto</a>";
        }
?>      </p>
        
    </td></tr></table>
</fieldset>

<!--//  FIM DA PRIMEIRA PARTE DO FORMULARIO DE MATRICULA NOVA  -->