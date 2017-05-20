<script>
    function cancelar(){
        window.location.href = 'ExcluirSolicitacaoDeInscricaoEmTurmas_controle.php?action=listar';
    }
</script>

<form name="listaInscricao" method="POST" action="ExcluirSolicitacaoDeInscricaoEmTurmas_controle.php?action=excluir">
    <input type="HIDDEN" name="idTurma" value=<?php echo $idTurma ?>>
    <input type="HIDDEN" name="idPessoa" value=<?php echo $idPessoa ?>>
    <table class="table_lista" width="100%" align=CENTER id="Lista">
        <caption class="cabecalho">
            Voc&#234; solicitou exclus&#227;o para seguinte turma:
        </caption>
        <tr align=center class="tr_cabecalho_lista">
            <td>Turno </td>
            <td>Grade Horária</td>
            <td>Disciplina</td>
            <td>Pr&#233;-Requisito</td>
            <td>Horário da Aula</td>
        </tr>

        <?php

        $turmaHorarios = array (); //USADO PARA RECEBER OS HORARIOS ALOCADOS PARA A TURMA
        $turmaAloca = new Aloca();
        $turmaSolicitada=Turma::getTurmaById($idTurma);
        $componente = ComponenteCurricular::obterComponenteCurricular($turmaSolicitada->getSiglaCurso(), $turmaSolicitada->getIdMatriz(), $turmaSolicitada->getSiglaDisciplina());

        echo"<tr class='tr_turma'>";
        echo"<td>". $turmaSolicitada->getTurno(). "</td>"; //Turno
        echo"<td>". $turmaSolicitada->getGradeHorario() ."</td>"; //Grade
        echo"<td>". $turmaSolicitada->getSiglaDisciplina() ." ( ".$componente->getNomeDisciplina()." )</td>";//Disciplina

        //OBTEM UMA COLECAO DE PREREQUISITOS
        $preRequisitos = $componente->obterPreRequisitos();

        echo "<td>";//Pres-Requisitos
        foreach($preRequisitos as $preRequisito) {
            echo $preRequisito->getSiglaDisciplina()."; ";
        }
        echo"</td>";

        // OBTENDO OS HORARIOS PARA AS TURMAS
        $turmaAloca=$turmaAloca->pega_aloca($turmaSolicitada->getIdTurma());
        $turmaHorarios=$turmaAloca->lista_aloca(" WHERE a.idTurma = ".$turmaSolicitada->getIdTurma());

        echo "<td>";// Horarios das Turmas
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

        echo"</tr>";
        ?>
    </table>
    <br><br>

    <?php
    //TABELA RNs
    if($colideRN08 || !$cumpreRequisitosRN09 || $alunoRF_RN10 || ($contaRN11>=3) ||$alunoRN12 || $tIngressoRN22) {
        echo"<table class='table_RN' border=1>";
        echo"<caption class='RN_cabecalho'>";
        $alerta = htmlspecialchars("Atenção para(s) a(s) seguinte(s) regra".
                ", por favor leia atentamente: ", ENT_QUOTES, "iso-8859-1");
        echo $alerta;
        echo"</caption>";

                //VERIFICA RN08
                if($colideRN08) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN08</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN08();
                    $listaRN.=$rn->mensagemRN08()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM DA RN08

                //VERIFICA RN09
                if(!$cumpreRequisitosRN09) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN09</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN09($listaComponente);
                    $listaRN.=$rn->mensagemRN09($listaComponente)."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM DA RN09

                //VERIFICA RN10
                if($alunoRF_RN10) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN10</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN10();
                    $listaRN.=$rn->mensagemRN10()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM DA RN10
                /*
                * 3 vezes ou mais (seja por falta ou por m?dia), do mesmo curso,
                */

                //VERIFICA RN11
                if($contaRN11>=3) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN11</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN11();
                    $listaRN.=$rn->mensagemRN11()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM DA RN11

                // CASO EXISTA RN12
                if($alunoRN12) {
                    echo"<tr>";
                    echo"<td class='td_fundoRN'>RN12</td>";
                    echo"<td class='td_textoRN'>";
                    echo $rn->mensagemRN12();
                    $listaRN.=$rn->mensagemRN12()."\n\n";
                    echo "</td>";
                    echo"</tr>";
                }
                //FIM RN12

        // CASO EXISTA RN22
        if($tIngressoRN22) {
            echo"<tr>";
            echo"<td class='td_fundoRN'>RN22</td>";
            echo"<td class='td_textoRN'>";
            echo $rn->mensagemRN22();
            $listaRN.=$rn->mensagemRN22()."\n\n";
            echo "</td>";
            echo"</tr>";
        }
        //FIM RN12

        echo"</table>";
    }
    ?>



    <?php
    //TABELA BOTOES
    echo"<table border='0' align='center'>";
    echo"<tr>";
    echo"<td><input class='confirmar' name='excluirInscricao' type='submit' value='Excluir Solicita&#231;&#227;o' /></td>";
    echo"<td><input class='cancelar' name='cancelarInscricao' type='button' value='Cancelar Exclus&#227;o' onclick='cancelar();'/></td>";
    echo"</tr>";
    echo"</table>";
    ?>
    <br />
</form>
