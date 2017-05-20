<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script type="text/javascript">
    function validar() {
        if(document.reativaMatricula.texto.value=="") {
            window.alert("Campo Justificativa é obrigatório.");
            document.reativaMatricula.texto.focus();
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
    <legend>Reativar Matr&iacute;cula</legend>
    <?php require_once "$BASE_DIR/interno/manter_situacao_matricula/verHistoricoMatricula.php"; ?>
    <!-- alertas de impedimento -->
    <?php
    if( $ma->getSituacaoMatricula() == "CURSANDO" ) {
    ?>
    <p class="erro">Essa matr&iacute;cula n&atilde;o pode ser reaberta pois j&aacute; se encontra como CURSANDO. Opera&ccedil;&atilde;o Cancelada.</p>
    <?php
    } else {
    ?>
    <?php
        if($ma->verificaMatriculaAlunoExcedeTempo()) {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATENÇÃO: essa matrícula excedeu o tempo máximo para integralização. A justificativa para reativação deve conter a página e data da Ata do Conselho Acadêmico que autorizou a reabertura.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <?php
        }
    ?>
    <form method="post" name="reativaMatricula"
          action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php"
          onsubmit="return validar();" >
        <input type="hidden" name="acao" value="reativarMatricula" />
        <input type="hidden" name="matriculaAluno" value="<?php echo $matriculaAluno; ?>" />
        <p>
            Justificativa Reabertura de Matr&iacute;cula<br/>
            <textarea name="texto" rows="4" cols="40" tabindex="1" ></textarea>
        </p>
        <input type="submit" value="Reativar Matr&iacute;cula" />
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
