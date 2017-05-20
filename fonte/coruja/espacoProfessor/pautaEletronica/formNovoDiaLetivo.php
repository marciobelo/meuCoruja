<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>

<?php include "$BASE_DIR/espacoProfessor/pautaEletronica/trechoCabecTurma.php"; ?>

<script type="text/javascript">

$(function () {
    $("#dataBr").datepicker({
    monthNamesShort: [ "Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez" ],
    monthNames: [ "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro" ],
    dayNames: [ "Domingo", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado" ],
    dayNamesMin: [ "Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sab" ],
    minDate: new Date( <?php echo $dtInicio->format("Y") ?>, <?php echo $dtInicio->format("m") ?>-1, <?php echo $dtInicio->format("d") ?>),
    maxDate: new Date( <?php echo $dtFim->format("Y") ?>, <?php echo $dtFim->format("m") ?>-1, <?php echo $dtFim->format("d") ?>),
    dateFormat: "dd/mm/yy",
    onSelect: mudarData });
    $("#dataBr").focus();
});
    
function mudarData(data) {
    window.open("/coruja/espacoProfessor/pautaEletronica/criarNovoDiaLetivo_controle.php?acao=carregarTemposAula&idTurma=" + $("#idTurma").val() + "&dataBr=" + data, "_top" );
}

function voltar() {
    window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>&data=<?php echo $data; ?>","_top");
}

function confirma() {
    var diaExistente = <?php if($diaLetivoTurma->estaPersistido()) echo "true"; else echo "false";  ?>;
    if( diaExistente ) {
        return window.confirm("Os apontamentos desse dia serão perdidos. Deseja continuar?");
    } else {
        return true;
    }
}
</script>

<div id="msgsErro">
    <!-- Mensagens de erro, se houver -->
    <?php
    if(count($msgsErro) > 0) {
    ?>
    <ul class="erro">
    <?php
        foreach($msgsErro as $msgErro) {
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
</div>

<form id="formCriaNovoDiaLetivo" 
      action="/coruja/espacoProfessor/pautaEletronica/criarNovoDiaLetivo_controle.php"
      method="post"
      onsubmit="return confirma();">
    <input type="hidden" id="acao" name="acao" value="salvarDiaLetivo" />
    <input type="hidden" id="idTurma" name="idTurma" value="<?php echo $turma->getIdTurma(); ?>" />
    <input type="hidden" id="data" name="data" value="<?php echo $data; ?>" />

    <span class="rotulo">Data:</span>
    <input name="dataBr" id="dataBr" type="text" size="10" maxlength="10"
           value="<?php echo $dataBr; ?>"
           tabindex="1" />
    <div>
    <?php 
    if( !isset($temposDiaSemana) || count($temposDiaSemana) == 0 ) {
    ?>
    <p>Dia da semana sem tempos para aloca&ccedil;&atilde;o</p>
    <?php
    } else {
        foreach($temposDiaSemana as $tempoSemanal) {
            echo "<input type=\"checkbox\" name=\"listaIdTempoSemanal[]\" value=\"" . $tempoSemanal->getIdTempoSemanal() . "\" " .
                    gerarSelecao($tempoSemanal->getIdTempoSemanal(), $temposSelecionados) . "/>";
            echo $tempoSemanal->getHoraInicio() . " - "  . $tempoSemanal->getHoraFim();
            echo "<br/>\n";
        }
    }
    ?>
    </div>
    <input type="submit" value="Salvar" />
    <input type="button" value="Voltar" onclick="voltar();"/>
</form>

<?php
require_once "$BASE_DIR/includes/rodape.php";

function gerarSelecao($id, array $selecionados) {
    foreach($selecionados as $selecionado) {
        if( $id == $selecionado->getIdTempoSemanal() ) {
            return "checked";
            break;
        }
    }
    return "";
}
?>
