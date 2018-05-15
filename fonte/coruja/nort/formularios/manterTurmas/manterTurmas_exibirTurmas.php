<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";

?>

<script type="text/javascript">
    function consultarTurma (idTurma){
        document.formExibir.action="<?php echo $_SERVER['PHP_SELF'] ?>?acao=consultarTurma";
        document.formExibir.idTurma.value = idTurma;
        document.formExibir.voltar_turno = "<?php echo $_SERVER['turno'] ?>"; <!-- necessário apenas para o botao volar-->
        document.formExibir.submit();
    }

    function editarTurma (idTurma){
        document.formExibir.action="<?php echo $RAIZ_CORUJA ?>/nort/controle/editarTurma_controle.php";
        document.formExibir.idTurma.value = idTurma;
        document.formExibir.voltar_turno = document.formBuscar.turno; <!-- necessário apenas para o botao volar-->
        document.formExibir.submit();
    }

    function mudarSituacaoDaTurma (idTurma){
        document.formExibir.action="<?php echo $RAIZ_CORUJA ?>/nort/controle/mudarSituacaoDaTurma_controle.php";
        document.formExibir.idTurma.value = idTurma;
        document.formExibir.voltar_turno = document.formBuscar.turno; <!-- necessário apenas para o botao volar-->
        document.formExibir.submit();
    }

    function reabrirTurmaFinalizada(idTurma) {
        document.frmReabrirTurmaFinalizada.action="<?php echo $RAIZ_CORUJA ?>/nort/controle/reabrirTurmaFinalizada_controle.php";
        document.frmReabrirTurmaFinalizada.idTurma.value = idTurma;
        document.frmReabrirTurmaFinalizada.voltar_turno = document.formBuscar.turno; <!-- necessário apenas para o botao volar-->
        document.frmReabrirTurmaFinalizada.submit();
    }

    function enviarExtratoTurmaParaProfessor(idTurma){
        document.frmEnviarExtratoTurma.idTurma.value = idTurma;
        document.frmEnviarExtratoTurma.submit();
    }
    
    function devolverPautaAoProfessor(idTurma){
        document.frmDevolverPautaAoProfessor.idTurma.value = idTurma;
        document.frmDevolverPautaAoProfessor.submit();
    }
    
    function editarPauta(idTurma)
    {
        window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=" + idTurma, "_top");
    }

    function criarTurma(){
        document.frmCriarTurma.voltar_turno = document.formBuscar.turno; <!-- necessário apenas para o botao volar-->
        document.frmCriarTurma.submit();
    }
</script>

<form name="frmEnviarExtratoTurma" method="POST" action="<?php echo $RAIZ_CORUJA ?>/interno/enviarExtratoTurmaParaProfessor/enviarExtratoTurmaParaProfessor_controle.php">
    <input type="hidden" name="idTurma" value="" >
    <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso?>">
    <input type="hidden" name="idPeriodoLetivo" value="<?php echo $idPeriodoLetivo?>">
    <input type="hidden" name="turno" value="<?php echo $_REQUEST['turno']?>"> <!-- necessário apenas para o botao volar-->
</form>

<form name="frmDevolverPautaAoProfessor" method="POST" action="<?php echo $RAIZ_CORUJA ?>/nort/controle/manterTurmas_controle.php">
    <input type="hidden" name="acao" value="devolverPautaAoProfessor" />
    <input type="hidden" name="idTurma" value="" />
    <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
    <input type="hidden" name="idPeriodoLetivo" value="<?php echo $idPeriodoLetivo; ?>" />
    <input type="hidden" name="turno" value="<?php echo $_REQUEST['turno']; ?>" /> <!-- necessário apenas para o botao voltar -->
</form>

<form name="frmCriarTurma" method="POST" action="<?php echo $RAIZ_CORUJA ?>/nort/controle/criarTurma_controle.php">
    <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso?>">
    <input type="hidden" name="idPeriodo" value="<?php echo $idPeriodoLetivo?>">
    <input type="hidden" name="voltar_turno" value="<?php echo $_POST['turno']?>"> <!-- necessário apenas para o botao volar-->
</form>

<form name="frmReabrirTurmaFinalizada" method="POST" action="<?php echo $RAIZ_CORUJA ?>/nort/controle/reabrirTurmaFinalizada_controle.php">
    <input type="hidden" name="idTurma" value="" />
    <input type="hidden" name="acao" value="exibirConfirmaReabrirTurmaFinalizada" />
    <input type="hidden" name="turno" value="<?php echo $_REQUEST['turno']?>"> <!-- necessário apenas para o botao volar-->
</form>

