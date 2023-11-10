<?php
if(isset($_GET['valor']) && !empty($_GET['valor'])){
	$valor=sprintf("%01.2f",$_GET['valor']);
	echo $valor;
}

?>