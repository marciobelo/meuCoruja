<?php
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/TempoSemanal.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Espaco.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/interno/emitir_grade_horario/GradeHorarioPDF.php";

global $EXIBIR_GRADE_HORARIO;
if( !$login->temPermissao($EXIBIR_GRADE_HORARIO) ) {
    require_once "$BASE_DIR/baseCoruja/formularios/sem_permissao.php";
    exit;
} else {
    
    $siglaCurso = $_GET["siglaCurso"];
    $idPeriodoLetivo = $_GET["idPeriodoLetivo"];
    
    if( isset( $siglaCurso ) && isset( $idPeriodoLetivo ) ) {

        $grade = gerarGrade($siglaCurso, $idPeriodoLetivo);

        $curso = Curso::obterCurso($siglaCurso);
        $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodoLetivo);
        
        $pdf = new GradeHorarioPDF( $curso, $periodoLetivo, $grade );
        $pdf->Output();
        exit;
        
    } else { // apresentar dialogo de parametros
        
        Header("Location: /coruja/interno/selecionar_curso_periodoletivo/selecionarCursoPeriodoLetivo_controle.php?titulo=" .
                urlencode("Emitir Grade Horario") . "&destino=" . 
                urlencode("/coruja/interno/emitir_grade_horario/GradeHorario_controle.php") );
        exit;
    }
}

/*
 * $grade[turno][periodo][grade][dia_semana][tempo]
 * 
 */
function gerarGrade($siglaCurso, $idPeriodoLetivo) {

    $con = BD::conectar();
    
    $query = sprintf("select T.turno, CC.periodo, T.gradeHorario, 
            A.idTurma, A.idTempoSemanal, A.idEspaco 
            from Turma T inner join ComponenteCurricular CC 
                on T.siglaCurso = CC.siglaCurso and
                    T.idMatriz = CC.idMatriz and
                    T.siglaDisciplina = CC.siglaDisciplina
                inner join Aloca A 
                    on T.idTurma = A.idTurma
            where T.siglaCurso = '%s' and T.idPeriodoLetivo = %d 
            and T.tipoSituacaoTurma <> 'CANCELADA'
            order by T.turno, CC.periodo, T.gradeHorario",
            mysql_real_escape_string( $siglaCurso ),
            $idPeriodoLetivo);
    
    $result = mysql_query( $query, $con );
    
    $grade = array();
    $maiorTempoOrdinal = 0;
    while( $registro = mysql_fetch_array($result) ) {
        
        $turno = $registro["turno"];
        $periodo = $registro["periodo"];
        $gradeHorario = $registro["gradeHorario"];
        
        $idTempoSemanal = $registro["idTempoSemanal"];
        $tempoSemanal = TempoSemanal::getTempoSemanalById( $idTempoSemanal );
        $ordinalTempo = $tempoSemanal->obterTempoOrdinalDoTurno();
        if( $maiorTempoOrdinal < $ordinalTempo ) {
            $maiorTempoOrdinal = $ordinalTempo;
        }
        $idTurma = $registro["idTurma"];
        $turma = Turma::getTurmaById($idTurma);
        $idEspaco = $registro["idEspaco"];
        $espaco = Espaco::obterEspacoPorId($idEspaco);
        $diaSemana = $tempoSemanal->getDiaSemana();

        $grade[$turno][$periodo][$gradeHorario][$diaSemana][$ordinalTempo] =
                new TempoGrade( $turma, $espaco, 
                        $tempoSemanal->getHoraInicio(), 
                        $tempoSemanal->getHoraFim() );
    }
    $grade["MAIOR_TEMPO_ORDINAL"] = $maiorTempoOrdinal;
    return $grade;
}
?>