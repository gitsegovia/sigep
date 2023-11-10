<?php
class Capp03EfectividadDepartamentoController extends AppController {
   var $name = 'capp03_efectividad_departamento';
   var $uses = array('capd01_tipo_documento','capd02_procesos','capd03_numero','ccfd04_cierre_mes','cugd02_direccionsuperior',
   					'cugd02_coordinacion','cugd02_secretaria','cugd02_direccion','cugd02_division','cugd02_departamento',
   					'cepd01_compromiso_beneficiario_cedula','cepd01_compromiso_beneficiario_rif','cpcd02','capd03_documentos',
   					'cepd01_compromiso_cuerpo','cscd04_ordencompra_encabezado','cepd02_contratoservicio_cuerpo','v_cobp01_cfpd07_cuerpo','capd04_flujo',
   					'Usuario');
   var $helpers = array('Html','Ajax','Javascript', 'Sisap');

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




 function beforeFilter(){
 	$this->checkSession();
 	 /*echo'<script>
							 document.getElementById("valida_codigo").innerHTML = "";
							 document.getElementById("valida_codigo").style.display = "none";
							 if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
                          </script>';*/
 }




function index($var=null){///////////////<<--INDEX
	 $this->layout = "ajax";

	 $this->set('year',$this->ano_ejecucion());
	 $this->Session->delete('ano_trabajar');
	 $this->Session->write('ano_trabajar',$this->ano_ejecucion());


	$this->data=null;
}//fin index


function ano($ano){
	$this->layout = "ajax";
	$this->Session->delete('ano_trabajar');
	$this->Session->write('ano_trabajar',$ano);
}


function busqueda($tipo=null,$pagina=null,$pista=null){
	$this->layout = "ajax";
	$ano=$this->Session->read('ano_trabajar');
	if($tipo==1){
		$cod_presi = $this->Session->read('SScodpresi');
	    $cod_entidad = $this->Session->read('SScodentidad');
	    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
	    $cod_inst = $this->Session->read('SScodinst');
	    $cod_dep = $this->Session->read('SScoddep');


		if(isset($pagina)){
			$Tfilas=$this->capd03_documentos->findCount($this->SQLCA()." and ano=".$ano);
	        if($Tfilas!=0){
	        	$x=$this->capd03_documentos->findAll($this->SQLCA()." and ano=".$ano,null,"numero_control ASC",1,$pagina,null);

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
		 	       $this->busqueda(3);
				   $this->render("busqueda");
				   return;
	        }

		}else{
			$pagina=1;
			$Tfilas=$this->capd03_documentos->findCount($this->SQLCA()." and ano=".$ano);

	        if($Tfilas!=0){
	        	$x=$this->capd03_documentos->findAll($this->SQLCA()." and ano=".$ano,null,"numero_control ASC",1,$pagina,null);
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
		 	       $this->busqueda(3);
				   $this->render("busqueda");
				   return;
	        }
		}

			//		$x[0]["capd03_documentos"]["cod_coordinacion"];
			$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"]);
			$this->set('pasos_cumplir',$deno1[0][0]['pasos_cumplir']);
			$this->set('denominacion',$deno1[0][0]['denominacion']);
//			$this->set('dias_pago',$deno1[0][0]['dias_probable_pago']);
			$this->set('x',$x);

		$sql="select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.numero_control,a.cod_tipo_documento,a.paso,a.username1,a.username2,a.fecha_entrada,a.fecha_salida,a.hora_entrada,a.hora_salida,
				(select l.estimacion_dias from capd02_procesos l where l.cod_presi=a.cod_presi and l.cod_entidad=a.cod_entidad and l.cod_tipo_inst=a.cod_tipo_inst and l.cod_inst=a.cod_inst and l.cod_dep=a.cod_dep and l.cod_tipo_documento=a.cod_tipo_documento and l.paso=a.paso) as estimacion_dias,
				(select ll.estimacion_horas from capd02_procesos ll where ll.cod_presi=a.cod_presi and ll.cod_entidad=a.cod_entidad and ll.cod_tipo_inst=a.cod_tipo_inst and ll.cod_inst=a.cod_inst and ll.cod_dep=a.cod_dep and ll.cod_tipo_documento=a.cod_tipo_documento and ll.paso=a.paso) as estimacion_horas,
				(select lll.estimacion_minutos from capd02_procesos lll where lll.cod_presi=a.cod_presi and lll.cod_entidad=a.cod_entidad and lll.cod_tipo_inst=a.cod_tipo_inst and lll.cod_inst=a.cod_inst and lll.cod_dep=a.cod_dep and lll.cod_tipo_documento=a.cod_tipo_documento and lll.paso=a.paso) as estimacion_minutos,
				 (select b.denominacion from cugd02_departamento b where b.cod_tipo_institucion=(select c.cod_tipo_inst from usuarios c where c.username=a.username1) and
				 b.cod_institucion=(select d.cod_inst from usuarios d where d.username=a.username1) and b.cod_dependencia=(select e.cod_dep from usuarios e where e.username=a.username1) and
				 b.cod_dir_superior=(select f.cod_dir_superior from  usuarios f where f.username=a.username1) and b.cod_coordinacion=(select g.cod_coordinacion from usuarios g where g.username=a.username1) and
				 b.cod_secretaria=(select h.cod_secretaria from usuarios h where h.username=a.username1) and  b.cod_direccion=(select i.cod_direccion from usuarios i where i.username=a.username1) and
				 b.cod_division=(select j.cod_division from usuarios j where j.username=a.username1) and b.cod_departamento=(select k.cod_departamento from usuarios k where k.username=a.username1)) as departamento1,
				 (select bb.denominacion from cugd02_departamento bb where bb.cod_tipo_institucion=(select cc.cod_tipo_inst from usuarios cc where cc.username=a.username2) and
				 bb.cod_institucion=(select dd.cod_inst from usuarios dd where dd.username=a.username2) and bb.cod_dependencia=(select ee.cod_dep from usuarios ee where ee.username=a.username2) and
				 bb.cod_dir_superior=(select ff.cod_dir_superior from  usuarios ff where ff.username=a.username2) and bb.cod_coordinacion=(select gg.cod_coordinacion from usuarios gg where gg.username=a.username2) and
				 bb.cod_secretaria=(select hh.cod_secretaria from usuarios hh where hh.username=a.username2) and  bb.cod_direccion=(select ii.cod_direccion from usuarios ii where ii.username=a.username2) and
				 bb.cod_division=(select jj.cod_division from usuarios jj where jj.username=a.username2) and bb.cod_departamento=(select kk.cod_departamento from usuarios kk where kk.username=a.username2)) as departamento2 from v_capp03_efectividad_departamento a where a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano." and a.numero_control=".$x[0]["capd03_documentos"]["numero_control"]." order by a.paso asc";




			$datos1=$this->capd04_flujo->execute($sql);

//			pr($datos1);
			$this->set('pasos',$datos1);
			if($x[0]["capd03_documentos"]["procesos_realizados"]==1){
				if($x[0]["capd03_documentos"]["pasos_cumplidos"]==1){
					$paso=0;
				}else{
					$paso=$x[0]["capd03_documentos"]["pasos_cumplidos"]-1;
				}

				$paso2=" and paso > ".$x[0]["capd03_documentos"]["pasos_cumplidos"];

			}else if($x[0]["capd03_documentos"]["procesos_realizados"]==2){
				$paso=$x[0]["capd03_documentos"]["pasos_cumplidos"];
				$paso2=" and paso > ".$x[0]["capd03_documentos"]["pasos_cumplidos"];
			}else{
				$paso=0;
				$paso2="";
			}
			$this->set('paso',$paso);

			$datos1=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"].$paso2."  order by paso asc");
			if($datos1!=null){
				$this->set('datos',$datos1);
			}else{
				$this->set('datos',null);
			}
	}else{
		if($tipo!=3){
			$url                  =  "/capp03_efectividad_departamento/buscar_datos";
		$width_aux            =  "750px";
		$height_aux           =  "400px";
		$title_aux            =  "Buscar";
		$resizable_aux        =  false;
		$maximizable_aux      =  false;
		$minimizable_aux      =  false;
		$closable_aux         =  false;

		 echo"<script>";
		 	echo "document.getElementById('peticion_1').checked=false;";
		 	echo "document.getElementById('peticion_2').checked=false;";
           echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."')";
         echo"</script>";
		}

	}

$this->set('tipo',$tipo);
}// fin busqueda


function seleccion_busqueda($var=null){
	$this->layout="ajax";
	$ano=$this->Session->read('ano_trabajar');
			$x=$this->capd03_documentos->findAll($this->SQLCA($ano)." and numero_control=".$var,null,"numero_control ASC",null,null,null);

			$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"]);
			$this->set('pasos_cumplir',$deno1[0][0]['pasos_cumplir']);
			$this->set('denominacion',$deno1[0][0]['denominacion']);
			$this->set('x',$x);


			$sql="select a.cod_presi,a.cod_entidad,a.cod_tipo_inst,a.cod_inst,a.cod_dep,a.ano,a.numero_control,a.cod_tipo_documento,a.paso,a.username1,a.username2,a.fecha_entrada,a.fecha_salida,a.hora_entrada,a.hora_salida,
				(select l.estimacion_dias from capd02_procesos l where l.cod_presi=a.cod_presi and l.cod_entidad=a.cod_entidad and l.cod_tipo_inst=a.cod_tipo_inst and l.cod_inst=a.cod_inst and l.cod_dep=a.cod_dep and l.cod_tipo_documento=a.cod_tipo_documento and l.paso=a.paso) as estimacion_dias,
				(select ll.estimacion_horas from capd02_procesos ll where ll.cod_presi=a.cod_presi and ll.cod_entidad=a.cod_entidad and ll.cod_tipo_inst=a.cod_tipo_inst and ll.cod_inst=a.cod_inst and ll.cod_dep=a.cod_dep and ll.cod_tipo_documento=a.cod_tipo_documento and ll.paso=a.paso) as estimacion_horas,
				(select lll.estimacion_minutos from capd02_procesos lll where lll.cod_presi=a.cod_presi and lll.cod_entidad=a.cod_entidad and lll.cod_tipo_inst=a.cod_tipo_inst and lll.cod_inst=a.cod_inst and lll.cod_dep=a.cod_dep and lll.cod_tipo_documento=a.cod_tipo_documento and lll.paso=a.paso) as estimacion_minutos,
				 (select b.denominacion from cugd02_departamento b where b.cod_tipo_institucion=(select c.cod_tipo_inst from usuarios c where c.username=a.username1) and
				 b.cod_institucion=(select d.cod_inst from usuarios d where d.username=a.username1) and b.cod_dependencia=(select e.cod_dep from usuarios e where e.username=a.username1) and
				 b.cod_dir_superior=(select f.cod_dir_superior from  usuarios f where f.username=a.username1) and b.cod_coordinacion=(select g.cod_coordinacion from usuarios g where g.username=a.username1) and
				 b.cod_secretaria=(select h.cod_secretaria from usuarios h where h.username=a.username1) and  b.cod_direccion=(select i.cod_direccion from usuarios i where i.username=a.username1) and
				 b.cod_division=(select j.cod_division from usuarios j where j.username=a.username1) and b.cod_departamento=(select k.cod_departamento from usuarios k where k.username=a.username1)) as departamento1,
				 (select bb.denominacion from cugd02_departamento bb where bb.cod_tipo_institucion=(select cc.cod_tipo_inst from usuarios cc where cc.username=a.username2) and
				 bb.cod_institucion=(select dd.cod_inst from usuarios dd where dd.username=a.username2) and bb.cod_dependencia=(select ee.cod_dep from usuarios ee where ee.username=a.username2) and
				 bb.cod_dir_superior=(select ff.cod_dir_superior from  usuarios ff where ff.username=a.username2) and bb.cod_coordinacion=(select gg.cod_coordinacion from usuarios gg where gg.username=a.username2) and
				 bb.cod_secretaria=(select hh.cod_secretaria from usuarios hh where hh.username=a.username2) and  bb.cod_direccion=(select ii.cod_direccion from usuarios ii where ii.username=a.username2) and
				 bb.cod_division=(select jj.cod_division from usuarios jj where jj.username=a.username2) and bb.cod_departamento=(select kk.cod_departamento from usuarios kk where kk.username=a.username2)) as departamento2 from v_capp03_efectividad_departamento a where a.cod_presi=".$this->verifica_SS(1)." and a.cod_entidad=".$this->verifica_SS(2)." and a.cod_tipo_inst=".$this->verifica_SS(3)." and a.cod_inst=".$this->verifica_SS(4)." and a.cod_dep=".$this->verifica_SS(5)." and a.ano=".$ano." and a.numero_control=".$x[0]["capd03_documentos"]["numero_control"]." order by a.paso asc";


			$datos1=$this->capd04_flujo->execute($sql);
			$this->set('pasos',$datos1);
			if($x[0]["capd03_documentos"]["procesos_realizados"]==1){
				if($x[0]["capd03_documentos"]["pasos_cumplidos"]==1){
					$paso=0;
				}else{
					$paso=$x[0]["capd03_documentos"]["pasos_cumplidos"]-1;
				}

				$paso2=" and paso > ".$x[0]["capd03_documentos"]["pasos_cumplidos"];

			}else if($x[0]["capd03_documentos"]["procesos_realizados"]==2){
				$paso=$x[0]["capd03_documentos"]["pasos_cumplidos"];
				$paso2=" and paso > ".$x[0]["capd03_documentos"]["pasos_cumplidos"];
			}else{
				$paso=0;
				$paso2="";
			}
			$this->set('paso',$paso);

			$datos1=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"].$paso2."  order by paso asc");
			if($datos1!=null){
				$this->set('datos',$datos1);
			}else{
				$this->set('datos',null);
			}

}



function busqueda_ventana($tipo=null,$var=null){
	$this->layout="ajax";
	$ano=$this->Session->read('ano_trabajar');
	if($tipo==3){
		$campo='numero_control';
	}else if($tipo==2){
		$campo='beneficiario';
	}else{
		$campo='rif_cedula';
	}


    	$var2 = strtoupper($var);
    	$this->set('pista',$var2);
//					if(is_int($var2)){$sql   = " (cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%')  or   ";}else{ $sql = "";}
					$Tfilas=$this->capd03_documentos->findCount($this->SQLCA($ano)." and upper(".$campo."::text) LIKE upper('%".$var2."%') ");
//					        echo "cod_tipo_nomina='$nomina' and denominacion_busqueda LIKE '%$var2%'";
					        if($Tfilas!=0){
					        	$pagina=1;
					        	$Tfilas=(int)ceil($Tfilas/100);
					        	$this->set('pag_cant',$pagina.'/'.$Tfilas);
								$this->set('total_paginas',$Tfilas);
								$this->set('pagina_actual',$pagina);
								$this->set('ultimo',$Tfilas);
					     	    $datos_filas=$this->capd03_documentos->findAll($this->SQLCA($ano)."and upper(".$campo."::text) LIKE upper('%".$var2."%')",null,"numero_control ASC",100,1,null);
						        $this->set("datosFILAS",$datos_filas);
						        $this->set('siguiente',$pagina+1);
								$this->set('anterior',$pagina-1);
								$this->bt_nav($Tfilas,$pagina);
					          }else{
					        	$this->set("datosFILAS",'');
					          }



//$this->set("cscd01_unidad_medida",$this->cscd01_unidad_medida->findAll());
//$this->set("opcion",$var1);

	$this->set('tipo',$tipo);
}

function buscar_datos(){
	$this->layout="ajax";
	$this->set("opcion",'');

	$this->Session->delete('pista');
}//fin buscar_ficha



function buscar($var=null){
	$this->layout="ajax";
	$ano=$this->Session->read('ano_trabajar');
	/*if($var==3){
		$lista=$this->capd03_documentos->generateList($this->SQLCA($ano),'numero_control ASC', null, '{n}.capd03_documentos.numero_control', '{n}.capd03_documentos.numero_control');
		$this->set('numero_documento',$lista);
		$opcion="select";
	}else{
		$opcion="input";
	}*/

//	$this->set($opcion,$opcion);
	$this->set('tipo',$var);

}//fin buscar_ficha



function bt_nav($Tfilas,$pagina){
    if($Tfilas==1){
                $this->set('mostrarS',false);
                $this->set('mostrarA',false);
          	}else if($Tfilas==2){
          		if($pagina==2){
                   $this->set('mostrarS',false);
                   $this->set('mostrarA',true);
          		}else{
          		   $this->set('mostrarS',true);
                   $this->set('mostrarA',false);
          		}
          	}else if($Tfilas>=3){
          		if($pagina==$Tfilas){
                     $this->set('mostrarS',false);
                     $this->set('mostrarA',true);
          		}else if($pagina==1){
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',false);
          		}else{
          			 $this->set('mostrarS',true);
                     $this->set('mostrarA',true);
          		}
          	}
 }//fin navegacion


