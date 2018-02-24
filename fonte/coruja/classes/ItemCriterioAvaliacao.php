<?php

require_once "$BASE_DIR/classes/Inscricao.php";

class ItemCriterioAvaliacao {

    // enumeração para tipo
    const LANÇADO = "LANÇADO";
    const CALCULADO = "CALCULADO";
    const SITUAÇÃO = "SITUAÇÃO";
    const NOTA_FINAL = "FINAL";

    private $idItemCriterioAvaliacao;
    private $idCriterioAvaliacao;
    private $rotulo;
    private $descricao;
    private $ordem;
    private $tipo;
    private $formulaCalculo;

    public function __construct($idItemCriterioAvaliacao, $idCriterioAvaliacao, $rotulo, $descricao, $ordem, $tipo, $formulaCalculo) {
        $this->idItemCriterioAvaliacao = $idItemCriterioAvaliacao;
        $this->idCriterioAvaliacao = $idCriterioAvaliacao;
        $this->rotulo = $rotulo;
        $this->descricao = $descricao;
        $this->ordem = $ordem;
        $this->tipo = $tipo;
        $this->formulaCalculo = $formulaCalculo;
    }

    public function getIdItemCriterioAvaliacao() {
        return $this->idItemCriterioAvaliacao;
    }

    public function getRotulo() {
        return $this->rotulo;
    }

    public function getFormulaCalculo() {
        return $this->formulaCalculo;
    }

    /**
     * Exibe texto da nota lançada ou calculada
     * @param Inscricao $inscricao
     */
    public function exibir(Inscricao $inscricao) {
        $itensCriterioAvaliacaoInscricaoNota = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();
        if ($this->tipo == ItemCriterioAvaliacao::LANÇADO) {
            $itemAvaliacao = $this->obterItemNotaDesteCriterio($itensCriterioAvaliacaoInscricaoNota);
            return $itemAvaliacao == null ? "" : str_replace(".", ",", $itemAvaliacao->getNota());
        } elseif ($this->tipo == ItemCriterioAvaliacao::CALCULADO) {

            $faltas = $inscricao->obterFaltasLancadas();
            $limiteFaltas = $inscricao->getTurma()->getComponenteCurricular()->getLimiteFaltas();

            $resultado = $this->resolverExpressao($itensCriterioAvaliacaoInscricaoNota, $faltas, $limiteFaltas);
            if (is_numeric($resultado)) {
                $resultadoEmFloat = round($this->resolverExpressao($itensCriterioAvaliacaoInscricaoNota, $faltas, $limiteFaltas), 1);
                $stringResultado = str_replace(".", ",", sprintf("%.1f", $resultadoEmFloat));
            } else {
                $stringResultado = $resultado;
            }
            return $stringResultado;
        }
        throw new Exception("IllegalStateException");
    }

    private function resolverExpressao($itensCriterioAvaliacaoInscricaoNota, $faltas, $limiteFaltas) {

        // inicializa o mapa de itens resolvidos
        $resolvidos = array();
        for ($l = 0; $l < count($itensCriterioAvaliacaoInscricaoNota); $l++) {
            $item = $itensCriterioAvaliacaoInscricaoNota[$l];
            if ($item->getItemCriterioAvaliacao()->isLancado()) {
                $resolvidos[$item->getItemCriterioAvaliacao()->getRotulo()] = $item->getNota();
            }
        }
        $resolvidos["LIMITE_FALTAS"] = $limiteFaltas;
        $resolvidos["FALTAS"] = $faltas;

        for ($c = 0; $c < count($itensCriterioAvaliacaoInscricaoNota); $c++) {
            $item = $itensCriterioAvaliacaoInscricaoNota[$c];
            if ($item->getItemCriterioAvaliacao()->isCalculado()) {
                $expressaoCrua = $item->getItemCriterioAvaliacao()->getFormulaCalculo();
                $expressaoPronta = $this->produzExpressaoPronta($expressaoCrua, $resolvidos);
                $php_errormsg = "";
                $resultado = @eval($expressaoPronta);
                if (!empty($php_errormsg)) {
                    $stringErro = "Erro ao resolver expressao - expressao crua = " . $expressaoCrua
                            . " - expressao resolvida = " . $expressaoPronta
                            . " - php_errormsg = " . $php_errormsg;
                    error_log($stringErro);
                    throw new Exception($stringErro);
                }
                if (($resultado === false))
                    $resultado = null;
                $resolvidos[$item->getItemCriterioAvaliacao()->getRotulo()] = $resultado;
            }
        }
        return $resolvidos[$this->getRotulo()];
    }

    private function produzExpressaoPronta($expressaoCrua, $resolvidos) {
        $expressaoPronta = $expressaoCrua;
        foreach ($resolvidos as $chave => $valor) {
            $expressaoPronta = str_replace($chave, empty($valor) ? "null" : $valor, $expressaoPronta);
        }
        return "return (" . $expressaoPronta . ");";
    }

    /**
     * Exibe comentario da nota lançada
     * @param Inscricao $inscricao
     */
    public function exibirComentario(Inscricao $inscricao) {
        $itensCriterioAvaliacaoInscricaoNota = $inscricao->obterItensCriterioAvaliacaoInscricaoNota();
        $itemCriterioAvaliacaoInscricaoNota = $this->obterItemNotaDesteCriterio($itensCriterioAvaliacaoInscricaoNota);
        return $itemCriterioAvaliacaoInscricaoNota->getComentario();
    }

    public function obterItemNotaDesteCriterio($itensAvaliacao) {
        foreach ($itensAvaliacao as $itemAvaliacao) {
            if ($itemAvaliacao->getItemCriterioAvaliacao()->getRotulo() == $this->getRotulo()) {
                return $itemAvaliacao;
            }
        }
        throw new Exception("IllegalStateException");
    }

    public static function obterPorId($idItemCriterioAvaliacao) {
        $con = BD::conectar();
        $query = sprintf("select * from ItemCriterioAvaliacao 
            where idItemCriterioAvaliacao = %d", $idItemCriterioAvaliacao);
        $result = mysql_query($query, $con);
        $linha = mysql_fetch_array($result);
        return new ItemCriterioAvaliacao($linha["idItemCriterioAvaliacao"], $linha["idCriterioAvaliacao"], $linha["rotulo"], $linha["descricao"], $linha["ordem"], $linha["tipo"], $linha["formulaCalculo"]);
    }

    public static function obterItensCriterioAvaliacao() {
        $con = BD::conectar();
        //$query = sprintf("SELECT MAX(idItemCriterioAvaliacao) FROM itemcriterioavaliacao;");
        //$qtdItensCriterioAvaliacao = mysqli_fetch_array(mysql_query($query, $con));
        $query = sprintf("SELECT idItemCriterioAvaliacao, rotulo FROM itemcriterioavaliacao");
        $result = mysql_query($query, $con);
        
        while($row = mysql_fetch_assoc($result)){
            $arrayItensCriterioAvaliacao[] = $row;
        }
        return $arrayItensCriterioAvaliacao;
    }

    public function isLancado() {
        return $this->tipo == ItemCriterioAvaliacao::LANÇADO;
    }

    private function isCalculado() {
        return $this->tipo == ItemCriterioAvaliacao::CALCULADO;
    }

    public function isSituacaoFinal() {
        if ($this->rotulo == ItemCriterioAvaliacao::SITUAÇÃO) {
            return true;
        } else {
            return false;
        }
    }

    public function isNotaFinal() {
        if ($this->rotulo == ItemCriterioAvaliacao::NOTA_FINAL) {
            return true;
        } else {
            return false;
        }
    }

}
