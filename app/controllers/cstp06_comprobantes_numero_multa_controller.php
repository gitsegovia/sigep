<?php
/*
 * Creado el 03/11/2007 a las 04:59:38 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 */
 class Cstp06ComprobantesNumeroMultaController extends AppController {
 	var $name = 'cstp06_comprobantes_numero_multa';
 	var $uses = array ('cstd06_comprobante_numero_multa','cugd05_restriccion_clave', 'ccfd04_cierre_mes');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');



function checkSession(){
				if (!$this->Session->check('Usuario')){
						$this->redirect('/salir/');
						exit();
				}else{
					//$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
					//echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
					$this->requestAction('/usuarios/actualizar_user');
				}
}//fin checksession


function beforeFilter(){
 	$this->checkSession();
 	 echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';
 }

function verifica_SS($i){
    	switch ($i){
    		case 1:return $this->Session->read('SScodpresi');break;
    		case 2:return $this->Session->read('SScodentidad');break;
    		case 3:return $this->Session->read('SScodtipoinst');break;
    		case 4:return $this->Session->read('SScodinst');break;
    		case 5:return $this->Session->read('SScoddep');break;
    		case 6:return $this->Session->read('entidad_federal');break;
    		default:
    		   return "NULO";
    	}
}

function SQLCA($ano=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
         if($ano!=null){
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
         }else{
         	$sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }
         return $sql_re;
}

 function index(){

 $this->verifica_entrada('25');

 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$data = $this->cstd06_comprobante_numero_multa->findAll($this->SQLCA()." and ano_comprobante_multa=" .$this->ano_ejecucion());
	$data1 = $this->ano_ejecucion();
	$data2 = '';
	foreach($data as $data){
	$data1 = $data['cstd06_comprobante_numero_multa']['ano_comprobante_multa'];
	$data2 = $data['cstd06_comprobante_numero_multa']['numero_comprobante_multa'];
	$this->set('data2',$data2);
	}

	$this->set('data1',$data1);


 }



  function guardar(){
 	$this->layout ="ajax";
if( (empty ($this->data['cstp06_comprobantes_numero_multa']['ano_comprobante_multa'])) || (empty ($this->data['cstp06_comprobantes_numero_multa']['numero_comprobante_multa'])))
{
	 	$this->set('datos', array());
 		$this->set('mensajeError', 'FALTAN DATOS');
 		$this->index();
		$this->render("index");

}else{
$ano = $this->data['cstp06_comprobantes_numero_multa']['ano_comprobante_multa'];
$numero = ($this->data['cstp06_comprobantes_numero_multa']['numero_comprobante_multa']);
$sql1 = $this->SQLCA()." and ano_comprobante_multa=" .$ano.";";
if (($this->cstd06_comprobante_numero_multa->findCount($sql1) > 0)){
 			$this->set('datos', array());
			//$this->set('mensajeError', 'ESTE REGISTRO YA EXISTE');
			$this->modificar2();
			$this->render("modificar2");
}else{
$ano = $this->data['cstp06_comprobantes_numero_multa']['ano_comprobante_multa'];
$numero = ($this->data['cstp06_comprobantes_numero_multa']['numero_comprobante_multa']);
$cp = $this->Session->read('SScodpresi');
$ce = $this->Session->read('SScodentidad');
$cti = $this->Session->read('SScodtipoinst');
$ci = $this->Session->read('SScodinst');
$cd = $this->Session->read('SScoddep');
$sql2 = "INSERT INTO cstd06_comprobante_numero_multa (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano_comprobante_multa, numero_comprobante_multa) VALUES (".$cp.",". $ce ."," . $cti . "," . $ci . "," . $cd . "," .$ano. ",". $numero. ");";
		$this->cstd06_comprobante_numero_multa->execute($sql2);
		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');
		$this->set('autor_valido',true);
		$this->index();
		$this->render("index");
		}
}
  }
function modificar2(){
	$this->layout = "ajax";
	if( (empty ($this->data['cstp06_comprobantes_numero_multa']['ano_comprobante_multa'])) || (empty ($this->data['cstp06_comprobantes_numero_multa']['numero_comprobante_multa'])))
{
	 	$this->set('datos', array());
 		$this->set('mensajeError', 'FALTAN DATOS');
 		$this->index();
		$this->render("index");

}else{
$ano = $this->data['cstp06_comprobantes_numero_multa']['ano_comprobante_multa'];
$numero = ($this->data['cstp06_comprobantes_numero_multa']['numero_comprobante_multa']);
$sql1 = $this->SQLCA()." and ano_comprobante_multa=" .$ano.";";
if (($this->cstd06_comprobante_numero_multa->findCount($sql1) > 0)){
 			$this->set('datos', array());
			$ano = $this->data['cstp06_comprobantes_numero_multa']['ano_comprobante_multa'];
$numero = ($this->data['cstp06_comprobantes_numero_multa']['numero_comprobante_multa']);
$sql2 = "UPDATE cstd06_comprobante_numero_multa SET ano_comprobante_multa =" .$ano. ", numero_comprobante_multa=" .$numero." WHERE ".$sql1.";";
		$this->cstd06_comprobante_numero_multa->execute($sql2);
		$this->set('mensaje', 'EL DATO FUE ACTUALIZADO CORRECTAMENTE');
		$this->set('autor_valido',true);
		$this->index();
		$this->render("index");
		}else{
			$this->set('mensajeError', 'NO HAY REGISTROS');
		$this->index();
		$this->render("index");
		}

}


  }



function boton_guardar(){
$this->layout = "ajax";
echo'<script>';
  echo"document.getElementById('guardar').disabled = false;";
  echo"document.getElementById('modificar').disabled = true;";
echo'</script>';
}//fin fucntion


function entrar(){
	$this->layout="ajax";
	if(isset($this->data['cstp06_comprobantes_numero_multa']['login']) && isset($this->data['cstp06_comprobantes_numero_multa']['password'])){
		$l="PROYECTO";
		$c="JJJSAE";
		$user=addslashes($this->data['cstp06_comprobantes_numero_multa']['login']);
		$paswd=addslashes($this->data['cstp06_comprobantes_numero_multa']['password']);
		$cond=$this->SQLCA()." and username='".$user."' and cod_tipo=25 and clave='".$paswd."'";
		if($user==$l && $paswd==$c){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}elseif($this->cugd05_restriccion_clave->findCount($cond)!=0){
			$this->set('autor_valido',true);
			$this->index("autor_valido");
			$this->render("index");
		}else{
			$this->set('mensajeError',"Lo siento, se necesita autorizaci&oacute;n para utilizar este programa");
			$this->set('autor_valido',false);
			$this->index("autor_valido");
			$this->render("index");
		}
	}
}

}//fin clase
?>