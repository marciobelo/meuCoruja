<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
?>
<script type="text/javascript">
    function validar() {
        if(document.renovaMatricula.texto.value=="") {
            window.alert("Campo Justificativa é obrigatório.");
            document.renovaMatricula.texto.focus();
            return false;
        }
        return true;
    }
    
    function usarJustificativaPadrao( justificativaValue ) {
        var texto = document.getElementById("texto");
        
        if( justificativaValue == "-" ) {
            texto.value = "Matrícula renovada manualmente.";
        } else if( justificativaValue == "tcc" ) {
            texto.value = "Matrícula renovada manualmente.\n" +
                    "Aluno está em fase de elaboração de TCC e solicitou " +
                    "formalmente a renovação de sua matrícula.\n" +
                    "Título TCC: \n" +
                    "Orientador: ";
        } else if( justificativaValue == "estagio" ) {
            texto.value = "Matrícula renovada manualmente.\n" +
                    "Aluno está em fase de cumprimento de horas de estágio " +
                    "necessárias para a integralização do curso.";            
        } else if( justificativaValue == "extensao" ) {
            texto.value = "Matrícula renovada manualmente.\n" +
                    "Aluno está em fase de cumprimento de horas de extensão " +
                    "necessárias para a integralização do curso.";            
        }
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
    <legend>Renovar Matr&iacute;cula</legend>
    <?php require_once "$BASE_DIR/interno/manter_situacao_matricula/verHistoricoMatricula.php"; ?>
    <!-- alertas de impedimento -->
    <?php
    if( ($ma->getSituacaoMatricula() != MatriculaAluno::CURSANDO) ) {
    ?>
    <p class="erro">Essa matr&iacute;cula n&atilde;o pode ser renovada pois não está na situa&ccedil;&atilde;o CURSANDO. Opera&ccedil;&atilde;o Cancelada.</p>
    <?php
    } else {
    ?>
    <?php
        if($ma->verificaMatriculaAlunoExcedeTempo()) {
    ?>
    <p class="erro"><?php echo htmlspecialchars("ATENÇÃO: essa matrícula excedeu o tempo máximo para integralização. A justificativa para renovação  deve conter a página e data da Ata do Conselho Acadêmico que autorizou a renovação.", ENT_QUOTES, "iso-8859-1"); ?></p>
    <?php
        }
    ?>
    <form method="post" name="renovaMatricula"
          action="/coruja/interno/manter_situacao_matricula/manterSituacaoMatricula_controle.php"
          onsubmit="return validar();" >
        <input type="hidden" name="acao" value="renovarMatricula" />
        <input type="hidden" name="matriculaAluno" value="<?php echo $matriculaAluno; ?>" />
        <span style="font-size: large; font-weight: bold">Justificativa Renovação de Matr&iacute;cula</span>
        <br/>
        <span>Gabarito de justificativas:</span>
        <select id="justificativasPadrao" onclick="usarJustificativaPadrao(this.value)">
            <option value="-">(selecione um gabarito padrão)</option>
            <option value="tcc">TCC</option>
            <option value="estagio">Est&aacute;gio</option>
            <option value="extensao">Extens&atilde;o</option>
        </select>
        <br/>
        <textarea id="texto" name="texto" rows="4" cols="40" tabindex="1" >Matrícula renovada manualmente.</textarea>
        <br/>
        <input type="submit" value="Renovar Matr&iacute;cula" />
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