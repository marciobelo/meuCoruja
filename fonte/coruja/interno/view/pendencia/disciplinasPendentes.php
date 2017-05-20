<?php 
require_once("../../../includes/comum.php");
require_once("$BASE_DIR/includes/topo.php");
require_once("$BASE_DIR/includes/menu_horizontal.php");
?>
<body>
<p>&nbsp;</p>
<table width="461" border="0" align="center">
  <tr>
    <td colspan="2"><div align="center"><strong>DISCIPLINAS PENDENTES </strong></div></td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="177"><strong>Sigla Disciplina</strong></td>
    <td width="274"><strong>Disciplina</strong></td>
  </tr>
  <?php 

 //print_r($_SESSION['arr']);
  foreach($_SESSION['arr'] as $key=>$arr){?>
  <tr>
    <td><?php echo $_SESSION['arr'][$key]['SiglaDisciplina'];?></td>
    <td><?php echo $_SESSION['arr'][$key]['NomeDisciplina'];?></td>
  </tr>
  <?php }?>
</table>
</body>
<?php require_once("$BASE_DIR/includes/rodape.php");
?>
