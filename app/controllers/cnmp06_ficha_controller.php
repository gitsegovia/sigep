<?php
/*
 * Creado el  30/10/2007 a las 12:03:17 PM
 *
 * Herramienta: EasyEclipse.
 *
 * Proyecto: SIGEP
 *
 */
class Cnmp06FichaController extends AppController
{
    public $name = 'cnmp06_ficha';
    public $uses = array('cnmd05','ccfd04_cierre_mes','v_cnmd06_fichas','cstd02_cuentas_bancarias','cstd01_sucursales_bancarias', 'v_cnmd06_fichas_datos_personales',
                      'cstd01_entidades_bancarias','v_cnmd06_nombres','cnmd06_datos_personales','cnmd06_fichas', 'cugd10_imagenes', 'cnmd06_fichas_historial_condicion',
                      'Cnmd01','Cnmd02_empleados_puestos','v_cnmd06','Cnmd02_varios_puestos','cnmd02_varios_puestos', "cnmd06_fichas" ,"v_cnmd06_fichas_2"
                      , 'datos_personales_super_busqueda', "cugd02_institucion", "cugd02_dependencia", "Cnmd01", "cnmd08_historia_trabajador");





    public $helpers = array('Html','Ajax','Javascript', 'Sisap');

    public function checkSession()
    {
        if (!$this->Session->check('Usuario')) {
            $this->redirect('/salir/');
            exit();
        } else {
            //$this->set('userTable', $this->requestAction('/cnmp03partidas/', array('return')));
            //echo "H".$this->requestAction('/usuarios/actualizar_user',array('return'));
            $this->requestAction('/usuarios/actualizar_user');
        }
    }//fin checksession

    public function beforeFilter()
    {
        $this->checkSession();

    }

    public function verifica_SS($i)
    {
        /**
    	 * Funcion que permite leer las varibles de session. la cual permite capturar los codigos del usuario
    	 * para ser insertados en todas las tablas.
    	 * */
        switch ($i) {
            case 1:return $this->Session->read('SScodpresi');
            break;
            case 2:return $this->Session->read('SScodentidad');
            break;
            case 3:return $this->Session->read('SScodtipoinst');
            break;
            case 4:return $this->Session->read('SScodinst');
            break;
            case 5:return $this->Session->read('SScoddep');
            break;
            case 6:return $this->Session->read('entidad_federal');
            break;
            default:
                return "NULO";


        }//fin switch
    }//fin verifica_SS

    public function SQLCA($ano=null) //sql para busqueda de codigos de arranque con y sin año
    {$sql_re = "cod_presi=".$this->verifica_SS(1)."  and    ";
        $sql_re .= "cod_entidad=".$this->verifica_SS(2)."  and  ";
        $sql_re .= "cod_tipo_inst=".$this->verifica_SS(3)."  and ";
        $sql_re .= "cod_inst=".$this->verifica_SS(4)."  and  ";
        if($ano!=null) {
            $sql_re .= "cod_dep=".$this->verifica_SS(5)."  and  ";
            $sql_re .= "ano=".$ano."  ";
        } else {
            $sql_re .= "cod_dep=".$this->verifica_SS(5)." ";
        }
        return $sql_re;
    }//fin funcion SQLCA



    public function zero($x=null)
    {
        if($x != null) {
            if($x<10) {
                $x="0".$x;
            } elseif($x>=10 && $x<=99) {
                $x=$x;
            }
        }
        return $x;

    }//fin zero




    public function concatena_tres_digitos($vector1=null, $nomVar=null, $extra=null)
    {
        $cod = array();
        if($vector1 != null) {
            foreach($vector1 as $x => $y) {
                if($extra!=null) {
                    if($x<99 && $x>9) {
                        $cod[$x] = $extra.'0'.$x.' - '.$y;
                    } elseif($x<=9) {
                        $cod[$x] = $extra.'00'.$x.' - '.$y;
                    } else {
                        $cod[$x] = $extra.''.$x.' - '.$y;
                    }

                } else {
                    if($x<99 && $x>9) {
                        $cod[$x] = '0'.$x.' - '.$y;
                    } elseif($x<=9) {
                        $cod[$x] = '00'.$x.' - '.$y;
                    } else {
                        $cod[$x] = ''.$x.' - '.$y;
                    }
                }
            }
            //print_r($cod);
        }

        $this->set($nomVar, $cod);
    }//fin function









    public function concatena_cuatro_digitos($vector1=null, $nomVar=null)
    {
        $cod = array();
        if($vector1 != null) {
            foreach($vector1 as $x => $y) {

                if($x<999 && $x>99) {
                    $cod[$x] = '0'.$x.' - '.$y;
                } elseif($x<99 && $x>9) {
                    $cod[$x] = '00'.$x.' - '.$y;
                } elseif($x<=9) {
                    $cod[$x] = '000'.$x.' - '.$y;
                } else {
                    $cod[$x] = ''.$x.' - '.$y;
                }

            }//fin for
        }//fin if

        $this->set($nomVar, $cod);
    }//fin function












    public function index($var=null)
    {
        $this->layout ="ajax";
        $this->data=null;
        //$Lista = $this->v_cnmd06->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmd06.cod_tipo_nomina', '{n}.v_cnmd06.tipo_nomina');
        //$this->concatena($Lista, 'cod_tipo_nomina');



        $lista = $this->Cnmd01->generateList($conditions = $this->condicion()." and status_nomina IN (0,1)", $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
        if($lista !=null) {
            $this->concatena($lista, 'cod_tipo_nomina');
        } else {
            $this->set('cod_tipo_nomina', '');
        }
        $Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
        $this->set('cod_entidad_bancaria', $Listaentidad);
        $persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
        $this->concatena($persona, 'persona');
        $this->set("forma", array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
        $condicion = array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo");
        $this->concatena($condicion, "condicion");
        $this->set("motivo", array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));


        $this->Session->delete('tipo_nomina_cod_cargo');

        if($var!=null) {
            $ss=$this->cnmd06_fichas->findAll($this->SQLCA()." and cod_tipo_nomina=".$var, 'cod_ficha', 'cod_ficha DESC', 1);
            if($ss==null) {
                $new_numero=1;
            } else {
                $new_numero=$ss[0]["cnmd06_fichas"]["cod_ficha"]+1;
            }//fin else

            $b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$var, array('cod_tipo_nomina','denominacion'));

            $this->set("disabled_radio", "");
            $this->set("cod_ficha", "");
            $this->set("radio", 1);
            $this->set("codigo_tipo_nomina", mascara2($var));
            $this->set("denominacion_nomina", $b[0]['Cnmd01']['denominacion']);

        } else {

            $this->Session->delete('tipo_nomina');

            $this->set("disabled_radio", "disabled");
            $this->set("cod_ficha", "");
            $this->set("radio", "");
            $this->set("codigo_tipo_nomina", "");
            $this->set("denominacion_nomina", "");

        }//fin else


    }//fin if





    public function puesto($var=null)
    {
        $this->layout = "ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $con  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
        $con .= " and (condicion_actividad=1) and cod_tipo_nomina=".$var;

        if($var!=null) {
            $var2 = $this->v_cnmd06->generateList($con, null, null, '{n}.v_cnmd06.cod_cargo', '{n}.v_cnmd06.demonimacion_puesto');
            $this->set('var', $var);
            if($var2!=null) {
                $this->concatena($var2, 'cod_puesto');
            } else {
                $this->set('cod_puesto', array());
            }



        } else {
            $this->set('var2', null);
            $this->set('cod_puesto', array());

        }

    }

    public function mostrarPuesto($var=null, $var2=null)
    {
        $this->layout="ajax";
        $var2 = strtoupper($var2);
        $var_min = strtolower($var2);
        $var_wrap = ucfirst($var_min);
        $this->set('var2', $var2);
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $con = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;

        if($var != null) {
            $this->set('var', $var);

            if($this->v_cnmd06->findCount("demonimacion_puesto LIKE '%$var2%' or demonimacion_puesto LIKE '%$var_min%' or demonimacion_puesto LIKE '%$var_wrap%'") != 0) {
                $con .=" and (demonimacion_puesto LIKE '%$var2%' or demonimacion_puesto LIKE '%$var_min%' or demonimacion_puesto LIKE '%$var_wrap%')";
                $puesto = $this->v_cnmd06->generateList($con, null, null, '{n}.v_cnmd06.cod_cargo', '{n}.v_cnmd06.demonimacion_puesto');
                if($puesto!=null) {
                    $this->concatena($puesto, 'puesto');
                } else {
                    $this->set('puesto', array());
                }

            } else {
                $this->set('puesto', null);
                $this->set('var2', null);

            }


        }
    }





