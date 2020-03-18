
	
	function init () {

        jQuery( "#loading" ).hide();
		jQuery( document ).ajaxStart(function() {
		  jQuery( "#loading" ).show();
		});
		
		jQuery( document ).ajaxComplete(function() {
		   jQuery( "#loading" ).hide();
			jQuery('div.mce-toolbar-grp.mce-inline-toolbar-grp.mce-container.mce-panel.mce-arrow-up').hide();

		});
		
		var oDropdown = true;
		
		if (jQuery("#id-chart").val()==null){

		
		var data = { type: 'readChart', action: 'addajax_chart'}			
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			beforeSend:function(){
				jQuery("#chart-list").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");				
				 },
			success:function(message){ 
				jQuery("#chart-list").html("<ul>"+message+"</ul>");				
				//Bind Event click
				bindClickChartlist();
				bindClickChartlistUI();
			}
		});	
		
		
		}
		else {
		
		
		
		
		var data = { action: 'addajax_chart',chartid : jQuery("#id-chart").val() }			
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: data,
			beforeSend:function(){
				jQuery("#chart-list").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");				
				 },
			success:function(message){ 
				jQuery("#chart-list").html("<ul>"+message+"</ul>");

				/*
					DROPDOWN MENU
				*/
				
				UpdateDropDown(jQuery("#chart-list a.button-primary").data('theme'));

				bindClickChartlist();
				bindClickChartlistUI();
				
					var data = { type: 'graphchart', action: 'graphchart',chartid : jQuery("#chart-list a.button-primary").data('id')};
					jQuery.ajax({type: "POST", url: ajaxurl, data: data,
					
					beforeSend:function(){						
						jQuery(".tips").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");		
					 },
					
					success:function(response){
						jQuery(".tips").addClass('hidden');
						jQuery("#person").removeClass('hidden');
						
						if(response!=""){
							jQuery("#team-chart").prepend('<ul id="org" style="display:none;"></ul>');
							jQuery("#org").html(response);
						}
						
						// Read insert data js
						jQuery('#org li').each(function(){
							person= new Object();
							person["id"]=jQuery(this).data('id');
							person["name"]=jQuery(this).data('name');
                            person["socialInfoFacebook"]=jQuery(this).data('socialinfofacebook');
                            person["socialInfoEmail"]=jQuery(this).data('socialinfoemail');
                            person["socialInfoIn"]=jQuery(this).data('socialinfoin');
                            person["socialInfoTwitter"]=jQuery(this).data('socialinfotwitter');
							person["job"]=jQuery(this).data('job');
							person["description"]=jQuery(this).data('description');
							person["media-id"]=jQuery(this).data('mediaid');
							person["media-url"]=jQuery(this).data('mediaurl');
							person["parent"]=jQuery(this).data('parent');
							person["pos"]=jQuery(this).data('pos');	
							persons[jQuery(this).data('id')]=person;
						});
						graphChart();	
						UpdatePersonList();
				     }});
				
			}
		});	
		
		}
} // END INIT();

	function bindClickChartlist(){

		jQuery("#chart-list").on('click','a:not(.button-primary)',function (){
					
					//Clear var
					persons=new Object;
					jQuery("#org").remove();
					graphChart();
					jQuery(".tips").removeClass('hidden');
					jQuery("#person-list").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");	
					
					jQuery("#chart-list a").removeClass('button-primary');
					jQuery(this).addClass('button-primary');
					
					var data = { type: 'graphchart', action: 'graphchart',chartid : jQuery("#chart-list a.button-primary").data('id')};
					jQuery.ajax({type: "POST", url: ajaxurl, data: data,
					beforeSend:function(){						
						jQuery(".tips").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");		
					 },
					success:function(response){
						jQuery(".tips").addClass('hidden');
						jQuery("#person").removeClass('hidden');
						
						if(response!=""){
							UpdateDropDown(jQuery("#chart-list a.button-primary").data('theme'));
							jQuery("#team-chart").prepend('<ul id="org" style="display:none;"></ul>');
							jQuery("#org").html(response);
						}
						
						// Read insert data js
						jQuery('#org li').each(function(){
							person= new Object();
							person["id"]=jQuery(this).data('id');
							person["name"]=jQuery(this).data('name');
							person["job"]=jQuery(this).data('job');
							person["socialInfoFacebook"]=jQuery(this).data('socialinfofacebook');
							person["socialInfoEmail"]=jQuery(this).data('socialinfoemail');
							person["socialInfoIn"]=jQuery(this).data('socialinfoin');
							person["socialInfoTwitter"]=jQuery(this).data('socialinfotwitter');
							person["description"]=jQuery(this).data('description');
							person["media-id"]=jQuery(this).data('mediaid');
							person["media-url"]=jQuery(this).data('mediaurl');
							person["parent"]=jQuery(this).data('parent');
							person["pos"]=jQuery(this).data('pos');	
							persons[jQuery(this).data('id')]=person;
						});
						graphChart();	
						UpdatePersonList();
				     }});
					
					
					return false;
				});	
	}
	
	function getTheme()
    {
        var themeNumber =  jQuery("#chart-list a.button-primary").data('theme');
        if (typeof (themeNumber) != 'undefined') {
            if (themeNumber == 1 ) {
                return 'default';
            } else  if (themeNumber == 2 ) {
                return 'circle';
            } else  if (themeNumber == 3 ) {
                return 'nature';
            } else  if (themeNumber == 4 ) {
                return 'lightbox';
            } else {
                return false;
            }
        } else {
            return false;
        }

    }
	
	/*
	 DROPDOWN MENU
	*/
	function UpdateDropDown($theme) {

        if ($theme != 4) {
            jQuery("#socialInfo").addClass("hidden");
        } else {
            jQuery("#socialInfo").removeClass("hidden");
        }
		oDropdown.set('disabled', false);
		oDropdown.set("selectedIndex", $theme-1);
		
		oDropdown.on("change", function(res) {
			if(jQuery("#msdropdown20").val() == 4) {
                jQuery("#socialInfo").removeClass("hidden");
            } else {
                jQuery("#socialInfo").addClass("hidden");
            }
			
			var data = { type: 'updatetheme', action: 'updatetheme',chartid : jQuery("#chart-list a.button-primary").data('id'),theme: jQuery("select[name='Template']").val()};
					jQuery.ajax({type: "POST", url: ajaxurl, data: data,
					beforeSend:function(){						
						oDropdown.set('disabled', true);	
					 },
					success:function(response){
						if(response){
						oDropdown.set('disabled', true);}
						else{
						oDropdown.set('disabled', false);
						jQuery("#chart-list a.button-primary").attr('data-theme',jQuery("select[name='Template']").val());
						}
					}
					});

		});

		
	}
	
	
	
	
	
	function bindClickChartlistUI(){
		
		jQuery("#chart-list").on('click','a span.delete',function (){
			
			jQuery("#chart-list").off('click',"a:not(.button-primary)");
			jQuery("#chart-list").off('click','a span.edit');
			jQuery("#chart-list").off('click','a span.delete');	
			
			jQuery(this).parent("a").parent("li").append("<form id='delete-chart'>"+teamchart_popup_vars.deletechart+"<input type='submit' class='button' value='"+teamchart_popup_vars.yes+"'/><input type='submit' class='button button-primary' value='"+teamchart_popup_vars.no+"'/></form>");
			
			var val;
			var $chartid=jQuery(this).parent("a").data('id');

			jQuery('#delete-chart input').click(function(){
				val = jQuery(this).val();	

			});
			
			jQuery('#delete-chart').submit(function() {
				
				if ((val=="Yes") || (val=="Oui"))
				{
					var data = { type: 'deletechart', action: 'deletechart',chartid : $chartid};
					jQuery.ajax({type: "POST", url: ajaxurl, data: data,
					beforeSend:function(){			
						jQuery(".tips").removeClass('hidden');			
						jQuery(".tips").html(teamchart_popup_vars.tips);		
						jQuery(this).remove();	
						jQuery(".tips").removeClass('hidden');
						jQuery("#person").addClass('hidden');
						jQuery("#org").remove();	
					 },
					success:function(response){											
							graphChart();						
							init();
						}
					});	
	
				}
				
				
				else {
					
					jQuery(this).remove();	
					bindClickChartlist();
					bindClickChartlistUI();
				}
				
				
				return false;	
			});

						
		});	

		
		
		jQuery("#chart-list").on('click','a span.edit',function (){
			
			
			
			jQuery("#chart-list").off('click','a span.delete');	
			jQuery("#chart-list").off('click','a span.edit');	
			jQuery("#chart-list").off('click',"a:not(.button-primary)");
		
					
			jQuery(this).parent("a").parent("li").append("<form id='update-chart'><input type='text' name='name' value='"+jQuery(this).parent("a").text()+"'/><input type='submit' class='button button-primary' value='"+teamchart_popup_vars.updateform+"'/></form>");
			
			var $chartid=jQuery(this).parent("a").data('id');
			
			jQuery('#update-chart').submit(function() {
				
				var data = { type: 'updatechart', action: 'updatechart',chartid : $chartid , name :jQuery(this).serialize()};
				jQuery.ajax({type: "POST", url: ajaxurl, data: data,
				beforeSend:function(){						
						jQuery("#chart-list").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");	
						jQuery(this).remove();	
						
					 },				
				success:function(response){
					
						
											
						var data = { action: 'addajax_chart',chartid : $chartid}			
						jQuery.ajax({
							type: "POST",
							url: ajaxurl,
							data: data,
							success:function(message){ 
								jQuery("#chart-list").html("<ul>"+message+"</ul>");
								bindClickChartlist();
								bindClickChartlistUI();
								UpdatePersonList();
							}
						});	
					}
				});
				
				
				return false;
			});
			
							
		});	

	}
	
	
	
	
	function UpdatePersonList(){
		var data = { type: 'teamchart_person', action: 'teamchart_person',chartid : jQuery("#chart-list a.button-primary").data('id')};
		jQuery.ajax({type: "POST", url: ajaxurl, data: data,
		beforeSend:function(){						
						jQuery("#person-list").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");	
						
						
					 },	
		success:function(response){
			jQuery("#person-list").html(response);
			
			/*  DELETE PERSON FROM DATABASE  */
			jQuery('.person .delete').click(function(){				
				if (confirm(teamchart_popup_vars.deleteconfirm)){
					var data = { type: 'deleteperson', action: 'deleteperson',idperson : jQuery(this).parent().data('id')}
					jQuery(this).parent().unbind('click');								
					jQuery(this).parent().remove();	

				
						
					jQuery.ajax({
						type: "POST",
						url: ajaxurl,
						data: data
					});	
					
					
				}
		
			});
			
			
			
			/*  ADD PERSON IN CHART  */
			jQuery('.person').click(function(){
				jQuery(this).unbind('click');
								
				person= new Object();			
				person["id"]=jQuery(this).data('id');
				person["name"]=jQuery(this).data('name');
				person["job"]=jQuery(this).data('job');
                person["socialInfoFacebook"]=jQuery(this).data('socialinfofacebook');
                person["socialInfoEmail"]=jQuery(this).data('socialinfoemail');
                person["socialInfoIn"]=jQuery(this).data('socialinfoin');
                person["socialInfoTwitter"]=jQuery(this).data('socialinfotwitter');
				person["description"]=jQuery(this).data('description');
				person["media-id"]=jQuery(this).data('mediaid');
				person["media-url"]=jQuery(this).data('mediaurl');
				person["parent"]=null;
				person["pos"]=null;
				persons[person["id"]]=person;
				
				jQuery(this).remove();
				
				
				if(document.getElementById("org") == null)
				{
					jQuery("#team-chart").prepend('<ul id="org" style="display:none;"></ul>');
				}
                if ("lightbox" === getTheme()) {
                    jQuery("#org").append('<li id="first" data-id="'+person["id"]+'">'+person["name"]+'<div class="imgnode"><img src="'+person["media-url"]+'"/></div><ul></ul></li>');
                } else {
                    if (jQuery("#org li").is('#first')){
                        if (jQuery("#org li#first ul:last").length!=0)
                        {
                            jQuery("#org li#first ul:last").append('<li data-id="'+person["id"]+'">'+person["name"]+'<div class="imgnode"><img src="'+person["media-url"]+'"/></div></li>');
                        } else
                        {

                            jQuery("#org li#first").append('<ul><li data-id="'+person["id"]+'">'+person["name"]+'<div class="imgnode"><img src="'+person["media-url"]+'"/></div></li></ul>');
                        }
                    }
                    else {

                        jQuery("#org").append('<li id="first" data-id="'+person["id"]+'">'+person["name"]+'<div class="imgnode"><img src="'+person["media-url"]+'"/></div><ul></ul></li>');
                    }
                }

				updatePosition();
				addDB(jQuery(this).data('id'));
	
				return false;	
			});
		}});
	}
	
	
	/**
	* Action to upload or choose picture
	**/	
		jQuery('body').on('click','.custom_media_upload',function() {
			
		    var send_attachment_bkp = wp.media.editor.send.attachment;

		    wp.media.editor.send.attachment = function(props, attachment) {
				
				jQuery('.custom_media_upload').removeClass("picture");
				jQuery('#media-upload-hidden').val('attachment.id');
				jQuery('.custom_media_upload').html("<img class='custom_media_image' src='"+attachment.url+"' data-id='"+attachment.id+"'/><a href='#crop' id='imagecrop'></a>");
		        /*
		        $('.custom_media_url').val(attachment.url);
		        $('.custom_media_id').val(attachment.id);
				*/
		        wp.media.editor.send.attachment = send_attachment_bkp;
		    }
		
		    wp.media.editor.open();
		
		    return false;       
		});


		
		
	/**
	* Action to Add Person or Update person
	* "idchart" give ID to new person
	* "editing_tmp" temp var to update person
	* "persons" object with all person in the chart
	**/		
	var idchart=1;// IMPORTANT
	var editing_tmp;
	var persons=new Object();
	var person=new Object();
	
	jQuery('body').on('submit','.add-person',function() {
			
		if (jQuery(this).attr('id')=="add-new-person"){
			
			person= new Object();
			person["name"]=jQuery("#new-person").val();
			person["job"]=jQuery("#job").val();
			person["socialInfoFacebook"]=jQuery("#socialInfoFacebook").val();
			person["socialInfoEmail"]=jQuery("#socialInfoEmail").val();
			person["socialInfoIn"]=jQuery("#socialInfoIn").val();
			person["socialInfoTwitter"]=jQuery("#socialInfoTwitter").val();
			person["description"]=jQuery("#description").val();
			person["media-id"]=jQuery('.custom_media_image').attr('data-id');
			person["media-url"]=jQuery('.custom_media_image').attr('src');
			person["parent"]=null;
			person["pos"]=null;				

			addNewPerson(person);
			
		}
		else if (jQuery(this).attr('id')=="update-person"){
			updatePerson();
		}
		resetFields();			
	
		return false;
	
	});
	
	

	function graphChart(){
		jQuery(".jOrgChart").remove();
			jQuery("#org").jOrgChart({
	            chartElement : '#build-chart',
	            dragAndDrop  : function() {
       		     updatePosition();
       		     updateDB();
     	 		 }
	        });
	}
	
	
	// Reset Fields
	function resetFields(){
		jQuery("#new-person").val("");
		jQuery("#job").val("");
		jQuery("#socialInfoFacebook").val("");
		jQuery("#socialInfoEmail").val("");
		jQuery("#socialInfoIn").val("");
		jQuery("#socialInfoTwitter").val("");
		jQuery("#description").val("");
        tinyMCE.activeEditor.setContent("");
		jQuery('.custom_media_upload').addClass("picture").html(teamchart_popup_vars.mediabutton);
		jQuery('#media-upload-hidden').val("");
	}
		
	function addNewPerson(persondata){
		// Save data
		person= new Object();
		person=persondata;

		
			
		// submit Data
		var personsValues = person;
		var data = { type: 'save', action: 'iajax_save',chartid : jQuery("#chart-list a.button-primary").data('id'), persons: personsValues };
		jQuery.ajax({
			 type: "POST",
			 url: ajaxurl,
			 data: data,
			 beforeSend:function(){
			 // Do action
			 },
			 success:function(idperson){ 
			 	person["id"]=idperson;			 	
				persons[idperson]=person;	
				
				
				if(document.getElementById("org") == null)
				{
					jQuery("#team-chart").prepend('<ul id="org" style="display:none;"></ul>');
				}

                 if ("lightbox" === getTheme()) {
                     jQuery("#org").append('<li id="first" data-id="'+idperson+'">'+person["name"]+'<div class="imgnode"><img src="'+person["media-url"]+'"/></div><ul></ul></li>');
                 } else {
                     if (jQuery("#org li").is('#first'))
                         jQuery("#org li#first ul:last").append('<li data-id="'+idperson+'">'+person["name"]+'<div class="imgnode"><img src="'+person["media-url"]+'"/></div></li>');
                     else
                         jQuery("#org").append('<li id="first" data-id="'+idperson+'">'+person["name"]+'<div class="imgnode"><img src="'+person["media-url"]+'"/></div><ul></ul></li>');
                 }
				// Add node in Chart

							
				updatePosition();
				graphChart();   
				updateDB();
			 }
		});		
		
		
		

		
	
	}
	
	function updatePerson(){
        persons[editing_tmp]["socialInfoFacebook"]=jQuery("#socialInfoFacebook").val();
        persons[editing_tmp]["socialInfoEmail"]=jQuery("#socialInfoEmail").val();
        persons[editing_tmp]["socialInfoIn"]=jQuery("#socialInfoIn").val();
        persons[editing_tmp]["socialInfoTwitter"]=jQuery("#socialInfoTwitter").val();
		persons[editing_tmp]["name"]=jQuery("#new-person").val();
		persons[editing_tmp]["job"]=jQuery("#job").val();
		persons[editing_tmp]["description"]=jQuery("#description").val();
		persons[editing_tmp]["media-id"]=jQuery('.custom_media_image').attr('data-id');
		persons[editing_tmp]["media-url"]=jQuery('.custom_media_image').attr('src');
		// CLONE LES ENFANT et les réinjecter
		var clone=jQuery('li[data-id="'+editing_tmp+'"]').clone().children("ul").html();		
		
		jQuery('li[data-id="'+editing_tmp+'"]').html(persons[editing_tmp]["name"]+'<div class="imgnode"><img src="'+persons[editing_tmp]["media-url"]+'"/></div><ul>'+clone+'</ul>');
		
		
		
		
		/*
		Database Persist		
		*/
		
		var personsValues = persons[editing_tmp];
		var data = { type: 'updateperson', action: 'iajax_save', persons: personsValues };
		jQuery.ajax({
			 type: "POST",
			 url: ajaxurl,
			 data: data,
			 beforeSend:function(){
			   updatePosition();
			   graphChart();   
			   
			 },
			 success:function(){ 
				
				
				updateDB();
				
				jQuery('#submit-person').val(teamchart_popup_vars.addperson);
				jQuery('.add-person').attr('id',"add-new-person"); 								
			 }
		});	
		
	}
	
	
	// Read position of person
	function updatePosition(){
        //if (getTheme() === "lightbox") {
        //    var pos=0;
        //    jQuery('#org li').each(function(){
        //        persons[jQuery(this).data("id")]["parent"]="-1";
        //        persons[jQuery(this).data("id")]["pos"]=pos;
        //        pos++;
        //    });
        //
        //} else {
            var tmpparent=null;
            var pos=0;
            jQuery('#org li').each(function(){
                persons[jQuery(this).data("id")]["parent"]=jQuery(jQuery(this)).parent().parent().data('id');
                if((typeof persons[jQuery(this).data("id")]["parent"])=='undefined'){
                    persons[jQuery(this).data("id")]["parent"]="-1";
                }
                persons[jQuery(this).data("id")]["pos"]=pos;
                pos++;
            });
        //}
	}
	
	function updateDB(){

		var personsValues = persons;	
		var data = { type: 'updatepos', action: 'iajax_save',chartid : jQuery("#chart-list a.button-primary").data('id'), persons: personsValues };
		jQuery.ajax({type: "POST", url: ajaxurl, data: data,success:function(){}});
	}
	
	function addDB($idperson){
		graphChart(); 
		var data = { type: 'addperson', action: 'iajax_save',chartid : jQuery("#chart-list a.button-primary").data('id'), personid: $idperson };
		jQuery.ajax({type: "POST", url: ajaxurl, data: data,success:function(){
		
		updateDB();

		
		}});
	}
	
	
	function deleteDB($idperson,$parent){
		
		
		
		
		var personsValues = new Object();	
		person_id= new Object();
		person_id["id"]=$idperson;
		personsValues[$idperson]=person_id;		
		
		jQuery("#org li[data-id='"+$idperson+"'] li").each(function(){
			person_id= new Object();
			person_id["id"]=jQuery(this).data('id');
			personsValues[jQuery(this).data('id')]=person_id;
			
		});
		
		
	  jQuery("#org li[data-id='"+$idperson+"']").remove();
		
			updatePosition();
			graphChart();
		
		   if($parent==null){
			 jQuery("#org").remove();
			 updatePosition();
			 graphChart();
		}
			
		var data = { type: 'delete', action: 'iajax_save',chartid : jQuery("#chart-list a.button-primary").data('id'), persons: personsValues };
		jQuery.ajax({type: "POST", url: ajaxurl, data: data,
		beforeSend:function(){
			jQuery('body').on("click","a.trash",DeleteAction);
		},
		success:function(){				
				
				UpdatePersonList();	
		}});
		
	}
	
	
	/**
	* CLICK on EDIT element
	**/
	
	jQuery('body').on("click","a.edit",function() {
		// read meta-datas
		editing_tmp=this.dataset.id;
		// update input
		jQuery('#new-person').val(persons[editing_tmp]["name"]);
		jQuery("#socialInfoFacebook").val(persons[editing_tmp]["socialInfoFacebook"]);
		jQuery("#socialInfoEmail").val(persons[editing_tmp]["socialInfoEmail"]);
		jQuery("#socialInfoIn").val(persons[editing_tmp]["socialInfoIn"]);
		jQuery("#socialInfoTwitter").val(persons[editing_tmp]["socialInfoTwitter"]);
		jQuery("#job").val(persons[editing_tmp]["job"]);
        tinyMCE.activeEditor.setContent(persons[editing_tmp]["description"]);
		jQuery('.custom_media_upload').removeClass("picture");
		jQuery('.custom_media_upload').html("<img class='custom_media_image' src='"+persons[editing_tmp]["media-url"]+"' data-id='"+persons[editing_tmp]["media-id"]+"'/><a href='#crop' id='imagecrop'></a>");
		jQuery('#media-upload-hidden').val("true");
		// ui form
		jQuery('#submit-person').val(teamchart_popup_vars.updateperson);
		jQuery('.add-person').attr('id',"update-person");
		return false;	
	});
	
	
	
	/**
	* CLICK on CROP element
	**/
	var datacropped="null";
	jQuery('body').on("click","a#imagecrop",function() {
		
		var data = { type: 'cropimage', action: 'cropimage',idmedia : jQuery(".custom_media_image").data('id')}			
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				beforeSend:function(){
					
				jQuery('#column').before("<div id='cropoverlay'><div id='crop'></div></div>");
				},
				success:function(message){ 
					jQuery('#crop').html(message);
					jQuery('#cropimage').imgAreaSelect({
				        handles: true,aspectRatio:"1:1",
				        onSelectEnd:function(img, selection){
				        	
				        	
				        								
							// Create new offscreen image to test
							var theImage = new Image();
							theImage.src = jQuery("#cropimage").attr("src");
							
							// Get accurate measurements from that.
							var imageWidth = theImage.width;
							var imageHeight = theImage.height;
				        	var factor=imageWidth/jQuery("#cropimage").width();
				        	
				        	
				        	datacropped = { type: 'croppingimage', action: 'croppingimage',
				        	src:jQuery(".custom_media_image").data('id'),src_x:selection.x1*factor,src_y:selection.y1*factor,src_w:selection.width*factor,src_h:selection.height*factor,dst_w:182,dst_h:182};
				        	
				        	
				        }
				    });
				}
			});	

		
		
		return false;
	});
	
	
	
	/**
	* CLICK on CROP element
	**/
	
	jQuery('body').on("click","a#savecrop",function() {
		
				
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: datacropped,
				success:function(message){ 				
					jQuery('#cropimage').imgAreaSelect({remove:true});
					jQuery('#cropoverlay').remove();					
					jQuery(".custom_media_image").attr('src', jQuery(".custom_media_image").attr('src')+'?'+Math.random());
				  }
				});
				        	
			
		return false;
	});
	
	
		
	/**
	* CLICK on CLOSECROP element
	**/
	
	jQuery('body').on("click","a#closecrop",function() {
		
		jQuery('#cropimage').imgAreaSelect({remove:true});
		jQuery('#cropoverlay').remove();					
					 	
			
		return false;
	});
	
	

	/**
	* CLICK on DELETE element
	**/
	jQuery('body').on("click","a.trash",DeleteAction);
	
	
	
	function DeleteAction() {
		
		if (confirm(teamchart_popup_vars.deleteconfirm)){
			delete_id=this.dataset.id;	
		
		jQuery('body').off("click","a.trash",DeleteAction);
		jQuery("#person-list").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");
		
		
		deleteDB(delete_id,persons[delete_id]['parent']);	
			
	
		}	
	}

	/**
	*  ADD CHART
	**/
	
		jQuery('body').on('click',"a#add-chart",function(){
			if (jQuery("#chart-name").val()=="")
				return;
			jQuery(".tips").addClass('hidden');
			jQuery("#person").removeClass('hidden');
			jQuery("#org").remove();
			graphChart();
					
			var data = { type: 'addChart', action: 'addajax_chart',chartname : jQuery("#chart-name").val()}			
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				beforeSend:function(){
					jQuery("#chart-list").html("<div id='loading'>"+teamchart_popup_vars.loading+"...</div>");	
				},
				success:function(message){ 
					jQuery("#chart-list").html("<ul>"+message+"</ul>");
					//bindClickChartlist();
					//bindClickChartlistUI();
					UpdatePersonList();
					UpdateDropDown(jQuery("#chart-list a.button-primary").data('theme'));
					jQuery("#chart-name").val("");
				}
			});	
			
			
						
			return false;	
		});
		



	/**
	* CLICK on SAVE CHART
	**/
	jQuery('body').on('click',"#save-chart",function(){
		
		if (jQuery('#responsivemode').prop('checked')== true){
			window.send_to_editor("[teamchart id='"+jQuery("#chart-list a.button-primary").data('id')+"' responsivemode='false']");
		}
		else {
			window.send_to_editor("[teamchart id='"+jQuery("#chart-list a.button-primary").data('id')+"']");
		}
		
			
		parent.jQuery.fancybox.close();
		return false;		
	});
	
	
	


