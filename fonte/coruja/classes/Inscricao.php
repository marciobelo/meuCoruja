<?php
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/DiaLetivoTurma.php";
require_once "$BASE_DIR/classes/ItemCriterioAvaliacao.php";
require_once "$BASE_DIR/classes/ItemCriterioAvaliacaoInscricaoNota.php";
require_once "$BASE_DIR/siro/classes/funcoesRN.php";

class Inscricao {

    // Estados da Inscrição
    const REQ   = "REQ";
    const DEF   = "DEF";
    const NEG   = "NEG";
    const CUR   = "CUR";
    const EXC   = "EXC";
    const AP    = "AP";
    const RF    = "RF";
    const RM    = "RM";
    const ID    = "ID";

    const RECLAMADO_PELO_PROFESSOR = "RECLAMADO PELO PROFESSOR";
    
    private $idTurma;
    private $matriculaAluno;
    private $situacaoInscricao;
    private $dataInscricao;
    private $mediaFinal;
    private $totalFaltas;
    private $parecerInscricao;

    private $nomeAluno;
    private $siglaDisciplina;
    private $nomeDisciplina;
    private $gradeHorario;

    public function setIdTurma($idTurma) {
        $this->idTurma = $idTurma;
    }

    public function getIdTurma() {
        return $this->idTurma;
    }
    
    public function getTurma() {
        return Turma::getTurmaById($this->idTurma);
    }

    function setMatriculaAluno($matriculaAluno){
    	$this->matriculaAluno = $matriculaAluno;
    }

    function getMatriculaAluno() {
        return $this->matriculaAluno;
    }

    public function setSituacaoInscricao($situacaoInscricao){
    	$this->situacaoInscricao = $situacaoInscricao;
    }

    public function getSituacaoInscricao() {
        return $this->situacaoInscricao;
    }

    public function setDataInscricao($dataInscricao){
    	$this->dataInscricao = $dataInscricao;
    }

    public function getDataInscricao() {
        return $this->dataInscricao;
    }

    public function setMediaFinal($mediaFinal){
    	$this->mediaFinal = $mediaFinal;
    }

    public function getMediaFinal() {
        return $this->mediaFinal;
    }

    public function setTotalFaltas($totalFaltas){
    	$this->totalFaltas = $totalFaltas;
    }

    public function getTotalFaltas() {
        return $this->totalFaltas;
    }

    public function setParecerInscricao($parecerInscricao){
    	$this->parecerInscricao = $parecerInscricao;
    }

    public function getParecerInscricao() {
        return $this->parecerInscricao;
    }
    
    // TODO MB eliminar esse método
    function setNomeAluno($nomeAluno) {
        $this->nomeAluno = $nomeAluno;
    }

    function getNomeAluno() {
        return MatriculaAluno::obterMatriculaAluno($this->matriculaAluno)->getAluno()->getNome();
    }
    public function setSiglaDisciplina($siglaDisciplina) {
        $this->siglaDisciplina = $siglaDisciplina;
    }
    public function getSiglaDisciplina() {
        return $this->siglaDisciplina;
    }
    public function setNomeDisciplina($nomeDisciplina) {
        $this->nomeDisciplina = $nomeDisciplina;
    }
    public function getNomeDisciplina() {
        return $this->nomeDisciplina;
    }
    public function setGradeHorario($gradeHorario) {
        $this->gradeHorario = $gradeHorario;
    }

    public function getGradeHorario() {
        return $this->gradeHorario;
    }

    public function obterMatriculaAluno() {
        return MatriculaAluno::obterMatriculaAluno( $this->matriculaAluno );
    }
   
    public static function requererInscricao($idTurma, $matriculaAluno, $parecerInscricao, $con = null) {
        if($con == null) $con = BD::conectar();
        $cmdAlteraInscricao = sprintf("UPDATE Inscricao
            set situacaoInscricao='%s',
            parecerInscricao='%s'
            where idTurma = %d AND
            matriculaAluno = '%s' AND ( situacaoInscricao in ('EXC','NEG','REQ','DEF') )",
                Inscricao::REQ,
                mysql_real_escape_string($parecerInscricao),
                $idTurma,
                mysql_real_escape_string($matriculaAluno) );
        mysql_query($cmdAlteraInscricao, $con);
        if( mysql_affected_rows() != 1 ) {
            $cmdInclui = sprintf("insert into Inscricao
                (idTurma, matriculaAluno, situacaoInscricao, parecerInscricao)
                    VALUES (%d, '%s', '%s', '%s')",
                    $idTurma,
                    mysql_real_escape_string($matriculaAluno),
                    Inscricao::REQ,
                    mysql_real_escape_string($parecerInscricao) );
            mysql_query($cmdInclui, $con);
            if( mysql_affected_rows($con) != 1 ) {
                throw new Exception("Erro ao registar solicitação de inscrição.");
            }
        }
    }

