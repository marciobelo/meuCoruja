<?php
    //DECLARACAO DE VARIAVEIS UTILIZADAS NA LISTA
    $corLinha; //Usada para alternar o CSS nas linhas
    $turmaHorarios = array (); //USADO PARA RECEBER OS HORARIOS ALOCADOS PARA A TURMA
    $preRequisitos; //USADO PARA RECEBER UMA COLE??O DE PREREQUISITOS
    $turmaAloca = new Aloca();
    $componente = ComponenteCurricular::obterComponenteCurricular($turmasListadas->getSiglaCurso(), $turmasListadas->getIdMatriz(), $turmasListadas->getSiglaDisciplina());

    // Alterna as cores das linhas (zebramento)
    if ($contaLinha % 2 ==0) {
        $corLinha="class='tr_turma'";
        $corHorario="class='td_turmaListaCor'";
    }
    else {
        $corLinha="class='tr_turmaSemCor'";
        $corHorario="class='td_turmaListaSemCor'";
    }

    // OBTENDO OS HORARIOS PARA AS TURMAS
    $turmaAloca=$turmaAloca->pega_aloca($turmasListadas->getIdTurma());
    $turmaHorarios=$turmaAloca->lista_aloca(" WHERE a.idTurma = ".$turmasListadas->getIdTurma());


    echo"<tr align=center ".$corLinha." >";
    echo"<td>". $turmasListadas->getTurno() ."</td>";//Turno
    echo"<td>". $turmasListadas->getGradeHorario() ."</td>";//Grade
    echo"<td>". $turmasListadas->getSiglaDisciplina() ." ( ".$componente->getNomeDisciplina()." )</td>";//Disciplina

    //OBTEM UMA COLECAO DE PREREQUISITOS
    $preRequisitos = $componente->obterPreRequisitos();

    echo "<td>";//Pres-Requisitos
    foreach($preRequisitos as $preRequisito) {
        echo $preRequisito->getSiglaDisciplina()."; ";
    }
    echo"</td>";

    echo "<td ".$corHorario."' width=100px >";// Horarios das Turmas
    echo "<table class='table_lista'>";
    echo "<tr align=center><th> Dia </th><th> In&iacute;cio </th><th> Fim </th></tr>";
    foreach ($turmaHorarios as $turmaHorarios) {
        echo "<tr align=center><td>";
        echo $turmaHorarios->getDiaSemana();
        echo "</td><td>";
        echo $turmaHorarios->getHoraInicio();
        echo "</td><td>";
        echo $turmaHorarios->getHoraFim();
        echo "</td></tr>";
    }
    echo"</table>";
    echo"</td>";


    $turma[]=$turmasListadas->getIdTurma();
    $turmaInput=end($turma);

    echo"<td width='100px'>";
    if($jaRequerida) {
        echo "<form action='ExcluirSolicitacaoDeInscricaoEmTurmas_controle.php' method='post'>";
        echo "<input type='hidden' name='acao' value='excluirSolicitacao' />";
        echo "<input type='hidden' name='idTurma' value='". $turmaInput ."' />";
        echo "<input type='hidden' name='matriculaAluno' value='" . $matriculaAluno->getMatriculaAluno() . "' />";
        echo "<input class='cancelar' name='solicita_".$turmaInput."' ".
            "type='submit' id='turma_" . $turmaInput . "' " .
            " value='Excluir Solicita&ccedil;&atilde;o' />";
        echo "</form>";
    } else {
        echo "<form action='SolicitarInscricaoEmTurmas_controle.php?action=confirmarSolicitacao' method='post'>";
        echo "<input type='hidden' name='idPessoa' value='" . $idPessoa . "'/>";
        echo "<input type='hidden' name='idTurma' value='". $turmaInput ."' />";
        echo"<input class='".$classBotao."' name='solicita_".$turmaInput."'".
                "type='submit' id='turma_".$turmaInput.
                "' " . $controlaBotao .
                " value='Solicitar Inscri&ccedil;&atilde;o' />";
        echo "</form>";
    }
    echo"</td>";//Botao Inserir

    $contaLinha++;

?>
