
(function() {
	tinymce.create('tinymce.plugins.teamchart', {

		init : function(ed, url) {
			var t = this;
			t.url = url;
			var DOM = tinymce.DOM;
			var UI = tinymce.ui;
			
			t.responsive = "true";			
			t._createUI();
			var editor=ed;
			
			//replace shortcode before editor content set
			ed.on("BeforeSetContent",function(o) {
				o.content = t._do_spot(o.content);
				
			});
			
			//replace shortcode as its inserted into editor (which uses the exec command)
			ed.on("ExecCommand",function(cmd) {
			    if (cmd ==='mceInsertContent'){
					tinyMCE.activeEditor.setContent( t._do_spot(tinyMCE.activeEditor.getContent()) );
				}
			});
			//replace the image back to shortcode on save
			ed.on("PostProcess",function(o) {
				if (o.get)
					o.content = t._get_spot(o.content);
			});
			
			
			ed.on("click",function(ed){ 
				editor.plugins.wordpress._hideButtons();
			    
			    // IE
			    if (ed.target) { // this is the img-element
			      if (ed.target.className == 'wpSpot mceItem'){
			      		       
			      var offset = jQuery(ed.target).offset();
			      
			      	var $id=ed.target.id;
			      	if (jQuery(ed.target).attr("data-responsive") == "false"){
			      		jQuery('#teamchart_editbtns img#wp_editimgbtn').attr('data-responsive',"false"); 
			      	}
			      	else {
			      		jQuery('#teamchart_editbtns img#wp_editimgbtn').attr('data-responsive',"true"); 
			      	}
			      	$id= $id.replace("'","");
			      	$id= $id.replace("'",""); 
			      	
			      	jQuery('#teamchart_editbtns img#wp_editimgbtn').attr('data-id',$id);  
			      	jQuery('div.mce-wp-image-toolbar>div.mce-stack-layout').hide();
			      	
			   		t._showButtons(DOM,editor,ed.target, 'teamchart_editbtns');		
			   	
			      }
			    }

			    
			}); // end click event
			
	
			// popup buttons for images and the gallery
			ed.on("init",function(ed) {
                tinymce.dom.EventUtils.bind(editor.getWin(), 'scroll', function(e) {
					t._hideButtons();
				});
                tinymce.dom.EventUtils.bind(editor.getBody(), 'dragstart', function(e) {
					t._hideButtons();
				});
			});
			
			ed.on("MouseDown",function(ed, e) {			
					t._hideButtons();
			});

			ed.on("SaveContent",function(ed, o) {
                //tinyMCE.activeEditor.setContent( t._do_spot(tinyMCE.activeEditor.getContent()) );
				t._hideButtons();
			});

	
			ed.on("keydown",function(e){
				if ( e.which == tinymce.util.VK.DELETE  || e.which == tinymce.util.VK.BACKSPACE )
					t._hideButtons();
			});


		},

		_do_spot : function(co) {
			var t = this;
			return co.replace(/\[teamchart([^\]]*)\]/g, function(a,b){
				
				var str = tinymce.DOM.encode(b);
				
			
				var n = str.search("responsivemode");
				if (n != -1){
					t.responsive = "false";
					return '<img src="'+t.url+'/tinymce-img/t.gif" class="wpSpot mceItem" alt="TeamChart" title="teamchart'+tinymce.DOM.encode(b)+'" '+tinymce.DOM.encode(b)+' data-responsive="false" data-mce-src="/wp-content/plugins/teamchart/asset/images/t.gif"/>';
				}
				else{
					t.responsive = "true";
					return '<img src="'+t.url+'/tinymce-img/t.gif" class="wpSpot mceItem" alt="TeamChart" title="teamchart'+tinymce.DOM.encode(b)+'" '+tinymce.DOM.encode(b)+' data-mce-src="/wp-content/plugins/teamchart/asset/images/t.gif"/>';
				}
				
				
				
				
					
			});
			
			
		},

		_get_spot : function(co) {

			function getAttr(s, n) {
				n = new RegExp(n + '=\"([^\"]+)\"', 'g').exec(s);
				return n ? tinymce.DOM.decode(n[1]) : '';
			};

			return co.replace(/(?:<p[^>]*>)*(<img[^>]+>)(?:<\/p>)*/g, function(a,im) {
				var cls = getAttr(im, 'class');

				if ( cls.indexOf('wpSpot') != -1 )
					return '<p>['+tinymce.trim(getAttr(im, 'title'))+']</p>';

				return a;
			});
		},
		
		_createUI : function() {
			var t = this, ed = tinymce.activeEditor, DOM = tinymce.DOM, editButton, dellButton, isRetina;

			if ( DOM.get('teamchart_editbtns') )
				return;

			DOM.add(document.body, 'div', {
				id : 'teamchart_editbtns',
				style : 'display:none;position:absolute;'
			});

			editButton = DOM.add('teamchart_editbtns', 'img', {
				src : t.url+'/tinymce-img/image.png',
				id : 'wp_editimgbtn',
				width : '24',
				height : '24'
			});
			
			
			jQuery(editButton).bind('click', function(e) {
				 var DOM = tinymce.DOM;
			     DOM.hide( DOM.select('#teamchart_editbtns') );
				 if (jQuery('#teamchart_editbtns img#wp_editimgbtn').attr('data-responsive')=="false"){			 
				 var strhref=t.url+'/../../include/admin/teamchart-popup-window.php?responsive=false&popup=true&id='+jQuery('#teamchart_editbtns img#wp_editimgbtn').attr('data-id');
				 }
				 else{
				 var strhref=t.url+'/../../include/admin/teamchart-popup-window.php?popup=true&id='+jQuery('#teamchart_editbtns img#wp_editimgbtn').attr('data-id');
				}
				 jQuery.fancybox(				 
					 {
					 	href : strhref
			    	 },
			    	  {
                		maxWidth	: 1200,
                		type		: 'ajax',
						maxHeight	: 1200,
						fitToView	: false,
						width		: "80%",
						height		: "90%",
						autoSize	: false,
						closeClick	: false,
						openEffect	: "none",
						closeEffect	: "none"
					}
 				);
 				
			});
			
			
			dellButton = DOM.add('teamchart_editbtns', 'img', {
				src : t.url+'/tinymce-img/delete.png',
				id : 'wp_delimgbtn',
				width : '24',
				height : '24'
			});
			
		
			jQuery(dellButton).bind('click', function(e) {
				var ed = tinymce.activeEditor, el = ed.selection.getNode(), parent;
				ed.dom.remove(el);
				t._hideButtons();
			});
			
		},
		
		_hideButtons : function() {
			var DOM = tinymce.DOM;
			jQuery('div.mce-wp-image-toolbar>div.mce-stack-layout').show();
			DOM.hide( DOM.select('#teamchart_editbtns') );
		},
		_showButtons :	function(DOM,editor, n, id ) {
			var p1, p2, vp, X, Y;
	
			vp = editor.dom.getViewPort( editor.getWin() );
			p1 = DOM.getPos( editor.getContentAreaContainer() );
			p2 = editor.dom.getPos( n );
	
			X = Math.max( p2.x - vp.x, 0 ) + p1.x;
			Y = Math.max( p2.y - vp.y, 0 ) + p1.y;
	
			DOM.setStyles( id, {
				'top' : Y + 5 + 'px',
				'left' : X + 5 + 'px',
				'display': 'block'
			});
			
		}

		
		
	});
	

	tinymce.PluginManager.add('teamchart', tinymce.plugins.teamchart);
	

})();
