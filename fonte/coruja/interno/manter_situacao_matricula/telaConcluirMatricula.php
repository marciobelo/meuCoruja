<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script type="text/javascript">
    function validar() {
        if(document.concluirMatricula.texto.value=="") {
            window.alert("Campo Justificativa é obrigatório.");
            document.concluirMatricula.texto.focus();
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
    <legend>Concluir Matr&iacute;cula</legend>
    <?php require_once "$BASE_DIR/interno/manter_situacao_matricula/verHistoricoMatricula.php"; ?>
    <!-- alertas de impedimento -->
    <?php
    if( $ma->getSituacaoMatricula() != "CURSANDO" ) {
    ?>
    <p class="erro">Essa matr&iacute;cula n&atilde;o pode ser conclu&iacute;da pois se encontra em situa&ccedil;&atilde;o que n&atilde;o permite essa opera&ccedil;&atilde;o. Opera&ccedil;&atilde;o Cancelada.</p>
    <?php
    } else {
    ?>
    <?php
        // verifica se cumpriu todos os componentes curriculares obrigatórios
        if(!$cumpriuCC) {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATENÇÃO: essa matrícula não cumpriu uma ou mais disciplinas. Operação Cancelada.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <?php
        } else {
    ?>
    <?php
            // verifica se está ok com a entrega dos docs. obrigatórios
            if($pendenciaDocs) {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATENÇÃO: consta que matrícula tem documentos não entregues à secretaria. Operação Cancelada.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <?php
            } else {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATENÇÃO: certifique-se que a matrícula cumpriu todos os requisitos para ter sua matrícula concluída.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <ul>
        <li>Entregou todos os documentos obrigatórios</li>
        <li>Não deve exemplar de livro à biblioteca</li>
        <li>Cumpriu todas as horas de extensão</li>
        <li>Cumpriu todas as horas de estágio obrigatório</li>
        <li>Concluiu TCC (Trabalho de Conclusão de Curso)</li>
        <li>Está quite com o ENADE</li>
    </ul>
    <form method="post" name="concluirMatricula"
          action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php"
          onsubmit="return validar();" >
        <input type="hidden" name="acao" value="concluirMatricula" />
        <input type="hidden" name="matriculaAluno" value="<?php echo $matriculaAluno; ?>" />
        <p>
            Texto de Conclus&atilde;o da Matr&iacute;cula<br/>
            <textarea name="texto" rows="4" cols="40" tabindex="1" ><?php if(isset($texto)) echo $texto;?></textarea>
        </p>
        <p>Data Conclusão: &nbsp;
            <input name="dataConclusaoDia" type="text" size="2" value="<?php if(isset($dataConclusaoDia)) echo $dataConclusaoDia; else echo date("d");?>" />
            /
            <input name="dataConclusaoMes" type="text" size="2" value="<?php if(isset($dataConclusaoMes)) echo $dataConclusaoMes; else echo date("m");?>" />
            /
            <input name="dataConclusaoAno" type="text" size="4" value="<?php if(isset($dataConclusaoAno)) echo $dataConclusaoAno; else echo date("Y");?>" />
        </p>

        <input type="submit" value="Concluir Matr&iacute;cula" />
    </form>
    <?php
            }
        }
    }
    ?>
    <form method="post" action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php">
        <input type="hidden" name="acao" value="exibirSituacoesMatriculasCurso" />
        <input type="hidden" name="siglaCurso" value="<?php echo $siglaCurso; ?>" />
        <input type="submit" value="Voltar" />
    </form>
</fieldset>
<br/>
<?php
require_once "$BASE_DIR/includes/rodape.php";
?>

