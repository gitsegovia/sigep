<?php
class Sisap2Helper extends Helper{
	var $helpers = array('Html', 'Javascript', 'Ajax');

	function Tabla($entidad=null,$fecha=false,$titulo=null,$subtitulo=null,$anchoPX=null) {
	$url = $this->webroot . $this->themeWeb . IMAGES_URL;
	if(isset($_SESSION["concejo_comunal"])){
		$img_dependencia=$url.'logos_consejos/logo_'.$_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'';
		$img_dependencia_o='../webroot/img/logos_consejos/logo_'.$_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'';

		if(file_exists($img_dependencia_o.".gif") || file_exists($img_dependencia_o.".png")){
			$img_dependencia=file_exists($img_dependencia_o.".gif")==true?$img_dependencia.".gif":$img_dependencia.".png";
			$escudo='<img src="'.$img_dependencia.'" alt="logo_'.$_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'"/>';
		}else{
			$escudo='<img src="/img/logos_dependencias/logo_1_11_30_11_1.png" alt="logo"/>';
		}

		if(isset($entidad)){
			if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
			if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
		}else{
			if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
			if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
		}
		$fecha = $fecha == true ? date("d/m/Y") : "";
        $titulo = !empty($titulo) ? $titulo :"Error: debe pasar como parametro el titulo.";
        $subtitulo = isset($subtitulo) ? $subtitulo : "";
        $width = isset($anchoPX) ? $anchoPX : "100%";

		$contenido='<table width="100%"  height="100" border="0" cellspacing="0" cellpadding="0" id="tabla_top">' .
				   '<tr><td width="73" height="80">'.$escudo.'</td>' .
				   '<td class="text14Tabla">&nbsp;</td>' .
				   '<td align="right" valign="top" class="text14Tabla">'.$fecha.'&nbsp;&nbsp;&nbsp;</td>' .
				   '</tr>' .
				   '<tr>' .
				   '<td colspan="3" height="28" align="center" class="text18Tabla">'.$this->cambiar(strtoupper($titulo)).'</td>' .
				   '</tr>' .
				   '<tr><td colspan="3" align="center" >'.$subtitulo.' &nbsp;</td></tr>'.
                   '<tr><td colspan="3">&nbsp;&nbsp;&nbsp; '.$this->cambiar($dependencia).'</td></tr>' .
                   	'</table>';
		if(defined('VERSION')== true && VERSION == 2){
			$this->MarcoTabla_v2($contenido,$width);
		}else{
			$this->MarcoTabla_v1($contenido,$width);
		}
	}else{
		if(isset($_SESSION['SScodpresi']) && isset($_SESSION['SScodentidad'])){
			$img_escudo=$url.'escudo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'';
			//$img_dependencia=$url.'logos_dependencias/logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';
			$img_dependencia=$url.'logos_dependencias/logo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';
			$img_institucion=$url.'escudos/logo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'';
			$img_escudo_o='../webroot/img/escudos/escudo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'';
			//$img_dependencia_o='../webroot/img/logos_dependencias/logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';
			$img_dependencia_o='../webroot/img/logos_dependencias/logo_'.$_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'';
			if($_SESSION['SScodpresi']==1 & $_SESSION['SScodentidad']==1 && $_SESSION['SScodtipoinst']==1 && $_SESSION['SScodinst']==1 && $_SESSION['SScoddep']==1){
				$l=$_SESSION['SScodpresi']."_".$_SESSION['SScodentidad']."_".$_SESSION['SScodtipoinst']."_".$_SESSION['SScodinst']."_".$_SESSION['SScoddep'];
				$escudo="<img src='".$url."/logos_dependencias/logo_".$l.".png'/>";
			}else{
				if(file_exists($img_dependencia_o.".gif") || file_exists($img_dependencia_o.".png")){
					$img_dependencia=file_exists($img_dependencia_o.".gif")==true?$img_dependencia.".gif":$img_dependencia.".png";
					$escudo='<img src="'.$img_dependencia.'" alt="logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'"/>';
				}else{
					if(file_exists($img_escudo_o.".gif") || file_exists($img_escudo_o.".png")){
                        $img_dependencia=file_exists($img_escudo_o.".gif")==true?$img_institucion.".gif":$img_institucion.".png";
					    $escudo='<img src="'.$img_dependencia.'" alt="logo_'.$_SESSION['SScodinst'].'_'.$_SESSION['SScoddep'].'"/>';
					}else{
						$escudo='<img src="'.$url.'logos_dependencias/logo_jl.png"/>';
					}
				}
			}//fin else
		}else{
  			$escudo='<img src="'.$url.'logos_dependencias/logo_jl.png"/>';
		}

		if(isset($entidad)){
			if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
			if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
		}else{
			if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
			if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
		}

        $fecha = $fecha == true ? date("d/m/Y") : "";
        $titulo = !empty($titulo) ? $titulo :"Error: debe pasar como parametro el titulo.";
        $subtitulo = isset($subtitulo) ? $subtitulo : "";
        $width = isset($anchoPX) ? $anchoPX : "100%";

		  $contenido='<table width="100%"  height="100" border="0" cellspacing="0" cellpadding="0" id="tabla_top">' .
						   '<tr><td width="73" height="80">'.$escudo.'</td>' .
						   '<td class="text14Tabla">&nbsp;</td>' .
						   '<td align="right" valign="top" class="text14Tabla">'.$fecha.'&nbsp;&nbsp;&nbsp;</td>' .
						   '</tr>' .
						   '<tr>' .
						   '<td colspan="3" height="28" align="center" class="text18Tabla">'.$this->cambiar(strtoupper($titulo)).'</td>' .
						   '</tr>' .
						   '<tr><td colspan="3" align="center" >'.$subtitulo.' &nbsp;</td></tr>'.
		                   '<tr><td colspan="3">&nbsp;&nbsp;&nbsp;'.$this->cambiar($dependencia).'</td></tr>' .
		                   	'</table>';



			               $this->MarcoTabla_v2($contenido,$width);
	}//fin else



}//fin function Tabla


