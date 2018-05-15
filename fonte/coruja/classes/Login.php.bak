<?php
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Funcao.php";
require_once "$BASE_DIR/classes/Permite.php";
require_once "$BASE_DIR/classes/GrupoFuncao.php";
require_once "$BASE_DIR/classes/Log.php";

class Login 
{
    const ALUNO = "ALUNO";
    const PROFESSOR = "PROFESSOR";
    const ADMINISTRADOR = "ADMINISTRADOR";    
    
    private $nomeAcesso;
    private $bloqueado;
    private $motivoBloqueio;
    private $pessoa;
    private $foto;
    private $validouLog;
    private $perfil;

    private $permissoes; // array of Permite
    private $gruposFuncao;

    private function __construct($nomeAcesso, $bloqueado, $motivoBloqueio, 
            Pessoa $pessoa) 
    {
        $this->nomeAcesso = $nomeAcesso;
        $this->bloqueado = $bloqueado;
        $this->motivoBloqueio = $motivoBloqueio;
        $this->pessoa = $pessoa;
        $this->foto = Login::obterFoto( $nomeAcesso);
        $this->validouLog = false;
    }

    private static function obterFoto( $nomeAcesso) 
    {
        $con = BD::conectar();
        $query=sprintf("select l.foto from Login l
            where l.nomeAcesso='%s'",
        mysql_real_escape_string( $nomeAcesso));
        $result=mysql_query($query,$con);
        $foto=mysql_result($result,0,0);
        return $foto;
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

    public function getValidouLog() 
    {
        return $this->validouLog;
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
                        Pessoa::obterPessoaPorId( $idPessoa) );
            $login->permissoes = Permite::obterPermissoesPorIdPessoa($idPessoa);
            $login->gruposFuncao = GrupoFuncao::obterGruposDeFuncaoPorPermissoes($login->permissoes);
            return $login;
        } 
        else 
        {
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

    public static function obterLoginPorNomeAcesso($nomeAcesso) 
    {
        $con   = BD::conectar();
        $query = sprintf("SELECT p.idPessoa
                          FROM Pessoa p 
                          INNER JOIN Login l ON p.idPessoa = l.idPessoa  
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
    public static function atualizarFoto($idPessoa, $foto, $con) 
    {
        if($con == null) { $con = BD::conectar(); }
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
    public static function criarLogin($idPessoa, $nomeAcesso, $senha, $con=null) 
    {
        if($con == null) { $con=BD::conectar(); }
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

    public static function alterarSenhaLogin($idPessoa,$nomeAcesso,$novaSenha,$con=null) 
    {
        if($con==null) { $con=BD::conectar(); }
        $query=sprintf("update Login set senha='%s',bloqueado='NÃO' where idPessoa=%d and nomeAcesso='%s'",
            md5(mysql_real_escape_string($novaSenha)),
            $idPessoa,
            mysql_real_escape_string($nomeAcesso));
        mysql_query($query, $con);
        if(mysql_affected_rows()!=1) {
            throw new Exception("Erro ao resetar senha do Login.");
        }
    }

    public static function incluirLogAdministrador($idCasoUso, $descricao, $con) 
    {
        if($con==null) { $con = BD::conectar(); }
        $query=sprintf("insert into Log (idPessoa,idCasoUso,descricao) values (%d,'%s','%s')",
                Config::ADMINISTRADOR_ID_PESSOA,
                $idCasoUso,
                mysql_escape_string($descricao));
        $result=mysql_query($query,$con);
        if(!$result) {
            throw new Exception("Erro ao inserir na tabela Log.");
        }
    }

    public static function recuperarSenha($idPessoa, $nomeAcesso) 
    {
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
    
    public function getFoto() 
    {
        return $this->foto;
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

    public function getEmail() 
    {
        return $this->pessoa->getEmail();
    }
    
    /**
     * Retorna a quantidade de avisos que ainda não foram
     * lidos (dado aceite) por este login.
     */
    public function obterQtdeAvisosNaoLidos() 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MensagemPessoa 
            where idPessoa = %d and
            lido='NÃO'", $this->pessoa->getIdPessoa());
        $result = mysql_query($query, $con);
        return mysql_result($result, 0, 0);
    }    
    
    /**
    * Obtem todos os registros de Logs deste usuário ainda não conferidos
    */
    public function getLogsNaoConferidos() 
    {
        $naoConferidos = Log::getLogsNaoConferidos( $this->nomeAcesso);
        if( empty( $naoConferidos))
        {
            $this->validouLog = true;
        }
        return $naoConferidos;
    }

    public function getIdPessoa() 
    {
        return $this->pessoa->getIdPessoa();
    }
    
    /**
    * Retorna um booleano indicado se, para um dado código de caso de uso,
    * o login tem ou não permissão de uso.
    */
    public function temPermissao( $idCasoUso) 
    {
        foreach ($this->permissoes as $permite)
        {
            if( $permite->getFuncao()->getIdCasoUso() === $idCasoUso )
            {
                return true;
            }
        }
        return false;
    }

    /**
    * Registra o confere por parte do usuário sobre um registro de Log
    */
    public function incluirLog($idCasoUso,$descricao,$con=null) 
    {
        return Log::incluirLog( $this->getIdPessoa(), $idCasoUso, $descricao, $con);
    }
    
    public function trocarSenha($senhaAtual,$novaSenha) 
    {
        $con = BD::conectar();
        $query = sprintf("update Login set senha='%s'
            where senha='%s' and idPessoa=%d and nomeAcesso='%s'",
        mysql_escape_string(md5($novaSenha)),
        mysql_escape_string(md5($senhaAtual)),
        mysql_escape_string($this->getIdPessoa()),
        mysql_escape_string($this->getNomeAcesso()));
        $result = mysql_query($query,$con);
        if( (!$result) || mysql_affected_rows()==0 ) 
        {
            throw new Exception("Não foi possível alterar a senha. Verifique se a senha atual está correta.");
        }
    }
    
    /**
     * Retorna um valor lógico indicado se o login
     * autenticado é aluno ou não. Confere se ele tem matrícula
     * e não tem nenhum permissão.
     */
    public function isAluno() 
    {
        return $this->perfil === Login::ALUNO;
    }

    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é administrador ou não.
     */
    public function isAdministrador() 
    {
        return $this->perfil === Login::ADMINISTRADOR;
    }
    
    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é professor ou não. Confere se ele tem matrícula vigente.
     */
    public function isProfessor() 
    {
        return $this->perfil === Login::PROFESSOR;
    }
    
    /**
    * Realiza o carregamento do login
     * 
    */
    /**
     * Cria um objeto Login autenticado
     * @param string $nomeAcesso
     * @param string $senha
     * @param string $perfil
     * @return Login se autenticado
     * @throws Exception se não pôde autenticar
     */
    public static function autenticar( $nomeAcesso , $senha, $perfil) 
    {
        $con = BD::conectar();
        $query = sprintf("select senha, tentativas, bloqueado, motivoBloqueio "
                . "from Login where nomeAcesso='%s'",
            mysql_real_escape_string( $nomeAcesso) );
        $result = mysql_query($query,$con);
        $loginExiste = mysql_num_rows($result) === 1;
        
        if( $loginExiste ) 
        {
            $tentativas = mysql_result( $result,0,1);
            // confere nao senha
            if( mysql_result($result, 0, 0) !== md5( $senha))
            {
                if( $tentativas >= 3  )
                {
                    Login::bloquearLogin( $nomeAcesso, $con);
                    Login::registrarLoginErro("Login existente "
                        . "$nomeAcesso tentou autenticar com perfil $perfil "
                            . "e falhou auntenticação mais do que 3 vezes. "
                            . "Login foi bloqueado.", $con);
                    throw new Exception("Conta bloqueada. Procure a secretaria.");
                }
                else
                {
                    Login::incrementarErrosAutenticacao( $nomeAcesso, $con);
                    Login::registrarLoginErro("Login existente "
                        . "$nomeAcesso tentou autenticar com perfil $perfil "
                            . "e falhou a senha. Quantidade de erros foi "
                            . "incrementada.", $con);
                    Login::lancarErroGenericoAutenticacao();
                }
            }
            
            $bloqueado = mysql_result( $result,0,2) === "SIM";
            if( $bloqueado )
            {
                Login::registrarLoginErro("Login existente "
                    . "$nomeAcesso tentou autenticar com perfil $perfil "
                        . "mas está bloqueado.", $con);
                $motivoBloqueio = mysql_result( $result,0,3);
                throw new Exception( sprintf("Conta %s bloqueada. Motivo: %s", 
                        $nomeAcesso,
                        $motivoBloqueio) );
            }
            else 
            {
                $login = Login::obterLoginPorNomeAcesso( $nomeAcesso);
                $mudouPerfil = $login->mudarPerfil( $perfil);
                if( !$mudouPerfil)
                {
                    Login::registrarLoginErro("Login existente "
                        . "$nomeAcesso tentou autenticar com perfil $perfil "
                        . "mesmo não o possuindo.", $con);
                    Login::incrementarErrosAutenticacao( $nomeAcesso, $con);
                    Login::lancarErroGenericoAutenticacao();
                }
                else
                {
                    if( $tentativas > 0) // reseta quantidade de tentativas
                    {
                        Login::resetarTentativas( $nomeAcesso, $con);
                    }
                }
                return $login;
            }
        }
        else 
        {
            Login::registrarLoginErro("Login inexistente "
                    . "$nomeAcesso tentou autenticar com perfil $perfil", $con);
            Login::lancarErroGenericoAutenticacao();
        }
    }

    private static function incrementarErrosAutenticacao( $nomeAcesso, $con) 
    {
        $updateQtdeErros = sprintf("update Login set "
                . "tentativas=tentativas+1 "
                . "where nomeAcesso='%s'", 
                $nomeAcesso);
        mysql_query( $updateQtdeErros, $con);
    }    
    
    private static function lancarErroGenericoAutenticacao()
    {
        throw new Exception("Nome acesso, senha e ou perfil não conferem. "
                . "Sua conta será bloqueada após 3 erros.");
    }

    private static function registrarLoginErro( $msg, $con)
    {
        $updateLoginErro = sprintf("insert LoginErro (texto) values ('%s')", 
                mysql_real_escape_string($msg));
        mysql_query( $updateLoginErro, $con);        
    }
    
    private static function resetarTentativas($nomeAcesso, $con) 
    {
        $updateResetaTentativas = sprintf("update Login set "
                . "tentativas=0 where nomeAcesso='%s'", 
                $nomeAcesso);
        mysql_query( $updateResetaTentativas, $con);
    }

    private static function bloquearLogin($nomeAcesso, $con) 
    {
        // bloquear Login
        $updateBloquear = sprintf("update Login set bloqueado='SIM',"
                . "motivoBloqueio='Errou a senha "
                . "mais do que 3 vezes'"
                . ", tentativas=tentativas+1 where nomeAcesso='%s'", 
                $nomeAcesso);
        mysql_query( $updateBloquear, $con);
    }
    
    public function getPerfil() 
    {
        return $this->perfil;
    }
    
    /**
     * Muda o perfil do login
     * @param String $perfil
     * @return boolean indica se mudou com sucesso ou não
     */
    private function mudarPerfil( $perfil ) 
    {
        if( $perfil === Login::ALUNO && $this->isPerfilAluno() ) 
        {
            $this->perfil = Login::ALUNO;
            return true;
        } 
        else if( $perfil === Login::PROFESSOR && $this->isPerfilProfessor() ) 
        {
            $this->perfil = Login::PROFESSOR;
            return true;
        } 
        else if( $perfil === Login::ADMINISTRADOR && $this->isPerfilAdministrador() ) 
        {
            $this->perfil = Login::ADMINISTRADOR;
            return true;
        }
        return false;
    }

    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é aluno ou não. Confere se ele tem matrícula
     * e não tem nenhum permissão.
     */
    private function isPerfilAluno() {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MatriculaAluno ma
               where ma.idPessoa=%d and not exists (select * from Permite pe
                where pe.idPessoa=ma.idPessoa)",
            $this->pessoa->getIdPessoa());
        $result = mysql_query($query,$con);
        if(mysql_result($result, 0, 0) >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é professor ou não. Confere se ele tem matrícula vigente.
     */
    private function isPerfilProfessor() 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from MatriculaProfessor mp
               where mp.idPessoa = %d and (dataEncerramento is NULL or dataEncerramento >= CURDATE())",
            $this->pessoa->getIdPessoa());
        $result = mysql_query($query,$con);
        if(mysql_result($result, 0, 0) >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Retorna um valor lógico indicado se o usuário
     * autenticado é administrador ou não.
     */
    private function isPerfilAdministrador() 
    {
        $con = BD::conectar();
        $query = sprintf("select count(*) from Permite permite
               where permite.idPessoa = %d",
            $this->pessoa->getIdPessoa());
        $result = mysql_query($query,$con);
        if(mysql_result($result, 0, 0) >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
    * Registra o confere por parte do usuário sobre um registro de Log
    */
    public function aceitarLog( $idCasoUso, $dataHora) 
    {
        $con = BD::conectar();
        $query = sprintf("update Log set confere='SIM' where idPessoa=%d " .
                " and idCasoUso='%s' and dataHora='%s'",
                $this->pessoa->getIdPessoa(), $idCasoUso, $dataHora);
        if( !mysql_query( $query, $con))
        {
            throw new Exception("Erro ao atualizar conferencia de log");
        }
    }
}