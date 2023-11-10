<?php

//iniciamos el output buffer

ob_start();

include('./caop00_relacion_modificacion/caop00_relacion_modificacion.js'); echo "\r\r\n";
include('./caop02_solicitud_cotizacion/caop02_solicitud_cotizacion.js'); echo "\r\r\n";
include('./caop03_cotizacion_cuerpo/caop03_cotizacion_cuerpo.js'); echo "\r\r\n";
include('./caop04_ordencompra_modificacion/caop04_ordencompra_modificacion.js'); echo "\r\r\n";
include('./caop04_ordencompra_anticipo/caop04_ordencompra_anticipo.js'); echo "\r\r\n";
include('./caop04_ordencompra_autorizacion_pagos/caop04_ordencompra_autorizacion_pagos.js'); echo "\r\r\n";
include('./caop05_ordencompra_nota_entrega/caop05_ordencompra_nota_entrega.js'); echo "\r\r\n";

include('./cfpp00/cfpp00.js'); echo "\r\r\n";
include('./cfpp01/cfpp01.js'); echo "\r\r\n";
include('./cfpp01_formulacion/cfpp01_formulacion.js'); echo "\r\r\n";
include('./cfpp02/cfpp02.js'); echo "\r\r\n";
include('./cfpp03/cfpp03.js'); echo "\r\r\n";
include('./cfpp05auxiliar/cfpp05auxiliar.js'); echo "\r\r\n";
include('./cfpp05_asignacion_ingreso_gasto/cfpp05_asignacion_ingreso_gasto.js'); echo "\r\r\n";
include('./cfpp06/cfpp06.js'); echo "\r\r\n";
include('./cfpp07/cfpp07.js'); echo "\r\r\n";
include('./cfpp07_clasificacion_recurso/cfpp07_clasificacion_recurso.js'); echo "\r\r\n";
include('./cfpp07_plan_inversion/cfpp07_plan_inversion.js'); echo "\r\r\n";
include('./cfpp09_metas_proyecto/cfpp09_metas_proyecto.js'); echo "\r\r\n";
include('./cfpp09_metas_actividad/cfpp09_metas_actividad.js'); echo "\r\r\n";
include('./cfpp09_metas_sector/cfpp09_metas_sector.js'); echo "\r\r\n";
include('./cfpp09_metas_programa/cfpp09_metas_programa.js'); echo "\r\r\n";
include('./cfpp09_metas_sub_prog/cfpp09_metas_sub_prog.js'); echo "\r\r\n";
include('./cfpp08/cfpp08.js'); echo "\r\r\n";
include('./cfpp09/cfpp09.js'); echo "\r\r\n";
include('./cfpp10_reformulacion/cfpp10_reformulacion.js'); echo "\r\r\n";
include('./cfpp10_asiento_contable/cfpp10_asiento_contable.js'); echo "\r\r\n";
include('./cfpp10_reformulacion_clave_generico/cfpp10_reformulacion_clave_generico.js'); echo "\r\r\n";
include('./cfpp10_revision/cfpp10_revision.js'); echo "\r\r\n";
include('./cfpp15/cfpp15.js'); echo "\r\r\n";

include('./cugp01_republica/cugp01_republica.js'); echo "\r\r\n";
include('./cugp01_estados/cugp01_estados.js'); echo "\r\r\n";
include('./cugp01_municipios/cugp01_municipios.js'); echo "\r\r\n";
include('./cugp01_parroquias/cugp01_parroquias.js'); echo "\r\r\n";
include('./cugp01_centropoblados/cugp01_centropoblados.js'); echo "\r\r\n";
include('./cugp01_vereda/cugp01_vereda.js'); echo "\r\r\n";
include('./cugp01_vialidad/cugp01_vialidad.js'); echo "\r\r\n";
include('./cugp02_institucion/cugp02_institucion.js'); echo "\r\r\n";
include('./cugp02_dependencia/cugp02_dependencia.js'); echo "\r\r\n";
include('./cugp02_direccionsuperior/cugp02_direccionsuperior.js'); echo "\r\r\n";
include('./cugp02_coordinacion/cugp02_coordinacion.js'); echo "\r\r\n";
include('./cugp02_secretaria/cugp02_secretaria.js'); echo "\r\r\n";
include('./cugp02_direccion/cugp02_direccion.js'); echo "\r\r\n";
include('./cugp02_departamento/cugp02_departamento.js'); echo "\r\r\n";
include('./cugp02_oficina/cugp02_oficina.js'); echo "\r\r\n";
include('./cugp02_division/cugp02_division.js'); echo "\r\r\n";

