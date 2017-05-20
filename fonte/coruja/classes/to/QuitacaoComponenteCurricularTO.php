<?php
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
/**
 * Transfer Object para indicar informações de cumprimento de
 * um componente curricular
 * @author mbelo
 */
class QuitacaoComponenteCurricularTO {

    private $periodoLetivo; // instância de PeriodoLetivo
    private $mediaFinal;    /* valor decimal com a média final. Caso seja
                            nulo, indica que foi isento (ID). */
    private $creditos;      /* valor inteiro maior que 0 */
    private $isento;        /* valor lógico que indica se o aluno foi isento do
                            * componente curricular */

    /*
    * Construtor da Classe
    */
    public function __construct(PeriodoLetivo $periodoLetivoParam,$mediaFinal,$creditos,$isento) {
        
        // Não permite que o creditos esteja nulo
        if( ($creditos==null) || (!is_numeric($creditos)) || ($creditos <=0) ) {
            trigger_error("Parâmetro creditos para instanciamento de objeto " .
                "de QuitacaoComponenteCurricularTO inválido.",E_USER_ERROR);
        }

        $this->periodoLetivo = $periodoLetivoParam;
        $this->mediaFinal = $mediaFinal;
        $this->creditos=$creditos;
        $this->isento=$isento;
    }

    public function getPeriodoLetivo(){
        return $this->periodoLetivo;
    }

    public function getMediaFinal(){
        return $this->mediaFinal;
    }

    public function getCreditos(){
        return $this->creditos;
    }

    public function isIsento() {
        return $this->isento;
    }
}
?>