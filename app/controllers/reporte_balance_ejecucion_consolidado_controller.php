<?php
class ReporteBalanceEjecucionConsolidadoController extends AppController{

    var $uses = array('cimd06_acta_firmantes','cugd02_institucion','cugd02_dependencia','arrd05','ccfd04_cierre_mes','v_balance_ejecucion','v_balance_ejecucion2','v_balance_ejecucion_inst','v_balance_ejecucion2_inst','v_cfpd05_denominaciones', 'v_cfpd05_tipo_gasto',
                      'cfpd10_reformulacion_texto','cfpd05','v_analisis_presupuesto','cfpd10_reformulacion_partidas','cfpd10_reformulacion_texto','cscd04_ordencompra_encabezado','cscd04_ordencompra_partidas','cobd01_contratoobras_partidas','cobd01_contratoobras_cuerpo','cepd02_contratoservicio_partidas','cepd02_contratoservicio_cuerpo','cepd01_compromiso_partidas','cepd01_compromiso_cuerpo','cepd03_ordenpago_partidas','cepd03_ordenpago_cuerpo','cstd03_cheque_partidas','cstd03_cheque_cuerpo',
                       'cepd03_ordenpago_cuerpo','cscd04_ordencompra_encabezado','cscd04_ordencompra_autorizacion_cuerpo','v_cscd04_ordencompra_completa','cscd04_ordencompra_anticipo_cuerpo','cpcd02','cugd03_acta_anulacion_cuerpo','cstd03_cheque_cuerpo','cstd03_movimientos_manuales','cepd01_compromiso_cuerpo','cepd03_ordenpago_tipopago','cepd01_tipo_compromiso','cstd04_movimientos_generales','v_cstd_mov_gral','v_proyeccion_gasto_inst','v_proyeccion_gasto_dep','v_credito_agrupado','v_credito_agrupado_inst','v_credito_agrupado_dep','v_credito_agrupado_inst2','v_credito_agrupado_dep2','cobd01_contratoobras_anticipo_cuerpo','cobd01_contratoobras_valuacion_cuerpo','cepd02_contratoservicio_anticipo_cuerpo','cepd02_contratoservicio_valuacion_cuerpo','cstd01_entidades_bancarias','v_credito_presupuestario_dependencia',
                       'cugd07_firmas_oficio_anulacion','cobd01_contratoobras_cuerpo','cobd01_contratoobras_valuacion_cuerpo','cobd01_contratoobras_retencion_cuerpo','cepd02_contratoservicio_valuacion_partidas','cepd02_contratoservicio_retencion_cuerpo','cfpd07_obras_cuerpo',
                       'ccfd01_tipo','ccfd01_cuenta','ccfd01_subcuenta','ccfd01_division','ccfd01_subdivision', 'v_cscd04_ordencompra', 'v_cfpd05_tipo_gasto2','v_ejecucion_dep','v_ejecucion_inst','v_cfpd20','v_cfpd21','v_cfpd22','v_cfpd23','v_cfpd20_cfpd21','v_librodiario_2','v_cfpd10_reformulacion_partidas_tipopresupuesto',
                       'balance_mes2','balance_mes22_inst','v_proyeccion_gastos_nomina_final','v_proyeccion_gastos_nomina_final_i','v_proyeccion_gastos_nomina_final2','v_proyeccion_gastos_nomina_final2_i','cfpd10_reformulacion_tipo','cugd90_municipio_defecto','cugd01_estados','cugd01_municipios','cfpd02_activ_obra_ordinario');

