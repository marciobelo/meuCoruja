<?php

class GrupoFuncao
{
    private $id;
    private $nome;
    private $funcoes;
    
    public function __construct($id, $nome, $funcoes){
        $this->id = $id;
        $this->nome = $nome;
        $this->funcoes = $funcoes;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getNome() {
        return $this->nome;
    }
    
    public function getFuncoes() {
        return $this->funcoes;
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function setFuncoes($funcoes) {
        $this->funcoes = $funcoes;
    }
    
    public static function obterTodos() {
        $con   = BD::conectar();
        $query = "SELECT *
                  FROM GrupoFuncao";
  
        $result = mysql_query($query, $con);
        if (mysql_affected_rows() > 0) {
            $grupos = array();
            while ($row = mysql_fetch_assoc($result)) {
                $funcoesGrupo = self::obterFuncoesPorIdGrupo($row['id']);
                $grupos[] = new GrupoFuncao($row['id'], $row['nome'], $funcoesGrupo); 
            } 
            return $grupos;
        } else {
            return null;
        }
    }
    
    public static function obterPorId($idGrupo) {
        $con = BD::conectar();
        $query = sprintf("SELECT *
                  FROM GrupoFuncao
                  WHERE id = %d", $idGrupo);
        
        $result = mysql_query($query, $con);
        $row = mysql_fetch_assoc($result);
        
        $funcoes = self::obterFuncoesPorIdGrupo($idGrupo);

        $grupo  = null;
        if ($row) {
            $grupo = new GrupoFuncao($row['id'], $row['nome'], $funcoes); 
        }
        
        return $grupo;
    }
    
    
    public static function obterFuncoesPorIdGrupo($idGrupo) {
        $con   = BD::conectar();
        $query = sprintf("SELECT *
                  FROM FuncaoPorGrupo
                  WHERE idGrupo = %d", $idGrupo );
        
        $result = mysql_query($query, $con);
        
        if ($result) {
            while ($row = mysql_fetch_assoc($result)) {
                $funcoes[] = Funcao::obterPorId($row["idCasoUso"]);
            }
        
            return $funcoes;
        } else {
            return null;
        }
    }
       
    public static function criar($nome, $idFuncoes) {
        $con    = BD::conectar();
        $query  = sprintf("insert into GrupoFuncao (nome) values ('%s')",
                           mysql_escape_string($nome));
        $result = mysql_query($query,$con);
        
        if (!$result) {
            throw new Exception("Erro ao inserir na tabela de Grupos de Permissões.");
        }
        
        $query  = sprintf("SELECT id FROM GrupoFuncao WHERE nome = '%s'",
                           mysql_escape_string($nome));        
        
        $result = mysql_query($query,$con);
        if ($row = mysql_fetch_assoc($result)) {
            $idGrupo = $row['id'];
        }
        
        $permissoesDoGrupo = '';
        if (strlen($idFuncoes) > 0) {
            $idFuncoes = explode(',', $idFuncoes);
            foreach ($idFuncoes as $val) {
                $permissao = Funcao::obterPorId($val);
                $permissoesDoGrupo .= " - " . $permissao->getDescricao() . '<br/>';
                
                $query  = sprintf("insert into FuncaoPorGrupo (idGrupo, idCasoUso) values ('%s', '%s')",
                                   $idGrupo, $val);
                $result = mysql_query($query,$con);
                if(!$result) {
                    throw new Exception("Erro ao relacionar Permissão com Grupo.");
                }
            }
        }

        $strLog = "Incluído o Grupo de Permiss&otilde;es " .  $nome . " com as permiss&otilde;es: <br/> $permissoesDoGrupo";
        $_SESSION['usuario']->incluirLog("UC09.02.01", $strLog, $con);
        
        return true;
    }
    
    public function editar($nomeGrupo, $nomeAntigo, $idFuncoes) {
        $con = BD::conectar();
        $query = sprintf("UPDATE GrupoFuncao SET nome = '%s' WHERE id = %d",
                          mysql_escape_string($nomeGrupo), $this->getId());
        
        $result = mysql_query($query, $con);
        if (!$result) {
            throw new Exception("Erro ao alterar nome do Grupo.");
        }
        
        $query = sprintf("DELETE FROM FuncaoPorGrupo WHERE idGrupo = %d",
                          $this->getId());        
        
        $result = mysql_query($query, $con);
        if (!$result) {
            throw new Exception("Erro ao deletar permissões.");
        }
        
        $idFuncoes = explode(',', $idFuncoes);
        foreach ($idFuncoes as $idFuncao) {
            $permissao = Funcao::obterPorId($idFuncao);
            $permissoesDoGrupo .= " - " . $permissao->getDescricao() . '<br/>';
            
            $query  = sprintf("insert into FuncaoPorGrupo (idGrupo, idCasoUso) values ('%s', '%s')",
                               $this->getId(), $idFuncao);

            $result = mysql_query($query, $con);
            if(!$result) {
                throw new Exception("Erro ao relacionar Permissão com Grupo.");
            }
        }
        
        $strLog = "Modificado o Grupo de Permiss&otilde;es " .  $nomeGrupo . " (era " . $nomeAntigo . "), as novas permiss&otilde;es s&atildeo: <br/> $permissoesDoGrupo";
        $_SESSION['usuario']->incluirLog("UC09.02.03", $strLog, $con);
        
        return true;
    }
    
    public function deletar() {        
        $con = BD::conectar();
        $permissoesDoGrupo = $this->getFuncoes();
        $query = sprintf("DELETE FROM GrupoFuncao WHERE id = %d", $this->getId());
        
        $result = mysql_query($query, $con);
        if (!$result) {
            throw new Exception("Erro ao deletar Grupo.");
        }
        
        $query = sprintf("DELETE FROM FuncaoPorGrupo WHERE idGrupo = %d", $this->getId());
        $result = mysql_query($query, $con);
        if (!$result) {
            throw new Exception("Erro ao permissões do Grupo.");
        }
        
        $permissoesDoGrupo = '';
        foreach($this->getFuncoes() as $permissao) {
            $permissoesDoGrupo .= " - " . $permissao->getDescricao() . '<br/>';
        }
        $strLog = "Exclu&iacute;do o Grupo de Permiss&otilde;es " .  $this->getNome() . " com as Permiss&otilde;es: <br/>" . $permissoesDoGrupo;
        $_SESSION['usuario']->incluirLog("UC09.02.02", $strLog, $con);
        
        return true;
    }
    
     public static function obterGruposDeFuncaoPorPermissoes($permissoes) {
        $gruposFuncao = array();
        if ($permissoes) {
            foreach ($permissoes as $permissao) {
                $idsPermissoes[] = $permissao->getFuncao()->getIdCasoUso();
            }

            $todosOsGrupos = GrupoFuncao::obterTodos();
            $idGrupo = 0;
            foreach ($todosOsGrupos as $grupo) {
                $idGrupo = $grupo->getId();
                foreach ($grupo->getFuncoes() as $funcao) {
                    $idsPermissoesGrupo[$idGrupo][] = $funcao->getIdCasoUso();
                }

                if ( empty(array_diff($idsPermissoesGrupo[$idGrupo], $idsPermissoes)) ) {
                    $gruposFuncao[] = GrupoFuncao::obterPorId($idGrupo);
                }
            }
        }
        
        return $gruposFuncao;
    }
}