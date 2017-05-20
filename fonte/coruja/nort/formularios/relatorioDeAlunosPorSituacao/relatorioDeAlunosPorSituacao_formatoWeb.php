<?php
require_once "$BASE_DIR/includes/topo.php";
require_once "$BASE_DIR/includes/menu_horizontal.php";
?>
<form id="cadastro" method="POST" action="nenhum">

    <input type='hidden' name='nomeCurso' value='<?php echo $umCurso->getNomeCurso(); ?>'>
    <input type='hidden' name='siglaCurso' value='<?php echo $umCurso->getSiglaCurso(); ?>'>
    <input type='hidden' name='periodoInicial' value='<?php echo $periodoInicial?>'>
    <input type='hidden' name='periodoFinal' value='<?php echo $periodoFinal?>'>
    <?php foreach ($situacoesEscolhidas as $sit) {?>
        <input type='hidden' name='situacoesEscolhidas[]' value='<?php echo $sit; ?>'>
    <?php } ?>

    <fieldset>
    <legend>Relatório de Alunos Por Situação</legend>
    <center>
    <table>
        <tr>
            <td>Matrícula</td>
            <td><center>Nome</center></td>
            <td>Situação da Matrícula</td>
            <!-- <td>Sigla Curso</td> -->
            <td>Período da Matrícula</td>
        </tr>
            <?php
            $colorir = True;
            for($i = 0; $i < count($listaDeMatriculaAluno); $i++) 
            {
                $matriculaAluno = $listaDeMatriculaAluno[$i];
                $nome = $listaDenome[$i];
                $situacaoMatricula = $listaDeSituacaoMatricula[$i];
                $siglaPeriodoLetivo = $listaDedescPeriodoLetivo[$i];

                if ($colorir) {
                        ?><tr bgcolor="#C7F7FF"><?php
                    } else {
                        ?><tr><?php
                    }
                    $colorir = !$colorir;
                ?>
        
            <td><?php echo $matriculaAluno; ?></td>
            <td><?php echo $nome; ?></td>
            <td align="center"><?php echo $situacaoMatricula; ?></td>
            <td align="center"><?php echo $siglaPeriodoLetivo; ?></td>
            </tr>
            <?php
            }
            ?>
    </table>

    <table width="700">
        <tr>
            <td>
                
                <INPUT  id="botaoExibirDocumento" TYPE ="button" value="Gerar relatório para impressão"
                onClick="open('/coruja/nort/controle/emitirRelatorioDeAlunosPorSituacao_controle.php?acao=exibirPDF', 'new', 'width=800,height=600,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no')">
            </td>
            <td align="right">
                <input type="button" value="Retornar" onclick="window.location = '/coruja/nort/controle/emitirRelatorioDeAlunosPorSituacao_controle.php';">
            </td>
        </tr>
    </table>

    </center></fieldset>
</form>
