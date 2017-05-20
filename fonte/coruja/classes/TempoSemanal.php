<?php
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/DiaLetivoTurma.php";
require_once "$BASE_DIR/classes/Curso.php";

class TempoSemanal {

    const DOM = 0;
    
    private $idTempoSemanal;
    private $siglaCurso;
    private $horaInicio;
    private $horaFim;
    private $diaSemana;

    // ATENÇÃO: 
    // 1. os turnos NUNCA devem estar sobrepostos... NUNCA!
    // 2. os horários na tabela devem obedecer os intervalos dos respectivos turnos
    public static $INICIO_TURNO_MANHA = "07:10:00";
    public static $FIM_TURNO_MANHA = "12:20:00";
    public static $INICIO_TURNO_TARDE = "13:00:00";
    public static $FIM_TURNO_TARDE = "18:00:00";
    public static $INICIO_TURNO_NOITE = "18:00:00";
    public static $FIM_TURNO_NOITE = "22:10:00";

    function __construct($idTempoSemanal, $siglaCurso, $horaInicio, $horaFim, $diaSemana) {
        $this->idTempoSemanal = $idTempoSemanal;
        $this->siglaCurso = $siglaCurso;
        $this->horaInicio = $horaInicio;
        $this->horaFim = $horaFim;
        $this->diaSemana = $diaSemana;
    }

    public function getIdTempoSemanal( ) {
        return $this->idTempoSemanal;
    }

    public function getSiglaCurso( ) {
        // retorna o valor de: siglaCurso
        return $this->siglaCurso;
    }

    public function getHoraInicio( ) {
        // retorna o valor de: horaInicio
        return $this->horaInicio;
    }

    public function getHoraFim( ) {
        // retorna o valor de: horaFim
        return $this->horaFim;
    }

    public function getDiaSemana( ) {
        // retorna o valor de: diaSemana
        return $this->diaSemana;
    }

    public function setIdTempoSemanal( $idTempoSemanal ) {
        // seta o valor de: idTempoSemanal
        $this->idTempoSemanal = $idTempoSemanal;
    }

    public function setSiglaCurso( $siglaCurso ) {
        // seta o valor de: siglaCurso
        $this->siglaCurso = $siglaCurso;
    }

    public function setHoraInicio( $horaInicio ) {
        // seta o valor de: horaInicio
        $this->horaInicio = $horaInicio;
    }

    public function setHoraFim( $horaFim ) {
        // seta o valor de: horaFim
        $this->horaFim = $horaFim;
    }

    public function setDiaSemana( $diaSemana ) {
        // seta o valor de: diaSemana
        $this->diaSemana = $diaSemana;
    }
    
    /* Retorna um TempoSemanal de acordo com seu Id
     * 
     * Casos de Uso:
     *      UC01.03.01 - Editar Turma (Classe Aloca / getTempoSemanal())
     *      UC01.03.02 - Criar Turma (Classe Aloca / getTempoSemanal())
     * 
     * @author: Marcelo Atie
     * @param idTempoSemanal
     * @result new TempoSemanal
     */
    public static function getTempoSemanalById($idTempoSemanal) {

        $con = BD::conectar();

        $query = sprintf(
                "SELECT * " .
                "FROM TempoSemanal " .
                "WHERE idTempoSemanal = %s", mysql_real_escape_string($idTempoSemanal));
        $result = mysql_query($query, $con);
        $__obj = null;
        while ($linha = mysql_fetch_array($result)) {
            $__obj = new TempoSemanal($linha['idTempoSemanal'], $linha['siglaCurso'], $linha['horaInicio'], $linha['horaFim'], $linha['diaSemana']);
        }
        return $__obj;
    }

