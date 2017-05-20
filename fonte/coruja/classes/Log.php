<?php
class Log {

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
}