    public static function registrarCursando($idTurma, $numMatriculaAluno, $parecerInscricao, $con = null) {
        if($con == null) $con = BD::conectar();
        $alteraInscricao = sprintf("UPDATE Inscricao
            set situacaoInscricao='%s',
            parecerInscricao='%s'
            where idTurma = %d AND
            matriculaAluno = '%s' AND ( situacaoInscricao in ('EXC','NEG','REQ','DEF') )",
                Inscricao::CUR,
                mysql_real_escape_string($parecerInscricao),
                $idTurma,
                mysql_escape_string($numMatriculaAluno) );
        mysql_query($alteraInscricao, $con);
        if( mysql_affected_rows() != 1 ) { // Se não alterou ninguém, inclui como cursando
            $incluiInscricao = sprintf("INSERT INTO Inscricao
            (idTurma, matriculaAluno, situacaoInscricao,parecerInscricao )
            VALUES (%d, '%s', '%s',
                'Incluído na turma por solicitação deferida dentro do prazo de inclusão e exclusão')",
                    $idTurma,
                    mysql_real_escape_string($numMatriculaAluno),
                    Inscricao::CUR);
            @mysql_query($incluiInscricao,$con);
            if( mysql_affected_rows($con) != 1 ) {
                throw new Exception("Erro ao registar solicitação de inscrição.
                    Possivelmente o aluno já está cursando ou cursou essa turma.");
            }
        }
    }


    /**
     * Nega a inscrição de uma solicitação
     * @param type $idTurma
     * @param type $numMatriculaAluno
     * @param type $parecerInscricao
     * @param type $con
     * @throws Exception
     */
    public static function negarInscricao($idTurma, $numMatriculaAluno, $parecerInscricao, $con) {
        if($con == null) $con = BD::conectar();
        $alteraInscricao = sprintf("UPDATE Inscricao
            set situacaoInscricao='%s',
            parecerInscricao='%s'
            where idTurma = %d AND
            matriculaAluno = '%s' AND situacaoInscricao in (%s)",
                Inscricao::NEG,
                mysql_real_escape_string($parecerInscricao),
                $idTurma,
                mysql_escape_string($numMatriculaAluno),
                "'" . Inscricao::REQ . "','" . Inscricao::DEF . "'" );
        mysql_query($alteraInscricao, $con);
        if( mysql_affected_rows() != 1 ) {
                throw new Exception("Erro ao negar solicitação de inscrição.");
        }        
    }
    
    /**
     * Indica se existe conflito de horário entre a turma que se deseja 
     * inscrever o aluno e outra em que ele esteja inscrito
     * Casos de Uso: UC02.06.00, UC02.01.00
     * @param type $idTurma turma onde se intenciona inscrever o aluno
     * @param type $matriculaAluno matricula do aluno
     * @param type $situacaoInscricao
     * @return boolean indica conflito
     */
    public static function verificaConflito($idTurma, $matriculaAluno) 
    {
            $con = BD::conectar();
            
            $queryVerificacao = sprintf("select idTempoSemanal from Aloca A1 
                    inner join Turma T1 on A1.idTurma = T1.idTurma  
                where T1.idTurma = %d
                and idTempoSemanal 
                in (
                    select idTempoSemanal from Aloca A2
                        inner join Turma T2 on A2.idTurma = T2.idTurma
                        inner join Inscricao I1 on I1.idTurma = T2.idTurma
                    where T2.tipoSituacaoTurma <> 'CANCELADA'
                    and T2.idTurma <> T1.idTurma 
                    and T2.idPeriodoLetivo = T1.idPeriodoLetivo
                    and I1.matriculaAluno='%s'
                    and I1.situacaoInscricao not in ('EXC', 'ID', 'NEG') 
                   )",
                   $idTurma,
                   mysql_escape_string($matriculaAluno) );
            $resultado = mysql_query($queryVerificacao,$con);
            
            if(mysql_num_rows($resultado) > 0) {//se trouxer resultado significa que há conflito de horario;
                return true; //"O aluno não vai poder se inscrever na disciplina por conflito de horário";
            } else {
                return false; //"Não há conflito de horário";
            }
    }
    
    public static function excluir($idTurma, $matriculaAluno, $parecer, $con = null ) {
         if($con==null) $con = BD::conectar();
         $query = sprintf("update Inscricao set situacaoInscricao='%s',
             parecerInscricao = '%s'
            where idTurma = %d and matriculaAluno = '%s' and
            situacaoInscricao in ('CUR','REQ')",
            Inscricao::EXC,
            mysql_real_escape_string($parecer),
            $idTurma,
            mysql_real_escape_string($matriculaAluno) );
         mysql_query($query,$con);
         if( mysql_affected_rows($con) != 1 ) {
            throw new Exception("Não foi possível excluir a inscrição.");
        }
    }

    /*Funcao que retorna a lista de alunos inscritos numa turma
    /*Funcao que obtem uma lista de alunos cursando uma determinada turma
    * Casos de Uso: UC02.07.00
    * @param: $idTurma - idTurma
    * @return <listaDeAlunos>  Uma lista com as turmas liberadas.
    */
    public static function listaAlunosTurma($idTurma) {
        $listaDeAlunos = array();
        $con = BD::conectar();
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
                                WHERE i.idTurma = %d AND situacaoInscricao='CUR' order by p.nome", $idTurma);

        $result = mysql_query($queryListaAlunos, $con);

        while ($aluno = mysql_fetch_array($result)) {
            $listaInscricao = new Inscricao();

            $listaInscricao->setMatriculaAluno($aluno['matriculaAluno']);
            $listaInscricao->setNomeAluno($aluno['nome']);
            $listaInscricao->setSiglaDisciplina($aluno['siglaDisciplina']);
            $listaInscricao->setNomeDisciplina($aluno['nomeDisciplina']);
            $listaInscricao->setIdTurma($aluno['idTurma']);
            $listaDeAlunos[] = $listaInscricao;
        }
        return $listaDeAlunos;
    }

    /*Funcao que obtem uma lista de alunos solicitantes
     * Casos de Uso: UC02.03.00
     * @param: $siglaCurso, $idPeriodoLetivo
     * @return <solicitantes>  Uma lista com os solicitantes.
     */
    public static function buscaSolicitantes( $siglaCurso, $idPeriodoLetivo ) {
            $listaSolicitantes = array();
            // retorna o dado
            $con = BD::conectar();

            // retorna o valor no DB
            $query = sprintf("select DISTINCT (i.matriculaAluno), nome ".
                                "from Inscricao i ".
                                "inner join Turma t ".
                                "on t.idTurma = i.idTurma ".
                                "inner join MatriculaAluno m ".
                                "on i.matriculaAluno = m.matriculaAluno ".
                                "inner join Pessoa p ".
                                "on m.idPessoa = p.idPessoa ".
                                "where t.siglaCurso= '%s' ".
                                "and t.idPeriodoLetivo= %d ".
                                "order by nome",$siglaCurso,$idPeriodoLetivo);
            $result=mysql_query($query,$con);
            
            while($resSolicitante = mysql_fetch_array($result) ) {
                $buscaSolicitantes = new Inscricao();

                $buscaSolicitantes->setMatriculaAluno($resSolicitante['matriculaAluno']);
                $buscaSolicitantes->setNomeAluno($resSolicitante['nome']);
                $listaSolicitantes[] = $buscaSolicitantes;
            }

            //monta a descricao para o log de autoria
            $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo($idPeriodoLetivo);
            $classeCurso = Curso::obterCurso( $periodoLetivo->getSiglaCurso() );

            global $EXIBIR_RESULTADO_SOLICITACAO_INSCRICAO;


            $descricao="Emissão do resultado da solicitação de inscricao para o período letivo ".
                    $periodoLetivo->getSiglaPeriodoLetivo()." (".
                    $periodoLetivo->getDataInicio()." - ".
                    $periodoLetivo->getDatafim().") ".
                    "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso()."). ";


            $usuario = $_SESSION["usuario"];

            $usuario->incluirLog($EXIBIR_RESULTADO_SOLICITACAO_INSCRICAO,$descricao);
            return $listaSolicitantes;
    }

