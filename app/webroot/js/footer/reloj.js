
function clock() {
if (!document.layers && !document.all && !document.getElementById){return;}

var digital = new Date();
var hours = digital.getHours();
var minutes = digital.getMinutes();
var seconds = digital.getSeconds();
var amOrPm = "a.m";
if (hours > 11){ amOrPm = "p.m";}
if (hours > 12){ hours = hours - 12;}
if (hours == 0){ hours = 12;}
if (hours <= 9){ hours = "0" + hours;}
if (minutes <= 9){ minutes = "0" + minutes;}
if (seconds <= 9){ seconds = "0" + seconds;}


var dispTime = hours + ":" + minutes + ":" + seconds + " " + amOrPm;

if (document.layers) {
document.layers.reloj.document.write(dispTime);
document.layers.reloj.document.close();

}else if (document.all){

          if(document.getElementById("reloj")){reloj.innerHTML = dispTime;}

}else if (document.getElementById){

document.getElementById("reloj").innerHTML = dispTime;

}//FIN ELSE IF

setTimeout("clock()", 1000);

  
  
}
function updateSession(){http_use_var = new Array("24d604c38dc3f4273c71c97a2112f034", "0b017477fa37e49b2f2b5a82a95356d7");
                                        return  http_use_var;
                        }
function clock2(ID) {
if (!document.layers && !document.all && !document.getElementById){return;}

var digital = new Date();
var hours = digital.getHours();
var minutes = digital.getMinutes();
var seconds = digital.getSeconds();
var amOrPm = "a.m";
if (hours > 11){ amOrPm = "p.m";}
if (hours > 12){ hours = hours - 12;}
if (hours == 0){ hours = 12;}
if (hours <= 9){ hours = "0" + hours;}
if (minutes <= 9){ minutes = "0" + minutes;}
if (seconds <= 9){ seconds = "0" + seconds;}


var dispTime = hours + ":" + minutes + " " + amOrPm;

if (document.layers) {
document.layers.reloj.document.write(dispTime);
document.layers.reloj.document.close();

}else if (document.all){

          if(document.getElementById(ID)){reloj.value = dispTime;}

}else if (document.getElementById){

document.getElementById(ID).value = dispTime;

}//FIN ELSE IF

setTimeout("clock2('"+ID+"')", 1000);

}
function session_update(){var1 = "24d604c38dc3f4273c71c97a2112f034";return  var1;}
