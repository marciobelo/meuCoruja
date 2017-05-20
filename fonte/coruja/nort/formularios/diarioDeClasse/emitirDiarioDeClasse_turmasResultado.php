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
    function gerar(){
        temSelecionado = false;
        listaDeInputs = document.formSelecionaTurmas.getElementsByTagName('INPUT');
        for(i=0;i < listaDeInputs.length;i++){
            if(listaDeInputs[i].checked == true){
                temSelecionado = true;
            }
        }
        if(temSelecionado){
            document.formSelecionaTurmas.submit();
        }else{
            alert("Escolha uma ou mais turmas");
        }
    }
</script>

<form id="cadastro" name="formSelecionaTurmas" action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=gerarPDF" method="POST">
    <fieldset>
        <legend>Turmas</legend>
        <table>
            <tbody>
                <tr>
                    <th></th>
                    <th>Disciplina</th>
                    <th>Professor</th>
                    <th align="center">Turno</th>
                    <th align="center">Grade</th>
                    <th align="center">Período</th>
                    <th align="center">Situação</th>
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

                    <td> <input type=checkbox name=arrayTurmas[] value=" <?php echo $turma['idTurma'] ?> "> </td>
                    <td><?php echo $turma['siglaDisciplina'] . ' - ' . $turma['nomeDisciplina'] ?></td>
                    <?php if ($turma['nome'] != null) { ?>
                        <td><?php echo $turma['nome'] ?></td>
                    <?php } else { ?>
                        <td><font color="red">Sem Professor</font></td>
                    <?php } ?>
                    <td><center><?php echo $turma['turno'] ?></center></td>
                    <td><center><?php echo $turma['gradeHorario'] ?></center></td>
                    <td><center><?php echo $turma['siglaPeriodoLetivo'] ?></center></td>
                    <td><center><?php echo $turma['tipoSituacaoTurma'] ?></center></td>

                    </tr>
                <?php } ?>
                <tr>
                    <td></td>
                    <td>
                        <!-- <input type="submit" value="Gerar" name="Gerar"> -->
                        <input type="button" onclick="gerar();" value="Gerar" name="btGerar">
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</form>