     /*Funcao que obtem o resultado das solicitacoes dos alunos
     * Casos de Uso: UC02.03.00
     * @param: $siglaCurso, $idPeriodoLetivo, $matriculaAluno
     * @return <solicitacoes>  Uma lista com as solicitacoes.
     */
    public static function buscaResultadoSolicitacoes( $siglaCurso, $idPeriodoLetivo, $matriculaAluno ) {
            $listaResultadoSolicitacoes = array();
         
            $con = BD::conectar();
            
            // retorna o valor no DB
            $query = sprintf("select t.idTurma, gradeHorario, t.siglaDisciplina, nomeDisciplina, ".
                                "situacaoInscricao, IFNULL(parecerInscricao,'') as parecerInscricao ".
                                "from Inscricao i ".
                                "inner join Turma t ".
                                "on t.idTurma = i.idTurma ".
                                "inner join MatriculaAluno m ".
                                "on i.matriculaAluno = m.matriculaAluno ".
                                "inner join Pessoa p ".
                                "on m.idPessoa = p.idPessoa ".
                                "INNER JOIN ComponenteCurricular c ".
                                "ON t.siglaDisciplina = c.siglaDisciplina ".
                                "AND c.idMatriz = t.idMatriz ".
                                "where t.siglaCurso= '%s' ".
                                "and t.idPeriodoLetivo= %d ".
                                "and i.matriculaAluno= '%s' ",
                    mysql_escape_string($siglaCurso),$idPeriodoLetivo,
                    mysql_escape_string($matriculaAluno));
 
            $result=mysql_query($query,$con);
            
            while($resSolicitacao = mysql_fetch_array($result) ) {

                $buscaSolicitacoes = new Inscricao();

                $buscaSolicitacoes->setIdTurma($resSolicitacao['idTurma']);
                $buscaSolicitacoes->setGradeHorario($resSolicitacao['gradeHorario']);
                $buscaSolicitacoes->setSiglaDisciplina($resSolicitacao['siglaDisciplina']);
                $buscaSolicitacoes->setNomeDisciplina($resSolicitacao['nomeDisciplina']);
                $buscaSolicitacoes->setSituacaoInscricao($resSolicitacao['situacaoInscricao']);
                $buscaSolicitacoes->setParecerInscricao($resSolicitacao['parecerInscricao']);

                $listaResultadoSolicitacoes[] = $buscaSolicitacoes;
            }
            return $listaResultadoSolicitacoes;
    }

    /*Funcao que valida se o aluno ja obteve aprovacao em uma dada turma,
    * ecenssial para validacao da regra de negocio RN05, RN09;
    * Casos de Uso: UC02.06; UC02.00;
    * @param: $siglaDisciplina -> Seleciona a Turma;
    * @param: $matriculaAluno -> Filtra para o aluno;
    * @param: $situacaoInscricao -> Seleciona o tipo de verificacao;
    * @return: False -> Nao houve aprovacao para a disciplina;
    * @return: True -> Houve aprovacao para a disciplina;
    */
    public function verificaInscricaoAlunoTurmas($siglaDisciplina,$matriculaAluno,$situacaoInscricao) {
        $con = BD::conectar();

        $query =sprintf("select i.* from Inscricao as i, Turma as t ".
                "where i.idTurma=t.idTurma and ".
                "i.matriculaAluno= '%s' and t.siglaDisciplina = '%s' ".
                "and i.situacaoInscricao in ('%s')",$matriculaAluno,$siglaDisciplina,$situacaoInscricao);

        $resObj = mysql_query($query,$con);
       
        if(mysql_num_rows($resObj) > 0) 
        {
            return true; //"O aluno ja obteve aprovacao para essa disciplina;
        }
        else 
        {
            return false; //"O aluno nao obteve aprovacao para essa disciplina";

        }
    }

    /**
     * Indica se uma matricula já solicitou inscrição em uma determinada turma
     * Casos de Uso: UC02.06.00
     * @param <type> $idTurma
     * @param <type> $matriculaAluno
     * @return boolean
     */
    public static function alunoJaRequereuInscricaoTurma($idTurma,$matriculaAluno) {
        $con = BD::conectar();
        $query = sprintf("select i.* from Inscricao as i ".
                "where i.idTurma = %d and i.matriculaAluno= '%s' and i.situacaoInscricao <> 'EXC' ",
                $idTurma,
                mysql_real_escape_string($matriculaAluno) );
        $resObj = mysql_query($query,$con);
        if(mysql_num_rows($resObj) > 0) {
            return true; // O aluno ja solicitou inscricao para essa turma
        }
        return false; // O aluno nao solicitou inscricao para essa turma
    }

