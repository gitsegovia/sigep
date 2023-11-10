<?php
class ccnp01DatosConcejosComunalController extends AppController {
   var $name = 'ccnp01_datos_concejos_comunal';
   var $uses = array('ccnd01_tipo_directivo','ccnd01_cargos_directivos',"cugd01_republica", "cugd01_estados", "cugd01_municipios",
                     "cugd01_parroquias", "cugd02_direccionsuperior", "ccnd01_concejo_comunal", "cnmd06_parentesco", "cnmd06_profesiones",
                     "cnmd06_oficio", "ccnd01_directiva_familiar", "ccnd01_directiva", "casd01_datos_familiares", "cugd10_imagenes", 'v_ccnd01_directiva');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

function checkSession(){
				if (!$this->Session->check('concejo_comunal')){
						$this->redirect('/salir');
						exit();
				}
}//fin checksession




 function beforeFilter(){
 	$this->checkSession();

 }





 function index(){
 	$this->layout ="ajax";

$conditions  = "     cod_republica=".$this->Session->read('CC_republica')." and cod_estado=".$this->Session->read('CC_estado')." and cod_municipio=".$this->Session->read('CC_municipio')." and cod_parroquia=".$this->Session->read('CC_parroquia')." ";
$conditions .= " and cod_centro=".$this->Session->read('CC_centro')." and cod_concejo=".$this->Session->read('CC_concejo')."";



$pagina=1;
$Tfilas=$this->ccnd01_concejo_comunal->findCount($conditions);
if($Tfilas!=0){
    	$x=$this->ccnd01_concejo_comunal->findAll($conditions, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo ASC",1,$pagina,null);
		$this->set('DATA',$x);
		$this->set('pagina',$pagina);
      	$this->set('siguiente',$pagina+1);
      	$this->set('anterior',$pagina-1);
      	$this->bt_nav($Tfilas,$pagina);
      	$this->set('numT',$Tfilas);
		$this->set('numP',$pagina);

}else{
        $this->set('errorMessage', 'No se encontrar&oacute;n datos');
        $this->set('noExiste',true);
        $this->index();
	    $this->render("index");

	 return;
}//fin else





    $cod_republica = $x[0]["ccnd01_concejo_comunal"]["cod_republica"];
    $cod_estado    = $x[0]["ccnd01_concejo_comunal"]["cod_estado"];
    $cod_municipio = $x[0]["ccnd01_concejo_comunal"]["cod_municipio"];
    $cod_parroquia = $x[0]["ccnd01_concejo_comunal"]["cod_parroquia"];
    $cod_centro    = $x[0]["ccnd01_concejo_comunal"]["cod_centro"];
    $cod_concejo   = $x[0]["ccnd01_concejo_comunal"]["cod_concejo"];
    $zonificacion  = $x[0]["ccnd01_concejo_comunal"]["tipo_zona"];


    $sql = "cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro."  and  cod_concejo='".$cod_concejo."'    ";
    $xx=$this->v_ccnd01_directiva->findAll($sql, null,"cod_republica, cod_estado, cod_municipio, cod_parroquia, cod_centro, cod_concejo, cod_tipo, cod_cargo ASC");
    $this->set('xx', $xx);

    $resultado        = $x[0]["ccnd01_concejo_comunal"]["resultado"];
    $numero_votantes  = $x[0]["ccnd01_concejo_comunal"]["numero_votantes"];

   $porcentaje=(($resultado/$numero_votantes)*100);


switch($zonificacion){
		case "1":
			$zonificacion='Urbanización';
		break;
		case "2":
			$zonificacion='Barrio';
		break;
		case "3":
			$zonificacion='Caserio';
		break;
		case "4":
			$zonificacion='Comuna';
		break;
		case "5":
			$zonificacion='Vialidad';
		break;
}


    $this->set('zonificacion',$zonificacion);
    $this->set('porcentaje',$porcentaje);

	$this->set('republica',$this->cugd01_republica->field('denominacion',  $conditions ="cod_republica=".$cod_republica, $order ="cod_republica ASC"));
	$this->set('estado',$this->cugd01_estados->field('denominacion',       $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado, $order ="cod_estado ASC"));
	$this->set('municipio',$this->cugd01_municipios->field('denominacion', $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio, $order ="cod_municipio ASC"));
	$this->set('parroquia',$this->cugd01_parroquias->field('denominacion', $conditions ="cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia, $order ="cod_parroquia ASC"));

    $sql="select * from cugd01_centros_poblados where cod_republica=".$cod_republica." and cod_estado=".$cod_estado." and cod_municipio=".$cod_municipio." and cod_parroquia=".$cod_parroquia." and cod_centro=".$cod_centro;
	$datos=$this->cugd01_parroquias->execute($sql);
	$this->set('datos',$datos);






 }// fin index













 }//Fin de la clase controller
 ?>