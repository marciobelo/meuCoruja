<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
?>
<script type="text/javascript">
    function validar() {
        if(document.concluirMatricula.texto.value=="") {
            window.alert("Campo Justificativa � obrigat�rio.");
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
        // verifica se cumpriu todos os componentes curriculares obrigat�rios
        if(!$cumpriuCC) {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATEN��O: essa matr�cula n�o cumpriu uma ou mais disciplinas. Opera��o Cancelada.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <?php
        } else {
    ?>
    <?php
            // verifica se est� ok com a entrega dos docs. obrigat�rios
            if($pendenciaDocs) {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATEN��O: consta que matr�cula tem documentos n�o entregues � secretaria. Opera��o Cancelada.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <?php
            } else {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATEN��O: certifique-se que a matr�cula cumpriu todos os requisitos para ter sua matr�cula conclu�da.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <ul>
        <li>Entregou todos os documentos obrigat�rios</li>
        <li>N�o deve exemplar de livro � biblioteca</li>
        <li>Cumpriu todas as horas de extens�o</li>
        <li>Cumpriu todas as horas de est�gio obrigat�rio</li>
        <li>Concluiu TCC (Trabalho de Conclus�o de Curso)</li>
        <li>Est� quite com o ENADE</li>
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
        <p>Data Conclus�o: &nbsp;
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

