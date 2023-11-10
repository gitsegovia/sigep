<?php
/*
 * Creado el 03/11/2007 a las 04:59:38 PM
 * Herramienta: EasyEclipse.
 * Proyecto: SIGEP
 */
 class Cstp06ComprobantesNumeroOtrosController extends AppController {
 	var $name = 'cstp06_comprobantes_numero_otros';
 	var $uses = array ('cstd06_comprobantes_numero_otros');
 	var $helpers = array ('Html','Ajax','Javascript','Sisap');



function checkSession()
    {
        if (!$this->Session->check('Usuario'))
        {
            $this->redirect('/salir');
            exit();
        }
    }


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


 function index(){
 	$this->layout ="ajax";
 	$this->set('entidad_federal', $this->Session->read('entidad_federal'));
	$data = $this->cstd06_comprobantes_numero_otros->findAll("cod_presi= " . $this->Session->read('SScodpresi'));
	$data1 = '';
	$data2 = '';
	foreach($data as $data){
	$data1 = $data['cstd06_comprobantes_numero_otros']['ano_comprobante_otros'];
	$data2 = $data['cstd06_comprobantes_numero_otros']['numero_comprobante_otros'];
	$this->set('data1',$data1);
	$this->set('data2',$data2);
	}
 }



  function guardar(){
 	$this->layout ="ajax";
if( (empty ($this->data['cstp06_comprobantes_numero_otros']['ano_comprobante_otros'])) || (empty ($this->data['cstp06_comprobantes_numero_otros']['numero_comprobante_otros'])))
{
	 	$this->set('datos', array());
 		$this->set('mensajeError', 'FALTAN DATOS');
 		$this->index();
		$this->render("index");

}else{
$ano = $this->data['cstp06_comprobantes_numero_otros']['ano_comprobante_otros'];
$numero = ($this->data['cstp06_comprobantes_numero_otros']['numero_comprobante_otros']);
$sql1 = "ano_comprobante_otros=" .$ano.";";
if (($this->cstd06_comprobantes_numero_otros->findCount() > 0)){
 			$this->set('datos', array());
			$this->set('mensajeError', 'ESTE REGISTRO YA EXISTE');
			$this->index();
			$this->render("index");
}else{
$ano = $this->data['cstp06_comprobantes_numero_otros']['ano_comprobante_otros'];
$numero = ($this->data['cstp06_comprobantes_numero_otros']['numero_comprobante_otros']);
$cp = $this->Session->read('SScodpresi');
$ce = $this->Session->read('SScodentidad');
$cti = $this->Session->read('SScodtipoinst');
$ci = $this->Session->read('SScodinst');
$sql2 = "INSERT INTO cstd06_comprobantes_numero_otros (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano_comprobante_otros, numero_comprobante_otros) VALUES (".$cp.",". $ce ."," . $cti . "," . $ci . "," .$ano. ",". $numero. ");";
		$this->cstd06_comprobantes_numero_otros->execute($sql2);
		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');
		$this->index();
		$this->render("index");
		}
}
  }
function modificar2(){
	$this->layout = "ajax";
	if( (empty ($this->data['cstp06_comprobantes_numero_otros']['ano_comprobante_otros'])) || (empty ($this->data['cstp06_comprobantes_numero_otros']['numero_comprobante_otros'])))
{
	 	$this->set('datos', array());
 		$this->set('mensajeError', 'FALTAN DATOS');
 		$this->index();
		$this->render("index");

}else{
$ano = $this->data['cstp06_comprobantes_numero_otros']['ano_comprobante_otros'];
$numero = ($this->data['cstp06_comprobantes_numero_otros']['numero_comprobante_otros']);
$sql1 = "ano_comprobante_otros=" .$ano.";";
if (($this->cstd06_comprobantes_numero_otros->findCount() > 0)){
 			$this->set('datos', array());
			$ano = $this->data['cstp06_comprobantes_numero_otros']['ano_comprobante_otros'];
$numero = ($this->data['cstp06_comprobantes_numero_otros']['numero_comprobante_otros']);
$sql2 = "UPDATE cstd06_comprobantes_numero_otros SET ano_comprobante_otros =" .$ano. ", numero_comprobante_otros=" .$numero.";";
		$this->cstd06_comprobantes_numero_otros->execute($sql2);
		$this->set('mensaje', 'EL DATO FUE GUARDADO CORRECTAMENTE');
		$this->index();
		$this->render("index");
		}else{
			$this->set('mensajeError', 'NO HAY REGISTROS');
		$this->index();
		$this->render("index");
		}

}


  }
 }
?>