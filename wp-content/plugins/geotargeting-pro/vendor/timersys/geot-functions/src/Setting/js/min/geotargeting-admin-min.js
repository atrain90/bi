!function($){"use strict";$("document").ready(function(){function e(e){var t=e.data("counter"),n=$("#cities"+t),o=n.next(".chosen-container");o.find(".default").val("loading...."),$.post(geot.ajax_url,{action:"geot_cities_by_country",country:e.val()},function(e){n.html(e),n.trigger("chosen:updated"),o.find(".default").val("Choose one")})}$(".geot-chosen-select").chosen({width:"90%",no_results_text:"Oops, nothing found!"}),MutationObserver=window.MutationObserver||window.WebKitMutationObserver;var t=new MutationObserver(function(e){for(var t=0;t<e.length;t++)if($(e[t].target).is(".geot-chosen-select")){var n=$(e[t].target).parent(".geot-select2");n.find(".chosen-container").remove(),$(e[t].target).chosen({width:"90%",no_results_text:"Oops, nothing found!"})}});$(".acf-table").each(function(){t.observe($(this)[0],{subtree:!0,attributes:!0})}),$(".add-region").click(function(e){e.preventDefault();var t=$(this).prev(".region-group"),n=t.clone(),o=parseInt(t.data("id"))+1;n.find('input[type="text"]').attr("name","geot_settings[region]["+o+"][name]").val(""),n.find("select").attr("name","geot_settings[region]["+o+"][countries][]").find("option:selected").removeAttr("selected"),n.find(".chosen-container").remove(),n.insertAfter(t),$(".geot-chosen-select").chosen({width:"90%",no_results_text:"Oops, nothing found!"})}),$(".geot-settings").on("click",".remove-region",function(e){e.preventDefault();var t=$(this).parent(".region-group");t.remove()}),$(".add-city-region").click(function(t){t.preventDefault();var n=$(this).prev(".city-region-group"),o=n.clone(),i=o.find(".cities_container"),s=o.find(".country_ajax"),r=parseInt(n.data("id"))+1;o.find('input[type="text"]').attr("name","geot_settings[city_region]["+r+"][name]").val(""),s.attr("name","geot_settings[city_region]["+r+"][countries][]").find("option:selected").removeAttr("selected"),i.attr("name","geot_settings[city_region]["+r+"][cities][]").find("option:selected").removeAttr("selected"),o.find(".chosen-container").remove(),o.insertAfter(n),s.attr("data-counter",r),i.attr("id","cities"+r),i.chosen({width:"90%",no_results_text:"Oops, nothing found!"}),s.chosen({width:"90%",no_results_text:"Oops, nothing found!"}).on("change",function(){e(s)})}),$(".geot-settings").on("click",".remove-city-region",function(e){e.preventDefault();var t=$(this).parent(".city-region-group");t.remove()}),$(".country_ajax").on("change",function(){e($(this))}),$(document).on("widget-updated",function(){$(".geot-chosen-select").chosen({width:"90%",no_results_text:"Oops, nothing found!"})}),$(document).on("widget-added",function(e,t){$(t).find(".chosen-container").remove(),$(t).find(".geot-chosen-select").show().chosen({width:"90%",no_results_text:"Oops, nothing found!"})}),$(".check-license").on("click",function(e){e.preventDefault();var t=$(this),n=$("#license").val();t.prop("disabled",!0).addClass("btn-spinner"),$.ajax({url:ajaxurl,method:"POST",dataType:"json",data:{action:"geot_check_license",license:n},success:function(e){e.error&&($('<p style="color:red">'+e.error+"</p>").insertAfter(t).hide().fadeIn(),$("#license").removeClass("geot_license_valid")),e.success&&($('<p style="color:green">'+e.success+"</p>").insertAfter(t).hide().fadeIn(),$("#license").addClass("geot_license_valid")),t.prop("disabled",!1).removeClass("btn-spinner")}})})})}(jQuery);