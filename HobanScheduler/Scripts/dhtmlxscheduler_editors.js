/*

@license
dhtmlxScheduler v.5.3.11 Professional Evaluation

This software is covered by DHTMLX Evaluation License. Contact sales@dhtmlx.com to get Commercial or Enterprise license. Usage without proper license is prohibited.

(c) XB Software Ltd.

*/
Scheduler.plugin(function(e){e.form_blocks.combo={render:function(e){e.cached_options||(e.cached_options={});var t="";return t+="<div class='"+e.type+"' style='height:"+(e.height||20)+"px;' ></div>"},set_value:function(t,a,n,i){!function(){function a(){if(t._combo&&t._combo.DOMParent){var e=t._combo;e.unload?e.unload():e.destructor&&e.destructor(),e.DOMParent=e.DOMelem=null}}a();var n=e.attachEvent("onAfterLightbox",function(){a(),e.detachEvent(n)})}(),window.dhx_globalImgPath=i.image_path||"/",
t._combo=new dhtmlXCombo(t,i.name,t.offsetWidth-8),i.onchange&&t._combo.attachEvent("onChange",i.onchange),i.options_height&&t._combo.setOptionHeight(i.options_height);var r=t._combo;if(r.enableFilteringMode(i.filtering,i.script_path||null,!!i.cache),i.script_path){var o=n[i.map_to];o?i.cached_options[o]?(r.addOption(o,i.cached_options[o]),r.disable(1),r.selectOption(0),r.disable(0)):e.$ajax.get(i.script_path+"?id="+o+"&uid="+e.uid(),function(t){var a,n=t.xmlDoc.responseText;try{
var s=JSON.parse(n);a=s.options[0].text}catch(n){var d=e.$ajax.xpath("//option",t.xmlDoc)[0];a=d.childNodes[0].nodeValue}i.cached_options[o]=a,r.addOption(o,a),r.disable(1),r.selectOption(0),r.disable(0)}):r.setComboValue("")}else{for(var s=[],d=0;d<i.options.length;d++){var _=i.options[d],l=[_.key,_.label,_.css];s.push(l)}if(r.addOption(s),n[i.map_to]){var c=r.getIndexByValue(n[i.map_to]);r.selectOption(c)}}},get_value:function(e,t,a){var n=e._combo.getSelectedValue()
;return a.script_path&&(a.cached_options[n]=e._combo.getSelectedText()),n},focus:function(e){}},e.form_blocks.radio={render:function(t){var a="";a+="<div class='dhx_cal_ltext dhx_cal_radio' style='height:"+t.height+"px;' >";for(var n=0;n<t.options.length;n++){var i=e.uid();a+="<input id='"+i+"' type='radio' name='"+t.name+"' value='"+t.options[n].key+"'><label for='"+i+"'> "+t.options[n].label+"</label>",t.vertical&&(a+="<br/>")}return a+="</div>"},set_value:function(e,t,a,n){
for(var i=e.getElementsByTagName("input"),r=0;r<i.length;r++){i[r].checked=!1;var o=a[n.map_to]||t;i[r].value==o&&(i[r].checked=!0)}},get_value:function(e,t,a){for(var n=e.getElementsByTagName("input"),i=0;i<n.length;i++)if(n[i].checked)return n[i].value},focus:function(e){}},e.form_blocks.checkbox={render:function(t){return e.config.wide_form?'<div class="dhx_cal_wide_checkbox" '+(t.height?"style='height:"+t.height+"px;'":"")+"></div>":""},set_value:function(t,a,n,i){
t=document.getElementById(i.id);var r=e.uid(),o=void 0!==i.checked_value?a==i.checked_value:!!a;t.className+=" dhx_cal_checkbox";var s="<input id='"+r+"' type='checkbox' value='true' name='"+i.name+"'"+(o?"checked='true'":"")+"'>",d="<label for='"+r+"'>"+(e.locale.labels["section_"+i.name]||i.name)+"</label>";if(e.config.wide_form?(t.innerHTML=d,t.nextSibling.innerHTML=s):t.innerHTML=s+d,i.handler){t.getElementsByTagName("input")[0].onclick=i.handler}},get_value:function(e,t,a){
e=document.getElementById(a.id);var n=e.getElementsByTagName("input")[0];return n||(n=e.nextSibling.getElementsByTagName("input")[0]),n.checked?a.checked_value||!0:a.unchecked_value||!1},focus:function(e){}}});