    /* Funcao que obt?m a turma que est? na tabela inscri??o quando h? colisao de horarios
     * compoe a RN08
     * Casos de Uso: UC02.06.00
     * @return: idTurma, idTempoSemanal;
     * @return explication: Retorna o idTurma da turma que j? existe no BD e
     * os hor?rios que colide;
    *ALTERAÇÃO: UTILIZAR QUANDO A TURMA TIVER DEFERIDO TBMM
    */
    //RETORNAR ID TURMA E TEMPO
    public function obterTurmaComConflitoHorario($idTurma,$matriculaAluno,$situacaoInscricao,$idPeriodoLetivo) {
       $turmas=$this->obterTurmasInscricoesAluno($matriculaAluno, $situacaoInscricao,$idPeriodoLetivo);
       $con = BD::conectar();
       $collection=array(array(),array());
       $j=0;
        foreach ($turmas as $turmaSolicitada){
           $query =sprintf("select idturma,idTempoSemanal from Aloca ".
               "where idTurma=%d and ".
               "idTempoSemanal in (select idTempoSemanal from Inscricao i ".
               "inner join Aloca a ".
               "on i.idTurma = a.idTurma ".
               "where a.idTurma= %d and ".
               "i.situacaoInscricao = '%s' and ".
               "i.matriculaAluno='%s')",$idTurma,$turmaSolicitada->getIdTurma(),$situacaoInscricao,$matriculaAluno);
               
            $result = mysql_query($query,$con);

            while( $resTurmasSolicitadas = mysql_fetch_array($result) ){
                 if($turmaSolicitada->getIdTurma()!=$idTurma){ //ADICIONADO PARA EVITAR AUTO CONFLITO
                   $collection[$j]["turma"] = $turmaSolicitada->getIdTurma();
                   $collection[$j]["tempo"] = $resTurmasSolicitadas['idTempoSemanal'];
                   $j++;
                 }
                }             
        }
        return $collection;
    }

    /* Funcao que obtem turmas inscritas pelo aluno no periodo letivo atual
    * compoe a RN08
    * Casos de Uso: UC02.06.00
    * @return: turma -> objeto
    */
    /*ALTERAÇÃO
     * FOI NECESSARIO ALTERAR O NOME DO METODO DE obterTurmasInscricoes PARA
     * obterTurmasInscricoesAluno, PARA QUE ASSIM FIQUE MAIS CLARO QUE ENVOLVE UM
     * PARAMETRO DE ALUNO
     */
    public static function obterTurmasInscricoesAluno($matriculaAluno,$situacaoInscricao,$idPeriodoLetivo) {
       $collection = array();
       $turma;
       $con = BD::conectar();
        $query=sprintf("select t.idTurma ".
                "from Turma as t ".
                "inner join Inscricao i ".
                "on i.idTurma = t.idTurma and ".
                "i.situacaoInscricao = '%s' and ".
                "i.matriculaAluno='%s' and ".
                "t.idPeriodoLetivo = %d ", $situacaoInscricao,$matriculaAluno,$idPeriodoLetivo);
        $result=mysql_query($query,$con);
        
        while( $resTurmasInscricoesSolicitadas = mysql_fetch_array($result) ) {
            $turma = Turma::getTurmaById($resTurmasInscricoesSolicitadas['idTurma']);
            $collection[] = $turma;
        }
        return $collection;
    }

    /* Funcao que obtem turmas que o aluno reprovou
     * compoe a RN10, RN11
     * Casos de Uso: UC02.06.00
     * @param: $situacaoInscricao -> Filtra o tipo de reprovacao
     * @param: $periodoLetivo -> Verifica em qual periodo
     */
    public function obterTurmasQueAlunoReprovou($matriculaAluno,$siglaDisciplina,$situacaoInscricao,$periodoLetivo) {
       $con = BD::conectar();
        $query=sprintf("select i.*  ".
                "from Inscricao as i, Turma as t ".
                "where i.idTurma=t.idTurma and ".
                "i.idTurma = t.idTurma and ".
                "i.matriculaAluno= '%s' and t.siglaDisciplina = '%s' and ".
                "i.situacaoInscricao in (%s) and ".
                "t.idperiodoLetivo in (%d) ", $matriculaAluno,$siglaDisciplina,$situacaoInscricao,$periodoLetivo);
        $result=mysql_query($query,$con);

               
        if( mysql_num_rows($result) > 0) 
        {
            return true; //"O aluno reprovou por falta na disciplina no periodo passado;
        }
        else 
        {
            return false; //"O aluno nao reprovou por falta na disciplina no periodo passado;
        }
    }

     /* FUNCAO QUE OBTEM AS INSCRICOES DOS ALUNOS EM UMA DADA TURMA, EM UM DADO ESTADO
     * Casos de Uso: UC02.01.00
     * @return: inscricao -> objeto
     */
    public static function obterInscricoesAlunos($idTurma,$situacaoInscricao) {
        $collection = array();
        $con = BD::conectar();

        $query=sprintf("select i.* ".
                "from Inscricao i, Turma t ".
                "where i.idTurma = t.idTurma and ".
                "i.idTurma = %d and ".
                "i.situacaoInscricao in (%s)",
               $idTurma,
               $situacaoInscricao);

        $result=mysql_query($query,$con);

        while( $resTurmasInscricoesDeferidas = mysql_fetch_array($result) ) {
            $inscricao = new Inscricao();
            $inscricao-> setIdTurma($resTurmasInscricoesDeferidas['idTurma']);
            $inscricao-> setMatriculaAluno($resTurmasInscricoesDeferidas['matriculaAluno']);
            $inscricao-> setSituacaoInscricao($resTurmasInscricoesDeferidas['situacaoInscricao']);
            $inscricao-> setDataInscricao($resTurmasInscricoesDeferidas['dataInscricao']);
            $inscricao-> setMediaFinal($resTurmasInscricoesDeferidas['mediaFinal']);
            $inscricao-> setTotalFaltas($resTurmasInscricoesDeferidas['totalFaltas']);
            $inscricao-> setParecerInscricao($resTurmasInscricoesDeferidas['parecerInscricao']);
            $collection[] = $inscricao;
        }
        return $collection;
    }

