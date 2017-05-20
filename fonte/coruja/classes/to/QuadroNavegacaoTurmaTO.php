<?php
class QuadroNavegacaoTurmaTO {
    private $qtdeTotalDiasAnterior = 0;
    private $qtdeTotalDiasPosterior = 0;
    private $qtdeTotalDiasAnteriorEmAberto = 0;
    private $qtdeTotalDiasPosteriorEmAberto = 0;
    
    public function __construct($qtdeTotalDiasAnterior,$qtdeTotalDiasPosterior,
            $qtdeTotalDiasAnteriorEmAberto,$qtdeTotalDiasPosteriorEmAberto) {
        $this->qtdeTotalDiasAnterior = $qtdeTotalDiasAnterior;
        $this->qtdeTotalDiasPosterior = $qtdeTotalDiasPosterior;
        $this->qtdeTotalDiasAnteriorEmAberto = $qtdeTotalDiasAnteriorEmAberto;
        $this->qtdeTotalDiasPosteriorEmAberto = $qtdeTotalDiasPosteriorEmAberto;
    }
    
    public function getQtdeTotalDiasAnterior() {
        return $this->qtdeTotalDiasAnterior;
    }

    public function getQtdeTotalDiasPosterior() {
        return $this->qtdeTotalDiasPosterior;
    }

    public function getQtdeTotalDiasAnteriorEmAberto() {
        return $this->qtdeTotalDiasAnteriorEmAberto;
    }

    public function getQtdeTotalDiasPosteriorEmAberto() {
        return $this->qtdeTotalDiasPosteriorEmAberto;
    }
}
?>
