<?php
$BASE_DIR = ".."; // indica o caminho dos fontes relativo ao diretório onde estão estes testes

require_once "$BASE_DIR/config.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";

class VerificaCumprimentoComponenteCurricularTest extends PHPUnit_Framework_TestCase 
{
    public function testPossuiQuitacaoMesmaMatriz() 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "AC1"); // matriz 2005
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNotNull( $quitacao );
        $this->assertEquals( 8.0, $quitacao->getMediaFinal());
        $this->assertEquals( 4, $quitacao->getCreditos());
    }
    
    public function testNaoPossuiQuitacaoMesmaMatriz()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular = ComponenteCurricular::obterComponenteCurricular("TASI", 4, "AL2");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNull( $quitacao );
    }
    
    public function testQuitacaoComComponenteMatrizAnterior()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular = ComponenteCurricular::obterComponenteCurricular("TASI", 4, "OO1");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertEquals( 6.0, $quitacao->getMediaFinal());
        $this->assertEquals( 6, $quitacao->getCreditos());
    }
    
    public function testQuitacaoCaso4()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "OO2");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNull( $quitacao );
    }
    
    public function testQuitacaoCaso5()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "LPW");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNotNull( $quitacao );
        $this->assertEquals( 8.5, $quitacao->getMediaFinal());
        $this->assertEquals( 6, $quitacao->getCreditos());
    }
    
    public function testQuitacaoCaso6()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "SPB");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNull( $quitacao );
    }
    
    public function testQuitacaoCaso7()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "AL1");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNotNull( $quitacao );
        $this->assertEquals( 5.5, $quitacao->getMediaFinal());
        $this->assertEquals( 6, $quitacao->getCreditos());        
    }
    
    public function testQuitacaoCaso8()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 4, "TRI");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNotNull( $quitacao );
        $this->assertEquals( 9.3, $quitacao->getMediaFinal());
        $this->assertEquals( 2, $quitacao->getCreditos());        
        $this->assertFalse( $quitacao->isIsento() );
    }
    
    public function testQuitacaoCaso9()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1328");
        $componenteCurricular=ComponenteCurricular::obterComponenteCurricular("TASI", 5, "ADM");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNotNull( $quitacao );
        $this->assertTrue( $quitacao->isIsento() );
    }
    
    public function testQuitacaoAl1Matriz2006_com1FACe1IHM_Matriz2018()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1331");
        $componenteCurricular = ComponenteCurricular::obterComponenteCurricular("TASI", 5, "AL1");
        $quitacao = $componenteCurricular->obterQuitacao($matriculaAluno);
        $this->assertNotNull( $quitacao );
        $this->assertFalse( $quitacao->isIsento() );
        $this->assertEquals( 6.7, $quitacao->getMediaFinal(), "aproximado", 0.1);
        $this->assertEquals( 6, $quitacao->getCreditos());
    }
    
    public function testNaoQuitacaoAl2Matriz2006_com2FPRsem3ESD_Matriz2018()
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno("1331");
        $componenteCurricular = ComponenteCurricular::obterComponenteCurricular("TASI", 5, "AL2");
        $quitacao = $componenteCurricular->obterQuitacao( $matriculaAluno);
        $this->assertNull( $quitacao );
    }
}