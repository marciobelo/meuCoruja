<?php
class Image {

    /**
     * Método invocado pelo flash após capturar a imagem do usuário
     * @param array de bytes $byteArray blob com a foto capturada do usuário
     * @param string $parametros contém o id da pessoa
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