  function MarcoTabla_v2($content,$width){
  	  $ancho_td_central=$width-(28*2);
      $url = $this->webroot . $this->themeWeb . IMAGES_URL;
  	 $tablaGeneral ='<table width="'.$width.'" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td  style="width:28px;height:23px;background-image:url('.$url.'tabla_r2_c2.png);">&nbsp;</td>
    <td  style="background-image:url('.$url.'tabla_r2_c4.png); background-repeat:repeat-x"><img src="'.$url.'spacer.gif" width="'.$ancho_td_central.'" height="23" border="0" alt="" /></td>
    <td  style="width:28px;height:23px;background-image:url('.$url.'tabla_r2_c6.png);">&nbsp;</td>
  </tr>
  <tr>
    <td style="width:28px;background-image:url('.$url.'tabla_r4_c2.png); background-repeat:repeat-y"><img src="'.$url.'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
    <td bgcolor="#AAD6EA">'.$content.'</td>
    <td style="width:28px;background-image:url('.$url.'tabla_r4_c6.png); background-repeat:repeat-y"><img src="'.$url.'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
  </tr>
  <tr>
    <td width="28" height="25"><img name="tabla_r6_c2" src="'.$url.'tabla_r6_c2.png" width="28" height="25" border="0" id="tabla_r6_c2" alt="" /></td>
    <td  style="background-image:url('.$url.'tabla_r6_c4.png); background-repeat:repeat-x"><img src="'.$url.'spacer.gif" width="1" height="25" border="0" alt="" /></td>
    <td width="28" height="25"><img name="tabla_r6_c6" src="'.$url.'tabla_r6_c6.png" width="28" height="25" border="0" id="tabla_r6_c6" alt="" /></td>
  </tr>
</table>';
		echo $tablaGeneral;
  }//fin marcoTabla



   function OpenTable($width){
			$this->OpenTable_v2($width);
   }//
    function CloseTable(){
			$this->CloseTable_v2();
   }//


  function OpenTable_v2($width){
      $url = $this->webroot . $this->themeWeb . IMAGES_URL;
  	  $AbreTabla ='<table width="'.$width.'" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="28" height="23"><img name="tabla_r2_c2" src="'.$url.'tabla_r2_c2.png" width="28" height="23" border="0" id="tabla_r2_c2" alt="" /></td>
    <td style="background-image:url('.$url.'tabla_r2_c4.png); background-repeat:repeat-x"><img src="'.$url.'spacer.gif" width="1" height="23" border="0" alt="" /></td>
    <td width="28" height="23"><img name="tabla_r2_c6" src="'.$url.'tabla_r2_c6.png" width="28" height="23" border="0" id="tabla_r2_c6" alt="" /></td>
  </tr>
  <tr>
    <td  style="background-image:url('.$url.'tabla_r4_c2.png); background-repeat:repeat-y"><img src="'.$url.'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
    <td bgcolor="#AAD6EA" valign="top" align="center" style="font-family: arial,sans-serif;font-size: 10px;text-transform: uppercase;">';
		echo $AbreTabla;
  }//fin OpenTable

