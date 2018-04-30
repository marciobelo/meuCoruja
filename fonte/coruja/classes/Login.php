<?php
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Funcao.php";
require_once "$BASE_DIR/classes/Permite.php";
require_once "$BASE_DIR/classes/GrupoFuncao.php";

class Login 
{
    private $nomeAcesso;
    private $bloqueado;
    private $motivoBloqueio;
    private $pessoa;
    private $foto;
    
    private $permissoes; // array of Permite
    private $gruposFuncao;

    private function __construct($nomeAcesso, $bloqueado, $motivoBloqueio, Pessoa $pessoa) 
    {
        $this->nomeAcesso = $nomeAcesso;
        $this->bloqueado = $bloqueado;
        $this->motivoBloqueio = $motivoBloqueio;
        $this->pessoa = $pessoa;
    }
    
    public function getNomeAcesso() 
    {
        return $this->nomeAcesso;
    }

    public function setNomeAcesso($nomeAcesso) 
    {
        $this->nomeAcesso = $nomeAcesso;
    }

    public function isBloqueado() 
    {
        return $this->bloqueado;
    }

    public function setBloqueado($bloqueado) 
    {
        $this->bloqueado = $bloqueado;
    }

    public function getMotivoBloqueio() {
        return $this->motivoBloqueio;
    }

    public function setMotivoBloqueio($motivoBloqueio) {
        $this->motivoBloqueio = $motivoBloqueio;
    }

    public function getPessoa() 
    {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa) 
    {
        $this->pessoa = $pessoa;
    }

    public function getPermissoes() 
	{
        return $this->permissoes;
    }
    
    public function getGruposFuncao() 
	{
        return $this->gruposFuncao;
    }

    /**
     * Obtem um objeto de Login pelo idPessoa
     * @param integer $idPessoa
     * @return Login Objeto de login, ou null, se não encontrar
     */
    public static function obterLoginPorIdPessoa( $idPessoa) 
    {
        $con=BD::conectar();
        $query=sprintf("select * from Login where idPessoa=%d",$idPessoa);
        $result=mysql_query($query, $con);
        $linha=mysql_fetch_array($result);
        if($linha) {
            $login = new Login( $linha["nomeAcesso"],
                        $linha["bloqueado"] === "SIM",
                        $linha["motivoBloqueio"],
                        Pessoa::obterPessoaPorId( $idPessoa));
            $login->permissoes = Permite::obterPermissoesPorIdPessoa($idPessoa);
            $login->gruposFuncao = GrupoFuncao::obterGruposDeFuncaoPorPermissoes($login->permissoes);
            return $login;
        } else {
            return null;
        }
    }
    
    public static function obterLoginsPorNome($nome) {
        $con   = BD::conectar();
        $query = sprintf("SELECT p.idPessoa
                          FROM Pessoa p 
                          INNER JOIN Login l ON p.idPessoa = l.idPessoa  
                          WHERE p.idPessoa NOT IN (select idPessoa from Aluno) 
                          AND p.nome like '%s%%'", mysql_escape_string($nome));

        $result = mysql_query($query, $con);

        if (mysql_affected_rows() > 0) {
            $logins = array();
            while ($row = mysql_fetch_assoc($result)) {
                $logins[] = Login::obterLoginPorIdPessoa($row['idPessoa']);
            }
            return $logins;
        } else {
            return null;
        }
    }

    public static function obterLoginPorNomeAcesso($nomeAcesso) {
        $con   = BD::conectar();
        $query = sprintf("SELECT p.idPessoa
                          FROM Pessoa p 
                          INNER JOIN Login l ON p.idPessoa = l.idPessoa  
                          WHERE p.idPessoa NOT IN (select idPessoa from Aluno) 
                          AND l.nomeAcesso = '%s'", mysql_escape_string($nomeAcesso));
        
        $result = mysql_query($query, $con);
        $row    = mysql_fetch_assoc($result);
        
        $login = null;
        if ($row) {
            $login = Login::obterLoginPorIdPessoa($row['idPessoa']);
        }
        return $login;
    }

    /**
     * Altera a foto de um login pelo id da pessoa.
     * @param <type> $idPessoa
     * @param <type> $foto
     * @param <type> $con 
     */
    public static function atualizarFoto($idPessoa, $foto, $con) {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update Login set foto='%s' where idPessoa=%d",
                addslashes($foto),
                $idPessoa);
        mysql_query($query, $con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Erro ao alterar foto do Login.");
        }
    }

    /**
     * Cria um novo login
     * @param <type> $idPessoa
     * @param <type> $nomeAcesso
     * @param <type> $senha senha sugerida pelo sistema
     * @param <type> $con opcional, conexão externa usada na transação
     */
    public static function criarLogin($idPessoa, $nomeAcesso, $senha, $con=null) {
        if($con==null) $con=BD::conectar();
        $query=sprintf("insert into Login (idPessoa,nomeAcesso,senha)
            values (%d,'%s','%s')",
                $idPessoa,
                mysql_real_escape_string($nomeAcesso),
                md5(mysql_real_escape_string($senha)));
        mysql_query($query, $con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Erro ao inserir novo Login.");
        }
    }

    public static function alterarSenhaLogin($idPessoa,$nomeAcesso,$novaSenha,$con=null) {
        if($con==null) $con=BD::conectar();
        $query=sprintf("update Login set senha='%s',bloqueado='NÃO' where idPessoa=%d and nomeAcesso='%s'",
            md5(mysql_real_escape_string($novaSenha)),
            $idPessoa,
            mysql_real_escape_string($nomeAcesso));
        mysql_query($query, $con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Erro ao resetar senha do Login.");
        }
    }

    public static function incluirLogAdministrador($idCasoUso, $descricao, $con) {
        if($con==null) $con = BD::conectar();
        $query=sprintf("insert into Log (idPessoa,idCasoUso,descricao) values (%d,'%s','%s')",
                Config::ADMINISTRADOR_ID_PESSOA,
                $idCasoUso,
                mysql_escape_string($descricao));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Log.");
        }
    }

