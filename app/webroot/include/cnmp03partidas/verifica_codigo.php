<?php


include("../../../config/database.php");

class conex extends DATABASE_CONFIG{
	function ver_conex(){
		return $this->default ;
	}//fin function
}//fin class

$conexion = new conex;
$conexion_variables = $conexion->ver_conex();

//echo $conexion_variables['login'];


   $sql_1="";
   $tabla = "";
   $sql_3 = "";
   $codigo = "";

$sql_3 =  'where cod_transaccion =  '.$_GET["cod_transaccion"].'  ';
$tabla=' cnmd03_partidas ';
$codigo =$_GET["cod_transaccion"];

$sql_1 ="Select * from ";

$sql_re = $sql_1.$tabla.$sql_3;

$output='';
$existe=0;


 $connection = pg_connect("host=".$conexion_variables['host']."  port=".$conexion_variables['port']." dbname=".$conexion_variables['database']."  user=".$conexion_variables['login']." password=".$conexion_variables['password']." ");


$result=pg_query($sql_re)or die ("La Consulta Fallo");
if(pg_fetch_array($result)){ $existe++;}pg_close( $connection);//FIN WHILE

if($existe!=0){?>



<div style="width:150px; border:solid 1px #FF0000; background:#FFFFCC;">
    <img src="/sisap/img/warning.png"/>
	   <font color="#FF0000"> El Codigo ya existe</font>
</div>

<input type="hidden" name="existe" value="si" id="existe"/>
<input type="hidden" name="aux_codigo" value="<?php echo  $codigo; ?>" id="aux_codigo"/>



<?php }else{?>

<input type="hidden" name="existe" value="no" id="existe"/>
<input type="hidden" name="aux_codigo" value="<?php echo  $codigo; ?>"  id="aux_codigo"/>

<?php } ?>
