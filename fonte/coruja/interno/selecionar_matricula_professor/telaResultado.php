<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<fieldset id="coruja">
    <legend><?php echo $controleDestinoTitulo; ?><br/>
        Pesquisa por <?php echo $criterio; ?> de Professor</legend>

    <table border="1">
        <thead>
            <tr>
                <td>Matr&iacute;cula</td>
                <td>Nome do Professor</td>
                <td>Carga Horária</td>
                <td>Data de Início</td>
                <td>Data de Encerramento</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php //print_r($listaMatriculasProfessor);
           foreach($listaMatriculasProfessor as $professor) {
            ?>
                <?php
                foreach($professor->getMatriculasProfessor() as $matriculaProfessor) {
                ?>

            <tr>
                <td>
                    <?php echo $matriculaProfessor->getMatriculaProfessor(); ?>
                </td>
                <td>
                    <?php echo $professor->getNome(); ?>
                </td>
                <td>
                    <?php echo $matriculaProfessor->getCargaHoraria(); ?>
                </td>
                <td>
                    <?php echo Util::dataSQLParaBr($matriculaProfessor->getDataInicio()); ?>
                </td>
                 <td>
                    <?php echo Util::dataSQLParaBr($matriculaProfessor->getDataEncerramento()); ?>
                </td>
                <td>
                    <form action="/coruja/interno/manter_professor/manterProfessor_controle.php">
                        <input type="hidden" name="acao" value="ExibirDados" />
                        <input type="hidden" name="matriculaProfessor" value="<?php echo $matriculaProfessor->getMatriculaProfessor(); ?>">
                        <input type="hidden" name="idPessoa" value="<?php echo $matriculaProfessor->getIdPessoa(); ?>"/>
                        <input type="submit" value="Selecionar" />
                    </form>
                </td>
            </tr>
                   
                <?php
                }
                ?>
            <?php
            }
            ?>
        </tbody>
    </table>
    <form method="GET" action="/coruja/interno/selecionar_matricula_professor/selecionarMatricula_controle.php?acao=exibirFiltroPesquisa">
        <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
             <input type="hidden" name="controleDestino" value="/coruja/interno/selecionar_matricula_professor/manterProfessor_controle.php" />
        <input type="hidden" name="acaoControleDestino" value="<?php echo $acaoControleDestino; ?>" />
        <input type="hidden" name="controleDestinoTitulo" value="<?php echo $controleDestinoTitulo; ?>" />
        <input type="hidden" name="tipoBusca" value="<?php echo $tipoBusca; ?>" />
        <input type="submit" value="Voltar" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>