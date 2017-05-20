<?php
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Util.php";
require_once "$BASE_DIR/classes/TipoCurso.php";

class Curso {

    private $siglaCurso;
    private $nomeCurso;
    private $idTipoCurso;
    private $tipoCurso;
    private $tempoMaximoIntegralizacaoEmMeses;

    public function processarRematriculaAutomatica($con=null) {
        if($con==null) throw new Exception("Não há conexão aberta.");
        $texto = "Rematrícula Atualizada Automaticamente";
        $ultPeriodoLetivo = PeriodoLetivo::obterPeriodoLetivoVigenteMaisAntigo($this->getSiglaCurso());
        $dataFimTrancamento = $ultPeriodoLetivo->obterDataFimTrancMatricula();
        if($dataFimTrancamento==null) throw new Exception("Data de trancamento não especificada para o período letivo.");
        $listaMatsDesatualizada = $this->obterMatriculasDesatualizadas($ultPeriodoLetivo->getDataInicio());
        foreach($listaMatsDesatualizada as $mat) {
            $matriculaAluno = $mat["matriculaAluno"];
            $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno,$con);

            if( $ma->temInscricaoEmTurma($ultPeriodoLetivo,$con) ) { // Se tem inscrição diferente de REQ ou NEG
                $ma->renovarMatricula($texto,$con);
            } else {
                if( Util::obterDataAtual($con) >  $dataFimTrancamento) { // Coloca o aluno como EVADIDO
                    $ma->evadirMatricula($texto,$con);
                }
            }
        }
    }

    /**
     * Retorna uma lista de matrículas desatualizadas para este curso
     * @param Date $dataInicioUltPeriodoLetivo
     * @return Collection
     */
    public function obterMatriculasDesatualizadas($dataInicioUltPeriodoLetivo) {
        $con=BD::conectar();
        $query=sprintf("select MA.matriculaAluno as matriculaAluno,P.nome as nomeAluno,
	MA.situacaoMatricula as situacaoMatricula,
	SIT_MATR_HIST.dataHistorico as dataAtualizacao,SIT_MATR_HIST.texto as textoAtualizacao
	from MatriculaAluno MA inner join Pessoa P on MA.idPessoa=P.idPessoa
	left outer join (select matriculaAluno, dataHistorico,texto from SituacaoMatriculaHistorico SMH) SIT_MATR_HIST
		on MA.matriculaAluno=SIT_MATR_HIST.matriculaAluno
	where
		(MA.situacaoMatricula='CURSANDO' or MA.situacaoMatricula='TRANCADO') AND
                MA.siglaCurso='%s' AND
		(SIT_MATR_HIST.dataHistorico IS NULL OR SIT_MATR_HIST.dataHistorico=(select MAX(dataHistorico) from SituacaoMatriculaHistorico SMH2
				where SMH2.matriculaAluno=MA.matriculaAluno)) AND
		(SIT_MATR_HIST.dataHistorico IS NULL OR SIT_MATR_HIST.dataHistorico < '%s')
	order by MA.matriculaAluno",
        mysql_escape_string($this->getSiglaCurso()),
        mysql_escape_string($dataInicioUltPeriodoLetivo));
        $result=mysql_query($query,$con);
        $i=0;
        $col=array();
        while($linha=mysql_fetch_array($result)) {
            $col[$i]["matriculaAluno"]=$linha["matriculaAluno"];
            $col[$i]["nomeAluno"]=$linha["nomeAluno"];
            $col[$i]["situacaoMatricula"]=$linha["situacaoMatricula"];
            $col[$i]["dataAtualizacao"]=$linha["dataAtualizacao"];
            $col[$i]["textoAtualizacao"]=$linha["textoAtualizacao"];
            $i++;
        }
        return $col;
    }

    public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    public function setSiglaCurso($siglaCurso) {
        $this->siglaCurso = $siglaCurso;
    }

    public function getNomeCurso() {
        return $this->nomeCurso;
    }

    public function setNomeCurso($nomeCurso) {
        $this->nomeCurso = $nomeCurso;
    }

    public function getIdTipoCurso() {
        return $this->idTipoCurso;
    }

    public function setIdTipoCurso($idTipoCurso) {
        $this->idTipoCurso = $idTipoCurso;
    }

    public function getTipoCurso() {
        return $this->tipoCurso;
    }

    public function setTipoCurso(TipoCurso $tipoCurso) {
        $this->tipoCurso = $tipoCurso;
    }
    
    public function getTempoMaximoIntegralizacaoEmMeses() {
        return $this->tempoMaximoIntegralizacaoEmMeses;
    }

    public function setTempoMaximoIntegralizacaoEmMeses($tempoMaximoIntegralizacaoEmMeses) {
        $this->tempoMaximoIntegralizacaoEmMeses = $tempoMaximoIntegralizacaoEmMeses;
    }
    
    /*
     * Função cuja finalidade é obter um Curso
     * @param: siglaCurso (Sigla de um curso válido)
     * @result: curso (Objeto Curso)
     * Usado em todos os casos de uso do SIRO para pegar o nome do curso dada uma determinada sigla
    */
    public static function obterCurso( $siglaCurso) 
    {
        $con = BD::conectar();

        $query =sprintf("SELECT siglaCurso, nomeCurso, idTipoCurso, "
                . "tempoMaximoIntegralizacaoEmMeses FROM Curso WHERE siglaCurso = '%s'",
                mysql_real_escape_string($siglaCurso));
        
        $result=mysql_query($query,$con);

        $curso = null;

        if(mysql_num_rows($result) > 0) { //Valida se algum Curso foi encontrado no sistema.
            $resCurso = mysql_fetch_array($result); //Obtem o resultado do banco
            $curso = new Curso();

            $curso->setNomeCurso($resCurso['nomeCurso']);
            $curso->setSiglaCurso($resCurso['siglaCurso']);
            $curso->setIdTipoCurso($resCurso['idTipoCurso']);
            $tipoCurso = TipoCurso::obterTipoCursoPorId($resCurso['idTipoCurso']);
            $curso->setTipoCurso($tipoCurso);
            $curso->setTempoMaximoIntegralizacaoEmMeses($resCurso['tempoMaximoIntegralizacaoEmMeses']);
        }
        return $curso;
    }


        /**
     * Retorna a lista de objetos de Curso ordenados por sigla
     * @result coleção de objetos: Curso
     * */
    public static function obterListaCurso() 
    {
        $con = BD::conectar();
        $sqlStatement = "SELECT c.*, tc.descricao FROM Curso c
            inner join TipoCurso tc
            on(c.idTipoCurso=tc.idTipoCurso) order by c.nomecurso";

        // recupera os valores com base no resultado
        $result = mysql_query($sqlStatement, $con);
        $col = array();
        while ($rs = mysql_fetch_array($result)) {

            $curso = new Curso();
            $curso->setSiglaCurso($rs['siglaCurso']);
            $curso->setNomeCurso($rs['nomeCurso']);
            $tipoCurso = TipoCurso::obterTipoCursoPorId($rs['idTipoCurso']);
            $curso->setTipoCurso($tipoCurso);
            $curso->setTempoMaximoIntegralizacaoEmMeses($rs['tempoMaximoIntegralizacaoEmMeses']);

            array_push($col, $curso);
        }
        // retorna a coleção de objetos
        return $col;
    }

    /**
     * Retorna a lista de objetos de Curso ordenados por sigla
     * @result coleção de objetos: Curso
     * */
    public static function obterCursosOrdemPorSigla() 
    {
        $con = BD::conectar();
        $sqlStatement = "SELECT * FROM Curso order by siglaCurso ASC";

        // recupera os valores com base no resultado
        $result = mysql_query($sqlStatement, $con);

        $__collectionOfObjects = array();
        while ($__rs = mysql_fetch_array($result)) 
        {
            $__newObj = new Curso();
            $__newObj->setSiglaCurso($__rs['siglaCurso']);
            $__newObj->setNomeCurso($__rs['nomeCurso']);
            $__newObj->setIdTipoCurso($__rs['idTipoCurso']);
            $__newObj->setTempoMaximoIntegralizacaoEmMeses($__rs['tempoMaximoIntegralizacaoEmMeses']);

            // adiciona objetos à coleção
            array_push($__collectionOfObjects, $__newObj);
        }
        // retorna a coleção de objetos
        return $__collectionOfObjects;
    }

    /**
     * Altera um Curso
     * @param <type> $siglaCurso
     * @param <type> $nomeCurso
     * @param <type> $idTipoCurso
     * @param <type> $con
     */
    public static function alterarCurso($siglaCursoAntes,$siglaCursoDepois, $nomeCurso, $idTipoCurso, $con){
        if($con==null) $con=BD::conectar();
        $query=sprintf("update Curso set siglaCurso='%s',nomeCurso='%s', idTipoCurso=%d
        where siglaCurso='%s'",
            mysql_real_escape_string($siglaCursoDepois),
            mysql_real_escape_string($nomeCurso),
            $idTipoCurso,
            mysql_real_escape_string($siglaCursoAntes));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao alterar o Curso");
        }
    }

    public static function excluirCurso(Curso $Curso, $con) {
        if ($con == null) $con = BD::conectar();

        $query2 = sprintf("delete from Curso where siglaCurso='%s'",
                        $Curso->getSiglaCurso());
        $result2 = mysql_query($query2, $con);
        if (!$result2) {
            throw new Exception("Erro ao excluir o Curso");
        }
    }

    public static function incluirCurso($siglaCurso, $nomeCurso, $idTipoCurso, $con) 
    {
        if($con==null) $con=BD::conectar();
        $query=sprintf("insert into Curso (siglaCurso, nomeCurso, idTipoCurso)
            values('%s','%s',%d)",
            mysql_real_escape_string($siglaCurso), mysql_real_escape_string($nomeCurso),
                $idTipoCurso);
        $result=mysql_query($query,$con);
        if(!$result) {
          throw new Exception("Erro ao incluir o Curso");
        }
    }

    // TODO MB refatorar esse código
    public static function obterRankingCRDoCurso(Curso $curso) 
    {

        $matriculasAtivas = MatriculaAluno::obterListaMatriculasCursando($curso);

        $matriculasCR[] = array();

        // produz matriz matricula-cr nao ordenada
        foreach ($matriculasAtivas as $matricula) {
            
            if( $matricula->getMatriculaAluno() >= "101" ) {

                $cr = $matricula->calcularCR();
            
                $matriculasCR[$matricula->getMatriculaAluno()] = $cr;
            }
        }

        arsort($matriculasCR);

        return $matriculasCR;
    }

    /**
     * Pegar todos as matrículas que estão ativas no curso
     * Ativas são as matrículas
     */
    public function obterMatriculasAtivas() 
    {
        $matriculasAtivas = array();
        return $matriculasAtivas;
    }
}
?>
