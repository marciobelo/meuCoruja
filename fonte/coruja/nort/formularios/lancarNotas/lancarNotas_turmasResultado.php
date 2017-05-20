<?php
/*
 *      IMPORTANTE
 *  ESTE ARQUIVO NÃO FAZ PARTE DO UC MANTER TURMAS
 *
 * Este arquivo é utilizado pelos casos de uso:
 *      UC01.07.00 - Emitir listagem de alunos por turma
 *      UC01.04.00 - Emitir diário de classe
 *
 */
?>

<script type="text/javascript">
    function consultarNotasDaTurma(idTurma){
        document.formSelecionaTurma.idTurma.value = idTurma;
        document.formSelecionaTurma.submit();
    }
</script>

<form id="cadastro" name="formSelecionaTurma" action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=consultarNotas" method="POST">
    <input type="hidden" name="idTurma" value="ajustadoPorJavascrit">
    <input type="hidden" name="voltar_turno" value="<?php echo $_POST['turno'] ?>">
    <fieldset>
        <legend>Turmas</legend>
        <table>
            <tbody>
                <tr>
                    <th align="center">Turno</th>
                    <th align="center">Grade</th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                    <th align="center">Período</th>
                    <th align="center">Situação</th>
                    <th></th>
                </tr>

                <?php
                $colorir = False;
                foreach ($arrayTurmas as $turma) {
                    if ($colorir) {
                        ?><tr bgcolor="#C7F7FF"><?php
                    } else {
                        ?><tr><?php
                    }
                    $colorir = !$colorir;
                ?>
                    <td><center><?php echo $turma['turno'] ?></center></td>
                    <td><center><?php echo $turma['gradeHorario'] ?></center></td>
                    <td><?php echo $turma['siglaDisciplina'] . ' - ' . $turma['nomeDisciplina'] ?></td>
                    <?php if ($turma['nome'] != null) { ?>
                        <td><?php echo $turma['nome'] ?></td>
                    <?php } else { ?>
                        <td><font color="red">Sem Professor</font></td>
                    <?php } ?>
                    <td><center><?php echo $turma['siglaPeriodoLetivo'] ?></center></td>
                    <td><center><?php echo $turma['tipoSituacaoTurma'] ?></center></td>
                    <td> <input type=button name=btVisualizar onclick="consultarNotasDaTurma(<?php echo $turma['idTurma'] ?>);" value=" Consultar Notas "> </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </fieldset>
</form>