    /***
     * Retorna a lista de objetos baseado em parametros: temposemanal
     *
     * @param conditionalStatement = ''
     * @result coleção de objetos: TempoSemanal
     **/
    public static function lista_temposemanal( $conditionalStatement = '' ) {

        $con = BD::conectar();
        // checa se foram passados parametros
        if(!empty($conditionalStatement)) {
            $sqlStatement = "SELECT * FROM TempoSemanal WHERE $conditionalStatement";
        } else {
            $sqlStatement = "SELECT * FROM TempoSemanal";
        }

        // recupera os valores com base no resultado
        $result = mysql_query($sqlStatement, $con);

        $temposSemanal = array();
        while($rs = mysql_fetch_array($result) ) 
        {
            $ts = new TempoSemanal($rs['idTempoSemanal'], 
                    $rs['siglaCurso'], 
                    $rs['horaInicio'], 
                    $rs['horaFim'], 
                    $rs['diaSemana']);
            // adiciona objetos à coleção
            array_push($temposSemanal, $ts);
        }
        return $temposSemanal;
    }

    /*
    *Casos de Uso: UC02.06.00, UC02.04.00
    */
    public static function obterTurmasByTempoSemanal($matriculaAluno,$situacaoInscricao,$idTempoSemanal,$idPeriodoLetivo) {
        $collection=array();
        $con = BD::conectar();
        $query=sprintf("SELECT t.idTurma ".
                "from Turma t ".
                "INNER JOIN Aloca a ".
                "ON t.idTurma  = a.idTurma ".
                "INNER JOIN Inscricao i ".
                "ON t.idTurma = i.idTurma ".
                "WHERE i.situacaoInscricao in ('%s') and ".
                "a.idTempoSemanal= %d and ".
                "i.matriculaAluno = '%s' and ".
                "t.idPeriodoLetivo = %d ",$situacaoInscricao,$idTempoSemanal,$matriculaAluno,$idPeriodoLetivo);
       
        $result = mysql_query($query,$con);
        while( $resPorTempo = mysql_fetch_array($result) ) {
            $turma=Turma::getTurmaById($resPorTempo['idTurma']);
            $collection[]=$turma;
        }
        return $collection;
    }

    /*FUNCAO QUE OBTEM OS TEMPOS SEMANAIS ORDENADAS PELO HORARIO E PELO DIA DA SEMANA
     *FUNCAO NECESSARIA PARA A NOVA GRADE HORARIA
    */
    public static function obterTempoSemanalOrdenado( Curso $curso) {
        $collection=array();
        $con = BD::conectar();
        $query = sprintf("select * from TempoSemanal where siglaCurso = '%s' "
                . "order by horaInicio,diaSemana", $curso->getSiglaCurso() );
        $result = mysql_query($query,$con);

        while( $resTempo = mysql_fetch_array($result) ) {
            $tempo = new temposemanal();
            $tempo->setIdTempoSemanal($resTempo['idTempoSemanal']);
            $tempo->setSiglaCurso($resTempo['siglaCurso']);
            $tempo->setHoraInicio($resTempo['horaInicio']);
            $tempo->setHoraFim($resTempo['horaFim']);
            $tempo->setDiaSemana($resTempo['diaSemana']);
            $collection[]=$tempo;
        }
        return $collection;
    }
    
    /* Indica se o tempo de aula é o primeiro, segundo, terceiro ...
     * exemplo:
     *   1 para o tempo que se inicia 7:10,
     *   2 para o tempo que se inicia 8:00
     *
     * @result int
     */
    public function obterTempoOrdinalDoTurno() {

        if( $this->horaFim <= TempoSemanal::$FIM_TURNO_MANHA ) {
            $tempoInicial = TempoSemanal::$INICIO_TURNO_MANHA;
            $tempoFinal = TempoSemanal::$FIM_TURNO_MANHA;
        } else if( $this->horaFim <= TempoSemanal::$FIM_TURNO_TARDE ) {
            $tempoInicial = TempoSemanal::$INICIO_TURNO_TARDE;
            $tempoFinal = TempoSemanal::$FIM_TURNO_TARDE;
        } else if( $this->horaFim <= TempoSemanal::$FIM_TURNO_NOITE ) {
            $tempoInicial = TempoSemanal::$INICIO_TURNO_NOITE;
            $tempoFinal = TempoSemanal::$FIM_TURNO_NOITE;
        } else {
            $tempoInicial = "00:00:00";
            $tempoFinal = "23:59:59";
        }

        $con = BD::conectar();
        $query = sprintf("select * from TempoSemanal
            where siglaCurso='%s'
            and diaSemana='%s'
            and horaInicio >= '%s' 
            and horaFim <= '%s'
            order by horaInicio",
                $this->siglaCurso,
                $this->diaSemana,
                $tempoInicial,
                $tempoFinal);
        $result = mysql_query($query, $con);
        $ordem = 1;
        while( $linha = mysql_fetch_array($result) ) {
            if( $this->idTempoSemanal == $linha["idTempoSemanal"] ) {
                return $ordem;
            }
            $ordem++;
        }
        trigger_error("Nao foi possivel determinar o turno do tempo semanal idTempoSemanal=" .
                $this->getIdTempoSemanal(), E_USER_ERROR);
    }

