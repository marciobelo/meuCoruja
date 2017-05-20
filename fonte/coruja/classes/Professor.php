<?php
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/MatriculaProfessor.php";

class Professor extends Pessoa 
{
    private $matriculasProfessor;
    private $titulacaoAcademica;
    private $cvLattes;
    private $nomeGuerra;
    private $corFundo;

    public function getTitulacaoAcademica( ) {
        return $this->titulacaoAcademica;
    }


    public function getCvLattes( ) {
        return $this->cvLattes;
    }

   public function getNomeGuerra( ) {
        return $this->nomeGuerra;
    }

    public function getCorFundo( ) {
        return $this->corFundo;
    }

    function setTitulacaoAcademica( $titulacaoAcademica ) {
        $this->titulacaoAcademica = $titulacaoAcademica;
    }

    function setCvLattes( $cvLattes ) {
        $this->cvLattes = $cvLattes;
    }

    function setMatriculasProfessor($matriculasProfessor) {
        $this->matriculasProfessor = $matriculasProfessor;
    }

    public function getMatriculasProfessor() {
        return $this->matriculasProfessor;
    }

    function setNomeGuerra( $nomeGuerra ) {
        $this->nomeGuerra = $nomeGuerra;
    }

    function setCorFundo( $corFundo ) {
        $this->corFundo = $corFundo;
    }

    /**
     * Obtém um professor dada a matrícula
     * @param String $matricula
     * @return Professor referência ao objeto de professor, ou null, caso não
     * exista
     */
    public static function obterProfessorPorMatricula($matricula) {
         $con = BD::conectar();
         $query = sprintf("SELECT * FROM Pessoa P inner join Professor PR
             on P.idPessoa=PR.idPessoa
             inner join MatriculaProfessor MP
              on MP.idPessoa = PR.idPessoa
             WHERE LPAD(MP.matriculaProfessor,15,'0') = LPAD('%s',15,'0')",
             mysql_real_escape_string($matricula));
         $result=mysql_query($query, $con);
         $professor = null;
         while($reg=mysql_fetch_array($result)) {
            $professor = new Professor();
            $professor->setTitulacaoAcademica($reg['titulacaoAcademica']);
            $professor->setCvLattes($reg['cvLattes']);
            $professor->setNomeGuerra($reg['nomeGuerra']);
            $professor->setCorFundo($reg['corFundo']);
            $professor->carregaDadosPessoa($reg['idPessoa']);
            $professor->setMatriculasProfessor(MatriculaProfessor::obterMatriculasPorIdPessoa($reg['idPessoa']));
         }
         return $professor;
    }

    /**
     * Retorna os professores vigentes, ou seja, que tenham uma ou
     * mais matrículas vigentes.
     * @return array Coleção de objetos de Professor
     */
    public static function obterProfessoresVigentes() {
        $con=BD::conectar();
        $query="select distinct * from Professor pr
            inner join Pessoa p on pr.idPessoa=p.idPessoa
            where exists (select * from MatriculaProfessor mp
                where mp.idPessoa=pr.idPessoa and
                (mp.dataEncerramento is NULL or
                mp.dataEncerramento > CURDATE()))
            order by p.nome";
        $result=mysql_query($query, $con);
        $col=array();
        while($reg=mysql_fetch_array($result)) {
            // cria novo objeto
            $professor = new Professor();
            $professor->setIdPessoa($reg['idPessoa']);
            $professor->setTitulacaoAcademica($reg['titulacaoAcademica']);
            $professor->setCvLattes($reg['cvLattes']);
            $professor->carregaDadosPessoa($reg['idPessoa']);
            array_push($col, $professor);
        }
        //TODO carregar matriculasProfessor
        return $col;
    }

     /***
     * Retorna a lista de objetos de Pessoa por nome
     *
     * @result coleção de objetos: Pessoa
     *
     * a razão de não utilizar o lista_pessoa() é que o mysql_real_escape_string
     * gera problema com o % do parametro LIKE
     **/
    public static function obterProfessoresPorNome( $nome ) {

         $con = BD::conectar();
         $query = sprintf("SELECT * FROM Pessoa P inner join Professor PR
             on(P.idPessoa=PR.idPessoa)
             WHERE P.nome like '%s%%'
             order by P.nome",
             mysql_real_escape_string($nome));

         $result=mysql_query($query, $con);

         $col=array();
         while($reg=mysql_fetch_array($result)) {
            $professor = new Professor();
            $professor->setTitulacaoAcademica($reg['titulacaoAcademica']);
            $professor->setCvLattes($reg['cvLattes']);
            $professor->carregaDadosPessoa($reg['idPessoa']);
            $professor->setMatriculasProfessor(MatriculaProfessor::obterMatriculasPorIdPessoa($reg['idPessoa']));
            array_push($col, $professor);

         }
         return $col;
    }

