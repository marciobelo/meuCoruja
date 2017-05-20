<?php
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Login.php";
require_once "$BASE_DIR/classes/Util.php";

class Usuario {
    
    const ALUNO = "ALUNO";
    const PROFESSOR = "PROFESSOR";
    const ADMINISTRADOR = "ADMINISTRADOR";

    private $nomeAcesso;
    private $idPessoa;
    private $perfil;
    private $validouLog;
    private $foto;

    /**
     * Recupera o usuario por nome de acesso
     * @param String $nomeAcesso nome de login do usuário no sistema
     */
    public static function obterUsuarioPorNomeAcesso($nomeAcesso) {
        $con = BD::conectar();
        $query=sprintf("select L.nomeAcesso from Pessoa P
            inner join Login L on P.idPessoa = L.idPessoa and 
            L.nomeAcesso='%s'",
                mysql_real_escape_string($nomeAcesso));
        $result=mysql_query($query,$con);
        if(mysql_num_rows($result)==1) {
            $usuario = new Usuario(mysql_result($result, 0, 0));
            return $usuario;
        }
        throw new Exception( sprintf("Usuário com nome de acesso %s "
                . "não existe.", $nomeAcesso) );
    }

    /**
    * Construtor
    */
    public function __construct($nomeAcesso) {
        $this->nomeAcesso=$nomeAcesso;
        $this->idPessoa=$this->obterIdPessoa($nomeAcesso);
        $this->validouLog=false;
        $this->foto = $this->obterFoto($nomeAcesso);
    }

    public function getNomeAcesso() {
        return $this->nomeAcesso;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function getValidouLog() {
        return $this->validouLog;
    }
    
    public function setValidouLog($validou)
    {
        $this->validouLog = $validou;
    }
    
    /**
    * Obtem todos os registros de Logs deste usuário ainda não conferidos
    */
    public function getLogsNaoConferidos() {
        $con = BD::conectar();
        $query=sprintf("select Log.idCasoUso as idCasoUso,Log.dataHora as dataHora," .
                "Log.descricao as descricaoLog,Funcao.descricao as descricaoFuncao," .
                "Funcao.critico as critico from Login inner join Pessoa on " .
                "Login.idPessoa=Pessoa.idPessoa inner join Log on " .
                "Pessoa.idPessoa=Log.idPessoa inner join Funcao on " .
                "Log.idCasoUso=Funcao.idCasoUso where Login.nomeAcesso='%s' ".
                "and Log.confere='NÃO' " .
                "order by Funcao.critico DESC,Log.dataHora DESC",$this->nomeAcesso);
        $result=mysql_query($query,$con);
        $logs=array();
        while( $linha = mysql_fetch_object($result) ) {
                $logs[] = $linha;
        }

        // Caso todos os registros de logs já esteja conferidos, seja o indicador para true
        $this->validouLog=true;

        return $logs;
    }
    
    /**
     * Retorna a quantidade de avisos que ainda não foram
     * lidos (dado aceite) por este usuário.
     */
    public function obterQtdeAvisosNaoLidos() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MensagemPessoa 
            where idPessoa = %d and
            lido='NÃO'", $this->getIdPessoa());
        $result = mysql_query($query, $con);
        return mysql_result($result, 0, 0);
    }

    /**
    * Registra o confere por parte do usuário sobre um registro de Log
    */
    public function aceitarLog($idCasoUso,$idDataHora) {
        $con = BD::conectar();
        $query=sprintf("update Log set confere='SIM' where idPessoa=%d " .
                " and idCasoUso='%s' and dataHora='%s'",
                $this->idPessoa,$idCasoUso,$idDataHora);
        $result=mysql_query($query,$con);
    }

    private function obterIdPessoa($nomeAcesso) {
        $con = BD::conectar();
        $query=sprintf("select Pessoa.idPessoa from Login inner join Pessoa on " .
                "Login.idPessoa=Pessoa.idPessoa where Login.nomeAcesso='%s'",$nomeAcesso);
        $result=mysql_query($query,$con);
        $idPessoa=mysql_result($result,0,0);
        return $idPessoa;
    }
	
    private function obterFoto($nomeAcesso) {
        $con = BD::conectar();
        $query=sprintf("select l.foto from Login l
            where l.nomeAcesso='%s'",
        mysql_real_escape_string($nomeAcesso));
        $result=mysql_query($query,$con);
        $foto=mysql_result($result,0,0);
        return $foto;
    }

    public function getIdPessoa() {
        return $this->idPessoa;
    }

    public function getPerfil() {
        return $this->perfil;
    }
    
    /**
    * Retorna um booleano indicado se, para um dado código de caso de uso,
    * o usuário tem ou não permissão de uso.
    */
    public function temPermissao($idCasoUso) {
        $con = BD::conectar();
        $query=sprintf("select count(*) from Permite where idPessoa=%d " .
                " and idCasoUso='%s'",
                $this->idPessoa,$idCasoUso);
        $result=mysql_query($query,$con);
        $tem=mysql_result($result,0,0);
        if($tem==1) return true;
        else return false;
    }

