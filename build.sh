# gera um novo release para deploy
svn update
rm ./fonte/coruja/versao.txt
svnversion --no-newline > ./fonte/coruja/versao.txt
date +\(%d/%m/%Y\) >> ./fonte/coruja/versao.txt
svn commit ./fonte/coruja/versao.txt -m "Build de nova versão"
svn export ./fonte/coruja /tmp/exportCoruja

