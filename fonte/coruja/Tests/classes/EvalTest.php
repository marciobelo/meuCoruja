<?php
class EvalTest extends PHPUnit_Framework_TestCase {
    
    public function testEval() {
        $expressao = "return (5.5 + 4.5) / 2;";
        $resultado = eval( $expressao );
        $this->assertEquals( 5.0, $resultado );
    }
    
    
    public function testEvalWithoutRound() {
        $expressao = "return ( 8.3 === null && 5.7 === null ? null : ((8.3 + 5.7) / 2) );";
        $resultado = eval( $expressao );
        $this->assertEquals( 7.0, $resultado );

        
        $expressao = "return ( 8.3 === null && 5.6 === null ? null : round( ((8.3 + 5.6) / 2) , 1) );";
        $resultado = eval( $expressao );
        $this->assertEquals( 7.0, $resultado );
        
        $expressao = "return ( 8.3 === null && 5.6 === null ? null : round( ((8.3 + 5.6) / 2) , 1) );";
        $resultado = eval( $expressao );
        $this->assertEquals( 7.0, $resultado );

        $expressao = "return ( 8.3 === null && 5.5 === null ? null : round( ((8.3 + 5.5) / 2) , 1) );";
        $resultado = eval( $expressao );
        $this->assertEquals( 6.9, $resultado );
        
        
        $expressao = "return ( 0 > 30 ? \"0.0\" : ( 6.4 === null && 5.0 === null ? \"\" : ( 6.4 >= 7 ? 6.4 : ( 5.6 === null ? null : round( ( 6.4 + 5.6 ) / 2 , 1 ) ) ) ) );";
        $resultado = eval( $expressao );
        $this->assertEquals( 6.0, $resultado );
        
        $expressao = "return ( 0 > 30 ? \"0.0\" : ( 6.4 === null && 5.0 === null ? \"\" : ( 6.4 >= 7 ? 6.4 : ( 5.5 === null ? null : round( ( 6.4 + 5.5 ) / 2 , 1 ) ) ) ) );";
        $resultado = eval( $expressao );
        $this->assertEquals( 6.0, $resultado );
        
    }
    
}

?>
