<?php
header("Content-type: application/vnd.oasis.opendocument.spreadsheet");
header("Content-Disposition: attachment; filename=dadosCarteira");
header("Pragma: no-cache");
?>
<html>
    <head><style type="text/css">
    table tr td{white-space:nowrap}
    </style></head>
    <body>
       <table width="1047">
        <thead>
          <tr bgcolor="#CCCCCC">
            <td colspan="8"><div align="center"><strong>Rela&ccedil;&atilde;o de Alunos com a situa&ccedil;&atilde;o &quot;Cursando&quot; - Sistema Coruja </strong></div></td>
          </tr>
          <tr bgcolor="#CCCCCC">
            <td width="86"><div align="center"><strong>Nome</strong></div></td>
            <td width="103"><div align="center"><strong>Matr&iacute;cula</strong></div></td>
            <td width="70"><div align="center"><strong>Turno de Ingresso</strong></div></td>
            <td width="80"><div align="center"><strong>Data de Nascimento</strong></div></td>
            <td width="88"><div align="center"><strong>RG</strong></div></td>
            <td width="126"><div align="center"><strong>&Oacute;rg&atilde;o Emissor do RG</strong></div></td>
            <td width="238"><div align="center"><strong>Nome da M&atilde;e</strong></div></td>
            <td width="220"><div align="center"><strong>Nome do Pai</strong></div></td>
          </tr>
        </thead>
        <tbody>
          <?php for($a=0;$a< sizeof($colecao)+1;$a++){?>
          <tr>
            <td><?php echo $colecao[$a]['matricula'];?></td>
            <td><?php echo $colecao[$a]['nome'];?></td>
			<td><?php echo $colecao[$a]['turno'];?></td>
            <td><?php echo $colecao[$a]['dataNascimento'];?></td>
            <td><?php echo $colecao[$a]['rg'];?></td>
            <td><?php echo $colecao[$a]['rgOrgaoEmissor'];?></td>           
            <td><?php echo $colecao[$a]['mae'];?></td>
            <td><?php echo $colecao[$a]['pai'];?></td>
          </tr>
          <?php }?>
        </tbody>
      </table>

    </body>
</html>