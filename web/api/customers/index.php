<?php
header('Content-Type: application/json; charset=utf-8');

include '../../bootstrap.php';



$TAMANO_PAGINA = (int)$_REQUEST['total'];

$page = ($_REQUEST['page'])?$_REQUEST['page']:1;
if((int)$page == 0 || (int)$page == 1){
    $limit = 'limit 0,'.(int)$_REQUEST['total'];  
}else{
    //Limito la busqueda
    
    
    //examino la página a mostrar y el inicio del registro a mostrar
    $pagina = $page;
    if (!$pagina) {
       $inicio = 0;
       $pagina = 1;
    }
    else {
       $inicio = ($pagina - 1) * $TAMANO_PAGINA;
    }
    //calculo el total de páginas
    $num_total_registros = (int)($_REQUEST['total'])?$_REQUEST['total']:0;
    $total_paginas = ceil($num_total_registros / $TAMANO_PAGINA);
    
    $limit = 'limit '.$inicio.','.$TAMANO_PAGINA;  
}

$SQL = "select * from Customers order by id desc ". $limit;
//var_dump($SQL);

$Result = $Conn->fetchAll($SQL);


$SQL = "select count(*) as total from Customers limit 1";
$ResultCount = $Conn->fetchAll($SQL);

$count = count($Result);
$haveNext = $ResultCount[0]['total'];
$TotalPages = $haveNext/$TAMANO_PAGINA;
die(json_encode(array('results'=>$Result,  'total'=>$haveNext, 'haveNext'=>($haveNext-($page*$TAMANO_PAGINA))>0?true:false, 'size_page'=>$TAMANO_PAGINA, 'page'=>(int)$page, 'total_pages'=>ceil($TotalPages)  )));