    public static function inserirProfessor( $idPessoa, $titulacaoAcademica, $cvLattes, $nomeGuerra, $corFundo,$con=null ) {

        if($con==null) $con = BD::conectar();
        $query=sprintf("INSERT INTO `Professor` (`idPessoa`, `titulacaoAcademica`, `cvLattes`, `nomeGuerra`, `corFundo`) " .
            "VALUES (%d,'%s','%s','%s','%s')",
            $idPessoa,
            mysql_real_escape_string($titulacaoAcademica),
            mysql_real_escape_string($cvLattes),
            mysql_real_escape_string($nomeGuerra),
            mysql_real_escape_string($corFundo));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Professor.");
        }
    }

    public static function atualizar( $idPessoa, $titulacaoAcademica,$cvLattes,$nomeGuerra,$corFundo, $con=null ) {
        if($con==null) $con = BD::conectar();
        $query = sprintf("UPDATE Professor set
            titulacaoAcademica='%s',
            cvLattes='%s',
            nomeGuerra='%s',
            corFundo='%s'
            where idPessoa=%d",
            mysql_real_escape_string($titulacaoAcademica),
            mysql_real_escape_string($cvLattes),
            mysql_real_escape_string($nomeGuerra),
            mysql_real_escape_string($corFundo),
            mysql_real_escape_string($idPessoa) );
        $result=mysql_query($query,$con);
       
        if(!$result) {
            throw new Exception("Erro ao atualizar na tabela Professor.");
        }
    }


     protected function carregaDadosProfessor($idPessoa) {
        $con = BD::conectar();
        $query=sprintf("SELECT * FROM `Professor` ".
                "WHERE `idPessoa` = %d ",$idPessoa);
        $result=mysql_query($query,$con);
        while( $resProfessor = mysql_fetch_array($result) ) {

            $this->setCvLattes($resProfessor['cvLattes']);
            $this->setTitulacaoAcademica($resProfessor['titulacaoAcademica']);
            $this->setNomeGuerra($resProfessor['nomeGuerra']);
            $this->setCorFundo($resProfessor['corFundo']);

        }
        $this->setMatriculasProfessor(MatriculaProfessor::obterMatriculasPorIdPessoa($idPessoa));
    }

    /**
     * Recupera um professor pelo id de pessoa.
     * @param int $idPessoa identificador da Pessoa-Professor
     * @return Professor professor recuperado
     */
    public static function getProfessorByIdPessoa($idPessoa) {

        $professor = new Professor();
        //é necessário manter esta ordem para carregar os dados
        $professor->carregaDadosProfessor($idPessoa);
        $professor->carregaDadosPessoa($idPessoa);

        return $professor;
    }

    /**
     * Gera uma versão legível do estado desse objeto. Usado para inserir
     * log de auditoria.
     * @return String
     */
    public function toString() {
        $str = "";
        // Dados de Pessoa
        $str .= sprintf("Nome: %s<br/>",$this->getNome());
        $str .= sprintf("Sexo: %s<br/>",$this->getSexo());
        $str .= sprintf("Endereço Logradouro: %s<br/>",$this->getEnderecoLogradouro());
        $str .= sprintf("Endereço Número: %s<br/>",$this->getEnderecoNumero());
        $str .= sprintf("Endereço Complemento: %s<br/>",$this->getEnderecoComplemento());
        $str .= sprintf("Endereço Bairro: %s<br/>",$this->getEnderecoBairro());
        $str .= sprintf("Endereço Município: %s<br/>",$this->getEnderecoMunicipio());
        $str .= sprintf("Endereço Estado: %s<br/>",$this->getEnderecoEstado());
        $str .= sprintf("Endereço CEP: %s<br/>",$this->getEnderecoCEP());
        $str .= sprintf("Data de Nascimento: %s<br/>",Util::dataSQLParaBr($this->getDataNascimento()));
        $str .= sprintf("Nacionalidade: %s<br/>",$this->getNacionalidade());
        $str .= sprintf("Naturalidade: %s<br/>",$this->getNaturalidade());
        $str .= sprintf("Tel.Residencial: %s<br/>",$this->getTelefoneResidencial());
        $str .= sprintf("Tel.Comercial: %s<br/>",$this->getTelefoneComercial());
        $str .= sprintf("Tel.Celular: %s<br/>",$this->getTelefoneCelular());
        $str .= sprintf("E-mail: %s<br/>",$this->getEmail());

        // Professor
        $str .= sprintf("Titulação : %s<br/>",$this->getTitulacaoAcademica());
        $str .= sprintf("Lattes :%s<br/>",$this->getCvLattes());
        $str .= sprintf("Nome de Guerra : %s<br/>",$this->getNomeGuerra());
        $str .= sprintf("Cor : %s<br/>",$this->getCorFundo());
       
        return $str;
    }

}
?>