     /*
     * Casos de Uso: UC02.01.00
     * @return: coleção de objetos de Inscricao
     */
    public static function obterInscricoesAlunosMesmoTurnoDaOferta($idTurma,$situacaoInscricao) {
        $collection = array();
        $con = BD::conectar();

        $query=sprintf("select i.*
                from Inscricao i inner join Turma t on i.idTurma = t.idTurma
                    inner join MatriculaAluno ma on i.matriculaAluno = ma.matriculaAluno and
                        t.turno = ma.turnoIngresso
                where i.idTurma = %d and
                i.situacaoInscricao in (%s)",
               $idTurma,
               $situacaoInscricao);

        $result=mysql_query($query,$con);

        while( $resTurmasInscricoesDeferidas = mysql_fetch_array($result) ) {
            $inscricao = new Inscricao();
            $inscricao-> setIdTurma($resTurmasInscricoesDeferidas['idTurma']);
            $inscricao-> setMatriculaAluno($resTurmasInscricoesDeferidas['matriculaAluno']);
            $inscricao-> setSituacaoInscricao($resTurmasInscricoesDeferidas['situacaoInscricao']);
            $inscricao-> setDataInscricao($resTurmasInscricoesDeferidas['dataInscricao']);
            $inscricao-> setMediaFinal($resTurmasInscricoesDeferidas['mediaFinal']);
            $inscricao-> setTotalFaltas($resTurmasInscricoesDeferidas['totalFaltas']);
            $inscricao-> setParecerInscricao($resTurmasInscricoesDeferidas['parecerInscricao']);
            $collection[] = $inscricao;
        }
        return $collection;
    }

     /*
     * Casos de Uso: UC02.01.00
     * @return: coleção de objetos de Inscricao
     */
    public static function obterInscricoesAlunosTurnoDiferenteDaOferta($idTurma,$situacaoInscricao) {
        $collection = array();
        $con = BD::conectar();

        $query=sprintf("select i.*
                from Inscricao i inner join Turma t on i.idTurma = t.idTurma
                    inner join MatriculaAluno ma on i.matriculaAluno = ma.matriculaAluno and
                        t.turno <> ma.turnoIngresso
                where i.idTurma = %d and
                i.situacaoInscricao in (%s)",
               $idTurma,
               $situacaoInscricao);

        $result=mysql_query($query,$con);

        while( $resTurmasInscricoesDeferidas = mysql_fetch_array($result) ) {
            $inscricao = new Inscricao();
            $inscricao-> setIdTurma($resTurmasInscricoesDeferidas['idTurma']);
            $inscricao-> setMatriculaAluno($resTurmasInscricoesDeferidas['matriculaAluno']);
            $inscricao-> setSituacaoInscricao($resTurmasInscricoesDeferidas['situacaoInscricao']);
            $inscricao-> setDataInscricao($resTurmasInscricoesDeferidas['dataInscricao']);
            $inscricao-> setMediaFinal($resTurmasInscricoesDeferidas['mediaFinal']);
            $inscricao-> setTotalFaltas($resTurmasInscricoesDeferidas['totalFaltas']);
            $inscricao-> setParecerInscricao($resTurmasInscricoesDeferidas['parecerInscricao']);
            $collection[] = $inscricao;
        }
        return $collection;
    }

    /* FUNCAO QUE CARREGA UMA INSCRICÃO DE UM ALUNO EM UMA DADA TURMA, EM UM DADO ESTADO
     *    Casos de Uso:
     *        UC02.01.00
     *        UC01.08.01 - EDITAR NOTAS
     *
     *    @Observação: Se você pretende obter uma insrição, utilize o método getInscricao
     *
     * @param: idTurma -> IdTurma que quer recuperar a inscricao
     * @param: matriculaAluno -> Matricula do Aluno que se quer carregar as informacoes
     * @return: void
     */
    public function carregarInscricao($idTurma,$matriculaAluno) {
       $con = BD::conectar();

       $query=sprintf("select i.* ".
                "from Inscricao i ".
                "where i.idTurma = %d and ".
                "i.matriculaAluno = '%s' ", mysql_real_escape_string($idTurma),  mysql_real_escape_string($matriculaAluno));
        $result=mysql_query($query,$con);

        while( $resInscricao = mysql_fetch_array($result) ) {
            $this->setIdTurma($resInscricao['idTurma']);
            $this->setMatriculaAluno($resInscricao['matriculaAluno']);
            $this->setSituacaoInscricao($resInscricao['situacaoInscricao']);
            $this->setDataInscricao($resInscricao['dataInscricao']);
            $this->setMediaFinal($resInscricao['mediaFinal']);
            $this->setTotalFaltas($resInscricao['totalFaltas']);
            $this->setParecerInscricao($resInscricao['parecerInscricao']);
        }
    }

  /* RETORNA UMA INSCRICAO DADA UMA TURMA E O NUMERO DE MATRÍCULA DE UM ALUNO
   *    Casos de Uso:
   *        UC01.08.01 - EDITAR NOTAS
   *    @param: idTurma -> IdTurma da turma com incricoes
   *    @param: numMatriculaAluno -> Matricula do Aluno da incrição
   *    @result: new Inscricao
   */
    public static function getInscricao($idTurma, $numMatriculaAluno){
        $umaInscricao = new Inscricao();
        $umaInscricao->carregarInscricao($idTurma, $numMatriculaAluno);
        return $umaInscricao;
    }

    /**
     * Aluno já está inscrito no mesmo componente no mesmo período letivo
     * @param MatriculaAluno $matriculaAluno
     * @param Turma $turma
     * @return boolean
     */
    public static function alunoJaInscritoMesmoComponente( MatriculaAluno $matriculaAluno, Turma $turma ) {

        $con = BD::conectar();
        $query = sprintf("select * from Inscricao I inner join 
                Turma T on I.idTurma = T.idTurma 
                where I.matriculaAluno='%s' and T.siglaCurso='%s' and 
                T.idMatriz=%d and T.siglaDisciplina='%s' 
                and T.idPeriodoLetivo = %d
                and I.situacaoInscricao='%s'",
                $matriculaAluno->getNumMatriculaAluno(), 
                $turma->getSiglaCurso(),
                $turma->getIdMatriz(), 
                $turma->getSiglaDisciplina(),
                $turma->getIdPeriodoLetivo(),
                Inscricao::CUR );

        $resObj = mysql_query( $query, $con);
        return mysql_num_rows($resObj) > 0;
    }

