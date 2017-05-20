<?php
require_once "../classes/Util.php";

class UtilTest extends PHPUnit_Framework_TestCase {
    
    public function testPHPUnitFuncionado() {
        $this->assertTrue( TRUE );
    }
    
    public function testEhDecimalValido() {
        $this->assertTrue( Util::ehDecimalValido( "123,45" ) );
        $this->assertTrue( Util::ehDecimalValido( "1" ) );
        $this->assertTrue( Util::ehDecimalValido( "0,2" ) );
        $this->assertFalse( Util::ehDecimalValido( ",2" ) );
        $this->assertFalse( Util::ehDecimalValido( "abc" ) );
        $this->assertFalse( Util::ehDecimalValido( "1,,2" ) );
        $this->assertFalse( Util::ehDecimalValido( "1,000.00" ) );
        $this->assertFalse( Util::ehDecimalValido( "1.2" ) );
        $this->assertFalse( Util::ehDecimalValido( "1.e2" ) );
    }
    
    public function testConverteParaNota() {
        $this->assertEquals(5.5, Util::converteParaNota("5,5"));
    }
    
    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage "5.5" não é um valor correto
     */
    public function testConvertParaNotaIncorreta() {
        Util::converteParaNota("5.5");
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage "10,5" fora da faixa de 0 a 10
     */
    public function testConvertParaNotaForaInverlo() {
        Util::converteParaNota("10,5");
    }
    
    
    public function testAbreviaOuTruncaNome() 
    {
        $this->assertEquals( "Horacio da C. e", 
                Util::abreviaOuTruncaNome("HORACIO DA CUNHA E SOUSA RIBEIRO", 15));
    }
    
    public function testDateTimeAddDay()
    {
        $data = new DateTime("2015-01-31");
        $novaData = Util::DateTimeAddDay($data, 2);
        $this->assertEquals( new DateTime("2015-02-02"), $novaData);
    }
    
// Teste dependente de hora relógio (como ajustar)
//    public function testDiasAnteHoje()
//    {
//        $data = new DateTime( "2016-06-23");
//        $this->assertTrue( Util::isDiasOuMaisAntesDeHoje( $data, 0));
//        $this->assertTrue( Util::isDiasOuMaisAntesDeHoje( $data, 1));
//        $this->assertFalse( Util::isDiasOuMaisAntesDeHoje( $data, 2));
//        $this->assertFalse( Util::isDiasOuMaisAntesDeHoje( $data, 3));
//        $data2 = new DateTime( "2016-06-24");
//        $this->assertFalse( Util::isDiasOuMaisAntesDeHoje( $data2, 1));
//    }       
}