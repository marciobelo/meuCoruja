<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<fieldset id="coruja">
    <legend>Administrar Matr&iacute;culas<br/><?php echo htmlspecialchars($nomeCurso, ENT_QUOTES, "iso-8859-1"); ?></legend>
    <table border="1" width="100%">
        <tr>
            <td colspan="12">
                Resumo Sint&eacute;tico da Situa&ccedil;&otilde;es de Matr&iacute;culas do Curso<br/>
            </td>
        </tr>
        <tr>
            <td>
                Cursando:
            </td>
            <td>
                <?php echo $totalCURSANDO; ?>
            </td>
            <td>
                Trancado:
            </td>
            <td>
                <?php echo $totalTRANCADO; ?>
            </td>
            <td>
                Evadido:
            </td>
            <td>
                <?php echo $totalEVADIDO; ?>
            </td>
            <td>
                Concluído:
            </td>
            <td>
                <?php echo $totalCONCLUIDO; ?>
            </td>
            <td>
                Desistente:
            </td>
            <td>
                <?php echo $totalDESISTENTE; ?>
            </td>
            <td>
                Desligado:
            </td>
            <td>
                <?php echo $totalDESLIGADO; ?>
            </td>
        </tr>
    </table>
<table>
    <tr>
        <td>
            <form action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
                <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
                <input type="hidden" name="controleDestino" value="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php" />
                <input type="hidden" name="acaoControleDestino" value="verHistoricoMatricula" />
                <input type="hidden" name="controleDestinoTitulo" value="Visualizar Hist&oacute;rico de Matr&iacute;cula" />
                <input type="submit" value="Ver Hist&oacute;rico de Matr&iacute;cula" />
            </form>
        </td>
        <td>
            <form action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
                <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
                <input type="hidden" name="controleDestino" value="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php" />
                <input type="hidden" name="acaoControleDestino" value="exibirConfirmacaoReativarMatricula" />
                <input type="hidden" name="controleDestinoTitulo" value="Reativar Matr&iacute;cula" />
                <input type="submit" value="Reativar Matr&iacute;cula" />
            </form>
        </td>
        <td>
            <form action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
                <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
                <input type="hidden" name="controleDestino" value="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php" />
                <input type="hidden" name="acaoControleDestino" value="exibirConfirmacaoRenovarMatricula" />
                <input type="hidden" name="controleDestinoTitulo" value="Renovar Matr&iacute;cula" />
                <input type="submit" value="Renovar Matr&iacute;cula" />
            </form>
        </td>
        <td>
            <form action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
                <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
                <input type="hidden" name="controleDestino" value="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php" />
                <input type="hidden" name="acaoControleDestino" value="exibirConfirmacaoTrancamentoMatricula" />
                <input type="hidden" name="controleDestinoTitulo" value="Trancar Matr&iacute;cula" />
                <input type="submit" value="Trancar Matr&iacute;cula" />
            </form>
        </td>
        <td>
            <form action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
                <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
                <input type="hidden" name="controleDestino" value="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php" />
                <input type="hidden" name="acaoControleDestino" value="exibirConfirmacaoConcluirMatricula" />
                <input type="hidden" name="controleDestinoTitulo" value="Concluir Matr&iacute;cula" />
                <input type="submit" value="Concluir Matr&iacute;cula" />
            </form>
        </td>
        <td>
            <form action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
                <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
                <input type="hidden" name="controleDestino" value="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php" />
                <input type="hidden" name="acaoControleDestino" value="exibirConfirmacaoDesistirMatricula" />
                <input type="hidden" name="controleDestinoTitulo" value="Desistir de Matr&iacute;cula" />
                <input type="submit" value="Desistir de Matr&iacute;cula" />
            </form>
        </td>
        <td>
            <form action="/coruja/interno/selecionar_matricula_aluno/selecionarMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirFiltroPesquisa" />
                <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
                <input type="hidden" name="controleDestino" value="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php" />
                <input type="hidden" name="acaoControleDestino" value="exibirConfirmacaoDesligarMatricula" />
                <input type="hidden" name="controleDestinoTitulo" value="Desligar Matr&iacute;cula" />
                <input type="submit" value="Desligar Matr&iacute;cula" />
            </form>
        </td>
    </tr>
</table>
<table border="1">
    <tr>
        <td>
            &Uacute;ltimo Per&iacute;odo Letivo:
        </td>
        <td>
            <?php echo $siglaPeriodoLetivoVigente . " (" . Util::dataSQLParaBr($dataInicioPeriodoLetivoVigente) . " at&eacute; " . Util::dataSQLParaBr($dataFimPeriodoLetivoVigente) . ")"; ?>
        </td>
    </tr>
    <tr>
        <td>
            Data Fim Inscrição em Disciplinas:
        </td>
        <td>
            <?php echo Util::dataSQLParaBr($dataLimiteInscricaoDisciplina); ?>
        </td>
    </tr>
    <tr>
        <td>
            Data Fim Pedido de Trancamento:
        </td>
        <td>
            <?php echo Util::dataSQLParaBr($dataLimitePedidoTrancamento); ?>
        </td>
    </tr>
</table>
<br/>
<form action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php">
    <input type="hidden" name="acao" value="exibirConfirmacaoRematriculaAutomatica" />
    <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
    <input type="submit" value="Processar Rematr&iacute;cula Autom&aacute;tica" />
</form>
<table border="1">
    <tr>
        <td colspan="7" align="center">Rela&ccedil;&atilde;o de Matr&iacute;culas Desatualizadas</td>
    </tr>
    <tr>
        <td>Matr&iacute;cula</td>
        <td>Nome</td>
        <td>Situação</td>
        <td>Data Atualiza&ccedil;&atilde;o</td>
        <td>Observa&ccedil;&atilde;o</td>
        <td colspan="2">&nbsp;</td>
    </tr>
    <?php
    if(count($matriculasDesatualizadas)==0) {
    ?>
    <tr><td colspan="7" align="center">N&atilde;o h&aacute; registros.</td></tr>
    <?php
    } else {
        foreach($matriculasDesatualizadas as $matDes) {
    ?>
    <tr>
        <td><a name="<?php echo $matDes["matriculaAluno"]; ?>"><?php echo $matDes["matriculaAluno"] ?></a></td>
        <td><?php echo htmlspecialchars($matDes["nomeAluno"], ENT_QUOTES, "iso-8859-1"); ?></td>
        <td><?php echo $matDes["situacaoMatricula"]; ?></td>
        <td><?php echo Util::dataSQLParaBr($matDes["dataAtualizacao"]); ?></td>
        <td><?php echo htmlspecialchars($matDes["textoAtualizacao"], ENT_QUOTES, "iso-8859-1"); ?></td>
        <td>
            <form action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php">
                <input type="hidden" name="acao" value="verHistoricoMatricula" />
                <input type="hidden" name="matriculaAluno" value="<?php echo $matDes["matriculaAluno"] ?>" />
                <input type="submit" value="Ver Hist&oacute;rico" />
            </form>
        </td>
        <td>
            <?php if($matDes["situacaoMatricula"] == MatriculaAluno::CURSANDO ) {?>
            <form action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php">
                <input type="hidden" name="acao" value="exibirConfirmacaoRenovarMatricula" />
                <input type="hidden" name="matriculaAluno" value="<?php echo $matDes["matriculaAluno"] ?>" />
                <input type="submit" value="Renovar Matr&iacute;cula" />
            </form>
            <?php } ?>
        </td>
    </tr>
    <?php
        } // fecha o for
    } // fecha o else
    ?>
</table>
</fieldset>

<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
