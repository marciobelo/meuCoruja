<?php
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/baseCoruja/classes/bdfuncoes.php";

class ComponenteCurricularProposto {
    private $siglaCurso;
    private $idMatriz;
    private $siglaDisciplina;
    private $nomeDisciplina;
    private $creditos;
    private $cargaHoraria;
    private $periodo;
    private $tipoComponenteCurricular;

    function __construct($siglaCurso, $idMatriz, $siglaDisciplina, $nomeDisciplina,
                         $creditos, $cargaHoraria, $periodo, $tipoComponenteCurricular, $posicaoPeriodo = 0) {
        $this->siglaCurso = $siglaCurso;
        $this->idMatriz = $idMatriz;
        $this->siglaDisciplina = $siglaDisciplina;
        $this->nomeDisciplina = $nomeDisciplina;
        $this->creditos = $creditos; 
        $this->cargaHoraria = $cargaHoraria; 
        $this->periodo = $periodo;
        $this->tipoComponenteCurricular = $tipoComponenteCurricular;
        $this->posicaoPeriodo = $posicaoPeriodo;
    }

    public function getSiglaCurso() {
        return $this->siglaCurso;
    }

    public function getIdMatriz() {
        return $this->idMatriz;
    }

    public function getSiglaDisciplina() {
        return $this->siglaDisciplina;
    }

    public function getNomeDisciplina() {
        return $this->nomeDisciplina;
    }

    public function getCreditos(){
        return $this->creditos; 
    }

    public function getCargaHoraria() {
        return $this->cargaHoraria;
    }

    public function getPeriodo(){
        return $this->periodo;
    }

    public function getTipoComponenteCurricular() {
        return $this->tipoComponenteCurricular;
    }
    
    public function getPosicaoPeriodo() {
        return $this->posicaoPeriodo;
    }

    public function setSiglaCurso($siglaCurso) {
       $this->siglaCurso = $siglaCurso;
    }

    public function setidMatriz($idMatriz) {
       $this->idMatriz = $idMatriz;
    }

    public function setSiglaDisciplina($siglaDisciplina) {
        $this->siglaDisciplina = $siglaDisciplina;
    }

    public function setNomeDisciplina($nomeDisciplina) {
        $this->nomeDisciplina = $nomeDisciplina;
    }

    public function setCreditos($creditos) {
        $this->creditos = $creditos;
    }

    public function setCargaHoraria($cargaHoraria) {
        $this->cargaHoraria = $cargaHoraria;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
    }
    
    public function setTipoComponenteCurricular($tipoComponenteCurricular) {
        $this->tipoComponenteCurricular = $tipoComponenteCurricular;
    }
    
    public function setPosicaoPeriodo($posicaoPeriodo) {
        $this->posicaoPeriodo = $posicaoPeriodo;
    }
    
    public static function criar($siglaCursoMatrizProposta, $idMatriz , $siglaDisciplina, $nomeDisciplina, $creditos, $cargaHoraria, $periodo, $tipoComponenteCurricular, $posicaoPeriodo = 0) {
        $con = BD::conectar();
        
        $query  = sprintf("insert into ComponenteCurricularProposto (siglaCurso, idMatriz, "
                         . "siglaDisciplina, nomeDisciplina, creditos, cargaHoraria, periodo, "
                         . "tipoComponenteCurricular, posicaoPeriodo) values ('%s', %d, '%s', '%s', %d, %d, %d, '%s', %d)", 
                         mysql_escape_string($siglaCursoMatrizProposta), $idMatriz, 
                         mysql_escape_string($siglaDisciplina), mysql_escape_string($nomeDisciplina), $creditos, $cargaHoraria, $periodo, 
                         mysql_escape_string($tipoComponenteCurricular), $posicaoPeriodo);

        $result = mysql_query($query,$con);
        if (!$result) {
            throw new Exception("Erro ao inserir na tabela Componente Curricular Proposto.");
        }
        
        $strLog = "Adicionado o Componente Curricular Proposto " . $siglaDisciplina . " na Matriz Curricular Proposta do Curso " . $siglaCursoMatrizProposta;  
        $_SESSION['usuario']->incluirLog('UC11.01.02.01', $strLog, $con);
    }
    
