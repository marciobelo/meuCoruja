<?php
class Image {

    /**
     * M�todo invocado pelo flash ap�s capturar a imagem do usu�rio
     * @param array de bytes $byteArray blob com a foto capturada do usu�rio
     * @param string $parametros cont�m o id da pessoa
     */
    public function saveDataToFile($byteArray, $parametros) {
        $vet_par = explode(";",$parametros);
        $idPessoa = $vet_par[0];
        $con=BD::conectar();
        $query = sprintf("UPDATE Login SET foto='%s' WHERE idPessoa=%d",
                addslashes($byteArray->data),
                $idPessoa);
        mysql_query($query,$con);
    }	 
}	
?>