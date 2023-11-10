<?php
class Caop05ordencompranotaentregaController extends AppController {

   var $name = "caop05_ordencompra_nota_entrega";
   var $uses = array('v_cscd05_ordencompra_nota_entrega','cscd02_solicitud_numero','cscd02_solicitud_encabezado', 'ccfd04_cierre_mes', 'cugd03_acta_anulacion_numero', 'cugd03_acta_anulacion_cuerpo','cscd02_solicitud_cuerpo','cscd03_cotizacion_encabezado','cscd03_cotizacion_cuerpo', 'cscd04_ordencompra_parametros', 'cscd04_ordencompra_encabezado', 'cscd04_ordencompra_autorizacion_cuerpo', 'cscd04_ordencompra_a_pago_partidas', 'cscd04_ordencompra_anticipo_partidas','ccfd03_instalacion', 'cscd04_ordencompra_partidas', 'cpcd02', 'cscd05_ordencompra_nota_entrega_encabezado', 'cscd05_ordencompra_nota_entrega_cuerpo', 'cscd01_unidad_medida', 'v_cscd05_notaentrega_incompleta','select_orden_compra','select_nota_entrega');
   var $helpers = array('Html','Ajax','Javascript','Sisap');

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
 }//fin function



function verifica_SS($i){
			/**
			 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
			 * para ser insertados en todas las tablas.
			 * */
			switch ($i){
				case 1:return $this->Session->read('SScodpresi');break;
				case 2:return $this->Session->read('SScodentidad');break;
				case 3:return $this->Session->read('SScodtipoinst');break;
				case 4:return $this->Session->read('SScodinst');break;
				case 5:return $this->Session->read('SScoddep');break;
				case 6:return $this->Session->read('entidad_federal');break;
				default:
					 return "NULO";


			}//fin switch
		}//fin verifica_SS

		function SQLCA($ano=null){//sql para busqueda de codigos de arranque con y sin año
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
		}//fin funcion SQLCA

		function SQLCAIN($ano=null){//sql para busqueda de codigos de arranque con y sin año
				 $sql_re = $this->verifica_SS(1).",";
				 $sql_re .= $this->verifica_SS(2).",";
				 $sql_re .=  $this->verifica_SS(3).",";
				 $sql_re .= $this->verifica_SS(4).",";
				 if($ano!=null){
					 $sql_re .= $this->verifica_SS(5).",";
						$sql_re .= $ano."";
				 }else{
					 $sql_re .=  $this->verifica_SS(5)."";
				 }
				 return $sql_re;
		}//fin funcion SQLCAIN



   function Formato1($monto) {
    $monto = preg_replace("/[^0-9\.]/", "", str_replace(',','.',$monto));
    if (substr($monto,-3,1)=='.') {
        $sents = '.'.substr($monto,-2);
        $monto = substr($monto,0,strlen($monto)-3);
    } elseif (substr($monto,-2,1)=='.') {
        $sents = '.'.substr($monto,-1);
        $monto = substr($monto,0,strlen($monto)-2);
    } else {
        $sents = '.00';
    }
    $monto = preg_replace("/[^0-9]/", "", $monto);
    return number_format($monto.$sents,2,'.','');
    }

function Formato2($monto){return number_format($monto,2,",",".");}


function index(){



$this->layout = "ajax";
 $ano='';
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
 $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($this->condicion()."  and ano_orden_compra='".$this->ano_ejecucion()."' AND condicion_actividad=1 AND tipo_orden=1 AND (entrega_completa=1)"." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');

 //print_r($lista);
 if($lista != null){
 	$this->set('lista_numero',$lista);
 }else{
 	$this->set('lista_numero', array());
 }

$this->set('ano',$ano);

}//fin function




function selecion($var1=null){
  $this->layout = "ajax";
  $this->Session->delete('lista_cscd03_cotizacion_cuerpo_entregado');
  $this->Session->delete('cont');
  $ano = $this->ano_ejecucion();
 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';

 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
 $lista = $this->select_orden_compra->generateList($this->condicion()."  and ano_orden_compra='".$this->ano_ejecucion()."' AND condicion_actividad=1 AND tipo_orden=1 AND (entrega_completa=1)"." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", ' numero_orden_compra ASC', null, '{n}.select_orden_compra.numero_orden_compra', '{n}.select_orden_compra.beneficiario');
 //print_r($lista);
 if($lista != null){
 	$this->set('lista_numero',$lista);
 }else{
 	$this->set('lista_numero', array());
 }

 $this->set('numero_orden_compra', $var1);



if($var1==null){

$this->index();
$this->render('index');

}else{




$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$var1.'  and  ano_orden_compra='.$ano.' ';
$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);
$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion);

$numero_datos_aux =  $numero_datos;
foreach($numero_datos_aux as $aux){
	$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
	$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
	$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
	$numero_cotizacion = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
	$ano_cotizacion = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
}//fin foreach

$this->set('ano_cotizacion', $ano_cotizacion);
$this->set('numero_cotizacion', $numero_cotizacion);
$this->set('rif', $rif);

$opc2 = $this->cscd05_ordencompra_nota_entrega_encabezado->findCount($conditions = $this->condicion()." and upper(rif)=upper('$rif') and ano_nota_entrega='$ano' and numero_orden_compra='".$numero_orden_compra."'   ");

$opc2++;

$rif_datos = $this->cpcd02->findAll("upper(rif)=upper('$rif')");
foreach($rif_datos as $aux_2){
	$denominacion_rif = $aux_2['cpcd02']['denominacion'];
	$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
}//fin foreach



$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_cotizacion='.$ano_cotizacion." and numero_cotizacion='$numero_cotizacion' and upper(rif)=upper('$rif')";
$lista = $this->cscd03_cotizacion_cuerpo->findAll($condicion,null, 'numero_cotizacion ASC', null);
$this->set('lista_cscd03_cotizacion_cuerpo', $lista);
$this->set('unidades', $this->cscd01_unidad_medida->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null));