  function CloseTable_v2(){
      $url = $this->webroot . $this->themeWeb . IMAGES_URL;
  	  $cerrarTabla ='</td>
    <td style="background-image:url('.$url.'tabla_r4_c6.png); background-repeat:repeat-y"><img src="'.$url.'spacer.gif" width="28" height="100%" border="0" alt="" /></td>
  </tr>
  <tr>
    <td width="28" height="25"><img name="tabla_r6_c2" src="'.$url.'tabla_r6_c2.png" width="28" height="25" border="0" id="tabla_r6_c2" alt="" /></td>
    <td  style="background-image:url('.$url.'tabla_r6_c4.png); background-repeat:repeat-x"><img src="'.$url.'spacer.gif" width="1" height="25" border="0" alt="" /></td>
    <td width="28" height="25"><img name="tabla_r6_c6" src="'.$url.'tabla_r6_c6.png" width="28" height="25" border="0" id="tabla_r6_c6" alt="" /></td>
  </tr>
</table>';
		echo $cerrarTabla;
  }//fin CloseTable

  function TablaMsj($capa1=null,$capa2=null){
  	$url = $this->webroot . $this->themeWeb . IMAGES_URL;
  	if(isset($capa1)){
  		$o='<div id="'.$capa1.'"></div>';
  	}else{
  		$o='';
  	}
  	if(isset($capa2)){
  		$oo='<div id="'.$capa2.'"></div>';
  	}else{
  		$oo='';
  	}
  	$contenido='<table width="750"  border="0" cellpadding="0" cellspacing="0"  style="margin-top:10px;">
<tr><td width="1"><img src="'.$url.'blank.gif" width="1" height="30"></td><td><div id="msj_cancelar" style="display:none;"></div>
			<div id="msj_aceptar" style="display:none;"></div>'.$o.$oo.'</td></tr>
</table>';

    echo $contenido;


  }//fin tablamsj


  function cambiar($cadena) {
  	 $cadena = str_replace("&AACUTE;","&Aacute;", $cadena);
     $cadena = str_replace("&EACUTE;","&Eacute;", $cadena);
     $cadena = str_replace("&IACUTE;","&Iacute;", $cadena);
     $cadena = str_replace("&OACUTE;","&Oacute;", $cadena);
     $cadena = str_replace("&UACUTE;","&Uacute;", $cadena);
     $cadena = str_replace("à","&aacute;", $cadena);
     $cadena = str_replace("è","&eacute;", $cadena);
     $cadena = str_replace("ì","&iacute;", $cadena);
     $cadena = str_replace("ò","&oacute;", $cadena);
     $cadena = str_replace("ù","&uacute;", $cadena);
     $cadena = str_replace("À","&Aacute;", $cadena);
     $cadena = str_replace("È","&Eacute;", $cadena);
     $cadena = str_replace("Ì","&Iacute;", $cadena);
     $cadena = str_replace("Ò","&Oacute;", $cadena);
     $cadena = str_replace("Ù","&Uacute;", $cadena);
     return $cadena;
}












function top_tabla($entidad=null,$fecha=false,$titulo=null,$subtitulo=null,$anchoPX=null) {

	$url             = $this->webroot . $this->themeWeb . IMAGES_URL;
	$contenido       = $this->imagen_top();
    if(defined('LOGOINST')){
   	   $dir_escudo_inst=LOGOINST;
    }else{
       $dir_escudo_inst="jl";
    }
    $comunal_referencia=substr_count (strtoupper(env('HTTP_REFERER')), 'CCNP01_CONCEJO_COMUNALES_ENTRADA');
    if(isset($_SESSION["concejo_comunal"]) && $comunal_referencia!=0){
    	$dir_escudo      = $_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'';
        $dir_escudo_dep  = $_SESSION['CC_republica'].'_'.$_SESSION['CC_estado'].'_'.$_SESSION['CC_municipio'].'_'.$_SESSION['CC_parroquia'].'_'.$_SESSION['CC_centro'].'_'.$_SESSION['CC_concejo'].'';
        $url_escudo      = "img/logos_consejos/logo_";
    }else{
    	$dir_escudo      = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst'];
	    $dir_escudo_dep  = $_SESSION['SScodpresi'].'_'.$_SESSION['SScodentidad'].'_'.$_SESSION['SScodtipoinst'].'_'.$_SESSION['SScodinst']."_".$_SESSION['SScoddep'];
	    $url_escudo      = "img/logos_dependencias/logo_";
    }

    if(DEMOSISAP==true){
              if(file_exists($url_escudo."".$dir_escudo_inst.".png")){
               $img_escudo = $this->webroot.$url_escudo."".$dir_escudo_inst.".png";
    	}else{ $img_escudo = $this->webroot."img/logos_dependencias/logo_jl.png";}
    }else{
    	   if(isset($_SESSION["concejo_comunal"]) && $comunal_referencia!=0){
	          	  if(file_exists($url_escudo."".$dir_escudo_dep.".png")){
	          	   $img_escudo = $this->webroot.$url_escudo."".$dir_escudo_dep.".png";
	    	}else if(file_exists("img/logos_dependencias/logo_"."".$dir_escudo_inst.".png")){
	    		   $img_escudo = $this->webroot."img/logos_dependencias/logo_".$dir_escudo_inst.".png";
	    	}else{ $img_escudo = $this->webroot."img/logos_dependencias/logo_jl.png";}
    	}else{
                 if(file_exists($url_escudo."".$dir_escudo_dep.".png")){
	          	   $img_escudo = $this->webroot.$url_escudo."".$dir_escudo_dep.".png";
	    	}else if(file_exists($url_escudo."".$dir_escudo.".png")){
	    		   $img_escudo = $this->webroot.$url_escudo."".$dir_escudo.".png";
	    	}else{ $img_escudo = $this->webroot."img/logos_dependencias/logo_jl.png";}
    	}
    }

    $escudo='<img src="'.$img_escudo.'" alt="logo"/>';

	    if(isset($entidad)){
			if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
			if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
		}else{
			if(isset($_SESSION['entidad_federal'])){$entidad=strtoupper($_SESSION['entidad_federal']);}else{$entidad='';}
			if(isset($_SESSION['dependencia'])){$dependencia=strtoupper($_SESSION['dependencia']);}else{$dependencia='';}
		}

        $fecha      = $fecha == true ? date("d/m/Y") : "";
        $titulo     = !empty($titulo) ? $titulo :"Error: debe pasar como parametro el titulo.";
        $subtitulo  = isset($subtitulo) ? $subtitulo : "";
		$contenido .='<style> </style>' .
				'<style type="text/css">' .
				'* {
margin: 0;
padding: 0;
}

body {
padding: 14px 0 0 14px ;
}

#sombra_2, #sombra_1 {
font-size: 14pt;
text-transform: uppercase;
font-family: Arial;
width: 90%;
display: block;
font-weight: normal;
}

