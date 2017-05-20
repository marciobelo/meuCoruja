<?php
$BASE_DIR = ".."; // indica o caminho dos fontes relativo ao diretуrio onde estгo estes testes

require_once "$BASE_DIR/classes/TipoCurso.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";

class PeriodoLetivoTest extends PHPUnit_Framework_TestCase {
    
    public function testIsDataForaPeriodoLetivo() {
        $curso = new Curso();
        $curso->setIdTipoCurso(1);
        $curso->setNomeCurso("Curso de Teste");
        $curso->setSiglaCurso("XPTO");
        $curso->setTipoCurso( new TipoCurso(1,"Graduaзгo") );
              
        $periodoLetivo = new PeriodoLetivo(1, "2014.1", "2014-02-01", "2014-07-30", "XPTO", false);
        
        $this->assertTrue( $periodoLetivo->isDataForaPeriodo("2014-01-01") ); 
        $this->assertTrue( $periodoLetivo->isDataForaPeriodo("2014-08-01") );
        $this->assertFalse( $periodoLetivo->isDataForaPeriodo("2014-02-01") );
        $this->assertFalse( $periodoLetivo->isDataForaPeriodo("2014-07-30") );
        $this->assertFalse( $periodoLetivo->isDataForaPeriodo("2014-03-15") );
    }   
}
?>