    public function editar($oldSiglaDisciplina, $siglaCurso, $idMatriz, $siglaDisciplina, $nomeDisciplina, $creditos, $cargaHoraria, $periodo, $tipoComponenteCurricular, $posicaoPeriodo=0) {
        $con = BD::conectar();
        
        $query = sprintf("UPDATE ComponenteCurricularProposto set siglaDisciplina='%s', "
                          . "nomeDisciplina='%s', creditos=%d, cargaHoraria=%d, periodo=%d, "
                          . "tipoComponenteCurricular='%s', posicaoPeriodo=%d WHERE siglaDisciplina = '%s' AND siglaCurso = '%s' AND idMatriz = %d",
                          mysql_escape_string($siglaDisciplina), mysql_escape_string($nomeDisciplina),
                          $creditos, $cargaHoraria, $periodo, mysql_escape_string($tipoComponenteCurricular), $posicaoPeriodo, $oldSiglaDisciplina, $siglaCurso, $idMatriz);
        
        $result = mysql_query($query,$con);
        if (!$result) {
            throw new Exception("Erro ao alterar informacoes de Componente Curricular Proposto.");
        }
        
        $queryPreRequisitos = sprintf("UPDATE PreRequisitoProposto set siglaPreRequisito = '%s' "
                          . "WHERE siglaCurso = '%s' AND idMatriz = %d AND siglaPreRequisito = '%s'",
                          mysql_escape_string($siglaDisciplina), $siglaCurso, $idMatriz, $oldSiglaDisciplina);
        
        $resultPreRequisitos = mysql_query($queryPreRequisitos,$con);
        if (!$resultPreRequisitos) {
            throw new Exception("Erro ao alterar informacoes de Pre Requisito Proposto.");
        }
        
        $strLog = "Editado o Componente Curricular Proposto " . $siglaDisciplina . " (era " . $oldSiglaDisciplina . ") na Matriz Curricular Proposta do Curso " . $siglaCurso;  
        $_SESSION['usuario']->incluirLog('UC11.01.02.02', $strLog, $con);
    }
    
    public static function obterComponeteCurricular($siglaCurso, $idUltimaMatrizEquivalente, $siglaDisciplina) {
        $con = BD::conectar();

        $componenteCurricularProposto = null;
        $query  = sprintf("SELECT * FROM ComponenteCurricularProposto WHERE siglaCurso = '%s' "
                          . "AND idMatriz = %d AND siglaDisciplina = '%s'", 
                          mysql_escape_string($siglaCurso), $idUltimaMatrizEquivalente, mysql_escape_string($siglaDisciplina));
        $result = mysql_query($query,$con);
        
        if ($infos = mysql_fetch_assoc($result)) {
             $componenteCurricularProposto = new ComponenteCurricularProposto($infos['siglaCurso'], $infos['idMatriz'], $infos['siglaDisciplina'], $infos['nomeDisciplina'], 
                                                                              $infos['creditos'], $infos['cargaHoraria'], $infos['periodo'], $infos['tipoComponenteCurricular'], $infos['posicaoPeriodo']);
        }
        
        return $componenteCurricularProposto;
    }
    
    public static function obterComponentesCurricularPorCurso($siglaCurso) {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM ComponenteCurricularProposto WHERE siglaCurso = '%s'", $siglaCurso);

        $result = mysql_query($query,$con);

        if ($result) {
             while ($row = mysql_fetch_assoc($result)) {
                $componentesCurricularesPropostos[] = new ComponenteCurricularProposto($row['siglaCurso'], $row['idMatriz'], $row['siglaDisciplina'], $row['nomeDisciplina'], 
                                                                              $row['creditos'], $row['cargaHoraria'], $row['periodo'], $row['tipoComponenteCurricular']);

            }
        } else {
            throw new Exception("Erro ao obter Componentes Curriculares Propostos.");
        }
        
        return $componentesCurricularesPropostos;
    }
    
