<?php
class BD {

    /**
     * Obtem uma conexão do pool de conexões.
     * @return Connection conexão com o banco de dados
     */
    public static function conectar() 
    {
        $con=@mysql_connect( Config::BANCO_SERVIDOR . ":" . Config::BANCO_PORTA, 
                Config::BANCO_USUARIO, Config::BANCO_SENHA);
        $db=mysql_select_db( Config::BANCO_NOME, $con);
        if($con) 
        { // Seleciona para uso do banco Coruja
            if(!$db) {
                trigger_error("Não foi possível selecionar o banco de dados.",
                        E_USER_ERROR);
            }
        } else {
            trigger_error("Não foi possível conectar ao servidor de banco de dados.", 
                    E_USER_ERROR);
        }
        return $con;
    }
    
    public static function conectarPDO()
    {
        $pdo = new PDO( "mysql:host=" . Config::BANCO_SERVIDOR . ";dbname=" . 
                Config::BANCO_NOME . ";charset=utf8mb4", 
                Config::BANCO_USUARIO, Config::BANCO_SENHA);
        $pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    }
}