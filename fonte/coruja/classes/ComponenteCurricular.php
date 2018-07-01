<?php
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/to/QuitacaoComponenteCurricularTO.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";

class ComponenteCurricular 
{
    private $siglaCurso;
    private $idMatriz;
    private $siglaDisciplina;
    private $nomeDisciplina;
    private $creditos;
    private $cargaHoraria;
    private $periodo;
    private $tipoComponenteCurricular;
    private $posicaoNoPeriodo;

    /*
    * Construtor da Classe
    */
    function __construct($siglaCurso,$idMatriz,$siglaDisciplina) {
        $this->siglaCurso = $siglaCurso;
        $this->idMatriz = $idMatriz;
        $this->siglaDisciplina = $siglaDisciplina;
    }

    /*
     * Funcao para obter um Componente Curricular
     ** Casos de Uso:
     *      UC01.03.02 - Criar Turma
     *      UC02.06.00, UC02.07.00
     *      Classe Turma - getComponenteCurricular()
     *      UC01.08.02 - Lançar Notas
     * @param: Curso, Matriz e Sigla da Disciplina
     * @result: Objeto de Componente Curricular
    */
    public static function obterComponenteCurricular($siglaCurso, $idMatriz, $siglaDisciplina) {
        $con = BD::conectar();
        $query = sprintf("select * from ComponenteCurricular ".
                "where siglaCurso= '%s' and idMatriz = %d and siglaDisciplina = '%s' ",
                $siglaCurso, 
                $idMatriz,
                $siglaDisciplina);
        $result = mysql_query($query,$con);

        while( $resComponente = mysql_fetch_array($result) ) {
            $matrizCurricular = new MatrizCurricular($resComponente['siglaCurso'], $resComponente['idMatriz']);
            $matrizCurricular->carregarMatrizCurricular();
            $curso=Curso::obterCurso($resComponente['siglaCurso']);
            $componente = new ComponenteCurricular($curso->getSiglaCurso(),$matrizCurricular->getIdMatriz(),$resComponente['siglaDisciplina']);

            $componente->setNomeDisciplina($resComponente['nomeDisciplina']);
            $componente->setCreditos($resComponente['creditos']);
            $componente->setCargaHoraria($resComponente['cargaHoraria']);
            $componente->setPeriodo($resComponente['periodo']);
            $componente->setTipoComponenteCurricular($resComponente['tipoComponenteCurricular']);
            $componente->setPosicaoPeriodo($resComponente['posicaoPeriodo']);
        }
        return $componente;
    }

    /*
     * Funcao para obter os Prerequisitos de um dado componente curricular
     ** Casos de Uso: UC02.06.00
     * @resul: Retorna um array de componentes curriculares
    */
    public function obterPreRequisitos() {
        $preRequisitos = array();
        $con = BD::conectar();

        $query=sprintf("Select * ".
                "from ComponentePreRequisito ".
                "where siglaCurso= '%s' and ".
                "idMatriz = %d and ".
                "siglaDisciplina = '%s' ",$this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina());
        $result=mysql_query($query,$con);
        
        while( $resComponentePreRequisito = mysql_fetch_array($result) ) {
            $siglaCurso=$resComponentePreRequisito['siglaCurso'];
            $idMatriz=$resComponentePreRequisito['idMatriz'];
            $siglaDisciplina=$resComponentePreRequisito['siglaDisciplinaPreRequisito'];
            $componente = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
            $preRequisitos[] = $componente;
        }
        return $preRequisitos;
    }

    public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    public function getIdMatriz() {
        return $this->idMatriz;
    }

    public function getSiglaDisciplina() {
        return $this->siglaDisciplina;
    }

    public function getNomeDisciplina() {
        return $this->nomeDisciplina;
    }

