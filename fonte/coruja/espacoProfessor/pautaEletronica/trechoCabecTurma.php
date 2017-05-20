<div id="cabecalhoTurma">
    <span class="rotulo">Curso:</span>
    <span class="campo" style="width: 600px"><?php echo $turma->getCurso()->getSiglaCurso(); ?>
        &nbsp;(<?php echo $turma->getCurso()->getNomeCurso(); ?>)</span>
    <span class="rotulo">Per&iacute;odo Letivo:</span>
    <span class="campo" style="width: 100px"><?php echo $turma->getPeriodoLetivo()->getSiglaPeriodoLetivo(); ?></span>
    <br/>
    <span class="rotulo">Disciplina:</span>
    <span class="campo" style="width: 600px"><?php echo $turma->getComponenteCurricular()->getSiglaDisciplina(); ?>
    &nbsp;(<?php echo $turma->getComponenteCurricular()->getNomeDisciplina(); ?>)</span>
    <span class="rotulo">Turno:</span>
    <span class="campo" style="width: 100px"><?php echo $turma->getTurno(); ?></span>
    <span class="rotulo">Grade:</span>
    <span class="campo" style="width: 100px"><?php echo $turma->getGradeHorario(); ?></span>
    <br/>
    <span class="rotulo">Tempos de aula:</span>
    <span class="campo" style="width: 600px"><?php echo $turma->obterAlocacoesComoString(); ?></span>
    <span class="rotulo">C.H. Total:</span>
    <span class="campo" style="width: 100px"><?php echo $turma->getComponenteCurricular()->getCargaHoraria(); ?></span>
    <span class="rotulo">Limite de Faltas:</span>
    <span class="campo" style="width: 100px"><?php echo $turma->getComponenteCurricular()->getLimiteFaltas(); ?></span>
</div>