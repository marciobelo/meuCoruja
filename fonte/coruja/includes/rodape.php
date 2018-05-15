        <div class ='center'>
            <img src='/coruja/imagens/coruja.png' border="0" style="text-align: center;width: 50px; height:50px;" alt="" title='Sistema Coruja' onclick="window.open('/coruja/interno/creditos.php', 'new', 'width=800,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no')" onmouseover="this.style.cursor='pointer'" />

            <!-- espaçamento entre logos -->
            &nbsp;&nbsp;&nbsp;

            <img src='/coruja/imagens/SiRO.png' border="0" style="text-align: center; width: 80px;height:80px;" alt="" title='Sistema de Solicitação de Inscrição em Turmas Online' onclick="window.open('/coruja/siro/formularios/creditos.php', 'new', 'width=800,height=640,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no')" onmouseover="this.style.cursor='pointer'" />

            <!-- espaçamento entre logos -->
            &nbsp;&nbsp;&nbsp;

            <img src='/coruja/imagens/nort.png' border="0" style="text-align: center; width: 80px;height:80px;" alt="" title='Sistema de lançamento de notas, manutenção de turmas e emissão de relatórios' onclick="window.open('/coruja/nort/formularios/creditos.php', 'new', 'width=800,height=640,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no')" onmouseover="this.style.cursor='pointer'" />

            <!-- espaçamento entre logos -->
            &nbsp;&nbsp;&nbsp;

            <img src='/coruja/imagens/mmc.png' border="0" style="text-align: center; width: 80px;height:95px;" alt="" title='Sistema de Gerência de Perfis e Logs com Módulo de Matriz Curricular' onclick="window.open('/coruja/mmc_gpl/formularios/creditos.php', 'new', 'width=800,height=640,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no')" onmouseover="this.style.cursor='pointer'" />
        </div>

        <div id="versaoCoruja">
            <p style="text-align: center; font-size: 60%; ">Vers&atilde;o: 1.7 - Revis&atilde;o:
                <?php
                    $arrayRevisao = file("$BASE_DIR/versao.txt");
                    $revisao = str_replace("M", " ", $arrayRevisao[0]);
                    echo $revisao;
                ?>
            </p>
        </div>

    </body>
</html>