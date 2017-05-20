<?php

/**
 * Description of Pessoa
 *
 * @author Marcelo Atie, Marcio Belo
 */
class Pessoa {

    private $idPessoa;
    private $nome;
    private $sexo;
    private $enderecoLogradouro;
    private $enderecoNumero;
    private $enderecoComplemento;
    private $enderecoBairro;
    private $enderecoMunicipio;
    private $enderecoEstado;
    private $enderecoCEP;
    private $dataNascimento;
    private $nacionalidade;
    private $naturalidade;
    private $telefoneResidencial;
    private $telefoneComercial;
    private $telefoneCelular;
    private $email;

    protected function carregaDadosPessoa($idPessoa) {

        $con = BD::conectar();
        $query=sprintf("SELECT `idPessoa`, `nome`, `sexo`, `enderecoLogradouro`, ".
                "`enderecoNumero`, `enderecoComplemento`, `enderecoBairro`, ".
                "`enderecoMunicipio`, `enderecoEstado`, `enderecoCEP`, `dataNascimento`, ".
                "`nacionalidade`, `naturalidade`, `telefoneResidencial`, ".
                "`telefoneComercial`, `telefoneCelular`, `email` ".
                "FROM `Pessoa` ".
                "WHERE `idPessoa` = %s ",$idPessoa);
        $result=mysql_query($query,$con);

        while($resPessoa = mysql_fetch_array($result) ) {
            $this->setDataNascimento($resPessoa['dataNascimento']);
            $this->setEmail($resPessoa['email']);
            $this->setEnderecoBairro($resPessoa['enderecoBairro']);
            $this->setEnderecoCEP($resPessoa['enderecoCEP']);
            $this->setEnderecoComplemento($resPessoa['enderecoComplemento']);
            $this->setEnderecoEstado($resPessoa['enderecoEstado']);
            $this->setEnderecoLogradouro($resPessoa['enderecoLogradouro']);
            $this->setEnderecoMunicipio($resPessoa['enderecoMunicipio']);
            $this->setEnderecoNumero($resPessoa['enderecoNumero']);
            $this->setIdPessoa($resPessoa['idPessoa']);
            $this->setNacionalidade($resPessoa['nacionalidade']);
            $this->setNaturalidade($resPessoa['naturalidade']);
            $this->setNome($resPessoa['nome']);
            $this->setSexo($resPessoa['sexo']);
            $this->setTelefoneCelular($resPessoa['telefoneCelular']);
            $this->setTelefoneComercial($resPessoa['telefoneComercial']);
            $this->setTelefoneResidencial($resPessoa['telefoneResidencial']);
        }
    }

    public function getIdPessoa() {
        return $this->idPessoa;
    }

