<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    function gerarPauta(idTurma) {
        window.open("/coruja/nort/controle/emitirDiarioDeClasse_controle.php?acao=gerarDiarioProfessor&idTurma=" + idTurma, "_blank" );
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
            <?php echo $msgErro; ?>
        </li>
    <?php
        }
    ?>
    </ul>
    <?php
    }
    ?>
</div>

<div id="minhasTurmas">
    <fieldset>
        <legend>Minhas Turmas</legend>
        <?php 
        if( count($turmas) == 0 ) {
        ?>
        <span>Nenhuma turma dispon&iacute;vel.</span><img src="/coruja/imagens/comment_icon.png" title="Somente turmas confirmadas são exibidas nessa lista."/>
        <?php 
        } else {
            foreach ($turmas as $turma) { ?>
        <ul>
            <li>
                <?php if( !$turma->isPautaLiberadaPeloProfessor() ) { ?>
                <a href="/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>">
                <?php } ?>
                <?php echo $turma->getSiglaPeriodoLetivo(); ?>
                &nbsp;/&nbsp;
                <?php echo $turma->getCurso()->getSiglaCurso(); ?>
                &nbsp;/&nbsp;
                <?php echo $turma->getSiglaDisciplina(); ?>
                &nbsp;/&nbsp;
                <?php echo $turma->getGradeHorario(); ?>
                &nbsp;/&nbsp;
                <?php echo $turma->getTurno(); ?>
                <?php if( !$turma->isPautaLiberadaPeloProfessor() ) { ?>
                </a>
                <?php } else { ?>
                &nbsp;<img src="/coruja/imagens/comment_icon.png" title="Pauta liberada para a secretaria. Se deseja retificar, solicite &agrave; secretaria a devolu&ccedil;&atilde;o."/>
                <?php } ?>
                <input id="gerarPauta" type="button" value="Gerar Pauta" title="Gerar em PDF a pauta já liberada" onclick="gerarPauta(<?php echo $turma->getIdTurma(); ?>);" />
        </ul>

        <?php 
            }
        }
        ?>

    </fieldset>
</div>
<?php

require_once "$BASE_DIR/includes/rodape.php";
?>