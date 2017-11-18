<?php
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/TipoDocumento.php";
require_once "$BASE_DIR/classes/MatrizCurricular.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/FormaIngresso.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/to/QuitacaoComponenteCurricularTO.php";

class MatriculaAluno 
{
    
    const CURSANDO = "CURSANDO";
    const TRANCADO = "TRANCADO";
    const EVADIDO = "EVADIDO";
    const CONCLUIDO = "CONCLUÍDO";
    const DESISTENTE = "DESISTENTE";
    const DESLIGADO = "DESLIGADO";

    private $matriculaAluno;
    private $idPessoa;
    private $dataMatricula;
    private $dataConclusao;
    private $siglaCurso;
    private $idMatriz;
    private $idPeriodoLetivo;
    private $periodoLetivo;
    private $turnoIngresso;
    private $concursoPontos;
    private $concursoClassificacao;
    private $situacaoMatricula;
    private $idFormaIngresso;
   
    // TODO: que campo é esse?
    private $descricao;
    
    private $dataInicioVigencia;
    
    private $siglaPeriodoLetivo;

    private $nomeCurso;

    private $aluno;
    private $matrizCurricular; // objeto da classe MatrizCurricular associado
    private $formaIngresso;

    /**
     * Retorna o primeiro número de matrícula, iniciando por 1 e
     * formatado com 15 dígitos, disponível
     * @return String matrícula numérica com zeros a esquerda
     */
    public static function gerarMatriculaProvisoria() 
    {
        $con=BD::conectar();
        for($cont=1;$cont<1000;$cont++) {
            $matStr = str_pad($cont, 15,"0",STR_PAD_LEFT);
            $query=sprintf("select count(*) from MatriculaAluno 
                where matriculaAluno='%s'",
            mysql_real_escape_string($matStr));
            $result = mysql_query($query,$con);
            $count = mysql_result($result, 0, 0);
            if($count==0) return $matStr;
        }
    }
    
    /**
     * Retorna a qtde. de horas aula cumpridas pelo aluno
     * @return <int> qtde. de horas/aula cumpridas pelo aluno
     */
    public function obterCargaCumprida() 
    {
        $matrizCurricular = $this->getMatrizCurricular();
        $componentes = $matrizCurricular->obterComponentesCurriculares();
        $totalCH = 0;
        foreach($componentes as $componente) {
            $quitacaoComponenteCurricularTO = $componente->obterQuitacao($this);
            if( $quitacaoComponenteCurricularTO != null ) {
                $totalCH += $componente->getCargaHoraria();

            }
        }
        return $totalCH;
    }

    /**
     * Calcula o período relativo que o aluno se encontra
     * considerando a carga horária cumprida.
     * @return <int> período relativo
     */
    public function getPeriodoReferencia() 
    {
        $matrizCurricular = $this->getMatrizCurricular();
        $colCargaPorPeriodo = $matrizCurricular->obterCargaPorPeriodo();
        $cargaCumprida = $this->obterCargaCumprida();
        $acumTotalHoras = 0;
        foreach($colCargaPorPeriodo as $periodo=>$totalHoras) {
            $acumTotalHoras += $totalHoras;
            if($cargaCumprida <  $acumTotalHoras) return $periodo;
        }
        return $matrizCurricular->obterQuantidadePeriodos();
    }