include('./cscp01_snc_grupo/cscp01_snc_grupo.js'); echo "\r\r\n";
include('./cscp01_unidad_medida/cscp01_unidad_medida.js'); echo "\r\r\n";
include('./cscp02_solicitud_cotizacion/cscp02_solicitud_cotizacion.js'); echo "\r\r\n";
include('./cscp03_cotizacion_cuerpo/cscp03_cotizacion_cuerpo.js'); echo "\r\r\n";
include('./cscp04_ordencompra_parametros/cscp04_ordencompra_parametros.js'); echo "\r\r\n";
include('./cscp04_ordencompra_modificacion/cscp04_ordencompra_modificacion.js'); echo "\r\r\n";
include('./cscp04_ordencompra_anticipo/cscp04_ordencompra_anticipo.js'); echo "\r\r\n";
include('./cscp04_ordencompra_autorizacion_pagos/cscp04_ordencompra_autorizacion_pagos.js'); echo "\r\r\n";
include('./cscp05_ordencompra_nota_entrega/cscp05_ordencompra_nota_entrega.js'); echo "\r\r\n";
include('./cscp06_requisicion/cscp06_requisicion.js'); echo "\r\r\n";

include('./cstp01_entidades_bancarias/cstp01_entidades_bancarias.js'); echo "\r\r\n";
include('./cstp01_sucursales_bancarias/cstp01_sucursales_bancarias.js'); echo "\r\r\n";
include('./cstp02_cuentas_bancarias/cstp02_cuentas_bancarias.js'); echo "\r\r\n";
include('./cstp03_cheque_numero/cstp03_cheque_numero.js'); echo "\r\r\n";
include('./cstp03_movimientos_manuales/cstp03_movimientos_manuales.js'); echo "\r\r\n";
include('./cstp04_movimientos_generales/cstp04_movimientos_generales.js'); echo "\r\r\n";

include('./ccfp01_tipo/ccfp01_tipo.js'); echo "\r\r\n";
include('./ccfp01_cuenta/ccfp01_cuenta.js'); echo "\r\r\n";
include('./ccfp01_division/ccfp01_division.js'); echo "\r\r\n";
include('./ccfp01_subdivision/ccfp01_subdivision.js'); echo "\r\r\n";
include('./ccfp03instalacion/ccfp03instalacion.js'); echo "\r\r\n";

include('./cobp01_contratoobras_valuacion/cobp01_contratoobras_valuacion.js'); echo "\r\r\n";
include('./cobp01_contratoobras_retencion/cobp01_contratoobras_retencion.js'); echo "\r\r\n";
include('./cobp01_contratoobras/cobp01_contratoobras.js'); echo "\r\r\n";
include('./cobp01_contratoobras_valuacion_uso_general/cobp01_contratoobras_valuacion_uso_general.js'); echo "\r\r\n";
include('./cobp01_contratoobras_anticipo_uso_general/cobp01_contratoobras_anticipo_uso_general.js'); echo "\r\r\n";
include('./cobp01_contratoobras_valuacion_uso_general_iva/cobp01_contratoobras_valuacion_uso_general_iva.js'); echo "\r\r\n";

include('./cepp01/cepp01_tipo_documento.js'); echo "\r\r\n";
include('./cepp01/cnmp15_rango.js'); echo "\r\r\n";
include('./cepp01/cepp03_ordenpago.js'); echo "\r\r\n";
include('./cepp01/cnmp02_tablas_grados_pasos.js'); echo "\r\r\n";
include('./cepp01/cnmp10_escenarios_todos.js'); echo "\r\r\n";
include('./cepp01/cnmp10_escenarios.js'); echo "\r\r\n";
include('./cepp01_compromiso_beneficiario_cedula_rif/cepp01_compromiso_beneficiario_cedula_rif.js'); echo "\r\r\n";
include('./cepp02_contratoservicio_valuacion/cepp02_contratoservicio_valuacion.js'); echo "\r\r\n";
include('./cepp02_contratoservicio_retencion/cepp02_contratoservicio_retencion.js'); echo "\r\r\n";
include('./cepp02_contratoservicio/cepp02_contratoservicio.js'); echo "\r\r\n";
include('./cepp03_pagos_por_cancelar/cepp03_pagos_por_cancelar.js'); echo "\r\r\n";