<form id="cadastro" name="formExibir" method="POST" action="acao ajustada por javascript" >
    <input type="hidden" name="idTurma" value="valor ajustado por javascript" >
    <input type="hidden" name="voltar_turno" value="<?php echo $_POST['turno']?>"> <!-- necessário apenas para o botao voltar-->
    
    <fieldset>

        <legend>Turmas Encontradas</legend><br>

        <table align="center" style="width: 95%; font-size: small;">
            <thead>
                <tr bgcolor="#C7F7FF" align="center">
                    <td>
                        Turno
                    </td>
                    <td>
                        Grade
                    </td>
                    <td>
                        Disciplina
                    </td>
                    <td>
                        Professor
                    </td>
                    <td>
                        Situação
                    </td>
                    <td>
                        <!-- Consular -->
                    </td>
                    <td>
                        <!-- Editar -->
                    </td>
                    <td>
                        <!-- Situação -->
                    </td>
                    <td>
                        <!-- extrato -->
                    </td>
                </tr>
            </thead>

            <?php
            $colorir = true;
            foreach ($arrayDeExibicaoDeTurmas as $auxTurma) {
                $colorir = !$colorir;
            if ($colorir) { ?>
                <tr bgcolor="#C7F7FF">
            <?php } else { ?>
                <tr>
            <?php } ?>
                <td>
                    <?php echo $auxTurma['turno'] ?>
                </td>
                <td align="center">
                    <?php echo $auxTurma['gradeHorario'] ?>
                </td>
                <td>
                    <?php echo '<b>'.$auxTurma['siglaDisciplina'].'</b> - '.$auxTurma['nomeDisciplina'] ?>
                </td>
                <td>
                    <?php echo $auxTurma['nomeProfessor'] ?>
                </td>
                <td align="center">
                    <?php echo Util::formataNome($auxTurma['tipoSituacaoTurma']) ?>
                </td>
                <td>
                    <input type="button" value="Consultar" onclick="consultarTurma(<?php echo $auxTurma['idTurma'] ?>)" >
                </td>
                <td>
                    <input type="button" value="Alterar" onclick="editarTurma(<?php echo $auxTurma['idTurma'] ?>)" >
                </td>
                <td>
                    <?php 
                            if( !($auxTurma['tipoSituacaoTurma'] == 'CANCELADA' || $auxTurma['tipoSituacaoTurma'] == 'FINALIZADA') ) { ?>
                        <input id="mudarSituacaoTurma<?php echo $auxTurma['idTurma'] ?>" type="button" 
                               value="Mudar Situação" onclick="mudarSituacaoDaTurma(<?php echo $auxTurma['idTurma'] ?>)" >
                    <?php   } else if($auxTurma['tipoSituacaoTurma'] == 'FINALIZADA' ) { ?>
                        <input type="button" value="Reabrir Turma" onclick="reabrirTurmaFinalizada(<?php echo $auxTurma['idTurma'] ?>)" >
                    <?php   } ?>
                </td>
                <td>
                    <input type="button" value="Enviar Extrato ao Professor" onclick="enviarExtratoTurmaParaProfessor(<?php echo $auxTurma['idTurma'] ?>)" >
                    <?php if( $auxTurma['dataLiberacaoPautaPeloProfessor'] != null && $auxTurma['tipoSituacaoTurma'] == 'CONFIRMADA' ) { ?>
                    <input type="button" value="Devolver Pauta ao Professor" onclick="devolverPautaAoProfessor(<?php echo $auxTurma['idTurma'] ?>)" >
                    <?php } else { ?>
                    <input type="button" value="Editar Pauta" onclick="editarPauta(<?php echo $auxTurma['idTurma'] ?>)" >
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>

        </table>
        <input type="button" value="Criar Turma" onclick="criarTurma()">
    </fieldset>
</form>

<?php
if(!empty ($arrayDeTurmasNaoOfertadas)){
?>
<form id="cadastro" name="formExibirDiscNaOfer" method="POST" action="acao ajustada por javascript" >
    <fieldset>

        <legend>Disciplinas não ofertadas</legend><br>

        <table align="center" style="width: 95%">
            <thead>
                <tr bgcolor="#C7F7FF" align="center">
                    <td>
                        Periodo
                    </td>
                    <td>
                        Turno
                    </td>
                    <td>
                        Disciplina
                    </td>
                </tr>
            </thead>

            <?php
            $colorir = true;
            foreach ($arrayDeTurmasNaoOfertadas as $auxDisciplina) {
                $colorir = !$colorir;
            if ($colorir) { ?>
                <tr bgcolor="#C7F7FF">
            <?php } else { ?>
                <tr>
            <?php } ?>
                <td align="center">
                    <?php echo $auxDisciplina['periodo'] ?>
                </td>
                <td align="center">
                    <?php echo $turno ?>
                </td>
                <td>
                    <?php echo '<b>'.$auxDisciplina['siglaDisciplina'].'</b> - '.$auxDisciplina['nomeDisciplina'] ?>
                </td>
            </tr>
            <?php } ?>

        </table>
    </fieldset>
</form>
<?php
} //Fim do IF
?>