    public function getCreditos(){
        return $this->creditos; 
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function getLimiteFaltas() {
        return ($this->cargaHoraria * 25) / 100;
    }

    public function getPeriodo(){
        return $this->periodo;
    }

    public function getTipoComponenteCurricular(){
        return $this->tipoComponenteCurricular;
    }
    public function getPosicaoPeriodo(){
        return $this->posicaoPeriodo;
    }

    public function setSiglaCurso($siglaCurso) {
       $this->siglaCurso=$siglaCurso;
    }

    public function setidMatriz($idMatriz){
       $this->idMatriz=$idMatriz;
    }

    public function setSiglaDisciplina($siglaDisciplina){
        $this->siglaDisciplina=$siglaDisciplina;
    }

    public function setNomeDisciplina($nomeDisciplina){
        $this->nomeDisciplina=$nomeDisciplina;
    }

    public function setCreditos($creditos){
        $this->creditos=$creditos;
    }

    public function setCargaHoraria($cargaHoraria){
        $this->cargaHoraria=$cargaHoraria;
    }

    public function setPeriodo($periodo){
        $this->periodo=$periodo;
    }
    
    public function setTipoComponenteCurricular($tipoComponenteCurricular){
        $this->tipoComponenteCurricular=$tipoComponenteCurricular;
    }
    public function setPosicaoPeriodo($posicaoPeriodo){
        $this->posicaoPeriodo=$posicaoPeriodo;
    }

    /**
     * Retorna uma instância QuitacaoComponenteCurricularTO, ou nulo,
     * indicando se o componente curricular foi cumprido por uma
     * determinada matriculaAluno.
     * Casos de Uso: UC01.02.00 e UC03.01.00
     * com o período letivo de cumprimento
     * @param $matriculaAluno instância de MatriculaAluno da qual se deseja
                              saber se houve cumprimento do componente.
     * @result objeto de QuitacaoComponenteCurricularTO, se o componente
     *         foi cumprido pela matricula, ou nulo, caso não tenha.
     * @author Marcio Belo
     */
    public function obterQuitacao(MatriculaAluno $matriculaAluno) {
        $con = BD::conectar();

        // Verifica se cumpriu da mesma matriz
        $query = sprintf("select t.idPeriodoLetivo as idPeriodoLetivo,cc.creditos as creditos, " .
            "i.situacaoInscricao,max(i.mediaFinal) as mediaFinal from " .
            "MatriculaAluno ma inner join ComponenteCurricular cc ".
            "on ma.siglaCurso = cc.siglaCurso  " .
            "inner join Turma t on t.siglaCurso = cc.siglaCurso " .
            "and t.siglaDisciplina = cc.siglaDisciplina " .
            "and t.idMatriz = cc.idMatriz " .
            "inner join Inscricao i on i.idTurma = t.idTurma " .
            "where t.tipoSituacaoTurma='FINALIZADA' " .
            "and i.situacaoInscricao in ('AP','ID') " .
            "and i.matriculaAluno = '%s' " .
            "and cc.siglaDisciplina = '%s' " .
            "and cc.idMatriz = %d " .
            "group by t.idPeriodoLetivo,cc.creditos,i.situacaoInscricao",
            mysql_real_escape_string($matriculaAluno->getMatriculaAluno()),
            mysql_real_escape_string($this->getSiglaDisciplina()),
            $this->getIdMatriz());
        $result=mysql_query($query,$con);     
        if( mysql_num_rows($result) >= 1 ) {
            $resCC = mysql_fetch_array($result);
            $idPeriodoLetivo = $resCC["idPeriodoLetivo"];
            $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodoLetivo);
            $creditos = $resCC["creditos"];
            $mediaFinal = $resCC["mediaFinal"];
            $isento = ( $resCC["situacaoInscricao"] == Inscricao::ID ? true : false );
            $quitacaoComponenteCurricularTO=new QuitacaoComponenteCurricularTO($periodoLetivo, 
                $mediaFinal,$creditos,$isento);
            return $quitacaoComponenteCurricularTO;
        }

        // Verifica se cumpriu em matriz anterior, direta ou indiretamente.
        if($this->getIdMatriz() <= $matriculaAluno->getIdMatriz() ) {
            $arrayCeMatrizAnterior=$this->obterComponentesEquivalentesMatrizAnterior();
            if( count( $arrayCeMatrizAnterior ) > 0 ) {
                $notaTotal=0;
                $creditosTotal=0;
                foreach($arrayCeMatrizAnterior as $ce) {
                    $quitacaoAnteriorTO = $ce->obterQuitacao( $matriculaAluno );
                    if($quitacaoAnteriorTO == null) {
                        $creditosTotal=0;
                        break;
                    } else {
                        // TODO MB criar testes unitários que verifiquem essa situação
                        if( $quitacaoAnteriorTO->isIsento() ) {
                            $quitacaoComponenteEquivalenteTO = new QuitacaoComponenteCurricularTO(
                                $quitacaoAnteriorTO->getPeriodoLetivo(), 0.0 , $this->getCreditos(), TRUE );
                            return $quitacaoComponenteEquivalenteTO;                            
                        } else {
                            $notaTotal += $quitacaoAnteriorTO->getMediaFinal() * $quitacaoAnteriorTO->getCreditos();
                            $creditosTotal += $quitacaoAnteriorTO->getCreditos();
                            $periodoLetivo = $quitacaoAnteriorTO->getPeriodoLetivo();
                        }
                    }
                }
                if( $creditosTotal > 0 ) {
                    $mediaFinal = $notaTotal / $creditosTotal;
                    $quitacaoComponenteEquivalenteTO=new QuitacaoComponenteCurricularTO(
                        $periodoLetivo, $mediaFinal , $this->getCreditos(), FALSE );
                    return $quitacaoComponenteEquivalenteTO;
                }
            }
        }

        // Verifica se cumpriu em matriz posterior, direta ou indiretamente.
        if($this->getIdMatriz() >= $matriculaAluno->getIdMatriz() ) {
            // Verifica se existe matriz posterior
            $idMatrizPosterior = $this->getIdMatriz() + 1;
            $matrizPosterior=MatrizCurricular::obterMatrizCurricular($this->getSiglaCurso(),$idMatrizPosterior);
            if($matrizPosterior != null) {
                $arrayCeMatrizPosterior=$this->obterComponentesEquivalentesMatrizPosterior();
                if( count( $arrayCeMatrizPosterior ) > 0 ) {
                    $notaTotal=0;
                    $creditosTotal=0;
                    foreach($arrayCeMatrizPosterior as $ce) {
                        $quitacaoPosteriorTO = $ce->obterQuitacao( $matriculaAluno );
                        if($quitacaoPosteriorTO == null) {
                            $creditosTotal=0;
                            break;
                        } else {
                            // TODO MB criar testes unitários que verifiquem essa situação
                            if( $quitacaoPosteriorTO->isIsento() ) {
                                $quitacaoComponenteEquivalenteTO = new QuitacaoComponenteCurricularTO(
                                    $quitacaoPosteriorTO->getPeriodoLetivo(), 0.0 , $this->getCreditos(), TRUE );
                                return $quitacaoComponenteEquivalenteTO;                            
                            } else {                            
                                $notaTotal += $quitacaoPosteriorTO->getMediaFinal() * $quitacaoPosteriorTO->getCreditos();
                                $creditosTotal += $quitacaoPosteriorTO->getCreditos();
                                $periodoLetivo = $quitacaoPosteriorTO->getPeriodoLetivo();
                            }
                        }
                    }
                    if( $creditosTotal > 0 ) {
                        $mediaFinal = $notaTotal / $creditosTotal;
                        $quitacaoComponenteEquivalenteTO=new QuitacaoComponenteCurricularTO(
                            $periodoLetivo, $mediaFinal , $this->getCreditos(), FALSE);
                        return $quitacaoComponenteEquivalenteTO;
                    }
                }
            }
        }
        
        return null; // indica que não cumpriu o componente
    }

