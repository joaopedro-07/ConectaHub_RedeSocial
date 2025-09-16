<?php
    $dbhost="localhost";
    $dbuser="root";
    $dbpassword="";
    $dbname="conectatech";

    $conexao = mysqli_connect($dbhost,$dbuser,$dbpassword,$dbname);
    
    if(!$conexao){
        die("Falhou a conexão". mysqli_connect_error());
    }
?>