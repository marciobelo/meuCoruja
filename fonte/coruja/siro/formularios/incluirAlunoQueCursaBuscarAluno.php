<br /><b>Aluno(s) encontrado(s):</b><br />

<?php
    foreach($resultadoBusca as $itens)
    {
        $buscaPessoa .= "<form id='cadastro' action='ManterAlunosQueCursamTurma_controle.php' method='post'>";
        $buscaPessoa .= "<fieldset id='fieldsetGeral'>";

        $buscaPessoa .= "<input type='hidden' name='acao' value='incluirAlunoQueCursa'>";
        $buscaPessoa .= "<input type='hidden' name='tipo' value='aluno'>";
        $buscaPessoa .= "<input type='hidden' name='idTurma' value='" . $idTurma . "'>";
        $buscaPessoa .= "<input type='hidden' name='matricula' value='" .$itens->getMatriculaAluno() . "'>";
        $buscaPessoa .= "<br />";

        $buscaPessoa .= "<label>Nome: </label>".$itens->getNomeAluno();
        $buscaPessoa .= "<br />";
        $buscaPessoa .= "<label>Matricula: </label>".$itens->getMatriculaAluno();
        $buscaPessoa .= "<br />";
        $buscaPessoa .= "<br />";
        $buscaPessoa .= "<center><input type='submit' value='  Incluir na Turma  ' /></center>";
        $buscaPessoa .= "</fieldset>";
        $buscaPessoa .= "</form>";
        $buscaPessoa .= "<br />";
    }
    echo $buscaPessoa;
?>

    <form name="voltar" id="voltar" action="ManterAlunosQueCursamTurma_controle.php" method="post">
        <input type="hidden" name="acao" value="formBusca" />
        <input type="hidden" name="idTurma" value="<?php echo $idTurma;?>"/>
        <input type="hidden" name="siglaDisciplina" value="<?php echo $siglaDisciplina;?>"/>
        <input type="hidden" name="nomeDisciplina" value="<?php echo $nomeDisciplina;?>"/>
        <input id='button1' type='submit' value='  Voltar  ' >
    </form>