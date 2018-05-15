<?php
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/Professor.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/Aloca.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/DiaLetivoTurma.php";
require_once "$BASE_DIR/classes/CriterioAvaliacao.php";
require_once "$BASE_DIR/classes/ItemCriterioAvaliacao.php";
require_once "$BASE_DIR/classes/to/QuadroNavegacaoTurmaTO.php";
require_once "$BASE_DIR/classes/Util.php";

class Turma {

    // Estados da Turma
    const PLANEJADA = "PLANEJADA";
    const LIBERADA = "LIBERADA";
    const CANCELADA = "CANCELADA";
    const CONFIRMADA = "CONFIRMADA";
    const FINALIZADA = "FINALIZADA";

    private $idTurma;
    private $siglaCurso;
    private $idMatriz;
    private $siglaDisciplina;
    private $gradeHorario;
    private $idPeriodoLetivo;
    private $matriculaProfessor;
    private $turno;
    private $tipoSituacaoTurma;
    private $qtdeTotal;
    private $dataLiberacaoPautaPeloProfessor;
    private $dataInicioVigencia;//campo da tabela matrizCurricular
    private $nomeDisciplina;
    private $criterioAvaliacao;

    public function reabrirDiaLetivoTurma(DateTime $dataDiaLetivoTurma, $con = null) {
        if( $con == null ) $con = BD::conectar ();
        $cmd = sprintf("update DiaLetivoTurma set dataLiberacao = NULL
            where idTurma = %d and
            data = '%s'",
                $this->idTurma,
                $dataDiaLetivoTurma->format("Y-m-d"));
        $result = mysql_query($cmd, $con);
        if(!$result) {
            throw new Exception("Nao foi possivel reabrir apontamento de dia letivo da turma");
        }
    }

    /**
     * Retorna objetos de Inscricao que devem figurar na pauta do professor
     */
    public function getInscricoesDePauta() {
        $con = BD::conectar();
        $query = sprintf("select I.matriculaAluno from Inscricao I
                inner join MatriculaAluno MA on I.matriculaAluno = MA.matriculaAluno
                inner join Pessoa P on MA.idPessoa = P.idPessoa
            where idTurma = %d and
            (situacaoInscricao in ('CUR','AP','RM','RF') or
                (situacaoInscricao in ('REQ','EXC') and
                    parecerInscricao = '%s')
            ) order by P.nome",
                $this->getIdTurma(),
                Inscricao::RECLAMADO_PELO_PROFESSOR);
        $result = mysql_query($query, $con);
        $inscricoes = array();
        while( $reg = mysql_fetch_array($result) ) {
            $inscricoes[] = Inscricao::getInscricao($this->getIdTurma(),
                    $reg["matriculaAluno"] );
        }
        return $inscricoes;
    }

    /**
     * Retorna uma coleção de turmas confirmadas de um professor
     * @param Professor $professor
     * @return Turma[]
     */
    public static function obterTurmasConfirmadasPorProfessor(Professor $professor) {        
        $con = BD::conectar();
        $query=sprintf("select t.idTurma, t.siglaCurso, t.idMatriz, t.siglaDisciplina,
                t.gradeHorario, t.idPeriodoLetivo, t.matriculaProfessor, t.turno,
                t.tipoSituacaoTurma, t.qtdeTotal, t.dataLiberacaoPautaPeloProfessor, 
                t.idCriterioAvaliacao 
                from Turma t inner join ComponenteCurricular cc
                    on t.siglaCurso=cc.siglaCurso and
                    t.idMatriz=cc.idMatriz and
                    t.siglaDisciplina=cc.siglaDisciplina
                    inner join MatriculaProfessor MP
                        on t.matriculaProfessor = MP.matriculaProfessor
                    inner join Professor P on MP.idPessoa = P.idPessoa
                where t.tipoSituacaoTurma = 'CONFIRMADA' and
                P.idPessoa = %d 
                order by cc.periodo,t.turno,t.siglaDisciplina,t.gradeHorario",
                $professor->getIdPessoa() );
        $result=mysql_query($query,$con);
        $turmasProfessor = array();
        while( $resTurmas = mysql_fetch_array($result) ) {
            $componenteCurricular = new ComponenteCurricular($resTurmas['siglaCurso'], $resTurmas['idMatriz'], $resTurmas['siglaDisciplina']);
            $componenteCurricular->obterComponenteCurricular($resTurmas['siglaCurso'], $resTurmas['idMatriz'], $resTurmas['siglaDisciplina']);
            $turmasLiberadas = new Turma($resTurmas['idTurma']);
            $turmasLiberadas->setGradeHorario($resTurmas['gradeHorario']);
            $turmasLiberadas->setIdMatriz($componenteCurricular->getIdMatriz());
            $turmasLiberadas->setIdPeriodoLetivo($resTurmas['idPeriodoLetivo']);
            $turmasLiberadas->setIdTurma($resTurmas['idTurma']); //previne erros caso o construtor seja alterado
            $turmasLiberadas->setMatriculaProfessor($resTurmas['matriculaProfessor']);
            $turmasLiberadas->setQtdeTotal($resTurmas['qtdeTotal']);
            $turmasLiberadas->setDataLiberacaoPautaPeloProfessor($resTurmas['dataLiberacaoPautaPeloProfessor']);
            $turmasLiberadas->setSiglaCurso($componenteCurricular->getSiglaCurso());
            $turmasLiberadas->setSiglaDisciplina($componenteCurricular->getSiglaDisciplina());
            $turmasLiberadas->setTipoSituacaoTurma($resTurmas['tipoSituacaoTurma']);
            $turmasLiberadas->setTurno($resTurmas['turno']);
            $turmasLiberadas->setCriterioAvaliacao($resTurmas['idCriterioAvaliacao']);
            $turmasProfessor[] = $turmasLiberadas;
        }
        return $turmasProfessor;
    }