    var $helpers = array('Html','Ajax','Javascript', 'Sisap', 'Fpdf');

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
        if(defined('SERVIDOR_REPORTE')){
            if(SERVIDOR_REPORTE=="localhost"){
                $this->checkSession();
            }else{
                if(defined('SERVIDOR_HOST')){
                      $longitud              =  strlen(SERVIDOR_HOST);
                      $direccion_referencial =  substr($_SERVER["HTTP_REFERER"], 0, $longitud);
                      if(SERVIDOR_HOST==$direccion_referencial && isset($_SERVER["HTTP_REFERER"])){
                         $_SESSION = recibe_input_array($this->params["form"]["REPORTE"]);
                         if($_SESSION==null){
                              $this->redirect($_SERVER["HTTP_REFERER"]);
                         }else{
                              $cod_presi       =   $_SESSION["SScodpresi"];
                              $cod_entidad     =   $_SESSION["SScodentidad"];
                              $cod_tipo_inst   =   $_SESSION["SScodtipoinst"];
                              $cod_inst        =   $_SESSION["SScodinst"];
                              $cod_dep         =   $_SESSION["SScoddep"];
                              $username        =   $_SESSION["nom_usuario"];
                              $rsp =$this->v_cfpd05_denominaciones->execute("select * from cugd04_entrada_modulo where cod_presi='".$cod_presi."' and  cod_entidad='".$cod_entidad."' and  cod_tipo_inst='".$cod_tipo_inst."' and  cod_inst='".$cod_inst."' and  cod_dep='".$cod_dep."' and  username='".$username."' ");
                           if(!isset($rsp[0][0]["username"])){$this->redirect($_SERVER["HTTP_REFERER"]);}
                         }//else
                      }else{
                         $this->redirect($_SERVER["HTTP_REFERER"]);
                      }
                }else{
                      $this->checkSession();
                }//fin else
            }//fin else
        }else{
            $this->checkSession();
        }//fin else
    }//fin function

    function add_c_c($var){
        if($var<=9 && strlen($var)==1){
                $codigo = '0'.$var;
            }else{$codigo = ''.$var;}
        return $codigo;
    }//fin AddCero

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
   
    function SQLCA_report($pre=null){
         $sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
             $sql_re .= "cod_inst=".$this->verifica_SS(4)." ";
             //$sql_re .= "cod_dep=0";
         }else{
            $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
   
    function SQLCA_report_a($pre=null){
         $sql_re = "a.cod_presi=".$this->verifica_SS(1)."  and    ";
         $sql_re .= "a.cod_entidad=".$this->verifica_SS(2)."  and  ";
         $sql_re .= "a.cod_tipo_inst=".$this->verifica_SS(3)."  and ";
         if($pre!=null && $pre==1){
         $sql_re .= "a.cod_inst=".$this->verifica_SS(4)." ";
         //$sql_re .= "cod_dep=0";
         }else{
            $sql_re .= "a.cod_inst=".$this->verifica_SS(4)."  and  ";
            $sql_re .= "a.cod_dep=".$this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
   
    function SQLCA_report_in($pre=null){
         $sql_re = $this->verifica_SS(1).",";
         $sql_re .= $this->verifica_SS(2).",";
         $sql_re .= $this->verifica_SS(3).",";
         if($pre!=null && $pre==1){
         $sql_re .= $this->verifica_SS(4).",";
         $sql_re .= 0;
         }else{
            $sql_re .= $this->verifica_SS(4).",";
            $sql_re .= $this->verifica_SS(5)." ";
         }

         return $sql_re;
    }//fin funcion SQLCA
   
    function AddCero($nomVar,$vector=object,$extra=null){
      if($vector!=null){
          if($extra==null){
        foreach($vector as $x){
            if($x<10){
               $Var[$x]="0".$x;
            }else{
               $Var[$x]=$x;
            }
        }//fin each
      }else{
          foreach($vector as $x){
            if($x<10){
               $Var[$x]=$extra.".0".$x;
            }else{
               $Var[$x]=$extra.".".$x;
            }
        }//fin each
      }
      $this->set($nomVar,$Var);
      }else{
          $this->set($nomVar,'');
      }
    }//fin AddCero
    
    function AddCeroR($n,$extra=null){
        if($n!=null){
            if($extra==null){
                if($n<10){
                    $Var="0".$n;
                }else{
                    $Var=$n;
                }
            }else{
                if($n<10){
                    $Var=$extra.".0".$n;
                }else{
                    $Var=$extra.".".$n;
                }
            }
            return $Var;
        }else{
            //return $Var;
        }
    }


    function limpia_menu(){

         echo'<script>
                             document.getElementById("valida_codigo").innerHTML = "";
                             document.getElementById("valida_codigo").style.display = "none";
                             if(document.getElementById("registro")){document.getElementById("registro").style.visibility = "hidden";}
                             if(document.getElementById("nomina")){document.getElementById("nomina").style.visibility = "hidden";}
                             if(document.getElementById("dependencia")){document.getElementById("dependencia").style.visibility = "hidden";}
                             if(document.getElementById("sistema_general")){document.getElementById("sistema_general").style.visibility = "hidden";}
               </script>';
    }

  function exclusion_presupuesto($consolidado = false)
  {
    if($this->verifica_SS(5)==1)
    {

          if($consolidado){
                  $sql=" and (
                              (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,401) and
                              (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,402) and 
                              (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,403) and
                              (a.cod_sector,a.cod_programa,a.cod_sub_prog,a.cod_activ_obra,a.cod_partida) != (5,1,6,51,404)
                          )";
          }else{
                  $sql=" and (
                              (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,401) and
                              (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,402) and 
                              (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,403) and
                              (cod_sector,cod_programa,cod_sub_prog,cod_activ_obra,cod_partida) != (5,1,6,51,404)
                          )";
          }

          return $sql;

      }
      else
      {
        return "";
      }
  }


    function reporte_form_balance_ejecucion($var=null) { 

        $this->layout="ajax";
        $this->limpia_menu();
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
        $ano = $this->ano_ejecucion();
        $this->set('ano', $ano);
        $this->Session->write('ano_reporte',$ano);
        $this->Session->write('tipo_reporte', $var);

        if($this->verifica_SS(5)==1){
            $cond=$this->SQLCA_report(1);
        }else{
            $cond=$this->SQLCA_report();
        }
        $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $cond." and ano=".$ano." ORDER BY cod_sector ASC");
        foreach($rs as $l){
            $v[]=$l[0]["cod_sector"];
            $d[]=$l[0]["deno_sector"];
        }
        $lista = array_combine($v, $d);
        $rsp=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones  WHERE ". $cond." and ano=".$ano." ORDER BY cod_partida ASC");
        foreach($rsp as $lp){
            $vp[]=$lp[0]["cod_partida"];
            $dp[]=$lp[0]["deno_partida"];
        }
        $partida = array_combine($vp, $dp);
        $this->concatena($lista, 'sector');
        $this->concatena($partida, 'partida');

        if(isset($var)){
            $this->set('tipo_reporte',$var);
        }else{
            $this->set('tipo_reporte',"reporte_balance_ejecucion");
        }
    }

    //
    function escribir_ano($var){
        $this->layout="ajax";

        if(isset($var) && $var!=null){
            $this->Session->write('ano_reporte',$var);
            $this->Session->write('ano',$var);
            $ano=$var;
        }else{
            $this->Session->write('ano_reporte',$this->ano_ejecucion());
            $ano=$this->ano_ejecucion();
        }
        if($this->verifica_SS(5)==1){
                $cond=$this->SQLCA_report(1);
        }else{
                $cond=$this->SQLCA_report();
        }
        $lista=  $this->v_cfpd05_denominaciones->generateList($cond." and ano=".$var, 'cod_sector ASC', null, '{n}.v_cfpd05_denominaciones.cod_sector', '{n}.v_cfpd05_denominaciones.deno_sector');
        $this->concatena($lista, 'sector');
    }

    function select3($select=null,$var=null) { //select codigos presupuestarios
        $this->layout = "ajax";

        if($select!=null && $var!=null){
            if($this->verifica_SS(5)==1){
                $cond=$this->SQLCA_report(1);
            }else{
                $cond=$this->SQLCA_report();
            }
            switch($select){
                case 'sector':
                    $this->set('SELECT','programa');
                    $this->set('codigo','sector');
                    $this->set('seleccion','');
                    $this->set('n',1);
                    $ano=$this->Session->read('ano_reporte');
                    $this->Session->write('ano',$ano);
                    $cond.=" and ano=".$ano;
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sector,deno_sector FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_sector ASC");
                    foreach($rs as $l){
                        $v[]=$l[0]["cod_sector"];
                        $d[]=$l[0]["deno_sector"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                break;
                case 'programa':
                    $this->set('SELECT','subprograma');
                    $this->set('codigo','programa');
                    $this->set('seleccion','');
                    $this->set('n',2);
                    $ano=$this->Session->read('ano_reporte');
                    $this->Session->write('ano',$ano);
                    $this->Session->write('sec',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$var;
                    //$lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_programa ASC', null, '{n}.v_cfpd05_denominaciones.cod_programa', '{n}.v_cfpd05_denominaciones.deno_programa');
                    //$this->concatena($lista, 'vector');
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_programa,deno_programa FROM v_cfpd05_denominaciones WHERE ". $cond ." ORDER BY cod_programa ASC");
                   foreach($rs as $l){
                        $v[]=$l[0]["cod_programa"];
                        $d[]=$l[0]["deno_programa"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                break;
                case 'subprograma':
                    $this->set('SELECT','partida');
                    $this->set('codigo','subprograma');
                    $this->set('seleccion','');
                    $this->set('n',3);
                    $ano =  $this->Session->read('ano');
                    $sec =  $this->Session->read('sec');
                    $this->Session->write('prog',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sub_prog,deno_sub_prog FROM v_cfpd05_denominaciones WHERE ". $cond ." ORDER BY cod_sub_prog ASC");
                    foreach($rs as $l){
                        $v[]=$l[0]["cod_sub_prog"];
                        $d[]=$l[0]["deno_sub_prog"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                break;
                case 'partida':
                    $this->set('SELECT','generica');
                    $this->set('codigo','partida');
                    $this->set('seleccion','');
                    $this->set('n',4);
                    $ano =  $this->Session->read('ano');
                    $sec =  $this->Session->read('sec');
                    $prog =  $this->Session->read('prog');
                    $this->Session->write('subp',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_partida,deno_partida FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_partida ASC");
                    foreach($rs as $l){
                        $v[]=$l[0]["cod_partida"];
                        $d[]=$l[0]["deno_partida"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                break;
                case 'generica':
                    $this->set('SELECT','especifica');
                    $this->set('codigo','generica');
                    $this->set('seleccion','');
                    $this->set('n',5);
                    if(isset($_SESSION["ano"])){
                        $ano=$this->Session->read('ano');
                    }else if(isset($_SESSION["ano_reporte"])){
                        $ano=$this->Session->read('ano_reporte');
                    }else{
                        $ano=$this->ano_ejecucion();
                    }
                    $sec =  $this->Session->read('sec');
                    $prog =  $this->Session->read('prog');
                    $subp =  $this->Session->read('subp');
                    $this->Session->write('cpar',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_partida=".$var;
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_generica,deno_generica FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_generica ASC");
                    foreach($rs as $l){
                        $v[]=$l[0]["cod_generica"];
                        $d[]=$l[0]["deno_generica"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                break;
                case 'especifica':
                    $this->set('SELECT','subespecifica');
                    $this->set('codigo','especifica');
                    $this->set('seleccion','');
                    $this->set('n',6);
                    if(isset($_SESSION["ano"])){
                        $ano=$this->Session->read('ano');
                    }else if(isset($_SESSION["ano_reporte"])){
                        $ano=$this->Session->read('ano_reporte');
                    }else{
                        $ano=$this->ano_ejecucion();
                    }
                    $sec =  $this->Session->read('sec');
                    $prog =  $this->Session->read('prog');
                    $subp =  $this->Session->read('subp');
                    $cpar =  $this->Session->read('cpar');
                    $this->Session->write('cgen',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_partida=".$cpar." and cod_generica=".$var;
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_especifica,deno_especifica FROM v_cfpd05_denominaciones WHERE ". $cond." ORDER BY cod_especifica ASC");
                    foreach($rs as $l){
                        $v[]=$l[0]["cod_especifica"];
                        $d[]=$l[0]["deno_especifica"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                break;
                case 'subespecifica':
                    $this->set('SELECT','auxiliar');
                    $this->set('codigo','subespecifica');
                    $this->set('seleccion','');
                    $this->set('n',7);
                    if(isset($_SESSION["ano"])){
                        $ano=$this->Session->read('ano');
                    }else if(isset($_SESSION["ano_reporte"])){
                        $ano=$this->Session->read('ano_reporte');
                    }else{
                        $ano=$this->ano_ejecucion();
                    }
                    $sec =  $this->Session->read('sec');
                    $prog =  $this->Session->read('prog');
                    $subp =  $this->Session->read('subp');
                    $cpar =  $this->Session->read('cpar');
                    $cgen =  $this->Session->read('cgen');
                    $this->Session->write('cesp',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$var;
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_sub_espec,deno_sub_espec FROM v_cfpd05_denominaciones WHERE ". $cond ." ORDER BY cod_sub_espec ASC");
                    foreach($rs as $l){
                        $v[]=$l[0]["cod_sub_espec"];
                        $d[]=$l[0]["deno_sub_espec"];
                    }
                    $lista = array_combine($v, $d);
                    $this->concatena($lista, 'vector');
                break;
                case 'auxiliar':
                    $this->set('SELECT','escribir_aux');
                    $this->set('codigo','auxiliar');
                    $this->set('seleccion','');
                    $this->set('n',8);
                    $ano =  $this->Session->read('ano');
                    $sec =  $this->Session->read('sec');
                    $prog =  $this->Session->read('prog');
                    $subp =  $this->Session->read('subp');
                    $cpar =  $this->Session->read('cpar');
                    $cgen =  $this->Session->read('cgen');
                    $cesp =  $this->Session->read('cesp');
                    $this->Session->write('csesp',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$var;
                    $rs=$this->v_cfpd05_denominaciones->execute("SELECT DISTINCT cod_auxiliar,deno_auxiliar FROM v_cfpd05_denominaciones WHERE ". $cond ." ORDER BY cod_auxiliar ASC");
                    foreach($rs as $l){
                        $v[]=$l[0]["cod_auxiliar"];
                        $d[]=$l[0]["deno_auxiliar"];
                    }
                    $lista = array_combine($v, $d);
                    if($lista!=null){
                        $this->concatena_auxiliar($lista, 'vector');
                    }else{
                        $this->set('vector',array('0'=>'00'));
                    }
                break;
                case 'auxiliar2':
                    $this->set('SELECT','auxiliar');
                    $this->set('codigo','auxiliar');
                    $this->set('seleccion','');
                    $this->set('n',9);
                    $ano =  $this->Session->read('ano');
                    $sec =  $this->Session->read('sec');
                    $prog =  $this->Session->read('prog');
                    $subp =  $this->Session->read('subp');
                    $cpar =  $this->Session->read('cpar');
                    $cgen =  $this->Session->read('cgen');
                    $cesp =  $this->Session->read('cesp');
                    $f=$this->Session->read('CodigosDireccion');
                    $p=$this->Session->read('partidas');
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subp." and cod_partida=".$cpar." and cod_generica=".$cgen." and cod_especifica=".$cesp." and cod_sub_espec=".$p[0]["cscd01_catalogo"]["cod_sub_espec"];
                    $lista=  $this->v_cfpd05_denominaciones->generateList($cond, 'cod_auxiliar ASC', null, '{n}.v_cfpd05_denominaciones.cod_auxiliar', '{n}.v_cfpd05_denominaciones.deno_auxiliar');
                    if($lista!=null){
                        $this->concatena_auxiliar($lista, 'vector');
                    }else{
                        $this->set('vector',array('0'=>'00'));
                        $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], 0);
                        echo "<script>" .
                                "document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."'; " .
                                "</script>";
                    }
                break;
                case 'escribir_aux':
                    $this->Session->write('auxiliar',$var);
                    $disponibilidad = $this->disponibilidad($_SESSION["ano"], $_SESSION["sec"], $_SESSION["prog"], $_SESSION["subp"], $_SESSION["cpar"], $_SESSION["cgen"], $_SESSION["cesp"], $_SESSION["csesp"], $_SESSION["auxiliar"]);

                         echo "<script>" .
                                "document.getElementById('td_disponibilidad').innerHTML='".$this->Formato2($disponibilidad)."';" .
                                "</script>";
                         $this->set("ocultar",true);
                break;
            }//fin wsitch
        }else{
            $this->set('SELECT','');
            $this->set('codigo','');
            $this->set('seleccion','');
            $this->set('n',12);
            $this->set('no','no');
            $this->set('vector',array('0'=>'00'));
        }
    }//fin select codigos presupuestarios

    function mostrar3($select=null,$var=null) { //mostrar3 codigos presupuestarios
        $this->layout = "ajax";
        if( $var!=null){
            if($this->verifica_SS(5)==1){
                $cond=$this->SQLCA_report(1);
            }else{
                $cond=$this->SQLCA_report();
            }
            switch($select){
                case 'sector':
                    $ano=$this->Session->read('ano_reporte');
                    $this->Session->write('ano',$ano);
                    $this->Session->write('dsec',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$var;
                    $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_sector'));
                    $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_sector'];
                    if($xdeno==''){
                        echo "N/A";
                    }else{
                        echo $xdeno." &nbsp;";
                    }
                break;
                case 'programa':
                    $ano =  $this->Session->read('ano');
                    $sec =  $this->Session->read('dsec');
                    $this->Session->write('dprog',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$var;
                    $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_programa'));
                    $xdeno=$a[0]['v_cfpd05_denominaciones']['deno_programa'];
                    if($xdeno==''){
                        echo "N/A";
                    }else{
                        echo $xdeno." &nbsp;";
                    }
                break;
                case 'subprograma':
                    $ano =  $this->Session->read('ano');
                    $sec =  $this->Session->read('dsec');
                    $prog =  $this->Session->read('dprog');
                    $this->Session->write('dsubprog',$var);
                    $cond .=" and ano=".$ano." and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$var;
                    $a=  $this->v_cfpd05_denominaciones->findAll($cond,array('deno_sub_prog'));
                    $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_sub_prog'];
                    if($xdeno==''){
                        echo "N/A";
                    }else{
                        echo $xdeno." &nbsp;";
                    }
                break;
                case 'partida':
                  if(isset($_SESSION["ano"])){
                        $ano=$this->Session->read('ano');
                    }else if(isset($_SESSION["ano_reporte"])){
                        $ano=$this->Session->read('ano_reporte');
                    }else{
                        $ano=$this->ano_ejecucion();
                    }
                  $this->Session->write('dpar',$var);
                  $cond2 ="and ano=".$ano." and cod_partida=".$var;
                  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_partida'));
                  $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_partida'];
                  if($xdeno==''){
                    echo "N/A";
                  }else{
                    echo $xdeno." &nbsp;";
                  }
                break;
                case 'generica':
                  if(isset($_SESSION["ano"])){
                        $ano=$this->Session->read('ano');
                    }else if(isset($_SESSION["ano_reporte"])){
                        $ano=$this->Session->read('ano_reporte');
                    }else{
                        $ano=$this->ano_ejecucion();
                    }
                  $dpar=  $this->Session->read('dpar');
                  $this->Session->write('dgen',$var);
                  $cond2 =" and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$var;
                  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_generica'));
                  $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_generica'];
                  if($xdeno==''){
                    echo "N/A";
                  }else{
                    echo $xdeno." &nbsp;";
                  }
                break;
                case 'especifica':
                   if(isset($_SESSION["ano"])){
                        $ano=$this->Session->read('ano');
                    }else if(isset($_SESSION["ano_reporte"])){
                        $ano=$this->Session->read('ano_reporte');
                    }else{
                        $ano=$this->ano_ejecucion();
                    }
                  $dpar=  $this->Session->read('dpar');
                  $dgen =  $this->Session->read('dgen');
                  $this->Session->write('desp',$var);
                  $cond2 =" and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$var;
                  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_especifica'));
                  $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_especifica'];
                  if($xdeno==''){
                    echo "N/A";
                  }else{
                    echo $xdeno." &nbsp;";
                  }
                break;
                case 'subespecifica':
                  if(isset($_SESSION["ano"])){
                        $ano=$this->Session->read('ano');
                    }else if(isset($_SESSION["ano_reporte"])){
                        $ano=$this->Session->read('ano_reporte');
                    }else{
                        $ano=$this->ano_ejecucion();
                    }
                  $dpar=  $this->Session->read('dpar');
                  $dgen =  $this->Session->read('dgen');
                  $desp =  $this->Session->read('desp');
                  $this->Session->write('dsubesp',$var);
                  $cond2 =" and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$var;
                  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_sub_espec'));
                  $xdeno= $a[0]['v_cfpd05_denominaciones']['deno_sub_espec'];
                  if($xdeno==''){
                    echo "N/A";
                  }else{
                    echo $xdeno." &nbsp;";
                  }
                break;
                case 'auxiliar':
                  $sec =  $this->Session->read('dsec');
                  $prog =  $this->Session->read('dprog');
                  $subprog =  $this->Session->read('dsubprog');
                  $ano =  $this->Session->read('ano');
                  $dpar=  $this->Session->read('dpar');
                  $dpar = $dpar; //< 10 ? CE."0".$dpar : CE.$dpar;
                  $dgen =  $this->Session->read('dgen');
                  $desp =  $this->Session->read('desp');
                  $dsubesp =  $this->Session->read('dsubesp');
                  $con3=" and cod_sector=".$sec." and cod_programa=".$prog." and cod_sub_prog=".$subprog;
                  $cond2 =$con3." and ano=".$ano." and cod_partida=".$dpar." and cod_generica=".$dgen." and cod_especifica=".$desp." and cod_sub_espec=".$dsubesp." and cod_auxiliar=".$var;

                  $a=  $this->v_cfpd05_denominaciones->findAll($cond.$cond2,array('deno_auxiliar'));
                  //print_r($a);
                  $xdeno=$a[0]['v_cfpd05_denominaciones']['deno_auxiliar'];
                  if($xdeno==''){
                    echo "N/A";
                  }else{
                    echo $xdeno." &nbsp;";
                  }
                break;
            }//fin wsitch
        }else{
            echo " &nbsp;";
        }
    }//fin mostrar3 codigos presupuestarios



    function reporte_balance_ejecucion() {
        $this->layout="pdf";
        //$this->layout="ajax";print_r($this->data);
        $this->set('entidad_federal', $this->Session->read('entidad_federal'));
             if(isset($this->data["reporte"]["ano"]) && !empty($this->data["reporte"]["ano"])){
                  $Ano=$this->data["reporte"]["ano"];

             }else{
                $Ano=$this->ano_ejecucion();

             }

            $this->set('ANO',$Ano);
            if(isset($this->data['cfpp05']['consolidacion'])){
                 $con=$this->SQLCA_consolidado($this->data['cfpp05']['consolidacion']);
                 $modelo=$this->data['cfpp05']['consolidacion']==1?"v_balance_ejecucion_inst":"v_balance_ejecucion";
                  $this->set("modelo",$modelo);
            }else{
                $con=$this->SQLCA_consolidado();
                $modelo="v_balance_ejecucion";
                $this->set("modelo",$modelo);
            }

            $titulo_a = $this->Session->read('dependencia');
            $this->set('titulo_a',$titulo_a);

            if(isset($this->data["reporte"]["cod_sector"]) && $this->data["reporte"]["cod_sector"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_sector=" a.cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
                }else{
                    $cod_sector=" cod_sector=".$this->data["reporte"]["cod_sector"]." and ";
                }
            }
            else{
                $cod_sector=" 1=1 and ";
            }

            if(isset($this->data["reporte"]["cod_programa"]) && $this->data["reporte"]["cod_programa"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_programa=" a.cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
                }else{
                    $cod_programa=" cod_programa=".$this->data["reporte"]["cod_programa"]." and ";
                }
            }
            else{
                $cod_programa=" 1=1 and ";
            }

            if(isset($this->data["reporte"]["cod_subprograma"]) && $this->data["reporte"]["cod_subprograma"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_sub_prog=" a.cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
                }else{
                    $cod_sub_prog=" cod_sub_prog=".$this->data["reporte"]["cod_subprograma"]." and ";
                }
            }
            else{
                $cod_sub_prog=" 1=1 and ";
            }

            $cod_proyecto=" 1=1 and ";

            $cod_activ_obra=" 1=1 ";

            if(isset($this->data["reporte"]["cod_partida"]) && $this->data["reporte"]["cod_partida"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_partida=" a.cod_partida=".$this->data["reporte"]["cod_partida"]." ";
                }else{
                    $cod_partida=" cod_partida=".$this->data["reporte"]["cod_partida"]." ";
                }
            }
            else{
                $cod_partida=" 1=1 ";
            }

            if(isset($this->data["reporte"]["cod_generica"]) && $this->data["reporte"]["cod_generica"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_generica=" a.cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
                }else{
                    $cod_generica=" cod_generica=".$this->data["reporte"]["cod_generica"]." and ";
                }
            }
            else{
                $cod_generica=" 1=1 and ";
            }

            if(isset($this->data["reporte"]["cod_especifica"]) && $this->data["reporte"]["cod_especifica"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_especifica=" a.cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
                }else{
                    $cod_especifica=" cod_especifica=".$this->data["reporte"]["cod_especifica"]." and ";
                }
            }
            else{
                $cod_especifica=" 1=1 and ";
            }

            if(isset($this->data["reporte"]["cod_subespecifica"]) && $this->data["reporte"]["cod_subespecifica"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_sub_espec=" a.cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
                }else{
                    $cod_sub_espec=" cod_sub_espec=".$this->data["reporte"]["cod_subespecifica"]." and ";
                }
            }
            else{
                $cod_sub_espec=" 1=1 and ";
            }

            if(isset($this->data["reporte"]["cod_auxiliar"]) && $this->data["reporte"]["cod_auxiliar"]!=""){
                if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
                    $cod_auxiliar=" a.cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
                }else{
                    $cod_auxiliar=" cod_auxiliar=".$this->data["reporte"]["cod_auxiliar"]." ";
                }
            }
            else{
                $cod_auxiliar=" 1=1 ";
            }

            $modo= (int) $this->data["reporte"]["modo"];
           // echo "MODO: ".$modo;
            switch($modo){
                case 1:
                     //completo todo
                     $condicion=" 1=1";
                break;
                case 2:
                      //por categoria
                      $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra;
                break;
                case 3:
                    //por categoria y partida
                    $condicion=" ".$cod_sector.$cod_programa.$cod_sub_prog.$cod_proyecto.$cod_activ_obra." and ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
                break;
                case 4:
                     $condicion=" ".$cod_partida;
                break;
                case 5:
                     $condicion=" ".$cod_partida." and ".$cod_generica.$cod_especifica.$cod_sub_espec.$cod_auxiliar;
                break;
                default: $condicion=" 1=1";
            }//fin switch
            //echo $condicion;

            if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
              $condicion.=$this->exclusion_presupuesto(true);
            }
            else
            {
              $condicion.=$this->exclusion_presupuesto();
            }
            
            if(isset($this->data['cfpp05']['consolidacion']) && $this->data['cfpp05']['consolidacion'] == 3){
               
                $vector = $this->$modelo->execute("SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, deno_sector, cod_programa, deno_programa, cod_sub_prog, deno_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec, deno_sub_espec, cod_auxiliar, eno_auxiliar, asignacion_anual, aumento, disminucion, total_asignacion, pre_compromiso, compromiso_anual, causado_anual, pagado_anual, deuda, disponibilidad, cod_tipo_gasto,tipo_presupuesto, aumento_traslado_anual, credito_adicional_anual, nacionales from v_balance_ejecucion
                                                    where
                                                    cod_presi=".$this->verifica_SS(1)." and 
                                                    cod_entidad=".$this->verifica_SS(2)." and 
                                                    cod_tipo_inst=".$this->verifica_SS(3)." and 
                                                    cod_dep =".$_SESSION['cod_dep_reporte_consolidado']." and
                                                    ano=".$Ano.$this->exclusion_presupuesto(true)."
                                                    order by cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC;");


                $total_spsppa = $this->$modelo->execute("SELECT a.cod_sector,a.cod_programa,a.cod_sub_prog
                                FROM ".$modelo." AS a 
                                INNER JOIN cfpd02_activ_obra_ordinario AS b 
                                ON b.cod_dep = ".$_SESSION['cod_dep_reporte_consolidado']."
                                WHERE a.ano=".$Ano." and ".$condicion."  
                                GROUP BY a.cod_sector,a.cod_programa,a.cod_sub_prog  
                                ORDER BY a.cod_sector,a.cod_programa,a.cod_sub_prog ASC");
                $this->set('tipo_reporte',2);
                
            }else{
                if($modelo=="v_balance_ejecucion"){
                    $sql="SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_sector, deno_sector, cod_programa,
                    deno_programa, cod_sub_prog, deno_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec, 
                    deno_sub_espec, cod_auxiliar, deno_auxiliar, SUM(asignacion_anual) as asignacion_anual, SUM(aumento) as 
                    aumento, SUM(disminucion) as disminucion, SUM(total_asignacion) as total_asignacion, SUM(pre_compromiso) as 
                    pre_compromiso, SUM(compromiso_anual) as compromiso_anual, SUM(causado_anual) as causado_anual, SUM(pagado_anual) as pagado_anual,
                    SUM(deuda) as deuda, SUM(disponibilidad) as disponibilidad, SUM(cod_tipo_gasto) as cod_tipo_gasto,
                    SUM(tipo_presupuesto) as tipo_presupuesto, SUM(aumento_traslado_anual) as aumento_traslado_anual, SUM(credito_adicional_anual) as credito_adicional_anual,
                    SUM(nacionales) as nacionales from ". $modelo ."
                                                    where ".$con." and
                                                    ano=".$Ano." and 
                                                    ".$condicion."
                                                    group by cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, ano, cod_sector, deno_sector, cod_programa, deno_programa, cod_sub_prog, deno_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec, deno_sub_espec, cod_auxiliar, deno_auxiliar
                                                    order by cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC;";
                }else{
                    $sql="SELECT cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_sector, deno_sector, cod_programa,
                    deno_programa, cod_sub_prog, deno_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec, 
                    deno_sub_espec, cod_auxiliar, deno_auxiliar, SUM(asignacion_anual) as asignacion_anual, SUM(aumento) as 
                    aumento, SUM(disminucion) as disminucion, SUM(total_asignacion) as total_asignacion, SUM(pre_compromiso) as 
                    pre_compromiso, SUM(compromiso_anual) as compromiso_anual, SUM(causado_anual) as causado_anual, SUM(pagado_anual) as pagado_anual,
                    SUM(deuda) as deuda, SUM(disponibilidad) as disponibilidad, SUM(cod_tipo_gasto) as cod_tipo_gasto,
                    SUM(tipo_presupuesto) as tipo_presupuesto, SUM(aumento_traslado_anual) as aumento_traslado_anual, SUM(credito_adicional_anual) as credito_adicional_anual,
                    SUM(nacionales) as nacionales from ". $modelo ."
                                                    where ".$con." and
                                                    ano=".$Ano." and 
                                                    ".$condicion."
                                                    group by cod_presi, cod_entidad, cod_tipo_inst, cod_inst, ano, cod_sector, deno_sector, cod_programa, deno_programa, cod_sub_prog, deno_sub_prog, cod_partida, cod_generica, cod_especifica, cod_sub_espec, deno_sub_espec, cod_auxiliar, deno_auxiliar
                                                    order by cod_partida,cod_generica,cod_especifica,cod_sub_espec,cod_auxiliar ASC;";
                }
                $vector = $this->$modelo->execute($sql);

                $total_spsppa = $this->$modelo->execute("SELECT cod_sector,cod_programa,cod_sub_prog 
                    FROM ".$modelo." WHERE ".$con." and ano=".$Ano." and ".$condicion."  GROUP BY cod_sector,cod_programa,cod_sub_prog ORDER BY cod_sector,cod_programa,cod_sub_prog ASC");
                $this->set('tipo_reporte',1);

            }
            
            $this->set('distintos_sectores',$total_spsppa);
            $this->set('cfpd05',$vector);

    }//fin reporte_balance_ejecucion


}//fin class
?>
