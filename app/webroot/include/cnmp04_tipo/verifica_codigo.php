<style type="text/css">

#msj_aceptar {

			font-family: Arial, Helvetica, sans-serif;
			font-size: 14px;
			font-style: normal;
			line-height: normal;
			font-weight: bold;
			color: #339933;
			background-color: #FFFBEC;
			text-align: left;
			white-space: normal;
			height: 18px;
			width: 98%;
			border: 1px dashed #339966;
			/*padding-top: 4px;
			margin-left: 4px;*/
            padding:5px 5px 1px 5px;
            position:fixed;
            margin-top:-225px;
            margin-left:-340px;
}


</style>



<?php


include("../../../config/database.php");
include("../../../../cake/libs/object.php");
include("../../../../cake/libs/view/helper.php");
include("../../../views/helpers/sisap.php");


class conex extends DATABASE_CONFIG{
	function ver_conex(){
		return $this->default ;
	}//fin function
}//fin class

$conexion = new conex;
$conexion_variables = $conexion->ver_conex();

$sisap = new SisapHelper();

//echo $conexion_variables['login'];


   $sql_1="";
   $tabla = "";
   $sql_3 = "";
   $codigo = "";

$sql_1 ="Select * from cnmd04_tipo where cod_nivel_i=".$_GET['cod_nivel_i']."  ";

$sql_re = $sql_1;

$output='';
$existe=0;


 $connection = pg_connect("host=".$conexion_variables['host']."  port=".$conexion_variables['port']." dbname=".$conexion_variables['database']."  user=".$conexion_variables['login']." password=".$conexion_variables['password']." ");


$result=pg_query($sql_re)or die ("La Consulta Fallo");
if(pg_fetch_array($result)){ $existe++;}pg_close( $connection);//FIN WHILE

if($existe!=0){?>
<div id="msj_aceptar" >
    <img src="/sisap/img/warning.png"/>
	   <font color="#FF0000"> EL CÓDIGO YA EXISTE</font>
</div>
<input type="hidden" name="existe" value="si" id="existe"/>
<input type="hidden" name="aux_codigo" value="<?php echo  $_GET['cod_nivel_i']; ?>" id="aux_codigo"/>
<?php }else{?>

<input type="hidden" name="existe" value="no" id="existe"/>
<input type="hidden" name="aux_codigo" value="<?php echo  $_GET['cod_nivel_i']; ?>"  id="aux_codigo"/>

<?php } ?>
