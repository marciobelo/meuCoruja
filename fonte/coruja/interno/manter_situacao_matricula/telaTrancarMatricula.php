<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script type="text/javascript">
    function validar() {
        if(document.trancaMatricula.texto.value=="") {
            window.alert("Campo Justificativa é obrigatório.");
            document.trancaMatricula.texto.focus();
            return false;
        }
        return true;
    }
</script>

<!-- Mensagens de erro, se houver -->
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

<fieldset id="coruja">
    <legend>Trancar Matr&iacute;cula</legend>
    <?php require_once "$BASE_DIR/interno/manter_situacao_matricula/verHistoricoMatricula.php"; ?>
    <!-- alertas de impedimento -->
    <?php
    if(($ma->getSituacaoMatricula() != "CURSANDO") && ($ma->getSituacaoMatricula() != "EVADIDO") 
            && ($ma->getSituacaoMatricula() != "TRANCADO") ) {
    ?>
    <p class="erro">Essa matr&iacute;cula n&atilde;o pode ser trancada pois se encontra em situa&ccedil;&atilde;o que não permite essa operação. Opera&ccedil;&atilde;o Cancelada.</p>
    <?php
    } else {
    ?>
    <?php
        // verifica se já trancou duas vezes
        if($ma->obterQtdeTrancamentos()>=2) {
    ?>
    <p class="erro"><?php echo 
    htmlspecialchars("ATENÇÃO: essa matrícula já foi TRANCADA duas vezes. A justificativa para novo trancamento deve conter a página e data da Ata do Conselho Acadêmico que autorizou novo trancamento.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <?php
        }
    ?>
    <form method="post" name="trancaMatricula"
          action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php"
          onsubmit="return validar();" >
        <input type="hidden" name="acao" value="trancarMatricula" />
        <input type="hidden" name="matriculaAluno" value="<?php echo $matriculaAluno; ?>" />
        <p>
            Justificativa Trancamento de Matr&iacute;cula<br/>
            <textarea name="texto" rows="4" cols="40" tabindex="1" ></textarea>
        </p>
        <input type="submit" value="Trancar Matr&iacute;cula" />
    </form>
    <?php
    }
    ?>
    <form method="post" action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php#<?php echo $matriculaAluno; ?>">
        <input type="hidden" name="acao" value="exibirSituacoesMatriculasCurso" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="submit" value="Voltar" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>
