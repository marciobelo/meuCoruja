<script type="text/javascript" src="/coruja/siro/javascripts/carrega_div.js"></script>

<form id="formManterAlunosQueCursamTurma_controle" name="formManterAlunosQueCursamTurma_controle" method="post">
    <input type="hidden" name="acao" id="acao" />
    <input type="hidden" name="matriculaAluno" id="matriculaAluno" />
    <input type="hidden" name="idTurma" id="idTurma" />
</form>
<script type="text/javascript">
    function obterAcao(acao,idTurma) {
        document.getElementById("formManterAlunosQueCursamTurma_controle").acao.value = acao;
        document.getElementById("formManterAlunosQueCursamTurma_controle").idTurma.value = idTurma;
        document.getElementById("formManterAlunosQueCursamTurma_controle").action = "ManterAlunosQueCursamTurma_controle.php";
        document.getElementById("formManterAlunosQueCursamTurma_controle").submit();
    }
</script>
<form name="listaTurmas" id="listaTurmas">
    <fieldset id="fieldsetGeral">

        <b>Lista de Turmas Liberadas ou Confirmadas para o Per&iacute;odo Letivo de
        <?php echo $perLetivo->getSiglaPeriodoLetivo(); ?>
        <br />Curso: <?php echo $siglaCurso.' - '.$nomeCurso; ?>
        <br />Turno: <?php echo $turno; ?>
        </b><br />
    </fieldset>
</form>

<form name="listaTurmas" id="listaTurmas" action="ManterAlunosQueCursamTurma_controle.php" method="post">
    <fieldset id="fieldsetGeral">
        <input type="hidden" id="siglaCurso" name="siglaCurso" value='<?php echo $siglaCurso?>'>
        <input type="hidden" id="nomeCurso" name="nomeCurso" value='<?php echo $nomeCurso?>'>
        <input type="hidden" id="turno" name="turno" value='<?php echo $turno?>'>
        <input type="hidden" id="periodoLetivo" name="periodoLetivo" value='<?php echo $perLetivo->getSiglaPeriodoLetivo();?>'>
<?php
        $cor = 1;

        $classeAloca = new Aloca();
        // checa se há resultados
        if(empty($listaTurmas))
        {
            echo "<b>Nenhuma turma encontrada com os par&acirc;metros fornecidos.</b>";
            echo "<br />";
        }
        else{

            echo "<table width=100%>";
            echo "<tr align='center'><th>Grade</th><th>Disciplina".
            "<font size='-2' color='#F00'> (Clique no nome da turma para ver detalhes)</font></th><th>Aloca&ccedil;&atilde;o</th></tr>";

            foreach($listaTurmas as $itens)
            {
                // SCRIPT REFERENTE A EDIÇÃO DA TURMA DE FORMA ASSINCRONA
                    $componente = ComponenteCurricular::obterComponenteCurricular($itens->getSiglaCurso(), $itens->getIdMatriz(), $itens->getSiglaDisciplina());
                    $idTurma = $itens->getIdTurma();
                    $retorna = "return {idTurma : $idTurma};";
                    $nomeclasse = ".select_editavel$idTurma";
                    $idMatriz = $itens->getIdMatriz();

                    if($cor==1){$corfundo='#00BFFF'; $cor=2;}
                    elseif($cor==2){$corfundo=''; $cor=1;}

                $lista .= "<tr bgcolor='$corfundo'><td width='7%' align='center'>";
                $lista .= $itens->getGradeHorario();
                $lista .= "</td>";
                $lista .= "<td>&nbsp;";
                                
                $lista .= "<a href=\"javascript:obterAcao('verAlunosTurma','" .$itens->getIdTurma(). "')\"> <b> ";
                $lista .= $itens->getSiglaDisciplina();
                $lista .= " - ";
                $lista .= $componente->getNomeDisciplina();
                $lista .= " </b></a>";
                $lista .="</td>";
                $lista .= "<td>&nbsp;";
                $lista .= "<br />";

                    // ALOCAÇÃO DA TURMA

                    $alocacao = $classeAloca->lista_aloca("WHERE a.idTurma = $idTurma");

                    if(empty($alocacao))
                    {

                        $lista .= "Turma sem aloca&ccedil;&atilde;o definida.<br /><br />";
                    }
                    else
                    {
                        $lista .= "<table width=100%>";
                        $lista .= "<tr align=center><th> Local </th><th> Dia </th><th> In&iacute;cio </th><th> Fim </th></tr>";

                        foreach($alocacao as $aloca)
                        {
                            $lista .= "<tr align=center><td>";
                            $lista .= $aloca->getNome();
                            $lista .= "</td><td>";
                            $lista .= $aloca->getDiaSemana();
                            $lista .= "</td><td>";
                            $lista .= $aloca->getHoraInicio();
                            $lista .= "</td><td>";
                            $lista .= $aloca->getHoraFim();
                            $lista .= "</td></tr>";

                        }

                        $lista .= "</table><br /><br />";

                    }
                
                $lista .= "</td>";
            }
            $lista .= "</table><br />";
            $lista.= "<input type='hidden' id='tipoSituacaoTurma' name='tipoSituacaoTurma' value='CONFIRMADA' />";
            $lista.= "<input type='hidden' id='siglaCurso' name='siglaCurso' value='$siglaCurso' />";
            $lista.= "<input type='hidden' id='turno' name='turno' value='$turno' />";
            $lista.= "<input type='hidden' id='idPeriodoLetivo' name='idPeriodoLetivo' value='$idPeriodoLetivo' />";
            $lista.= "<input type='hidden' id='idMatriz' name='idMatriz' value='$idMatriz' />";
            
            echo $lista;
        }
?>

    <br />
        </fieldset>
</form>

<form name="voltar" id="voltar" action="ManterAlunosQueCursamTurma_controle.php?act=main" method="post">
    <input id='button1' type='submit' value='  Voltar  '  >
</form>