    public static function recuperarSenha($idPessoa, $nomeAcesso) {
        $con = BD::conectar();
        $senha = Util::gerarSenhaAleatoria();
        $query = sprintf("update Login set senha='%s'
            where idPessoa = %d and nomeAcesso='%s'",
                mysql_real_escape_string(md5($senha)),
                $idPessoa,
                mysql_real_escape_string($nomeAcesso));
        try {
            mysql_query("BEGIN", $con); // Inicia transação

            // Atualiza senha
            $result=mysql_query($query,$con);
            if(!$result) {
                throw new Exception("Não foi possível resetar a senha do usuário.");
            }

            $pessoa = Pessoa::obterPessoaPorId($idPessoa);
            $email = $pessoa->getEmail();
            $assunto = "Senha resetada";
            $nome = $pessoa->getNome();
            $texto = "Prezado(a) $nome, \n\n
                Sua conta $nomeAcesso no sistema Coruja foi resetada.\n
                Sua nova senha é $senha\n
                É fortemente recomendado que você a altere o mais rápido possível. 
                Caso não tenha sido você a solicitar essa operação, 
                comunique imediatamente à instituição.";
            Util::enviarEmail($email,$assunto,$texto);

            mysql_query("COMMIT", $con);
        } catch (Exception $ex) {
            mysql_query("ROLLBACK", $con);
            throw new Exception($ex->getMessage());
        }
    }
    
    public function getFoto() {
        $con = BD::conectar();
        $query=sprintf("select l.foto from Login l where l.nomeAcesso='%s'",
        mysql_real_escape_string( $this->nomeAcesso ));
        $result=mysql_query($query,$con);
        $foto=mysql_result($result,0,0);
        return $foto;
    }
    
    /**
    * Bloqueia um login dado o nome de acesso e um motivo
    */
    public static function bloquear( $nomeAcesso, $motivoBloqueio) 
    {
        $con = BD::conectar();
        $query = sprintf("update Login set bloqueado='SIM', motivoBloqueio='%s' where " .
                "nomeAcesso='%s'",
                mysql_real_escape_string($motivoBloqueio),
                mysql_real_escape_string($nomeAcesso) );
        $result = mysql_query( $query, $con);
        if( !$result ) 
        {
            throw new Exception("Erro ao bloquear login de usuario");
        }
    }

    public function desbloquear( $nomeAcesso, $con = null) 
    {
        if( $con == null)
        {
            $con = BD::conectar();
        }
        $query = sprintf("update Login set bloqueado='NÃO', motivoBloqueio=null where " .
                "nomeAcesso='%s'",
                mysql_real_escape_string($nomeAcesso) );
        $result = mysql_query( $query, $con);
        if( !$result ) 
        {
            throw new Exception("Erro ao bloquear login de usuario");
        }        
    }
    
    public function ObterHashSenha($nomeAcesso){
        $con   = BD::conectar();
        $query = sprintf("SELECT senha
                          FROM Login 
                          WHERE nomeAcesso = '%s'", mysql_escape_string($nomeAcesso));
        
        $result = mysql_query($query, $con);
        $senha =mysql_result($result,0,0);
        return $senha;
    }
}