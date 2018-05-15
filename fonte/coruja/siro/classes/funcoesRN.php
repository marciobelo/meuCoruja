<?php
require_once "$BASE_DIR/classes/Pessoa.php";
require_once "$BASE_DIR/classes/Curso.php";
require_once "$BASE_DIR/classes/Turma.php";
require_once "$BASE_DIR/classes/PeriodoLetivo.php";
require_once "$BASE_DIR/classes/Inscricao.php";
require_once "$BASE_DIR/classes/MatriculaAluno.php";
require_once "$BASE_DIR/classes/Aluno.php";
require_once "$BASE_DIR/classes/ComponenteCurricular.php";

class funcoesRN {

    private $inscricao;

    public function __construct() {
        $this->inscricao = new Inscricao();
    }

    public function RN08($manterInscricaoTurma, $matriculaAluno) {
        $colideRN08=$this->inscricao->verificaConflito($manterInscricaoTurma, $matriculaAluno);
        return $colideRN08;
    }

    //OBTEM A TURMA QUE COLIDE - RN08
    public function obterTurmaRN08($colideRN08,$idTurma,$situacaoInscricao,$matriculaAluno,$idPeriodoLetivo) {
        if($colideRN08) {
            $turmaColide=array(array(),array());
            $turmaColide=$this->inscricao->obterTurmaComConflitoHorario($idTurma, $matriculaAluno, $situacaoInscricao,$idPeriodoLetivo);
            //OBTENDO OS TEMPOS QUE COLIDE PARA A RN08
            $tempoColide=array();
            $tColide = array();
            for($i=0;$i<count($turmaColide);$i++) {
                $tempoColide[]=$turmaColide[$i]["tempo"];
                $tColide[]=$turmaColide[$i]["turma"];
            }

            $RN08= array();
            $RN08['turma']=$tColide;
            $RN08['tempo']=$tempoColide;

            return  $RN08;
        }
        //FIM DA VERIFICACAO DA RN08
        return NULL;
    }