    /* FUNCAO QUE OBTEM HISTORICO DO ALUNO EM UMA DISCIPLINA
    * Casos de Uso: UC02.01.00
    * @return: colecao de :inscricao
     * 
    */

    public static function obterHistoricoDisciplina($siglaDisciplina, $matriculaAluno) {
       $collection = array();
       $con = BD::conectar();

       $query=sprintf("select i.* ".
                "from Inscricao i, Turma t ".
                "where i.idturma = t.idturma and ".
                "t.siglaDisciplina = '%s' and ".
                "i.matriculaAluno = %d and ".
                "i.situacaoInscricao in ('NEG', 'RM', 'RF') ".
                "order by t.idPeriodoLetivo asc", $siglaDisciplina, $matriculaAluno);
        $result=mysql_query($query,$con);
        
        while( $resTurmasInscricoesDeferidas = mysql_fetch_array($result) ) {
            $inscricao = new Inscricao();
            $inscricao-> setIdTurma($resTurmasInscricoesDeferidas['idTurma']);
            $inscricao-> setMatriculaAluno($resTurmasInscricoesDeferidas['matriculaAluno']);
            $inscricao-> setSituacaoInscricao($resTurmasInscricoesDeferidas['situacaoInscricao']);
            $inscricao-> setDataInscricao($resTurmasInscricoesDeferidas['dataInscricao']);
            $inscricao-> setMediaFinal($resTurmasInscricoesDeferidas['mediaFinal']);
            $inscricao-> setTotalFaltas($resTurmasInscricoesDeferidas['totalFaltas']);
            $inscricao-> setParecerInscricao($resTurmasInscricoesDeferidas['parecerInscricao']);
            $collection[] = $inscricao;
        }
        return $collection;
    }

     /***
     * Atualiza um registro existente: inscricao
     *  Casos de Uso: UC02.01.00
     * @result void
      * TODO MB retirar esse método
     **/
    public function atualizarInscricao($UC,$situacaoInscricao,$parecerInscricao, $matriculaAluno, $idTurma) {

         // conexao com o BD
         $con = BD::conectar();

          $query=sprintf("update Inscricao set situacaoInscricao = '%s', ".
                         "parecerInscricao =  '%s' ".
                         "where matriculaAluno = '%s' " .
                         "and idTurma =%d ", $situacaoInscricao,$parecerInscricao, $matriculaAluno, $idTurma);
        $result=mysql_query($query,$con);
        
        //PEGANDO O LOG
         global $MANTER_SITUACAO_INSCRICOES_TURMAS;
         $UC020100 = $MANTER_SITUACAO_INSCRICOES_TURMAS;
         global $DEFERIR_SOLICATACAO_INSCRICAO_JUSTIFICATIVA;
         $UC020101=$DEFERIR_SOLICATACAO_INSCRICAO_JUSTIFICATIVA;
         global $INDEFERIR_SOLICATACAO_INSCRICAO;
         $UC020102=$INDEFERIR_SOLICATACAO_INSCRICAO;
         global $CANCELAR_SOLICATAÇÃO_INSCRICAO;
         $UC020103=$CANCELAR_SOLICATAÇÃO_INSCRICAO;

         //CASO SEJA O CASO DE USO DE MANTER SITUACAO
         // PODE HAVER MAIS DE UM CASO DE USO UTILIZANDO O LOG
         if($UC==$UC020100 || $UC==$UC020101 || $UC==$UC020102 || $UC==$UC020103){

             //Obtem os dados para o log
             $mAluno = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
             $aluno = Aluno::getAlunoByIdPessoa($mAluno->getIdPessoa());
             $tAluno = Turma::getTurmaById($idTurma);
             $periodoLetivo = PeriodoLetivo::obterPeriodoLetivo( $tAluno->getIdPeriodoLetivo() );
             $classeCurso = Curso::obterCurso( $periodoLetivo->getSiglaCurso() );

             //Gera a palavra de acordo com a acao, evitando repeticao de codigo
             if($UC==$UC020100)
                 $descAcao = 'deferida';
             elseif($UC==$UC020101)
                 $descAcao = 'deferida com justificativa';
             elseif($UC==$UC020102)
                 $descAcao = 'indeferida';
             elseif($UC==$UC020103)
                 $descAcao = 'cancelada';
             
            $descricao="Foi ".$descAcao." a solicitação de inscrição do(a) aluno(a) ".$aluno->getNome().
                    ", matricula ".$mAluno->getMatriculaAluno().
                    " na turma ". $tAluno->getSiglaDisciplina() . ", grade ". $tAluno->getGradeHorario().", ".
                    "turno " . $tAluno->getTurno() . ", " .
                    "do curso ".$classeCurso->getSiglaCurso()." (".$classeCurso->getNomeCurso().").";
            if($UC==$UC020101 || $UC==$UC020102){
                $descricao.=" Com a seguinte justificativa: ".$parecerInscricao;
            }

            $usuario = $_SESSION["usuario"];

            $usuario->incluirLog($UC,$descricao);
         }
    }

    /**
     * Registrar a inscrição como deferida
     * @param string $parecer 
     * @throws Exception
     */
    public function deferirInscricao( $parecer) 
    {
        $con = BD::conectar();
        $query = sprintf("update Inscricao set situacaoInscricao = '%s', ".
                         "parecerInscricao =  '%s' where matriculaAluno = '%s' " .
                         "and idTurma =%d", 
                Inscricao::DEF, mysql_real_escape_string( $parecer),
                $this->matriculaAluno, $this->idTurma );
        $result = mysql_query($query,$con);
        if( !$result) 
        {
            throw new Exception( "Erro ao deferir automaticamente inscrição.");
        }
    }
    
