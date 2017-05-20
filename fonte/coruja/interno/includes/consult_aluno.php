<?php
if ( $criterio === "1") 
{
    require_once("$BASE_DIR/interno/view/aluno/consulta_mat.php");
}
else if( $criterio === "2")
{
    require_once( "$BASE_DIR/interno/view/aluno/consulta_nome.php");
}
else
{
    trigger_error("Criterio de pesquisa nao identificado", E_USER_ERROR);
}