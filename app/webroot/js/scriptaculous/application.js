var Application =  {
  lastId: 0,
  currentSampleNb: 0,

  getNewId: function() {
    Application.lastId++;
    return "window_id_" + Application.lastId;
  },
  
  showCode: function(a, id) {
    a.innerHTML = $(id + "_codediv").visible() ? "View source" : "Hide source"
    $(id + "_codediv").toggle();
  },

  editCode: function(id) {
    var pre = $(id);
    // First time
    if (!pre.textarea) {
      var textarea = document.createElement("textarea");
      var dim = pre.getDimensions();
      textarea.setAttribute('id', id + "_edit");
      textarea.setAttribute('class', 'listing');
  		
  		pre.textarea = textarea
  		
  		pre.parentNode.insertBefore(textarea, pre);
    } 
    // Show text area
    if (pre.visible()) {
      var dim = pre.getDimensions();      
      
      pre.textarea.value = pre.innerHTML;
      pre.hide();
      pre.textarea.style.height = dim.height + "px"
      pre.textarea.style.width = "100%"
      pre.textarea.style.display = "block";
  
  		pre.textarea.focus();
  		$(id+'_edit_button').innerHTML = "Stop editing";
    }
    // Remove text area
    else {
      pre.update(pre.textarea.value);
      pre.textarea.style.display = "none";
      pre.show();
  		$(id+'_edit_button').innerHTML = "Edit Source";
    }
  },
  
  evalCode: function(id) {
    var pre = $(id);
    var code;
    if (pre.textarea && pre.textarea.visible)
      code = pre.textarea.value;
    else
      code = pre.innerHTML;
    
    code = code.gsub("&lt;", "<");
    code = code.gsub("&gt;", ">");
    
    try {
      eval(code);
    }
    catch (error) {
      Dialog.alert(" error accurs while interprating your javascript code <br/>" + error, {windowParameters: {width:300, showEffect:Element.show}, okLabel: "close"});
    }
  },

	addTitle: function(title, id) {
	  Application.currentSampleNb++;
	  idButton = id + '_click_button';
	  
	  document.write("<h2>" + Application.currentSampleNb + ". " + title + " (<span class='title'  id='" + idButton + "' href='#' onmouseover=\"$('" + idButton + "').addClassName('selected')\" onmouseout=\"$('" + idButton + "').removeClassName('selected')\" onclick=\"Application.evalCode('" + id + "', true)\">click here</span>)</h2>")
	},

	addShowButton: function(id) {
	  idButton = id + '_show_button';
	  document.write("<span class='title2' id='" + idButton + "' onmouseover=\"$('" + idButton + "').addClassName('selected')\" onmouseout=\"$('" + idButton + "').removeClassName('selected')\" onclick=\"Application.showCode(this, '" + id + "')\">View source</span>")
	},

	addEditButton: function(id) {
	  idButton = id + '_edit_button';
		html = "<p class='buttons'><span class='button' onmouseover=\"$('" + idButton + "').addClassName('selected')\" onmouseout=\"$('" + idButton + "').removeClassName('selected')\" id='" + idButton + "' onclick=\"Application.editCode('" + id + "')\">Edit Source</span> </p>";
    document.write(html)
	},
	
	addViewThemeButton: function(theme, modal) {
	  idButton = theme + '_theme_button';
		html = "<span class='title2' onmouseover=\"$('" + idButton + "').addClassName('selected')\" onmouseout=\"$('" + idButton + "').removeClassName('selected')\" id='" + idButton + "' onclick=\"Application.openThemeWindow('" + theme + "')\">View a window</span>";
    document.write(html)
	},
	
	addViewThemeDialogButton: function(theme, modal) {
	  idButton = theme + '_modal_theme_button';
		html = "<span class='title2' onmouseover=\"$('" + idButton + "').addClassName('selected')\" onmouseout=\"$('" + idButton + "').removeClassName('selected')\" id='" + idButton + "' onclick=\"Application.openThemeDialog('" + theme + "')\">Open a alert dialog</span>";
    document.write(html)
	},
	
	openThemeWindow: function(theme, modal) {
	  var win = new Window(Application.getNewId(), {className: theme, width:300, height:200, title: "Theme : " + theme});
	  win.getContent().innerHTML= "Lorem ipsum dolor sit amet, consectetur adipiscing elit, set eiusmod tempor incidunt et labore et dolore magna aliquam. Ut enim ad minim veniam, quis nostrud exerc.";
	  win.showCenter(modal);
	},
	
	openThemeDialog: function(theme, modal) {
    Dialog.alert("Lorem ipsum dolor sit amet, consectetur adipiscing elit, set eiusmod tempor incidunt et labore et dolore magna aliquam. Ut enim ad minim veniam, quis nostrud exerc", 
                {windowParameters: {className: theme, width:350}, okLabel: "close"});
	},
	
	insertDocOverview: function() {
	  var div = $('Overview');
	  var html = "<table class='overview''><tr>";
	  
	  // Window
	  html += "<td>Window Class <ul>";
	  $$(".window").each(function(element){html += "- <a href='#" + element.title + "'>" + element.title + "</a><br/>"});
	  html += "</ul></td>";
	  
	  // Dialog
	  html += "<td>Dialog Module <ul>";
	  $$(".dialogmodule").each(function(element){html += "- <a href='#" + element.title + "'>" + element.title + "</a><br/>"});
	  html += "</ul></td>";
	  
	  // Windows
	  html += "<td>Windows Module <ul>";
	  $$(".windows").each(function(element){html += "- <a href='#" + element.title + "'>" + element.title + "</a><br/>"});
	  html += "</ul></td>";
		  
	  // Windows
	  html += "<td>Windows Add-ons <ul>";
	  $$(".addons").each(function(element){html += "- <a href='#" + element.title + "'>" + element.title + "</a><br/>"});
	  html += "</ul></td>";
		  
	  html += "</tr></table>"
	  div.innerHTML = html;
	},
	
	insertNavigation: function(selected) {
	  document.write('\
	  <h1><a href="http://prototype-window.xilinus.com"><img border=0 src="pwc.gif"/></a></h1>\
    <div class="navigation">\
      <ul>\
        <li><a id="menu_introduction" href="index.html">Introduction</a></li>\
        <li><a id="menu_documentation" href="documentation.html">Documentation</a></li>\
        <li><a id="menu_samples" href="samples.html">Samples</a></li>\
        <li><a id="menu_themes" href="themes.html">Themes</a></li>\
        <li><a id="menu_debug" href="debug.html">Debug</a></li>\
        <li><a id="menu_download" href="download.html">Download</a></li>\
        <li><a id="menu_about" href="about.html">About</a></li>\
      </ul>\
    </div>');
    $('menu_' + selected).addClassName("selected");
	},
	
	addRightColumn: function() {
    document.write('\
    	  <div id="navigation">\
          <div id="nm">\
          <span style="color:#F37D1F; font-size:16px">My Personal Projects</span>\
    \
    <a href="http://www.jobalize.com"><img border=0 width=187 src="jobalize.jpg"/></a>\
    <a href="http://www.neomeeting.net"><img border=0 width=187 src="neomeeting.png"/></a>\
          Conduct live meeting over internet\
    \
          </div>\
          <div id="g">\
          <script type="text/javascript">\
          google_ad_client = "pub-3593675344652080";\
          google_ad_width = 120;\
          google_ad_height = 600;\
          google_ad_format = "120x600_as";\
          google_ad_type = "text_image";\
          google_ad_channel = "1401878792+2664779569+8025923418+4188663988+8193138037";\
          google_color_border = "fef1e2";\
          google_color_bg = "fef1e2";\
          google_color_link = "F37D1F";\
          google_color_text = "000000";\
          google_color_url = "ED2123";\
          </script>\
          <script type="text/javascript"\
            src="http://pagead2.googlesyndication.com/pagead/show_ads.js">\
          </script>\
          </div>\
        </div>\
    ');    
    
    setTimeout(Application.moveFrame, 100);
	},
	
	moveFrame: function() {
	  var f =$$("iframe")[0]; 
    if (f) {
      $("g").appendChild(f)
    }
    else
      setTimeout(Application.moveFrame, 100)
	}
}
