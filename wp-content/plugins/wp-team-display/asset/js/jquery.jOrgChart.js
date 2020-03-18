/**
 * jQuery org-chart/tree plugin.
 *
 * Author: Wes Nolte
 * http://twitter.com/wesnolte
 *
 * Based on the work of Mark Lee
 * http://www.capricasoftware.co.uk 
 *
 * Copyright (c) 2011 Wesley Nolte
 * Dual licensed under the MIT and GPL licenses.
 *
 */
(function($) {
  $.fn.jOrgChart = function(options) {
    var opts = $.extend({}, $.fn.jOrgChart.defaults, options);
    var $appendTo = $(opts.chartElement);

    // build the tree
    $this = $(this);
    var $container = $("<div class='" + opts.chartClass + "'/>");
      // build the tree theme lightbox

      if ("lightbox" == getTheme()) {
            buildLightboxTheme($this, $container, $appendTo, opts);
      } else {
          // build tree other themes
          if($this.is("ul")) {
              //buildNode($this.find("li:first"), $container, 0, opts);
              buildNodes($this, $container, opts);
          }
          else if($this.is("li")) {
              buildNode($this, $container, 0, opts,true);
          }
          $appendTo.append($container);




          // add drag and drop if enabled
          if(opts.dragAndDrop){

              $('div.node').draggable({
                  cursor      : 'move',
                  distance    : 10,
                  helper      : 'clone',
                  opacity     : 0.8,
                  revert      : 'invalid',
                  revertDuration : 100,
                  snap        : 'div.node.expanded',
                  snapMode    : 'inner',
                  stack       : 'div.node',
                  scrollSpeed : 10,
                  scroll : true
              });



              $('div.node').droppable({
                  accept      : '.node',
                  activeClass : 'drag-active',
                  hoverClass  : 'drop-hover'
              });

              // Drag start event handler for nodes
              $('div.node').bind("dragstart", function handleDragStart( event, ui ){



                  var sourceNode = $(this);
                  sourceNode.parentsUntil('.node-container')
                      .find('*')
                      .filter('.node:data(draggable)')
                      .droppable('disable');
                  /*
                   $('.node-container.first')
                   .find('*')
                   .filter('.node-container.first .node:data(draggable)')
                   .droppable('disable');
                   */
                  if (sourceNode.parentsUntil('.node-container').find('*').filter('.node:data(draggable)').length == 1)
                      $('.between.first .empty').css('display','block');





                  $(this).parents("table").parents("td").prev().removeClass('between');
                  $(this).parents("table").parents("td").next().removeClass('between');

                  $(this).parents("table").parents("tr").children('.between').children('.empty').css('display','block');




                  /*
                   $(".node:data(draggable)").not($(this)).append("<div class='after'></div>");
                   $(".node:data(draggable)").not($(this)).prepend("<div class='before'></div>");
                   */
              });

              // Drag stop event handler for nodes
              $('div.node').bind("dragstop", function handleDragStop( event, ui ){

                  /* reload the plugin */

                  $(opts.chartElement).children().remove();
                  $this.jOrgChart(opts);
                  opts.dragAndDrop.call(this);


              });

              // Drop event handler for nodes
              $('div.node').bind("drop", function handleDropEvent( event, ui ) {

                  // IF Drop on empty node
                  if ($(this).hasClass('empty')){
                      if ($(this).parent().prev().length == 0){
                          $element = $(this).parent().next().find(".node")
                          var nextel=true;
                      }
                      else {
                          $element = $(this).parent().prev().find(".node")
                          var nextel=false;
                      }

                      var targetID = $element.data("tree-node");
                      var targetLi = $this.find("li").filter(function() { return $(this).data("tree-node") === targetID; } );
                      var targetUl = targetLi.children('ul');


                  }
                  else {
                      var targetID = $(this).data("tree-node");
                      var targetLi = $this.find("li").filter(function() { return $(this).data("tree-node") === targetID; } );
                      var targetUl = targetLi.children('ul');
                  }


                  var sourceID = ui.draggable.data("tree-node");
                  var sourceLi = $this.find("li").filter(function() { return $(this).data("tree-node") === sourceID; } );
                  var sourceUl = sourceLi.parent('ul');


                  if ($(this).hasClass('empty')){

                      if ($(this).hasClass('first')){
                          sourceLi.attr('data-parent','-1');
                      }


                      if (nextel)
                          targetLi.before(sourceLi);
                      else
                          targetLi.after(sourceLi);
                  }


                  else {

                      if (sourceLi.is('#first')) {
                          return false;
                      }

                      if (sourceLi.find(targetLi).length > 0) {
                          return false;
                      }

                      if (targetUl.length > 0){
                          targetUl.append(sourceLi);
                      } else {
                          targetLi.append("<ul></ul>");
                          targetLi.children('ul').append(sourceLi);
                      }
                      //Removes any empty lists
                      if (sourceUl.children().length === 0){
                          sourceUl.remove();
                      }
                  }
              }); // handleDropEvent



          } // Drag and drop
      }

  };

  // Option defaults
  $.fn.jOrgChart.defaults = {
    chartElement : 'body',
    depth      : -1,
    chartClass : "jOrgChart",
    dragAndDrop: false
  };
  
  
  
  
   function buildNodes($list, $appendTo, opts) {
    var $table = $("<table cellpadding='0' cellspacing='0' border='0' class='bg'/>");
    var $tbody = $("<tbody/>");

    // Construct the node container(s)
    var $nodeRow = $("<tr/>");
    var first=0;
    $list.children("li").each(function(i, elem) {
      if (first==0)
     	 var $td = $("<td class='node-container'/>");
      else
     	 var $td = $("<td class='node-container first'/>");
      	 
      $td.attr("colspan", 2);
	  	
      buildNode($(elem), $td, 0, opts, "true");
      
      if (first==0)
      	$nodeRow.append($td).append("<td class='between first'><div class='node empty first'></div></td>");
      else
      	$nodeRow.append($td);
      first++;
    });

    $tbody.append($nodeRow);
    $table.append($tbody);
    $appendTo.append($table);
  }

  
  
  
  
  
  var nodeCount = 0;
  // Method that recursively builds the tree
  function buildNode($node, $appendTo, level, opts, $firstline) {
  	
  	if(typeof($firstline)==='undefined') $firstline = false;
  	
    var $table = $("<table cellpadding='0' cellspacing='0' align='center' border='0'/>");
    var $tbody = $("<tbody/>");

    // Construct the node container(s)
    var $nodeRow = $("<tr/>").addClass("node-cells");
    var $nodeCell = $("<td/>").addClass("node-cell").attr("colspan", 2);
    var $childNodes = $node.children("ul:first").children("li");
    var $nodeDiv;
    
    if($childNodes.length > 0) {
      $nodeCell.attr("colspan", $childNodes.length * 2 + $childNodes.length+1);
    }
    // Draw the node
    // Get the contents - any markup except li and ul allowed
    var datasetid= $node.data("id");
    var editbutton= "<a href='#edit-person' class='edit' data-id='"+datasetid+"'></a>";
    var trashbutton= "<a href='#trash-person' class='trash' data-id='"+datasetid+"'></a>";
    var $nodeContent = $node.clone()
                            .children("ul,li")
                            .remove()
                            .end()
                            .html();
  
      //Increaments the node count which is used to link the source list and the org chart
    nodeCount++;
    $node.data("tree-node", nodeCount);
    $nodeDiv = $("<div data-id='"+datasetid+"'>").addClass("node")
                                     .data("tree-node", nodeCount)
                                     .append($nodeContent)
                                     .append(trashbutton)
                                     .append(editbutton);

       
    $nodeCell.append($nodeDiv);
    
    if ($firstline==true){
   		$nodeRow.append($nodeCell).append("<td class='between'><div class='node empty'></div></td>");
   		alert('test');
    }
    else {
    	$nodeRow.append($nodeCell);
    }
    $tbody.append($nodeRow);

    if($childNodes.length > 0) {
      // if it can be expanded then change the cursor
    //  $nodeDiv.css('cursor','n-resize');
    
      // recurse until leaves found (-1) or to the level specified
      if(opts.depth == -1 || (level+1 < opts.depth)) { 
        var $downLineRow = $("<tr/>");
        var $downLineCell = $("<td/>").attr("colspan", $childNodes.length*2+$childNodes.length+1);
        $downLineRow.append($downLineCell);
        
        // draw the connecting line from the parent node to the horizontal line 
        $downLine = $("<div></div>").addClass("line down");
        $downLineCell.append($downLine);
        $tbody.append($downLineRow);

        // Draw the horizontal lines
        var $linesRow = $("<tr/>");
        var len = $childNodes.length;
        
        
        $childNodes.each(function(index,element) {
          var $left = $("<td>&nbsp;</td>").addClass("line left top");
          var $right = $("<td>&nbsp;</td>").addClass("line right top");
          
          if (index == 0)
          $linesRow.append("<td></td>").append($left).append($right).append("<td></td>");
          else          
          $linesRow.append($left).append($right).append("<td></td>");
          
        });

        // horizontal line shouldn't extend beyond the first and last child branches
        $linesRow.find("td.top:first")
                    .removeClass("top")
                 .end()
                 .find("td.top:last")
                    .removeClass("top");

        $tbody.append($linesRow);
        var $childNodesRow = $("<tr/>");
        
        var len = $childNodes.length;
        $childNodes.each(function(index,element) {
           var $td = $("<td class='node-container'/>");
           $td.attr("colspan", 2);
           // recurse through children lists and items
           buildNode($(this), $td, level+1, opts);
           if (index==0)
           $childNodesRow.append("<td class='between'><div class='node empty'></div></td>");
           $childNodesRow.append($td);
           $childNodesRow.append("<td class='between'><div class='node empty'></div></td>");
        });

      }
      $tbody.append($childNodesRow);
    }

    // any classes on the LI element get copied to the relevant node in the tree
    // apart from the special 'collapsed' class, which collapses the sub-tree at this point
    if ($node.attr('class') != undefined) {
        var classList = $node.attr('class').split(/\s+/);
        $.each(classList, function(index,item) {
            if (item == 'collapsed') {
                $nodeRow.nextAll('tr').css('visibility', 'hidden');
                    $nodeRow.removeClass('expanded');
                    $nodeRow.addClass('contracted');
                    $nodeDiv.css('cursor','s-resize');
            } else {
                $nodeDiv.addClass(item);
            }
        });
    }

    $table.append($tbody);
    $appendTo.append($table);
   };
   // end buildnode

    // get theme
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

    function buildLightboxNodes($list, $appendTo, opts) {
        var $table = $("<table class='parent' cellpadding='0' cellspacing='0' border='0' class='bg'/>");
        var $tbody = $("<tbody class='parent'/>");
        //
        //// Construct the node container(s)
        $("tr.node-cells").each(function () {
            i++;
            console(i);

        });
        var start=0;
        var end=3;
        var numberElement = $list.children("li").length;
        var numberTrTag = Math.ceil(numberElement/3);
        for ($row = 1; $row <= numberTrTag; $row++) {
            var $nodeRow = $("<tr/>");

            var each = 0;
            $list.children("li").each(function(i, elem) {

                if ((start <= each) && ( each < end)) {
                    var $td = $("<td class='node-container'/>");
                    buidLightboxNode($(elem), $td, 0, opts, "true");
                    $nodeRow.append($td).append("<td class='between first'><div class='node empty first'></div></td>");
                }

                each++;
            });
            start = start+3;
            end = end+3;
            $tbody.append($nodeRow);
        }

        $table.append($tbody);
        $appendTo.append($table);
    }

    function buidLightboxNode($node, $appendTo, level, opts, $firstline) {
        if(typeof($firstline)==='undefined') $firstline = false;

        var $table = $("<table class='child' cellpadding='0' cellspacing='0' align='center' border='0'/>");
        var $tbody = $("<tbody class='child'/>");

        // Construct the node container(s)
        var $nodeRow = $("<tr/>").addClass("node-cells");
        var $nodeCell = $("<td/>").addClass("node-cell").attr("colspan", 2);
        var $childNodes = $node.children("ul:first").children("li");
        var $nodeDiv;


        if($childNodes.length > 0) {
            $nodeCell.attr("colspan", $childNodes.length * 2 + $childNodes.length+1);
        }
        // Draw the node
        // Get the contents - any markup except li and ul allowed
        var datasetid= $node.data("id");
        var editbutton= "<a href='#edit-person' class='edit' data-id='"+datasetid+"'></a>";
        var trashbutton= "<a href='#trash-person' class='trash' data-id='"+datasetid+"'></a>";
        var $nodeContent = $node.clone()
            .children("ul,li")
            .remove()
            .end()
            .html();

        //Increaments the node count which is used to link the source list and the org chart
        nodeCount++;
        $node.data("tree-node", nodeCount);
        $nodeDiv = $("<div data-id='"+datasetid+"'>").addClass("node")
            .data("tree-node", nodeCount)
            .append($nodeContent)
            .append(trashbutton)
            .append(editbutton);


        $nodeCell.append($nodeDiv);

        if ($firstline==true){
            $nodeRow.append($nodeCell).append("<td class='between'><div class='node empty'></div></td>");
            alert('test');
        }
        else {
            $nodeRow.append($nodeCell);
        }
        $tbody.append($nodeRow);

        if($childNodes.length > 0) {
            // if it can be expanded then change the cursor
            //  $nodeDiv.css('cursor','n-resize');

            // recurse until leaves found (-1) or to the level specified
            if(opts.depth == -1 || (level+1 < opts.depth)) {
                var $downLineRow = $("<tr/>");
                var $downLineCell = $("<td/>").attr("colspan", $childNodes.length*2+$childNodes.length+1);
                $downLineRow.append($downLineCell);

                // draw the connecting line from the parent node to the horizontal line
                $downLine = $("<div></div>").addClass("line down");
                $downLineCell.append($downLine);
                $tbody.append($downLineRow);

                // Draw the horizontal lines
                var $linesRow = $("<tr/>");
                var len = $childNodes.length;


                $childNodes.each(function(index,element) {
                    var $left = $("<td>&nbsp;</td>").addClass("line left top");
                    var $right = $("<td>&nbsp;</td>").addClass("line right top");

                    if (index == 0)
                        $linesRow.append("<td></td>").append($left).append($right).append("<td></td>");
                    else
                        $linesRow.append($left).append($right).append("<td></td>");

                });

                // horizontal line shouldn't extend beyond the first and last child branches
                $linesRow.find("td.top:first")
                    .removeClass("top")
                    .end()
                    .find("td.top:last")
                    .removeClass("top");

                $tbody.append($linesRow);
                var $childNodesRow = $("<tr/>");

                var len = $childNodes.length;
                $childNodes.each(function(index,element) {
                    var $td = $("<td class='node-container'/>");
                    $td.attr("colspan", 2);
                    // recurse through children lists and items
                    buildNode($(this), $td, level+1, opts);
                    if (index==0)
                        $childNodesRow.append("<td class='between'><div class='node empty'></div></td>");
                    $childNodesRow.append($td);
                    $childNodesRow.append("<td class='between'><div class='node empty'></div></td>");
                });

            }
            $tbody.append($childNodesRow);
        }

        // any classes on the LI element get copied to the relevant node in the tree
        // apart from the special 'collapsed' class, which collapses the sub-tree at this point
        if ($node.attr('class') != undefined) {
            var classList = $node.attr('class').split(/\s+/);
            $.each(classList, function(index,item) {
                if (item == 'collapsed') {
                    $nodeRow.nextAll('tr').css('visibility', 'hidden');
                    $nodeRow.removeClass('expanded');
                    $nodeRow.addClass('contracted');
                    $nodeDiv.css('cursor','s-resize');
                } else {
                    $nodeDiv.addClass(item);
                }
            });
        }

        $table.append($tbody);
        $appendTo.append($table);
    }

    function buildLightboxTheme($this, $container,  $appendTo, opts) {
        if($this.is("ul")) {
            //buildNode($this.find("li:first"), $container, 0, opts);
            buildLightboxNodes($this, $container, opts);
        }
        else if($this.is("li")) {
            buidLightboxNode($this, $container, 0, opts,true);
        }
        $appendTo.append($container);




        // add drag and drop if enabled
        if(opts.dragAndDrop){
            $('div.node').draggable({
                cursor      : 'move',
                distance    : 10,
                helper      : 'clone',
                opacity     : 0.8,
                revert      : 'invalid',
                revertDuration : 100,
                snap        : 'div.node.expanded',
                snapMode    : 'inner',
                stack       : 'div.node',
                scrollSpeed : 10,
                scroll : true
            });



            $('div.node').droppable({
                accept      : '.node',
                activeClass : 'drag-active',
                hoverClass  : 'drop-hover'
            });

            // Drag start event handler for nodes
            $('div.node').bind("dragstart", function handleDragStart( event, ui ){

                var sourceNode = $(this);
                sourceNode.parentsUntil('.node-container')
                    .find('*')
                    .filter('.node:data(draggable)')
                    .droppable('disable');
                /*
                 $('.node-container.first')
                 .find('*')
                 .filter('.node-container.first .node:data(draggable)')
                 .droppable('disable');
                 */
                if (sourceNode.parentsUntil('.node-container').find('*').filter('.node:data(draggable)').length == 1)
                    $('.between.first .empty').css('display','block');





                $(this).parents("table").parents("td").prev().removeClass('between');
                $(this).parents("table").parents("td").next().removeClass('between');

                $(this).parents("table").parents("tr").children('.between').children('.empty').css('display','block');




                /*
                 $(".node:data(draggable)").not($(this)).append("<div class='after'></div>");
                 $(".node:data(draggable)").not($(this)).prepend("<div class='before'></div>");
                 */
            });

            // Drag stop event handler for nodes
            $('div.node').bind("dragstop", function handleDragStop( event, ui ){

                /* reload the plugin */

                $(opts.chartElement).children().remove();
                $this.jOrgChart(opts);
                opts.dragAndDrop.call(this);


            });

            // Drop event handler for nodes
            $('div.node').bind("drop", function handleDropEvent( event, ui ) {

                // IF Drop on empty node
                if ($(this).hasClass('empty')){
                    if ($(this).parent().prev().length == 0){
                        $element = $(this).parent().next().find(".node")
                        var nextel=true;
                    }
                    else {
                        $element = $(this).parent().prev().find(".node")
                        var nextel=false;
                    }

                    var targetID = $element.data("tree-node");
                    var targetLi = $this.find("li").filter(function() { return $(this).data("tree-node") === targetID; } );
                    var targetUl = targetLi.children('ul');


                }
                else {
                    var targetID = $(this).data("tree-node");
                    var targetLi = $this.find("li").filter(function() { return $(this).data("tree-node") === targetID; } );
                    var targetUl = targetLi.children('ul');
                }


                var sourceID = ui.draggable.data("tree-node");
                var sourceLi = $this.find("li").filter(function() { return $(this).data("tree-node") === sourceID; } );
                var sourceUl = sourceLi.parent('ul');


                if ($(this).hasClass('empty')){

                    if ($(this).hasClass('first')){
                        sourceLi.attr('data-parent','-1');
                    }


                    if (nextel)
                        targetLi.before(sourceLi);
                    else
                        targetLi.after(sourceLi);
                }


                else {

                    if (sourceLi.is('#first')) {
                        return false;
                    }

                    if (sourceLi.find(targetLi).length > 0) {
                        return false;
                    }

                    if (targetUl.length > 0){
                        targetUl.append(sourceLi);
                    } else {
                        targetLi.append("<ul></ul>");
                        targetLi.children('ul').append(sourceLi);
                    }
                    //Removes any empty lists
                    if (sourceUl.children().length === 0){
                        sourceUl.remove();
                    }
                }



            });



        } // Drag and drop


}

})(jQuery);