    public function eliminar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null)
    {

        $this->layout="ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $condi = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
        $sql   =  $condi." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."' and cedula_identidad='".$var5."'   ";
        $sql2  =  $condi." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."' ";

        $verificar=$this->cnmd08_historia_trabajador->findCount($sql2);



        if($verificar==0) {

            $cont = $this->cnmd06_fichas->findCount("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cedula_identidad=".$var5." and condicion_actividad!=6");
            if($cont<=1) {
                $sql2 = "update cnmd06_datos_personales set  condicion_actual='2' where cedula_identidad=".$var5;
                $sw2 = $this->cnmd06_datos_personales->execute($sql2);
            }//fin if

            $vvv   =  $this->cnmd06_fichas->execute("delete from cnmd06_fichas where ".$sql);
        } else {
            $vvv   =  0;
        }//fin else


        if($vvv>1) {

            $cond = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_tipo_nomina='".$var1."'  and cod_cargo=".$var2." and cod_puesto=".$var3."   ";
            $sql  = "update cnmd05 set condicion_actividad='1', cod_ficha='0' where ".$cond;
            $sw1  = $this->cnmd05->execute($sql);

            $status_actual = $this->Cnmd01->field("status_nomina", $this->SQLCA()." and cod_tipo_nomina=".$var1);
            if ($status_actual==1) {
                $sqlupd_estatus = "update cnmd01 set status_nomina='0' where ".$condi." and cod_tipo_nomina='".$var1."';   ";
                $swupd_est = $this->Cnmd01->execute($sqlupd_estatus);
            }

            $this->set('Message_existe', 'el registro fue eliminado');
            $pagina = $var6-1;

        } else {

            $this->set('errorMessage', 'el registro no fue eliminado');
            $pagina = $var6;

        }//fin function


        $this->consulta($pagina);
        $this->render("consulta");


    }//fin function




    public function todo($cod_tipo_nomina=null, $cod_cargo=null)
    {
        $this->layout="ajax";
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');
        $condi = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_tipo_nomina='".$cod_tipo_nomina."'and cod_cargo=".$cod_cargo;

        $todov=$this->v_cnmd06->findAll($condi);
        $this->set('Message_existe', 'Por Favor Ingrese El Numero de Ficha.');
        $this->set('todov', $todov);

    }//fin function




    public function radio($var2)
    {
        $this->layout ="ajax";
        $this->set('mensaje', 'POR FAVOR INGRESE EL CÓDIGO');
        $this->set('datos', array());
        $this->set('tipo', array());
        $tipo_nomina= $this->Session->read('tipo_nomina');



        echo "<script>";

        if($var2==1) {
            $ss=$this->cnmd06_fichas->findAll($this->SQLCA()." and cod_tipo_nomina=".$tipo_nomina, 'cod_ficha', 'cod_ficha DESC', 1);
            if($ss==null) {
                $new_numero=1;
            } else {
                $new_numero=$ss[0]["cnmd06_fichas"]["cod_ficha"]+1;
            }
            $this->set('numero', "");

            echo'document.getElementById("numero_input").readOnly=true; ';

        }//fin if



        if($var2==2) {
            $this->set('numero', "");
            echo'document.getElementById("numero_input").readOnly=false; ';
        }//fin if



        echo "</script>";

    }//fin function




    public function select($var3)
    {
        $this->layout ="ajax";
        $var = strtoupper($var3);
        $var_min = strtolower($var3);
        $var_wrap = ucfirst($var_min);
        $this->set('var3', $var3);

        if($this->v_cnmd06_nombres->findCount("datos_completos LIKE '%$var3%' or datos_completos LIKE '%$var_min%' or datos_completos LIKE '%$var_wrap%'") != 0) {
            $c="datos_completos LIKE '%$var%' or datos_completos LIKE '%$var_min%' or datos_completos LIKE '%$var_wrap%'";
            $persona = $this->v_cnmd06_nombres->generateList($c, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
            $this->concatena($persona, 'persona');
        } else {
            $this->set('persona', array());
            $this->set('var3', "");

        }
    }//fin function


    public function personales($var)
    {
        $this->layout="ajax";
        $condici= "cedula_identidad=".$var;
        $datos=$this->cnmd06_datos_personales->findAll($condici);
        $this->set('datos', $datos);

    }//fin function




    public function select4($select=null, $var=null) //select codigos presupuestarios
    {$this->layout = "ajax";
        if($var!=null) {
            switch($select) {
                case 'entidad':
                    $this->set('SELECT', 'sucursal');
                    $this->set('codigo', 'entidad');
                    $this->set('seleccion', '');
                    $this->set('n', 1);
                    $listaprofesion=$this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
                    $this->set('vector', $listaprofesion);
                    break;
                case 'sucursal':
                    $this->set('SELECT', 'sucursal');
                    $this->set('codigo', 'sucursal');
                    $this->set('seleccion', '');
                    $this->set('n', 2);
                    $this->set('no', 'no');
                    $this->Session->write('ent', $var);
                    $cond=" cod_entidad_bancaria=".$var;
                    $listaespecialidades=$this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
                    $this->set('vector', $listaespecialidades);
                    break;

            }
        } else {
            $this->set('SELECT', '');
            $this->set('codigo', '');
            $this->set('seleccion', '');
            $this->set('n', 16);
            $this->set('no', 'no');
            $this->set('vector', '');
        }
    }//fin select codigos presupuestarios




    public function guardar()
    {
        $this->layout = "ajax";
        if(!empty($this->data)) {
            $aa[1]=$this->verifica_SS(1);
            $aa[2]=$this->verifica_SS(2);
            $aa[3]=$this->verifica_SS(3);
            $aa[4]=$this->verifica_SS(4);
            $aa[5]=$this->verifica_SS(5);
            $cod_tipo_nomina   =  $this->data['cnmp06_ficha']['cod_tipo_nomina'];
            $cod_puesto        =  $this->data['cnmp06_ficha']['cod_puesto'];
            $cod_cargo         =  $this->data['cnmp06_ficha']['cod_cargo'];

            $cedula_identidad  =  $this->data['cnmp06_ficha']['cedula_identidad'];
            $paso_input        =  $this->data['cnmp06_ficha']['paso_input'];
            $fi=$this->data['cnmp06_ficha']['fecha_ingreso'];
            $fi=$fi=="" ? "01/01/1900" : $fi;
            $fecha_ingreso=$fi;
            $forma_pago                      =  $this->data['cnmp06_ficha']['forma_pago'];
            $condicion_actividad             =  $this->data['cnmp06_ficha']['condicion'];
            $funciones_realizar              =  $this->data['cnmp06_ficha']['funciones_realizar'];
            $responsabilidad_administrativa  =  $this->data['cnmp06_ficha']['responsabilidad'];
            $horas_laborar                   =  $this->Formato1($this->data['cnmp06_ficha']['horas_laborar']);
            $porcentaje_jub_pension          =  $this->Formato1($this->data['cnmp06_ficha']['porcentaje']);

            $tipo_contrato      =  $this->data['cnmp06_ficha']['tipo_contrato'];



            $situacion          =  $this->data['cnmp06_ficha']['situacion'];
            $nivel              =  $this->data['cnmp06_ficha']['nivel'];
            $categoria          =  $this->data['cnmp06_ficha']['categoria'];

            $fecha_registro                 =     date("Y-m-d");
            $username_registro                      =     $_SESSION['nom_usuario'];

            $fecha_movimiento                       =     "01/01/1990";
            $username_movimiento                    =     0;

            $ultimo_recibo                          =     0;




            if(empty($this->data['cnmp06_ficha']['tipo_contrato'])) {
                $tipo_contrato=1;
            }
            if(empty($this->data['cnmp06_ficha']['situacion'])) {
                $situacion="";
            }
            if(empty($this->data['cnmp06_ficha']['nivel'])) {
                $nivel="";
            }
            if(empty($this->data['cnmp06_ficha']['categoria'])) {
                $categoria="";
            }


            if(empty($this->data['cnmp06_ficha']['existe'])) {
                $ss=$this->cnmd06_fichas->findAll($this->SQLCA()." and cod_tipo_nomina=".$cod_tipo_nomina, 'cod_ficha', 'cod_ficha DESC', 1);
                if($ss==null) {
                    $cod_ficha=1;
                } else {
                    $cod_ficha=$ss[0]["cnmd06_fichas"]["cod_ficha"]+1;
                }
            } else {
                $cod_ficha         =  $this->data['cnmp06_ficha']['existe'];
            }//fin else


            if(empty($this->data['cnmp06_ficha']['cod_entidad_bancaria'])) {
                $cod_entidad_bancaria="0";
            } else {
                $cod_entidad_bancaria            =  $this->data['cnmp06_ficha']['cod_entidad_bancaria'];
            }
            if(empty($this->data['cnmp06_ficha']['cod_sucursal'])) {
                $cod_sucursal_bancaria="0";
            } else {
                $cod_sucursal_bancaria           =  $this->data['cnmp06_ficha']['cod_sucursal'];
            }
            if(empty($this->data['cnmp06_ficha']['cod_cuenta'])) {
                $numero_cuenta="0";
            } else {
                $numero_cuenta                   =  mascara($cod_entidad_bancaria, 4).mascara($cod_sucursal_bancaria, 4).$this->data['cnmp06_ficha']['cod_cuenta'];
            }


            $ff=$this->data['cnmp06_ficha']['fecha_fin_contrato'];
            $ff=$ff=="" ? "01/01/1900" : $ff;
            $fecha_terminacion_contrato=$ff;
            $fr=$this->data['cnmp06_ficha']['fecha_retiro'];
            $fr=$fr=="" ? "01/01/1900" : $fr;
            $fecha_retiro=$fr;

            if(!empty($this->data['cnmp06_ficha']['motivo'])) {
                $motivo_retiro=$this->data['cnmp06_ficha']['motivo'];
            } else {
                $motivo_retiro="0";
            }//fin else

            $fecha_condicion =  date("Y-m-d");
            ;


            $SQL_INSERT =" BEGIN; INSERT INTO cnmd06_fichas (cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,cod_tipo_nomina,cod_cargo,cod_ficha,cedula_identidad,
						  fecha_ingreso,forma_pago,cod_entidad_bancaria,cod_sucursal,cuenta_bancaria,condicion_actividad,funciones_realizar,
						  responsabilidad_administrativa,horas_laborar,porcentaje_jub_pension,fecha_terminacion_contrato,fecha_retiro,motivo_retiro, paso, tipo_contrato, situacion, nivel, categoria,
						  username_registro, fecha_registro, username_movimiento, fecha_movimiento, ultimo_recibo,fecha_condicion)";

            $SQL_INSERT .=" VALUES (".$aa[1].",".$aa[2].",".$aa[3].",".$aa[4].",".$aa[5].",$cod_tipo_nomina,$cod_cargo,$cod_ficha,$cedula_identidad,
						  '".$fecha_ingreso."', $forma_pago, $cod_entidad_bancaria, $cod_sucursal_bancaria, '".$numero_cuenta."', $condicion_actividad, '".$funciones_realizar."',
						  '".$responsabilidad_administrativa."', $horas_laborar, $porcentaje_jub_pension, '".$fecha_terminacion_contrato."', '".$fecha_retiro."', '".$motivo_retiro."', ".$paso_input.", $tipo_contrato,  '".$situacion."',  '".$nivel."',  '".$categoria."',  '".$username_registro."',  '".$fecha_registro."', '".$username_movimiento."', '".$fecha_movimiento."', '".$ultimo_recibo."', '".$fecha_condicion."')";



            $resp=$this->cnmd06_fichas->execute($SQL_INSERT);

            if($resp>1) {
                $cond="cod_presi = ".$aa[1]." and cod_entidad = ".$aa[2]." and cod_tipo_inst = ".$aa[3]." and cod_inst = ".$aa[4]." and cod_dep = ".$aa[5]." and cod_tipo_nomina='".$cod_tipo_nomina."'  and cod_cargo=".$cod_cargo." and cod_puesto=".$cod_puesto."   ";
                $sql ="update cnmd05 set condicion_actividad='2', cod_ficha='".$cod_ficha."' where ".$cond;

                $condicion_cargo = $this->condicion()." and cod_tipo_nomina='".$cod_tipo_nomina."' and  cod_cargo='".$cod_cargo."' and (cod_ficha=0 or cod_ficha IS NULL) and (condicion_actividad=1 or condicion_actividad=0)";
                $datos_contar    = $this->cnmd05->findCount($condicion_cargo);

                if($datos_contar!="0" || $datos_contar!=0) {

                    $sw1 = $this->cnmd05->execute($sql);

                    if($sw1>1) {
                        $sql2 = "update cnmd06_datos_personales set  condicion_actual='1' where cedula_identidad=".$cedula_identidad;
                        $sw2 = $this->cnmd06_datos_personales->execute($sql2);

                        if($sw2>1) {
                            $this->cnmd06_datos_personales->execute("COMMIT;");
                            $this->data=null;
                            $this->set('Message_existe', 'Registro Agregado con exito');
                            $this->index($cod_tipo_nomina);
                            $this->render("index");
                        } else {
                            $this->cnmd06_datos_personales->execute("ROLLBACK;");
                            $this->set('errorMessage', 'Disculpe, El Registro no fue creado');
                            $this->index();
                            $this->render("index");
                        }//fin else

                    } else {
                        $this->cnmd06_datos_personales->execute("ROLLBACK;");
                        $this->set('errorMessage', 'Disculpe, El Registro no fue creado');
                        $this->index();
                        $this->render("index");
                    }//fin else

                } else {
                    $this->cnmd06_datos_personales->execute("ROLLBACK;");
                    $this->set('errorMessage', 'Disculpe, El cargo en estos momentos esta ocupado');
                    $this->index();
                    $this->render("index");
                }//fin else

            } else {
                $this->cnmd06_datos_personales->execute("ROLLBACK;");
                $this->set('errorMessage', 'Disculpe, El Registro no fue creado');
                $this->index();
                $this->render("index");
            }// fin else
        }//fin existe
    }//fin guardar










    public function cuenta_bancaria($var1=null, $var2=null)
    {

        $this->layout = "ajax";

        //echo mascara_cuatro($this->Session->read('ent'));

        $this->set('var1', mascara_cuatro($this->Session->read('ent')));
        $this->set('var2', mascara_cuatro($var2));

    }//fin function






    public function bt_nav($Tfilas, $pagina)
    {
        if($Tfilas==1) {
            $this->set('mostrarS', false);
            $this->set('mostrarA', false);
        } elseif($Tfilas==2) {
            if($pagina==2) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            }
        } elseif($Tfilas>=3) {
            if($pagina==$Tfilas) {
                $this->set('mostrarS', false);
                $this->set('mostrarA', true);
            } elseif($pagina==1) {
                $this->set('mostrarS', true);
                $this->set('mostrarA', false);
            } else {
                $this->set('mostrarS', true);
                $this->set('mostrarA', true);
            }
        }
    }//fin navegacion


    public function consulta($pagina=null)
    {
        $this->layout = "ajax";
        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');

        if($pagina!=null) {
            $pagina=$pagina;
            if($pagina<=0) {
                $pagina=1;
            }
            $Tfilas=$this->v_cnmd06_fichas_2->findCount($this->SQLCA());
            if($Tfilas==0) {
                $this->index();
                $this->render("index");
            }

            if($Tfilas!=0) {
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $datos=$this->v_cnmd06_fichas_2->findAll($this->SQLCA(), null, 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha ASC', 1, $pagina, null);

                $this->set('datos', $datos);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
            }
        } else {
            $pagina=1;
            $Tfilas=$this->v_cnmd06_fichas_2->findCount($this->SQLCA());
            if($Tfilas==0) {
                $this->index();
                $this->render("index");
            }
            if($Tfilas!=0) {
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $datos=$this->v_cnmd06_fichas_2->findAll($this->SQLCA(), null, 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha ASC', 1, $pagina, null);
                $this->set('datos', $datos);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);

            }
        }





        $puesto = $datos[0]['v_cnmd06_fichas_2']['cod_puesto'];
        $datos_air = $this->v_cnmd06_fichas_2->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$datos[0]['v_cnmd06_fichas_2']['cod_tipo_nomina']."'
										               ), '".$puesto."' ); ");
        $this->set('grado_input', $datos_air[0][0]["devolver_grado_puesto"]);


        $estatus_nomina = $this->Cnmd01->find($this->SQLCA()." and cod_tipo_nomina=".$datos[0]['v_cnmd06_fichas_2']['cod_tipo_nomina'], 'status_nomina');
        $dato_estatus = $estatus_nomina['Cnmd01']['status_nomina'];

        if($dato_estatus!=null && $dato_estatus<2) {
            $this->set('HABILITAR_ELIMINAR', true);
        } else {
            $this->set('HABILITAR_ELIMINAR', false);
        }





    }//fin function consultar2

    public function existe($numero)
    {
        $this->layout = "ajax";
        $cond="cod_ficha=".$numero;
        $si=$this->v_cnmd06_fichas->findCount($cond);
        if($si==1) {
            $datos=$this->v_cnmd06_fichas->findAll($cond);
            $this->set('datos', $datos);
            $this->set('Message_existe', 'Registro Existe.');
            $this->indexs();
            $this->render("indexs");
        } elseif($si==0) {
            $this->set('numero', $numero);
            $this->set('Message_existe', 'Por Favor Registre Los Datos.');
            $this->indexn();
            $this->render("indexn");
        }
    }
    public function indexs()
    {
        $this->layout="ajax";
    }
    public function indexn()
    {
        $this->layout="ajax";
        $Lista = $this->v_cnmd06->generateList($this->SQLCA(), 'cod_tipo_nomina ASC', null, '{n}.v_cnmd06.cod_tipo_nomina', '{n}.v_cnmd06.tipo_nomina');
        $this->concatena($Lista, 'cod_tipo_nomina');
        $Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
        $this->concatena($Listaentidad, 'cod_entidad_bancaria');
        $persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
        $this->concatena($persona, 'persona');
        $this->set("forma", array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
        $this->set("condicion", array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo"));
        $this->set("motivo", array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));
    }









    public function modificar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null)
    {

        $this->layout="ajax";

        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');

        $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
        $this->concatena($lista, 'cod_tipo_nomina');

        $Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
        $this->set('cod_entidad_bancaria', $Listaentidad);

        $persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
        $this->concatena($persona, 'persona');

        $this->set("forma", array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
        $condicion = array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo");
        $this->concatena($condicion, "condicion");
        $this->set("motivo", array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));

        $condi = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
        $sql   =  $condi." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."' and cedula_identidad='".$var5."'   ";


        $condicion_cargo = $condi." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."' and cod_ficha=0 and condicion_actividad=1 ";
        $datos_contar=$this->cnmd05->findCount($condicion_cargo);
        $this->set('contador_cnmd05', $datos_contar);



        $datos=$this->v_cnmd06_fichas_2->findAll($sql);
        $this->set('datos', $datos);
        foreach($datos as $da) {
            $cod_entx = $da['v_cnmd06_fichas_2']['cod_entidad_bancaria'];
        }
        $this->Session->write('ent', $cod_entx);
        //echo $cod_ent;
        //  pr($datos);

        $cond=" cod_entidad_bancaria=".$datos[0]["v_cnmd06_fichas_2"]["cod_entidad_bancaria"];
        $listaespecialidades=$this->cstd01_sucursales_bancarias->generateList($cond, 'cod_sucursal ASC', null, '{n}.cstd01_sucursales_bancarias.cod_sucursal', '{n}.cstd01_sucursales_bancarias.denominacion');
        $this->set('vector', $listaespecialidades);

        $this->set('pagina_consulta', $var6);


        $puesto = $var3;
        $datos_air = $this->v_cnmd06_fichas_2->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$var1."'
										               ), '".$puesto."' ); ");
        $this->set('grado_input', $datos_air[0][0]["devolver_grado_puesto"]);

    }//fin function









    public function guardar_modificar($var1=null, $var2=null, $var3=null, $var4=null, $var5=null, $var6=null)
    {

        $this->layout="ajax";

        $fi=$this->data['cnmp06_ficha']['fecha_ingreso'];
        $fi=$fi=="" ? "01/01/1900" : $fi;
        $fecha_ingreso=$fi;

        $forma_pago                      =   $this->data['cnmp06_ficha']['forma_pago'];
        $cedula_identidad                =   $this->data['cnmp06_ficha']['cedula_identidad'];
        $condicion_actividad             =   $this->data['cnmp06_ficha']['condicion'];
        $funciones_realizar              =   $this->data['cnmp06_ficha']['funciones_realizar'];
        $responsabilidad_administrativa  =   $this->data['cnmp06_ficha']['responsabilidad'];
        $horas_laborar                   =   $this->Formato1($this->data['cnmp06_ficha']['horas_laborar']);
        $porcentaje_jub_pension          =   $this->Formato1($this->data['cnmp06_ficha']['porcentaje']);
        $paso_input                      =   $this->data['cnmp06_ficha']['paso_input'];
        $cod_presi = $this->Session->read('SScodpresi');
        $cod_entidad = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst = $this->Session->read('SScodinst');
        $cod_dep = $this->Session->read('SScoddep');

        $ff=$this->data['cnmp06_ficha']['fecha_fin_contrato'];
        $ff=$ff=="" ? "01/01/1900" : $ff;
        $fecha_terminacion_contrato=$ff;

        $fr = $this->data['cnmp06_ficha']['fecha_retiro'];
        $fr = $fr=="" ? "01/01/1900" : $fr;
        $fecha_retiro   = $fr;

        $condicion_cargo = $this->condicion()." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."' and (cod_ficha=0 or cod_ficha IS NULL) and condicion_actividad=1 ";
        $datos_contar    = $this->cnmd05->findCount($condicion_cargo);


        $tipo_contrato      =  $this->data['cnmp06_ficha']['tipo_contrato'];

        if(empty($this->data['cnmp06_ficha']['situacion'])) {
            $situacion="";
        } else {
            $situacion     = $this->data['cnmp06_ficha']['situacion'];
        }
        if(empty($this->data['cnmp06_ficha']['nivel'])) {
            $nivel="";
        } else {
            $nivel         = $this->data['cnmp06_ficha']['nivel'];
        }
        if(empty($this->data['cnmp06_ficha']['categoria'])) {
            $categoria="";
        } else {
            $categoria     = $this->data['cnmp06_ficha']['categoria'];
        }
        if(empty($this->data['cnmp06_ficha']['tipo_contrato'])) {
            $tipo_contrato=1;
        } else {
            $tipo_contrato = $this->data['cnmp06_ficha']['tipo_contrato'];
        }

        if(empty($this->data['cnmp06_ficha']['cod_entidad_bancaria'])) {
            $cod_entidad_bancaria="0";
        } else {
            $cod_entidad_bancaria            =  $this->data['cnmp06_ficha']['cod_entidad_bancaria'];
        }
        if(empty($this->data['cnmp06_ficha']['cod_sucursal'])) {
            $cod_sucursal_bancaria="0";
        } else {
            $cod_sucursal_bancaria           =  $this->data['cnmp06_ficha']['cod_sucursal'];
        }
        if(empty($this->data['cnmp06_ficha']['cod_cuenta'])) {
            $numero_cuenta="0";
        } else {
            $numero_cuenta                   =  mascara($cod_entidad_bancaria, 4).mascara($cod_sucursal_bancaria, 4).$this->data['cnmp06_ficha']['cod_cuenta'];
        }
        //echo $numero_cuenta;

        if(!empty($this->data['cnmp06_ficha']['motivo'])) {
            $motivo_retiro=$this->data['cnmp06_ficha']['motivo'];
        } else {
            $motivo_retiro="0";
        }//fin else

        if($this->data['cnmp06_ficha']['condicion']!=6 && $datos_contar!=0) {
            $fecha_retiro   = "01/01/1900";
            $motivo_retiro="0";
        }

        $fecha_cambio  = $this->data['cnmp06_ficha']['fecha_cambio'];
        $motivo_cambio = $this->data['cnmp06_ficha']['motivo_cambio'];
        $nombre_representado = $this->data['cnmp06_ficha']['nombre_representado'];
        $cedula_representado = $this->data['cnmp06_ficha']['cedula_representado'];



        if($this->data['cnmp06_ficha']['condicion']!=$this->data['cnmp06_ficha']['condicion_aux']) {
            $fecha_movimiento                         =     date("Y-m-d");
            $username_movimiento                      =     $_SESSION['nom_usuario'];

            //$sql   = " BEGIN; UPDATE cnmd06_fichas set fecha_condicion='".cambiar_formato_fecha($fecha_cambio)."', username_movimiento='".$username_movimiento."', fecha_movimiento='".$fecha_movimiento."', tipo_contrato=".$tipo_contrato.", situacion='".$situacion."', nivel='".$nivel."', categoria='".$categoria."', paso=".$paso_input.", fecha_ingreso='".$fecha_ingreso."', forma_pago=".$forma_pago." ,cod_entidad_bancaria=".$cod_entidad_bancaria." , cod_sucursal=".$cod_sucursal_bancaria." , cuenta_bancaria='".$numero_cuenta."' , condicion_actividad=".$condicion_actividad." , funciones_realizar='".$funciones_realizar."' , responsabilidad_administrativa='".$responsabilidad_administrativa."' , horas_laborar=".$horas_laborar." , porcentaje_jub_pension=".$porcentaje_jub_pension." , fecha_terminacion_contrato='".$fecha_terminacion_contrato."' , fecha_retiro='".$fecha_retiro."' , motivo_retiro=".$motivo_retiro."";
            $sql   = " BEGIN; UPDATE cnmd06_fichas set fecha_condicion='".cambiar_formato_fecha($fecha_cambio)."', username_movimiento='".$username_movimiento."', fecha_movimiento='".$fecha_movimiento."', tipo_contrato=".$tipo_contrato.", situacion='".$situacion."', nivel='".$nivel."', categoria='".$categoria."', paso=".$paso_input.", forma_pago=".$forma_pago." ,cod_entidad_bancaria=".$cod_entidad_bancaria." , cod_sucursal=".$cod_sucursal_bancaria." , cuenta_bancaria='".$numero_cuenta."',cedula_identidad=".$cedula_identidad.", condicion_actividad=".$condicion_actividad." , funciones_realizar='".$funciones_realizar."' , responsabilidad_administrativa='".$responsabilidad_administrativa."' , horas_laborar=".$horas_laborar." , porcentaje_jub_pension=".$porcentaje_jub_pension." , fecha_terminacion_contrato='".$fecha_terminacion_contrato."' , fecha_retiro='".$fecha_retiro."' , motivo_retiro=".$motivo_retiro."";
            $sql  .= " WHERE ".$this->condicion()." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."' and cedula_identidad='".$var5."';   ";

            $cont_2 = $this->cnmd06_fichas_historial_condicion->findCount("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."'   ");

            if($cont_2!=0) {
                $datos_historia     = $this->cnmd06_fichas_historial_condicion->findAll("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep=".$cod_dep." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."'   ", null, "secuencia DESC");
                $secuencia_anterior = $datos_historia[0]["cnmd06_fichas_historial_condicion"]["secuencia"];
                $sql .= " UPDATE cnmd06_fichas_historial_condicion set fecha_hasta='".cambiar_formato_fecha($fecha_cambio)."'  WHERE ".$this->condicion()." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."' and secuencia='".$secuencia_anterior."'; ";
            }

            $sql  .= "  INSERT INTO cnmd06_fichas_historial_condicion (cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha, condicion_actividad, fecha_desde, fecha_hasta, motivo) ";
            $sql  .= "  VALUES ('".$cod_presi."', '".$cod_entidad."', '".$cod_tipo_inst."', '".$cod_inst."', '".$cod_dep."', '".$var1."', '".$var2."', '".$var4."', '".$condicion_actividad."', '".cambiar_formato_fecha($fecha_cambio)."', '".cambiar_formato_fecha("01/01/1900")."', '".$motivo_cambio."');  ";

        } else {
            $sql  = " BEGIN; UPDATE cnmd06_fichas set tipo_contrato=".$tipo_contrato.", situacion='".$situacion."', nivel='".$nivel."', categoria='".$categoria."', paso=".$paso_input.", fecha_ingreso='".$fecha_ingreso."', forma_pago=".$forma_pago." ,cod_entidad_bancaria=".$cod_entidad_bancaria." , cod_sucursal=".$cod_sucursal_bancaria." , cuenta_bancaria='".$numero_cuenta."' , condicion_actividad=".$condicion_actividad." , funciones_realizar='".$funciones_realizar."' , responsabilidad_administrativa='".$responsabilidad_administrativa."' , horas_laborar=".$horas_laborar." , porcentaje_jub_pension=".$porcentaje_jub_pension." , fecha_terminacion_contrato='".$fecha_terminacion_contrato."' , fecha_retiro='".$fecha_retiro."' , motivo_retiro=".$motivo_retiro.", nombre_representado='".$nombre_representado."', cedula_representado='".$cedula_representado."'";
            $sql .= " WHERE ".$this->condicion()." and cod_tipo_nomina='".$var1."' and  cod_cargo='".$var2."'  and cod_ficha='".$var4."' and cedula_identidad='".$var5."'   ";
        }//fin else







        if($this->data['cnmp06_ficha']['condicion']==6 && $this->data['cnmp06_ficha']['condicion']!=$this->data['cnmp06_ficha']['condicion_aux']) {
            $cont = $this->cnmd06_fichas->findCount("cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cedula_identidad=".$var5." and condicion_actividad!=6");
            if($cont<=1) {
                $sql2 = "update cnmd06_datos_personales set  condicion_actual='2' where cedula_identidad=".$var5;
                $sw2 = $this->cnmd06_datos_personales->execute($sql2);
            }//fin if
        }//fin if
        $vvv=$this->cnmd06_fichas->execute($sql);



        if($vvv>1) {
            if($this->data['cnmp06_ficha']['condicion']==6 && $this->data['cnmp06_ficha']['condicion']!=$this->data['cnmp06_ficha']['condicion_aux']) {
                $cond12 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_tipo_nomina='".$var1."'  and cod_cargo=".$var2."   ";
                $sql12  = "update cnmd05 set condicion_actividad='1', cod_ficha='0' where ".$cond12;
                $sw112  = $this->cnmd05->execute($sql12);
            }//fin if

            if($this->data['cnmp06_ficha']['condicion']!=6 && $datos_contar!=0) {

                $cond12 = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep." and cod_tipo_nomina='".$var1."'  and cod_cargo=".$var2." ";
                $sql12  = "update cnmd05 set condicion_actividad='2', cod_ficha='".$var4."' where ".$cond12;
                $sw112  = $this->cnmd05->execute($sql12);

                $sql2 = "update cnmd06_datos_personales set  condicion_actual='1' where cedula_identidad=".$var5;
                $sw2 = $this->cnmd06_datos_personales->execute($sql2);
            }

            $status_actual = $this->Cnmd01->field("status_nomina", $this->SQLCA()." and cod_tipo_nomina=".$var1);
            if ($status_actual==1) {
                $sqlupd_estatus = "update cnmd01 set status_nomina='0' where ".$this->condicion()." and cod_tipo_nomina='".$var1."';   ";
                $swupd_est = $this->Cnmd01->execute($sqlupd_estatus);
            }

            $this->cnmd06_fichas->execute("COMMIT;");
            $this->set('Message_existe', 'Registro Modificado con exito');
            $this->consulta($var6);
            $this->render("consulta");
        } else {
            $this->cnmd06_fichas->execute("ROLLBACK;");
            $this->set('errorMessage', 'Disculpe, El Registro no fue modificado');
            $this->consulta($var6);
            $this->render("consulta");
        }//fin else

    }//fin function






    public function preconsulta()
    {
        $this->layout="ajax";


    }

    public function datos($pista=null)
    {
        $this->layout="ajax";
        if($pista!=null) {
            $da="cedula_identidad=".$pista;
            $datos=$this->v_cnmd06_fichas->findAll($da);
            $this->set('datos', $datos);


        }

    }


    public function llenar_pista_opcion_cargo($var1=null)
    {

        $this->layout="ajax";
        $this->Session->write('pista_opcion_cargo', $var1);
        echo "<script>$('select_obra_cod_obra').value='';</script>";

    }//fin fucntion


    public function codigo_nomina($codigo=null)
    {
        $this->layout = "ajax";
        $a = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo, array('cod_tipo_nomina','denominacion', 'horas_laborables'));
        $this->set("a", $a[0]['Cnmd01']['cod_tipo_nomina']);
        $this->Session->write('tipo_nomina', $codigo);

        echo "<script>";
        echo "document.getElementById('segunda_ventana').disabled=false;";
        echo "document.getElementById('datos_ventana').disabled=false;";
        echo "document.getElementById('i_cod_cargo').readOnly=false;";
        echo "document.getElementById('numero_input').value='';";
        if($codigo==null) {
            echo "document.getElementById('radio_1').disabled=true;";
            echo "document.getElementById('radio_2').disabled=true;";

        } else {
            echo "document.getElementById('radio_1').disabled=false;";
            echo "document.getElementById('radio_2').disabled=false;";

        }//fin else
        echo "document.getElementById('radio_1').checked=true;";
        echo "document.getElementById('radio_2').checked=false;";
        echo "document.getElementById('horas_laborar').value='".$this->Formato2($a[0]['Cnmd01']['horas_laborables'])."';";
        echo "</script>";
    }//fin cpcp02_codigo

    public function denominacion_nomina($codigo)
    {
        $this->layout = "ajax";
        $b = $this->Cnmd01->findAll($this->SQLCA()." and cod_tipo_nomina=".$codigo, array('cod_tipo_nomina','denominacion'));
        $this->set("b", $b[0]['Cnmd01']['denominacion']);


    }//fin cpcp02_denominacion

    public function buscar_cargo($var1=null)
    {
        $this->layout="ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion_cargo', 1);
    }//fin function

    public function buscar_por_pista($var1=null, $var2=null, $var3=null)
    {
        $this->layout="ajax";
        $tipo_nomina=   $this->Session->read('tipo_nomina');
        $pista_opcion_cargo = $this->Session->read('pista_opcion_cargo');

        $sql_cargo =  "";

        if($pista_opcion_cargo==1) {
            $sql_cargo = "";

        } elseif($pista_opcion_cargo==2) {
            $sql_cargo = " and condicion_actividad!='2' ";

        } elseif($pista_opcion_cargo==3) {
            $sql_cargo = " and condicion_actividad='2' ";

        }



        if($var3==null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            if(is_int($var2)) {
                $sql   = " (demonimacion_puesto LIKE '%$var2%')  or   ";
            } else {
                $sql = "";
            }
            $Tfilas=$this->v_cnmd06->findCount($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var2%')  or cod_cargo::text  LIKE '%$var2%' )  ".$sql_cargo);
            if($Tfilas!=0) {
                $pagina=1;
                $Tfilas=(int)ceil($Tfilas/100);
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var2%') or cod_cargo::text  LIKE '%$var2%'  )   ".$sql_cargo, null, "cod_cargo,cod_puesto ASC", 100, 1, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        } else {
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            if(is_int($var22)) {
                $sql   = " (demonimacion_puesto  LIKE '%$var22%')  or   ";
            } else {
                $sql = "";
            }
            $Tfilas=$this->v_cnmd06->findCount($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var22%')  or cod_cargo::text  LIKE '%$var22%' ) ".$sql_cargo);
            if($Tfilas!=0) {
                $pagina=$var3;
                $Tfilas=(int)ceil($Tfilas/100);
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and '.$sql." (upper(demonimacion_puesto) LIKE upper('%$var22%') or cod_cargo::text  LIKE '%$var22%' )  ".$sql_cargo, null, "cod_cargo,cod_puesto ASC", 100, $pagina, null);
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }//fin function







    public function seleccion_busqueda_venta($var1=null, $var2=null)
    {
        $this->layout="ajax";


        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');


        $tipo_nomina=   $this->Session->read('tipo_nomina');
        $resultado=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and cod_cargo='.$var1.' and cod_puesto='.$var2);

        $this->Session->write('tipo_nomina_cod_cargo', $var1);

        if($resultado[0]['v_cnmd06']["cod_ficha"]=="0") {
            $resultado[0]['v_cnmd06']["cod_ficha"] = "";
        }

        if($resultado[0]['v_cnmd06']['condicion_actividad']==2 && $resultado[0]['v_cnmd06']['cod_ficha']!="") {


            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatena($lista, 'cod_tipo_nomina');

            $Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
            $this->concatena($Listaentidad, 'cod_entidad_bancaria');

            $persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
            $this->concatena($persona, 'persona');

            $this->set("forma", array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
            $condicion = array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo");
            $this->concatena($condicion, "condicion");
            $this->set("motivo", array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));

            $condi  = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
            $sql2   =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var1."'  and cod_ficha='".$resultado[0]['v_cnmd06']['cod_ficha']."'  ";

            //                       $datos2 = $this->v_cnmd06_fichas->findAll($sql2,null,'cod_ficha ASC',null);
            //                       $pagina = 1;
            //			          	 $Tfilas = $this->v_cnmd06_fichas->findCount($sql2);
            //                       $Tfilas = $this->v_cnmd06_fichas->findCount($this->SQLCA());
            $datos  = $this->v_cnmd06_fichas_2->findAll($this->SQLCA(), "cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad", 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha ASC');
            $contar = 0;
            $pagina = 1;
            $Tfilas = count($datos);
            foreach($datos as $ve2) {
                $cod_tipo_nomina_aux = $ve2["v_cnmd06_fichas_2"]["cod_tipo_nomina"];
                $cod_cargo_aux       = $ve2["v_cnmd06_fichas_2"]["cod_cargo"];
                $cod_ficha_aux       = $ve2["v_cnmd06_fichas_2"]["cod_ficha"];
                $contar++;
                if($cod_tipo_nomina_aux==$tipo_nomina && $cod_cargo_aux==$var1 && $cod_ficha_aux==$resultado[0]['v_cnmd06']['cod_ficha']) {
                    $datos[0]['v_cnmd06_fichas_2']['cedula_identidad'] = $ve2["v_cnmd06_fichas_2"]["cedula_identidad"];
                    break;
                }
            }
            if($contar!=0) {
                $pagina = $contar;
            }
            if($Tfilas!=0) {
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
            }//fin if
            $sql   =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var1."'  and cod_ficha='".$resultado[0]['v_cnmd06']['cod_ficha']."' and cedula_identidad='".$datos[0]['v_cnmd06_fichas_2']['cedula_identidad']."'   ";
            $datos=$this->v_cnmd06_fichas_2->findAll($sql);
            $this->set('datos', $datos);

            $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
            $datos_air = $this->v_cnmd06_fichas_2->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");


            $this->set('grado_input', $datos_air[0][0]["devolver_grado_puesto"]);
            $this->set('HABILITAR_ELIMINAR', true);
            $this->render("consulta");


        } else {

            $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
            $datos_air = $this->v_cnmd06_fichas->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");


            echo "<script>";
            echo "document.getElementById('grado_input').value='".mascara2($datos_air[0][0]["devolver_grado_puesto"])."';   ";
            echo "document.getElementById('i_cod_cargo').value='".$this->AddCeroR($resultado[0]['v_cnmd06']['cod_cargo'])."';   ";
            echo "document.getElementById('i_cod_puesto').value='".$resultado[0]['v_cnmd06']['cod_puesto']."';   ";
            echo "document.getElementById('i_deno_puesto').value='".$resultado[0]['v_cnmd06']['demonimacion_puesto']."';   ";
            echo "document.getElementById('ubicacion_geografica').value='".$resultado[0]['v_cnmd06']['deno_cod_dir_superior']."\\n".$resultado[0]['v_cnmd06']['deno_cod_coordinacion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_secretaria']."\\n".$resultado[0]['v_cnmd06']['deno_cod_direccion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_division']."\\n".$resultado[0]['v_cnmd06']['deno_cod_departamento']."\\n".$resultado[0]['v_cnmd06']['deno_cod_oficina']."';   ";
            echo "document.getElementById('ubicacion_administrativa').value='".$resultado[0]['v_cnmd06']['deno_cod_estado']."\\n".$resultado[0]['v_cnmd06']['deno_cod_municipio']."\\n".$resultado[0]['v_cnmd06']['deno_cod_parroquia']."\\n".$resultado[0]['v_cnmd06']['deno_cod_centro']."';   ";
            echo "document.getElementById('recursos_tipo').value='".$resultado[0]['v_cnmd06']['denominacion_cod_nivel_i']."\\n".$resultado[0]['v_cnmd06']['denominacion_cod_nivel_ii']."';   ";
            echo "document.getElementById('sueldo').value='".$this->Formato2($resultado[0]['v_cnmd06']['sueldo_basico'])."';   ";
            echo "document.getElementById('compensaciones').value='".$this->Formato2($resultado[0]['v_cnmd06']['compensaciones'])."';   ";
            echo "document.getElementById('primas').value='".$this->Formato2($resultado[0]['v_cnmd06']['primas'])."';   ";
            echo "document.getElementById('bonos').value='".$this->Formato2($resultado[0]['v_cnmd06']['bonos'])."';   ";
            echo "document.getElementById('total').value='".$this->Formato2($resultado[0]['v_cnmd06']['sueldo_basico'] + $resultado[0]['v_cnmd06']['compensaciones'] + $resultado[0]['v_cnmd06']['primas'] + $resultado[0]['v_cnmd06']['bonos'])."';   ";
            echo "</script>";
            $this->funcion();
            $this->render("funcion");




        }//fin else










    }//fin function







    public function buscar_cargo_input($var1=null)
    {



        $this->layout="ajax";
        $tipo_nomina=   $this->Session->read('tipo_nomina');
        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');


        $tipo_nomina=   $this->Session->read('tipo_nomina');
        $resultado=$this->v_cnmd06->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and cod_cargo='.$var1);

        if($resultado[0]['v_cnmd06']["cod_ficha"]=="0") {
            $resultado[0]['v_cnmd06']["cod_ficha"] = "";
        }

        if($resultado[0]['v_cnmd06']['condicion_actividad']==2 && $resultado[0]['v_cnmd06']['cod_ficha']!="") {



            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatena($lista, 'cod_tipo_nomina');

            $Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
            $this->concatena($Listaentidad, 'cod_entidad_bancaria');

            $persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
            $this->concatena($persona, 'persona');

            $this->set("forma", array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
            $condicion = array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo");
            $this->concatena($condicion, "condicion");
            $this->set("motivo", array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));

            $condi = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
            $sql2  =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var1."'  and cod_ficha='".$resultado[0]['v_cnmd06']['cod_ficha']."'  ";
            $datos=$this->v_cnmd06_fichas->findAll($sql2, null, 'cod_ficha ASC', null);

            $pagina=1;
            $Tfilas=$this->v_cnmd06_fichas->findCount($sql2);
            if($Tfilas!=0) {
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
            }//fin if

            $sql   =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var1."'  and cod_ficha='".$resultado[0]['v_cnmd06']['cod_ficha']."' and cedula_identidad='".$datos[0]['v_cnmd06_fichas']['cedula_identidad']."'   ";
            $datos=$this->v_cnmd06_fichas->findAll($sql);
            $this->set('datos', $datos);

            echo "<script>";
            echo "document.getElementById('capa_aux_principal').innerHTML='';   ";
            echo "</script>";

            $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
            $datos_air = $this->v_cnmd06_fichas->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");

            $this->set('grado_input', $datos_air[0][0]["devolver_grado_puesto"]);




            $this->render("consulta");


        } else {


            $puesto = $resultado[0]['v_cnmd06']['cod_puesto'];
            $datos_air = $this->v_cnmd06_fichas->execute("
										select devolver_grado_puesto(
										               (select xy.clasificacion_personal from cnmd01 xy where
										                  xy.cod_presi           =     '".$cod_presi."'         and
														  xy.cod_entidad         =     '".$cod_entidad."'       and
														  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
														  xy.cod_inst            =     '".$cod_inst."'         and
														  xy.cod_dep             =     '".$cod_dep."'         and
														  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
										               ), '".$puesto."' ); ");


            echo "<script>";
            echo "document.getElementById('grado_input').value='".mascara2($datos_air[0][0]["devolver_grado_puesto"])."';   ";
            //echo "document.getElementById('i_cod_cargo').value='".$this->AddCeroR($resultado[0]['v_cnmd06']['cod_cargo'])."';   ";
            echo "document.getElementById('i_cod_puesto').value='".$resultado[0]['v_cnmd06']['cod_puesto']."';   ";
            echo "document.getElementById('i_deno_puesto').value='".$resultado[0]['v_cnmd06']['demonimacion_puesto']."';   ";
            echo "document.getElementById('ubicacion_geografica').value='".$resultado[0]['v_cnmd06']['deno_cod_dir_superior']."\\n".$resultado[0]['v_cnmd06']['deno_cod_coordinacion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_secretaria']."\\n".$resultado[0]['v_cnmd06']['deno_cod_direccion']."\\n".$resultado[0]['v_cnmd06']['deno_cod_division']."\\n".$resultado[0]['v_cnmd06']['deno_cod_departamento']."\\n".$resultado[0]['v_cnmd06']['deno_cod_oficina']."';   ";
            echo "document.getElementById('ubicacion_administrativa').value='".$resultado[0]['v_cnmd06']['deno_cod_estado']."\\n".$resultado[0]['v_cnmd06']['deno_cod_municipio']."\\n".$resultado[0]['v_cnmd06']['deno_cod_parroquia']."\\n".$resultado[0]['v_cnmd06']['deno_cod_centro']."';   ";
            echo "document.getElementById('recursos_tipo').value='".$resultado[0]['v_cnmd06']['denominacion_cod_nivel_i']."\\n".$resultado[0]['v_cnmd06']['denominacion_cod_nivel_ii']."';   ";
            echo "document.getElementById('sueldo').value='".$this->Formato2($resultado[0]['v_cnmd06']['sueldo_basico'])."';   ";
            echo "document.getElementById('compensaciones').value='".$this->Formato2($resultado[0]['v_cnmd06']['compensaciones'])."';   ";
            echo "document.getElementById('primas').value='".$this->Formato2($resultado[0]['v_cnmd06']['primas'])."';   ";
            echo "document.getElementById('bonos').value='".$this->Formato2($resultado[0]['v_cnmd06']['bonos'])."';   ";
            echo "document.getElementById('total').value='".$this->Formato2($resultado[0]['v_cnmd06']['sueldo_basico'] + $resultado[0]['v_cnmd06']['compensaciones'] + $resultado[0]['v_cnmd06']['primas'] + $resultado[0]['v_cnmd06']['bonos'])."';   ";
            echo "</script>";
            $this->funcion();
            $this->render("funcion");


        }//fin else



    }//fin function












    public function funcion($var1=null, $var2=null, $var3=null)
    {

        $this->layout="ajax";


    }//fin function

    public function buscar_persona($var1=null)
    {
        $this->layout="ajax";
        $this->set("opcion", $var1);
        $this->Session->delete('pista');
        $this->Session->write('pista_opcion', 2);
    }//fin function

    public function buscar_por_pista2($var1=null, $var2=null, $var3=null)
    {
        $this->layout="ajax";
        $var_like = "";
        $tipo_nomina=   $this->Session->read('tipo_nomina');
        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');
        if($var3==null) {
            $var2 = strtoupper($var2);
            $this->Session->write('pista', $var2);
            $var_like = $var2;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
            $Tfilas=$this->datos_personales_super_busqueda->findCount($sql_like);
            if($Tfilas!=0) {
                $pagina=1;
                $Tfilas=(int)ceil($Tfilas/100);
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas=$this->datos_personales_super_busqueda->findAll($sql_like, null, "primer_nombre,primer_apellido ASC", 100, 1, null);
                $sql = "";
                foreach($datos_filas as $ve) {
                    if($sql=="") {
                        $sql .= "    a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'  ";
                    }
                }//fin foreach
                $dato_a =   $this->datos_personales_super_busqueda->execute("
                                    SELECT
                                          a.cod_presi,
										  a.cod_entidad,
										  a.cod_tipo_inst,
										  a.cod_inst,
										  a.cod_dep,
										  a.cod_tipo_nomina,
										  a.cod_cargo,
										  a.cod_ficha,
										  a.cedula_identidad,
										  a.condicion_actividad
								     FROM
								           cnmd06_fichas a,
								           cnmd05        b
								     WHERE
								     	  a.cod_presi         =  '".$cod_presi."'      and
										  a.cod_entidad       =  '".$cod_entidad."'    and
										  a.cod_tipo_inst     =  '".$cod_tipo_inst."'  and
										  a.cod_inst          =  '".$cod_inst."'       and
										  a.cod_dep           =  '".$cod_dep."'        and
										  a.cod_tipo_nomina   =  '".$tipo_nomina."'    and
								          b.cod_presi         =  a.cod_presi           and
										  b.cod_entidad       =  a.cod_entidad         and
										  b.cod_tipo_inst     =  a.cod_tipo_inst       and
										  b.cod_inst          =  a.cod_inst            and
										  b.cod_dep           =  a.cod_dep             and
										  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
										  b.cod_cargo         =  a.cod_cargo           and ( ".$sql." ) ");



                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
            } else {
                $this->set("datosFILAS", '');
            }
            $this->set("dato_a", $dato_a);
        } else {
            $var22 = $this->Session->read('pista');
            $var22 = strtoupper($var22);
            $var_like = $var22;
            $sql_like = $this->sql_like_super_busqueda($var_like, $this->Session->read('pista_opcion'));
            $Tfilas=$this->datos_personales_super_busqueda->findCount($sql_like);
            if($Tfilas!=0) {
                $pagina=$var3;
                $Tfilas=(int)ceil($Tfilas/100);
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $this->set('total_paginas', $Tfilas);
                $this->set('pagina_actual', $pagina);
                $this->set('ultimo', $Tfilas);
                $datos_filas=$this->datos_personales_super_busqueda->findAll($sql_like, null, "primer_nombre,primer_apellido ASC", 100, $pagina, null);
                $sql = "";
                foreach($datos_filas as $ve) {
                    if($sql=="") {
                        $sql .= "    a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."' ";
                    } else {
                        $sql .= " or a.cedula_identidad = '".$ve["datos_personales_super_busqueda"]["cedula_identidad"]."'  ";
                    }
                }//fin foreach
                $dato_a =   $this->datos_personales_super_busqueda->execute("
					                                    SELECT
					                                          a.cod_presi,
															  a.cod_entidad,
															  a.cod_tipo_inst,
															  a.cod_inst,
															  a.cod_dep,
															  a.cod_tipo_nomina,
															  a.cod_cargo,
															  a.cod_ficha,
															  a.cedula_identidad,
															  a.condicion_actividad
													     FROM
													           cnmd06_fichas a,
													           cnmd05        b
													     WHERE
													     	  a.cod_presi         =  '".$cod_presi."'      and
															  a.cod_entidad       =  '".$cod_entidad."'    and
															  a.cod_tipo_inst     =  '".$cod_tipo_inst."'  and
															  a.cod_inst          =  '".$cod_inst."'       and
															  a.cod_dep           =  '".$cod_dep."'        and
															  a.cod_tipo_nomina   =  '".$tipo_nomina."'    and
													          b.cod_presi         =  a.cod_presi           and
															  b.cod_entidad       =  a.cod_entidad         and
															  b.cod_tipo_inst     =  a.cod_tipo_inst       and
															  b.cod_inst          =  a.cod_inst            and
															  b.cod_dep           =  a.cod_dep             and
															  b.cod_tipo_nomina   =  a.cod_tipo_nomina     and
															  b.cod_cargo         =  a.cod_cargo           and ( ".$sql." ) ");
                $this->set("datosFILAS", $datos_filas);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
                $this->set("dato_a", $dato_a);
            } else {
                $this->set("datosFILAS", '');
            }
        }//fin else
        $this->set("opcion", $var1);
    }//fin function


    public function seleccion_busqueda_venta2($var1=null, $var2=null, $var3=null)
    {
        $this->layout="ajax";
        $cod_presi      =  $this->Session->read('SScodpresi');
        $cod_entidad    =  $this->Session->read('SScodentidad');
        $cod_tipo_inst  =  $this->Session->read('SScodtipoinst');
        $cod_inst       =  $this->Session->read('SScodinst');
        $cod_dep        =  $this->Session->read('SScoddep');
				$ano_ejecucion = $this->ano_ejecucion();



        $tipo_nomina   =  $this->Session->read('tipo_nomina');

        if($var2==null) {
            $var2=0;
        }
        if($var3==null) {
            $var3=0;
        }
        $resultado=$this->cnmd06_fichas->findAll($this->SQLCA().' and cod_tipo_nomina='.$tipo_nomina.' and cod_cargo='.$var2.' and cod_ficha='.$var3);
        if(!isset($resultado[0]['cnmd06_fichas']["cod_ficha"])) {
            $resultado[0]['cnmd06_fichas']["cod_ficha"]  = "";
        }

        if($resultado[0]['cnmd06_fichas']['cod_ficha']!="") {


            $lista = $this->Cnmd01->generateList($conditions = $this->condicion(), $order = 'cod_tipo_nomina', $limit = null, '{n}.Cnmd01.cod_tipo_nomina', '{n}.Cnmd01.denominacion');
            $this->concatena($lista, 'cod_tipo_nomina');

            $Listaentidad = $this->cstd01_entidades_bancarias->generateList(null, 'cod_entidad_bancaria ASC', null, '{n}.cstd01_entidades_bancarias.cod_entidad_bancaria', '{n}.cstd01_entidades_bancarias.denominacion');
            $this->concatena($Listaentidad, 'cod_entidad_bancaria');

            $persona = $this->v_cnmd06_nombres->generateList(null, null, null, '{n}.v_cnmd06_nombres.cedula_identidad', '{n}.v_cnmd06_nombres.nombre_completo');
            $this->concatena($persona, 'persona');

            $this->set("forma", array(1=>"Efectivo",2=>"Recibo",3=>"Deposito Bancario",4=>"Cheque"));
            $condicion = array(1=>"Activo",2=>"Permiso no Remunerado",3=>"Comisión de Servicio",4=>"Vacaciones",5=>"Suspendido",6=>"Retirado",7=>"Ascenso", 8=>"Reposo");
            $this->concatena($condicion, "condicion");
            $this->set("motivo", array(1=>"Despido Justificado",2=>"Despido Injustificado",3=>"Retiro Justificado",4=>"Renuncia",5=>"Culminacion de Contrato",6=>"Remoción del Cargo",7=>"Baja por Propia Solicitud",8=>"Baja por Expulsión",9=>"Reducción de Personal ",10=>"Jubilado",11=>"Pensionado",12=>"Fallecimiento"));

            $condi = "cod_presi = ".$cod_presi." and cod_entidad = ".$cod_entidad." and cod_tipo_inst = ".$cod_tipo_inst." and cod_inst = ".$cod_inst." and cod_dep = ".$cod_dep;
            $sql2  =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var2."'  and cod_ficha='".$resultado[0]['cnmd06_fichas']['cod_ficha']."'  ";
            //						                        $datos = $this->v_cnmd06_fichas->findAll($sql2,null,'cod_ficha ASC',null);

            $datos  = $this->v_cnmd06_fichas_2->findAll($this->SQLCA(), "cod_tipo_nomina, cod_cargo, cod_ficha, cedula_identidad", 'cod_presi, cod_entidad, cod_tipo_inst, cod_inst, cod_dep, cod_tipo_nomina, cod_cargo, cod_ficha ASC');
            $contar = 0;
            $pagina = 1;
            $Tfilas = count($datos);
            foreach($datos as $ve2) {
                $cod_tipo_nomina_aux = $ve2["v_cnmd06_fichas_2"]["cod_tipo_nomina"];
                $cod_cargo_aux       = $ve2["v_cnmd06_fichas_2"]["cod_cargo"];
                $cod_ficha_aux       = $ve2["v_cnmd06_fichas_2"]["cod_ficha"];
                $contar++;
                if($cod_tipo_nomina_aux==$tipo_nomina && $cod_cargo_aux==$var2 && $cod_ficha_aux==$resultado[0]['cnmd06_fichas']['cod_ficha']) {
                    $datos[0]['v_cnmd06_fichas_2']['cedula_identidad'] = $ve2["v_cnmd06_fichas_2"]["cedula_identidad"];
                    break;
                }
            }
            if($contar!=0) {
                $pagina = $contar;
            }
            if($Tfilas!=0) {
                $this->set('pag_cant', $pagina.'/'.$Tfilas);
                $this->set('siguiente', $pagina+1);
                $this->set('anterior', $pagina-1);
                $this->bt_nav($Tfilas, $pagina);
            }//fin if

            $sql   =  $condi." and cod_tipo_nomina='".$tipo_nomina."' and  cod_cargo='".$var2."'  and cod_ficha='".$resultado[0]['cnmd06_fichas']['cod_ficha']."' and cedula_identidad='".$datos[0]['v_cnmd06_fichas_2']['cedula_identidad']."'   ";
            $datos =  $this->v_cnmd06_fichas_2->findAll($sql);
            $this->set('datos', $datos);

            echo "<script>";
            echo "document.getElementById('capa_aux_principal').innerHTML='';   ";
            echo "</script>";


            $puesto = $datos[0]['v_cnmd06_fichas_2']['cod_puesto'];
            $datos_air = $this->v_cnmd06_fichas_2->execute("
															select devolver_grado_puesto(
															               (select xy.clasificacion_personal from cnmd01 xy where
															                  xy.cod_presi           =     '".$cod_presi."'         and
																			  xy.cod_entidad         =     '".$cod_entidad."'       and
																			  xy.cod_tipo_inst       =     '".$cod_tipo_inst."'         and
																			  xy.cod_inst            =     '".$cod_inst."'         and
																			  xy.cod_dep             =     '".$cod_dep."'         and
																			  xy.cod_tipo_nomina     =     '".$tipo_nomina."'
															               ), '".$puesto."' ); ");

            $this->set('grado_input', $datos_air[0][0]["devolver_grado_puesto"]);
            $this->set('HABILITAR_ELIMINAR', true);
            $this->render("consulta");

        } else {


            $resultado=$this->cnmd06_datos_personales->findAll('cedula_identidad='.$var1);
            $mensaje = "";
            $aceptado = 0;

						$sql_cierre_ficha = "select * from cnmd00_cierre_ficha where cod_dep=".$cod_dep." and ano=".$ano_ejecucion;
	          $cierre_ficha = $this->Cnmd01->execute($sql_cierre_ficha);

            $aceptado=$this->cnmd06_fichas->findCount('cedula_identidad='.$var1." and (condicion_actividad!=6 or condicion_actividad!=5)");


            if($aceptado!=0) {
                $aceptado_aux=$this->cnmd06_fichas->findAll('cedula_identidad='.$var1);
                $cod_presi_aux              =  $aceptado_aux[0]["cnmd06_fichas"]["cod_presi"];
                $cod_entidad_aux            =  $aceptado_aux[0]["cnmd06_fichas"]["cod_entidad"];
                $cod_tipo_inst_aux          =  $aceptado_aux[0]["cnmd06_fichas"]["cod_tipo_inst"];
                $cod_inst_aux               =  $aceptado_aux[0]["cnmd06_fichas"]["cod_inst"];
                $cod_dep_aux                =  $aceptado_aux[0]["cnmd06_fichas"]["cod_dep"];
                $cod_tipo_nomina_aux        =  $aceptado_aux[0]["cnmd06_fichas"]["cod_tipo_nomina"];
                $condicion_actividad        =  $aceptado_aux[0]["cnmd06_fichas"]["condicion_actividad"];
                if($condicion_actividad==1) {
                    $con_activ = 'ACTIVO';
                } elseif($condicion_actividad==2) {
                    $con_activ = 'PERMISO NO REMUNERADO';
                } elseif($condicion_actividad==3) {
                    $con_activ = 'COMISIÓN DE SERVICIO';
                } elseif($condicion_actividad==4) {
                    $con_activ = 'VACACIONES';
                } elseif($condicion_actividad==5) {
                    $con_activ = 'SUSPENDIDO';
                } elseif($condicion_actividad==6) {
                    $con_activ = 'RETIRADO';
                } elseif($condicion_actividad==7) {
                    $con_activ = 'ASCENSO';
                } elseif($condicion_actividad==8) {
                    $con_activ = 'Reposo';
                }

                $deno_a_aux=$this->cugd02_institucion->findAll('cod_tipo_institucion='.$cod_tipo_inst_aux." and cod_institucion=".$cod_inst_aux);
                $deno_b_aux=$this->cugd02_dependencia->findAll('cod_tipo_institucion='.$cod_tipo_inst_aux." and cod_institucion=".$cod_inst_aux." and cod_dependencia=".$cod_dep_aux);
                $deno_c_aux=$this->Cnmd01->findAll("cod_presi = '".$cod_presi_aux."' and cod_entidad = '".$cod_entidad_aux."' and cod_tipo_inst = '".$cod_tipo_inst_aux."' and cod_inst = '".$cod_inst_aux."' and cod_dep = '".$cod_dep_aux."' and cod_tipo_nomina = '".$cod_tipo_nomina_aux."' ");


                $deno_a=strtoupper($deno_a_aux[0]["cugd02_institucion"]["denominacion"]);
                $deno_b=strtoupper($deno_b_aux[0]["cugd02_dependencia"]["denominacion"]);
                $deno_c=strtoupper($deno_c_aux[0]["Cnmd01"]["denominacion"]);
                $mensaje  = "ESTA PERSONA YA ESTA REGISTRADA EN LA  \\n -. INSTITUCIÓN: ".$deno_a."  \\n -. DEPENDENCIA: ".$deno_b." \\n -. TIPO DE NOMINA: ".$deno_c." \\n -. CONDICIÓN ACTIVIDAD: ".$con_activ;
            }//fin if

            echo "<script>";
            if($aceptado!=0) {
                echo "if (confirm('".$mensaje."')) {";
								if(count($cierre_ficha)>0 && $cierre_ficha[0][0]['ficha_status']==0){
									echo "document.getElementById('cedula').value='".$this->AddCeroR($resultado[0]['cnmd06_datos_personales']['cedula_identidad'])."';   ";
									echo "document.getElementById('primer_n').value='".$resultado[0]['cnmd06_datos_personales']['primer_nombre']."';   ";
									echo "document.getElementById('segundo_n').value='".$resultado[0]['cnmd06_datos_personales']['segundo_nombre']."';   ";
									echo "document.getElementById('primer_a').value='".$resultado[0]['cnmd06_datos_personales']['primer_apellido']."';   ";
									echo "document.getElementById('segundo_a').value='".$resultado[0]['cnmd06_datos_personales']['segundo_apellido']."';   ";
								}else{
									echo "document.getElementById('imagen').innerHTML='';   ";
									echo "document.getElementById('cedula').value='';   ";
									echo "document.getElementById('primer_n').value='';   ";
									echo "document.getElementById('segundo_n').value='';   ";
									echo "document.getElementById('primer_a').value='';   ";
									echo "document.getElementById('segundo_a').value='';   ";
								}
								echo "}else{ ";
								echo "document.getElementById('imagen').innerHTML='';   ";
								echo "document.getElementById('cedula').value='';   ";
								echo "document.getElementById('primer_n').value='';   ";
								echo "document.getElementById('segundo_n').value='';   ";
								echo "document.getElementById('primer_a').value='';   ";
								echo "document.getElementById('segundo_a').value='';   ";
								echo " }";
            }else{

							echo "document.getElementById('cedula').value='".$this->AddCeroR($resultado[0]['cnmd06_datos_personales']['cedula_identidad'])."';   ";
							echo "document.getElementById('primer_n').value='".$resultado[0]['cnmd06_datos_personales']['primer_nombre']."';   ";
							echo "document.getElementById('segundo_n').value='".$resultado[0]['cnmd06_datos_personales']['segundo_nombre']."';   ";
							echo "document.getElementById('primer_a').value='".$resultado[0]['cnmd06_datos_personales']['primer_apellido']."';   ";
							echo "document.getElementById('segundo_a').value='".$resultado[0]['cnmd06_datos_personales']['segundo_apellido']."';   ";
						}
            echo "</script>";



            $vec=$this->cugd10_imagenes->findCount("cod_campo=11 and identificacion='".$var1."'");
            if($vec!=0) {
                $this->set('existe_imagen', true);
            } else {
                $this->set('existe_imagen', false);
            }
            $this->set('cedula', $var1);



        }//fin else


    }//fin function







    public function funcion_1($var=null)
    {
        $cod_presi     = $this->Session->read('SScodpresi');
        $cod_entidad   = $this->Session->read('SScodentidad');
        $cod_tipo_inst = $this->Session->read('SScodtipoinst');
        $cod_inst      = $this->Session->read('SScodinst');
        $cod_dep       = $this->Session->read('SScoddep');
        $this->layout="ajax";
        $url                  =  "/cnmp06_ficha/funcion_2/1";
        $width_aux            =  "550px";
        $height_aux           =  "200px";
        $title_aux            =  "Motivo de la modificación";
        $resizable_aux        =  false;
        $maximizable_aux      =  false;
        $minimizable_aux      =  false;
        $closable_aux         =  false;
        echo"<script>";
        echo  "codigo_ventana('".$url."', '".$width_aux."', '".$height_aux."', '".$title_aux."', '".$resizable_aux."', '".$maximizable_aux."', '".$minimizable_aux."', '".$closable_aux."');";
        echo  "if(document.getElementById('guardar_id')){document.getElementById('guardar_id').disabled = true;} ";
        echo"</script>";
        $this->set('opcion', $var);
    }//fin function




    public function funcion_2($var1=null)
    {

        $this->layout = "ajax";

    }//fin function




    public function funcion_3($var1=null)
    {

        $this->layout = "ajax";

        echo"<script>";
        echo  "  if(document.getElementById('fecha_cambio2')){ ";
        echo  " 	 if(document.getElementById('motivo_cambio2').value!=''){ ";
        echo" if(document.getElementById('fecha_cambio2')){document.getElementById('fecha_cambio').value   = document.getElementById('fecha_cambio2').value;} ";
        echo" if(document.getElementById('motivo_cambio2')){document.getElementById('motivo_cambio').value = document.getElementById('motivo_cambio2').value;} ";
        echo  "if(document.getElementById('guardar_id')){document.getElementById('guardar_id').disabled = false;} ";
        echo" Windows.close(document.getElementById('capa_ventana').value);";
        echo  "      }else{      ";
        echo "var mensaje = 'FALTAN DATOS EN LOS MOTIVOS DEL CAMBIO';";
        echo "fun_msj(mensaje);";
        echo  "      }  ";
        echo  "  }  ";
        echo"</script>";

    }//fin function



}//fin de la clase controller