    /**
     * Obtem os Componentes Curriculares Equivalentes em matriz anterior
     * @param $idMatrizAnterior identificador da matriz anterior
     * @return array de ComponenteCurricular
     * @author Marcio Belo
     */
    private function obterComponentesEquivalentesMatrizAnterior() {
        $idMatrizAnterior = $this->getIdMatriz() - 1;
        $con = BD::conectar();
        $query = sprintf("select ce.siglaDisciplinaEquivalente as siglaDisciplinaEquivalente " .
            "from ComponenteEquivalente ce ".
            "where ce.siglaCurso='%s' and ce.idMatriz=%d and  " .
            "ce.siglaDisciplina='%s' and ce.idMatrizEquivalente=%d",
            mysql_real_escape_string($this->getSiglaCurso()),
            $this->getIdMatriz(),
            mysql_real_escape_string($this->getSiglaDisciplina()),
            $idMatrizAnterior);
        $col = array();
        $result=mysql_query($query,$con);
        while( $resCC = mysql_fetch_array($result) ) {
            $siglaDisciplinaEquivalente = $resCC["siglaDisciplinaEquivalente"];
            $ce = ComponenteCurricular::obterComponenteCurricular($this->getSiglaCurso(),
                $idMatrizAnterior, $siglaDisciplinaEquivalente);
            array_push($col, $ce);
        }
        return $col;
    }