    /**
     * Reabre a turma mudando o status dela de FINALIZADA para
     * CONFIRMADA.
     */
    public function reabrirTurma($con = null) {
        if($con == null) $con = BD::conectar();
        $query=sprintf("update Turma set tipoSituacaoTurma='%s'
            where idTurma = %d and
            tipoSituacaoTurma = '%s'",
                Turma::CONFIRMADA,
                $this->idTurma,
                Turma::FINALIZADA);
        $result = mysql_query($query,$con);
        if(mysql_affected_rows() != 1) {
            throw new Exception("Erro ao reabrir turma.");
        }
    }

    /**
     *
     * @return string texto longo com o extrato completo desta turma
     */
    public function gerarExtratoTurmaParaProfessor() {
        $siglaDisciplina = $this->getSiglaDisciplina();
        $turno = $this->getTurno();
        $gradeHorario = $this->getGradeHorario();
        $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($this->idPeriodoLetivo);
        $siglaPeriodoLetivo = $periodoLetivo->getSiglaPeriodoLetivo();

        $texto = "EXTRATO DE TURMA $siglaDisciplina / $turno / $gradeHorario / $siglaPeriodoLetivo\n" .
        "Prezado Prof(a), esta turma foi finalizada no sistema Coruja e abaixo está o extrato conforme o lançamento. Caso haja alguma incorreção, por favor notifique com urgência a secretaria do curso.\n\n" .
        "NOME\n" .
        "\tMATRÍCULA\tFALTAS\tNOTA\tSITUAÇÃO\n";

        $con = BD::conectar();
        $query=sprintf("select P.nome,I.matriculaAluno,I.situacaoInscricao,I.mediaFinal,I.totalFaltas
            from Inscricao I inner join MatriculaAluno MA on I.matriculaAluno = MA.matriculaAluno
                inner join Pessoa P on P.idPessoa = MA.idPessoa
            where I.idTurma = %d
            and situacaoInscricao in ('AP','RM','RF')
            order by P.nome",$this->idTurma);
        $result=mysql_query($query,$con);
        while( $resInscricao = mysql_fetch_array($result) ) {
            $texto .= $resInscricao["nome"] . "\n" .
                "\t" . $resInscricao["matriculaAluno"] .
                "\t" . str_pad($resInscricao["totalFaltas"],6) .
                "\t" . str_pad($resInscricao["mediaFinal"],4) .
                "\t" . str_pad($resInscricao["situacaoInscricao"],8) . "\n";
        }

        return $texto;
    }
    
    function __construct($idTurma) {
        $this->idTurma = $idTurma;
    }

  /*Funcao que obtem turmas dada um determinado parametro
     * Casos de Uso: UC02.06.00, UC02.07.00
     * @param: $condicao - Condicao
     * @return <turmas>  Uma lista com as turmas liberadas.
     */
   public static function obterTurmas($condicao) {
       $collection = array();
       $componenteCurricular;
       $con = BD::conectar();
        $query=sprintf("select idTurma, siglaCurso, idMatriz, siglaDisciplina, ".
                "gradeHorario, idPeriodoLetivo, matriculaProfessor, turno, ".
                "tipoSituacaoTurma, qtdeTotal, dataLiberacaoPautaPeloProfessor, idCriterioAvaliacao ".
                "from Turma ".
                "where %s ",$condicao);
        $result=mysql_query($query,$con);
        
        while( $resTurmasLiberadas = mysql_fetch_array($result) ) {
            $componenteCurricular = new ComponenteCurricular($resTurmasLiberadas['siglaCurso'], $resTurmasLiberadas['idMatriz'], $resTurmasLiberadas['siglaDisciplina']);
            $componenteCurricular->obterComponenteCurricular($resTurmasLiberadas['siglaCurso'], $resTurmasLiberadas['idMatriz'], $resTurmasLiberadas['siglaDisciplina']);
            $turmasLiberadas = new Turma($resTurmasLiberadas['idTurma']);
            $turmasLiberadas->setGradeHorario($resTurmasLiberadas['gradeHorario']);
            $turmasLiberadas->setIdMatriz($componenteCurricular->getIdMatriz());
            $turmasLiberadas->setIdPeriodoLetivo($resTurmasLiberadas['idPeriodoLetivo']);
            $turmasLiberadas->setIdTurma($resTurmasLiberadas['idTurma']); //previne erros caso o construtor seja alterado
            $turmasLiberadas->setMatriculaProfessor($resTurmasLiberadas['matriculaProfessor']);
            $turmasLiberadas->setQtdeTotal($resTurmasLiberadas['qtdeTotal']);
            $turmasLiberadas->setDataLiberacaoPautaPeloProfessor($resTurmasLiberadas['dataLiberacaoPautaPeloProfessor']);
            $turmasLiberadas->setSiglaCurso($componenteCurricular->getSiglaCurso());
            $turmasLiberadas->setSiglaDisciplina($componenteCurricular->getSiglaDisciplina()); 
            $turmasLiberadas->setTipoSituacaoTurma($resTurmasLiberadas['tipoSituacaoTurma']);
            $turmasLiberadas->setTurno($resTurmasLiberadas['turno']);
            $turmasLiberadas->setCriterioAvaliacao($resTurmasLiberadas['idCriterioAvaliacao']);
            $collection[] = $turmasLiberadas;
        }
        return $collection;
    }

    public static function getTurmaById($idTurma) {
        $con = BD::conectar();
        $query=sprintf("select * ".
                "from Turma ".
                "where idTurma = %d ", mysql_real_escape_string($idTurma));
        $result=mysql_query($query,$con);
       
        $turma = null;
        while( $resTurma = mysql_fetch_array($result) ) {
            $turma = new Turma($resTurma['idTurma']);
            $turma->setGradeHorario($resTurma['gradeHorario']);
            $turma->setIdMatriz($resTurma['idMatriz']);
            $turma->setIdPeriodoLetivo($resTurma['idPeriodoLetivo']);
            $turma->setIdTurma($resTurma['idTurma']); //previne erros caso o construtor seja alterado
            $turma->setMatriculaProfessor($resTurma['matriculaProfessor']);
            $turma->setQtdeTotal($resTurma['qtdeTotal']);
            $turma->setDataLiberacaoPautaPeloProfessor($resTurma['dataLiberacaoPautaPeloProfessor']);
            $turma->setSiglaCurso($resTurma['siglaCurso']);
            $turma->setSiglaDisciplina($resTurma['siglaDisciplina']);
            $turma->setTipoSituacaoTurma($resTurma['tipoSituacaoTurma']);
            $turma->setTurno($resTurma['turno']);
            $turma->setCriterioAvaliacao( $resTurma['idCriterioAvaliacao'] );
        }
        return $turma;
    }

    public function getCurso() {
        return Curso::obterCurso( $this->getSiglaCurso() );
    }

    public function getPeriodoLetivo() {
        return PeriodoLetivo::obterPeriodoLetivo( $this->getIdPeriodoLetivo() );
    }

    public function getComponenteCurricular() {
        return ComponenteCurricular::obterComponenteCurricular($this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina());
    }

    public function getProfessor() {
        if($this->getMatriculaProfessor() == NULL ) {
            return NULL;
        } else {
            return Professor::obterProfessorPorMatricula($this->getMatriculaProfessor());
        }
    }

    /* Método que retirna as proximas situações possíveis para a turma baseando-se apenas no estado atual da turma
     * @param: void
     * @return <String>  lista com as próximas situações.
     */
    public function obterProximasSituacoesPossiveis(){
        switch (strtoupper($this->getTipoSituacaoTurma())) {
            case 'PLANEJADA':
                return array('LIBERADA','CANCELADA');
                break;
            case 'LIBERADA':
                return array('CONFIRMADA','CANCELADA');
                break;
            case 'CONFIRMADA':
                return array('FINALIZADA','CANCELADA');
                break;
            case 'FINALIZADA':
                return array();
                break;
            case 'CANCELADA':
                return array();
                break;
            default:
                return null;
        }
    }

    public function getIdTurma() {
        return $this->idTurma;
    }

    public function setIdTurma($idTurma) {
        $this->idTurma = $idTurma;
    }

    public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    public function setSiglaCurso($siglaCurso) {
        $this->siglaCurso = $siglaCurso;
    }

    public function getIdMatriz() {
        return $this->idMatriz;
    }

    public function setIdMatriz($idMatriz) {
        $this->idMatriz = $idMatriz;
    }

    public function getSiglaDisciplina() {
        return $this->siglaDisciplina;
    }

    public function setSiglaDisciplina($siglaDisciplina) {
        $this->siglaDisciplina = $siglaDisciplina;
    }

    public function getGradeHorario() {
        return $this->gradeHorario;
    }

    public function setGradeHorario($gradeHorario) {
        $this->gradeHorario = $gradeHorario;
    }

    public function getIdPeriodoLetivo() {
        return $this->idPeriodoLetivo;
    }

    public function setIdPeriodoLetivo($idPeriodoLetivo) {
        $this->idPeriodoLetivo = $idPeriodoLetivo;
    }

    public function getMatriculaProfessor() {
        return $this->matriculaProfessor;
    }

    public function setMatriculaProfessor($matriculaProfessor) {
        $this->matriculaProfessor = $matriculaProfessor;
    }

    public function getTurno() {
        return $this->turno;
    }

    public function setTurno($turno) {
        $this->turno = $turno;
    }

    public function getTipoSituacaoTurma() {
        return $this->tipoSituacaoTurma;
    }

    public function setTipoSituacaoTurma($tipoSituacaoTurma) {
        $this->tipoSituacaoTurma = $tipoSituacaoTurma;
    }

    public function getQtdeTotal() {
        return $this->qtdeTotal;
    }

    public function setQtdeTotal($qtdeTotal) {
        $this->qtdeTotal = $qtdeTotal;
    }
    
    public function getDataLiberacaoPautaPeloProfessor() {
        return $this->dataLiberacaoPautaPeloProfessor;
    }

    private function setDataLiberacaoPautaPeloProfessor($dataLiberacaoPautaPeloProfessor) {
        $this->dataLiberacaoPautaPeloProfessor = $dataLiberacaoPautaPeloProfessor;
    }
    
    public function getDataInicioVigencia( ) {
        return $this->dataInicioVigencia;
    }
    public function setDataInicioVigencia( $dataInicioVigencia ) {
        $this->dataInicioVigencia = $dataInicioVigencia;
    }

    public function setNomeDisciplina($nomeDisciplina) {
        $this->nomeDisciplina = $nomeDisciplina;
    }

    public function getNomeDisciplina() {
        return $this->nomeDisciplina;
    }
    
    public function getSiglaPeriodoLetivo() {
        return $this->getPeriodoLetivo()->getSiglaPeriodoLetivo();
    }

    public static function obterTurmasLiberadasOuConfirmadas($siglaCurso, $idPeriodoLetivo) {
       $collection = array();
       $componenteCurricular;
       $con = BD::conectar();
        $query=sprintf("select t.idTurma, t.siglaCurso, t.idMatriz, t.siglaDisciplina,
                t.gradeHorario, t.idPeriodoLetivo, t.matriculaProfessor, t.turno,
                t.tipoSituacaoTurma, t.qtdeTotal, t.dataLiberacaoPautaPeloProfessor, 
                t.idCriterioAvaliacao
                from Turma t inner join ComponenteCurricular cc
                    on t.siglaCurso=cc.siglaCurso and
                    t.idMatriz=cc.idMatriz and
                    t.siglaDisciplina=cc.siglaDisciplina 
                where t.tipoSituacaoTurma in ('LIBERADA','CONFIRMADA') and
                t.siglaCurso = '%s' and
                t.idPeriodoLetivo = %d
                order by cc.periodo,t.turno,t.siglaDisciplina,t.gradeHorario",
                mysql_real_escape_string($siglaCurso),
                $idPeriodoLetivo);
        $result=mysql_query($query,$con);
        
        while( $resTurmasLiberadas = mysql_fetch_array($result) ) {
            $componenteCurricular = new ComponenteCurricular($resTurmasLiberadas['siglaCurso'], $resTurmasLiberadas['idMatriz'], $resTurmasLiberadas['siglaDisciplina']);
            $componenteCurricular->obterComponenteCurricular($resTurmasLiberadas['siglaCurso'], $resTurmasLiberadas['idMatriz'], $resTurmasLiberadas['siglaDisciplina']);
            $turmasLiberadas = new Turma($resTurmasLiberadas['idTurma']);
            $turmasLiberadas->setGradeHorario($resTurmasLiberadas['gradeHorario']);
            $turmasLiberadas->setIdMatriz($componenteCurricular->getIdMatriz());
            $turmasLiberadas->setIdPeriodoLetivo($resTurmasLiberadas['idPeriodoLetivo']);
            $turmasLiberadas->setIdTurma($resTurmasLiberadas['idTurma']); //previne erros caso o construtor seja alterado
            $turmasLiberadas->setMatriculaProfessor($resTurmasLiberadas['matriculaProfessor']);
            $turmasLiberadas->setQtdeTotal($resTurmasLiberadas['qtdeTotal']);
            $turmasLiberadas->setDataLiberacaoPautaPeloProfessor($resTurmasLiberadas['dataLiberacaoPautaPeloProfessor']);
            $turmasLiberadas->setSiglaCurso($componenteCurricular->getSiglaCurso());
            $turmasLiberadas->setSiglaDisciplina($componenteCurricular->getSiglaDisciplina());
            $turmasLiberadas->setTipoSituacaoTurma($resTurmasLiberadas['tipoSituacaoTurma']);
            $turmasLiberadas->setTurno($resTurmasLiberadas['turno']);
            $turmasLiberadas->setCriterioAvaliacao( $resTurmasLiberadas['idCriterioAvaliacao'] );
            $collection[] = $turmasLiberadas;
        }
        return $collection;
    }


    /*Método que obtem uma lista de alunos em uma determinada turma, de acordo com o estado da sua inscrição na turma
     * OBS.: método similar ao public static function listaAlunosTurma - classe Inscricao
     * Casos de Uso: UC01.03.03
     * @param: $situacao - REQ, ID, NEG, CUR ...
     * @return <listaDeAlunos>  Uma lista com os alunos
     */
    public function getAlunosBySituacao($situacao) {

        $listaDeAlunos = array();
        $conn = BD::conectar();
        // lista os alunos da turma
        $queryListaAlunos = sprintf("SELECT i.matriculaAluno, p.nome, c.siglaDisciplina,
                                nomeDisciplina, i.idTurma from Inscricao i
                                INNER JOIN MatriculaAluno m
                                ON i.matriculaAluno = m.matriculaAluno
                                INNER JOIN Pessoa p
                                ON m.idPessoa = p.idPessoa
                                INNER JOIN Turma t
                                ON t.idTurma = i.idTurma
                                INNER JOIN ComponenteCurricular c
                                ON t.siglaDisciplina = c.siglaDisciplina
                                AND c.idMatriz = t.idMatriz
                                WHERE i.idTurma = %d AND situacaoInscricao='%s' order by p.nome",
                        mysql_real_escape_string($this->getIdTurma()),
                        mysql_real_escape_string($situacao)
        );

        $result=mysql_query($queryListaAlunos,$conn);

        while( $aluno = mysql_fetch_array($result) ) {
                $umaInscricao = new Inscricao();

                $umaInscricao->setMatriculaAluno($aluno['matriculaAluno']);
                $umaInscricao->setNomeAluno($aluno['nome']);
                $umaInscricao->setSiglaDisciplina($aluno['siglaDisciplina']);
                $umaInscricao->setNomeDisciplina($aluno['nomeDisciplina']);
                $umaInscricao->setIdTurma($aluno['idTurma']);
                $listaDeAlunos[] = $umaInscricao;
        }

        return $listaDeAlunos;
    }
    
    /* Retorna uma lista de objetos Aloca da instancia do objeto turma
     * @author: Marcelo Atie
     * @Result: Array<Aloca>
     */
    public function getAlocacoes() {
        return Aloca::getListAlocaByIdTurma($this->getIdTurma());
    }

    /**
     * Produz uma string com o resumo dos tempos de aula-espaço que 
     * esta turma ocupa
     * @return string 
     */
    public function obterAlocacoesComoString() {
        
        // TODO MB para o funcionamento esperado, depende que as alocações
        // venham ordenadas por dia da semana e horaInicio, mas isso não é 
        // garantido pela query no banco (depende da ordem de inserção)
        $arrayAlocacoesAux = $this->getAlocacoes();
        $strAlocacoes = '';
        $c = 0;
        while($c < count($arrayAlocacoesAux)) {
            $diaSemanaBase = $arrayAlocacoesAux[$c]->getTempoSemanal()->getDiaSemana();
            while( ($c < count($arrayAlocacoesAux)) && 
                    ($diaSemanaBase == $arrayAlocacoesAux[$c]->getTempoSemanal()->getDiaSemana()) ) {
                $espacoBase = $arrayAlocacoesAux[$c]->getEspaco()->getNome();
                $strAlocacoes .= $diaSemanaBase . ' (';
                $qtdeTempos = 0;
                while( ($c < count($arrayAlocacoesAux)) && 
                        ($diaSemanaBase == $arrayAlocacoesAux[$c]->getTempoSemanal()->getDiaSemana())
                        && ($espacoBase == $arrayAlocacoesAux[$c]->getEspaco()->getNome()) ) {
                    $strAlocacoes .= $arrayAlocacoesAux[$c]->getTempoSemanal()->obterTempoOrdinalDoTurno() . 'º ';
                    $qtdeTempos++;
                    $c++;
                }
                if($qtdeTempos > 1 ) {
                    $strAlocacoes .= "tempos) na ". $espacoBase . "; ";
                } else {
                    $strAlocacoes .= "tempo) na ". $espacoBase . "; ";
                }
            }
        }
        return $strAlocacoes;
    }

    public function obterDiaLetivoTurmaPorData( DateTime $data ) {
        return new DiaLetivoTurma( $this, clone $data );
    }

    /**
     * Retorna a data da aula que seria hoje ou a mais recente
     * @return DateTime
     */
    public function obterDataAulaMaisRecente() {     
       
        $datasDiaLetivo = $this->obterDatasDiaLetivo();
        
        $dataAtual = new DateTime();
        foreach($datasDiaLetivo as $data) {
            if($dataAtual < $data ) break;
            $dataAnterior = clone $data;
        }
        return new DiaLetivoTurma( $this, clone $dataAnterior );
    }

    /**
     * Gera um array com os dias da semana que tem aula dessa turma
     * @return String[]
     */
    private function obterDiasSemanaComAula() {
        $con = BD::conectar();
        $query = sprintf("select distinct TS.diaSemana from TempoSemanal TS  
            inner join Aloca A on TS.idTempoSemanal = A.idTempoSemanal
            inner join Turma T on T.idTurma = A.idTurma 
            where T.idTurma = %d",
        mysql_real_escape_string($this->idTurma));
        $result = mysql_query($query, $con);
        $col = array();
        while( $reg = mysql_fetch_array($result) ) {
            $col[] = $reg["diaSemana"];
        }
        return $col;
    }

    public function obterDiaLetivoTurmaAnterior(DateTime $dataReferencia) {
        $datasDiaLetivo = $this->obterDatasDiaLetivo();
        $i = array_search($dataReferencia, $datasDiaLetivo);
        if( $i == 0 ) return null;
        return new DiaLetivoTurma( $this, clone $datasDiaLetivo[$i-1] );
    }

    public function obterDiaLetivoTurmaSeguinte(DateTime $dataReferencia) {
        $datasDiaLetivo = $this->obterDatasDiaLetivo();
        $i = array_search($dataReferencia, $datasDiaLetivo);
        if( $i == (count( $datasDiaLetivo ) - 1) ) return null;
        return new DiaLetivoTurma( $this, clone $datasDiaLetivo[$i+1] );
    }

    /**
     * Retorna um array de DateTime representando os dias regulares de aulas
     * @return DateTime[]
     */
    private function obterDatasDiaLetivoRegular() {
        $diasSemanaComAula = $this->obterDiasSemanaComAula();
        $dataInicioAulas = $this->getPeriodoLetivo()->obterDataInicioAulas();
        $dataFimAulas = $this->getPeriodoLetivo()->obterDataFimAulas();
        
        $atual = new DateTime( $dataInicioAulas );
        $fim = new DateTime( $dataFimAulas );
        
        $col = array();
        while( $atual <= $fim ) {
            $codigoDiaSemana = $atual->format("w");
            $siglaDiaSemana = Util::gerarSiglaDiaSemana( $codigoDiaSemana );
            if(in_array($siglaDiaSemana, $diasSemanaComAula)) {
                $col[] = clone $atual;
            }
            $atual = Util::DateTimeAddDay( $atual, 1 );
            $atual->setTime(0, 0, 0);
        }
        return $col;
    }

    /**
     * Retorna um array de DateTime com os dias letivos lançados
     * @return DateTime[]
     */
    private function obterDiasLetivosLancados() {
        $con = BD::conectar();
        $query = sprintf("select distinct data from DiaLetivoTurma DLT 
            inner join Turma T on DLT.idTurma = T.idTurma 
            where T.idTurma = %d",
                $this->idTurma);
        $result = mysql_query($query, $con);
        $col = array();
        while( $reg = mysql_fetch_array($result)) {
            $col[] = new DateTime( $reg["data"] );
        }
        return $col;
    }

    public function obterDatasDiaLetivo() {
        $datasDiaLetivoRegular = $this->obterDatasDiaLetivoRegular();
        $datasDiaLetivosLancado = $this->obterDiasLetivosLancados();
        $datasDiaLetivo = array_merge($datasDiaLetivoRegular,$datasDiaLetivosLancado);
        asort($datasDiaLetivo);
        $unicos = array();
        foreach($datasDiaLetivo as $valor) $unicos[serialize($valor)] = $valor;
        $unicos = array_values($unicos);
        return $unicos;
    }

    /**
     * Obtém informações sobre os apontamentos anteriores e posteriores a um
     * determinado dia letivo
     * @param DateTime $dataDiaLetivoTurma
     * @return \QuadroNavegacaoTurmaTO
     */
    public function gerarQuadroNavegacaoPautaTurma(DateTime $dataDiaLetivoTurma) {
        $listaDataDiaLetivo = $this->obterDatasDiaLetivo();

        $qtdeTotalDiasAnterior = 0;
        $qtdeTotalDiasAnteriorEmAberto = 0;
        while( $listaDataDiaLetivo[$qtdeTotalDiasAnterior] < $dataDiaLetivoTurma ) {
            $qtdeTotalDiasAnterior++;
            if( !$this->isDiaLetivoTurmaLiberado($listaDataDiaLetivo[$qtdeTotalDiasAnterior])) {
                $qtdeTotalDiasAnteriorEmAberto++;
            }
        }

        $qtdeTotalDiasPosterior = count($listaDataDiaLetivo) - 1;
        $qtdeTotalDiasPosteriorEmAberto = 0;
        while( $listaDataDiaLetivo[$qtdeTotalDiasPosterior] > $dataDiaLetivoTurma ) { 
            $qtdeTotalDiasPosterior--;
            if( !$this->isDiaLetivoTurmaLiberado($listaDataDiaLetivo[$qtdeTotalDiasPosterior])) {
                $qtdeTotalDiasPosteriorEmAberto++;
            }
        }
        $qtdeTotalDiasPosterior = count($listaDataDiaLetivo) - $qtdeTotalDiasPosterior - 1;
        return new QuadroNavegacaoTurmaTO($qtdeTotalDiasAnterior,$qtdeTotalDiasPosterior,
                $qtdeTotalDiasAnteriorEmAberto,$qtdeTotalDiasPosteriorEmAberto);
    }

    private function isDiaLetivoTurmaLiberado(DateTime $dataDiaLetivo) {
        $con = BD::conectar();
        $query = sprintf("select count(*) from DiaLetivoTurma 
            where idTurma = %d and
            data = '%s' and 
            dataLiberacao is not null",
                $this->idTurma,
                $dataDiaLetivo->format("Y-m-d"));
        $result = mysql_query($query, $con);
        if(mysql_result($result, 0, 0) == 0) return false;
        return true;
    }

    public function liberarDiaLetivoTurma(DateTime $dataDiaLetivoTurma) {
        $con = BD::conectar();
        $cmd = sprintf("update DiaLetivoTurma set dataLiberacao = CURRENT_DATE() 
            where idTurma = %d and
            data = '%s'",
                $this->idTurma,
                $dataDiaLetivoTurma->format("Y-m-d"));
        $result = mysql_query($cmd, $con);
        if(!$result) {
            throw new Exception("Nao foi possivel liberar apontamento de dia letivo da turma");
        }
    }

    public function getCriterioAvaliacao() {
        return $this->criterioAvaliacao;
    }
    private function setCriterioAvaliacao( $idCriterioAvaliacao ) {
        $this->criterioAvaliacao = CriterioAvaliacao::obterPorId($idCriterioAvaliacao);
    }
    
    /**
     * Indica se os apontamentos de notas para essa turma, para o itemCriterioAvaliacao,
     * já foram liberadas para divulgação. Se sim, as notas não pode ser editadas.
     * @param ItemCriterioAvaliacao $itemCriterioAvaliacao
     * @return Boolean
     */
    public function isAvaliacaoLiberada(ItemCriterioAvaliacao $itemCriterioAvaliacao) {
        return ( $this->getDataLiberacaoNotas($itemCriterioAvaliacao) != null );
    }
    
    /**
     * Indica se todas as situações de avaliação dos alunos estão definidas
     * @return boolean
     */
    public function isSituacoesAvaliacaoDefinidas() {
        $inscricoes = $this->getInscricoesDePauta();
        foreach($inscricoes as $inscricao) {
            $situacaoFinal = $inscricao->obterSituacaoFinalLancadaEmPauta();
            if( $situacaoFinal != "AP" && $situacaoFinal != "RM" && $situacaoFinal != "RF" ) {
                return false;
            }
        }
        return true;
    }

    /**
     * Data em que as notas de um ItemCriterioAvaliacao foi liberada.
     * @param ItemCriterioAvaliacao $itemCriterioAvaliacao
     * @return DateTime com a data da liberação das notas para o itemCriterioAvaliacao,
     * ou nulo, se ainda estiver liberado.
     */
    public function getDataLiberacaoNotas(ItemCriterioAvaliacao $itemCriterioAvaliacao) {
        $con = BD::conectar();
        $query = sprintf("select dataLiberacao from ItemCriterioAvaliacaoTurmaLiberada 
            where idTurma = %d and
            idItemCriterioAvaliacao = %d",
                $this->idTurma,
                $itemCriterioAvaliacao->getIdItemCriterioAvaliacao());
        $result = mysql_query($query, $con);
        if( mysql_num_rows($result) == 0 ) return null;
        return Util::converteDateTime(mysql_result($result, 0, 0));
    }

    /**
     * Marca como liberadas as notas apontadas para os alunos dessa turma
     * para o item de critério de avaliação informado (p.ex. AV1)
     * @param ItemCriterioAvaliacao $itemCriterioAvaliacao
     */
    public function liberarItemCriterioAvaliacao(ItemCriterioAvaliacao $itemCriterioAvaliacao, $con = null) {
        if( $con == null ) $con = BD::conectar ();
        $cmd = sprintf("insert into ItemCriterioAvaliacaoTurmaLiberada 
            (idTurma, idItemCriterioAvaliacao, dataLiberacao) values 
            (%d, %d, NOW() ) on duplicate key update dataLiberacao=NOW()",
                $this->idTurma,
                $itemCriterioAvaliacao->getIdItemCriterioAvaliacao());
        $result = mysql_query( $cmd, $con );
        if( !$result ) {
            throw new Exception("Erro ao atualizar data de liberação em ItemCriterioAvaliacaoTurmaLiberada.");
        }
    }

    public function reabrirItemCriterioAvaliacao($itemCriterioAvaliacao, $con) {
        if( $con == null ) $con = BD::conectar ();
        $cmd = sprintf("delete from ItemCriterioAvaliacaoTurmaLiberada 
            where idTurma = %d and idItemCriterioAvaliacao = %d",
                $this->idTurma,
                $itemCriterioAvaliacao->getIdItemCriterioAvaliacao() );
        $result = mysql_query( $cmd, $con );
        if( !$result ) {
            throw new Exception("Erro ao excluir trava em ItemCriterioAvaliacaoTurmaLiberada");
        }
    }

    /**
     * Retorna a quantidade de tempos de aula apontados e liberados para essa turma
     * @return integer 
     */
    public function obterQtdeTemposAulaApontados() {
        $dias = $this->obterDiasLetivosLancados();
        $qtde = 0;
        foreach($dias as $dia) {
            $diaLetivoTurma = new DiaLetivoTurma($this, $dia);
            $qtde += $diaLetivoTurma->getQtdeTempos();
        }
        return $qtde;
    }

    /**
     * Data em que o professor liberou a pauta (presenças/faltas + notas e 
     * situações) da turma para a secretaria
     * @param Connection $con
     */
    public function liberarPautaParaSecretaria($con = null) {
        if($con == null) $con = BD::conectar ();
        $cmd = sprintf("update Turma set dataLiberacaoPautaPeloProfessor=NOW() 
            where idTurma = %d",
                $this->idTurma );
        $result = mysql_query($cmd, $con);
        if( !$result ) {
            throw new Exception("Não foi possível indicar liberação da pauta da turma");
        }
        
        $inscricoes = $this->getInscricoesDePauta();
        foreach($inscricoes as $inscricao) {
            $inscricao->lancarSituacaoFinal($con);
        }
    }
    
    /**
     * Indica se a pauta da turma está liberada pelo professor
     * @return boolean
     */
    public function isPautaLiberadaPeloProfessor() {
        return $this->dataLiberacaoPautaPeloProfessor != null;
    }

    public function devolverPautaAoProfessor($con = null) {
        if($con == null) $con = BD::conectar ();
        $cmd = sprintf("update Turma set dataLiberacaoPautaPeloProfessor=NULL 
            where idTurma = %d",
                $this->idTurma );
        $result = mysql_query($cmd, $con);
        if( !$result ) {
            throw new Exception("Não foi possível indicar a devolução da pauta da turma ao professor");
        }
    }

    /**
     * Indica se a turma já teve algum apontamento de dia letivo ou de avaliação
     * liberado pelo professor, de forma que seja conveniente avisá-lo sobre 
     * qualquer mudança que ocorra na pauta.
     * 
     * @return boolean
     */
    public function isProfessorJaLiberouAlgumApontamento() {
        $con = BD::conectar();
        $queryDiaLetivo = sprintf("select count(*) from ItemCriterioAvaliacaoTurmaLiberada where "
                . "idTurma = %d and dataLiberacao is not null", $this->idTurma);
        $resultDiaLetivo = mysql_query($queryDiaLetivo, $con);
        if(mysql_result($resultDiaLetivo, 0, 0) == 0) {
            $queryAvaliacao = sprintf("select count(*) from DiaLetivoTurma where "
                . "idTurma = %d and dataLiberacao is not null", $this->idTurma);
            $resultAvaliacao = mysql_query($queryAvaliacao, $con);
            if(mysql_result($resultAvaliacao, 0,  0) == 0 ) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * Indica se existe turma não cancelada na mesma grade
     * @param type $siglaCurso
     * @param type $idMatriz
     * @param type $siglaDisciplina
     * @param type $gradeHorario
     * @param type $turno
     * @param type $idPeriodoLetivo
     * @return boolean
     */
    public static function existeTurmaNaoCancelada($siglaCurso, $idMatriz, $siglaDisciplina, $gradeHorario, $turno, $idPeriodoLetivo) {
        $con = BD::conectar();
        $query = sprintf("select count(*) from Turma where siglaCurso='%s' "
                . "and idMatriz=%d and siglaDisciplina='%s' and "
                . "gradeHorario='%s' and turno='%s' and idPeriodoLetivo=%d "
                . "and tipoSituacaoTurma <> '%s'",
                $siglaCurso,
                $idMatriz,
                $siglaDisciplina,
                $gradeHorario,
                $turno,
                $idPeriodoLetivo,
                Turma::CANCELADA);
        $result = mysql_query($query, $con);
        if(mysql_result($result, 0, 0) == 0 ) return false;
        return true;
    }

    /**
     * Determina se professor deve ser notificado por mudança de alunos na pauta
     */
    public function isNotificaProfessorMudancaPauta() {
        return  $this->isProfessorJaLiberouAlgumApontamento() &&
                !$this->isPautaLiberadaPeloProfessor() &&
                !$this->getPeriodoLetivo()->isDataForaPeriodo( Util::obterDataAtual() );
    }

    /**
     * Indica se o usuário informado pode editar essa pauta
     * @param Login $login login do usuário atual do sistema
     * @return boolean pode atualizar pauta?
     */
    public function isPodeEditarPauta( Login $login)
    {
        return $this->getProfessor()->getIdPessoa() === $login->getIdPessoa()
                || $login->getIdPessoa() == Config::SECRETARIA_ID_PESSOA; 
    }
}