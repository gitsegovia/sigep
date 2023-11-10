<?php
if (function_exists('ob_gzhandler') && !ini_get('zlib.output_compression')){
ini_set('zlib.output_compression_level', 3);
ob_start("ob_gzhandler");
}else @ob_start();

header("Content-type: text/javascript; charset: utf-8");
header("Cache-Control: must-revalidate");
$offset = 600 * 60 ;
$ExpStr = "Expires:" . gmdate("D, d M Y H:i:s",time() + $offset) . "GMT";
header($ExpStr);
?>
function SoundManager(_1,_2){
var _3=this;
this.version="V2.0b.20070415";
this.url=(_1||"/include/soundmanager2.swf");
this.debugMode=false;
this.useConsole=false;
this.consoleOnly=true;
this.nullURL="data/null.mp3";
this.defaultOptions={"autoLoad":false,"stream":true,"autoPlay":false,"onid3":null,"onload":null,"whileloading":null,"onplay":null,"whileplaying":null,"onstop":null,"onfinish":null,"onbeforefinish":null,"onbeforefinishtime":5000,"onbeforefinishcomplete":null,"onjustbeforefinish":null,"onjustbeforefinishtime":200,"multiShot":true,"pan":0,"volume":100};
this.allowPolling=true;
this.enabled=false;
this.o=null;
this.id=(_2||"sm2movie");
this.oMC=null;
this.sounds=[];
this.soundIDs=[];
this.isIE=(navigator.userAgent.match(/MSIE/));
this.isSafari=(navigator.userAgent.match(/safari/i));
this.debugID="soundmanager-debug";
this._debugOpen=true;
this._didAppend=false;
this._appendSuccess=false;
this._didInit=false;
this._disabled=false;
this._hasConsole=(typeof console!="undefined"&&typeof console.log!="undefined");
this._debugLevels=!_3.isSafari?["debug","info","warn","error"]:["log","log","log","log"];
this.getMovie=function(_4){
return _3.isIE?window[_4]:(_3.isSafari?document[_4+"-embed"]:document.getElementById(_4+"-embed"));
};
this.loadFromXML=function(_5){
try{
_3.o._loadFromXML(_5);
}
catch(e){
_3._failSafely();
return true;
}
};
this.createSound=function(_6){
if(!_3._didInit){
throw new Error("soundManager.createSound(): Not loaded yet - wait for soundManager.onload() before calling sound-related methods");
}
if(arguments.length==2){
_6={"id":arguments[0],"url":arguments[1]};
}
var _7=_3._mergeObjects(_6);
_3._writeDebug("soundManager.createSound(): \"<a href=\"#\" onclick=\"soundManager.play('"+_7.id+"');return false\" title=\"play this sound\">"+_7.id+"</a>\" ("+_7.url+")",1);
if(_3._idCheck(_7.id,true)){
_3._writeDebug("sound "+_7.id+" already defined - exiting",2);
return false;
}
_3.sounds[_7.id]=new SMSound(_3,_7);
_3.soundIDs[_3.soundIDs.length]=_7.id;
try{
_3.o._createSound(_7.id,_7.onjustbeforefinishtime);
}
catch(e){
_3._failSafely();
return true;
}
if(_7.autoLoad||_7.autoPlay){
_3.sounds[_7.id].load(_7);
}
if(_7.autoPlay){
_3.sounds[_7.id].playState=1;
}
};
this.destroySound=function(_8){
if(!_3._idCheck(_8)){
return false;
}
for(var i=_3.soundIDs.length;i--;){
if(_3.soundIDs[i]==_8){
delete _3.soundIDs[i];
continue;
}
}
_3.sounds[_8].unload();
delete _3.sounds[_8];
};
this.load=function(_a,_b){
if(!_3._idCheck(_a)){
return false;
}
_3.sounds[_a].load(_b);
};
this.unload=function(_c){
if(!_3._idCheck(_c)){
return false;
}
_3.sounds[_c].unload();
};
this.play=function(_d,_e){
if(!_3._idCheck(_d)){
if(typeof _e!="Object"){
_e={url:_e};
}
if(_e&&_e.url){
_3._writeDebug("soundController.play(): attempting to create \""+_d+"\"",1);
_e.id=_d;
_3.createSound(_e);
}else{
return false;
}
}
_3.sounds[_d].play(_e);
};
this.start=this.play;
this.setPosition=function(_f,_10){
if(!_3._idCheck(_f)){
return false;
}
_3.sounds[_f].setPosition(_10);
};
this.stop=function(sID){
if(!_3._idCheck(sID)){
return false;
}
_3._writeDebug("soundManager.stop("+sID+")",1);
_3.sounds[sID].stop();
};
this.stopAll=function(){
_3._writeDebug("soundManager.stopAll()",1);
for(var _12 in _3.sounds){
if(_3.sounds[_12] instanceof SMSound){
_3.sounds[_12].stop();
}
}
};
this.pause=function(sID){
if(!_3._idCheck(sID)){
return false;
}
_3.sounds[sID].pause();
};
this.resume=function(sID){
if(!_3._idCheck(sID)){
return false;
}
_3.sounds[sID].resume();
};
this.togglePause=function(sID){
if(!_3._idCheck(sID)){
return false;
}
_3.sounds[sID].togglePause();
};
this.setPan=function(sID,_17){
if(!_3._idCheck(sID)){
return false;
}
_3.sounds[sID].setPan(_17);
};
this.setVolume=function(sID,_19){
if(!_3._idCheck(sID)){
return false;
}
_3.sounds[sID].setVolume(_19);
};
this.setPolling=function(_1a){
if(!_3.o||!_3.allowPolling){
return false;
}
_3._writeDebug("soundManager.setPolling("+_1a+")");
_3.o._setPolling(_1a);
};
this.disable=function(){
if(_3._disabled){
return false;
}
_3._disabled=true;
_3._writeDebug("soundManager.disable(): Disabling all functions - future calls will return false.",1);
for(var i=_3.soundIDs.length;i--;){
_3._disableObject(_3.sounds[_3.soundIDs[i]]);
}
_3.initComplete();
_3._disableObject(_3);
};
this.getSoundById=function(sID,_1d){
if(!sID){
throw new Error("SoundManager.getSoundById(): sID is null/undefined");
}
var _1e=_3.sounds[sID];
if(!_1e&&!_1d){
_3._writeDebug("\""+sID+"\" is an invalid sound ID.",2);
}
return _1e;
};
this.onload=function(){
soundManager._writeDebug("<em>Warning</em>: soundManager.onload() is undefined.",2);
};
this.onerror=function(){
};
this._idCheck=this.getSoundById;
this._disableObject=function(o){
for(var _20 in o){
if(typeof o[_20]=="function"&&typeof o[_20]._protected=="undefined"){
o[_20]=function(){
return false;
};
}
}
_20=null;
};
this._failSafely=function(){
var _21="http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager04.html";
var _22="You may need to whitelist this location/domain eg. file:///C:/ or C:/ or mysite.com, or set ALWAYS ALLOW under the Flash Player Global Security Settings page. Note that this seems to apply only to file system viewing.";
var _23="<a href=\""+_21+"\" title=\""+_22+"\">view/edit</a>";
var _24="<a href=\""+_21+"\" title=\"Flash Player Global Security Settings\">FPGSS</a>";
if(!_3._disabled){
_3._writeDebug("soundManager: JS-&gt;Flash communication failed. Possible causes: flash/browser security restrictions ("+_23+"), insufficient browser/plugin support, or .swf not found",2);
_3._writeDebug("Verify that the movie path of <em>"+_3.url+"</em> is correct (<a href=\""+_3.url+"\" title=\"If you get a 404/not found, fix it!\">test link</a>)",1);
if(_3._didAppend){
if(!document.domain){
_3._writeDebug("Loading from local file system? (document.domain appears to be null, this URL path may need to be added to 'trusted locations' in "+_24+")",1);
_3._writeDebug("Possible security/domain restrictions ("+_23+"), should work when served by http on same domain",1);
}
}
_3.disable();
}
};
this._createMovie=function(_25,_26){
if(_3._didAppend&&_3._appendSuccess){
return false;
}
if(window.location.href.indexOf("debug=1")+1){
_3.debugMode=true;
}
_3._didAppend=true;
var _27=["<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"16\" height=\"16\" id=\""+_25+"\"><param name=\"movie\" value=\""+_26+"\"><param name=\"quality\" value=\"high\"><param name=\"allowScriptAccess\" value=\"always\" /></object>","<embed name=\""+_25+"-embed\" id=\""+_25+"-embed\" src=\""+_26+"\" width=\"1\" height=\"1\" quality=\"high\" allowScriptAccess=\"always\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed>"];
var _28="<div id=\""+_3.debugID+"-toggle\" style=\"position:fixed;_position:absolute;right:0px;bottom:0px;_top:0px;width:1.2em;height:1.2em;line-height:1.2em;margin:2px;padding:0px;text-align:center;border:1px solid #999;cursor:pointer;background:#fff;color:#333;z-index:706\" title=\"Toggle SM2 debug console\" onclick=\"soundManager._toggleDebug()\">-</div>";
var _29="<div id=\""+_3.debugID+"\" style=\"display:"+(_3.debugMode&&((!_3._hasConsole||!_3.useConsole)||(_3.useConsole&&_3._hasConsole&&!_3.consoleOnly))?"block":"none")+";opacity:0.85\"></div>";
var _2a="soundManager._createMovie(): appendChild/innerHTML set failed. Serving application/xhtml+xml MIME type? Browser may be enforcing strict rules, not allowing write to innerHTML. (PS: If so, this means your commitment to XML validation is going to break stuff now, because this part isn't finished yet. ;))";
var _2b="<div style=\"position:absolute;left:-256px;top:-256px;width:1px;height:1px;display:none;z-index:-1\" class=\"movieContainer\" >"+_27[_3.isIE?0:1]+"</div>"+(_3.debugMode&&((!_3._hasConsole||!_3.useConsole)||(_3.useConsole&&_3._hasConsole&&!_3.consoleOnly))&&!document.getElementById(_3.debugID)?"x"+_29+_28:"");
var _2c=(document.body?document.body:document.getElementsByTagName("div")[0]);
if(_2c){
_3.oMC=document.createElement("div");
_3.oMC.className="movieContainer";
_3.oMC.style.position="absolute";
_3.oMC.style.top="-100px";
_3.oMC.style.width="0";
_3.oMC.style.height="0";
try{
_2c.appendChild(_3.oMC);
_3.oMC.innerHTML=_27[_3.isIE?0:1];
_3._appendSuccess=true;
}
catch(e){
throw new Error(_2a);
}
if(!document.getElementById(_3.debugID)&&((!_3._hasConsole||!_3.useConsole)||(_3.useConsole&&_3._hasConsole&&!_3.consoleOnly))){
var _2d=document.createElement("div");
_2d.id=_3.debugID;
_2d.style.display=(_3.debugMode?"block":"none");
if(_3.debugMode){
try{
var oD=document.createElement("div");
_2c.appendChild(oD);
oD.innerHTML=_28;
}
catch(e){
throw new Error(_2a);
}
}
_2c.appendChild(_2d);
}
_2c=null;
}
_3._writeDebug("-- SoundManager 2 Version "+_3.version.substr(1)+" --",1);
_3._writeDebug("soundManager._createMovie(): trying to load <a href=\""+_26+"\" title=\"Test this link (404=bad)\">"+_26+"</a>",1);
};
this._writeDebug=function(_2f,_30){
if(!_3.debugMode){
return false;
}
if(_3._hasConsole&&_3.useConsole){
console[_3._debugLevels[_30]||"log"](_2f);
if(_3.useConsoleOnly){
return true;
}
}
var _31="soundmanager-debug";
try{
var o=document.getElementById(_31);
if(!o){
return false;
}
var p=document.createElement("div");
p.innerHTML=_2f;
o.insertBefore(p,o.firstChild);
}
catch(e){
}
o=null;
};
this._writeDebug._protected=true;
this._writeDebugAlert=function(_34){
alert(_34);
};
if(window.location.href.indexOf("debug=alert")+1){
_3.debugMode=true;
_3._writeDebug=_3._writeDebugAlert;
}
this._toggleDebug=function(){
var o=document.getElementById(_3.debugID);
var oT=document.getElementById(_3.debugID+"-toggle");
if(!o){
return false;
}
if(_3._debugOpen){
oT.innerHTML="+";
o.style.display="none";
}else{
oT.innerHTML="-";
o.style.display="block";
}
_3._debugOpen=!_3._debugOpen;
};
this._toggleDebug._protected=true;
this._debug=function(){
_3._writeDebug("soundManager._debug(): sounds by id/url:",0);
for(var i=0,j=_3.soundIDs.length;i<j;i++){
_3._writeDebug(_3.sounds[_3.soundIDs[i]].sID+" | "+_3.sounds[_3.soundIDs[i]].url,0);
}
};
this._mergeObjects=function(_39,_3a){
var o1=_39;
var o2=(typeof _3a=="undefined"?_3.defaultOptions:_3a);
for(var o in o2){
if(typeof o1[o]=="undefined"){
o1[o]=o2[o];
}
}
return o1;
};
this.createMovie=function(_3e){
if(_3e){
_3.url=_3e;
}
_3._initMovie();
};
this._initMovie=function(){
if(_3.o){
return false;
}
_3.o=_3.getMovie(_3.id);
if(!_3.o){
_3._createMovie(_3.id,_3.url);
_3.o=_3.getMovie(_3.id);
}
if(_3.o){
_3._writeDebug("soundManager._initMovie(): Got "+_3.o.nodeName+" element ("+(_3._didAppend?"created via JS":"static HTML")+")",1);
}
};
this.initComplete=function(){
if(_3._didInit){
return false;
}
_3._didInit=true;
_3._writeDebug("-- SoundManager 2 "+(_3._disabled?"failed to load":"loaded")+" ("+(_3._disabled?"security/load error":"OK")+") --",1);
if(_3._disabled){
_3._writeDebug("soundManager.initComplete(): calling soundManager.onerror()",1);
_3.onerror.apply(window);
return false;
}
_3._writeDebug("soundManager.initComplete(): calling soundManager.onload()",1);
try{
_3.onload.apply(window);
}
catch(e){
_3._writeDebug("soundManager.onload() threw an exception: "+e.message,2);
throw e;
}
_3._writeDebug("soundManager.onload() complete",1);
};
this.init=function(){
if(window.removeEventListener){
window.removeEventListener("load",_3.beginInit,false);
}else{
if(window.detachEvent){
window.detachEvent("onload",_3.beginInit);
}
}
try{
_3.o._externalInterfaceTest();
_3._writeDebug("Flash ExternalInterface call (JS -&gt; Flash) succeeded.",1);
if(!_3.allowPolling){
_3._writeDebug("Polling (whileloading/whileplaying support) is disabled.",1);
}
_3.setPolling(true);
_3.enabled=true;
}
catch(e){
_3._failSafely();
_3.initComplete();
return false;
}
_3.initComplete();
};
this.beginDelayedInit=function(){
setTimeout(_3.beginInit,200);
};
this.beginInit=function(){
_3.createMovie();
_3._initMovie();
setTimeout(_3.init,1000);
};
this.destruct=function(){
if(_3.isSafari){
for(var i=_3.soundIDs.length;i--;){
if(_3.sounds[_3.soundIDs[i]].readyState==1){
_3.sounds[_3.soundIDs[i]].unload();
}
}
}
_3.disable();
};
}
function SMSound(oSM,_41){
var _42=this;
var sm=oSM;
this.sID=_41.id;
this.url=_41.url;
this.options=sm._mergeObjects(_41);
this.id3={};
_42.resetProperties=function(_44){
_42.bytesLoaded=null;
_42.bytesTotal=null;
_42.position=null;
_42.duration=null;
_42.durationEstimate=null;
_42.loaded=false;
_42.loadSuccess=null;
_42.playState=0;
_42.paused=false;
_42.readyState=0;
_42.didBeforeFinish=false;
_42.didJustBeforeFinish=false;
};
_42.resetProperties();
this.load=function(_45){
_42.loaded=false;
_42.loadSuccess=null;
_42.readyState=1;
_42.playState=(_45.autoPlay||false);
var _46=sm._mergeObjects(_45);
if(typeof _46.url=="undefined"){
_46.url=_42.url;
}
try{
sm._writeDebug("loading "+_46.url,1);
sm.o._load(_42.sID,_46.url,_46.stream,_46.autoPlay,_46.whileloading?1:0);
}
catch(e){
sm._writeDebug("SMSound().load(): JS-&gt;Flash communication failed.",2);
}
};
this.unload=function(){
sm._writeDebug("SMSound().unload(): \""+_42.sID+"\"");
_42.setPosition(0);
sm.o._unload(_42.sID,sm.nullURL);
_42.resetProperties();
};
this.play=function(_47){
if(!_47){
_47={};
}
if(_47.onfinish){
_42.options.onfinish=_47.onfinish;
}
if(_47.onbeforefinish){
_42.options.onbeforefinish=_47.onbeforefinish;
}
if(_47.onjustbeforefinish){
_42.options.onjustbeforefinish=_47.onjustbeforefinish;
}
var _48=sm._mergeObjects(_47);
if(_42.playState==1){
var _49=_48.multiShot;
if(!_49){
sm._writeDebug("SMSound.play(): \""+_42.sID+"\" already playing? (one-shot)",1);
return false;
}else{
sm._writeDebug("SMSound.play(): \""+_42.sID+"\" already playing (multi-shot)",1);
}
}
if(!_42.loaded){
if(_42.readyState==0){
sm._writeDebug("SMSound.play(): .play() before load request. Attempting to load \""+_42.sID+"\"",1);
_48.stream=true;
_48.autoPlay=true;
_42.load(_48);
}else{
if(_42.readyState==2){
sm._writeDebug("SMSound.play(): Could not load \""+_42.sID+"\" - exiting",2);
return false;
}else{
sm._writeDebug("SMSound.play(): \""+_42.sID+"\" is loading - attempting to play..",1);
}
}
}else{
sm._writeDebug("SMSound.play(): \""+_42.sID+"\"");
}
if(_42.paused){
_42.resume();
}else{
_42.playState=1;
_42.position=(_48.offset||0);
if(_48.onplay){
_48.onplay.apply(_42);
}
_42.setVolume(_48.volume);
_42.setPan(_48.pan);
if(!_48.autoPlay){
sm.o._start(_42.sID,_48.loop||1,_42.position);
}
}
};
this.start=this.play;
this.stop=function(_4a){
if(_42.playState==1){
_42.playState=0;
_42.paused=false;
if(sm.defaultOptions.onstop){
sm.defaultOptions.onstop.apply(_42);
}
sm.o._stop(_42.sID);
}
};
this.setPosition=function(_4b){
sm.o._setPosition(_42.sID,_4b/1000,_42.paused||!_42.playState);
};
this.pause=function(){
if(_42.paused){
return false;
}
sm._writeDebug("SMSound.pause()");
_42.paused=true;
sm.o._pause(_42.sID);
};
this.resume=function(){
if(!_42.paused){
return false;
}
sm._writeDebug("SMSound.resume()");
_42.paused=false;
sm.o._pause(_42.sID);
};
this.togglePause=function(){
sm._writeDebug("SMSound.togglePause()");
if(!_42.playState){
_42.play({offset:_42.position/1000});
return false;
}
if(_42.paused){
sm._writeDebug("SMSound.togglePause(): resuming..");
_42.resume();
}else{
sm._writeDebug("SMSound.togglePause(): pausing..");
_42.pause();
}
};
this.setPan=function(_4c){
if(typeof _4c=="undefined"){
_4c=0;
}
sm.o._setPan(_42.sID,_4c);
_42.options.pan=_4c;
};
this.setVolume=function(_4d){
if(typeof _4d=="undefined"){
_4d=100;
}
sm.o._setVolume(_42.sID,_4d);
_42.options.volume=_4d;
};
this._whileloading=function(_4e,_4f,_50){
_42.bytesLoaded=_4e;
_42.bytesTotal=_4f;
_42.duration=_50;
_42.durationEstimate=parseInt((_42.bytesTotal/_42.bytesLoaded)*_42.duration);
if(_42.readyState!=3&&_42.options.whileloading){
_42.options.whileloading.apply(_42);
}
};
this._onid3=function(_51,_52){
sm._writeDebug("SMSound()._onid3(): \""+this.sID+"\" ID3 data received.");
var _53=[];
for(var i=0,j=_51.length;i<j;i++){
_53[_51[i]]=_52[i];
}
_42.id3=sm._mergeObjects(_42.id3,_53);
if(_42.options.onid3){
_42.options.onid3.apply(_42);
}
};
this._whileplaying=function(_56){
if(isNaN(_56)||_56==null){
return false;
}
_42.position=_56;
if(_42.playState==1){
if(_42.options.whileplaying){
_42.options.whileplaying.apply(_42);
}
if(_42.loaded&&_42.options.onbeforefinish&&_42.options.onbeforefinishtime&&!_42.didBeforeFinish&&_42.duration-_42.position<=_42.options.onbeforefinishtime){
sm._writeDebug("duration-position &lt;= onbeforefinishtime: "+_42.duration+" - "+_42.position+" &lt= "+_42.options.onbeforefinishtime+" ("+(_42.duration-_42.position)+")");
_42._onbeforefinish();
}
}
};
this._onload=function(_57){
_57=(_57==1?true:false);
sm._writeDebug("SMSound._onload(): \""+_42.sID+"\""+(_57?" loaded.":" failed to load (or loaded from cache - weird bug) - [<a href=\""+_42.url+"\">test URL</a>]"));
_42.loaded=_57;
_42.loadSuccess=_57;
_42.readyState=_57?3:2;
if(_42.options.onload){
_42.options.onload.apply(_42);
}
};
this._onbeforefinish=function(){
if(!_42.didBeforeFinish){
_42.didBeforeFinish=true;
if(_42.options.onbeforefinish){
_42.options.onbeforefinish.apply(_42);
}
}
};
this._onjustbeforefinish=function(_58){
if(!_42.didJustBeforeFinish){
_42.didJustBeforeFinish=true;
if(_42.options.onjustbeforefinish){
_42.options.onjustbeforefinish.apply(_42);
}
}
};
this._onfinish=function(){
sm._writeDebug("SMSound._onfinish(): \""+_42.sID+"\"");
_42.playState=0;
_42.paused=false;
if(_42.options.onfinish){
_42.options.onfinish.apply(_42);
}
if(_42.options.onbeforefinishcomplete){
_42.options.onbeforefinishcomplete.apply(_42);
}
_42.setPosition(0);
_42.didBeforeFinish=false;
_42.didJustBeforeFinish=false;
};
}
var soundManager=new SoundManager();
if(window.addEventListener){
window.addEventListener("load",soundManager.beginDelayedInit,false);
window.addEventListener("beforeunload",soundManager.destruct,false);
}else{
if(window.attachEvent){
window.attachEvent("onload",soundManager.beginInit);
window.attachEvent("beforeunload",soundManager.destruct);
}else{
soundManager.onerror();
soundManager.disable();
}
}