    /**
     * Obtem os Componentes Curriculares Equivalentes em matriz posterior
     * @param $idMatrizAnterior identificador da matriz anterior
     * @return array de ComponenteCurricular
     * @author Marcio Belo
     */
    private function obterComponentesEquivalentesMatrizPosterior() {
        $idMatrizPosterior = $this->getIdMatriz()+1;
        $con = BD::conectar();
        $query = sprintf("select ce.siglaDisciplina as siglaDisciplinaEquivalente " .
            "from ComponenteEquivalente ce ".
            "where ce.siglaCursoEquivalente='%s' and ce.idMatrizEquivalente=%d and " .
            "ce.siglaDisciplinaEquivalente='%s' and ce.idMatriz=%d",
            mysql_real_escape_string($this->getSiglaCurso()),
            $this->getIdMatriz(),
            mysql_real_escape_string($this->getSiglaDisciplina()),
            $idMatrizPosterior);
        $col = array();
        $result=mysql_query($query,$con);
        while( $resCC = mysql_fetch_array($result) ) {
            $siglaDisciplinaEquivalente = $resCC["siglaDisciplinaEquivalente"];
            $ce = ComponenteCurricular::obterComponenteCurricular($this->getSiglaCurso(),
                $idMatrizPosterior, $siglaDisciplinaEquivalente);
            array_push($col, $ce);
        }
        return $col;
    }

