/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


jQuery(document).ready(function(){
   
    
    jQuery('body,html').mousemove(function(e){
        e.preventDefault();
        e.stopPropagation();
        var split_href = window.location.href.split('/');
        jQuery.cookie('name_module',split_href[split_href.length-1],{path: '/' });
        //console.log(jQuery.cookie());
    });
    
});