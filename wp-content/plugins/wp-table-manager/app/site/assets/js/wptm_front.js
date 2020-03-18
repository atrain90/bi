(function($) {
    function posRestable(id_table, check) {
        var $wptmTbl = $("#wptmTbl"+id_table);
        var tblWidth =  $wptmTbl.width();
        var $wptmtable = $("#wptmtable" + id_table);
        var resTableWidth = $wptmtable.find(".restableOverflow").width();
        // set width cols table
        if (check === true) {
            var width = 0;
            var widths = 0;
            $wptmTbl.parent().find('.restableMenu ul li').each(function() {
                var inputs = $(this).find('input');
                var cols = inputs.data('col') + 1;
                widths = inputs.data('width');
                if (!isNaN(cols)) {
                    var that = $wptmTbl.find('th:nth-child('+cols+'):not(".form-horizontal"),td:nth-child('+cols+')');
                    if (inputs.hasClass('show')) {
                        that.width(widths);
                        $wptmTbl.find('.tablesorter-filter-row td:eq(' + inputs.data('col') + ')').outerWidth(that.outerWidth());
                        width = width + that.outerWidth();
                    }
                }
            });
            $wptmTbl.outerWidth(width);
            $wptmTbl.find('.tablesorter-filter-row').outerWidth(width);
            if ($wptmTbl.find('thead').length > 0) {
                $wptmTbl.find('tbody>tr').outerWidth(width);
            }
            $wptmTbl.find(".form-horizontal").outerWidth(width);
        }
        tblWidth = (typeof width !== 'undefined' && width > 0) ? width : tblWidth;

        var tblPos = $wptmTbl.offset();
        if (resTableWidth > tblWidth) {
            $wptmtable.find(".restableMenu").offset({left: tblPos.left + tblWidth - 20});
            $wptmtable.find(".restableMenu").css("right", "auto");
        } else {
            $wptmtable.find(".restableMenu").css("left", "auto").css("right", "0");
        }
    }

    $(document).ready(function(){
        $(".wptmtable .filterable").each(function (index) {
            var that = $(this);
            $(this).tablesorter({
                theme: "bootstrap",
                widthFixed: true,
                headerTemplate: '{content} {icon}',
                widgets: ["uitheme", "filter", "zebra"],
                cssIcon: '',
                imgAttr: 'src'
            }).bind('filterEnd', function () {
                event_click(that);
            });
            if (!$(this).hasClass('disablePager')) {
                $(this).tablesorterPager({
                    container: $(this).find('.ts-pager'),
                    cssGoto: ".pagenum",
                    output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'
                });
            }
        });

        setTimeout(function () {
            $(".wptmtable .fxdHdrCol").each(function (index) {
                var that = $(this);
                if ($(this).parent().hasClass('ft_scroller')) {
                    return true;
                }

                var fixedRows = parseInt($(this).data('freeze-rows'));
                var fixedCols = parseInt($(this).data('freeze-cols'));

                if ($(this).width() <= $(this).parent().width()) {
                    fixedCols = 0;
                }
                var tblHeight = $(this).height() + 20;
                var confgiHeight = parseInt($(this).data('table-height'));
                if (!confgiHeight) confgiHeight = 500;
                if (tblHeight > confgiHeight) {
                    tblHeight = confgiHeight;
                }
                var tblSort = ( $(this).hasClass('sortable')) ? true : false;
                $(this).fxdHdrCol({
                    fixedCols: fixedCols,
                    fixedRows: fixedRows,
                    width: "100%",
                    height: tblHeight,
                    sort: tblSort
                }).queue(function () {
                    var width = that.width();
                    that.parents('.wptmtable').find('.ft_r.ui-widget-header').css('width', that.width());
                    var i = 0;
                    if (that.find('thead tr').length > 1) {
                        that.parents('.wptmtable').find('.ft_r.ui-widget-header').find('thead').remove();
                        that.find('thead').prependTo(that.parents('.wptmtable').find('.ft_r.ui-widget-header'));
                        that.prepend(that.parents('.wptmtable').find('.ft_r.ui-widget-header thead').clone());
                    }

                    if (that.parents('.wptmtable').find('.ft_rc.ui-widget-header thead .tablesorter-filter-row').length > 0) {
                        that.parents('.wptmtable').find('.ft_rc.ui-widget-header thead .tablesorter-filter-row').find('td').each(function (e) {
                            if ($(this).find('input').data('column') >= fixedCols) {
                                $(this).remove();
                            }
                        });
                        that.parents('.wptmtable').find('.ft_cwrapper').css('width', that.parents('.wptmtable').find('.ft_rc.ui-widget-header').width());
                    }
                    for (var ii = 0; ii < fixedRows; ii++) {
                        if (typeof that.find('tbody .row' + ii).prop("clientHeight") !== 'undefined') {
                            i = i + that.find('tbody .row' + ii).prop("clientHeight");
                        }
                        that.find('tbody .row' + ii).addClass('row_hide');
                    }
                    that.css('margin-top', i);

                    if (that.parent().prop("clientWidth") === that.parent().width()) {
                        that.parents('.wptmtable').find('.ft_rwrapper').css('width', that.parent().width());
                    }
                    // event click button prev_next
                    that.find('button').contents().click(function () {
                        event_click(that);
                    });
                    // event click
                    that.contents().click(function () {
                        event_click(that);
                    });

                    that.parents('.wptmtable').find('.ft_rwrapper table').contents().click(function () {
                        event_click(that);
                    });

                    //if enable default sorting
                    if ($(this).hasClass('sortable')) {
                        var id_table = $(this).data('id');
                        var sorton = $(this).data('default-sort');
                        var order = $(this).data('order') === 1 ? 1 : 0;
                        if (sorton !== -1) {
                            $('.wptmtable #wptmTbl' + id_table).trigger('sorton', [[[sorton, order]]]);
                        }
                        $(".ft_r.ui-widget-header").css('width', that.width());
                    }

                    //set hightlight for .ft_c, .ft_r
                    if (that.closest('.wptm_table').data('hightlight') === 1) {
                        var $tableftc = that.closest('div').siblings().find('table.ft_c');
                        var $tableftr = that.closest('div').siblings().find('table.ft_r');
                        var classHightLight = 'droptables-highlight-vertical droptables-highlight-horizontal';
                        that.on('wholly.mouseenter', function (e, affectedAxes) {
                            if ($tableftc.length > 0) {
                                $tableftc.find('.dtr' + $(affectedAxes.horizontal[0]).data('dtr')).addClass(classHightLight);
                            }

                            if ($tableftr.length > 0) {
                                $tableftr.find('.dtc' + $(affectedAxes.vertical[0]).data('dtc')).addClass(classHightLight);
                            }
                        });

                        that.on('wholly.mouseleave', function (e, affectedAxes) {
                            if ($tableftc.length > 0) {
                                $tableftc.find('.dtr' + $(affectedAxes.horizontal[0]).data('dtr')).removeClass(classHightLight);
                            }
                            if ($tableftr.length > 0) {
                                $tableftr.find('.dtc' + $(affectedAxes.vertical[0]).data('dtc')).removeClass(classHightLight);
                            }
                        });
                    }
                    if (that.parent().prop("clientWidth") > that.width()) {
                        window.addEventListener('load', function () {
                            that.parents('.wptmtable').find('.ft_rwrapper').css('width', that.parent().prop("clientWidth"));
                        });
                    }
                });
            });
        }, 100);

        // change style width of table when have action with the table
        var event_click = function(e){
            var that = e;
            setTimeout(function(){
                if (that.parent().prop("clientWidth") === that.parent().width()){
                    that.parents('.wptmtable').find('.ft_rwrapper').css('width', that.parent().width());
                    $(".ft_r.ui-widget-header").css('width', that.width());
                } else {
                    that.parents('.wptmtable').find('.ft_rwrapper').css('width', that.parent().prop("clientWidth"));
                    $(".ft_r.ui-widget-header").css('width', that.width());
                }
            },50);
        };

        //when sorting and disable filter pagination
        $(".wptmtable table.sortable").not('.filterable').not('.enablePager').each(function( index ){
            $(this).tablesorter({
                theme: "bootstrap",
                widthFixed: true,
                headerTemplate: '{content} {icon}',
                widgets: ["uitheme", "zebra"],
                cssIcon: '',
                imgAttr : 'src'
            });
        });

        $(".wptmtable .enablePager").each(function( index ) {
            $(this).tablesorter({
                theme: "bootstrap",
                widthFixed: true,
                headerTemplate: '{content} {icon}',
                widgets: ["uitheme", "zebra"],
                cssIcon: '',
                imgAttr : 'src'
            })
                .tablesorterPager({
                    container: $(".ts-pager"),
                    cssGoto: ".pagenum",
                    size: $(this).data('pagesize'),
                    savePages: false,
                    output: '{startRow} - {endRow} / {filteredRows} ({totalRows})'
                });
        });

        //default sorting when disable freeze rows
        $(".wptmtable .sortable").not('.fxdHdrCol').each(function(){
            var id_table = $(this).data('id');
            var sorton = $(this).data('default-sort');
            var order = $(this).data('order') === 1 ? 1 : 0;
            if (sorton !== -1) {
                $('.wptmtable #wptmTbl' + id_table).trigger('sorton', [ [[ sorton, order]] ]);
            }
        });

        $(".wptm_tooltip").mouseenter(function() {
            if($(this).parent().find(".wptm_tooltipcontent_show").length ==0) {
                var curPos = $(this).position();
                $(this).parent().prepend('<span class="wptm_tooltipcontent_show" style="">' + $(this).find(".wptm_tooltipcontent").html() +'</span>');
                var curTT = $(this).parent().find(".wptm_tooltipcontent_show");
                var curTT_left = ( curPos.left -  curTT.width()/2 + $(this).width()/2);
                curTT.stop(true, true).css("margin-top", "-"+ ( curTT.height()+ 15)+"px" ).css("left", curTT_left+"px" );
            };
            $(this).parent().find(".wptm_tooltipcontent_show").fadeIn();
        });

        $(".wptm_tooltip").mouseleave(function() {
            $(this).siblings("span.wptm_tooltipcontent_show").fadeOut();
        });

        // hightlight function
        $('.wptm_table').each(function () {
            if ($(this).data('hightlight') === 1) {
                var $tablesTable = $(this).find('table#wptmTbl' + $(this).data('id'));
                $tablesTable.wholly({
                    highlightHorizontal: 'droptables-highlight-horizontal',
                    highlightVertical: 'droptables-highlight-vertical'
                });
            }
        });

        $(".wptmresponsive.wptmtable table").each(function() {
            if ($(this).find('tfoot').length > 0) {
                var colss = $(this).find('tr:first-child th:not(".form-horizontal")');
                if(colss.length===0){
                    colss = $(this).find('tr:first-child td:not(".form-horizontal")');
                }
                var width = 0;
                colss.each(function (i, v) {
                    width += $(v).filter(":visible").outerWidth();
                });
                $(this).width(width);
            }
        });

        // button cols(hide or show cols)
        $(".wptmresponsive.wptmtable table").each(function( index ) {
            var id_table = $(this).data('id');
            var hideCols= parseInt($(this).data('hidecols'));
            var $wptmTbl = $("#wptmTbl" + id_table );
            if (hideCols) {
                $wptmTbl.restable({
                    type: "hideCols",
                    priority: $(this).data('priority'),
                    afterclick: function () {
                        posRestable(id_table, true);
                    }
                });
                posRestable(id_table, false);

                $wptmTbl.on("resize", function() {
                    posRestable(id_table, false);
                });
            }
            $wptmTbl.find('.form-horizontal').show();
        });
    })
})( jQuery );

jQuery(document).ready(function($) {
    $(".wptm_table .download_wptm").unbind('click').click(function () {
        var id_table = $(this).parents('.wptm_table').data('id');
        var url = $(this).data('href') + 'task=sitecontrol.export&id=' + id_table + '&format_excel=xlsx&onlydata=0';
        $.fileDownload(url, {
            failCallback: function (html, url) {
            }
        });
    });
});