    /**
     * Obtem a quatindade de reprovacoes (RF ou RM) de uma matricula
     * @param MatriculaAluno $matriculaAluno
     * @return int qtde de reprovacoes
     */
    public function obterQtdeReprovacoes(MatriculaAluno $matriculaAluno)
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from Inscricao I inner join Turma T on I.idTurma = T.idTurma "
                . " where I.matriculaAluno='%s'"
                . " and T.siglaCurso='%s' "
                . " and T.idMatriz=%d "
                . " and T.siglaDisciplina = '%s'"
                . " and I.situacaoInscricao in ('RF', 'RM')", 
                mysql_real_escape_string( $matriculaAluno->getNumMatriculaAluno()),
                mysql_real_escape_string( $this->getSiglaCurso()),
                $this->idMatriz,
                mysql_real_escape_string( $this->getSiglaDisciplina()) );
        $result = mysql_query($query, $con);
        return mysql_result($result, 0, 0);
    }

    public function obterInformacoesEquivalenciasPropostas() {
        $informacoesEquivalencias = array();
        $totCreditosCc = $this->getCreditos();
        $totCargaHorariaCc = $this->getCargaHoraria();
        $totCreditosEquivalencia = 0;
        $totCargaHorariaEquivalencia = 0;
        
        $con = BD::conectar();
        $query = sprintf("SELECT cp.* FROM ComponenteCurricularProposto cp " .
                         "INNER JOIN EquivalenciaProposta ep ON ep.siglaDisciplina = cp.siglaDisciplina " .
                         "WHERE ep.siglaEquivalencia = '%s'", $this->getSiglaDisciplina());
        
        
        $result = mysql_query($query,$con);
        

        $informacoesEquivalencias['informacoesCc']['siglaDisciplina'] = $this->getSiglaDisciplina();
        $informacoesEquivalencias['informacoesCc']['cargaHoraria'] = $this->getCargaHoraria();
        $informacoesEquivalencias['informacoesCc']['creditos'] = $this->getCreditos();
        $informacoesEquivalencias['informacoesCc']['estadoEquivalencia'] = false;
        
        $count = 0;
        while ($row = mysql_fetch_array($result)) {
            $informacoesEquivalencias['informacoesEquivalencias'][$count] = ComponenteCurricularProposto::obterComponeteCurricular($this->getSiglaCurso(), $this->getIdMatriz(), $row['siglaDisciplina']);
            $totCreditosEquivalencia += $informacoesEquivalencias['informacoesEquivalencias'][$count]->getCreditos();
            $totCargaHorariaEquivalencia += $informacoesEquivalencias['informacoesEquivalencias'][$count]->getCargaHoraria();
            $count++;
        }
        if($totCreditosEquivalencia > 0 || $totCargaHorariaEquivalencia > 0) {
            $informacoesEquivalencias['informacoesCc']['estadoEquivalencia'] = 'parcial';
        }
        
        if($totCreditosEquivalencia >= $totCreditosCc && $totCargaHorariaEquivalencia >= $totCargaHorariaCc) {
            $informacoesEquivalencias['informacoesCc']['estadoEquivalencia'] = 'total';
        }
        
        return $informacoesEquivalencias;
    }
    
    public function definirPosicaoPeriodo ($posicao) {
        $con = BD::conectar();
        $query = sprintf("UPDATE ComponenteCurricular set posicaoPeriodo=%d WHERE siglaDisciplina = '%s' AND siglaCurso = '%s' AND idMatriz = %d",
                          $posicao, $this->getSiglaDisciplina(), $this->getSiglaCurso(), $this->getIdMatriz());
        
        $result = mysql_query($query,$con);

        if (!$result) {
            throw new Exception("Erro ao definir posicao periodo do componente.");
        }
    }
    
    public function criar() {
        $con = BD::conectar();
        
        $query = sprintf("INSERT INTO ComponenteCurricular (siglaCurso, idMatriz, siglaDisciplina, nomeDisciplina, creditos, cargaHoraria, periodo, "
                          . "tipoComponenteCurricular, posicaoPeriodo) VALUES ('%s', %d, '%s', '%s', %d, %d, %d, '%s', %d)",
                           $this->getSiglaCurso(), $this->getIdMatriz(), mysql_escape_string($this->getSiglaDisciplina()), mysql_escape_string($this->getNomeDisciplina()),
                          $this->getCreditos(), $this->getCargaHoraria(), $this->getPeriodo(), mysql_escape_string($this->getTipoComponenteCurricular()),
                          $this->getPosicaoPeriodo());

        $result = mysql_query($query,$con);
        if (!$result) {
            throw new Exception("Erro ao criar Componente Curricular.");
        }
    }
    
    public function definirPreRequisitos($preRequisitos) {
        $con = BD::conectar();
        
        foreach ($preRequisitos as $preRequisito) {            
            $query  = sprintf("INSERT INTO ComponentePreRequisito VALUES('%s', %d ,'%s', '%s', %d ,'%s')" , 
                                $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina(), 
                                $this->getSiglaCurso(), $this->getIdMatriz(), $preRequisito->getSiglaDisciplina());

            $result = mysql_query($query,$con);
            if(!$result) {
                throw new Exception("Erro ao definir Pre Requisito.");
            }
        }
    }


}