    public function deletar() {
        $con = BD::conectar();
        $queryComponente = sprintf("DELETE FROM ComponenteCurricularProposto WHERE siglaCurso = '%s' AND idMatriz = %d AND siglaDisciplina = '%s'",
                           $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina());
        
        $resultComponente = mysql_query($queryComponente, $con);
        
        if (!$resultComponente) {
            throw new Exception("Erro ao deletar Componente Curricular Proposto.");
        }
        
        $queryEquivalencia = sprintf("DELETE FROM EquivalenciaProposta WHERE siglaCurso = '%s' AND idMatriz = %d AND siglaDisciplina = '%s'",
                           $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina());
        
        $resultEquivalencia = mysql_query($queryEquivalencia, $con);
        
        if (!$resultEquivalencia) {
            throw new Exception("Erro ao deletar Equivalencia Proposta.");
        }
        
        $queryPreRequisito = sprintf("DELETE FROM PreRequisitoProposto WHERE siglaCurso = '%s' AND idMatriz = %d AND (siglaDisciplina = '%s' OR siglaPreRequisito = '%s')",
                           $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina(), $this->getSiglaDisciplina());

        $resultPreRequisito = mysql_query($queryPreRequisito, $con);
        
        if (!$resultPreRequisito) {
            throw new Exception("Erro ao deletar Pre Requisito Proposto.");
        }
        
        $strLog = "Exclu&iacute;do o Componente Curricular Proposto " . $this->getSiglaDisciplina() . " da Matriz Curricular Proposta do Curso " . $this->getSiglaCurso();  
        $_SESSION['usuario']->incluirLog('UC11.01.02.03', $strLog, $con);
    
        return true;
    }
    
    public static function obterPossiveisPreRequisitos($siglaCurso, $idMatriz, $periodo) {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM ComponenteCurricularProposto WHERE siglaCurso = '%s'"
                          . " AND idMatriz = %d AND periodo < %d" , $siglaCurso, $idMatriz, $periodo);

        $result = mysql_query($query,$con);
        
        while ($row = mysql_fetch_assoc($result)) {
           $preRequisitos[] = new ComponenteCurricularProposto($row['siglaCurso'], $row['idMatriz'], $row['siglaDisciplina'], $row['nomeDisciplina'], 
                                                                         $row['creditos'], $row['cargaHoraria'], $row['periodo'], $row['tipoComponenteCurricular']);
        }
        
