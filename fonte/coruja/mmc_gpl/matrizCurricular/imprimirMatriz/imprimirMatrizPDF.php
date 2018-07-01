<?php 
require_once "../../../includes/comum.php";
require_once "$BASE_DIR/baseCoruja/classes/fpdf/fpdf.php";
require_once "$BASE_DIR/mmc_gpl/matrizCurricular/imprimirMatriz/variable_stream.php";

class ImprimirMatrizPDF extends FPDF {
    
    private $stringImagemMatriz;
    private $componentesCurriculares;
      
    public function __construct($orientation='P', $unit='mm', $format='A4') {
            parent::__construct($orientation, $unit, $format);
            // Register var stream protocol
            stream_wrapper_register('var', 'VariableStream');
    }

    function MemImage($data, $x=null, $y=null, $w=0, $h=0, $link='') {       
            // Display the image contained in $data
            $v = 'img'.md5($data);
            $GLOBALS[$v] = $data;
            $a = getimagesize('var://'.$v);
            if(!$a)
                $this->Error('Invalid image data');
            $type = substr(strstr($a['mime'],'/'),1);
            $this->Image('var://'.$v, $x, $y, $w, $h, $type, $link);
            unset($GLOBALS[$v]);
    }

    function GDImage($im, $x=null, $y=null, $w=0, $h=0, $link='') {
            // Display the GD image associated with $im
            ob_start();
            imagepng($im);
            $data = ob_get_clean();
            $this->MemImage($data, $x, $y, $w, $h, $link);
    }
       
    public function getStringImagemMatriz() {
        return $this->stringImagemMatriz;
    }
   
    public function getComponentesCurriculares() {
        return $this->componentesCurriculares;
    }
   
    public function setStringImagemMatriz($stringImagemMatriz) {
        $this->stringImagemMatriz = $stringImagemMatriz;
    }
   
    public function setComponentesCurriculares($componentesCurriculares) {
        $this->componentesCurriculares = $componentesCurriculares;
    }
    
    public function insereQuebraDeLinha($qtde) {
        while ($qtde > 0) {
            $this->Multicell(0,2,""); 
            $qtde--;
        }
    }
    
    public function montaCabecalho($dataInicialVigencia, $nomeCurso) {
        
        $yCabecaolho = 40;
        $xCabecalho = 10;
        $this->setXY(9,$this->GetY()+3);
        $this->Image("../../../imagens/logorj.jpg",$this->lMargin,$this->tMargin,20);

        $this->SetFont('Arial','',10);
        
        $this->Text($yCabecaolho, $xCabecalho, 'GOVERNO DO ESTADO DO RIO DE JANEIRO');
        $this->Text($yCabecaolho, $xCabecalho += 5, 'SECRETARIA DE ESTADO DE CIÊNCIA E TECNOLOGIA');
        $this->Text($yCabecaolho, $xCabecalho += 5, 'FUNDAÇÃO DE APOIO À ESCOLA TÉCNICA');
        $this->Text($yCabecaolho, $xCabecalho += 5, 'FACULDADE DE EDUCAÇÃO TECNOLOGICA DO ESTADO DO RIO DE JANEIRO');
        
        $this->insereQuebraDeLinha(10);
        
        $this->SetFont('Arial','B',27);
        $this->Cell(190,10,'Matriz',0,100, 'C');
        
        $this->SetFont('Arial','B',8);

        if(strLen($dataInicialVigencia) > 0) {
            $this->Cell(190,2,'Data de Vigência: ' . $dataInicialVigencia,0,100, 'C');
        } else {
            $this->Cell(190,2,'Proposta',0,100, 'C');
        }
        
        
        $this->SetFont('Arial','B',12);
        $this->Cell(190,10, $nomeCurso, 0, 100, 'C');
    }    
    
    public function montaTabelaDisciplinas($CHTotalDisciplinas) 
    {
        $CHTotal = $CHTotalDisciplinas + 100; // SOMA HORAS ATC
        // Header
        $this->SetFont('Arial','B',10);

        $this->Cell(40,8,'sigla',1,'', 'C');
        $this->Cell(150,8,'Disciplina',1,'', 'C');
        $this->Ln();
        $this->SetFont('Arial','',10);

        //Body
        foreach($this->componentesCurriculares as $componente) {
            $this->Cell(40,5,$componente->getSiglaDisciplina(),1,'', 'C');
             
            $this->Cell(150,5,iconv("UTF-8", "ISO-8859-1",utf8_encode($componente->getNomeDisciplina())),1,'', 'C');
            $this->Ln();
        }
        
        //Footer
        $this->Cell(60,5,'',0,'', 'C');
        $this->Cell(130,5,'Disciplinas: ' . $CHTotalDisciplinas . 'h',1,'', 'R');
        $this->Ln();
        $this->Cell(60,5,'',0,'', 'C');
        $this->Cell(130,5,'Atividade de Extensão Cultural, Iniciação Científica e Projeto Final de Curso: 100h',1,'', 'R');
        $this->Ln();
        $this->Cell(60,5,'',0,'', 'C');
        $this->Cell(130,5, 'Total horas curso: ' . $CHTotal . 'h'  ,1,'', 'R');
    }
}