    /*
     *
     */
    public function possuiDeferimentoMesmaDisciplina() {
        $turma = Turma::getTurmaById($this->idTurma);
        $con = BD::conectar();
        $query=sprintf("select count(*) from Inscricao I inner join Turma T
            on I.idTurma = T.idTurma and I.matriculaAluno='%s' and
                T.siglaCurso='%s' and
                T.idMatriz=%d and
                T.siglaDisciplina='%s' and
                I.situacaoInscricao in ('DEF', 'CUR','AP')
                and T.idPeriodoLetivo = %d",
                $this->matriculaAluno,
                $turma->getSiglaCurso(),
                $turma->getIdMatriz(),
                $turma->getSiglaDisciplina(),
                $turma->getIdPeriodoLetivo() );
        $result=mysql_query($query, $con);
        $cont=mysql_result($result, 0, 0);
        if($cont!=0) return true;
        return false;
    }


// METODO NECESSARIO PARA VERIFICAR SE EXISTE SOLICITACOES COM SITUACAO REQUERIDA
 /*Funcao que obtem uma lista de alunos solicitantes
     * Casos de Uso: UC02.03.00
     * @param: $siglaCurso, $idPeriodoLetivo
     * @return verdadeiro se existir e falso se nao
*/
    function verificaSolicitacaoRequerida($siglaCurso, $idPeriodoLetivo){
            $encontrou = false;
            $con = BD::conectar();

            $query = sprintf("select DISTINCT (i.matriculaAluno), nome ".
                                "from Inscricao i ".
                                "inner join Turma t ".
                                "on t.idTurma = i.idTurma ".
                                "inner join MatriculaAluno m ".
                                "on i.matriculaAluno = m.matriculaAluno ".
                                "inner join Pessoa p ".
                                "on m.idPessoa = p.idPessoa ".
                                "where t.siglaCurso= '%s' ".
                                "and t.idPeriodoLetivo= %d ".
                                "and i.situacaoInscricao = 'REQ' ".
                                "order by nome",$siglaCurso,$idPeriodoLetivo);
            
            $sqlSolicitacao = mysql_query($query,$con);
            if(mysql_num_rows($sqlSolicitacao) > 0){
            	$encontrou = true;
            }
            return $encontrou;
    }
    
    /**
     * Cria uma representação em String do apontamento do dia letivo desta
     * Inscricao. P.ex.: [ff..] faltou aos dois primeiros tempos de aula
     * e veio nos dois finais.
     * @param DateTime $data
     * @return string
     */
    public function obterResumoApontamentoDiaLetivo( DiaLetivoTurma $diaLetivoTurma ) {
        $data = $diaLetivoTurma->getData();
        $con = BD::conectar();
        $query = sprintf("select situacao from ApontaTempoAula ATA 
            where idTurma = %d and 
            matriculaAluno = '%s' and 
            data = '%s' 
            order by idTempoSemanal",
                $this->idTurma,
                $this->matriculaAluno,
                $data->format("Y-m-d") );
        $result = mysql_query($query, $con);
        if(mysql_num_rows($result) == 0) {
            return str_repeat("-", $diaLetivoTurma->getQtdeTempos());
        } else {
            $string = "";
            while( $reg = mysql_fetch_array($result)) {
                switch( $reg["situacao"] ) {
                    case "P":
                        $string .= "P";
                        break;
                    case "F":
                        $string .= "F";
                        break;
                    default:
                        $string .= "?";
                }
            }
            return $string;
        }
    }
    
    /**
     * Registra um apontamento de faltas do aluno numa turma, retornando se houve
     * alteração sobre a comunicação anterior
     * @param DiaLetivoTurma $diaLetivoTurma
     * @param type $resumo
     * @return boolean indica se foi modificado ou não
     */
    public function atualizarResumoApontamentoDiaLetivo( DiaLetivoTurma $diaLetivoTurma, $resumo )
    {
        $con = BD::conectar();
        $data = $diaLetivoTurma->getData();
        $query = sprintf( "select resumo from ResumoApontamentoDiaLetivo 
                where idTurma = %d and 
            matriculaAluno = '%s' and 
            data = '%s'", $this->idTurma,
                $this->matriculaAluno,
                $data->format("Y-m-d") );
        $result = mysql_query($query, $con);
        if( mysql_num_rows($result) == 0)
        { 
            // se não existe, insere e retorna true
            $cmdInsere = sprintf("insert into ResumoApontamentoDiaLetivo 
                (idTurma, matriculaAluno, data, resumo) values 
                (%d, '%s', '%s', '%s')", 
                    $this->idTurma,
                $this->matriculaAluno,
                $data->format("Y-m-d"),
                $resumo );
            $resultInsere = mysql_query($cmdInsere, $con);
            if( !$resultInsere ) 
            {
                error_log( "Erro banco de dados: " . mysql_error());
                throw new Exception("Erro ao atualizar presença.");
            }
            return true;
        }
        else
        {
            $resumoAtual = mysql_result($result, 0, 0);
            if( $resumoAtual !== $resumo)
            {
                $cmdAtualiza = sprintf("update ResumoApontamentoDiaLetivo 
                    set resumo='%s' 
                    where idTurma = %d and 
                    matriculaAluno = '%s' and 
                    data = '%s'", 
                        $resumo,
                        $this->idTurma,
                        $this->matriculaAluno,
                        $data->format("Y-m-d") );
                $resultAtualiza = mysql_query($cmdAtualiza, $con);
                if( !$resultAtualiza ) 
                {
                    throw new Exception("Erro ao atualizar presença.");
                }
                return true;
            }
        }       
        return false;
    }
  