$this->set('cont_aux_nota_entrega', $opc2);
$this->set('ano_orden_compra_pago', $ano);
$this->set('numero_orden_compra_pago', $numero_orden_compra);
$this->set('datos_orden_compra', $numero_datos);
$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
$this->set('denominacion_rif', $denominacion_rif);
$this->set('direccion_comercial_rif', $direccion_comercial_rif);


}//fin else

}//fin function


















function agregar_todos_productos($ano_cotizacion=null,$numero_cotizacion=null, $rif=null ){

$this->layout = "ajax";

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_cotizacion='.$ano_cotizacion." and numero_cotizacion='$numero_cotizacion' and upper(rif)=upper('$rif')";
$lista_cscd03_cotizacion_cuerpo = $this->cscd03_cotizacion_cuerpo->findAll($condicion,null, 'numero_cotizacion ASC', null);

$i = 0; $f=0;
$cont = 0;
$opcion = "no";
$ano = $this->ano_ejecucion();
$lista_cscd03_cotizacion_cuerpo_entregado = "";
$cont = $this->Session->read('cont');
$lista_cscd03_cotizacion_cuerpo_entregado = $this->Session->read('lista_cscd03_cotizacion_cuerpo_entregado');


 if(isset($lista_cscd03_cotizacion_cuerpo)){
  if($lista_cscd03_cotizacion_cuerpo!=null){
    foreach($lista_cscd03_cotizacion_cuerpo as $ve){ $opcion = "no";
    	$descripcion = $ve['cscd03_cotizacion_cuerpo']['descripcion'];
		$cantidad_faltante = $ve['cscd03_cotizacion_cuerpo']['cantidad'] - $ve['cscd03_cotizacion_cuerpo']['cantidad_entregada'];
		$ve['cscd03_cotizacion_cuerpo']['cantidad'] = $cantidad_faltante;
          if($ve['cscd03_cotizacion_cuerpo']['codigo_prod_serv'] != 3228 && $cantidad_faltante!=0){


						for($ii=1; $ii<=$cont; $ii++){
						  if($lista_cscd03_cotizacion_cuerpo_entregado[$ii]['secuencia'] == $i){
						    $opcion = "si";
						  }//fin function
						}//fin function



						if($opcion=="no"){	$cont++;

								$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['codigo_prod_serv'] = $ve['cscd03_cotizacion_cuerpo']['codigo_prod_serv'];
								$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['cod_medida']       = $ve['cscd03_cotizacion_cuerpo']['cod_medida'];
								$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['cantidad']         = $cantidad_faltante;
								$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['descripcion']      = $ve['cscd03_cotizacion_cuerpo']['descripcion'];
								$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['precio_unitario']  = $ve['cscd03_cotizacion_cuerpo']['precio_unitario'];
								$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['secuencia']        = $i;

								echo "<script>document.getElementById('fila_".$i."').style.background='#ffffff';</script>";

								$f += $cantidad_faltante;

						}//fin if



             }//fin if
        $i++;
    }//fin foreach
  }//fin if
 }//fin if



echo'<script>';

echo 'cont = document.getElementById("cuenta_i").value;';
echo'if(cont=='.$cont.'){';
echo'document.getElementById("entrega_completa_radio_1").checked=true;';
echo'}else {';
echo'document.getElementById("entrega_completa_radio_2").checked=true;';
echo'}//fin';
echo'}//fin function';
echo'</script>';

$this->set('Message_existe', "Los Productos fueron agregados");

$this->Session->write('cont', $cont);
$this->Session->write('lista_cscd03_cotizacion_cuerpo_entregado', $lista_cscd03_cotizacion_cuerpo_entregado);
$this->set('lista_cscd03_cotizacion_cuerpo_entregado', $lista_cscd03_cotizacion_cuerpo_entregado);
$this->set('unidades', $this->cscd01_unidad_medida->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null));


$this->render('datos2');

}//fin function


















function datos2($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null){

$this->layout = "ajax";

$cont = 0;
$opcion = "no";
$ano = $this->ano_ejecucion();
$var4 = $this->cscd03_cotizacion_cuerpo->field('cscd03_cotizacion_cuerpo.descripcion', $conditions = $this->condicion()." and ano_cotizacion='$ano' and cscd03_cotizacion_cuerpo.numero_cotizacion='$var4' and codigo_prod_serv='$var1'", $order =null);
$lista_cscd03_cotizacion_cuerpo_entregado = "";
$cont = $this->Session->read('cont');
$lista_cscd03_cotizacion_cuerpo_entregado = $this->Session->read('lista_cscd03_cotizacion_cuerpo_entregado');


for($i=1; $i<=$cont; $i++){
  if($lista_cscd03_cotizacion_cuerpo_entregado[$i]['secuencia'] == $var6){
    $opcion = "si";
  }//fin function
}//fin function



if($opcion=="no"){
//$var4 = str_replace('7sisap7', '#', $var4);
$cont++;

$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['codigo_prod_serv'] = $var1;
$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['cod_medida']       = $var2;
$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['cantidad']         = $var3;
$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['descripcion']      = $var4;
$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['precio_unitario']  = $var5;
$lista_cscd03_cotizacion_cuerpo_entregado[$cont]['secuencia']        = $var6;

}else{


$errorMessage = "YA FUE AGREGADO ESTE PRODUCTO";
$this->set('errorMessage', $errorMessage);

}//fin else



echo'<script>';
echo "document.getElementById('fila_".$var6."').style.background='#ffffff';";
echo 'cont = document.getElementById("cuenta_i").value;';
echo'if(cont=='.$cont.'){';
echo'document.getElementById("entrega_completa_radio_1").checked=true;';
echo'}else {';
echo'document.getElementById("entrega_completa_radio_2").checked=true;';
echo'}//fin';
echo'}//fin function';
echo'</script>';



$this->Session->write('cont', $cont);
$this->Session->write('lista_cscd03_cotizacion_cuerpo_entregado', $lista_cscd03_cotizacion_cuerpo_entregado);
$this->set('lista_cscd03_cotizacion_cuerpo_entregado', $lista_cscd03_cotizacion_cuerpo_entregado);
$this->set('unidades', $this->cscd01_unidad_medida->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null));

}//fin function








