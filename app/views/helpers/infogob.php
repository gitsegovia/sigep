<?php

class InfogobHelper extends Helper {
/**
 * Included helpers.
 *
 * @var array
 */
	var $helpers = array('Html', 'Javascript', 'Form','Ajax','Sisap');
/**
 * HtmlHelper instance
 *
 * @var object
 * @access public
 */
	var $Html = null;
/**
 * JavaScriptHelper instance
 *
 * @var object
 * @access public
 */
	var $Javascript = null;
	var $Ajax = null;
/**
 * Names of Javascript callback functions.
 *
 * @var array
 */


	function link($title1,$title2, $url_cargar, $id_cargar) {
        if(isset($title1) && isset($title2) && isset($url_cargar) && isset($id_cargar)){
            $return="<a href=\"#".$title1."\" onClick=\"cargar_contenido('".$url_cargar."','".$id_cargar."');\">".$title2."</a>";
        }else{
        	$return="";
        }
		return $return;
	}//end link
    /**
     * Abre el marco para encerrar el contenido
     * @param text titulo
     */
	function AbrirMarco ($titulo) {
		if(!isset($titulo) || empty($titulo)){
			$titulo="&nbsp;";
		}

       $return ='<fieldset class="fieldset_marco"><legend class="titulo_marco"><b>'.$titulo.'</b></legend>
                 ';

                 return $return;
	}
	function CerrarMarco () {
          return '</fieldset>';
	}

	function input_fecha ($name,$id,$value=null) {
		echo "" .
			"<input type=\"text\" name=\"".$name."\" id=\"".$id."\" value=\"".$value."\" class=\"in_fe\" readonly=\"true\" onfocus=\"displayCalendar(document.getElementById('$id'),'yyyy-mm-dd',this)\">" .
					"<img style=\"cursor:pointer;margin:auto;\" onClick=\"displayCalendar(document.getElementById('$id'),'yyyy-mm-dd',this)\" src=\"/img/calendar.gif\" align=\"absmiddle\" id=\"img_fecha\"/>";
	}

