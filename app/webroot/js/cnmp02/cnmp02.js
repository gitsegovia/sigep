function valida_codigo_grado_cnmp02_tablas_grados_pasos(){
  if(document.getElementById('select_1').value==2){
    document.getElementById('id_grado_emp').style.display="none";
    document.getElementById('id_grado_obre').style.display="block";
  }else{
    document.getElementById('id_grado_emp').style.display="block";
    document.getElementById('id_grado_obre').style.display="none";
  }
}