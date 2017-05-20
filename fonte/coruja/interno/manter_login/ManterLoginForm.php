<?php

class ManterLoginForm {

    private $idPessoa;
    private $nome; // Nome da Pessoa
    private $nomeAcesso;
    private $senha;
    private $bloqueado; // boolean
    private $motivoBloqueio;
    private $foto;

    private function __construct() {
        
    }
    
    public static function criarPorLogin( Login $login)
    {
        $obj = new ManterLoginForm();
        $obj->idPessoa = $login->getPessoa()->getIdPessoa();
        $obj->nome = $login->getPessoa()->getNome();
        $obj->nomeAcesso = $login->getNomeAcesso();
        $obj->bloqueado = $login->isBloqueado();
        $obj->motivoBloqueio = $login->getMotivoBloqueio();
        $obj->foto = $login->getFoto();
        return $obj;
    }
    
    public static function criarPorPessoa( Pessoa $pessoa)
    {
        $obj = new ManterLoginForm();
        $obj->idPessoa = $pessoa->getIdPessoa();
        $obj->nome = $pessoa->getNome();
        return $obj;
    }

    public static function criarPeloRequest()
    {
        $obj = new ManterLoginForm();
        $obj->idPessoa = $_REQUEST["idPessoa"];
        $obj->nome = $_REQUEST["nome"];
        $obj->nomeAcesso = $_REQUEST["nomeAcesso"];
        $obj->senha = $_REQUEST["senha"];
        $obj->bloqueado = $_REQUEST["bloqueado"];
        return $obj;
    }

    public function getNome() 
    {
        return $this->nome;
    }

    public function setNome($nome) 
    {
        $this->nome = $nome;
    }

    public function getNomeAcesso() 
    {
        return $this->nomeAcesso;
    }

    public function setNomeAcesso($nomeAcesso) 
    {
        $this->nomeAcesso = $nomeAcesso;
    }

    public function getSenha() 
    {
        return $this->senha;
    }

    public function setSenha($senha) 
    {
        $this->senha = $senha;
    }

    public function isBloqueado() 
    {
        return $this->bloqueado;
    }

    public function setBloqueado($bloqueado) 
    {
        $this->bloqueado = $bloqueado;
    }
    
    public function getMotivoBloqueio() 
    {
        return $this->motivoBloqueio;
    }

    public function setMotivoBloqueio($motivoBloqueio) 
    {
        $this->motivoBloqueio = $motivoBloqueio;
    }
    
    public function getFoto() 
    {
        return $this->foto;
    }

    public function setFoto($foto) 
    {
        $this->foto = $foto;
    }

    public function getIdPessoa() 
    {
        return $this->idPessoa;
    }

    public function setIdPessoa($idPessoa) 
    {
        $this->idPessoa = $idPessoa;
    }
}