<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<form id="cadastro" name="formEditarNotas" method="POST" action="editarNotas_controle.php" >
    <input type="hidden" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>">
    <input type="hidden" name="numMatriculaAluno" value="ajustadoPorJavascrit">
    <input type="hidden" name="voltar_turno" value="<?php echo $_POST['voltar_turno']; ?>">
    <fieldset>
        <legend>Lançar Notas</legend>

            <table>
                <tr>
                    <td width="110">Curso</td>
                    <td><?php echo $turma->getCurso()->getNomeCurso(); ?></td>
                </tr>
                <tr>
                    <td>Período Letivo</td>
                    <td><?php echo $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(); ?></td>
                </tr>
                <tr>
                    <td>Turno</td>
                    <td><?php echo $turma->getTurno(); ?></td>
                </tr>
                <tr>
                    <td>Grade</td>
                    <td><?php echo $turma->getGradeHorario(); ?></td>
                </tr>
                <tr>
                    <td>Disciplina</td>
                    <td><?php echo $turma->getSiglaDisciplina() . ' - ' . $turma->getComponenteCurricular()->getNomeDisciplina(); ?></td>
                </tr>
                <tr>
                    <td>Professor</td>
                    <td>
                    <?php
                    if($turma->getProfessor()==null) {
                        echo "Sem professor";
                    } else {
                        echo $turma->getProfessor()->getNome();
                    }
                    ?>
                    </td>
                </tr>
            </table>
            <br />
            <table>
                <tr>
                    <td>
                        Matrícula
                    </td>
                    <td align="center">
                        Nome
                    </td>
                    <td>
                        Média Final &nbsp;&nbsp;
                    </td>
                    <td>
                        Total de Faltas &nbsp;&nbsp;
                    </td>
                    <td>
                        Situação &nbsp;&nbsp;
                    </td>
                    <td>
                        <!-- Opção Editar-->
                    </td>
                </tr>
                <?php
                $colorir = TRUE;
                foreach ($listaDeIncricoes as $incricao) {
                    if ($colorir) {
                        ?><tr bgcolor="#C7F7FF"><?php
                    } else {
                        ?><tr><?php
                    }
                    $colorir = !$colorir;
                    ?>
                    <td>
                        <?php echo $incricao->getMatriculaAluno() ;?>
                    </td>
                    <td>
                        <?php echo $incricao->getNomeAluno() ;?>
                    </td>
                    <td align="center">
                        <?php echo $incricao->getMediaFinal()==""?"N.D.":$incricao->getMediaFinal() ;?>
                    </td>
                    <td align="center">
                        <?php echo $incricao->getTotalFaltas()==""?"N.D.":$incricao->getTotalFaltas() ;?>
                    </td>
                    <td align="center">
                        <?php echo $incricao->getSituacaoInscricao() ;?>
                    </td>
                    <td>
                        <input type="button" value="Editar" onclick="document.formEditarNotas.numMatriculaAluno.value = '<?php echo $incricao->getMatriculaAluno() ;?>';document.formEditarNotas.submit();">
                        <!--
                        <input type="button" value="Editar" onclick="document.formEditarNotas.idTurma = <?php echo $turma->getIdTurma() ;?><?php echo $incricao->getMatriculaAluno() ;?> - ');">
                        -->
                    </td>
                </tr>
                <?php
                }
                ?>
            </table>
            <input type="button" value="Voltar" onclick="document.formVoltar.submit();" style="width: 100px">
    </fieldset>
</form>

<form name="formVoltar" action="lancarNotas_controle.php?acao=buscarTurmasResultado" method="POST">
    <input type="hidden" name="siglaCurso" value = "<?php echo $turma->getSiglaCurso(); ?>">
    <input type="hidden" name="idPeriodoLetivo" value = "<?php echo $turma->getIdPeriodoLetivo(); ?>">
    <input type="hidden" name="turno" value = "<?php echo $_POST['voltar_turno']; ?>">
</form>