.shadow_titulo {
color: #000;
line-height: 19px;
}

.text_shadow_titulo {
color: #fff;
position: relative;
bottom: 19px;
left: 2px;
}
' .
'
#sombra_22, #sombra_11 {
font-size: 10pt;
text-transform: uppercase;
font-family: Arial;
width: 90%;
display: block;
font-weight: normal;
}

.shadow_titulo1 {
color: #000;
line-height: 14px;
}

.text_shadow_titulo1 {
color: #fff;
position: relative;
bottom: 14px;
left: 2px;
}' .
'' .
'#sombra_23, #sombra_13 {
font-size: 8pt;
font-family: Arial;
width: 90%;
display: block;
font-weight: normal;
}

.shadow_titulo2 {
color: #000;
line-height: 11px;
}

.text_shadow_titulo2 {
color: #fff;
position: relative;
bottom: 11px;
left: 2px;
}
' .
				'</style>' .
				'<table width="100%"  height="100" border="0" cellspacing="0" cellpadding="0" id="tabla_top">' .
						   '<tr><td width="73" height="80">'.$escudo.'</td>' .
						   '<td class="text14Tabla">&nbsp;</td>' .
						   '<td align="right" valign="top" class="text14Tabla"><span class="shadow_titulo2" id="sombra_13">'.$fecha.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><h2 class="text_shadow_titulo2" id="sombra_23">'.$fecha.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h2></td>' .
						   '</tr>' .
						   '<tr>' .
						   //'<td colspan="3" height="28" align="center" class="text18Tabla"><span class="class_sombra">&nbsp;'.$this->cambiar(strtoupper($titulo)).'&nbsp;</span></td>' .
						   '<td colspan="3" height="28" align="center"><span class="shadow_titulo" id="sombra_1">'.$this->cambiar(strtoupper($titulo)).'</span><h2 class="text_shadow_titulo" id="sombra_2">'.$this->cambiar(strtoupper($titulo)).'</h2></td>' .
						   '</tr>' .
						   '<tr><td colspan="3" align="center" >'.$subtitulo.' &nbsp;</td></tr>'.
		                   '<tr><td colspan="3"> <span class="shadow_titulo1" id="sombra_11">'.$this->cambiar($dependencia).'</span><h2 class="text_shadow_titulo1" id="sombra_22">'.$this->cambiar($dependencia).'</h2></td></tr>' .
		                   	'</table>';
		return $contenido;
}//fin function









function imagen_top(){

	$img_top = '../webroot/img/fondos/top/'.date("d").'.jpg';
	if(file_exists($img_top)){
		$img_dia = date("d");
	}else{
		$img_dia = 'default';
	}

	$contenido='<style>
						#tabla_top{
							background-image:url(/img/fondos/top/'.$img_dia.'.jpg);
							background-repeat:no-repeat;
							 -moz-border-radius: 15px;
						     -webkit-border-radius: 15px;
						      border-radius: 15px;
						}
			    </style>';

return $contenido;


}











} //FIN CLASS
?>