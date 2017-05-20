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

<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<form id="cadastro" name="cadastro" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=buscarTurmasFiltro" >
    <fieldset>
        <legend>Diário de Classe</legend><br>
        <font size="-1" color="#FF0000">Os campos marcados com (*) são obrigatórios!</font>
        <table>
            <tr>
                <td>
                    Curso(*):
                </td>
                <td>
                    <select name="siglaCurso" onChange="document.cadastro.submit()">
                        <option value="">Selecione</option>
                        <?php
                        foreach ($arrayCursos as $curso) {
                            $selected = $curso->getSiglaCurso() == $siglaCurso ? 'selected' : '';
                            echo '<option ' . $selected . ' value="' . $curso->getSiglaCurso() . '" >' . $curso->getSiglaCurso() . ' - ' . $curso->getNomeCurso() . '</option>';
                        }
                        ?>
                    </select>
                </td>

            <tr>
                <td>
                    Período Letivo(*):
                </td>
                <td>
                    <select name="idPeriodoLetivo" onChange="document.cadastro.submit()" <?php echo $estaDesabilitadoPeriodo ?> >
                        <option value="">Selecione</option>
                        <?php
                        foreach ($arrayPeriodoLetivo as $periodoLetivo) {
                            $selected = $periodoLetivo->getIdPeriodoLetivo() == $_POST['idPeriodoLetivo'] ? 'selected' : '';
                            echo '<option ' . $selected . ' value="' . $periodoLetivo->getIdPeriodoLetivo() . '" >' . $periodoLetivo->getSiglaPeriodoLetivo() . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Disciplina:
                </td>
                <td>
                    <select name="siglaDisciplina" <?php echo $estaDesabilitado ?> >
                        <option value="">Todos</option>
                        <?php
                        foreach ($arraySiglaNomeDisciplina as $siglaDisciplina => $nomeDisciplina) {
                            $selected = $siglaDisciplina == $_POST['siglaDisciplina'] ? 'selected' : '';
                            echo '<option ' . $selected . ' value="' . $siglaDisciplina . '" >' . $siglaDisciplina . ' - ' . $nomeDisciplina . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Professor:
                </td>
                <td>
                    <select name="idPessoaProfessor" <?php echo $estaDesabilitado ?> >
                        <option value="">Todos</option>
                        <?php
                        foreach ($arrayIdNomeProfessor as $idPessoa => $nome) {
                            $selected = $idPessoa == $_POST['idPessoaProfessor'] ? 'selected' : '';
                            echo '<option ' . $selected . ' value="' . $idPessoa . '" >' . $nome . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>

                <td>
                    <!-- <input type="submit" value="Filtrar" name="Filtrar" <?php echo $estaDesabilitado ?> > -->
                    <input type="button" value="Filtrar" name="Filtrar" onclick="document.cadastro.action='<?php echo $_SERVER['PHP_SELF'] ?>?acao=buscarTurmasResultado';document.cadastro.submit()" <?php echo $estaDesabilitado ?> >
                </td>
            </tr>
        </table>
    </fieldset>
</form>
