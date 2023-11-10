<?
class ReportePanelRootController extends AppController{
	var $name = "reporte_panel_root";
	var $uses = array('v_estructura_organizacion_sisap');
    var $helpers = array('Html', 'Javascript', 'Ajax', 'Sisap', 'Fpdf');





function checkSession(){

				if (!$this->Session->check('Root_session')){
						$this->redirect('/root/salir/');
						exit();
				}else{
					if($this->Session->read('Root_session')!="VISION_INTEGRAL"){
						$this->redirect('/root/salir/');
						 exit();
					}
				}
}//fin checksession





function beforeFilter(){
    $this->checkSession();

 }





function estructura_organizacion_sisap($ir=null){
	if($ir=='si'){
		$this->layout = "ajax";
		$this->set('ir','si');
	}else if($ir=='no'){
		$this->layout = "pdf";
		$datos=$this->v_estructura_organizacion_sisap->execute("select cod_presi,denominacion_republica from v_estructura_organizacion_sisap group by cod_presi,denominacion_republica order by cod_presi,denominacion_republica");
		$datos1=$this->v_estructura_organizacion_sisap->execute("select cod_presi,cod_entidad,denominacion_estado from v_estructura_organizacion_sisap group by cod_presi,cod_entidad,denominacion_estado order by cod_presi,cod_entidad,denominacion_estado");
		$datos2=$this->v_estructura_organizacion_sisap->execute("select cod_presi,cod_entidad,cod_tipo_inst,denominacion_tipo_institucion from v_estructura_organizacion_sisap group by cod_presi,cod_entidad,cod_tipo_inst,denominacion_tipo_institucion order by cod_presi,cod_entidad,cod_tipo_inst,denominacion_tipo_institucion");
		$datos3=$this->v_estructura_organizacion_sisap->execute("select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,denominacion_institucion from v_estructura_organizacion_sisap group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,denominacion_institucion order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,denominacion_institucion");
		$datos4=$this->v_estructura_organizacion_sisap->execute("select cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,denominacion_dependencia from v_estructura_organizacion_sisap group by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,denominacion_dependencia order by cod_presi,cod_entidad,cod_tipo_inst,cod_inst,cod_dep,denominacion_dependencia");
		$this->set('datos',$datos);
		$this->set('datos1',$datos1);
		$this->set('datos2',$datos2);
		$this->set('datos3',$datos3);
		$this->set('datos4',$datos4);
		$this->set('ir','no');
	}//fin ir no


}//fin cimp01_clasificacion_funcional_bienes





}
?>
