<?php
class MatriculaProfessor 
{
    private $matriculaProfessor;
    private $idPessoa;
    private $nome; // TODO retirar
    private $cargaHoraria;
    private $dataInicio;
    private $dataEncerramento;
    private $dataInicioBr;
    private $dataEncerramentoBr;

    public function getMatriculaProfessor( ) {
        return $this->matriculaProfessor;
    }


    public function getIdPessoa( ) {
        return $this->idPessoa;
    }

    public function getNome( ) {
        return $this->nome;
    }

    public function getCargaHoraria( ) {
        return $this->cargaHoraria;
    }

    public function getDataInicio( ) {
        return $this->dataInicio;
    }

    public function getDataEncerramento( ) {
        return $this->dataEncerramento;
    }

    public function getDataInicioBr( ) {
        $this->dataInicioBr = explode('-',$this->dataInicio);
        $this->dataInicioBr = implode('/',array_reverse($this->dataInicioBr));
        
        // retorna o valor de: dataInicioBr
        return $this->dataInicioBr;
    }

    public function getDataEncerramentoBr( ) {
        $this->dataEncerramentoBr = explode('-',$this->dataEncerramento);
        $this->dataEncerramentoBr = implode('/',array_reverse($this->dataEncerramentoBr));
        return $this->dataEncerramentoBr;
    }

    function setMatriculaProfessor( $matriculaProfessor ) {
        // seta o valor de: matriculaProfessor
        $this->matriculaProfessor = $matriculaProfessor;
    }

    function setIdPessoa( $idPessoa ) {
        // seta o valor de: idPessoa
        $this->idPessoa = $idPessoa;
    }

    function setNome( $nome ) {
        // seta o valor de: nome
        $this->nome = $nome;
    }

    function setCargaHoraria( $cargaHoraria ) {
        // seta o valor de: cargaHoraria
        $this->cargaHoraria = $cargaHoraria;
    }

    function setDataInicio( $dataInicio ) {
        // seta o valor de: dataInicio
        $this->dataInicio = $dataInicio;
    }

    function setDataEncerramento( $dataEncerramento ) {
        // seta o valor de: dataEncerramento
        $this->dataEncerramento = $dataEncerramento;
    }

    public static function obterTodasMatriculasProfessorVigentes() {
        $con = BD::conectar();
        $query = "SELECT * FROM MatriculaProfessor as mp
            INNER JOIN Pessoa as p ON mp.idPessoa = p.idPessoa
            where mp.dataEncerramento is NULL or mp.dataEncerramento > CURDATE()
            ORDER BY p.nome ASC";
        $result = mysql_query($query,$con);
        $col = array();
        while( $linha = mysql_fetch_array($result)) {
            $obj = new MatriculaProfessor();
            $obj->setMatriculaProfessor($linha['matriculaProfessor']);
            $obj->setIdPessoa($linha['idPessoa']);
            $obj->setNome($linha['nome']);
            $obj->setCargaHoraria($linha['cargaHoraria']);
            $obj->setDataInicio($linha['dataInicio']);
            $obj->setDataEncerramento($linha['dataEncerramento']);

            // adiciona objetos à coleção
            array_push($col, $obj);
        }
        return $col;
    }
    
    public static function obterMatriculasPorIdPessoa($idPessoa) {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM MatriculaProfessor MP where idPessoa=%d",
                $idPessoa);
        $result=mysql_query($query,$con);
        $col = array();
        while($reg=mysql_fetch_array($result)) {
            $matriculaProfessor = new MatriculaProfessor();
            $matriculaProfessor->setMatriculaProfessor($reg['matriculaProfessor']);
            $matriculaProfessor->setIdPessoa($reg['idPessoa']);
            //$matriculaProfessor->setNome($reg['nome']);
            $matriculaProfessor->setCargaHoraria($reg['cargaHoraria']);
            $matriculaProfessor->setDataInicio($reg['dataInicio']);
            $matriculaProfessor->setDataEncerramento($reg['dataEncerramento']);
            array_push($col, $matriculaProfessor);
         }
         return $col;
    }

     public static function criarMatriculaProfessor($idPessoa,$matriculaProfessor,$cargaHoraria,$dataInicio,
        $dataEncerramento,$con=null) {
        

        if($con==null) $con = BD::conectar();
        $queryMatricula=sprintf("insert into MatriculaProfessor (matriculaProfessor,idPessoa,cargaHoraria,dataInicio,dataEncerramento)" .
        " value ('%s',%d,'%s','%s',%s)",
         mysql_real_escape_string($matriculaProfessor),
         $idPessoa,
         mysql_real_escape_string($cargaHoraria),
         mysql_real_escape_string($dataInicio),
         Util::tratarDataNullSQL($dataEncerramento));
        $rsMatricula = @mysql_query($queryMatricula,$con);

        if(!$rsMatricula) {
            throw new Exception("Erro ao inserir na tabela MatriculaProfessor.");
        }
    }

        /***
     * Retorna uma instância de MatriculaAluno, dada a chave de matricula.
     *
     * @param matricula string com a matrícula do aluno
     * @result instância de MatriculaAluno, ou null, se não encontrar
     * @author Helder, Marcio Belo
     **/
    public static function obterMatriculaProfessor( $matricula,$con=null ) {
        if($con==null) $con = BD::conectar();

        // retorna o valor no DB
        $query=sprintf("SELECT * FROM MatriculaProfessor " .
            "WHERE matriculaProfessor = '%s'", mysql_real_escape_string($matricula));
        $rs = mysql_query($query,$con);

        if( (!$rs) || mysql_num_rows($rs)==0 ) return null;

        $resMA = mysql_fetch_array($rs);

        // cria novo objeto
        $__obj = new MatriculaProfessor();
        $__obj->setMatriculaProfessor($resMA['matriculaProfessor']);
        $__obj->setIdPessoa($resMA['idPessoa']);
        $__obj->setCargaHoraria($resMA['cargaHoraria']);
        $__obj->setDataInicio($resMA['dataInicio']);
        $__obj->setDataEncerramento($resMA['dataEncerramento']);

        return $__obj;
    }

    public static function atualizar($idPessoa,
        $matriculaProfessorAntiga,
        $matriculaProfessorNova,
        $cargaHoraria,
        $dataInicio,
        $dataEncerramento,
        $con) {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update MatriculaProfessor set
            matriculaProfessor='%s',
            cargaHoraria='%s',
            dataInicio='%s',
            dataEncerramento=%s
            where idPessoa=%d and matriculaProfessor='%s'",
            mysql_escape_string($matriculaProfessorNova),
            mysql_escape_string($cargaHoraria),
            mysql_escape_string($dataInicio),
            Util::tratarDataNullSQL($dataEncerramento),
            $idPessoa,
            mysql_escape_string($matriculaProfessorAntiga));
        mysql_query($query,$con);
        if(mysql_errno()!=0 ) {
            throw new Exception("Erro ao atualizar MatriculaProfessor.");
        }
    }

} ?>