 function documento($numero_control){
 	$this->layout ="ajax";
 	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');

	$ano=$this->ano_ejecucion();
 	$this->set('year',$ano);

	$lista1=  $this->capd03_documentos->generateList($this->SQLCA()." and ano=".$ano, 'numero_control ASC', null, '{n}.capd03_documentos.numero_control', '{n}.capd03_documentos.numero_control');
	$this->set('numeros',$lista1);
	$this->set('numero_control',$numero_control);

	$x=$this->capd03_documentos->FindAll($this->SQLCA()." and ano='$ano' and numero_control='$numero_control'");
	$this->set('datos',$x);

	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep." and cod_dir_superior=".$x[0]["capd03_documentos"]["cod_dir_superior"];
	$this->set('dir_sup',$this->cugd02_direccionsuperior->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_coordinacion=".$x[0]["capd03_documentos"]["cod_coordinacion"];
	$this->set('coordinacion',$this->cugd02_coordinacion->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_secretaria=".$x[0]["capd03_documentos"]["cod_secretaria"];
	$this->set('secretaria',$this->cugd02_secretaria->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_direccion=".$x[0]["capd03_documentos"]["cod_direccion"];
	$this->set('direccion',$this->cugd02_direccion->field('denominacion', $conditions =$cond, $order =null));

	if($x[0]["capd03_documentos"]["cod_division"]!=''){
		$cond.=" and cod_division=".$x[0]["capd03_documentos"]["cod_division"];
		$this->set('division',$this->cugd02_division->field('denominacion', $conditions =$cond, $order =null));

	}else{
		$this->set('division','');
	}

	if($x[0]["capd03_documentos"]["cod_departamento"]!=''){
		$cond.=" and cod_departamento=".$x[0]["capd03_documentos"]["cod_departamento"];
		$this->set('departamento',$this->cugd02_departamento->field('denominacion', $conditions =$cond, $order =null));

	}else{
		$this->set('departamento','');
	}

	$tipo_documento=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"]);
	$this->set('deno_documento',$tipo_documento[0][0]['denominacion']);

	/////////////////////////////////para los pasos cumplidos////////////////////////////////////////////////////////

	if($x[0]["capd03_documentos"]["pasos_cumplidos"]==null){
		$this->set('pasos_cumplidos',0);
	}else{
		$this->set('pasos_cumplidos',$x[0]["capd03_documentos"]["pasos_cumplidos"]);
	}

	$this->set('pasos_cumplir',$tipo_documento[0][0]['pasos_cumplir']);
	$pasos_cumplir=$tipo_documento[0][0]['pasos_cumplir'];////////////////////////////////////////////////////////////////////////





	if($x[0]["capd03_documentos"]["pasos_cumplidos"]!=null){
		if($x[0]["capd03_documentos"]["procesos_realizados"]==1){
			$operacion=2;
			$paso=$x[0]["capd03_documentos"]["pasos_cumplidos"];
			$paso1=$paso;
		}else if($x[0]["capd03_documentos"]["procesos_realizados"]==2){
			$operacion=1;
			$paso=$x[0]["capd03_documentos"]["pasos_cumplidos"]+1;
			$paso1=$paso;
			if($paso>$pasos_cumplir){
				$paso1=$x[0]["capd03_documentos"]["pasos_cumplidos"];
				$operacion1=2;
			}
		}
	}else{
		$paso=1;
		$paso1=1;
		$operacion=1;
	}
	$en_sa=$this->capd02_procesos->execute("select * from capd02_procesos where ".$this->SQLCA()." and paso=".$paso1);

/*
//////////////////////////////para proceso y operacion/////////////////////////////////////////////////////////
$ver=$this->capd04_flujo->execute("select * from capd04_flujo where ".$this->SQLCA()." and ano=".$ano." and numero_control=".$numero_control." order by paso desc limit 1");
//	pr($ver);
	if($ver!=null){
		if($ver[0][0]['operacion']==1){
			$operacion=2;
			$paso=$ver[0][0]['paso'];
		}else if($ver[0][0]['operacion']==2){
			$operacion=1;
			$paso=$ver[0][0]['paso']+1;
		}
	}else{
		$paso=1;
		$operacion=1;

	}
	*/

	if($paso > $pasos_cumplir){
		$paso=$tipo_documento[0][0]['pasos_cumplir'];
		$operacion=2;
		$this->set('disabled','disabled');
		$this->set('readonly','readonly');
	}else{
		$this->set('disabled','');
		$this->set('readonly','');
	}
	$this->set('paso',$paso);

	if($operacion==1){
		$operacion='entrada';
		$this->set('concepto',$en_sa[0][0]['proceso_realizar_entrada']);
	}else{
		$operacion='salida';
		$this->set('concepto',$en_sa[0][0]['proceso_realizar_salida']);
	}
	$this->set('operacion',$operacion);
	$this->data=null;

	///////////////////////////////////////////////////////////////////////////////////////////////////////////////
 }// fin index


function guardar(){
	$this->layout = "ajax";

//	pr($this->data);
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $ano=$this->ano_ejecucion();
	$numero_control=$this->data['capp01']['numero_control'];
	if( empty($this->data['capp01']['observacion'])){
		$this->set('errorMessage', 'Debe ingresar la observación');
	}else{

		$paso=$this->data['capp01']['procesos'];

		$operacion=$this->data['capp01']['operacion'];
		if($operacion=='ENTRADA'){
			$operacion=1;
		}else{
			$operacion=2;
		}

		/*if($operacion==1){
			$update="set procesos_realizados='$operacion'";
		}else{
			$update="set pasos_cumplidos='$paso',procesos_realizados='$operacion'";
		}*/

		$update="set pasos_cumplidos='$paso',procesos_realizados='$operacion'";

		$observacion=$this->data['capp01']['observacion'];
		$fecha=$this->data['capp01']['fecha'];
//		$hora=$this->data['capp01']['hora'];
		$hora=date("h:ia");
		$usuario=$this->Session->read('nom_usuario');

			$sql_insert = "BEGIN;INSERT INTO capd04_flujo VALUES ('$cod_presi','$cod_entidad','$cod_tipo_inst','$cod_inst','$cod_dep','$ano','$numero_control','$paso','$usuario','$operacion','$observacion','$fecha','$hora')";
			$sw2 = $this->capd04_flujo->execute($sql_insert);
			if($sw2>1){
				 $this->capd04_flujo->execute("UPDATE  capd03_documentos ".$update." WHERE ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano);
				 $this->capd04_flujo->execute("COMMIT");
			 	 $this->set('Message_existe', 'Proceso realizado con exito');
			 }else{
			 	 $this->capd04_flujo->execute("ROOLBACK");
				 $this->set('errorMessage', 'INSERCI&Oacute;N FALLIDA');
			 }





}

	$x=$this->capd03_documentos->FindAll($this->SQLCA()." and ano='$ano' and numero_control='$numero_control'");

	$tipo_documento=$this->capd03_documentos->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"]);
	$pasos_cumplir=$tipo_documento[0][0]['pasos_cumplir'];
	$avance=$this->data['capp01']['avance'];
	if($avance==1){

			$this->documento($numero_control);
			$this->render('documento');


	}else{
		$this->index();
		$this->render('index');
	}


$this->data=null;

}// fin guardar



function consulta($pagina=null) {
	$this->layout="ajax";
	$cod_presi = $this->Session->read('SScodpresi');
    $cod_entidad = $this->Session->read('SScodentidad');
    $cod_tipo_inst = $this->Session->read('SScodtipoinst');
    $cod_inst = $this->Session->read('SScodinst');
    $cod_dep = $this->Session->read('SScoddep');
    $ano=$this->ano_ejecucion();

	if(isset($pagina)){
		$Tfilas=$this->capd03_documentos->findCount($this->SQLCA()." and ano=".$ano);
        if($Tfilas!=0){
        	$x=$this->capd03_documentos->findAll($this->SQLCA()." and ano=".$ano,null,"numero_control ASC",1,$pagina,null);

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
        }

	}else{
		$pagina=1;
		$Tfilas=$this->capd03_documentos->findCount($this->SQLCA()." and ano=".$ano);

        if($Tfilas!=0){
        	$x=$this->capd03_documentos->findAll($this->SQLCA()." and ano=".$ano,null,"numero_control ASC",1,$pagina,null);
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
        }
	}
	///////esto para el select del tipo de documento
	$lista=$this->capd01_tipo_documento->execute("select distinct b.cod_tipo_documento,
												(select a.denominacion from capd01_tipo_documento a where a.cod_tipo_documento=b.cod_tipo_documento and a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep=".$cod_dep.") as denominacion from capd02_procesos b
												where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and
												(select c.paso from capd02_procesos c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=".$cod_dep." and c.cod_tipo_documento=b.cod_tipo_documento order by paso desc limit 1)=(select aa.pasos_cumplir from capd01_tipo_documento aa where aa.cod_tipo_documento=b.cod_tipo_documento and aa.cod_presi=".$cod_presi." and aa.cod_entidad=".$cod_entidad." and aa.cod_tipo_inst=".$cod_tipo_inst." and aa.cod_inst=".$cod_inst." and aa.cod_dep=".$cod_dep.")");
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_tipo_documento"];
		$v[]=$l[0]["denominacion"];
//		$v[1]=$l[0]["denominacion"];
$i++;
	}
	$lista = array_combine($r, $v);
 	$this->set('documentos',$lista);

//pr($x);
//////////////////////////////////////////////////

	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep." and cod_dir_superior=".$x[0]["capd03_documentos"]["cod_dir_superior"];
	$this->set('dir_sup',$this->cugd02_direccionsuperior->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_coordinacion=".$x[0]["capd03_documentos"]["cod_coordinacion"];
	$this->set('coordinacion',$this->cugd02_coordinacion->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_secretaria=".$x[0]["capd03_documentos"]["cod_secretaria"];
	$this->set('secretaria',$this->cugd02_secretaria->field('denominacion', $conditions =$cond, $order =null));

	$cond.=" and cod_direccion=".$x[0]["capd03_documentos"]["cod_direccion"];
	$this->set('direccion',$this->cugd02_direccion->field('denominacion', $conditions =$cond, $order =null));

if($x[0]["capd03_documentos"]["cod_division"]!=''){
	$cond.=" and cod_division=".$x[0]["capd03_documentos"]["cod_division"];
	$this->set('division',$this->cugd02_division->field('denominacion', $conditions =$cond, $order =null));

}else{
	$this->set('division','');
}

if($x[0]["capd03_documentos"]["cod_departamento"]!=''){
	$cond.=" and cod_departamento=".$x[0]["capd03_documentos"]["cod_departamento"];
	$this->set('departamento',$this->cugd02_departamento->field('denominacion', $conditions =$cond, $order =null));

}else{
	$this->set('departamento','');
}

	$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$x[0]["capd03_documentos"]["cod_tipo_documento"]);
	$this->set('pasos_cumplir',$deno1[0][0]['pasos_cumplir']);

	$this->set('x',$x);
 }//consultar



function eliminar($numero_control=null,$pagina=null){
	  $this->layout = "ajax";
//echo "elim: ".$pagina;
			$ano=$this->ano_ejecucion();


		  $x = $this->capd03_documentos->execute("BEGIN;DELETE FROM capd03_documentos  WHERE ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano);
		  if($x>1){
		  	$this->capd03_documentos->execute("COMMIT");
		  	$this->capd03_numero->execute("UPDATE  capd03_numero SET situacion=4 WHERE ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano." and situacion=3");//pendiente en la condicion el año "mosca"
		  	$this->set('Message_existe','registro eliminado con exito');
		  }else{
		  	$this->capd03_documentos->execute("ROLLBACK");
		  	$this->set('errorMessage', 'EL DATO NO PUDO SER ELIMINADO');
		  }
	if($this->capd03_documentos->findCount($this->SQLCA())==0){
		$this->index();
		$this->render('index');
	}else{
		$this->consulta($pagina);
		$this->render('consulta');
	}


}//fin function





 function modificar($numero_control=null,$pagina=null){
 	$this->layout = "ajax";
 	$cod_presi                =       $this->Session->read('SScodpresi');
	$cod_entidad              =       $this->Session->read('SScodentidad');
	$cod_tipo_inst            =       $this->Session->read('SScodtipoinst');
	$cod_inst                 =       $this->Session->read('SScodinst');
	$cod_dep                  =       $this->Session->read('SScoddep');
	$ano=$this->ano_ejecucion();


 	$sql2="select * from capd03_documentos where ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano;
	$dato1=$this->capd03_documentos->execute($sql2);

	$lista=$this->capd01_tipo_documento->execute("select distinct b.cod_tipo_documento,
												(select a.denominacion from capd01_tipo_documento a where a.cod_tipo_documento=b.cod_tipo_documento and a.cod_presi=".$cod_presi." and a.cod_entidad=".$cod_entidad." and a.cod_tipo_inst=".$cod_tipo_inst." and a.cod_inst=".$cod_inst." and a.cod_dep=".$cod_dep.") as denominacion from capd02_procesos b
												where cod_presi=".$cod_presi." and cod_entidad=".$cod_entidad." and cod_tipo_inst=".$cod_tipo_inst." and cod_inst=".$cod_inst." and cod_dep=".$cod_dep." and
												(select c.paso from capd02_procesos c where c.cod_presi=".$cod_presi." and c.cod_entidad=".$cod_entidad." and c.cod_tipo_inst=".$cod_tipo_inst." and c.cod_inst=".$cod_inst." and c.cod_dep=".$cod_dep." and c.cod_tipo_documento=b.cod_tipo_documento order by paso desc limit 1)=(select aa.pasos_cumplir from capd01_tipo_documento aa where aa.cod_tipo_documento=b.cod_tipo_documento and aa.cod_presi=".$cod_presi." and aa.cod_entidad=".$cod_entidad." and aa.cod_tipo_inst=".$cod_tipo_inst." and aa.cod_inst=".$cod_inst." and aa.cod_dep=".$cod_dep.")");
	$i=1;
	foreach($lista as $l){
		$r[]=$l[0]["cod_tipo_documento"];
		$v[]=$l[0]["denominacion"];
	$i++;
	}
	$lista = array_combine($r, $v);
 	$this->set('documentos',$lista);


/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_dir_sup',$dato1[0][0]['cod_dir_superior']);
	$this->Session->write('dir_sup',$dato1[0][0]['cod_dir_superior']);

	$cond="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep;
	$lista1=  $this->cugd02_direccionsuperior->generateList($cond, 'cod_dir_superior ASC', null, '{n}.cugd02_direccionsuperior.cod_dir_superior', '{n}.cugd02_direccionsuperior.denominacion');
	$this->concatena($lista1, 'dir_sup');

	$cond1="cod_tipo_institucion=".$cod_tipo_inst." and cod_institucion=".$cod_inst." and cod_dependencia=".$cod_dep." and cod_dir_superior=".$dato1[0][0]['cod_dir_superior'];
	$deno_dir_sup = $this->cugd02_direccionsuperior->field('denominacion', $conditions = $cond1, $order ="cod_dir_superior ASC");
	$this->set('deno_dir_sup', $deno_dir_sup);

///////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_coordinacion',$dato1[0][0]['cod_coordinacion']);
	$this->Session->write('coor',$dato1[0][0]['cod_coordinacion']);

	$cond.=" and cod_dir_superior=".$dato1[0][0]['cod_dir_superior'];
	$lista2=  $this->cugd02_coordinacion->generateList($cond, 'cod_coordinacion ASC', null, '{n}.cugd02_coordinacion.cod_coordinacion', '{n}.cugd02_coordinacion.denominacion');
	$this->concatena($lista2, 'coor');

	$cond1.=" and cod_coordinacion=".$dato1[0][0]['cod_coordinacion'];
	$deno_coor = $this->cugd02_coordinacion->field('denominacion', $cond1, $order ="cod_coordinacion ASC");
	$this->set('deno_coor', $deno_coor);

///////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_secretaria',$dato1[0][0]['cod_secretaria']);
	$this->Session->write('secre',$dato1[0][0]['cod_secretaria']);

	$cond.=" and cod_coordinacion=".$dato1[0][0]['cod_coordinacion'];
	$lista3=  $this->cugd02_secretaria->generateList($cond, 'cod_secretaria ASC', null, '{n}.cugd02_secretaria.cod_secretaria', '{n}.cugd02_secretaria.denominacion');
	$this->concatena($lista3, 'secre');

	$cond1.=" and cod_secretaria=".$dato1[0][0]['cod_secretaria'];
	$deno_secre = $this->cugd02_secretaria->field('denominacion', $cond1, $order ="cod_secretaria ASC");
	$this->set('deno_secre', $deno_secre);

///////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_direccion',$dato1[0][0]['cod_direccion']);
	$this->Session->write('direc',$dato1[0][0]['cod_direccion']);

	$cond.=" and cod_secretaria=".$dato1[0][0]['cod_secretaria'];
	$lista4=  $this->cugd02_direccion->generateList($cond, 'cod_direccion ASC', null, '{n}.cugd02_direccion.cod_direccion', '{n}.cugd02_direccion.denominacion');
	$this->concatena($lista4, 'direc');

	$cond1.=" and cod_direccion=".$dato1[0][0]['cod_direccion'];
	$deno_direc = $this->cugd02_direccion->field('denominacion', $cond1, $order ="cod_direccion ASC");
	$this->set('deno_direc', $deno_direc);

///////////////////////////////////////////////////////////////////////////////////////////
$cond.=" and cod_direccion=".$dato1[0][0]['cod_direccion'];
$lista5=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
//pr($lista5);
if($lista5!=null){
	$this->concatena($lista5, 'div');
}else{
	$this->set('div',array());
}

if($dato1[0][0]['cod_division']!=''){

/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_division',$dato1[0][0]['cod_division']);
	$this->Session->write('div',$dato1[0][0]['cod_division']);

//	$cond.=" and cod_direccion=".$dato1[0][0]['cod_direccion'];
//	$lista5=  $this->cugd02_division->generateList($cond, 'cod_division ASC', null, '{n}.cugd02_division.cod_division', '{n}.cugd02_division.denominacion');
//	$this->concatena($lista5, 'div');

	$cond1.=" and cod_division=".$dato1[0][0]['cod_division'];
	$deno_div = $this->cugd02_division->field('denominacion', $cond1, $order ="cod_division ASC");
	$this->set('deno_div', $deno_div);

///////////////////////////////////////////////////////////////////////////////////////////

$cond.=" and cod_division=".$dato1[0][0]['cod_division'];
$lista6=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
if($lista6!=null){
	$this->concatena($lista6, 'depar');
}else{
	$this->set('depar',array());
}


if($dato1[0][0]['cod_departamento']!=''){
	/////////////////////////////////////////////////////////////////////////////////////////////
	$this->set('cod_departamento',$dato1[0][0]['cod_departamento']);
	$this->Session->write('depar',$dato1[0][0]['cod_departamento']);

//	$cond.=" and cod_division=".$dato1[0][0]['cod_division'];
//	$lista6=  $this->cugd02_departamento->generateList($cond, 'cod_departamento ASC', null, '{n}.cugd02_departamento.cod_departamento', '{n}.cugd02_departamento.denominacion');
//	$this->concatena($lista6, 'depar');

	$cond1.=" and cod_departamento=".$dato1[0][0]['cod_departamento'];
	$deno_depar = $this->cugd02_departamento->field('denominacion', $cond, $order ="cod_departamento ASC");
	$this->set('deno_depar', $deno_depar);

	///////////////////////////////////////////////////////////////////////////////////////////
	}else{
//		$this->set('depar',array());
		$this->set('cod_departamento','');
		$this->set('deno_depar','');
	}
}else{
//	$this->set('div',array());
	$this->set('cod_division','');
	$this->set('deno_div','');

	$this->set('depar',array());
	$this->set('cod_departamento','');
	$this->set('deno_depar','');
}

	$this->set('x',$dato1);
	$this->set('pagina',$pagina);

	$deno1=$this->capd01_tipo_documento->execute("select * from capd01_tipo_documento where ".$this->SQLCA()." and cod_tipo_documento=".$dato1[0][0]["cod_tipo_documento"]);
	$this->set('pasos_cumplir',$deno1[0][0]['pasos_cumplir']);

	$this->set('Message_existe', 'PROCEDA A MODIFICAR LOS DATOS');

 }// fin modificar_items




function guardar_modificar($numero_control=null,$pagina=null){
	$this->layout = "ajax";
	$ano=$this->ano_ejecucion();
//pr($this->data);
if(empty($this->data['capp01']['cod_dir_sup']) || empty($this->data['capp01']['cod_coordinacion']) || empty($this->data['capp01']['cod_secretaria']) || empty($this->data['capp01']['cod_direccion'])){
		$this->set('errorMessage', 'Debe ingresar los datos requeridos');
	}else{
		$cod_dir_sup=$this->data['capp01']['cod_dir_sup'];
		$cod_coordinacion=$this->data['capp01']['cod_coordinacion'];
		$cod_secretaria=$this->data['capp01']['cod_secretaria'];
		$cod_direccion=$this->data['capp01']['cod_direccion'];
		$observacion=$this->data['capp01']['observacion'];

		$update="set cod_dir_superior='$cod_dir_sup',cod_coordinacion='$cod_coordinacion',cod_secretaria='$cod_secretaria',cod_direccion='$cod_direccion',observaciones='$observacion'";
		$colar=',';

			$meter=',';
			if(!empty($this->data['capp01']['cod_division'])){
				$cod_division=$this->data['capp01']['cod_division'];
				$update.=",cod_division='$cod_division'";
				$colar.='cod_division,';
				$meter.="'$cod_division',";
			}else{
				$update.=",cod_division=null";
			}

			if(!empty($this->data['capp01']['cod_departamento'])){
				$cod_departamento=$this->data['capp01']['cod_departamento'];
				$update.=",cod_departamento='$cod_departamento'";
				$colar.='cod_departamento,';
				$meter.="'$cod_departamento',";
			}else{
				$update.=",cod_departamento=null";
			}

			$sql = "BEGIN;UPDATE capd03_documentos ".$update." where ".$this->SQLCA()." and numero_control=".$numero_control." and ano=".$ano;
			$sw2=$this->capd03_documentos->execute($sql);

			if($sw2>1){
					$this->capd03_documentos->execute("COMMIT");
			 		$this->set('Message_existe', 'los datos fueron modificados con exito');
			 }else{
			 		$this->capd03_documentos->execute("ROOLBACK");
				    $this->set('errorMessage', 'los datos no pudieron ser modificados');
			 }


}

$this->consulta($pagina);
$this->render('consulta');

}//fin guardar_items_modificar



function salir_documento($num_rc=null){
	$this->layout="ajax";
	$ano=$this->ano_ejecucion();
	$resultado=$this->capd03_numero->execute("UPDATE  capd03_numero SET situacion=1 WHERE ".$this->SQLCA()." and numero_control=".$num_rc." and ano=".$ano." and situacion=2");

	//$this->('index');
}


 }//Fin de la clase controller
 ?>