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


	 if($_GET["ejercicio"]){             $sql_3 =  'where ejercicio =  '.$_GET["ejercicio"].'  ';                           }
    if($_GET["grupo"]){                  $sql_3.=  'and cod_grupo =  '.$_GET["grupo"].'  ';                                  $tabla=' cfpd01_ano_1_grupo ';                             $codigo =$_GET["grupo"];  }
	if($_GET["partida"]){                $sql_3 .= 'and cod_partida = '.$_GET["partida"].'  ';                               $tabla=' cfpd01_ano_2_partida ';                           $codigo =$_GET["partida"];  }
	if($_GET["generica"]){             $sql_3 .= 'and cod_generica = '.$_GET["generica"].'  ';                         $tabla=' cfpd01_ano_3_generica ';                        $codigo =$_GET["generica"];  }
	if($_GET["especifica"]){           $sql_3 .= 'and cod_especifica = '.$_GET["especifica"].'  ';                    $tabla=' cfpd01_ano_4_especifica ';                     $codigo =$_GET["especifica"];  }
	if($_GET["sub_especifica"]){  $sql_3.= 'and cod_sub_espec = '.$_GET["sub_especifica"].'  ';           $tabla=' cfpd01_ano_5_sub_espec ';                   $codigo =$_GET["sub_especifica"];  }
	if($_GET["auxiliar"]){                 $sql_3.= 'and cod_auxiliar = '.$_GET["auxiliar"].'  ';                                $tabla=' cfpd01_ano_6_auxiliar ';                          $codigo =$_GET["auxiliar"];   }


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