    /**
     * Faltas lançadas na pauta eletrônica. Pode diferer da quantidade de
     * faltas que vai para o computo final do diário, portanto diferente
     * de total de faltas.
     * @return int
     */
    public function obterFaltasLancadas() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from ApontaTempoAula ATA 
            where idTurma = %d and
            matriculaAluno = '%s' and 
            situacao = 'F'",
                $this->idTurma,
                $this->matriculaAluno);
        $result = mysql_query($query, $con);
        return mysql_result($result, 0, 0);
    }

    /**
     * Indica se é aluno reclamado pelo professor
     * @return boolean
     */
    public function isReclamadoPeloProfessor() {
        return $this->situacaoInscricao == Inscricao::REQ &&
                $this->parecerInscricao == Inscricao::RECLAMADO_PELO_PROFESSOR;
    }
    
    /**
     * Retorna uma coleção de ItemCriterioAvaliacaoInscricaoNota
     */
    public function obterItensCriterioAvaliacaoInscricaoNota() {
        return ItemCriterioAvaliacaoInscricaoNota::obterItensCriterioAvaliacaoInscricaoNota($this);
    }

    /**
     * Lança a nota do aluno de uma turma para um item de critério de avaliação
     * ainda não liberado.
     * @param type $itemCriterioAvaliacao
     * @param Decimal $nota
     */
    public function lancarNota(ItemCriterioAvaliacao $itemCriterioAvaliacao, $nota, $comentario) {
        $con = BD::conectar();
        $notaAntes = sprintf("select nota from ItemCriterioAvaliacaoInscricaoNota 
            where idItemCriterioAvaliacao = %d and 
            idTurma = %d and 
            matriculaAluno = '%s'",
                $itemCriterioAvaliacao->getIdItemCriterioAvaliacao(),
                $this->idTurma,
                $this->matriculaAluno );
        $resultNotaAntes = mysql_query($notaAntes, $con);
        $mudouNota = false;
        if(mysql_num_rows($resultNotaAntes) != 0 ) {
            $notaAntes = mysql_result($resultNotaAntes, 0, 0);
            if($nota != $notaAntes) {
                $mudouNota = true;
            }
        }
        $cmd = sprintf("insert into ItemCriterioAvaliacaoInscricaoNota 
            (idItemCriterioAvaliacao, idTurma, matriculaAluno, nota, comentario) values
            (%d, %d, '%s', %s, '%s') on duplicate key update nota=%s, comentario='%s'" .
                ( $mudouNota ? ", dataNotificacao = NULL" : "") ,
                $itemCriterioAvaliacao->getIdItemCriterioAvaliacao(),
                $this->idTurma,
                $this->matriculaAluno,
                Util::tratarNumeroNullSQL($nota),
                mysql_real_escape_string($comentario),
                Util::tratarNumeroNullSQL($nota),
                mysql_real_escape_string($comentario) );           
        $result = mysql_query($cmd, $con);
        if( !$result ) {
            throw new Exception("Erro ao lançar nota.");
        }
    }

    /**
     * 
     * @return type
     */
    public function obterMediaFinalLancadaEmPauta() {
        $itensCriterioAvaliacaoInscricaoNota = $this->obterItensCriterioAvaliacaoInscricaoNota();
        foreach($itensCriterioAvaliacaoInscricaoNota as $itemCriterioAvaliacaoInscricaoNota) {
            if( $itemCriterioAvaliacaoInscricaoNota->getItemCriterioAvaliacao()->isNotaFinal() ) {
                $itemCriterioAvaliacao = $itemCriterioAvaliacaoInscricaoNota->getItemCriterioAvaliacao();
                $resultado = $itemCriterioAvaliacao->exibir($this);
                return $resultado;
            }
        }
        throw new Exception("Não foi encontrado item de critério de avalição FINAL.");
    }

    public function obterSituacaoFinalLancadaEmPauta() {
        $itensCriterioAvaliacaoInscricaoNota = $this->obterItensCriterioAvaliacaoInscricaoNota();
        foreach($itensCriterioAvaliacaoInscricaoNota as $itemCriterioAvaliacaoInscricaoNota) {
            if( $itemCriterioAvaliacaoInscricaoNota->getItemCriterioAvaliacao()->isSituacaoFinal() ) {
                $itemCriterioAvaliacao = $itemCriterioAvaliacaoInscricaoNota->getItemCriterioAvaliacao();
                $resultado = $itemCriterioAvaliacao->exibir($this);
                return $resultado;
            }
        }
        throw new Exception("Não foi encontrado item de critério de avalição SITUAÇÃO.");
    }
    
    public function lancarSituacaoFinal($con = null) {
        if( $con == null ) $con = BD::conectar ();
        $totalFaltas = $this->obterFaltasLancadas();
        $mediaFinal = str_replace(",",".",$this->obterMediaFinalLancadaEmPauta());
        $situacao = $this->obterSituacaoFinalLancadaEmPauta();
        $cmd = sprintf("" .
                        " update" .
                        "     Inscricao " .
                        " set".
                        "     `situacaoInscricao` = '%s', " . // #1
                        "     `mediaFinal` = %s, " .          // #2
                        "     `totalFaltas` = %d " .         // #3
                        " where " .
                        "     `idTurma` = %d".                // #4
                        "     and `matriculaAluno` = '%s'",   // #5

                        $situacao,    // #1
                        $mediaFinal,                         // #2
                        $totalFaltas,                        // #3
                        $this->idTurma,          // #4
                        $this->matriculaAluno); // #5        
        $result = mysql_query($cmd, $con);
        if( !$result ) {
            throw new Exception("Erro ao lançar a nota!");
        }
    }

    /**
     * Indica se aluno tem ou não alguma restrição
     * @return boolean 
     */
    public function isTemRestricoes() 
    {
        $idTurma = $this->idTurma;
        $numMatriculaAluno = $this->matriculaAluno;
        $rn = new funcoesRN();
        if( $rn->RN08( $idTurma, $numMatriculaAluno) )
        {
            return true;
        }
        $col = $rn->RN09($idTurma, $numMatriculaAluno);
        
        if( !empty( $col) ) 
        {                
            return true;
        }
        if( $rn->RN10( $numMatriculaAluno, $idTurma) ) 
        {                
            return true;
        }
        if( $rn->RN11( $numMatriculaAluno, $idTurma) >= 3 ) 
        {                
            return true;
        }
        if( $rn->RN12( $numMatriculaAluno) ) 
        {                
            return true;
        }
        if( $rn->RN22( $numMatriculaAluno, $idTurma) ) 
        {                
            return true;
        }
        return false;
    }
}