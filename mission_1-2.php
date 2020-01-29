<?php
    $hensu="byebye world";
    $filename="mission_1-2.txt";
    $fp=fopen($filename,"w"); 
    //"w"は書き込み専用(ファイルが有る時上書きする)、"a"は追加書き込み専用(ファイルが有る時最期に追加する)
    fwrite($fp,$hensu);
    fclose($fp);
?>