<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<div id="msgsErro">
    <!-- Mensagens de erro, se houver -->
    <?php
    if(count($msgs) > 0) {
    ?>
    <ul class="erro">
    <?php
        foreach($msgs as $msg) {
    ?>
        <li>
            <?php echo htmlspecialchars($msg, ENT_QUOTES, "iso-8859-1"); ?>
        </li>
    <?php
        }
    ?>
    </ul>
    <?php
    }
    ?>
</div>

<form id="cadastro" name="formBuscar" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=exibirTurmas" >
    <fieldset>
        <legend>Manter Turmas</legend><br>
        <table>
            <tr>
                <td>
                    Curso:
                </td>
                <td>
                    <select name="siglaCurso" onChange="document.formBuscar.action = '<?php echo $_SERVER['PHP_SELF'] ?>?acao=informarPeriodoLetivo';document.formBuscar.submit()">
                        <option value="">Selecione</option>
                        <?php
                        foreach ($listaDeCursos as $curso) {
                            $selected = $curso->getSiglaCurso() == $siglaCurso ? 'selected' : '';
                            echo '<option ' . $selected . ' value="' . $curso->getSiglaCurso() . '" >' . $curso->getSiglaCurso() . ' - ' . $curso->getNomeCurso() . '</option>';
                        }
                        ?>
                    </select>
                </td>

            <tr>
                <td>Per&iacute;odo Letivo:</td>
                <td>
                    <select name="idPeriodoLetivo" <?php echo $estaDesabilitado ?> >
                        <?php
                        foreach ($listaDePeriodosLetivos as $periodoLetivo) {
                            if($acao == 'informarPeriodoLetivo'){
                                //Primeiro período letivo é selecionado
                                $selected = ( $periodoLetivo->getIdPeriodoLetivo() == $listaDePeriodosLetivos[0] ? 'selected' : '' );
                            } else {
                                $selected = ( $periodoLetivo->getIdPeriodoLetivo() == $_REQUEST['idPeriodoLetivo'] ? 'selected' : '' );
                            }
                            echo '<option ' . $selected . ' value="' . $periodoLetivo->getIdPeriodoLetivo() . '" >' . $periodoLetivo->getSiglaPeriodoLetivo().'</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Turno:</td>
                <td>
                    <select name="turno" <?php echo $estaDesabilitado ?> >
                        <?php
                        foreach ($listaDeTurnos as $turno) {
                            $selected = ( $turno == $_REQUEST['turno'] ? 'selected' : '' );
                            echo '<option ' . $selected . ' value="' . $turno . '" >' . $turno . '</option>';
                        }
                        ?>
                    </select>
                    <font style="font-size: 12px">(Selecionando um turno espec&iacute;fico ser&atilde;o informadas as disciplinas ainda n&atilde;o ofertadas)</font>
                </td>
            </tr>
            <tr>
                <td></td>

                <td>
                    <input type="submit" value="Filtrar" name="Filtrar" <?php echo $estaDesabilitado ?> >
                </td>
            </tr>
        </table>
    </fieldset>
</form>
