<?php
    require_once("$BASE_DIR/includes/topo.php");
    echo '<div id="menuprincipal">';
    require_once("$BASE_DIR/includes/menu_horizontal.php");
    echo '</div>';
    echo '<div id="conteudo">';
?>
<!-- Mensagens de erro, se houver -->
<script type="text/javascript">
    function mudarCurso() {
        document.editaMatricula.acao.value="mudarCursoMatricula";
        document.editaMatricula.submit();
    }
    function cancelar() {
        document.editaMatricula.acao.value="exibirAluno";
        document.editaMatricula.submit();
    }
</script>
<?php
if(count($msgsErro)>0) {
?>
<ul class="erro">
<?php
    foreach($msgsErro as $msgErro) {
?>
    <li>
        <?php echo $msgErro; ?>
    </li>
<?php
    }
?>
</ul>
<?php
}
?>
    <form name="editaMatricula" action="/coruja/baseCoruja/controle/manterAluno_controle.php" method="post">

        <!--// PARÂMETROS QUE DEFINEM A AÇAO A SER EXECUTADA -->
        <input type="hidden" name="acao" value="salvarMatriculaEditada" />
        <input type="hidden" name="modo" value="<?php echo $formMatricula->modo; ?>" />
        <input type="hidden" name="idPessoa" value="<?php echo $formMatricula->idPessoa; ?>" />
        <input type="hidden" name="matriculaAlunoAntiga" value="<?php echo $formMatricula->matriculaAlunoAntiga; ?>" />
        <input type="hidden" name="aba" value="7" />

        <div id="conteudo_abas">
            <fieldset id="fieldsetGeral">
                <legend>Editar Matr&iacute;cula<br/></legend>
                <table>
                    <tr>
                        <td>
                            <span>Matr&iacute;cula Aluno:</span>
                        </td>
                        <td>
                            <input name="matriculaAlunoNova" size="15" maxlength="15" value="<?php echo $formMatricula->matriculaAlunoNova; ?>"/>
                            &nbsp;<font color='red'>ATEN&Ccedil;&Atilde;O: COLOQUE COM CUIDADO ESSA INFORMA&Ccedil;&Atilde;O. USE APENAS N&Uacute;MEROS.</font>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Curso:</span>
                        </td>
                        <td>
                            <select name="siglaCurso" <?php if($formMatricula->modo=="edicao") echo "onchange='javascript:mudarCurso();'"; ?> >
                                <?php
                                    if($formMatricula->siglaCurso=="")
                                            echo "<option value=''>-";
                                    foreach($formMatricula->cursos as $curso) {
                                        echo "<option value='" . $curso->getSiglaCurso() . "'";
                                        if( $curso->getSiglaCurso() == $formMatricula->siglaCurso) echo " selected";
                                        echo " >" . $curso->getSiglaCurso() . " - ". $curso->getNomeCurso();
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Data Matr&iacute;cula:</span>
                        </td>
                        <td>
                            <input name="dataMatriculaD" size="2" maxlength="2"
                                   value="<?php echo $formMatricula->dataMatriculaD; ?>">/
                            <input name="dataMatriculaM" size="2" maxlength="2"
                                   value="<?php echo $formMatricula->dataMatriculaM; ?>">/
                            <input name="dataMatriculaA" size="4" maxlength="4"
                                   value="<?php echo $formMatricula->dataMatriculaA; ?>">
                        </td>
                    </tr>

                    <?php if($formMatricula->modo=="edicao" && $formMatricula->concluido ) { ?>
                    <tr>
                        <td>
                            <span>Data Conclus&atilde;o:</span>
                        </td>
                        <td>
                            <input name="dataConclusaoD" size="2" maxlength="2"
                                   value="<?php echo $formMatricula->dataConclusaoD; ?>">/
                            <input name="dataConclusaoM" size="2" maxlength="2"
                                   value="<?php echo $formMatricula->dataConclusaoM; ?>">/
                            <input name="dataConclusaoA" size="4" maxlength="4"
                                   value="<?php echo $formMatricula->dataConclusaoA; ?>">
                        </td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td>
                            <span>Turno Ingresso:</span>
                        </td>
                        <td>
                            <select name="turnoIngresso">
                                <?php
                                    echo "<option value='MANHÃ' ";
                                    if($formMatricula->turnoIngresso=="MANHÃ") echo "selected";
                                    echo ">MANHÃ";
                                    echo "<option value='TARDE' ";
                                    if($formMatricula->turnoIngresso=="TARDE") echo "selected";
                                    echo ">TARDE";
                                    echo "<option value='NOITE' ";
                                    if($formMatricula->turnoIngresso=="NOITE") echo "selected";
                                    echo ">NOITE";
                                ?>
                            </select>
                        </td>
                    </tr>

                    <?php if($formMatricula->modo=="edicao") { ?>
                    <tr>
                        <td>
                            <span>Per&iacute;odo Letivo:</span>
                        </td>
                        <td>
                            <select name="idPeriodoLetivo">
                                <?php
                                    foreach($formMatricula->periodosLetivo as $pl) {
                                        echo "<option value='" . $pl->getIdPeriodoLetivo() . "'";
                                        if($formMatricula->idPeriodoLetivo==$pl->getIdPeriodoLetivo()) echo " selected";
                                        echo ">" . $pl->getSiglaPeriodoLetivo();
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if($formMatricula->modo=="edicao") { ?>
                    <tr>
                        <td>
                            <span>Matriz Curricular:</span>
                        </td>
                        <td>
                            <select name="idMatriz">
                                <?php
                                    foreach($formMatricula->matrizes as $matriz) {
                                        echo "<option value='" . $matriz->getIdMatriz() . "' ";
                                        if($formMatricula->idMatriz==$matriz->getIdMatriz()) echo "selected";
                                        echo ">" . $matriz->getDataInicioVigencia();
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td>
                            <span>Concurso Pontos:</span>
                        </td>
                        <td>
                            <input name="concursoPontos" size="12" maxlength="12"
                                   value="<?php echo $formMatricula->concursoPontos ?>"/>
                            <span>(use v&iacute;rgula como separador decimal. P.ex: 50,35)</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Concurso Classifica&ccedil;&atilde;o:</span>
                        </td>
                        <td>
                            <input name="concursoClassificacao" size="3" maxlength="3"
                                   value="<?php echo $formMatricula->concursoClassificacao ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span>Forma Ingresso:</span>
                        </td>
                        <td>
                            <select name="idFormaIngresso">
                                <?php
                                    foreach($formMatricula->formasIngresso as $fi) {
                                        echo "<option value='" . $fi->getIdFormaIngresso() . "'";
                                        if($formMatricula->idFormaIngresso==$fi->getIdFormaIngresso()) echo " selected";
                                        echo ">" . $fi->getDescricao();
                                    }
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="submit" value="Salvar" />&nbsp;&nbsp;
                <!-- TODO: colocar comportamento no cancelar -->
                <input type="button" value="Cancelar" onclick="javascript:cancelar();" />
            </fieldset>
        </div>
    </form>
<?php
    echo '</div>';
    require_once("$BASE_DIR/includes/rodape.php");
?>