    /**
     * Tornar matrícula evadida, colocando-a como EVADIDO.
     * @param String $texto
     * @param Connection $con
     */
    public function evadirMatricula($texto, $con) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set situacaoMatricula='EVADIDO'
            where matriculaAluno='%s'",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Não foi possível tornar EVADIDO a matrícula.");
        }
        $sqlHist=sprintf("insert into SituacaoMatriculaHistorico
            (matriculaAluno,situacaoMatricula,texto)
            values ('%s','EVADIDO','%s')",
            mysql_escape_string($this->getMatriculaAluno()),
            mysql_escape_string($texto));
        $result = mysql_query($sqlHist,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível tornar EVADIDO a matrícula.");
        }
    }

    /**
     * Renova matrícula, colocando-a como CURSANDO.
     * @param String $texto
     * @param Connection $con
     */
    public function renovarMatricula($texto,$con) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set situacaoMatricula='CURSANDO'
            where matriculaAluno='%s'",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Não foi possível renovar (CURSANDO) matrícula.");
        }
        $sqlHist=sprintf("insert into SituacaoMatriculaHistorico
            (matriculaAluno,situacaoMatricula,texto)
            values ('%s','CURSANDO','%s')",
            mysql_escape_string($this->getMatriculaAluno()),
            mysql_escape_string($texto));
        $result = mysql_query($sqlHist,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível renovar (CURSANDO) matrícula.");
        }
    }

    /**
     * Indica se existe inscrição da matricula em uma turma do período letivo
     * indicado
     * @param PeriodoLetivo $idPeriodoLetivo
     * @param Connection $con
     * @return Boolean
     */
    public function temInscricaoEmTurma(PeriodoLetivo $pl,$con=null) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("select count(*) as qtde from Inscricao i
            inner join Turma t on i.idTurma=t.idTurma
            where i.matriculaAluno='%s' and
            t.idPeriodoLetivo=%d and
            i.situacaoInscricao not in ('REQ','NEG')",
        mysql_escape_string($this->getMatriculaAluno()),
        mysql_escape_string($pl->getIdPeriodoLetivo()));
        $result=mysql_query($query);
        $linha=mysql_fetch_array($result);
        return $linha["qtde"] > 0 ? true : false;
    }

    /**
     * Verifica se a matrícula entregou todos os documentos exigidos
     * pelo curso
     * @return boolean
     */
    public function temPendenciaDocsEntregues() 
    {
        $con=BD::conectar();
        $query=sprintf("select count(*) as qtde from CursoTipoDocumento ctd 
            where not exists (select * from ExigenciaDocumento ed
                where ctd.siglaCurso=ed.siglaCurso and
                ctd.idTipoDocumento=ed.idTipoDocumento and
                ed.matriculaAluno='%s')
            and ctd.siglaCurso='%s'",
        mysql_escape_string($this->getMatriculaAluno()),
        mysql_escape_string($this->getSiglaCurso()));
        $result=mysql_query($query);
        $linha=mysql_fetch_array($result);
        return $linha["qtde"] > 0 ? true : false;
    }
    
    /**
     * Obtem a lista de documentos que ainda não foram entregues pelo aluno
     * 
     * @return ArrayObject TipoDocumento
     */
    public function obterTipoDocumentosNaoEntregues() 
    {
        return TipoDocumento::obterTipoDocumentosNaoEntregues($this->matriculaAluno, $this->siglaCurso);
    }

    /**
     * Registra conclusão da matrícula, colocando-a como CONCLUÍDO.
     * @param String $texto
     * @param Connection $con
     */
    public function concluirMatricula($texto,$dataConclusao,$con) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set situacaoMatricula='CONCLUÍDO',
            dataConclusao='%s'
            where matriculaAluno='%s' and 
            situacaoMatricula in ('CURSANDO')",
        mysql_escape_string($dataConclusao),
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Não foi possível concluir matrícula.");
        }
        $sqlHist=sprintf("insert into SituacaoMatriculaHistorico
            (matriculaAluno,situacaoMatricula,texto)
            values ('%s','CONCLUÍDO','%s')",
            mysql_escape_string($this->getMatriculaAluno()),
            mysql_escape_string($texto));
        $result = mysql_query($sqlHist,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível concluir matrícula.");
        }
    }

    /**
     * Registra desligamento da matrícula, colocando-a como DESLIGADO.
     * @param String $texto
     * @param Connection $con
     */
    public function desligarMatricula($texto, $con) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set situacaoMatricula='DESLIGADO'
            where matriculaAluno='%s'",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Não foi possível registrar desligamento da matrícula.");
        }
        $sqlHist=sprintf("insert into SituacaoMatriculaHistorico
            (matriculaAluno,situacaoMatricula,texto)
            values ('%s','DESLIGADO','%s')",
            mysql_escape_string($this->getMatriculaAluno()),
            mysql_escape_string($texto));
        $result = mysql_query($sqlHist,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível registrar desligamento da matrícula.");
        }
    }

    /**
     * Registra desistência da matrícula, colocando-a como DESISTENTE.
     * @param String $texto
     * @param Connection $con
     */
    public function desistirMatricula($texto, $con) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set situacaoMatricula='DESISTENTE'
            where matriculaAluno='%s'",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Não foi possível registrar desistência da matrícula.");
        }
        $sqlHist=sprintf("insert into SituacaoMatriculaHistorico
            (matriculaAluno,situacaoMatricula,texto)
            values ('%s','DESISTENTE','%s')",
            mysql_escape_string($this->getMatriculaAluno()),
            mysql_escape_string($texto));
        $result = mysql_query($sqlHist,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível registrar desistência da matrícula.");
        }
    }

    /**
     * Retorna a quantidade de trancamentos que a matrícula fez.
     * @return integer 
     */
    public function obterQtdeTrancamentos() 
    {
        $con=BD::conectar();
        $query=sprintf("select count(*) as qtde from SituacaoMatriculaHistorico
            where matriculaAluno='%s' and 
            situacaoMatricula='TRANCADO'",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query);
        $linha=mysql_fetch_array($result);
        return $linha["qtde"];
    }

    /**
     * Tranca uma matrícula, colocando-a como TRANCADO.
     * @param String $texto
     * @param Connection $con
     */
    public function trancarMatricula($texto,$con=null) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set situacaoMatricula='TRANCADO'
            where matriculaAluno='%s' and
            situacaoMatricula in ('CURSANDO','TRANCADO','EVADIDO')",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Não foi possível trancar matrícula.");
        }
        $sqlHist=sprintf("insert into SituacaoMatriculaHistorico
            (matriculaAluno,situacaoMatricula,texto)
            values ('%s','TRANCADO','%s')",
            mysql_escape_string($this->getMatriculaAluno()),
            mysql_escape_string($texto));
        $result = mysql_query($sqlHist,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível trancar matrícula.");
        }
    }

    /**
     * Reabre a matrícula colocando-a para CURSANDO
     * @param String $texto
     * @param Connection $con
     */
    public function reativarMatricula($texto,$con=null) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set situacaoMatricula='CURSANDO'
            where matriculaAluno='%s' and
            situacaoMatricula <> 'CURSANDO'",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível reabrir matrícula.");
        }
        $sqlHist=sprintf("insert into SituacaoMatriculaHistorico
            (matriculaAluno,situacaoMatricula,texto)
            values ('%s','CURSANDO','%s')",
            mysql_escape_string($this->getMatriculaAluno()),
            mysql_escape_string($texto));
        $result = mysql_query($sqlHist,$con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Não foi possível reabrir matrícula.");
        }
    }
    
    /**
     * Recupera a lista de histórico de modificação da situação
     * desta matrícula.
     * @return array 
     */
    public function obterListaSituacaoMatriculaHistorico() 
    {
        $con=BD::conectar();
        $query=sprintf("select dataHistorico,situacaoMatricula,texto
            from SituacaoMatriculaHistorico
            where matriculaAluno='%s'
            order by dataHistorico DESC",
        mysql_escape_string($this->getMatriculaAluno()));
        $result=mysql_query($query,$con);
        $listaSitMatHist=array();
        $i=0;
        while( $linha = mysql_fetch_array($result)) {
            $listaSitMatHist[$i]["dataHistorico"]=$linha["dataHistorico"];
            $listaSitMatHist[$i]["situacaoMatricula"]=$linha["situacaoMatricula"];
            $listaSitMatHist[$i]["texto"]=$linha["texto"];
            $i++;
        }
        return $listaSitMatHist;
    }

    /**
     * Obtem a quantidade de matrículas de alunos num curso
     * @param String $siglaCurso
     * @param String $situacaoMatricula (CURSANDO, TRANCADO, ...)
     */
    public static function obterTotalPorSituacao($siglaCurso, $situacaoMatricula) 
    {
        $con=BD::conectar();
        $query = sprintf("select count(*) as total from MatriculaAluno
            where siglaCurso='%s' and situacaoMatricula='%s'",
        mysql_escape_string($siglaCurso),
        mysql_escape_string($situacaoMatricula));
        $result=mysql_query($query,$con);
        $linha=mysql_fetch_array($result);
        return $linha["total"];
    }

   /*
    * Construtor da Classe
    */
    function __construct(Aluno $aluno, MatrizCurricular $matrizCurricular, 
            PeriodoLetivo $periodoLetivo,
            $matriculaAluno,
            $dataMatricula, $dataConclusao, $situacaoMatricula, $turnoIngresso,
            $concursoPontos, $concursoClassificacao, FormaIngresso $formaIngresso) {

        $this->aluno = $aluno;
        $this->matrizCurricular = $matrizCurricular;
        $this->periodoLetivo = $periodoLetivo;
        $this->matriculaAluno = $matriculaAluno;
        $this->dataMatricula = $dataMatricula;
        $this->dataConclusao = $dataConclusao;
        $this->situacaoMatricula = $situacaoMatricula;
        $this->turnoIngresso = $turnoIngresso;
        $this->concursoPontos = $concursoPontos;
        $this->concursoClassificacao = $concursoClassificacao;
        $this->formaIngresso = $formaIngresso;
    }

    /***
     * Pega o valor do campo: matriculaAluno
     *
     * @result matriculaAluno
     * @deprecated
     **/
    public function getMatriculaAluno( ) {
        // retorna o valor de: matriculaAluno
        return $this->matriculaAluno;
    }
    
    public function getNumMatriculaAluno() {
        return $this->matriculaAluno;        
    }

    public function getIdPessoa( ) {
        return $this->aluno->getIdPessoa();
    }

    public function getDataMatricula( ) {
        return $this->dataMatricula;
    }

    public function getDataConclusao() {
        return $this->dataConclusao;
    }

    public function getSiglaCurso( ) 
    {
        return $this->matrizCurricular->getSiglaCurso();
    }

    public function getIdMatriz( ) {
        return $this->matrizCurricular->getIdMatriz();
    }

    /***
     * Retorna o objeto de Matriz Curricular associada a esta
     * matricula de aluno, em comportamento Lazy.
     * @result objeto de MatrizCurricular
     * @author Marcio Belo
     **/
    public function getMatrizCurricular() 
    {
        return $this->matrizCurricular;
    }

    public function getTurnoIngresso( ) 
    {
        return $this->turnoIngresso;
    }


    public function getConcursoPontos( ) 
    {
        return $this->concursoPontos;
    }

    public function getConcursoClassificacao( ) 
    {
        return $this->concursoClassificacao;
    }

    public function getSituacaoMatricula( ) 
    {
        return $this->situacaoMatricula;
    }

    public function getIdFormaIngresso( ) 
    {
        // retorna o valor de: idFormaIngresso
        return $this->formaIngresso->getIdFormaIngresso();
    }
    
    /***
     * Pega o valor do campo: descricao
     *
     * @result descricao
     **/
    public function getDescricao( ) {
        // retorna o valor de: descricao
        return $this->descricao;
    }

    /***
     * Pega o valor do campo: dataInicioVigencia
     *
     * @result dataInicioVigencia
     **/
    public function getDataInicioVigencia( ) {
        // retorna o valor de: dataInicioVigencia
        return $this->dataInicioVigencia;
    }

    /***
     * Pega o valor do campo: siglaPeriodoLetivo
     *
     * @result siglaPeriodoLetivo
     **/
    public function getSiglaPeriodoLetivo( ) {
        // retorna o valor de: siglaPeriodoLetivo
        return $this->siglaPeriodoLetivo;
    }

    function getIdPeriodoLetivo( ) {
        return $this->periodoLetivo->getIdPeriodoLetivo();
    }

    function setMatriculaAluno( $matriculaAluno ) {
        $this->matriculaAluno = $matriculaAluno;
    }

    function setIdPessoa( $idPessoa ) {
        $this->idPessoa = $idPessoa;
    }

    function setDataMatricula( $dataMatricula ) {
        $this->dataMatricula = $dataMatricula;
    }

    function setDataConclusao($dataConclusao) {
        $this->dataConclusao = $dataConclusao;
    }

    function setSiglaCurso( $siglaCurso ) {
        $this->siglaCurso = $siglaCurso;
    }

    function setIdMatriz( $idMatriz ) {
        $this->idMatriz = $idMatriz;
    }

    /***
     * Seta valor para: idPeriodoLetivo
     *
     * @param idPeriodoLetivo
     * @result void
     **/
    function setIdPeriodoLetivo( $idPeriodoLetivo ) {
        // seta o valor de: idPeriodoLetivo
        $this->idPeriodoLetivo = $idPeriodoLetivo;
    }

    /***
     * Seta valor para: turnoIngresso
     *
     * @param turnoIngresso
     * @result void
     **/
    function setTurnoIngresso( $turnoIngresso ) {
        // seta o valor de: turnoIngresso
        $this->turnoIngresso = $turnoIngresso;
    }

    /***
     * Seta valor para: concursoPontos
     *
     * @param concursoPontos
     * @result void
     **/
    function setConcursoPontos( $concursoPontos ) {
        // seta o valor de: concursoPontos
        $this->concursoPontos = $concursoPontos;
    }

    /***
     * Seta valor para: concursoClassificacao
     *
     * @param concursoClassificacao
     * @result void
     **/
    function setConcursoClassificacao( $concursoClassificacao ) {
        // seta o valor de: concursoClassificacao
        $this->concursoClassificacao = $concursoClassificacao;
    }

    /***
     * Seta valor para: situacaoMatricula
     *
     * @param situacaoMatricula
     * @result void
     **/
    function setSituacaoMatricula( $situacaoMatricula ) {
        // seta o valor de: situacaoMatricula
        $this->situacaoMatricula = $situacaoMatricula;
    }

    /***
     * Seta valor para: descricao
     *
     * @param descricao
     * @result void
     **/
    function setDescricao( $descricao ) {
        // seta o valor de: descricao
        $this->descricao = $descricao;
    }
    
    /***
     * Seta valor para: dataInicioVigencia
     *
     * @param dataInicioVigencia
     * @result void
     **/
    function setDataInicioVigencia( $dataInicioVigencia ) {
        // seta o valor de: dataInicioVigencia
        $this->dataInicioVigencia = $dataInicioVigencia;
    }
    
    /***
     * Seta valor para: siglaPeriodoLetivo
     *
     * @param siglaPeriodoLetivo
     * @result void
     **/
    function setSiglaPeriodoLetivo( $siglaPeriodoLetivo ) {
        // seta o valor de: siglaPeriodoLetivo
        $this->siglaPeriodoLetivo = $siglaPeriodoLetivo;
    }
    
    /***
     * Seta valor para: nomeCurso
     *
     * @param nomeCurso
     * @result void
     **/
    function setNomeCurso( $nomeCurso ) {
        // seta o valor de: nomeCurso
        // carregado em listaMatriculaAluno()
        $this->nomeCurso = $nomeCurso;
    }


    public static function criarMatriculaAluno($idPessoa,$matriculaAluno,$dataMatricula,
        $siglaCurso, $turnoIngresso, $concursoPontos, $concursoClassificacao, $idFormaIngresso,
        $con=null) {

        // Obtendo id da matriz curricular vigente do curso
        $matriz = MatrizCurricular::obterMatrizCurricularAtual($siglaCurso);
        if( $matriz == null) {
            throw new Exception("Curso $siglaCurso não tem matriz curricular cadastrada");
        }
        $idMatriz = $matriz->getIdMatriz();
        
        // Obtem id do periodo letivo atual do curos
        $periodoLetivo=PeriodoLetivo::obterPeriodoLetivoAtual($siglaCurso);
        $idPeriodoLetivo = $periodoLetivo->getIdPeriodoLetivo();

        if($con==null) $con = BD::conectar();
        $queryMatricula=sprintf("insert into MatriculaAluno (matriculaAluno,idPessoa,dataMatricula," .
        "siglaCurso,idMatriz,idPeriodoLetivo,turnoIngresso,concursoPontos,concursoClassificacao,situacaoMatricula,idFormaIngresso)" .
        " value ('%s',%d,'%s','%s',%d,%d,'%s',%s,%s,'CURSANDO',%d)",
         mysql_real_escape_string($matriculaAluno),
         $idPessoa,
         $dataMatricula,
         $siglaCurso,
         $idMatriz,
         $idPeriodoLetivo,
         $turnoIngresso,
         Util::tratarDataNullSQL($concursoPontos),
         Util::tratarNumeroNullSQL($concursoClassificacao),
         $idFormaIngresso);
        $rsMatricula = @mysql_query($queryMatricula,$con);
        if(!$rsMatricula) {
            throw new Exception("Erro ao inserir na tabela MatriculaAluno.");
        }

        $queryHist = sprintf("insert into SituacaoMatriculaHistorico (matriculaAluno,
            situacaoMatricula,texto) values ('%s','CURSANDO','Matrícula inicial')",
            mysql_real_escape_string($matriculaAluno));
        $rsHist = @mysql_query($queryHist,$con);
        if(!$rsHist) {
            throw new Exception("Erro ao inserir na tabela SituacaoMatriculaHistorico.");
        }
    }

    /***
     * Retorna uma instância de MatriculaAluno, dada a chave de matricula.
     *
     * @param numMatriculaAluno string com a matrícula do aluno
     * @result instância de MatriculaAluno, ou null, se não encontrar
     **/
    public static function obterMatriculaAluno( $numMatriculaAluno, $con=null) 
    {
        if( $con == null) {
            $con = BD::conectar();
        }

        // retorna o valor no DB
        $query = sprintf("SELECT * FROM MatriculaAluno " .
            "WHERE matriculaAluno = '%s'", mysql_real_escape_string($numMatriculaAluno));
        $rs = mysql_query($query,$con);
        
        if( (!$rs) || mysql_num_rows($rs)==0 ) 
        {
            return null;
        }
        $resMA = mysql_fetch_array($rs);
        $__obj = new MatriculaAluno( Aluno::getAlunoByIdPessoa( $resMA['idPessoa']), 
            MatrizCurricular::obterMatrizCurricular( $resMA['siglaCurso'], $resMA['idMatriz']),
            PeriodoLetivo::obterPeriodoLetivo( $resMA['idPeriodoLetivo']),
            $resMA['matriculaAluno'],
            $resMA['dataMatricula'],
            $resMA['dataConclusao'],
            $resMA['situacaoMatricula'],
            $resMA['turnoIngresso'],
            $resMA['concursoPontos'],
            $resMA['concursoClassificacao'],
            FormaIngresso::getFormaIngressoById( $resMA['idFormaIngresso']));
        return $__obj;
    }

    public static function obterUltimaMatriculaPorPessoa(Pessoa $pessoa) {
        $con = BD::conectar();
        $query=sprintf("select * from MatriculaAluno ma1 where
  ma1.dataMatricula=(select max(ma2.dataMatricula) from MatriculaAluno ma2
    where ma2.idPessoa=%d)
  and ma1.idPessoa=%d",$pessoa->getIdPessoa(),$pessoa->getIdPessoa());
        $result=mysql_query($query,$con);
        if(mysql_num_rows($result)==1) {
            $rs = mysql_fetch_array($result);
            $obj = new MatriculaAluno( Aluno::getAlunoByIdPessoa( $rs['idPessoa']), 
                MatrizCurricular::obterMatrizCurricular( $rs['siglaCurso'], $rs['idMatriz']),
                PeriodoLetivo::obterPeriodoLetivo( $rs['idPeriodoLetivo']),
                $rs['matriculaAluno'],
                $rs['dataMatricula'],
                $rs['dataConclusao'],
                $rs['situacaoMatricula'],
                $rs['turnoIngresso'],
                $rs['concursoPontos'],
                $rs['concursoClassificacao'],
                FormaIngresso::getFormaIngressoById( $rs['idFormaIngresso']));
            return $obj;
        } 
        else 
        {
            return null;
        }
    }
    
    /***
     * Retorna a lista de objetos baseado em parametros: matriculaaluno
     *
     * @param conditionalStatement = ''
     * @result coleção de objetos: MatriculaAluno
     **/
    public static function obterMatriculasAlunoPorIdPessoa( $idPessoa ) 
    {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM MatriculaAluno " .
                                "WHERE idPessoa = %d", $idPessoa);
        $result = mysql_query( $query, $con);
        $matriculas = array();
        while( $registro = mysql_fetch_array( $result) ) 
        { 

            $__newObj = new MatriculaAluno( Aluno::getAlunoByIdPessoa( $registro['idPessoa']), 
                MatrizCurricular::obterMatrizCurricular( $registro['siglaCurso'], $registro['idMatriz']),
                PeriodoLetivo::obterPeriodoLetivo( $registro['idPeriodoLetivo']),
                $registro['matriculaAluno'],
                $registro['dataMatricula'],
                $registro['dataConclusao'],
                $registro['situacaoMatricula'],
                $registro['turnoIngresso'],
                $registro['concursoPontos'],
                $registro['concursoClassificacao'],
                FormaIngresso::getFormaIngressoById( $registro['idFormaIngresso']));
            // adiciona objetos à coleção 
            array_push($matriculas, $__newObj);
        }
        return $matriculas;
    }
    
    
    public function obterInscricoesCursando()
    {
        $inscricoes = array();
        $query = sprintf("select I.idTurma from Inscricao I "
                . "where I.matriculaAluno='%s'"
                . " and I.situacaoInscricao='%s'", 
                $this->matriculaAluno,
                Inscricao::CUR);
        $con = BD::conectar(); 
        $result = mysql_query($query, $con);
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
        {
            $idTurma = $row['idTurma'];
            $inscricao = Inscricao::getInscricao($idTurma, $this->matriculaAluno);
            $inscricoes[] = $inscricao;
        }
        return $inscricoes;
    }
    
    public function obterInscricoesConcluidas()
    {
        $inscricoes = array();
        $query = sprintf("select I.idTurma,I.situacaoInscricao,I.dataInscricao,I.mediaFinal,I.totalFaltas from Inscricao I "
                . "where I.matriculaAluno='%s'"
                . " and I.situacaoInscricao in('%s','%s','%s')", 
                $this->matriculaAluno,
                Inscricao::AP,
                Inscricao::RM,
                Inscricao::RF);
        $con = BD::conectar(); 
        $result = mysql_query($query, $con);
        
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
        {
            $idTurma = $row['idTurma'];
            $inscricao = Inscricao::getInscricao($idTurma, $this->matriculaAluno);
            $inscricoes[] = $inscricao;
        }
        return $inscricoes;
    }
    
    
    
    
    private static function listaMatriculaAluno( $conditionalStatement = '' ) 
    {
        $con = BD::conectar();
        $sqlStatement = sprintf("SELECT * FROM MatriculaAluno as m ".
                        "INNER JOIN Curso as c ".
                        "ON m.siglaCurso = c.siglaCurso ".
                        "INNER JOIN MatrizCurricular as matriz ".
                        "ON m.idMatriz = matriz.idMatriz ".
                        "INNER JOIN PeriodoLetivo as p ".
                        "ON m.idPeriodoLetivo = p.idPeriodoLetivo ".
                        "INNER JOIN FormaIngresso as f ".
                        "ON m.idFormaIngresso = f.idFormaIngresso ".
                        "WHERE %s", $conditionalStatement);
        $result = mysql_query($sqlStatement, $con);
        $collectionOfObjects = array();
        while( $registro = mysql_fetch_array($result) ) 
        {
            $__newObj = new MatriculaAluno( Aluno::getAlunoByIdPessoa( $registro['idPessoa']), 
                MatrizCurricular::obterMatrizCurricular( $registro['siglaCurso'], $registro['idMatriz']),
                PeriodoLetivo::obterPeriodoLetivo( $registro['idPeriodoLetivo']),
                $registro['matriculaAluno'],
                $registro['dataMatricula'],
                $registro['dataConclusao'],
                $registro['situacaoMatricula'],
                $registro['turnoIngresso'],
                $registro['concursoPontos'],
                $registro['concursoClassificacao'],
                FormaIngresso::getFormaIngressoById( $registro['idFormaIngresso']));

            // adiciona objetos à coleção
            array_push($collectionOfObjects, $__newObj);
         }
         // retorna a coleção de objetos
         return $collectionOfObjects;
    }

    public static function obterListaMatriculaPorSiglaCursoMatricula($siglaCurso,$matriculaAluno) {

        $con = BD::conectar();
        $query = sprintf("SELECT * FROM MatriculaAluno as ma ".
                      "INNER JOIN Curso as c ".
                      "ON ma.siglaCurso = c.siglaCurso ".
                      "INNER JOIN MatrizCurricular as matriz ".
                      "ON ma.siglaCurso = matriz.siglaCurso and ".
                      "ma.idMatriz = matriz.idMatriz ".
                      "INNER JOIN PeriodoLetivo as pl ".
                      "ON ma.idPeriodoLetivo = pl.idPeriodoLetivo ".
                      "INNER JOIN FormaIngresso as fi ".
                      "ON ma.idFormaIngresso = fi.idFormaIngresso ".
                      "WHERE ma.siglaCurso='%s' " .
                        " AND ma.matriculaAluno='%s'",
                      mysql_escape_string($siglaCurso),
                      mysql_escape_string($matriculaAluno));
        $result = mysql_query($query,$con);
        $collectionOfObjects = array();
        while( $rs = mysql_fetch_array($result)) {
            $newObj = new MatriculaAluno( Aluno::getAlunoByIdPessoa( $rs['idPessoa']), 
                MatrizCurricular::obterMatrizCurricular( $rs['siglaCurso'], $rs['idMatriz']),
                PeriodoLetivo::obterPeriodoLetivo( $rs['idPeriodoLetivo']),
                $rs['matriculaAluno'],
                $rs['dataMatricula'],
                $rs['dataConclusao'],
                $rs['situacaoMatricula'],
                $rs['turnoIngresso'],
                $rs['concursoPontos'],
                $rs['concursoClassificacao'],
                FormaIngresso::getFormaIngressoById( $rs['idFormaIngresso']));

            // adiciona objetos à coleção
            array_push($collectionOfObjects, $newObj);
        }
        return $collectionOfObjects;
    }

    public static function obterListaMatriculaPorSiglaCursoNomeAluno($siglaCurso, $nome) {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM MatriculaAluno MA ".
                      "INNER JOIN Curso C ".
                      "ON MA.siglaCurso = C.siglaCurso ".
                      "INNER JOIN MatrizCurricular MC ".
                      "ON MA.idMatriz = MC.idMatriz and MA.siglaCurso = MC.siglaCurso ".
                      "INNER JOIN PeriodoLetivo PL ".
                      "ON MA.idPeriodoLetivo = PL.idPeriodoLetivo ".
                      "INNER JOIN FormaIngresso FI ".
                      "ON MA.idFormaIngresso = FI.idFormaIngresso " .
                      "INNER JOIN Pessoa P ".
                      "ON MA.idPessoa = P.idPessoa " .
                      "WHERE MA.siglaCurso='%s' " .
                        " AND P.nome like '%s%%'",
                      mysql_escape_string($siglaCurso),
                      mysql_escape_string($nome));
        $result=mysql_query($query,$con);
        $collectionOfObjects = array();
        while( $rs = mysql_fetch_array($result)) {
            $newObj = new MatriculaAluno( Aluno::getAlunoByIdPessoa( $rs['idPessoa']), 
                MatrizCurricular::obterMatrizCurricular( $rs['siglaCurso'], $rs['idMatriz']),
                PeriodoLetivo::obterPeriodoLetivo( $rs['idPeriodoLetivo']),
                $rs['matriculaAluno'],
                $rs['dataMatricula'],
                $rs['dataConclusao'],
                $rs['situacaoMatricula'],
                $rs['turnoIngresso'],
                $rs['concursoPontos'],
                $rs['concursoClassificacao'],
                FormaIngresso::getFormaIngressoById( $rs['idFormaIngresso']));

            // adiciona objetos à coleção
            array_push($collectionOfObjects, $newObj);
        }
        return $collectionOfObjects;
    }

     /*
      * Funcao para verificar se o idPessoa pertence a um aluno
     ** Casos de Uso: UC02.06.00
      * @author: Rodrigo Henrique
      * @result: TRUE, caso o idPessoa perten?a a um aluno.
      * @result: FALSE, caso o idPessoa n?o perten?a a um aluno.
    */
   public static function existeAluno($idPessoa){
       $con=BD::conectar();
        $query=sprintf("Select * ".
                       "from MatriculaAluno ".
                       "where idPessoa = %d",$idPessoa);
        $result=mysql_query($query,$con);
        if(mysql_num_rows($result) > 0){
                return true; 
            }
            else {
                return false;
            }
   }

        /*
      * Funcao para verificar se a matricula do aluno excede 5 anos
      * RN012
      * Casos de Uso: UC02.06.00
      * @author: Rodrigo Henrique
      * @result: TRUE, caso a matricula exceda 5 anos
      * @result: FALSE, caso contr?rio.
    */
   // TODO MB refatorar esse método para usar o novo atributo tempoMaximoIntegralizacaoEmMeses
   public function verificaMatriculaAlunoExcedeTempo()
   {
       $con=BD::conectar();

       $query=sprintf("select year(now())-year('%s') as 'Anos', ".
                       "month(now())-month('%s') as 'Semanas', ".
                       "day(now())-day('%s') as 'Dias' ".
                       "from MatriculaAluno ".
                       "where matriculaAluno='%s'",$this->dataMatricula,$this->dataMatricula,$this->dataMatricula,$this->matriculaAluno);
       $result=mysql_query($query,$con);
        while($resExcede = mysql_fetch_array($result) ) {
                $anos=$resExcede['Anos'];
                $semanas=$resExcede['Semanas'];
                $dias=$resExcede['Dias'];
         
        if(($anos==5 && $semanas>0) || ($anos==5 && $semanas==0 && $dias<0) ||($anos>5) ){
                return true;
            }
            else {
                return false;
            }
         }
   }
   
    /*
    * Retorna a CR de uma "MatriculaAluno". A CR é uma nota que avalia o rendimento do aluno
    * num determinado curso, para um determinada matrícula.
    * O cálculo dessa nota está especificado na RN15.
    * @author: Marcio Belo
    * @result: Valor real de 0 a 10.
    */
    public function calcularCR() {
        $con=BD::conectar();
        if($con) {
            $query=sprintf("select sum(i.mediaFinal*cc.creditos)/sum(cc.creditos) as CR from Inscricao i " .
                "inner join Turma t on i.idTurma=t.idTurma inner join ComponenteCurricular cc " .
                "on t.siglaCurso=cc.siglaCurso and t.idMatriz=cc.idMatriz and t.siglaDisciplina=cc.siglaDisciplina and i.situacaoInscricao in ('AP','RM','RF') " .
                "and i.matriculaAluno='%s'",
                mysql_real_escape_string($this->matriculaAluno));
            $result=mysql_query($query,$con);
            $resCR = mysql_fetch_array($result);
            $CR=$resCR["CR"];
            if(is_null($CR)) $CR=0.0;
        } else {
            trigger_error("Não foi possível conectar ao servidor de banco de dados.",E_USER_ERROR);
        }
        return $CR;
    }

     /*Verifica se o aluno está com uma determinada situacao de Matricula
        * Casos de Uso: UC02.07.01
        * @return: False -> Nao esta com uma determinada situacao de Matricula;
        * @return: True -> Esta com uma determinada situacao de Matricula;
    */

    public function verificaSituacaoMatriculaAluno($matriculaAluno,$situacaoMatricula) {
        $con = BD::conectar();

        $query =sprintf("select m.* from MatriculaAluno as m ".
                "where m.matriculaAluno= '%s' ".
                " and m.situacaoMatricula in ('%s') ",$matriculaAluno,$situacaoMatricula);
                
        $resObj = mysql_query($query,$con);

        if(mysql_num_rows($resObj) > 0) {
            return true; //"O aluno está com a situacaoMatricula informada;
        }
        else {
            return false; //"O aluno nao está com a situacaoMatricula informada;

        }
    }

    public function anoMatricula() 
    {
        $con = BD::conectar();
             $query =sprintf("select year(dataMatricula) ".
                 "from MatriculaAluno as m ".
                "where m.matriculaAluno= '%s' ",$this->getMatriculaAluno());

        $resObj = mysql_query($query,$con);

        $resultado = mysql_result($resObj, 0);

        return $resultado;
    }

    public function getAluno() 
    {
        return $this->aluno;
    }

    /* Método semelhante ao getIdFormaIngresso(), porém este
    * método retorna a descrição da forma de ingresso (Concurso Vestibular, Concurso Cota, etc)
    * Casos de Uso:
    *    UC01.09.00 - Ficha de Matrícula
    * @author: Marcelo Atie
    * @return: String
    */

    public function getDescFormaIngresso() {
        $con = BD::conectar();
        $query = sprintf("select descricao " .
                        "from FormaIngresso FI " .
                        "where FI.idFormaIngresso = %d ",
                        mysql_real_escape_string($this->getIdFormaIngresso()));

        $resObj = mysql_query($query, $con);

        $resultado = mysql_result($resObj, 0);

        return $resultado;
    }

    /**
     * Produz uma coleção de dados para exportação da carteira
     * de estudante
     * @return array de dados
     */
    public static function obterDadosCarteirinha() {
        $con = BD::conectar();
        // retorna o valor do DB
        $query = "SELECT
     MatriculaAluno.`matriculaAluno`,
     Pessoa.`nome`,
     MatriculaAluno.`turnoIngresso`,
     DATE_FORMAT(Pessoa.`dataNascimento`,'%d/%m/%Y') as dataNascimento,
     Aluno.`rgNumero`,
     Aluno.`rgOrgaoEmissor`,
     Aluno.`nomeMae`,
     Aluno.`nomePai`,
     MatriculaAluno.`situacaoMatricula`
FROM
     Aluno INNER JOIN  MatriculaAluno ON (Aluno.`idPessoa` = MatriculaAluno.`idPessoa`)
     INNER JOIN  Pessoa ON (Aluno.`idPessoa` = Pessoa.`idPessoa`)
     WHERE
     MatriculaAluno.`situacaoMatricula` = 'CURSANDO' order by Pessoa.`nome`";

        $query= mysql_query($query);
        $collection=array();
        if(mysql_num_rows($query) > 0) {
            for($i=0;$i<mysql_num_rows($query);$i++) {
                // cria novo objeto
                $collection[$i]['matricula']=mysql_result($query,$i,'MatriculaAluno.matriculaAluno');
                $collection[$i]['nome']=mysql_result($query,$i,'Pessoa.nome');
                $collection[$i]['turno']=mysql_result($query,$i,'MatriculaAluno.turnoIngresso');
                $collection[$i]['dataNascimento']=mysql_result($query,$i,'dataNascimento');
                $collection[$i]['rg']=mysql_result($query,$i,'Aluno.rgNumero');
                $collection[$i]['rgOrgaoEmissor']=mysql_result($query,$i,'Aluno.rgOrgaoEmissor');
                $collection[$i]['mae']=mysql_result($query,$i,'Aluno.nomeMae');
                $collection[$i]['pai']=mysql_result($query,$i,'Aluno.nomePai');
                $collection[$i]['situacaoMatricula']=mysql_result($query,$i,'MatriculaAluno.situacaoMatricula');
            }
        }
        return $collection;
    }

    /**
     * Atualiza a matricula de um aluno existente.
     * @param <type> $idPessoa
     * @param <type> $matriculaAlunoAntiga
     * @param <type> $matriculaAluno
     * @param <type> $siglaCurso
     * @param <type> $dataMatricula
     * @param <type> $turnoIngresso
     * @param <type> $idPeriodoLetivo
     * @param <type> $concursoPontos
     * @param <type> $concursoClassificacao
     * @param <type> $idFormaIngresso
     * @param <type> $con 
     */
    public static function atualizar($idPessoa,
        $matriculaAlunoAntiga,
        $matriculaAlunoNova,
        $siglaCurso,
        $idMatriz,
        $dataMatricula,
        $dataConclusao,
        $turnoIngresso,
        $idPeriodoLetivo,
        $concursoPontos,
        $concursoClassificacao,
        $idFormaIngresso,
        $con) {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaAluno set 
            matriculaAluno='%s',
            dataMatricula='%s',
            dataConclusao=%s,
            siglaCurso='%s',
            idMatriz=%d,
            idPeriodoLetivo=%d,
            turnoIngresso='%s',
            concursoPontos=%s,
            concursoClassificacao=%s,
            idFormaIngresso=%d 
            where idPessoa=%d and matriculaAluno='%s'",
            mysql_escape_string($matriculaAlunoNova),
            mysql_escape_string($dataMatricula),
            Util::tratarDataNullSQL($dataConclusao),
            mysql_escape_string($siglaCurso),
            mysql_escape_string($idMatriz),
            mysql_escape_string($idPeriodoLetivo),
            mysql_escape_string($turnoIngresso),
            Util::tratarDataNullSQL($concursoPontos),
            Util::tratarDataNullSQL($concursoClassificacao),
            mysql_escape_string($idFormaIngresso),
            mysql_escape_string($idPessoa),
            mysql_escape_string($matriculaAlunoAntiga));
        mysql_query($query,$con);
        if(mysql_errno()!=0 ) {
            throw new Exception("Erro ao atualizar MatriculaAluno.");
        }
    }

    /**
     * Obtém a lista de matrículas do curso informados que estão CURSANDO.
     * @param Curso $curso curso para o qual se deseja obter as matrículas
     * @return MatriculaAluno[] vetor de matriculas
     */
    public static function obterListaMatriculasCursando(Curso $curso) {
        $restricao = sprintf(" c.siglaCurso = '%s' and m.situacaoMatricula in ('CURSANDO') ",
                $curso->getSiglaCurso());
        return MatriculaAluno::listaMatriculaAluno($restricao);
    }
    
    /**
     * Indica se a matrícula é considerada ativa
     * @return boolean 
     */
    public function isAtiva() {
        return $this->situacaoMatricula == MatriculaAluno::CURSANDO ||
                $this->situacaoMatricula == MatriculaAluno::TRANCADO || 
                $this->situacaoMatricula == MatriculaAluno::EVADIDO;
    }

    /**
     * Pegar todos as matrículas que estão ativas no curso
     * @param Curso $curso
     * @return type array de MatriculaAluno
     */
    public static function obterMatriculasAtivas(Curso $curso) 
    {
        $restricao = sprintf(" c.siglaCurso = '%s' and m.situacaoMatricula in ('CURSANDO','EVADIDO','TRANCADO') ",
                $curso->getSiglaCurso());
        return MatriculaAluno::listaMatriculaAluno($restricao);
    }

    /**
     * Calcula a quantidade de meses decorridos desde a data de matrícula 
     * @return int qtde de meses completos desde a data de matrícula
     */
    public function obterTempoCursoEmMeses() 
    {
        $datetime1 = new DateTime( $this->dataMatricula);
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);
        return $interval->m + 12*$interval->y;
    }

    /**
     * OBTEM TODOS OS COMPONENTES CURRICULARES PENDENTES DA MATRICULA DO ALUNO
     * @return array de ComponenteCurricular componentes curriculares pendentes
     */
    public function obterComponentesCurricularPendentes() 
    {
        $con = BD::conectar();
        $query=sprintf(''
            . ' SELECT'
            . ' CC.`siglaCurso`, CC.`idMatriz`, CC.`siglaDisciplina`'
            . ' FROM'
            . ' `ComponenteCurricular` CC, `MatriculaAluno` MA'
            . ' WHERE'
            . ' CC.`siglaCurso` = MA.`siglaCurso`'
            . ' and CC.`idMatriz` = MA.`idMatriz`'
            . " and MA.`matriculaAluno` = '%s'"
            . ' ORDER BY CC.`periodo`, CC.`siglaDisciplina`',
            mysql_real_escape_string( $this->matriculaAluno));
        $result = mysql_query($query,$con);
        $pendentes = array();
        while($row = mysql_fetch_array($result, MYSQL_ASSOC)) 
        {
            $siglaCurso = $row['siglaCurso'];
            $idMatriz = $row['idMatriz'];
            $siglaDisciplina = $row['siglaDisciplina'];
            $componenteCur = ComponenteCurricular::obterComponenteCurricular($siglaCurso, $idMatriz, $siglaDisciplina);
            
            //Se não há quitação, entao este componente curricular esta na lista de Componentes Pendentes
            if($componenteCur->obterQuitacao( $this) == null)
            {
                $pendentes[] = $componenteCur;
            }
        }
        return $pendentes;
    }
}