	function input_fecha2 ($name,$id,$value=null) {
		echo "" .
			"<span class=\"in_fe\"><input type=\"text\" name=\"".$name."\" id=\"".$id."\" value=\"".$value."\" class=\"in_fe2\" readonly=\"true\">" .
					"<img style=\"cursor:pointer;margin:auto;\" src=\"/img/calendar.gif\" align=\"top\" id=\"img_fecha_$id\"/></span>" .
					"<script>//<![CDATA[
Calendar.setup({ trigger: \"img_fecha_$id\", inputField: \"$id\",dateFormat: \"%d/%m/%Y\" });
//]]></script>";
	}



	function input($title, $href = null, $options = array(), $confirm = null, $escapeTitle = true) {
		if (!isset($href)) {
			$href = $title;
		}
		if (!isset($options['url'])) {
			$options['url'] = $href;
		}
        $options ['div']=false;
        $options ['label']=false;
        $options ['type']='change';
        $options ['error']=false;
		if (isset($confirm)) {
			$options['confirm'] = $confirm;
			unset($confirm);
		}
		$htmlOptions = $this->Ajax->__getHtmlOptions($options, array('url'));

		if (empty($options['fallback']) || !isset($options['fallback'])) {
			$options['fallback'] = $href;
		}
		$htmlDefaults = array('id' => 'link' . intval(mt_rand()), 'onchange' => '');
		$htmlOptions = array_merge($htmlDefaults, $htmlOptions);

		$htmlOptions['onchange'] .= ' event.returnValue = false; return false;';
		$return = $this->Form->input($title,$htmlOptions);
		$callback = $this->Ajax->remoteFunction($options);
		$script = $this->Javascript->event("'{$htmlOptions['id']}'", "change", $callback);

		if (is_string($script)) {
			$return .= $script;
		}
		return $return;
	}



	function msj_error ($msj) {
			if(!isset($msj) || empty($msj)){
				$msj="&nbsp;";
			}
			$return= "<script type=\"text/javascript\" language=\"javascript\">
					      error('$msj');
	                </script>";


	                 return $return;
	}//msj error
	function msj_exito ($msj) {
			if(!isset($msj) || empty($msj)){
				$msj="&nbsp;";
			}
			$return= "<script type=\"text/javascript\" language=\"javascript\">
					      exito('$msj');
	                 </script>";


	                 return $return;
	}//msj exito

    function redireccion_contenido ($opcion=array()) {
		$script="<script language=\"JavaScript\" type=\"text/javascript\">
                   cargar_contenido('".$opcion["url"]."','".$opcion["update"]."');
                 </script>";
        return $script;
	}


    function grados($g){
	$r="";
	switch($g){
		case '1':$r='1er';break;
		case '2':$r='2do';break;
		case '3':$r='3er';break;
		case '4':$r='4to';break;
		case '5':$r='5to';break;
		case '6':$r='6to';break;
	}
	return $r;
	}
	function mes($m){
		$r="";
		switch($m){
			case '1':$r= ' Enero ';break;
			case '2':$r= ' Febrero ';break;
			case '3':$r= ' Marzo ';break;
			case '4':$r= ' Abril ';break;
			case '5':$r= ' Mayo ';break;
			case '6':$r= ' Junio ';break;
			case '7':$r= ' Julio ';break;
			case '8':$r= ' Agosto ';break;
			case '9':$r= ' Septiembre ';break;
			case '10':$r=' Octubre ';break;
			case '11':$r=' Nobiembre ';break;
			case '12':$r=' Diciembre ';break;
		}
		return $r;
	}


    function fecha($fecha){
               $f1=explode('-',$fecha);//2008-01-01
               $f2=explode('/',$fecha);//01/01/2008
               if(count($f1)==3){
                   return "".$f1[2]."/".$f1[1]."/".$f1[0];
               }else if(count($f2)==3){
                   return "".$f2[2]."-".$f2[1]."-".$f2[0];
               }else{
                   return null;
               }
      }//fin funcion

     function seleccion_mes ($nombre,$id) {
		$meses=array('01'=>'Enero','02'=>'Febrero','03'=>'Marzo','04'=>'Abril','05'=>'Mayo','06'=>'Junio','07'=>'Julio','08'=>'Agosto','09'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre');
	    echo $this->Form->select($nombre,$meses,null,array('class'=>'input_1','id'=>$id));
	}

	function divide_fecha($fecha,$devolver){

               $f1=explode('-',$fecha);//2008-01-01
               $f2=explode('/',$fecha);//01/01/2008
               if(count($f1)==3){
                   switch(strtoupper($devolver)){
                   	case 'DIA': $R= "".$f1[2]; break;
                        case 'MES': $R= "".$f1[1]; break;
                        case 'ANO': $R= "".$f1[0]; break;
                        default:$R=null;
                   }
                   return $R;
               }else if(count($f2)==3){
                   switch(strtoupper($devolver)){
                   	case 'DIA': $R= "".$f2[0]; break;
                        case 'MES': $R= "".$f2[1]; break;
                        case 'ANO': $R= "".$f2[2]; break;
                        default:$R=null;
                   }
                   return $R;
               }else{
                   return null;
               }
      }//fin funcion
      function colorG ($i,$p) {
	    $colores[1] = array(0,0,0);        // Negro
		$colores[2] = array(220,220,220);  // Gris claro
		$colores[3] = array(255,150,150);  // Rojo claro
		$colores[4] = array(127,200,233);  // Azul dos claro
		$colores[5] = array(150,255,150);  // Verde claro
		$colores[6] = array(242,125,235);  // Rosa claro
		$colores[7] = array(247,186,91);  // Naranja claro
		$colores[8] = array(187,108,253);  // Morado claro
		$colores[9] = array(204,204,204);  // Gris claro
		$colores[10] = array(150,150,255);  // Azul claro
		$colores[11] = array(233,244,115);  // Amarillo claro
		$colores[12] = array(211,154,104);  // Naranja dos claro
		$colores[13] = array(210, 120, 126);  // Naranja dos claro
		$colores[14] = array(210, 10, 54);  // Naranja dos claro
		return $colores[$i][$p];
	}


function inputTagRemote($name=null, $options = array()){

Helper::return_helpers();
$this->Html->setFormTag($name);


if(!isset($options['id'])) {$options['id'] = $name;}
if(!isset($options['size'])) {$options['size'] = '3';}
if(!isset($options['onKeyPress'])) {$options['onKeyPress'] = '';}
if(!isset($options['onChange'])) {$options['onChange'] = '';}
if(!isset($options['onFocus'])) {$options['onFocus'] = '';}
if(!isset($options['onBlur'])) {$options['onBlur'] = '';}
if(!isset($options['readonly'])){$options['readonly'] = '';}
if(!isset($options['class'])) {$options['class'] = '';}
if(!isset($options['style'])) {$options['style'] = '';}
if(!isset($options['maxlength'])) {$options['maxlength'] = '30';}
if(!isset($options['value'])) {$options['value'] = '';}
if(!isset($options['disabled'])){$options['disabled'] = '';}
if(!isset($options['type'])){$options['type'] = 'text';}
if(!isset($options['autocomplete'])){$options['autocomplete'] = 'off';}


if (!isset($options['loading'])) {
				$options['loading'] = "Element.show('mini_loading');";
				$options['loading'] = $options['loading'] ;
				//htmlOptions['onclick'] = 'return false; ';
}//fin


if (!isset($options['complete'])) {
				$options['complete'] = "Element.hide('mini_loading');";
				$options['complete'] = $options['complete'] ;
				//htmlOptions['onclick'] = 'return false; ';
}//fin



echo '<input value="'.$options['value'].'" type="'.$options['type'].'"  style="'.$options ['style'].'"  '.$options['disabled'].'  size="'.$options ['size'].'" name="data['.$this->Html->model.']['.$this->Html->field.']" id="'.$options ['id'].'" onKeyPress="'.$options['onKeyPress'].'"  onBlur="'.$options['onBlur'].'"  onFocus="'.$options['onFocus'].'" onChange="'.$options ['onChange'].'"  maxlength="'.$options['maxlength'].'" class="'.$options['class'].'" autocomplete="'.$options['autocomplete'].'"  '.$options['readonly'].' />';


echo '<div id="aux" style="display:none;">
						<input type="hidden" name="existe" value="nada"  id="existe"/>
						<input type="hidden" name="aux_codigo" value=""  id="aux_codigo"/>
						<input type="hidden" name="aux_existe" value=""  id="aux_existe"/>
     </div>';



if(isset($options['update'])){
$options['update'] = $options['update'];
$options['url'] = $options['url'];
$optValue = $options['id'] ;
 $options['type'] = 'change';
echo  $this->Javascript->event("'{$optValue}'", $this->Ajax->ajax_remote("change", $this->http_use), $this->Ajax->remoteFunction($options));
}//fin if




for($i=1; $i<=10; $i++){
		if (isset($options['update'.$i])) {
		   		 $options['update'] = $options['update'.$i];
            	 $options['url'] = $options['url'.$i];
            	 $options['type'] = 'change';
            	 $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("change", $this->http_use), $this->Ajax->remoteFunction($options));
         	echo $script;
		   }//FIN IF
}//fin for



}//fin function



function submitTagRemote($title = 'Submit', $options = array(), $confirm=true) {
	    Helper::return_helpers();
		$htmlOptions          =  $this->Ajax->__getHtmlOptions($options);
		$htmlOptions['value'] =  $title;
		$options['type']      =  'click';
		$var_encargada        = 0;
        $ya                   = 0;
	    $script               = "";
	    $salir                = "";

					if (!isset($htmlOptions['loading'])) {
									$htmlOptions['loading'] = "Element.show('mini_loading');";
									$options['loading'] = $htmlOptions['loading'] ;
					}//fin
					if (!isset($htmlOptions['complete'])) {
									$htmlOptions['complete'] = "Element.hide('mini_loading');";
									$options['complete'] = $htmlOptions['complete'] ;
					}//fin

					if (!isset($htmlOptions['loading'])) {
									$htmlOptions['loading'] = "Element.show('mini_loading');";
									$options['loading'] = $htmlOptions['loading'] ;
					}//fin
					if (!isset($htmlOptions['complete'])) {
									$htmlOptions['complete'] = "Element.hide('mini_loading');";
									$options['complete'] = $htmlOptions['complete'] ;
					}//fin
							if (!isset($options['with'])) {
									$options['with'] = 'Form.serialize(Event.element(event).form)';
									//$options['with'] = "form";
							}
							if (!isset($htmlOptions['id'])) {
									$htmlOptions['id'] = 'submit_' . intval(rand());
									$options['id'] = $htmlOptions['id'] ;
							}

if($confirm==true && strtoupper($htmlOptions['value'])=='ELIMINAR'){$options['confirm'] ="Esta seguro que desea eliminar este registro";}else{$options['confirm']="";}

if(!empty($htmlOptions['funcion'])){$options['funcion'] = $htmlOptions['funcion'];}else{$options['funcion']  = '';}//fin if

if($var_encargada==0){$htmlOptions['onclick'] = "javascript:link_javascript_visor_submit('".$htmlOptions['id']."', '".$this->Ajax->ajax_remote("click", $this->http_use)."', '".$options['funcion']."', '".$this->http_use."',  '".$this->http_use_var."', event, '".$options['confirm']."' ";}

					  for($i=1; $i<=10; $i++){
						if (isset($options['update'.$i])) {
							     if($confirm==true && strtoupper($htmlOptions['value'])=='ELIMINAR' && $ya==0){$options['confirm'] ="Estas seguro que desea eliminar este registro"; $ya++;}else{$options['confirm']='';}
						   		 $options['update'] = $options['update'.$i];
				            	 $options['url'] = $options['url'.$i];
				            	 if($var_encargada!=0){
				            	   $script .= $this->Javascript->event("'{$htmlOptions['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
				            	 }else{
				                     $htmlOptions['onclick'] .= ", '".$options['url']."', '".$options['update']."'  ";
				            	 }//fin funtion
						   }//FIN IF
				       }//FIN FOR

   if($var_encargada==0){
	  	$htmlOptions['onclick'] .= "); return false;";
 }else{
 	$htmlOptions['onclick'] = '  return false; ';
 }


	echo $this->submit($title, $htmlOptions);
 	echo $script;

}//fin function


function submit($caption = 'Submit', $htmlAttributes = array(), $return = false, $title_value=null) {
		$htmlAttributes['value'] = $caption;
		return $this->Html->output(sprintf($this->Html->tags['submit'], $this->Html->_parseAttributes($htmlAttributes, null, '', ' ')), $return);
}

function buttonTagRemote($fieldName, $htmlAttributes = array(), $options = array(),  $return = false, $confirm=true) {
	     Helper::return_helpers();
	     $this->Html->setFormTag($fieldName);
	     $var_encargada      = 0;
         $ya                 = 0;
		 $script             = "";
		 $options['type']    = 'click';
		 $salir              = "";


		if (!isset($htmlAttributes['value'])) {
					$htmlAttributes['value'] = $this->Html->tagValue($fieldName);
	    }
		if(!isset($htmlAttributes['type'])){$htmlAttributes['type'] = 'button';}
		if(!isset($htmlAttributes['src'])){$htmlAttributes['src'] = '';}
		if(!isset($htmlAttributes['id'])){$htmlAttributes['id'] = 'button' . intval(rand());}
		if (!isset($htmlAttributes['loading'])) {
						$htmlAttributes['loading'] = "Element.show('mini_loading');";
						$options['loading'] = $htmlAttributes['loading'] ;
		}
		if (!isset($htmlAttributes['complete'])) {
						$htmlAttributes['complete'] = "Element.hide('mini_loading');";
						$options['complete'] = $htmlAttributes['complete'] ;
		}
        if (!isset($htmlAttributes['class'])) {$htmlAttributes['class'] = ' ';}//fin else
		    	if(!empty($htmlAttributes['funcion'])) {
				  $options['funcion'] = $htmlAttributes['funcion'];
		 }else{
				 $options['funcion']  = '';
		 }//fin if


if($confirm==true && strtoupper($htmlAttributes['value'])=='ELIMINAR'){ $options['confirm'] ="Esta seguro que desea eliminar este registro";}else{$options['confirm']="";}

if($var_encargada==0){$htmlAttributes['onclick'] = "javascript:link_javascript_visor('".$htmlAttributes['id']."', '".$this->Ajax->ajax_remote("click", $this->http_use)."', '".$options['funcion']."', '".$this->http_use."', '".$this->http_use_var."',  '".$options['confirm']."' ";}

	  for($i=1; $i<=10; $i++){
		if (isset($options['update'.$i])) {
			     if($confirm==true && strtoupper($htmlAttributes['value'])=='ELIMINAR' && $ya==0){$options['confirm'] ="Estas seguro que desea eliminar este registro"; $ya++;}else{ $options['confirm']='';}
		   		 $options['update'] = $options['update'.$i];
            	 $options['url'] = $options['url'.$i];
            	 if(strtoupper($htmlAttributes['value'])=='SALIR'){
                     if($options['url']=="/ccnp01_concejo_comunales_entrada/vacio" || $options['url']=="/administradors/vacio" || $options['url']=="/modulos/vacio" || $options['url']=="/administradors/"  || $options['url']=="/modulos/vacio/" || $options['url']=="/administradors/vacio" ||  $options['url']=="/administradors/uso_general"){
                        $salir = true;
                     }else{
                     	if($salir!=true){$salir = false;}
                     }//fin else
            	 }else{
            	 	    $salir = true;
            	 }//fin else
            	 if($var_encargada!=0){
            	   $script .= $this->Javascript->event("'{$htmlAttributes['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
            	 }else{
                     $htmlAttributes['onclick'] .= ", '".$options['url']."', '".$options['update']."'  ";
            	 }//fin funtion
		   }//FIN IF
       }//FIN FOR

       if($var_encargada==0){
		  	$htmlAttributes['onclick'] .= "); ";
		 }

	echo $this->output(sprintf($this->Html->tags['input'], $this->Html->model, $this->Html->field, $this->Html->_parseAttributes($htmlAttributes, null, ' ', ' ')), $return);

	echo $script;


}//fin function


function agregar_imagen ($opcion,$identificacion,$id_capa_cargar) {
	echo $this->Sisap->imagen_ventana(array("value"=>".."),6,"Cargar Imagen", "/info_imagenes/index/formulario/".$opcion."/".$identificacion."/".$id_capa_cargar."/add", "400px", "110px" );
}

function modificar_imagen ($opcion,$identificacion,$id_capa_cargar) {
	echo $this->Sisap->imagen_ventana(array("value"=>".."),6,"Cargar Imagen", "/info_imagenes/index/formulario/".$opcion."/".$identificacion."/".$id_capa_cargar."/modificar", "400px", "110px" );
}

function ver_miniatura_persona ($opcion,$identificador) {
	echo '<img src="/info_imagenes/ver_miniatura/'.$identificador.'/'.$opcion.'/'.intval(rand()).'" border="0" height="146" title="HACER CLICK PARA VER LA IMAGEN" width="110" style="cursor:pointer;" onclick="'.$this->Sisap->onclick_ventana('/info_imagenes/ver_imagen_grande/'.$identificador.'/'.$opcion,'Imagen','550px','400px').'"/>';
}





function radio_nivel_consulta($lista_ano=array(), $seleccion_ano=null, $vector_presi=array(),  $cod_presi_seleccion=null, $opcion=array()){


 $aux             = 'radio_nivel_consulta';
 $campos          = array('1'=>'República', '2'=>'Estados', '3'=>'Tipo de Instituciones', '4'=>'Institución');
 $options['type'] = 'click';

	if(!isset($opcion["url"])){$opcion["url"]="";}
	if(!isset($opcion["update"])){$opcion["update"]="";}

echo "<table width='100%' border='0' cellspacing='0' cellpadding='0' class='tablacompromiso tablacompromiso2'>";
echo "		<tr>
			<td width='20%' align='right' class='fila_titulos'>Año:</td>
			<td>";
			    $this->Sisap->selectTagRemote('datos/ano_consolidado', $lista_ano,  array('value1'=>'todo', 'opcion1'=>'Todos'),  $seleccion_ano ,array("onchange1"=>$opcion["url"], "update1"=>$opcion["update"], 'id'=>'ano_estimacion', 'style'=>'width:80px', 'maxlength'=>'8','class'=>'input_2', true), null, true);
 echo "     </td>
		    </tr>";



		if(LOGOINST!="1_11_30_11"){

										echo "		<tr>
													<td width='20%' align='right' class='fila_titulos'>Nivel de consulta:</td>
													<td align='left'>
										";
											foreach($campos as $optValue => $optTitle){
														$contar        = 0;
														$inbetween     = "<br>";
														$options['id'] = $aux.'_'.$optValue;
														$read          = "";
										                if($optValue != 1){$read="";}else{$read="checked";}
														if($optValue=="2"){ $inbetween = "";}
														echo'<input type="radio" name="data[datos][radio_nivel_consulta]"  id="'.$options['id'].'" value="'.$optValue.'"  '.$read.'  ><label for="'.$options['id'].'">'.$optTitle.'</label> '.$inbetween.'';
														  Helper::return_helpers();

																   		  $options['update'] = "capa_carga_sesion";
														            	  $options['url']    = "/info_select_nivel_consulta/index/".$optValue.'';
														            	  $script = $this->Javascript->event("'{$options['id']}'", $this->Ajax->ajax_remote("click", $this->http_use), $this->Ajax->remoteFunction($options));
																    echo $script;
											  }//fin for
										echo "
										           </td>
												</tr>
											</table>

										<br><br>

											<div id='capa_carga_sesion'>
											 <table width='25%' border='0' cellpadding='0' cellspacing='0' class='tablacompromiso tablacompromiso2' align='left'>
											  <tr>
											     <td align='center' class='fila_titulos'>República</td>
											  </tr>
											 <tr>
											     <td width='25%' id='n_select_1'>";
											            $this->Sisap->selectTagRemote('datos/cod_presi',  $vector_presi, null,  $cod_presi_seleccion, array('id'=>'select_1', 'onChange'=>'vacio', 'style'=>'width:100%','class'=>'input_2', true), null, true);
										echo "   </td>
											 </tr>
										</table>
										<br><br><br>
											</div>
										<br><br>
										";


		}else{


                   echo "<input name=data[datos][cod_presi]            value=1 type=hidden id=select_1 >";
                   echo "<input name=data[datos][radio_nivel_consulta] value=1 type=hidden id=select_2 >";


                               echo "
										           </td>
												</tr>
											</table>

										<br><br> ";



		}//fin else

}//fin funcion




















}//end class Info

?>