function verifica_nota_entrega($var1=null, $var2=null){


   $this->layout = "ajax";
   $cont = 0;
   $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
   $ano = $this->ano_ejecucion();
   $cont = $this->cscd05_ordencompra_nota_entrega_cuerpo->findCount($condicion." and rif='".$var1."' and ano_nota_entrega='".$ano."' and  numero_nota_entrega='".$var2."'  ");






if($var2=="S/N" || $var2=="SN" || $var2=="S-N"){


	                    echo'<script>';
							echo 'document.getElementById("guardar").disabled=true;';
					    echo'</script>';

					    $this->set('errorMessage', 'Número de la nota de entrega es invalido proveedor');


}else{


				if($cont==0){

					   echo'<script>';
							echo 'document.getElementById("guardar").disabled=false;';
					   echo'</script>';


				   }else{

					    echo'<script>';
							echo 'document.getElementById("guardar").disabled=true;';
					    echo'</script>';

					    $this->set('errorMessage', 'La nota de entrega ya existe para este proveedor');

				   }//fin else

}//fin else



}//function

















function guardar(){
$this->layout = "ajax";

$ano_orden_compra    = $this->data['caop05_ordencompra_nota_entrega']['ano_orden_compra'];
$numero_orden_compra = $this->data['caop05_ordencompra_nota_entrega']['numero_orden_compra'];
$fecha_cotizacion    = $this->data['caop05_ordencompra_nota_entrega']['fecha_cotizacion'];
$ano_orden_compra_pago    = $this->data['caop05_ordencompra_nota_entrega']['ano_orden_compra_pago'];
$numero_orden_compra_pago = $this->data['caop05_ordencompra_nota_entrega']['numero_orden_compra_pago'];
$fecha_pago    = $this->data['caop05_ordencompra_nota_entrega']['fecha_pago'];
$rif   = $this->data['caop05_ordencompra_nota_entrega']['rif'];
$observaciones    =   $this->data['caop05_ordencompra_nota_entrega']['observaciones'];
$entrega_completa    = $this->data['caop05_ordencompra_nota_entrega']['entrega_completa'];

if(!empty($ano_orden_compra)){
if(!empty($numero_orden_compra)){
if(!empty($fecha_cotizacion)){
if(!empty($ano_orden_compra_pago)){
if(!empty($numero_orden_compra_pago)){
if(!empty($fecha_pago)){
if(!empty($rif)){
if(!empty($observaciones)){
if(!empty($entrega_completa)){


$nro_cotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.numero_cotizacion', $conditions = $this->condicion()." and cscd03_cotizacion_encabezado.numero_ordencompra='$numero_orden_compra'  and ano_cotizacion='".$this->ano_ejecucion()."' ", $order =null);
$ano_cotizacion = $this->cscd03_cotizacion_encabezado->field('cscd03_cotizacion_encabezado.ano_cotizacion', $conditions = $this->condicion()." and cscd03_cotizacion_encabezado.numero_ordencompra='$numero_orden_compra'  and ano_cotizacion='".$this->ano_ejecucion()."' ", $order =null);



$cont = $this->Session->read('cont');
$lista_cscd03_cotizacion_cuerpo_entregado = $this->Session->read('lista_cscd03_cotizacion_cuerpo_entregado');



$cantidad_entregada_aux1 = 0;
$cantidad_entregada_aux2 = 0;
$cantidad_entregada_aux3 = 0;

$conta_cantdida          = $this->cscd05_ordencompra_nota_entrega_cuerpo->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion, SUM(cantidad) as cantidad from cscd03_cotizacion_cuerpo                      where ".$this->condicion()." and ano_cotizacion='$ano_cotizacion' and numero_cotizacion='$nro_cotizacion' and  upper(rif)=upper('$rif')  and codigo_prod_serv != 3228 GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion; " );
$cantidad_entregada_aux2 = $conta_cantdida[0][0]['cantidad'];

$conta_cantdida          = $this->cscd05_ordencompra_nota_entrega_cuerpo->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion, SUM(cantidad_entregada) as cantidad_entregada from cscd03_cotizacion_cuerpo where ".$this->condicion()." and ano_cotizacion='$ano_cotizacion' and numero_cotizacion='$nro_cotizacion' and  upper(rif)=upper('$rif')    and codigo_prod_serv != 3228 GROUP BY cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_cotizacion, numero_cotizacion; " );
$cantidad_entregada_aux3 = $conta_cantdida[0][0]['cantidad_entregada'];
$cantidad_entregada_aux1 = $cantidad_entregada_aux3;

for($i=1; $i<=$cont; $i++){  $cantidad_entregada_aux1 += $this->data['caop05_ordencompra_notaentrega']['cantidad_'.($i-1)]; }//fin for

$cantidad_entregada_aux2 = $this->Formato2($cantidad_entregada_aux2);
$cantidad_entregada_aux2 = $this->Formato1($cantidad_entregada_aux2);

$cantidad_entregada_aux1 = $this->Formato2($cantidad_entregada_aux1);
$cantidad_entregada_aux1 = $this->Formato1($cantidad_entregada_aux1);


if($cantidad_entregada_aux2!=$cantidad_entregada_aux1){
	        if($cantidad_entregada_aux2>$cantidad_entregada_aux1){
               $cantidad_entregada_aux2 = $cantidad_entregada_aux2 - 0.01;
	  }else if($cantidad_entregada_aux2<$cantidad_entregada_aux1){
               $cantidad_entregada_aux2 = $cantidad_entregada_aux2 + 0.01;
	  }
}


if($cantidad_entregada_aux2==$cantidad_entregada_aux1){$entrega_completa=2;}else{$entrega_completa=1;}



$sql="BEGIN; INSERT INTO cscd05_ordencompra_nota_entrega_encabezado(cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, rif, ano_nota_entrega, numero_nota_entrega, ano_orden_compra, numero_orden_compra, observaciones, entrega_completa, fecha_nota_entrega)";
$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$rif."', '".$ano_orden_compra_pago."', '".$numero_orden_compra_pago."', '".$ano_orden_compra."', '".$numero_orden_compra."', '".$observaciones."', '".$entrega_completa."','".$this->Cfecha($fecha_pago, 'A-M-D')."');";
$sw1 = $this->cscd05_ordencompra_nota_entrega_encabezado->execute($sql);



if($sw1 > 1){
	$sql_update_cotizacion ="";
	for($i=1; $i<=$cont; $i++){
			$lista_cscd03_cotizacion_cuerpo_entregado[$i]['codigo_prod_serv'] ;
			$lista_cscd03_cotizacion_cuerpo_entregado[$i]['cod_medida'];
			$lista_cscd03_cotizacion_cuerpo_entregado[$i]['cantidad'];
			$lista_cscd03_cotizacion_cuerpo_entregado[$i]['descripcion'];
			$lista_cscd03_cotizacion_cuerpo_entregado[$i]['precio_unitario'];
			$lista_cscd03_cotizacion_cuerpo_entregado[$i]['secuencia'];
			$cantidad_entregada = $this->data['caop05_ordencompra_notaentrega']['cantidad_'.($i-1)];
			$codigo_prod_serv = $lista_cscd03_cotizacion_cuerpo_entregado[$i]['codigo_prod_serv'];
			$sql ="INSERT INTO cscd05_ordencompra_nota_entrega_cuerpo(cod_presi, cod_entidad, cod_tipo_inst ,cod_inst, cod_dep, rif, ano_nota_entrega, numero_nota_entrega, codigo_prod_serv, descripcion, cod_medida, cantidad,  precio_unitario)";
			$descripcion = $lista_cscd03_cotizacion_cuerpo_entregado[$i]['descripcion'];
			$descripcion=str_replace("'","&rsquo;", $descripcion);
			$sql.="VALUES ('".$this->Session->read('SScodpresi')."', '".$this->Session->read('SScodentidad')."', '".$this->Session->read('SScodtipoinst')."', '".$this->Session->read('SScodinst')."', '".$this->Session->read('SScoddep')."', '".$rif."', '".$ano_orden_compra_pago."', '".$numero_orden_compra_pago."', '".$lista_cscd03_cotizacion_cuerpo_entregado[$i]['codigo_prod_serv']."',  '".$descripcion."',  '".$lista_cscd03_cotizacion_cuerpo_entregado[$i]['cod_medida']."',  '".$cantidad_entregada."',  '".$lista_cscd03_cotizacion_cuerpo_entregado[$i]['precio_unitario']."')";
			$this->cscd05_ordencompra_nota_entrega_cuerpo->execute($sql);
			$sql_update_cotizacion .= "UPDATE cscd03_cotizacion_cuerpo SET cantidad_entregada=cantidad_entregada + '$cantidad_entregada' WHERE ".$this->condicion()." and ano_cotizacion='$ano_cotizacion' and numero_cotizacion='$nro_cotizacion' and codigo_prod_serv='$codigo_prod_serv' and upper(rif)=upper('$rif'); ";
	}//fin for

	$sql_update_ocompra="UPDATE cscd04_ordencompra_encabezado SET entrega_completa='$entrega_completa' WHERE ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra' and ".$this->condicion()."; ";
	$ucotizacion = $this->cscd03_cotizacion_cuerpo->execute($sql_update_cotizacion);
	if($ucotizacion > 1){
		  $ucotizacion = $this->cscd03_cotizacion_cuerpo->execute($sql_update_ocompra);
			if($ucotizacion > 1){
				$this->cscd05_ordencompra_nota_entrega_cuerpo->execute("COMMIT;");
				$Message_existe = "LOS DATOS FUERON ALMACENADOS";
				$this->set('Message_existe', $Message_existe);

			}else{
				$this->set('errorMessage', 'Los datos no fueron almacenados');
				$this->cscd05_ordencompra_nota_entrega_cuerpo->execute("ROLLBACK;");
			}

	}else{
		$this->set('errorMessage', 'Los datos no fueron almacenados');
		$this->cscd05_ordencompra_nota_entrega_cuerpo->execute("ROLLBACK;");
	}




}else{
	$this->set('errorMessage', 'Los datos no fueron almacenados');
	$this->cscd05_ordencompra_nota_entrega_cuerpo->execute("ROLLBACK;");

}


	 $ano='';
	 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').'';
	 $ano = $this->ano_ejecucion();
	 $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_orden_compra='.$ano.'';
	 $lista = $this->cscd04_ordencompra_encabezado->generateList($this->condicion()." AND condicion_actividad=1 AND tipo_orden=1 AND (entrega_completa=1)", ' numero_orden_compra ASC', null, '{n}.cscd04_ordencompra_encabezado.numero_orden_compra', '{n}.cscd04_ordencompra_encabezado.rif');
	 //print_r($lista);
	 if($lista != null){
	 	$this->concatena($lista, 'lista_numero');
	 }else{
	 	$this->set('lista_numero', array());
	 }

}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}
}else{	$this->set('errorMessage', 'Los datos no pueden ser almacenados');}


$this->index();
$this->render('index');

}//function guardar








function show_buscar($var=null){
	$this->layout="ajax";
	//echo "el var es: ".$var;
	if($var!=null){
		echo "<script>".
			"if(document.getElementById('ano_ejecucion').value==''){
				fun_msj('EL A&Ntilde;O NO PUEDE ESTAR VACIO');
			}else{ show_save();}" .
		"</script>";
	}else{
		echo "<script>".
			"hide_save();" .
		"</script>";
	}

}











function consulta_index($var1=null){

$this->layout = "ajax";
$pag_num = 0;
$opcion = 'si';

if(!empty($this->data['caop05_ordencompra_nota_entrega']['ano_ejecucion'])){
$_SESSION['ano_compra'] = $this->data['caop05_ordencompra_nota_entrega']['ano_ejecucion'];
}else{$_SESSION['ano_compra'] = $this->ano_ejecucion();}

$ano = $_SESSION['ano_compra'];

$this->set('ano', $ano);
$listaCompra = $this->select_nota_entrega->generateList($conditions = $this->condicion()."  and ano_orden_compra='".$ano."' "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", $order = 'numero_orden_compra ASC', $limit = null, '{n}.select_nota_entrega.numero_orden_compra', '{n}.select_nota_entrega.beneficiario');
$this->set('listaCompra', $listaCompra);


if($var1!=null){
if($var1=='si'){

$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
$array = $this->cscd05_ordencompra_nota_entrega_encabezado->findAll($condicion. 'and ano_orden_compra = '.$ano , 'DISTINCT ano_orden_compra, numero_orden_compra, numero_nota_entrega ', 'ano_orden_compra, numero_orden_compra, numero_nota_entrega ASC', null);
$i = 0;
foreach($array as $aux){
$numero[$i]['ano_orden_compra'] = $aux['cscd05_ordencompra_nota_entrega_encabezado']['ano_orden_compra'];
$numero[$i]['numero_orden_compra'] = $aux['cscd05_ordencompra_nota_entrega_encabezado']['numero_orden_compra'];
$numero[$i]['numero_anticipo'] = $aux['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
$i++;
} $i--;


if(!empty($this->data['caop05_ordencompra_nota_entrega']['numero_orden_compra'] )){
for($a=0; $a<=$i; $a++){
if($this->data['caop05_ordencompra_nota_entrega']['numero_orden_compra'] == $numero[$a]['numero_orden_compra']){$pag_num = $a; $opcion='si'; break;}else{$opcion='no';}
}//fin for

if($opcion=='si'){$this->consulta($pag_num);$this->render('consulta');
}else if($opcion=='no'){
$this->set('errorMessage', 'No existen datos');
}//fin else

}else{$this->consulta($pag_num);$this->render('consulta');}//fin else



}//fin if
}//fin if

$this->set('entidad_federal', $this->Session->read('entidad_federal'));

}//fin function






function buscar_year($var1=null){

  $this->layout = "ajax";
  $SScoddeporig             =       $this->Session->read('SScoddeporig');
  $SScoddep                 =       $this->Session->read('SScoddep');
  $Modulo                   =       $this->Session->read('Modulo');


  $condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';


  //$lista = $this->cscd05_ordencompra_nota_entrega_encabezado->generateList($condicion." and ano_orden_compra=".$var1, ' numero_orden_compra ASC', null, '{n}.cscd05_ordencompra_nota_entrega_encabezado.numero_orden_compra', '{n}.cscd05_ordencompra_nota_entrega_encabezado.numero_orden_compra');
  //$this->AddCero('compras', $lista);

  $listaCompra = $this->select_nota_entrega->generateList($condicion."  and ano_orden_compra='".$var1."' "." and (cod_obra!='' and cod_obra!='0' and cod_obra IS NOT NULL)", $order = 'numero_orden_compra ASC', $limit = null, '{n}.select_nota_entrega.numero_orden_compra', '{n}.select_nota_entrega.beneficiario');
  $this->set('compras', $listaCompra);
	$this->Session->write('ano_compra', $var1);

}//fin function







function consulta($pag_num=null){
	$this->layout = "ajax";
	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').'';
	if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
	$this->set('ano_compra', $ano);
	$this->set('ano_ejecucion', $this->ano_ejecucion());

	$array = $this->cscd05_ordencompra_nota_entrega_encabezado->findAll($condicion. ' and ano_orden_compra = '.$ano , 'DISTINCT ano_orden_compra, numero_orden_compra, numero_nota_entrega, rif ', 'ano_orden_compra, numero_orden_compra, numero_nota_entrega ASC', null);
	//pr($array);
	$i = 0;
	if($pag_num==null){$pag_num=0;}

	foreach($array as $aux){
		$numero[$i]['ano_orden_compra'] = $aux['cscd05_ordencompra_nota_entrega_encabezado']['ano_orden_compra'];
		$numero[$i]['numero_orden_compra'] = $aux['cscd05_ordencompra_nota_entrega_encabezado']['numero_orden_compra'];
		$numero[$i]['numero_nota_entrega'] = $aux['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
		$num_notaentrega = $aux['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
		$numero[$i]['rif'] = $aux['cscd05_ordencompra_nota_entrega_encabezado']['rif'];
		$rif = $aux['cscd05_ordencompra_nota_entrega_encabezado']['rif'];
		$i++;
	} $i--;


	if($pag_num!=0){//echo '1';
		$num_notaentrega = $numero[$pag_num]['numero_nota_entrega'];
		$array2 = $this->cscd05_ordencompra_nota_entrega_encabezado->findAll($cond = $condicion.' and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra']." and numero_nota_entrega='$num_notaentrega' and ano_nota_entrega=".$ano.'  ' , null, 'ano_orden_compra, numero_orden_compra, numero_nota_entrega ASC', null);
		foreach($array2 as $aux222){
			$numero2['observaciones'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['observaciones'];
			$numero2['entrega_completa'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['entrega_completa'];
			$numero2['rif'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['rif'];
			$rif = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['rif'];
			$numero2['fecha_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['fecha_nota_entrega'];
			$numero2['numero_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
			$num_notaentrega = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
			$numero2['ano_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['ano_nota_entrega'];
		}//fin

	}else{//fin if
	//echo '2';
		$array2 = $this->cscd05_ordencompra_nota_entrega_encabezado->findAll($cond = $condicion.' and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra']." and numero_nota_entrega='$num_notaentrega' and ano_nota_entrega=".$ano.'  ' , null, 'ano_orden_compra, numero_orden_compra, numero_nota_entrega ASC', null);
		foreach($array2 as $aux222){
			$numero2['observaciones'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['observaciones'];
			$numero2['entrega_completa'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['entrega_completa'];
			$numero2['rif'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['rif'];
			$rif = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['rif'];
			$numero2['fecha_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['fecha_nota_entrega'];
			$numero2['numero_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
			$num_notaentrega = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
			$numero2['ano_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['ano_nota_entrega'];
		}//fin
	}
//	echo $cond;
//pr($array2);

//echo $condicion.' and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra']." and numero_nota_entrega='$num_notaentrega' and ano_nota_entrega='$ano'";
	if(isset($numero[$pag_num]['numero_orden_compra'])){
		$num_notaentrega = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
		$datos_orden_compra_pago_encabezado = $this->cscd05_ordencompra_nota_entrega_encabezado->findAll($conditions = $cond2 =$condicion.' and numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].' and ano_orden_compra='.$numero[$pag_num]['ano_orden_compra']." and numero_nota_entrega='$num_notaentrega' and ano_nota_entrega='$ano'", $fields = null, $order = 'ano_orden_compra, numero_orden_compra, numero_nota_entrega ASC', $limit = null, $page = null, $recursive = null);
		$datos_orden_compra_pago_cuerpo = $this->cscd05_ordencompra_nota_entrega_cuerpo->findAll($conditions = $this->condicion().' and ano_nota_entrega='.$ano."  and  numero_nota_entrega= '$num_notaentrega' and upper(rif)=upper('$rif')", $fields = null, $order = 'numero_nota_entrega ASC', $limit = null, $page = null, $recursive = null);

//pr($datos_orden_compra_pago_encabezado);

			foreach($datos_orden_compra_pago_encabezado as $aux222){
				$observaciones = $numero2['observaciones'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['observaciones'];
				$entrega_completa = $numero2['entrega_completa'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['entrega_completa'];
				$numero2['rif'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['rif'];
				$fecha_nota_entrega = $numero2['fecha_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['fecha_nota_entrega'];
				$numero_nota_entrega = $numero2['numero_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['numero_nota_entrega'];
				$ano_nota_entrega = $numero2['ano_nota_entrega'] = $aux222['cscd05_ordencompra_nota_entrega_encabezado']['ano_nota_entrega'];
			}//fin

		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  numero_orden_compra='.$numero[$pag_num]['numero_orden_compra'].'  and  ano_orden_compra='.$numero[$pag_num]['ano_orden_compra'].' ';
		$numero_datos = $this->cscd04_ordencompra_encabezado->findAll($condicion);
		$numero_datos_partidas = $this->cscd04_ordencompra_partidas->findAll($condicion);
		$numero_datos_aux =  $numero_datos;

		foreach($numero_datos_aux as $aux){
			$rif = $aux['cscd04_ordencompra_encabezado']['rif'];
			$ano_orden_compra = $aux['cscd04_ordencompra_encabezado']['ano_orden_compra'];
			$numero_orden_compra = $aux['cscd04_ordencompra_encabezado']['numero_orden_compra'];
			$numero_cotizacion = $aux['cscd04_ordencompra_encabezado']['numero_cotizacion'];
			$ano_cotizacion = $aux['cscd04_ordencompra_encabezado']['ano_cotizacion'];
			$fecha_orden_compra = $aux['cscd04_ordencompra_encabezado']['fecha_orden_compra'];
			$tipo_orden = $aux['cscd04_ordencompra_encabezado']['tipo_orden'];
		}//fin foreach

		$opc = $this->cscd05_ordencompra_nota_entrega_encabezado->findCount($condicion);
		$opc++;
		$rif_datos = $this->cpcd02->findAll("upper(rif)=upper('$rif')");
		foreach($rif_datos as $aux_2){
			$denominacion_rif = $aux_2['cpcd02']['denominacion'];
			$direccion_comercial_rif = $aux_2['cpcd02']['direccion_comercial'];
		}//fin foreach

		$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_cotizacion='.$ano_cotizacion." and numero_cotizacion='$numero_cotizacion' and upper(rif)=upper('$rif')";
		$lista = $this->cscd03_cotizacion_cuerpo->findAll($condicion,null, 'numero_cotizacion ASC', null);
		$this->set('lista_cscd03_cotizacion_cuerpo', $lista);

		$this->set('rif_encabezado', $rif);
		$this->set('observaciones', $observaciones);
		$this->set('entrega_completa', $entrega_completa);

		$this->set('numero_nota_entrega', $numero_nota_entrega);
		$this->set('ano_nota_entrega', $ano_nota_entrega);
		$this->set('fecha_nota_entrega', $fecha_nota_entrega);
		$this->set('fecha_orden_compra', $fecha_orden_compra);
		$this->set('tipo_orden', $tipo_orden);

		$this->set('ano_orden_compra', $numero[$pag_num]['ano_orden_compra']);
		$this->set('numero_orden_compra', $numero[$pag_num]['numero_orden_compra']);

		$this->set('numero_orden_compra_pago', $opc);
		$this->set('datos_orden_compra', $numero_datos);
		$this->set('datos_orden_compra_partidas', $numero_datos_partidas);
		$this->set('denominacion_rif', $denominacion_rif);
		$this->set('direccion_comercial_rif', $direccion_comercial_rif);
		$this->set('lista_cscd03_cotizacion_cuerpo_entregado', $datos_orden_compra_pago_cuerpo);

		$this->set('unidades', $this->cscd01_unidad_medida->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null));

		$this->set('pag_num', $pag_num);
		$this->set('totalPages_Recordset1', $i);

		$this->set('existe', 'si');

	}else{

		$this->set('existe', 'no');
		$this->set('pag_num', 0);
		$this->set('totalPages_Recordset1', '');
		$this->set('errorMessage', 'No existen datos');

	}//fin else


}//fin function

function consulta1($numero_orden_compra=null,$pagina=null){
	$this->layout='ajax';
		if(isset($_SESSION['ano_compra'])){$ano = $_SESSION['ano_compra'];}else{$ano = $this->ano_ejecucion();}
		$this->set('ano_orden_compra',$ano);
		$this->set('ano_ejecucion',$this->ano_ejecucion());
		$cond = $this->SQLCA()." and ano_orden_compra=".$ano." and numero_orden_compra=".$numero_orden_compra;
        if($pagina!=null){
        	$pagina=$pagina;
          	$this->set('pagina',$pagina);
          	$Tfilas=$this->v_cscd05_ordencompra_nota_entrega->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          		$datos=$this->v_cscd05_ordencompra_nota_entrega->findAll($cond,null,'numero_nota_entrega ASC',1,$pagina,null);
          	 	$this->set('datos',$datos);
          	 	$num_notaentrega = $datos[0]['v_cscd05_ordencompra_nota_entrega']['numero_nota_entrega'];
          	 	$ano_cotizacion = $datos[0]['v_cscd05_ordencompra_nota_entrega']['ano_cotizacion'];
          	 	$numero_cotizacion = $datos[0]['v_cscd05_ordencompra_nota_entrega']['numero_cotizacion'];
          	 	$rif = $datos[0]['v_cscd05_ordencompra_nota_entrega']['rif'];
          	 	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_cotizacion='.$ano_cotizacion." and numero_cotizacion='$numero_cotizacion' and upper(rif)=upper('$rif')";
				$lista = $this->cscd03_cotizacion_cuerpo->findAll($condicion,null, 'numero_cotizacion ASC', null);
          	 	$this->set('lista_cscd03_cotizacion_cuerpo', $lista);
          	 	$datos_orden_compra_pago_cuerpo = $this->cscd05_ordencompra_nota_entrega_cuerpo->findAll($conditions = $this->condicion().' and ano_nota_entrega='.$ano."  and  numero_nota_entrega= '$num_notaentrega' and upper(rif)=upper('$rif')", $fields = null, $order = 'numero_nota_entrega ASC', $limit = null, $page = null, $recursive = null);
          	 	$this->set('lista_cscd03_cotizacion_cuerpo_entregado', $datos_orden_compra_pago_cuerpo);
          	 	$this->set('unidades', $this->cscd01_unidad_medida->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null));
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
           	}
 		}else{
 			$pagina=1;
 			$this->set('pagina',$pagina);
          	$Tfilas=$this->v_cscd05_ordencompra_nota_entrega->findCount($cond);
          	if($Tfilas==0){
          		$this->set('Message_existe', 'No se encontraron datos.');
				$this->index();
				$this->render("index");
          	}
          	if($Tfilas!=0){
          		$this->set('pag_cant',$pagina.'/'.$Tfilas);
          	 	$datos=$this->v_cscd05_ordencompra_nota_entrega->findAll($cond,null,'numero_nota_entrega ASC',1,$pagina,null);
          	 	$this->set('datos',$datos);
          	 	$num_notaentrega = $datos[0]['v_cscd05_ordencompra_nota_entrega']['numero_nota_entrega'];
          	 	$ano_cotizacion = $datos[0]['v_cscd05_ordencompra_nota_entrega']['ano_cotizacion'];
          	 	$numero_cotizacion = $datos[0]['v_cscd05_ordencompra_nota_entrega']['numero_cotizacion'];
          	 	$rif = $datos[0]['v_cscd05_ordencompra_nota_entrega']['rif'];
          	 	$condicion = 'cod_presi='.$this->Session->read('SScodpresi').'  and  cod_entidad='.$this->Session->read('SScodentidad').' and cod_tipo_inst='.$this->Session->read('SScodtipoinst').' and  cod_inst='.$this->Session->read('SScodinst').' and cod_dep='.$this->Session->read('SScoddep').' and  ano_cotizacion='.$ano_cotizacion." and numero_cotizacion='$numero_cotizacion' and upper(rif)=upper('$rif')";
				$lista = $this->cscd03_cotizacion_cuerpo->findAll($condicion,null, 'numero_cotizacion ASC', null);
          	 	$this->set('lista_cscd03_cotizacion_cuerpo', $lista);
          	 	$datos_orden_compra_pago_cuerpo = $this->cscd05_ordencompra_nota_entrega_cuerpo->findAll($conditions = $this->condicion().' and ano_nota_entrega='.$ano."  and  numero_nota_entrega= '$num_notaentrega' and upper(rif)=upper('$rif')", $fields = null, $order = 'numero_nota_entrega ASC', $limit = null, $page = null, $recursive = null);
          	 	$this->set('lista_cscd03_cotizacion_cuerpo_entregado', $datos_orden_compra_pago_cuerpo);
          	 	$this->set('unidades', $this->cscd01_unidad_medida->findAll($conditions = null, $fields = null, $order = null, $limit = null, $page = null, $recursive = null));
          	 	$this->set('siguiente',$pagina+1);
          	 	$this->set('anterior',$pagina-1);
             	$this->bt_nav($Tfilas,$pagina);
			}
        }
}//fin function consultar2

function eliminar($ano_orden_compra=null, $numero_orden_compra=null, $rif_encabezado=null, $ano_nota_entrega=null, $numero_nota_entrega=null){
	$this->layout="ajax";
	$entrega = 1;
	$numero_datos = 0;


	$lista = $this->cscd05_ordencompra_nota_entrega_cuerpo->findAll($this->condicion()." and rif='$rif_encabezado' and ano_nota_entrega='$ano_nota_entrega' and numero_nota_entrega='$numero_nota_entrega'", null, null, null);



	$sql_delete_cscd05 = "BEGIN; DELETE FROM cscd05_ordencompra_nota_entrega_encabezado WHERE ".$this->condicion()." and rif='$rif_encabezado' and ano_nota_entrega='$ano_nota_entrega' and numero_nota_entrega='$numero_nota_entrega' and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra';";
	$sql_delete_cscd05 .= "      DELETE FROM cscd05_ordencompra_nota_entrega_cuerpo     WHERE ".$this->condicion()." and rif='$rif_encabezado' and ano_nota_entrega='$ano_nota_entrega' and numero_nota_entrega='$numero_nota_entrega';";

       $numero_datos = $this->cscd04_ordencompra_encabezado->findCount($this->condicion()." and rif='$rif_encabezado' and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra'");
    if($numero_datos!=0){$entrega=1;}


	$sql_update_cscd04_orden_compra = "UPDATE cscd04_ordencompra_encabezado SET entrega_completa=".$entrega." WHERE ".$this->condicion()." and ano_orden_compra='$ano_orden_compra' and numero_orden_compra='$numero_orden_compra';";
	$numero_cotizacion = $this->cscd03_cotizacion_encabezado->field('numero_cotizacion', $this->condicion()." and rif='$rif_encabezado' and ano_ordencompra='$ano_orden_compra' and numero_ordencompra='$numero_orden_compra'", null);


  foreach($lista as $aux){
          $sql_update_cotizacion   = "UPDATE cscd03_cotizacion_cuerpo SET cantidad_entregada=cantidad_entregada-".$aux['cscd05_ordencompra_nota_entrega_cuerpo']['cantidad']." WHERE ".$this->condicion()." and rif='$rif_encabezado' and ano_cotizacion='$ano_orden_compra' and numero_cotizacion='$numero_cotizacion' and codigo_prod_serv='".$aux['cscd05_ordencompra_nota_entrega_cuerpo']['codigo_prod_serv']."';";
          $sw1                     = $this->cscd05_ordencompra_nota_entrega_encabezado->execute($sql_update_cotizacion);


   }//fin foreach


	$sw2 = $this->cscd05_ordencompra_nota_entrega_encabezado->execute($sql_delete_cscd05);
	$sw3 = $this->cscd05_ordencompra_nota_entrega_encabezado->execute($sql_update_cscd04_orden_compra);

	if($sw1 > 1){
		$this->cscd05_ordencompra_nota_entrega_encabezado->execute("COMMIT;");
		$this->set('Message_existe', "LA NOTA DE ENTREGA FUE ELIMINADA CON EXITO");
	}else{
		$this->cscd05_ordencompra_nota_entrega_encabezado->execute("ROLLBACK;");
		$this->set('errorMessage', 'NO SE LOGRO ELIMINAR LA NOTA DE ENTREGA');
	}

	$this->consulta_index();
	$this->render('consulta_index');

}





}//fin class

?>