    public function mensagemRN08() {
        $mensagem=htmlspecialchars("Um aluno só pode ser inscrito em duas ou mais turmas colidindo um ou ".
                "mais tempos semanais de aulas, no mesmo período letivo, do mesmo ".
                "curso, com a expressa anuência do Coordenador Acadêmico, que deve ".
                "justificar sua autorização.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN08Curta() {
        $mensagem=htmlspecialchars("Colisão de horário:", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function RN09($idTurma, $matriculaAluno) {
        //VARIAVEIS
        $quitacaoTO; // UTILIZADA PARA OBTER A INFORMAÇÃO SE UM COMPONENTE CURRICULAR FOI CUMPRIDO
        $listaComponente = array(); // UTILIZADO PARA GUARDAR AS INFORMACOES DOS CCs NAO CUMPRIDOS

        $ma = MatriculaAluno::obterMatriculaAluno($matriculaAluno);
        $turma=Turma::getTurmaById($idTurma);
        $componenteRN= new ComponenteCurricular($turma->getSiglaCurso(), $turma->getIdMatriz(), $turma->getSiglaDisciplina());
        //  $cumpreRequisitosRN09=true;
        $turmaPreRequisitos=$componenteRN->obterPreRequisitos();
        //VERIFICA SE O ALUNO QUITOU OS PRÉ-REQUISITOS
        foreach ($turmaPreRequisitos as $turmaPre) {
            // PARA CADA TURMA PRÉ , OBTEM O COMPONENTE CURRICULAR ASSOCIADO
            $cc = ComponenteCurricular::obterComponenteCurricular($turmaPre->getSiglaCurso(), $turmaPre->getIdMatriz(), $turmaPre->getSiglaDisciplina());
            // VERIFICA SE O ALUNO CUMPRIU O PRÉ-REQUISITO;
            $quitacaoTO = $cc->obterQuitacao($ma);
            if(empty ($quitacaoTO)) {
                $listaComponente[]=$cc;
            }
        }
        return $listaComponente;

    }

    /*@param = Lista de componentes curriculares para serem exibidos ao final da mensagem
     * 
    */
    public function mensagemRN09($listaComponente) {
        $mensagem=htmlspecialchars("Um aluno só pode ser inscrito em uma turma para qual ".
                "não possua pré-requisitos, no mesmo período letivo, do mesmo curso, ".
                "com a expressa anuência do Coordenador Acadêmico, que deve ".
                "justificar sua autorização. ", ENT_QUOTES, "iso-8859-1");
        $mensagem.=htmlspecialchars("No caso da turma solicitada falta cumprir : ", ENT_QUOTES, "iso-8859-1");
        foreach($listaComponente as $cc) {
            $mensagem.=htmlspecialchars($cc->getSiglaDisciplina()."; ", ENT_QUOTES, "iso-8859-1");
        }

        return $mensagem;
    }

    public function mensagemRN09Curta($listaComponente) {
        $mensagem=htmlspecialchars("Um aluno não pode ser inscrito em uma turma que ".
                "não possua pré-requisitos. No caso da turma solicitada falta cumprir : ", ENT_QUOTES, "iso-8859-1");
        foreach($listaComponente as $cc) {
            $mensagem.=htmlspecialchars($cc->getSiglaDisciplina()."; ", ENT_QUOTES, "iso-8859-1");
        }
        return $mensagem;
    }

    public function RN10($matriculaAluno,$idTurma) 
    {
        $turma=Turma::getTurmaById($idTurma);
        $alunoRF_RN10=$this->inscricao->obterTurmasQueAlunoReprovou($matriculaAluno, $turma->getSiglaDisciplina(),"'RF'", $turma->getIdPeriodoLetivo()-1);
        return $alunoRF_RN10;
    }

    public function mensagemRN10() {
        $mensagem=htmlspecialchars("Um Aluno só pode ser inscrito em uma Turma ".
                "de uma disciplina na qual ele foi reprovado por falta ".
                "no período letivo imediatamente anterior, do mesmo curso, ".
                "com a expressa anuência do Coordenador Acadêmico, ".
                "que deve justificar sua autorização. ", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN10Curta() {
        $mensagem=htmlspecialchars("Aluno reprovado por falta no período anterior ao atual.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function RN11($matriculaAluno,$idTurma) {

        $turma=Turma::getTurmaById($idTurma);
        $contaRN11=0;
        $alunoRN11=false;
        for($i=1;$i<$turma->getIdPeriodoLetivo();$i++) {
            $alunoRN11=$this->inscricao->obterTurmasQueAlunoReprovou($matriculaAluno, $turma->getSiglaDisciplina(),"'RF'".","."'RM'", $i);
            if($alunoRN11) {
                $contaRN11++;
            }
        }

        return $contaRN11;

    }

    public function mensagemRN11() {
        $mensagem=htmlspecialchars("Um Aluno só pode ser inscrito em um Turma ".
                "de uma disciplina na qual ele foi reprovado 3 vezes ou mais ".
                "(seja por falta ou por média), do mesmo curso, ".
                "com a expressa anuência do Coordenador Acadêmico, ".
                "que deve justificar sua autorização.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN11Curta() {
        $mensagem=htmlspecialchars("Aluno excedeu limite de reprovações, ".
                "procurar Coordenador Acadêmico.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function RN12($matr) 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno( $matr);
        $alunoRN12 = $matriculaAluno->verificaMatriculaAlunoExcedeTempo();
        return $alunoRN12;
    }

    public function mensagemRN12() {
        $mensagem=htmlspecialchars("Um Aluno cuja matrícula exceda 5 (cinco) anos ".
                "só poderá ter sua solicitação de inscrição em turma ".
                "aceita com a expressa anuência do Coordenador Acadêmico.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    public function mensagemRN12Curta() {
        $mensagem=htmlspecialchars("Tempo de matrícula excedido, procurar secretaria.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

    /*
     * Casos de Uso: UC02.06; UC02.01;
     * @return: false -> O aluno e do mesmo turno da turma solicitada;
     * @return: true -> O aluno nao eh do mesmo turno da turma solicitada;
    */
    public function RN22($matr,$idTurma) 
    {
        $matriculaAluno = MatriculaAluno::obterMatriculaAluno( $matr);
        $turma=Turma::getTurmaById($idTurma);

        if($matriculaAluno->getTurnoIngresso()!=$turma->getTurno())
        {
            $tIngressoRN22 = true;
        }
        else 
        {
            $tIngressoRN22 = false;
        }
        return $tIngressoRN22;
    }

    public function mensagemRN22() {
        $mensagem=htmlspecialchars("Um Aluno só pode ser inscrito em uma Turma ".
                "ofertada em turno diferente do de ingresso (vinculado à matrícula) ".
                "com a expressa anuência do Coordenador Acadêmico, que deve justificar sua autorização. ".
                "Obs.: O aluno somente obterá deferimento se houver vaga na turma solicitada, ".
                "após ser dada preferência a todos os alunos que são do turno da turma.", ENT_QUOTES, "iso-8859-1");

        return $mensagem;
    }

    public function mensagemRN22Curta() {
        $mensagem=htmlspecialchars("A turma solicitada não pertence ao turno de ingresso do aluno.", ENT_QUOTES, "iso-8859-1");
        return $mensagem;
    }

}