    public function setIdPessoa($idPessoa) {
        $this->idPessoa = $idPessoa;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function setSexo($sexo) {
        $this->sexo = $sexo;
    }

    public function getEnderecoLogradouro() {
        return $this->enderecoLogradouro;
    }

    public function setEnderecoLogradouro($enderecoLogradouro) {
        $this->enderecoLogradouro = $enderecoLogradouro;
    }

    public function getEnderecoNumero() {
        return $this->enderecoNumero;
    }

    public function setEnderecoNumero($enderecoNumero) {
        $this->enderecoNumero = $enderecoNumero;
    }

    public function getEnderecoComplemento() {
        return $this->enderecoComplemento;
    }

    public function setEnderecoComplemento($enderecoComplemento) {
        $this->enderecoComplemento = $enderecoComplemento;
    }

    public function getEnderecoBairro() {
        return $this->enderecoBairro;
    }

    public function setEnderecoBairro($enderecoBairro) {
        $this->enderecoBairro = $enderecoBairro;
    }

    public function getEnderecoMunicipio() {
        return $this->enderecoMunicipio;
    }

    public function setEnderecoMunicipio($enderecoMunicipio) {
        $this->enderecoMunicipio = $enderecoMunicipio;
    }

    public function getEnderecoEstado() {
        return $this->enderecoEstado;
    }

    public function setEnderecoEstado($enderecoEstado) {
        $this->enderecoEstado = $enderecoEstado;
    }

    public function getEnderecoCEP() {
        return $this->enderecoCEP;
    }

    public function setEnderecoCEP($enderecoCEP) {
        $this->enderecoCEP = $enderecoCEP;
    }

    public function getDataNascimento() {
        return $this->dataNascimento;
    }

    public function setDataNascimento($dataNascimento) {
        $this->dataNascimento = $dataNascimento;
    }

    public function getNacionalidade() {
        return $this->nacionalidade;
    }

    public function setNacionalidade($nacionalidade) {
        $this->nacionalidade = $nacionalidade;
    }

    public function getNaturalidade() {
        return $this->naturalidade;
    }

    public function setNaturalidade($naturalidade) {
        $this->naturalidade = $naturalidade;
    }

    public function getTelefoneResidencial() {
        return $this->telefoneResidencial;
    }

    public function setTelefoneResidencial($telefoneResidencial) {
        $this->telefoneResidencial = $telefoneResidencial;
    }

    public function getTelefoneComercial() {
        return $this->telefoneComercial;
    }

    public function setTelefoneComercial($telefoneComercial) {
        $this->telefoneComercial = $telefoneComercial;
    }

    public function getTelefoneCelular() {
        return $this->telefoneCelular;
    }

    public function setTelefoneCelular($telefoneCelular) {
        $this->telefoneCelular = $telefoneCelular;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }
        
    public static function inserirPessoa( $nome, $sexo, $enderecoLogradouro, $enderecoNumero,
        $enderecoComplemento, $enderecoBairro, $enderecoMunicipio, $enderecoEstado, $enderecoCEP,
        $dataNascimento, $nacionalidade, $naturalidade, $telefoneResidencial, $telefoneComercial,
        $telefoneCelular, $email, $con=null ) {
        if($con==null) $con = BD::conectar();
        $query = sprintf("INSERT INTO `Pessoa` (`nome`, `sexo`, `enderecoLogradouro`, `enderecoNumero`, " .
            "`enderecoComplemento`, `enderecoBairro`, `enderecoMunicipio`, `enderecoEstado`, " .
            "`enderecoCEP`, `dataNascimento`, `nacionalidade`, `naturalidade`, `telefoneResidencial`, " .
            "`telefoneComercial`, `telefoneCelular`, `email`) " .
            "VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')",
            mysql_real_escape_string($nome),
            mysql_real_escape_string($sexo),
            mysql_real_escape_string($enderecoLogradouro),
            mysql_real_escape_string($enderecoNumero),
            mysql_real_escape_string($enderecoComplemento),
            mysql_real_escape_string($enderecoBairro),
            mysql_real_escape_string($enderecoMunicipio),
            mysql_real_escape_string($enderecoEstado),
            mysql_real_escape_string($enderecoCEP),
            mysql_real_escape_string($dataNascimento),
            mysql_real_escape_string($nacionalidade),
            mysql_real_escape_string($naturalidade),
            mysql_real_escape_string($telefoneResidencial),
            mysql_real_escape_string($telefoneComercial),
            mysql_real_escape_string($telefoneCelular),
            mysql_real_escape_string($email) );
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Pessoa.");
        }
        return mysql_insert_id();
    }