    /**
     * Cria lista de tempos semanais de um dia letivo da turma de acordo
     * com as alocações cadastradas para a turma, ordenados pela
     * hora de início. Diferente do obter, retorna os tempos semanais de
     * acordo com a grade de alocação determina para a turma.
     * @param DiaLetivoTurma $diaLetivoTurma
     * @return type
     */
    public static function criarListaTempoSemanalPorDiaLetivoTurma(DiaLetivoTurma $diaLetivoTurma) {
        $siglaDiaSemana = Util::obterSiglaDiaSemana( $diaLetivoTurma->getData() );
        $con = BD::conectar();
        $query = sprintf("select TS.idTempoSemanal from Turma T 
            inner join Aloca A on T.idTurma = A.idTurma
            inner join TempoSemanal TS on TS.idTempoSemanal = A.idTempoSemanal 
            where T.idTurma = %d and 
            TS.diaSemana = '%s'
            order by TS.horaInicio",
                $diaLetivoTurma->getTurma()->getIdTurma(),
                $siglaDiaSemana);
        $result = mysql_query($query, $con);
        $lista = array();
        while( $reg = mysql_fetch_array($result)) {
            $lista[] = TempoSemanal::getTempoSemanalById( $reg["idTempoSemanal"] );
        }
        return $lista;
    }

    /**
     * Obtém a lista de tempos semanais cadastrados para o dia letivo da turma,
     * ordenados pela hora de início. Diferente do criar, este método apenas
     * retorna os tempos semanais que estão cadastrados para um dia letivo que
     * podem ter sido alterados.
     * @param DiaLetivoTurma $diaLetivoTurma
     * @return TempoSemanal[]
     */
    public static function obterListaTempoSemanalPorDiaLetivoTurma(DiaLetivoTurma $diaLetivoTurma) {
        $con = BD::conectar();
        $query = sprintf("select TDL.idTempoSemanal from DiaLetivoTurma DLT 
            inner join TempoDiaLetivo TDL on DLT.idTurma = TDL.idTurma and
            DLT.data = TDL.data
            inner join TempoSemanal TS on TS.idTempoSemanal = TDL.idTempoSemanal
            where DLT.idTurma = %d and
            DLT.data = '%s'
            order by TS.horaInicio",
                $diaLetivoTurma->getTurma()->getIdTurma(),
                $diaLetivoTurma->getData()->format("Y-m-d"));
        $result = mysql_query($query, $con);
        $lista = array();
        while( $reg = mysql_fetch_array($result)) {
            $lista[] = TempoSemanal::getTempoSemanalById( $reg["idTempoSemanal"] );
        }
        return $lista;
    }

    /**
     * Retorna os tempos de um determinado dia da semana de um curso
     * @param string $siglaCurso
     * @param string $strDiaSemana
     */
    public static function obterListaTempoSemanalPorDiaSemana($siglaCurso, $strDiaSemana) {
        $con = BD::conectar();
        $query = sprintf("select * from TempoSemanal
            where siglaCurso='%s'
            and diaSemana='%s'
            order by horaInicio",
                $siglaCurso,
                $strDiaSemana);
        $result = mysql_query($query);
        $lista = array();
        while( $reg = mysql_fetch_array($result) ) {
            $lista[] = TempoSemanal::getTempoSemanalById( $reg["idTempoSemanal"] );
        }
        return $lista;
    }
    
} 
?>