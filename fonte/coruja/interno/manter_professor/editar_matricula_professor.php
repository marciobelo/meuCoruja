<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript" src="/coruja/baseCoruja/javascripts/carrega_div.js"></script>

<?php
if (count($msgsErro) > 0) {
?>
    <ul class="erro">
<?php
    foreach ($msgsErro as $msgErro) {
?>
        <li>
<?php echo htmlspecialchars($msgErro, ENT_QUOTES, "iso-8859-1"); ?>
        </li>
<?php
    }
?>
</ul>
<?php
}
?>

<form id="cadastro" method="post" name="cadastro" action="/coruja/interno/manter_professor/manterProfessor_controle.php" >

    <fieldset id="fieldsetGeral">
        <input type="hidden" name="modo" value="edicao" />
        <input type="hidden" name="acao" value="salvarMatriculaEditada" />
        <input type="hidden" name="idPessoa" value="<?php echo $_REQUEST['idPessoa']; ?>" />
        <input type="hidden" name="matriculaProfessorAntiga" value="<?php echo $formMatricula->matriculaProfessorAntiga; ?>" />

        <legend><br/>
            ALTERAR DADOS DA MATR&Iacute;CULA DO PROFESSOR <br/>
                <?php echo htmlspecialchars($_REQUEST['pessoa'], ENT_QUOTES, "iso-8859-1"); ?></legend>
        <br />

        <div class="row" id="didfv1" >
            <table width="1008">
                <tr>
                    <td width="160">Matricula :
                        <input name="matriculaProfessorNova" id="matriculaProfessorNova" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="10" value="<?php echo $formMatricula->matriculaProfessorNova; ?>" /></td>
                    <td width="218">Carga Horária :
                        <input id="cargaHoraria" type="text" onchange="this.value=this.value.toUpperCase();" style="text-transform: uppercase;" maxlength="80" size="10" value="<?php echo $formMatricula->cargaHoraria; ?>" name="cargaHoraria" /></td>
                    <td width="251">Data de In&iacute;cio:
                        <input id="dataInicioD" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $formMatricula->dataInicioD; ?>" name="dataInicioD" />
                        /
                        <input id="dataInicioM" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $formMatricula->dataInicioM; ?>" name="dataInicioM" />
                        /
                        <input id="dataInicioA" class="" type="text" onchange="" maxlength="4" size="4" value="<?php echo $formMatricula->dataInicioA; ?>" name="dataInicioA" /></td>
                    <td width="284">Data de Encerramento:
                        <input id="dataEncerramentoD" name="dataEncerramentoD" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $formMatricula->dataEncerramentoD; ?>" />
                        /
                        <input id="dataEncerramentoM" name="dataEncerramentoM" class="" type="text" onchange="" maxlength="2" size="2" value="<?php echo $formMatricula->dataEncerramentoM; ?>" />
                        /
                        <input id="dataEncerramentoA" name="dataEncerramentoA" class="" type="text" onchange="" maxlength="4" size="4" value="<?php echo $formMatricula->dataEncerramentoA; ?>" /></td>
                </tr>
            </table>

        </div>



    </fieldset>

    <div align="center" class="row">
        <input type="submit" id="button1" value="Salvar" />
    </div>
    <br />
    <br />
</form>

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>