    public static function atualizar( $idPessoa, $nome, $sexo, $enderecoLogradouro, $enderecoNumero,
        $enderecoComplemento, $enderecoBairro, $enderecoMunicipio, $enderecoEstado, $enderecoCEP,
        $dataNascimento, $nacionalidade, $naturalidade, $telefoneResidencial, $telefoneComercial,
        $telefoneCelular, $email, $con=null ) {
        if($con==null) $con = BD::conectar();
        $query = sprintf("UPDATE Pessoa set
            nome='%s',
            sexo='%s',
            enderecoLogradouro='%s',
            enderecoNumero='%s',
            enderecoComplemento='%s',
            enderecoBairro='%s',
            enderecoMunicipio='%s',
            enderecoEstado='%s',
            enderecoCEP='%s',
            dataNascimento='%s',
            nacionalidade='%s',
            naturalidade='%s',
            telefoneResidencial='%s',
            telefoneComercial='%s',
            telefoneCelular='%s',
            email='%s'
            where idPessoa=%d",
            mysql_real_escape_string($nome),
            mysql_real_escape_string($sexo),
            mysql_real_escape_string($enderecoLogradouro),
            mysql_real_escape_string($enderecoNumero),
            mysql_real_escape_string($enderecoComplemento),
            mysql_real_escape_string($enderecoBairro),
            mysql_real_escape_string($enderecoMunicipio),
            mysql_real_escape_string($enderecoEstado),
            mysql_real_escape_string($enderecoCEP),
            mysql_real_escape_string($dataNascimento),
            mysql_real_escape_string($nacionalidade),
            mysql_real_escape_string($naturalidade),
            mysql_real_escape_string($telefoneResidencial),
            mysql_real_escape_string($telefoneComercial),
            mysql_real_escape_string($telefoneCelular),
            mysql_real_escape_string($email),
            mysql_real_escape_string($idPessoa) );
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao atualizar na tabela Pessoa.");
        }
    }

    /**
     * Retorna um objeto de Pessoa dado seu id.
     * @param type $id
     * @return Pessoa 
     */
    public static function obterPessoaPorId($id) {
        $con = BD::conectar();
        $query = sprintf("select * from Pessoa p where p.idPessoa=%d",
                $id);
        $result=mysql_query($query, $con);
        if(!$result || mysql_num_rows($result)!=1) 
            trigger_error("Erro ao consultar a tabela Pessoa.",E_USER_ERROR);
        $registro = mysql_fetch_array($result);
        $pessoa = new Pessoa();
        $pessoa->setIdPessoa($registro['idPessoa']);
        $pessoa->setNome($registro['nome']);
        $pessoa->setSexo($registro['sexo']);
        $pessoa->setEnderecoLogradouro($registro['enderecoLogradouro']);
        $pessoa->setEnderecoNumero($registro['enderecoNumero']);
        $pessoa->setEnderecoComplemento($registro['enderecoComplemento']);
        $pessoa->setEnderecoBairro($registro['enderecoBairro']);
        $pessoa->setEnderecoMunicipio($registro['enderecoMunicipio']);
        $pessoa->setEnderecoEstado($registro['enderecoEstado']);
        $pessoa->setEnderecoCEP($registro['enderecoCEP']);
        $pessoa->setDataNascimento($registro['dataNascimento']);
        $pessoa->setNacionalidade($registro['nacionalidade']);
        $pessoa->setNaturalidade($registro['naturalidade']);
        $pessoa->setTelefoneResidencial($registro['telefoneResidencial']);
        $pessoa->setTelefoneComercial($registro['telefoneComercial']);
        $pessoa->setTelefoneCelular($registro['telefoneCelular']);
        $pessoa->setEmail($registro['email']);
        return $pessoa;
    }

    /***
     * Retorna a lista de objetos de Pessoa por nome
     *
     * @result coleção de objetos: Pessoa
     *
     * a razão de não utilizar o lista_pessoa() é que o mysql_real_escape_string
     * gera problema com o % do parametro LIKE
     **/
    public static function obterPessoasPorNome( $nome ) {
        $con = BD::conectar();
        $query = sprintf("SELECT * FROM Pessoa WHERE nome like '%s%%'",
            mysql_real_escape_string($nome));
        $result = mysql_query( $query, $con);
        $pessoas = array();
        while( $rs = mysql_fetch_array($result)) 
        {
           $pessoa = new Pessoa();
           $pessoa->setIdPessoa($rs['idPessoa']);
           $pessoa->setNome($rs['nome']);
           $pessoa->setSexo($rs['sexo']);
           $pessoa->setEnderecoLogradouro($rs['enderecoLogradouro']);
           $pessoa->setEnderecoNumero($rs['enderecoNumero']);
           $pessoa->setEnderecoComplemento($rs['enderecoComplemento']);
           $pessoa->setEnderecoBairro($rs['enderecoBairro']);
           $pessoa->setEnderecoMunicipio($rs['enderecoMunicipio']);
           $pessoa->setEnderecoEstado($rs['enderecoEstado']);
           $pessoa->setEnderecoCEP($rs['enderecoCEP']);
           $pessoa->setDataNascimento($rs['dataNascimento']);
           $pessoa->setNacionalidade($rs['nacionalidade']);
           $pessoa->setNaturalidade($rs['naturalidade']);
           $pessoa->setTelefoneResidencial($rs['telefoneResidencial']);
           $pessoa->setTelefoneComercial($rs['telefoneComercial']);
           $pessoa->setTelefoneCelular($rs['telefoneCelular']);
           $pessoa->setEmail($rs['email']);
           array_push($pessoas, $pessoa);
        }
        return $pessoas;
    }
}