include('./cnmp02/cnmp02.js'); echo "\r\r\n";
include('./cnmd03_partidas/cnmd03_partidas.js'); echo "\r\r\n";
include('./cnmp03transacciones/cnmp03transacciones.js'); echo "\r\r\n";
include('./cnmp04_tipo/cnmp04_tipo.js'); echo "\r\r\n";
include('./cnmp04_ocupacion/cnmp04_ocupacion.js'); echo "\r\r\n";
include('./cnmp06_experiencia_administrativa/cnmp06_experiencia_administrativa.js'); echo "\r\r\n";
include('./cnmp06_transaciones/cnmp06_transaciones.js'); echo "\r\r\n";
include('./cnmp06_profesiones/cnmp06_profesiones.js'); echo "\r\r\n";
include('./cnmp06_datos_personales/cnmp06_datos_personales.js'); echo "\r\r\n";
include('./cnmp06_ficha/cnmp06_ficha.js'); echo "\r\r\n";
include('./cnmp06_datos_amonestaciones/cnmp06_datos_amonestaciones.js'); echo "\r\r\n";
include('./cnmp06_soportes/cnmp06_soportes.js'); echo "\r\r\n";
include('./cnmp06_datos_bienes/cnmp06_datos_bienes.js'); echo "\r\r\n";
include('./cnmp07/cnmp07.js'); echo "\r\r\n";
include('./cnmp09_asignacion/cnmp09_asignacion.js'); echo "\r\r\n";
include('./cnmp09_deduccion/cnmp09_deduccion.js'); echo "\r\r\n";
include('./cnmp09_tqcs/cnmp09_tqcs.js'); echo "\r\r\n";
include('./cnmp09_tan/cnmp09_tan.js'); echo "\r\r\n";
include('./cnmp10_comunes52_semanas_porcentaje_ded/cnmp10_comunes52_semanas_porcentaje_ded.js'); echo "\r\r\n";
include('./cnmp10_aportes_patronales/cnmp10_aportes_patronales.js'); echo "\r\r\n";
include('./cnmp15_dias_antiguedad/cnmp15_dias_antiguedad.js'); echo "\r\r\n";
include('./cnmp15_devengado/cnmp15_devengado.js'); echo "\r\r\n";
include('./cnmp15_tasa_interes/cnmp15_tasa_interes.js'); echo "\r\r\n";
include('./cnmp15_tasa_interes/cnmp15_actualizar_escalas.js'); echo "\r\r\n";
include('./cnmp19_registro_asignacion_cargos/cnmp19_rac.js'); echo "\r\r\n";

include('./cimp01_bienes/cimp01_bienes.js'); echo "\r\r\n";;
include('./cimp01_operaciones/cimp01_operaciones.js'); echo "\r\r\n";
include('./cimp06_acta_firmantes/cimp06_acta_firmantes.js'); echo "\r\r\n";

include('./arrp00/arrp00.js'); echo "\r\r\n";

include('./cpcp01/cpcp01.js'); echo "\r\r\n";
include('./cpcp02/cpcp02.js'); echo "\r\r\n";

include('./funciones.js'); echo "\r\r\n";
include('./funciones_2.js'); echo "\r\r\n";
include('./funciones_3.js'); echo "\r\r\n";
include('./funciones_generales.js'); echo "\r\r\n";
include('./swfobject.js'); echo "\r\r\n";
include('./footer/reloj.js'); echo "\r\r\n";
include('./reporte/reporte.js'); echo "\r\r\n";
include('./reporte_hacienda2/reporte_hacienda2.js'); echo "\r\r\n";
include('./reporte3/reporte3.js'); echo "\r\r\n";
include('./reporte4/reporte4.js'); echo "\r\r\n";
include('./reporte_5/reporte5_informacion_impuesto_retenido.js'); echo "\r\r\n";
include('./actualizar_cnmp05_clasificacion/actualizar_cnmp05_clasificacion.js'); echo "\r\r\n";
include('./infogobierno/canp00_graficos.js'); echo "\r\r\n";
include('./infogobierno/canp00_reporte.js'); echo "\r\r\n";
include('./infogobierno/cnap00_reporte_valida_radio_nivel_consulta.js'); echo "\r\r\n";

include('./cpyp01/cpyp01.js'); echo "\r\r\n";

include('./catp01/catp01.js'); echo "\r\r\n";

include('./csrd01_solicitud_recurso/csrd01_solicitud_recurso.js'); echo "\r\r\n";
include('./csrp01_solicitud_recurso_aprobacion/csrp01_solicitud_recurso_aprobacion.js'); echo "\r\r\n";

include('./atencion_social/atencion_social.js'); echo "\r\r\n";

include('./ccnp01_concejo_comunal/ccnp01_concejo_comunal.js'); echo "\r\r\n";

include('./atencion_publico/atencion_publico.js'); echo "\r\r\n";

include('./cpop01_planmaestro/cpop01_planmaestro.js'); echo "\r\r\n";

include('./ciap01_inventario/ciap01_inventario.js'); echo "\r\r\n";

include('./crcp01/crcp01_actas_plantillas.js'); echo "\r\r\n";

