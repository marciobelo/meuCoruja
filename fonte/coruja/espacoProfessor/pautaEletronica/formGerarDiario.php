<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<script type="text/javascript">
    function gerarDiario() {
        window.open("/coruja/nort/controle/emitirDiarioDeClasse_controle.php?acao=gerarDiarioProfessor&idTurma=<?php echo $turma->getIdTurma() ?>", "_blank" );
        window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>&data=<?php echo $dataDiaLetivoTurma->format("Y-m-d"); ?>", "_top" );
    }
    
    function voltarPauta() {
        window.open("/coruja/espacoProfessor/pautaEletronica/pautaEletronica_controle.php?idTurma=<?php echo $turma->getIdTurma(); ?>&data=<?php echo $dataDiaLetivoTurma->format("Y-m-d"); ?>", "_top" );
    }
</script>

<?php include "$BASE_DIR/espacoProfessor/pautaEletronica/trechoCabecTurma.php"; ?>


<p>Deseja gerar o Diário Eletrônico?</p>

<input type="button" value="Sim" onclick="gerarDiario();"/>
&nbsp;
<input type="button" value="Agora não" onclick="voltarPauta();" />

<?php
require_once "$BASE_DIR/includes/rodape.php";
?>