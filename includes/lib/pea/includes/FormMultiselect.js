_Bbc(function($){/*a:inputname, b:values, c:options, d:parentvalue, e:contextobject, f:isAllwedNew, g:new title, h:addSelection*/function formMultiSelect(a,b,c,d,e,f,g,h){if(!h){return false}var s=document.createElement('select');var j="",k,l;$(s).attr("name",a).attr("class","form-control");for(var i=0;i<c.length;i++){if(c[i]["par_id"]==d){k=($.inArray(c[i]["id"],b)!=-1)?" selected":"";j+='<option value="'+c[i]["id"]+'"'+k+'>'+c[i]["title"]+'</option>'}};l=j!=""?true:false;if(f){if(!l){j+='<option value="" selected class="additional">---</option>'}j+='<option value="new_'+a+'" class="new">'+g+'</option>'}if(j!=""){$(s).html(j);$(e).append(s);$(e).append(" ");$(s).on("focus",function(){if($(this).val()!="new_"+a){$(this).data("value",$(this).get(0).selectedIndex)}}).on("change",function(){$(this).nextAll('select').remove();var o=$(this);var v=o.val();if(v=="new_"+a){$(this).prop("id","multiselect_active");$(".modal",e).off("show.bs.modal shown.bs.modal hide.bs.modal hidden.bs.modal").on("show.bs.modal",function(){$(".multiselect_new_input",$(this)).data("child","0").val('')}).on("shown.bs.modal",function(){$(".multiselect_new_input",$(this)).focus()}).on("hide.bs.modal",function(){$("#multiselect_active").removeAttr("id")}).on("hidden.bs.modal",function(){if($(".multiselect_new_input",$(this)).data("child")=="1"){formMultiSelect(a,b,c,v,e,f,"+++ new +++",true)}else{if($(o).get(0).selectedIndex!=$(o).data("value")){$(o).get(0).selectedIndex=$(o).data("value");$(o).trigger("change")}}}).modal("show")}else{if(v!=""){formMultiSelect(a,b,c,v,e,f,"+++ new +++",l)}}if(!l){$(s).focus()}}).trigger("change")}}$('.FormMultiselect_single_select').each(function(a){var a=$('select',$(this)).attr('name');var b=[];var d=$('.referenceArray',$(this));var c=eval('('+d.html()+')');$('select :selected',$(this)).each(function(i,j){b[i]=$(j).val()});if(d.hasClass("allow_new")){$(this).html('<div class="modal fade" tabindex="-1" role="dialog"><div class="modal-dialog modal-sm"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button><h4 class="modal-title">'+d.data("title")+'</h4></div><div class="modal-body"><div class="form-group"><input type="text" class="form-control multiselect_new_input" placeholder="insert new" /><div class="help-block">Press ENTER to submit new data, this data will only be saved if your new input is selected</div></div></div></div></div></div>')}else{$(this).html("")}formMultiSelect(a,b,c,"0",$(this),d.hasClass("allow_new"),d.data("title"),true)});$('.multiselect_new_input').on('keydown keypress',function(e){if(e.which==13){e.preventDefault();var a=$(this).val().trim();var b=$(this).closest('.modal');if(a!=""){$(".additional",$("#multiselect_active")).remove();$(".new",$("#multiselect_active")).before('<option value="new|'+a+'" class="additional">'+a+'</option>');$("#multiselect_active").val('new|'+a);$(this).data("child","1")};$(b).modal("hide")}})});