        return $preRequisitos;
    }
    
    public function obterPreRequisitos() {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM PreRequisitoProposto WHERE siglaCurso = '%s' AND idMatriz = %d AND siglaDisciplina = '%s'"  , 
                            $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina());

        $result = mysql_query($query,$con);
        $preRequisitos = array();
        while ($row = mysql_fetch_assoc($result)) {
           $preRequisitos[] = ComponenteCurricularProposto::obterComponeteCurricular($this->getSiglaCurso(), $this->getIdMatriz(), $row['siglaPreRequisito']);
        }

        return $preRequisitos;
    }
    
    public function definirPreRequisitos($preRequisitos) {
        $con = BD::conectar();
        
        foreach ($preRequisitos as $preRequisito) {            
            $query  = sprintf("INSERT INTO PreRequisitoProposto VALUES('%s', %d ,'%s', '%s')" , 
                                $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina(), $preRequisito);

            $result = mysql_query($query,$con);
            
            if(!$result) {
                throw new Exception("Erro ao definir Pre Requisito Proposto.");
            }
        }
    }
    
    public function limparPreRequisitos() {
        $con = BD::conectar();
        
        $query  = sprintf("DELETE FROM PreRequisitoProposto WHERE siglaCurso = '%s'"
                          . " AND idMatriz = %d AND siglaDisciplina = '%s'" , 
                          $this->siglaCurso, $this->idMatriz, $this->siglaDisciplina);
        $result = mysql_query($query,$con);
        
        if (!$result) {
            throw new Exception("Erro ao Limpar Pré Requisitos Propostos.");
        }
    }
    
    //verifica se o componente em questao ainda deve ser pre requisito
    public function verificaPosicaoComoPrerequisito () {
        $matrizProposta = MatrizCurricularProposta::obter($this->getSiglaCurso(), $this->getIdMatriz());
        $ccps = $matrizProposta->obterComponentes();

        $periodo = $this->getPeriodo();
        $siglaDisciplina = $this->getSiglaDisciplina();

        foreach ($ccps as $ccp) {
            if ((int)$ccp->getPeriodo() > (int)$periodo) {
                continue;
            }

            $preRequisitos = $ccp->obterPreRequisitos();
            foreach($preRequisitos as $preRequisito) {
                $siglasPreRequisitos[] = $preRequisito->getSiglaDisciplina();
            }

            if (in_array($siglaDisciplina, $siglasPreRequisitos)) {
                $pos = array_search($siglaDisciplina, $siglasPreRequisitos);
                unset($siglasPreRequisitos[$pos]);
                $ccp->limparPreRequisitos();
                $ccp->definirPreRequisitos($siglasPreRequisitos);
            }
        }
    }
    
    public function obterEquivalencias() {
        $con = BD::conectar();
        
        $query = sprintf("SELECT * FROM EquivalenciaProposta WHERE siglaCurso = '%s'"
                          . " AND idMatriz = %d AND siglaDisciplina = '%s'" , $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina());

        $result = mysql_query($query,$con);
        
        $equivalencias = array();
        while ($row = mysql_fetch_assoc($result)) {
           $equivalencias[] = $row['siglaEquivalencia'];
        }
        
        return $equivalencias;
    }
    
    public function definirEquivalencias($equivalencias) {
        $this->limparEquivalencias();
        
        $con = BD::conectar();
        
        foreach ($equivalencias as $equivalencia) {
            $query  = sprintf("INSERT INTO EquivalenciaProposta VALUES('%s', %d ,'%s', '%s')" , 
                                $this->getSiglaCurso(), $this->getIdMatriz(), $this->getSiglaDisciplina(), $equivalencia);

            $result = mysql_query($query,$con);            
        }
    }
    
    public function limparEquivalencias() {
        $con = BD::conectar();
        
        $query  = sprintf("DELETE FROM EquivalenciaProposta WHERE siglaCurso = '%s'"
                          . " AND idMatriz = %d AND siglaDisciplina = '%s'" , 
                          $this->siglaCurso, $this->idMatriz, $this->siglaDisciplina);

        $result = mysql_query($query,$con);
        
        if (!$result) {
            throw new Exception("Erro ao Limpar Equivalencias Propostas.");
        }
    }
    
    public static function obterComponentesCurricularesDeUmaMatriz(MatrizCurricularProposta $matrizProposta) {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM ComponenteCurricularProposto WHERE siglaCurso = '%s'", $matrizProposta->getSiglaCurso());

        $result = mysql_query($query,$con);

        if ($result) {
             while ($row = mysql_fetch_assoc($result)) {
                $componentesCurricularesPropostos[] = new ComponenteCurricularProposto($row['siglaCurso'], $row['idMatriz'], $row['siglaDisciplina'], $row['nomeDisciplina'], 
                                                                              $row['creditos'], $row['cargaHoraria'], $row['periodo'], $row['tipoComponenteCurricular']);

            }
        } else {
            throw new Exception("Erro ao obter Componentes Curriculares Propostos de Uma Matriz.");
        }
        
        return $componentesCurricularesPropostos;
    }
    
    public static function verificarEquivalencia($siglaCurso, $idMatriz, $siglaEquivalencia) {
        $con = BD::conectar();
        
        $query  = sprintf("SELECT * FROM EquivalenciaProposta WHERE siglaCurso = '%s' AND idMatriz = %d AND siglaEquivalencia = '%s'", 
                           $siglaCurso, $idMatriz, $siglaEquivalencia);
        
        $result = mysql_query($query,$con);

        if ($row = mysql_fetch_assoc($result)) {
             return true;
        }

        return false;
    }
    
    public function salva() {
        $con = BD::conectar();
        
        $query = sprintf("UPDATE ComponenteCurricularProposto set siglaDisciplina='%s', "
                          . "nomeDisciplina='%s', creditos=%d, cargaHoraria=%d, periodo=%d, "
                          . "tipoComponenteCurricular='%s', posicaoPeriodo=%d WHERE siglaDisciplina = '%s' AND siglaCurso = '%s' AND idMatriz = %d",
                          mysql_escape_string($this->getSiglaDisciplina()), mysql_escape_string($this->getNomeDisciplina()),
                          $this->getCreditos(), $this->getCargaHoraria(), $this->getPeriodo(), mysql_escape_string($this->getTipoComponenteCurricular()),
                          $this->getPosicaoPeriodo(), mysql_escape_string($this->getSiglaDisciplina()), $this->getSiglaCurso(), $this->getIdMatriz());

        $result = mysql_query($query,$con);
        if (!$result) {
            throw new Exception("Erro ao alterar tabela Componente Curricular Proposto.");
        }
    }
}
