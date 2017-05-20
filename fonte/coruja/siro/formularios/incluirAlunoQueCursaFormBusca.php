    <!--// PRIMEIRA ETAPA DA INCLUSAO DE ALUNO EM TURMA   -->
    <script language='javascript' src='/coruja/siro/javascripts/jquery.js'></script>
    <script language='javascript' src='/coruja/siro/javascripts/jquery.editinplace.js'></script>
    <script type="text/javascript" src="/coruja/siro/javascripts/carrega_div.js"></script>
        
    <form id="cadastro" method="post" name="cadastro" action="ManterAlunosQueCursamTurma_controle.php">

        <fieldset id="fieldsetGeral">
        
    	    <input type="hidden" name="tipo" value="aluno" />    <!--// CARREGA O TIPO PARA A PRÓXIMA ETAPA   -->
            <input type="hidden" name="acao" value="buscarAluno" />
            
            <input type="hidden" name="idTurma" value="<?php echo $idTurma;?>"/>
            <input type="hidden" name="siglaDisciplina" value="<?php echo $siglaDisciplina;?>"/>
            <input type="hidden" name="nomeDisciplina" value="<?php echo $nomeDisciplina;?>"/>
        
            <legend style="width: 600px">INCLUIR NOVO ALUNO NA TURMA: <?php echo $siglaDisciplina." - ".$nomeDisciplina; ?></legend>
            
            Escolha o tipo de consulta:
            <br /><br />
		    <input type="radio" name="tipoBusca" id="tipoBusca" value="nome" onClick="Hide('div2', this); Reveal('didfv1', this)" >Nome
                    <input type="radio" name="tipoBusca" id="tipoBusca" value="matricula" onClick="Hide('didfv1', this); Reveal('div2', this)" >Matr&iacute;cula
            <br /><br />

		    <div class="row" id="didfv1" style="display:none">
		    <label for="nome">Nome : </label> 
                <input name="nome" id="nome" class="obrigatorio" type="text" size="52" onchange="this.value=this.value.toUpperCase();" />
            <br />
            <div align="right">
    	    <input type="submit" id="button1" value="Procurar" />
		    </div>
            </div>
            
            <div class="row" id="div2" style="display:none">
            <label for="matricula">Matr&iacute;cula : </label> 
                <input name="matricula" id="matricula" class="obrigatorio" type="text" size="12" onchange="this.value=this.value.toUpperCase();"  />
            <br />
            <div align="right">
            <input type="submit" id="button1" value="Procurar" />
            </div>
            </div>
            
        </fieldset>


    </form>
    <form name="voltar" id="voltar" action="ManterAlunosQueCursamTurma_controle.php" method="post">
        <input type="hidden" name="acao" value="verAlunosTurma" />
        <input type="hidden" name="idTurma" value="<?php echo $idTurma;?>"/>
        <input id='button1' type='submit' value='  Voltar  ' >
    </form>