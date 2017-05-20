<?php
/*---------------------------------------------------------------------------------------------------------------------------------------
Resumo de Alocao de Professor
SISTEMA: CORUJA
ÚLTIMA ATUALIZAÇÃO -04/06/2011
AUTOR: VINÍCIUS MARQUES
-----------------------------------------------------------------------------------------------------------------------------------------
*/
require_once "../../includes/comum.php";
require_once "$BASE_DIR/classes/BD.php";
require_once "$BASE_DIR/baseCoruja/classes/fpdf/fpdf.php";
require_once "$BASE_DIR/classes/Util.php";



/**
 * 
 * @author Vinícius
 *
 */
class ResumoAlocaoProfessorPDF extends FPDF {



    /**
 * Este método retorna uma coleção de dados contendo resumidamente alocações dos professores, baseado nos parametros : $siglaCurso,$idPeriodoLetivo
 * @author Vinícius
 *
 */
public function obterGrade(){
	$query = sprintf("select
  p.nome as professor,
  mp.matriculaProfessor as matricula,
  mp.cargaHoraria as Cht,
  (select count(*) from Turma t
    inner join Aloca a on t.idTurma=a.idTurma
    where t.matriculaProfessor=mp.matriculaProfessor
    and t.siglaCurso='%s'
    and t.tipoSituacaoTurma<>'CANCELADA'
    and t.idPeriodoLetivo=%d) as temposAlocados,
  (select GROUP_CONCAT(distinct t.siglaDisciplina order by t.siglaDisciplina separator ',') from Turma t
    where t.matriculaProfessor=mp.matriculaProfessor
    and t.siglaCurso='%s'
    and t.tipoSituacaoTurma<>'CANCELADA'
    and t.idPeriodoLetivo=%d) as disciplinAlocada
 from Pessoa p inner join Professor pr on p.idPessoa=pr.idPessoa
   inner join MatriculaProfessor mp on pr.idPessoa=mp.idPessoa
 where mp.dataEncerramento is null order by p.nome",
	mysql_real_escape_string($_REQUEST['curso']),
        $_REQUEST['periodo'],
	mysql_real_escape_string($_REQUEST['curso']),
        $_REQUEST['periodo']);
       
        $query= mysql_query($query);

   $arr=array();

     if(mysql_num_rows($query)>0){
       for($i=0;$i<mysql_num_rows($query);$i++){// loop dados do sql
         $arr[$i]['professor']=mysql_result($query, $i,'professor');
         $arr[$i]['matricula']=mysql_result($query, $i,'matricula');
         $arr[$i]['cht']=mysql_result($query, $i,'cht');
         $arr[$i]['temposAlocados']=mysql_result($query, $i,'temposAlocados');
         $arr[$i]['disciplinAlocada']=mysql_result($query, $i,'disciplinAlocada');
        

       }
     }
     
	return $arr;
}

 public function Cabecalho($largura,$curso,$periodoLetivo){

        $this->setXY(9,$this->GetY()+3);
        $this->Image("../../imagens/logorj.jpg",$this->lMargin,$this->tMargin,20);

        $this->SetX(32);
        $this->SetFont('Arial','',9);
        $txt = Config::INSTITUICAO_FILIACAO_1 . "\n" .
            Config::INSTITUICAO_FILIACAO_2 . "\n" .
            Config::INSTITUICAO_FILIACAO_3 . "\n" .
            Config::INSTITUICAO_NOME_COMPLETO;               
        $this->MultiCell($tamHorizontalDoCabecalho,4.0,$txt, $this->debug,'L');

        $this->setXY(9,$this->GetY()+7);
	$this->SetFont('Arial','B',12);
	$txt='Resumo de Alocação de Professores do Curso : '.$curso;
        $this->Cell($largura,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'L');

        $this->setXY(9,$this->GetY()+5);
        $txt='Período Letivo : '.$periodoLetivo;
	$this->Cell($largura,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'L');


}

public function geraGrade($largura,$siglaCurso,$espaco,$periodoLetivo){
	
	$collectionDados =$this->obterGrade();

        //print_r($collectionDados);exit;
        	
	$this->larguraMaxima=282;
	 
	$cumprimento2 = 29;
	$cumprimento1 = 57;

	$fontePequena = 6;
	$espacamentoHorizontalPequeno = 5;
	$fonteMedia = 9;
	$espacamentoHorizontalGrande = 6;
        $this->tMargin=2;
        $this->bMargin=1;
        $this->AliasNbPages( '{total}' );
        $this->AddPage('P','A4');

  


        $this->Cabecalho($largura, $siglaCurso, $espaco, $periodoLetivo);
	
	$cont=0;$a=0;$b=0;
			
         $this->setXY(9, $this->GetY()+3);
         $this->SetFont('Arial', 'B', $fonteMedia);

   $txt='Professor';
   $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'L');

   $txt='Matrícula';
   $this->Cell($cumprimento2-6, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='CH. Total';
   $this->Cell($cumprimento2-10, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='Tempos Alocados';
   $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $txt='Disciplinas Alocadas';
   $this->Cell($cumprimento2+37, $espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'LTRB', 0, 'C');

   $i=0;
   foreach ($collectionDados as $key => $collectionDados) {

       $this->setXY(9, $this->GetY() + 5);
       $this->SetFont('Arial', 'B', $fonteMedia);
            $this->SetFont('Arial', '', $fontePequena+2);
            $txt = $collectionDados['professor'];
            $this->Cell($cumprimento1, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'L');

            $txt = $collectionDados['matricula'];
            $this->Cell($cumprimento2-6, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'C');

            $txt = $collectionDados['cht'];
            $this->Cell($cumprimento2-10, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'C');

            $txt = $collectionDados['temposAlocados'];
            $this->Cell($cumprimento2, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'C');

            $txt = $collectionDados['disciplinAlocada'];
            $this->Cell($cumprimento2+37, $espacamentoHorizontalPequeno, $txt, ($this->debug) ? 1 : 'LTRB', 0, 'C');

            $i++;

           }

            $this->rodape(50,87, 40,$this->GetY()+20);
   
	
}//fim método

public function rodape($c1,$c2,$c3,$c4){
	$this->setXY(9,$c4);
	$this->SetFont('Arial','',12);
	$this->setX(9);
	$txt='Coruja';
	$this->Cell($c1,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'L');

        $txt="Emitido em " . date('d/m/Y');
	$this->Cell($c2,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'C');

        $txt="Página " . $this->PageNo().' de {total}';
	$this->Cell($c1,$espacamentoHorizontalPequeno, $txt, ($this->debug)?1:'' , 0, 'R');
	
}
 
}
?>