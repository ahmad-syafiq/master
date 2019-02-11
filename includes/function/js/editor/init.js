function aceEditor(e){var t,n=JSON.parse(e.dataset.config),o=JSON.parse(e.dataset.options),i=e.dataset.id,a=e.dataset.syntax,c=JSON.parse(e.dataset.syntaxes);e.innerHTML=(t=document.getElementById("input_"+i).value,String(t).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;")),e.innerHTML=e.innerHTML.replace(/&nbsp;/g," "),document.getElementById("input_"+i).style.display="none";var d=n.maxLines||30,r=n.fullscreen||!1;delete n.fullscreen;var l=n.save_callback||null;delete n.save_callback;var s=n.readOnly||o.readOnly||!1;delete n.readOnly,delete o.readOnly;var m={mode:"ace/mode/php",wrap:!1,maxLines:30,minLines:r?2:30,autoScrollEditorIntoView:!0};n=Object.assign({},m,n),ace.require("ace/ext/emmet"),ace.require("ace/ext/language_tools");var u=ace.edit("editor_"+i,n);return u.setOptions(o),u.getSession().on("change",function(e){var t;document.getElementById("input_"+i).value=(t=u.getSession().getValue(),String(t).replace(/&quot;/g,'"').replace(/&gt;/g,">").replace(/&lt;/g,"<").replace(/&amp;/g,"&"))}),u.on("changeMode",function(e){var t=u.getOption("mode"),n={"ace/mode/css":{enableLiveAutocompletion:o.enableLiveAutocompletion||!0},"ace/mode/html":{enableEmmet:o.enableEmmet||!0},"ace/mode/php":{enableEmmet:o.enableEmmet||!0,inline:o.inline||!0},other:{enableBasicAutocompletion:o.enableBasicAutocompletion||!0,enableSnippets:o.enableSnippets||!0,enableLiveAutocompletion:o.enableLiveAutocompletion||!1,useSoftTabs:o.useSoftTabs||!1,showPrintMargin:o.showPrintMargin||!1,tabSize:o.tabSize||2}};if(void 0!==n[t])var a=Object.assign({},n.other,n[t]);else a=n.other;u.setOptions(a)}),u.commands.addCommands([{name:"showSettingsMenu",bindKey:"F1",exec:function(e){e.cfg={theme:n.theme,syntax:a,syntaxes:c.map(function(e){return"ace/mode/"+e})},ace.require("ace/ext/settings_menu").init(e),e.showSettingsMenu()},readOnly:!0}]),u.commands.addCommand({name:"showFullscreen",bindKey:"F2",exec:function(e){var t=ace.require("ace/lib/dom"),n=t.toggleCssClass(document.body,"fullScreen"),a=n?"auto":d;t.setCssClass(e.container,"fullScreen",n),e.setAutoScrollEditorIntoView(!n),e.setOptions({maxLines:a}),e.resize()}}),r&&(u.execCommand("showFullscreen"),u.focus()),s&&u.setOptions({readOnly:!0}),u.commands.addCommand({name:"submitForm",bindKey:{win:"F3|Ctrl-S",mac:"F3|Command-S"},exec:function(e){var t=!0;if(l&&"function"==typeof window[l]&&(window[l](),t=!1),t){var n=document.getElementById("input_"+i).closest("form"),a=n.querySelectorAll('[type="submit"]');0<a.length?a[0].click():n.submit()}}}),window.editAreaLoader={},window.editAreaLoader.getValue=function(e){return document.getElementById("input_"+e).value},u}!function(e,t){"use strict";e=e||"docReady",t=t||window;var n=[],a=!1,o=!1;function i(){if(!a){a=!0;for(var e=0;e<n.length;e++)n[e].fn.call(window,n[e].ctx);n=[]}}function c(){"complete"===document.readyState&&i()}t[e]=function(e,t){if("function"!=typeof e)throw new TypeError("callback for docReady(fn) must be a function");a?setTimeout(function(){e(t)},1):(n.push({fn:e,ctx:t}),"complete"===document.readyState||!document.attachEvent&&"interactive"===document.readyState?setTimeout(i,1):o||(document.addEventListener?(document.addEventListener("DOMContentLoaded",i,!1),window.addEventListener("load",i,!1)):(document.attachEvent("onreadystatechange",c),window.attachEvent("onload",i)),o=!0))}}("docReady",window),docReady(function(){ace.require("ace/lib/dom").importCssString(".ace_editor.fullScreen{height:auto!important;width:auto;border:0;margin:0;position:fixed!important;top:0;bottom:0;left:0;right:0;z-index:10;} .fullScreen{overflow:hidden}")});