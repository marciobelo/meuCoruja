<?php
    header('Content-Type: text/html; charset=ISO-8859-1');
    if( !isset( $turma ) ) 
        trigger_error("Variável de entrada para a visão não disponível.",E_USER_ERROR);  
?>
<html>
    <head>
        <title>Ata de Avalia&ccedil;&atilde;o</title>
        <style>
            body {
                font-size: small;
            }
            tr {
                height: 30px;
            }
            td {
                font-size: x-small;
            }
            h1 {
                text-align: center;
            }
            span.cabec_valor {
                font-weight: bold;
                font-size: smaller;
            }
            span.cabec_sigla_disc {
                font-weight: bold;
                font-size: large;
            }
            span.cabec_rotulo {
                font-size: small;
            }
            span.cabec_rotulo_inicio {
                display: inline-block;
                width: 80px;
                font-size: smaller;
            }
            span.campo {
                border: solid black;
            }
            span.campo_a_preencher {
                border: solid black;
                display: inline-block;
                width: 400px;                
            }
            span.campo_a_preencher_2_dig {
                border: solid black;
                display: inline-block;
                width: 40px;                
            }
            span.campo_a_preencher_4_dig {
                border: solid black;
                display: inline-block;
                width: 80px;                
            }
            table {
                width: 100%;
                border-collapse:collapse;
            }
            table th, td {
                border: 1px solid black ;
            }
            th#matricula {
                width: 10%;
            }
            th#nome {
                width: 60%;
            }
            th#assinatura {
                width: 12%;
            }
            th#assinaturaRecebido {
                width: 12%;
            }
            th#nota {
                width: 6%;
            }
        </style>
    </head>
    <body onload="window.print()">
        <h1>Ata de Avalia&ccedil;&atilde;o</h1>
        <p>
            <span class="cabec_rotulo_inicio">Institui&ccedil;&atilde;o:</span>&nbsp;
            <span class="cabec_valor"><?php echo Config::INSTITUICAO_NOME_COMPLETO; ?></span>
        <br/>
            <span class="cabec_rotulo_inicio">Curso:</span>&nbsp;
            <span class="cabec_valor"><?php echo $turma->getCurso()->getSiglaCurso(); ?>
        &nbsp;(<?php echo $turma->getCurso()->getNomeCurso(); ?>)</span>
            &nbsp;&boxh;&nbsp;
        <span class="cabec_rotulo">Per&iacute;odo Letivo:</span>&nbsp;
            <span class="cabec_valor"><?php echo $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(); ?></span>
        <br/>
            <span class="cabec_rotulo_inicio">Disciplina:</span>&nbsp;
            <span class="cabec_sigla_disc"><?php echo $turma->getComponenteCurricular()->getSiglaDisciplina(); ?>
                </span><span class="cabec_valor">&nbsp;(<?php echo $turma->getComponenteCurricular()->getNomeDisciplina(); ?>)</span>
            &nbsp;&boxh;&nbsp;
        <span class="cabec_rotulo">Turno:</span>&nbsp;
            <span class="cabec_valor"><?php echo $turma->getTurno(); ?></span>
            &nbsp;&boxh;&nbsp;
        <span class="cabec_rotulo">Grade:</span>&nbsp;
            <span class="cabec_valor"><?php echo $turma->getGradeHorario(); ?></span>
        <br/>
        <span class="cabec_rotulo_inicio">Professor:</span>&nbsp;
            <span class="cabec_valor"><?php echo $turma->getProfessor()->getNome(); ?></span>
        <br/>
            <span class="cabec_rotulo_inicio">Avaliação:</span>&nbsp;
            <span class="campo_a_preencher">&nbsp;</span>
        <br/>
            <span class="cabec_rotulo_inicio">Data:</span>&nbsp;
            <span class="campo_a_preencher_2_dig">&nbsp;</span>&nbsp;/&nbsp;
            <span class="campo_a_preencher_2_dig">&nbsp;</span>&nbsp;/&nbsp;
            <span class="campo_a_preencher_4_dig">&nbsp;</span>
        </p>
            
        <table>
            <thead>
                <tr>
                    <th id="num_ordem">N&ordm;</th>
                    <th id="matricula">Matr&iacute;cula</th>
                    <th id="nome">Nome do Aluno</th>
                    <th id="assinatura">Ass.Presença</th>
                    <th id="assinaturaRecebido">Ass.Recebido</th>
                    <th id="nota">Nota</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($turma->getInscricoesDePauta() as $inscricao) { ?>
                <tr>
                    <td><?php echo ++$num_ordem; ?></td>
                    <td><?php echo $inscricao->obterMatriculaAluno()->getMatriculaAluno(); ?></td>
                    <td><?php echo $inscricao->obterMatriculaAluno()->getAluno()->getNome(); ?></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <hr/>
        <p>Coruja&nbsp;&boxh;&nbsp;Emitido em <?php $d = new DateTime(); echo $d->format("d/m/Y"); ?></p>
    </body>
</html>
    