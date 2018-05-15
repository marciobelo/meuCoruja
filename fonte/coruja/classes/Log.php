<?php
class Log 
{
    private $idPessoa;
    private $nome;
    private $nomeAcesso;
    private $idCasoUso;
    private $dataHora;
    private $descricao;
    private $confere;

    function getIdPessoa() {
        return $this->idPessoa;
    }
    
    function getNome() {
        return $this->nome;
    }
    
    function getNomeAcesso() {
        return $this->nomeAcesso;
    }
    
    function getIdCasoUso() {
        return $this->idCasoUso;
    }

    function getDataHora() {
        return $this->dataHora;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getConfere() {
        return $this->confere;
    }

    public function __construct($idPessoa, $nome, $nomeAcesso, $idCasoUso, $dataHora, $descricao, $confere) {
        $this->idPessoa = $idPessoa;
        $this->nome = $nome;
        $this->nomeAcesso = $nomeAcesso;
        $this->idCasoUso = $idCasoUso;
        $this->dataHora = $dataHora;
        $this->descricao = $descricao;
        $this->confere = $confere;
    }
    public static function obterInformacoesPaginacao($whereConditions, $whereValues, $paginaAtual, $totalRegistrosPorPargina = 50) { 
        $con = BD::conectar();
        $infosPaginacao = array();
        
        $infosPaginacao['paginaAtual'] = $paginaAtual;
        
        $infosPaginacao['totalRegistrosPorPargina'] = $totalRegistrosPorPargina;
        $infosPaginacao['primeiroRegistro'] = (($infosPaginacao['paginaAtual']-1) * $infosPaginacao['totalRegistrosPorPargina'] + 1);
        $infosPaginacao['ultimoRegistro'] = ($infosPaginacao['paginaAtual'] * $infosPaginacao['totalRegistrosPorPargina']);        

        $strWhere = ' WHERE ' . implode(' AND ', $whereConditions);
        $query = vsprintf("SELECT log.idPessoa "
                            . "FROM Log log  "
                            . "INNER JOIN Pessoa p ON p.idPessoa = log.idPessoa "
                            . "INNER JOIN Login l ON l.idPessoa = log.idPessoa $strWhere",
                            $whereValues);

        $result = mysql_query($query, $con);
        
        $infosPaginacao['totalRegistros'] = mysql_num_rows($result);
        
        $infosPaginacao['totalDePaginas'] = ceil($infosPaginacao['totalRegistros']/$totalRegistrosPorPargina);
        
        if ((int)$infosPaginacao['paginaAtual'] === (int)$infosPaginacao['totalDePaginas']) {
            $totalRegistrosPenultimaPagina = (($infosPaginacao['totalDePaginas']-1) * $totalRegistrosPorPargina);
            $infosPaginacao['ultimoRegistro'] = $totalRegistrosPenultimaPagina + ((int)$infosPaginacao['totalRegistros'] - (int)$totalRegistrosPenultimaPagina);
        }
        
        $infosPaginacao['primeiraPaginaASerExibida'] = 1;
        $infosPaginacao['paginaLimite'] = $infosPaginacao['totalDePaginas'];
        if ((int)$infosPaginacao['paginaAtual'] > 10) {
            $infosPaginacao['primeiraPaginaASerExibida'] = (int)$infosPaginacao['paginaAtual'] - 9;
            $infosPaginacao['paginaLimite'] = $infosPaginacao['paginaAtual'];
        }
       
        return $infosPaginacao;
    }

    public static function select($whereConditions, $whereValues, $offSet = 0, $limit = 50) {
        $con = BD::conectar();

        $strWhere = ' WHERE ' . implode(' AND ', $whereConditions);
        

        $query = vsprintf("SELECT log.*, p.nome, l.nomeAcesso "
                            . "FROM Log log  "
                            . "INNER JOIN Pessoa p ON p.idPessoa = log.idPessoa "
                            . "INNER JOIN Login l ON l.idPessoa = log.idPessoa $strWhere",
                            $whereValues);

        $queryWithLimit = sprintf("ORDER BY dataHora LIMIT %d, %d", $offSet, $limit);        
        $queryFinal = $query . $queryWithLimit;

        $arrLogs = array();
        $result = mysql_query($queryFinal, $con);
        while ($row = mysql_fetch_assoc($result)) {
            $arrLogs[] = new self($row['idPessoa'], $row['nome'], $row['nomeAcesso'],  $row['idCasoUso'], $row['dataHora'], $row['descricao'], $row['confere']);
        }

        return $arrLogs;
    }
    
    /**
    * Obtem todos os registros de Logs deste usuário ainda não conferidos
    */
    public static function getLogsNaoConferidos($nomeAcesso) 
    {
        $con = BD::conectar();
        $query=sprintf("select Log.idCasoUso as idCasoUso,Log.dataHora as dataHora," .
                "Log.descricao as descricaoLog,Funcao.descricao as descricaoFuncao," .
                "Funcao.critico as critico from Login inner join Pessoa on " .
                "Login.idPessoa=Pessoa.idPessoa inner join Log on " .
                "Pessoa.idPessoa=Log.idPessoa inner join Funcao on " .
                "Log.idCasoUso=Funcao.idCasoUso where Login.nomeAcesso='%s' ".
                "and Log.confere='NÃO' " .
                "order by Funcao.critico DESC,Log.dataHora DESC",$nomeAcesso);
        $result = mysql_query( $query,$con);
        $logs = array();
        while( $linha = mysql_fetch_object($result) ) 
        {
                $logs[] = $linha;
        }
        return $logs;
    }
    
    public static function incluirLog( $idPessoa,$idCasoUso,$descricao,$con)
    {
        if( $con == null) { $con = BD::conectar(); }
        $query = sprintf("insert into Log (idPessoa,idCasoUso,descricao) "
                . "values (%d,'%s','%s')",
                $idPessoa,
                $idCasoUso,
                mysql_escape_string($descricao));
        $result = mysql_query($query,$con);
        if(!$result) 
        {
            throw new Exception("Erro ao inserir na tabela Log.");
        }
    }
}