include('./shp000_hacienda/hacienda.js'); echo "\r\r\n";
include('./shp000_hacienda/propaganda.js'); echo "\r\r\n";
include('./shp000_hacienda/convenio_pago.js'); echo "\r\r\n";
include('./shp002_cobranza_realizada/shp002_cobranza_realizada.js'); echo "\r\r\n";
include('./shp002_cobranza_pendiente/shp002_cobranza_pendiente.js'); echo "\r\r\n";
include('./shp100_patente/shp100_patente.js'); echo "\r\r\n";
include('./shp100_ordenanza/shp100_ordenanza.js'); echo "\r\r\n";

include('./cstp05_cheques_en_transito/cstp05_cheques_en_transito.js'); echo "\r\r\n";
include('./cstp06_comprobante_numero/cstp06_comprobante_numero.js'); echo "\r\r\n";
include('./cstp07_cancelaciones_iva/cstp07_cancelaciones_iva.js'); echo "\r\r\n";
include('./cstp07_cancelaciones_islr/cstp07_cancelaciones_islr.js'); echo "\r\r\n";
include('./cstp07_cancelaciones_timbre/cstp07_cancelaciones_timbre.js'); echo "\r\r\n";
include('./cstp07_cancelaciones_municipal/cstp07_cancelaciones_municipal.js'); echo "\r\r\n";
include('./cstp07_cancelaciones_multa/cstp07_cancelaciones_multa.js'); echo "\r\r\n";
include('./cstp07_cancelaciones_responsabilidad/cstp07_cancelaciones_responsabilidad.js'); echo "\r\r\n";
include('./cstp07_consulta_por_rendir/cstp07_consulta_por_rendir.js'); echo "\r\r\n";
include('./cstp09_notadebito_por_cancelar/cstp09_notadebito_por_cancelar.js'); echo "\r\r\n";
include('./cstp30_debito_islr/cstp30_debito_islr.js'); echo "\r\r\n";
include('./cstp30_debito_iva/cstp30_debito_iva.js'); echo "\r\r\n";

include('./cspp01/cspp01_evaluadores.js'); echo "\r\r\n";
include('./cspp01/cspp01_ejecutores.js'); echo "\r\r\n";
include('./cspp01/cspp01_areas.js'); echo "\r\r\n";
include('./cspp01/cspp01_reconocimiento.js'); echo "\r\r\n";
include('./cspp01/cspp02_datos_solicitante.js'); echo "\r\r\n";
include('./cspp01/cspp03_planteamientos.js'); echo "\r\r\n";

include('./fckconfig.js'); echo "\r\r\n";
include('./fckeditor.js'); echo "\r\r\n";

include('./cpop00/cpop01_filosofia_gestion.js'); echo "\r\r\n";
include('./cpop00/cpop01_proyectos.js'); echo "\r\r\n";
include('./cpop00/cpop02_recurso_humano.js'); echo "\r\r\n";
include('./cpop00/cpop03_organigrama.js'); echo "\r\r\n";
include('./cpop00/cpop04_objetivos.js'); echo "\r\r\n";
include('./cpop00/cpop04_problemas.js'); echo "\r\r\n";
include('./cpop00/cpop05_control_metas.js'); echo "\r\r\n";
include('./cpop00/cpop06_vinculacion_presupuesto.js'); echo "\r\r\n";
include('./cpop00/cpop06_distribucion_ingresos_propios.js'); echo "\r\r\n";

// modulo de tesoreria - cancelacion retenciones fiel cumplimiento y laboral
include('./cstp07_cancelaciones_fc/cstp07_cancelaciones_fc.js'); echo "\r\r\n";
include('./cstp07_cancelaciones_lab/cstp07_cancelaciones_lab.js'); echo "\r\r\n";

// modulo planilla liquidacion y recaudacion
include('./cstp10_planilla_recaudacion/cstp10_planilla_recaudacion.js'); echo "\r\r\n";

// modulo memoria y cuenta
include('./cmcp00/cmcp01_registro_trimestre.js'); echo "\r\r\n";

// Restringir registro de fichas activas nomina
include('./cnmp00/cnmp00_cierre_ficha.js'); echo "\r\r\n";


/**
 * Para agregar una nueva js... se copia y se pega alguna de las lineas que les sirva como ejemplo
 * no olvidar el (echo "\r\r\n";) al final de cada linea
 *
 */


//.............................
//obtenemos el output buffer
$contenido = ob_get_contents();
//guardamos el archivo
file_put_contents("js_sisap_comprimir.js",$contenido);
//volcamos por pantalla y cerramos el output buffer
ob_end_clean();
echo "<b>LISTO 1!</b>";
?>