    /**
    * Registra o confere por parte do usuário sobre um registro de Log
    */
    public function incluirLog($idCasoUso,$descricao,$con=null) {
        if($con==null) $con = BD::conectar();
        $query=sprintf("insert into Log (idPessoa,idCasoUso,descricao) values (%d,'%s','%s')",
                $this->idPessoa,$idCasoUso,
                mysql_escape_string($descricao));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Log.");
        }
    }

    public function trocarSenha($senhaAtual,$novaSenha) {
        $con = BD::conectar();
        $query=sprintf("update Login set senha='%s'
            where senha='%s' and idPessoa=%d and nomeAcesso='%s'",
        mysql_escape_string(md5($novaSenha)),
        mysql_escape_string(md5($senhaAtual)),
        mysql_escape_string($this->getIdPessoa()),
        mysql_escape_string($this->getNomeAcesso()));
        $result=mysql_query($query,$con);
        if( (!$result) || mysql_affected_rows()==0 ) {
            throw new Exception("Não foi possível alterar a senha. Verifique se a senha atual está correta.");
        }
    }

    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é aluno ou não. Confere se ele tem matrícula
     * e não tem nenhum permissão.
     */
    public function isAluno() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MatriculaAluno ma
               where ma.idPessoa=%d and not exists (select * from Permite pe
                where pe.idPessoa=ma.idPessoa)",
            $this->getIdPessoa());
        $result = mysql_query($query,$con);
        if(mysql_result($result, 0, 0) >= 1) return true;
        else return false;
    }

    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é professor ou não. Confere se ele tem matrícula vigente.
     */
    public function isProfessor() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MatriculaProfessor mp
               where mp.idPessoa = %d and (dataEncerramento is NULL or dataEncerramento >= CURDATE())",
            $this->getIdPessoa());
        $result = mysql_query($query,$con);
        if(mysql_result($result, 0, 0) >= 1) return true;
        else return false;
    }

    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é administrador ou não.
     */
    public function isAdministrador() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from Permite permite
               where permite.idPessoa = %d",
            $this->getIdPessoa());
        $result = mysql_query($query,$con);
        if(mysql_result($result, 0, 0) >= 1) return true;
        else return false;
    }
    
    /**
     * Obtém o email do usuario
     * @return string 
     */
    public function getEmail() {
        $con = BD::conectar();
        $query = sprintf("select email from Pessoa where idPessoa=%d",
                $this->idPessoa);
        $result = mysql_query($query,$con);
        if(mysql_num_rows($result) != 1) return null;
        else {
            return mysql_result($result, 0, 0);
        }        
    }

    /**
    * Realiza o carregamento do objeto usuário e suas permissões
    */
    public static function autenticar( $nomeAcesso , $senha, $perfil) {
        $con = BD::conectar();
        $senha_md5 = md5($senha);
        $query = sprintf("select count(*) from Login where senha='%s' ".
                "and nomeAcesso='%s'",
            mysql_real_escape_string($senha_md5),
            mysql_real_escape_string($nomeAcesso) );
        $result = mysql_query($query,$con);
        $loginExiste = mysql_result($result,0,0) == 1;
        
        if( $loginExiste ) 
        {
            $queryBloqueado = sprintf("select bloqueado, motivoBloqueio "
                    . "from Login where nomeAcesso='%s'",
                    mysql_real_escape_string( $nomeAcesso) );
            $resultBloqueado = mysql_query( $queryBloqueado,$con);
            $bloqueado = mysql_result( $resultBloqueado,0,0) === "SIM";
            if( $bloqueado )
            {
                $motivoBloqueio = mysql_result( $resultBloqueado,0,1);
                throw new Exception( sprintf("Login %s bloqueado. Motivo: %s", 
                        $nomeAcesso,
                        $motivoBloqueio) );
            }

            $usuario = new Usuario($nomeAcesso);
            $temPerfil = $usuario->mudarPerfil( $perfil );
            if( $temPerfil )
            {
                $_SESSION["usuario"] = $usuario;
                unset($_SESSION[$nomeAcesso]["tentativa"]);
                return true;
            }
        }
        else 
        {
            $tentativa = $_SESSION[$nomeAcesso]["tentativa"];
            if(isset($tentativa) && is_numeric($tentativa) ) 
            {
                $tentativa++;
            } 
            else 
            {
                $tentativa=1;
            }
            $_SESSION[$nomeAcesso]["tentativa"] = $tentativa;
        }
    }

    /**
     * Muda o perfil do usuário
     * @param Strin $perfil
     * @return boolean indica se mudou com sucesso ou não
     */
    private function mudarPerfil( $perfil ) {
        $mudou = false;
        if( $perfil == Usuario::ALUNO && $this->isAluno() ) {
            $this->perfil = Usuario::ALUNO;
            $mudou = true;
        } else if( $perfil == Usuario::PROFESSOR && $this->isProfessor() ) {
            $this->perfil = Usuario::PROFESSOR;
            $mudou = true;
        } else if( $perfil == Usuario::ADMINISTRADOR && $this->isAdministrador() ) {
            $this->perfil = Usuario::ADMINISTRADOR;
            $mudou = true;
        }
        return $mudou;
    }

    /**
     * Retorna um objeto da classe Usuario dado seu id
     * @param Integer $idPessoa
     * @return objeto de Usuario
     */
    public static function obterUsuarioPorIdPessoa($idPessoa) {
        $con = BD::conectar();
        $query=sprintf("select l.nomeAcesso from Login l where l.idPessoa=%d",
                $idPessoa);
        $result=mysql_query($query,$con);
        if(mysql_num_rows($result)!=1) return null;
        else {
            $nomeAcesso=mysql_result($result,0,0);
            return new Usuario($nomeAcesso);
        }
    }

    public function isBloqueado() {
        $con = BD::conectar();
        $query = sprintf("select bloqueado from Login where idPessoa=%d",
            $this->getIdPessoa() );
        $result = mysql_query($query, $con);
        if(mysql_result($result, 0, 0) == "SIM") return true;
        else return false;
    }
}
?>