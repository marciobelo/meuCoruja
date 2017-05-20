<?php

require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/classes/ComponenteCurricularProposto.php";

class MatrizCurricularProposta {
    private $siglaCurso;
    private $idMatrizVigente;
    private $totalPeriodos;

    public function __construct($siglaCurso, $idMatrizVigente, $totalPeriodos) {
        $this->siglaCurso = $siglaCurso;
        $this->idMatrizVigente = $idMatrizVigente;
        $this->totalPeriodos = $totalPeriodos;
    }

    public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    //a matriz proposta fica salva com o id da matriz vigente relacioanda a ela ate o momento da validacao.
    public function getIdMatrizVigente() {
        return $this->idMatrizVigente;
    }
    
    public function getTotalPeriodos() {
        return $this->totalPeriodos;
    }
    
    public function totalPeriodos() {
        return $this->totalPeriodos;
    }

    public function setSiglaCurso($siglaCurso) {
        $this->siglaCurso = $siglaCurso;
    }

    public function setIdMatrizVigente($idMatrizVigente) {
        $this->idMatrizVigente = $idMatrizVigente;
    }
    
    public function setTotalPeriodos($totalPeriodos) {
        $this->totalPeriodos = $totalPeriodos;
    }
    
    public static function criar($siglaCurso, $idMatrizVigente, $totalPeriodos=3) {
        $con = BD::conectar();

        $query  = sprintf("INSERT INTO MatrizCurricularProposta (siglaCurso, idMatrizVigente, totalPeriodos)"
                          . " values ('%s', %d, %d)", mysql_escape_string($siglaCurso), $idMatrizVigente, $totalPeriodos);

        $result = mysql_query($query,$con);

        if (!$result) {
            throw new Exception("Erro ao inserir na tabela Matriz Curricular Proposta.");
        }
    }
    
    public static function obter($siglaCurso, $idMatrizVigente) {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM  MatrizCurricularProposta WHERE siglaCurso = '%s' AND idMatrizVigente = %d",
                            mysql_escape_string($siglaCurso), $idMatrizVigente);

        $result = mysql_query($query,$con);
        
        $matrizProposta = null;
        if ($row = mysql_fetch_assoc($result)) {
           $matrizProposta = new MatrizCurricularProposta($row['siglaCurso'], $row['idMatrizVigente'], $row['totalPeriodos']);
        }
        
        return $matrizProposta;
    }
    
    public static function obterPorSiglaCuros($siglaCurso) {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM  MatrizCurricularProposta WHERE siglaCurso = '%s'", mysql_escape_string($siglaCurso));

        $result = mysql_query($query, $con);
        
        $matrizProposta = null;
        if ($row = mysql_fetch_assoc($result)) {
           $matrizProposta = new MatrizCurricularProposta($row['siglaCurso'], $row['idMatrizVigente'], $row['totalPeriodos']);
        }
        
        return $matrizProposta;
    }
    
    public function obterComponentes() {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM ComponenteCurricularProposto WHERE siglaCurso = '%s'", $this->getSiglaCurso());
        
        $result = mysql_query($query, $con);

        if ($result) {
             while ($row = mysql_fetch_assoc($result)) {
                 
                $componentesCurricularesPropostos[] = new ComponenteCurricularProposto($row['siglaCurso'], $row['idMatriz'], $row['siglaDisciplina'], $row['nomeDisciplina'], 
                                                                              $row['creditos'], $row['cargaHoraria'], $row['periodo'], $row['tipoComponenteCurricular'], $row['posicaoPeriodo']);
            }
        } else {
            throw new Exception("Erro ao obter Componentes Curriculares Propostos de Uma Matriz.");
        }

        return $componentesCurricularesPropostos;
    }
    
    public function alterarTotalPeriodos($newTotalPeriodos) {
        $con = BD::conectar();
        
        $query  = sprintf("UPDATE MatrizCurricularProposta SET totalPeriodos = %d WHERE siglaCurso = '%s' AND idMatrizVigente = %d", 
                            $newTotalPeriodos, $this->getSiglaCurso(), $this->getIdMatrizVigente());

        $result = mysql_query($query, $con);
        
        if (!$result) {
           throw new Exception("Erro ao Alterar Total de Periodos da Matriz Proposta.");
        }
    }
    
    public function deletar() {
        $con = BD::conectar();
        
        $componentesCurriculares = $this->obterComponentes();
        foreach ($componentesCurriculares as $componente) {
            $componente->deletar();
        }
        
        $query = sprintf("DELETE FROM MatrizCurricularProposta WHERE siglaCurso = '%s' AND idMatrizVigente = %d",
                           $this->getSiglaCurso(), $this->getIdMatrizVigente());

        $result = mysql_query($query, $con);
        
        if (!$result) {
            throw new Exception("Erro ao deletar Matriz Curricular Proposta.");
        }
    }
    
    public function obterQtdeComponentesPeriodo($posPeriodo) {
        $con = BD::conectar();
        $query = sprintf("SELECT count(siglaDisciplina) as totalComponentesPeriodo FROM ComponenteCurricularProposto WHERE siglaCurso = '%s' AND  idMatriz = %d AND periodo = %d",
                            $this->getSiglaCurso(), $this->getIdMatrizVigente(), $posPeriodo);
        
        $result = mysql_query($query, $con);
        
        if (!$result) {
            throw new Exception("Erro ao obter qtde de componentes por periodo.");
        }
        
        $result = mysql_fetch_assoc($result);
        
        return $result['totalComponentesPeriodo'];
    }
}