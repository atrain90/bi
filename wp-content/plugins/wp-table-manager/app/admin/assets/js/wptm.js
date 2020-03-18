/**
 * Wptm
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package Wptm
 * @copyright Copyright (C) 2014 JoomUnited (http://www.joomunited.com). All rights reserved.
 * @copyright Copyright (C) 2014 Damien Barrère (http://www.crac-design.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

jQuery(document).ready(function ($) {
    var isSelectionProcess = false; //hack because minicolos trigger change when value modified by js
    var availableHeight    = 0;
    var availableWidth     = 0;

    var F_name = ["january", "february", "march", "april", "may", "june", "july", "august", "september", "october", "november", "december"];
    var M_name = ["jan", "feb", "mar", "apr", "may", "jun", "jul", "aug", "sept", "oct", "nov", "dec"];
    var l_name = ["sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday"];
    var D_name = ["sun", "mon", "tue", "wed", "thu", "fri", "sat"];

    var replace_unit            = {};
    var text_replace_unit       = {};
    var date_format             = [];
    var check_value_data        = true;
    var string_currency_symbols = '';
    var selectFetch             = {};
    var oldAlternate            = {};
    var changeAlternate         = [];
    var checkChangeAlternate    = [];

    var regex2 = new RegExp('([a-zA-Z]+)([0-9]+)');
    var regex3 = new RegExp('([a-zA-Z]+)([0-9]+)', 'g');

    /* List of supported formulas */
    var regex = new RegExp('^=(DATE|DAY|DAYS|DAYS360|AND|OR|XOR|SUM|COUNT|MIN|MAX|AVG|CONCAT|date|day|days|days360|and|or|xor|sum|count|min|max|avg|concat)\\((.*?)\\)$');

    if (typeof (Wptm) == 'undefined') {
        Wptm = {};
        Wptm.can = {};
        Wptm.can.create = true;
        Wptm.can.edit = true;
        Wptm.can.delete = true;
        Wptm.selection = {};
        Wptm.value_unit_chart = [];
        Wptm.hyperlink = {};
    } else {
        Wptm.value_unit_chart = [];
    }

    if (typeof (Wptm.can) == 'undefined') {
        Wptm.can = {};
        Wptm.can.create = true;
        Wptm.can.edit = true;
        Wptm.can.delete = true;
        Wptm.hyperlink = {};
    }

    if (typeof(wptm_isAdmin) == 'undefined') {
        wptm_isAdmin = false;
    }

    var $mycategories = $('#mycategories');
    var $pwrapper = $('#pwrapper');
    var $categoriesList = $('#categorieslist');
    var $tableContainer = $('#tableContainer');
    var $wptm_create_new = $('#wptm_create_new');
    var $alternating_color = $('#alternating_color');

    $categoriesList.find('li.dd-item:not(".hasRole"):not(".wptmtable")').each(function () {
        if ($(this).find('li.hasRole').length < 1){
            $(this).remove();
        };
    });

    // is writing
    if (typeof idUser !== 'undefined' && idUser !== null) {
        var listUserEdit = {};
    }

    var userRole = window.document.getElementById('category-own-select');

    // functions edit role user
    if ($(userRole).length < 1) {
        var $wptm_category_own = $('#wptm_category_own');
        var setTypeOwn;
        // check user role category
        if ($wptm_category_own.length > 0) {
            userRole = window.parent.document.getElementById('category-own-select');
            setTypeOwn = 0;
            // get list data user role category
            listUserEdit = $(userRole).data('list');
            listUserEdit = typeof listUserEdit === 'string' ? jQuery.parseJSON(listUserEdit) : listUserEdit;
            $wptm_category_own.find('.list_user .checkbox' + parseInt(listUserEdit[0])).prop("checked", true);
        }

        // check user role table
        if ($wptm_category_own.length < 1) {
            $wptm_category_own = $('#wptm_table_own');
            setTypeOwn = 1;

            userRole = window.parent.document.getElementById('table-own-select');
            // get list data user role category
            listUserEdit = $(userRole).data('list');
            listUserEdit = typeof listUserEdit === 'string' ? jQuery.parseJSON(listUserEdit) : listUserEdit;
            $wptm_category_own.find('.list_user .checkbox' + parseInt(listUserEdit[0])).prop("checked", true);
        }

        //search user
        $('#search_user').on('click', function (e) {
            e.preventDefault();
            var textSearch = $('#content_user').val();
            var textName = '';
            var textMail = '';
            $('.list_user table tbody tr').each(function () {
                textName = ($(this).find('.column-username strong').html()).trim() + '|';
                textMail = ($(this).find('.column-email').html()).trim();
                if ((textName.concat(textMail)).indexOf(textSearch) < 0) {
                    $(this).hide();
                }
                if (textSearch === '') {
                    $(this).show();
                }
            });
        });

        //list users by role
        $('.list_user .subsubsub>li>a').on('click', function (e) {
            e.preventDefault();
            var keyRole = $(this).data('role');
            $('.list_user .subsubsub>li>a.active').removeClass('active');
            $(this).addClass('active');
            $('.list_user table tbody tr').each(function () {
                $(this).hide();
                if ($(this).hasClass(keyRole) || keyRole === 'all') {
                    $(this).show();
                }
            });
        });

        // check all user
        (checkAll = function () {
            if ($('.list_user .checkbox:visible').length > $('.list_user .checkbox:visible:checked').length) {
                $('#select_all').addClass('notAll');
                $('#select_all').prop("checked", false);
            } else {
                $('#select_all').removeClass('notAll');
                $('#select_all').prop("checked", true);
            }

            $('#select_all').on('click', function (e) {
                if ($(this).hasClass('notAll')) {
                    $('.list_user .checkbox:visible:not(".not_checked")').each(function() {
                        $(this).prop("checked", true);
                    });
                    $(this).removeClass('notAll');
                } else {
                    $('.list_user .checkbox:visible:not(".not_checked")').each(function() {
                        $(this).prop("checked", false);
                    });
                    $(this).addClass('notAll');
                }
            });
        });

        checkAll();

        // save data role user category
        (saveDataRole = function () {
            $('#save_category_role').on('click', function() {
                var category = window.parent.document.getElementById('categorieslist');
                var id_category = $(category).find('.hasRole.active').data('id-category');
                var id_table = $(category).find('.wptmtable.active').data('id-table');
                var id = setTypeOwn === 1 ? id_table : id_category;
                var nameUser = '';

                if ($('.list_user .checkbox:checked').length !== 1) {
                    $wptm_category_own.find('.search_user span').text(wptmText.CHANGE_ERROR_ROLE_OWN_CATEGORY);
                    $wptm_category_own.find('.search_user span').fadeIn(200).delay(2000).fadeOut(1000);
                    return;
                }
                $('.list_user .checkbox:checked').each(function() {
                    listUserEdit[0] = $(this).val();
                    nameUser = $(this).parents('td').siblings('td.username').find('strong').html();
                });

                var jsonVar = {
                    data: JSON.stringify(listUserEdit),
                    id: id,
                    type: setTypeOwn
                };
                $.ajax({
                    url: wptm_ajaxurl+"task=user.save",
                    dataType: "json",
                    type: "POST",
                    data: jsonVar,
                    success: function(datas) {
                        if (datas.response === true) {
                            $wptm_category_own.find('.search_user span').text(wptmText.CHANGE_ROLE_OWN_CATEGORY);
                            $wptm_category_own.find('.search_user span').fadeIn(200).delay(2000).fadeOut(1000);
                            setTimeout(function () {
                                $(userRole).val(nameUser);
                                $(userRole).data('list', '{"0": "' + listUserEdit[0] + '"}').attr('data-list', '{"0": "' + listUserEdit[0] + '"}');
                                if (setTypeOwn === 0 && idUser !== parseInt(listUserEdit[0]) && !wptm_permissions.can_edit_category) {
                                    window.parent.location.reload(true);
                                }

                                if (setTypeOwn === 1 && idUser !== parseInt(listUserEdit[0]) && !wptm_permissions.can_edit_tables) {
                                    window.parent.location.reload(true);
                                }
                            },500);
                        } else {
                            $wptm_category_own.find('.search_user span').text(datas.response);
                            $wptm_category_own.find('.search_user span').fadeIn(200).delay(2000).fadeOut(1000);
                        }
                    },
                    error: function(jqxhr, textStatus, error) {
                        bootbox.alert(textStatus + " : " + error, wptmText.Ok);
                    }
                });
            });
        });

        if ($wptm_category_own.length > 0) {
            saveDataRole();
        }
    }

    //Categories toggle button
    $('#cats-toggle').toggle(
        function () {
            $mycategories.animate({left: -260}, 50, function () {
                $pwrapper.css({'margin-left': 65});

                $pwrapper.find('.ht_clone_top.handsontable').css({'left': parseInt($pwrapper.find('.ht_clone_top.handsontable').css('left')) - 265});
                $pwrapper.find('.ht_clone_left.handsontable').css({'left': parseInt($pwrapper.find('.ht_clone_left.handsontable').css('left')) - 265});
                $pwrapper.find('.ht_clone_corner.handsontable').css({'left': parseInt($pwrapper.find('.ht_clone_corner.handsontable').css('left')) - 265});

                $(this).addClass('mycategories-hide');
                setTimeout(function () {
                    resizeTable();
                }, 500);
            });
            $(this).html('<span class="dashicons dashicons-arrow-right-alt2">');
        },
        function () {
            $mycategories.animate({left: 10}, 100, function () {
                $pwrapper.css({'margin-left': '320px'});
                $pwrapper.find('.ht_clone_top.handsontable').css({'left': parseInt($pwrapper.find('.ht_clone_top.handsontable').css('left')) + 265});
                $pwrapper.find('.ht_clone_left.handsontable').css({'left': parseInt($pwrapper.find('.ht_clone_left.handsontable').css('left')) + 265});
                $pwrapper.find('.ht_clone_corner.handsontable').css({'left': parseInt($pwrapper.find('.ht_clone_corner.handsontable').css('left')) + 265});

                $(this).removeClass('mycategories-hide');
                setTimeout(function () {
                    resizeTable();
                }, 500);
            });
            $(this).html('<span class="dashicons dashicons-arrow-left-alt2">');
        }
    );

    /* init menu actions */
    initMenu();

    /* Load nestable */

    if (typeof gcaninsert !== 'undefined' && Wptm.can.edit) {
        if (!!wptm_permissions.can_edit_category || !!wptm_permissions.can_edit_own_category) {
            $('.nested').nestable().on('change', function (event, e) {
                pk = $(e).data('id-category');
                if ($(e).prev('li').length === 0) {
                    position = 'first-child';

                    if ($(e).parents('li').length === 0) {
                        //root
                        ref = 0;
                    } else {
                        ref = $(e).parents('li').data('id-category');
                    }
                } else {
                    position = 'after';
                    ref = $(e).prev('li').data('id-category');
                }
                $.ajax({
                    url: wptm_ajaxurl + "task=categories.order&pk=" + pk + "&position=" + position + "&ref=" + ref,
                    type: "POST"
                }).done(function (data) {
                    result = jQuery.parseJSON(data);

                    if (result.response === true) {
                        $categoriesList.find('li').removeClass('active');
                        $(e).addClass('active');
                        $(e).parents('ol.dd-list.nav').find('ol.dd-list.show').removeClass('show');
                        $(e).parents('ol.dd-list:not(".nav")').addClass('show');
                        updatepreview();
                    } else {
                        bootbox.alert(result.response, wptmText.Ok);
                    }
                });
            });
        }

        if (Wptm.collapse === true) {
            $('.nested').nestable('collapseAll');
        }
    }
    if (!!wptm_permissions.can_edit_tables) {
        $(".wptm-tables-list").sortable({
            axis: 'y',
            revert: false,
            items: "> li.wptmtable",
            connectWith: ".wptm-tables-list",
            start: function (event, ui) {
                ui.item.addClass('sorting');
            },
            stop: function (event, ui) {
                setInterval(function () {
                    ui.item.removeClass('sorting');
                }, 1000);
            },
            update: function (event, ui) {
                var sortedIDs = $(this).sortable("toArray", {attribute: "data-id-table"});
                $.ajax({
                    url: wptm_ajaxurl + "task=table.order&data=" + sortedIDs.join(),
                    type: "POST"
                }).done(function (data) {
                    result = jQuery.parseJSON(data);
                    if (result.response === true) {
                        //do nothing
                    } else {
                        bootbox.alert(result.response, wptmText.Ok);
                    }
                });
            }
        });
    }

    // $(".wptm-tables-list li.wptmtable").draggable();
    $categoriesList.find("li.dd-item > .dd-content").droppable({
            hoverClass: "dd-content-hover",
            drop: function (event, ui) {
                $(this).addClass("ui-state-highlight");
                cat_target = $(event.target).parent().data("id-category");
                id_table = $(ui.draggable).data("id-table");
                if (typeof listRoleCategory[cat_target] === 'undefined') {
                    $(this).droppable("option", "disabled", true);
                    return false;
                }
                is_active = $(ui.draggable).hasClass('active');
                $.ajax({
                    url: wptm_ajaxurl + "task=table.changeCategory&id=" + id_table + "&category=" + cat_target
                }).done(function (data) {
                    result = jQuery.parseJSON(data);
                    if (result.response === true) {
                        //move to new category
                        $(event.target).parent().find("ul.wptm-tables-list").prepend($(ui.draggable));
                        $(ui.draggable).css('top', '').css('left', ''); //reset offset position

                        if (is_active) {
                            $categoriesList.find('li').removeClass('active');
                            $(event.target).parent().addClass('active');
                            $(event.target).parent().find('ul.wptm-tables-list li:first').addClass('active');
                        }
                    } else {
                        bootbox.alert(result.response, wptmText.Ok);
                    }
                });

            }
        }
    );

    /* --- Center part: table edition --- */
    var autosaveNotification;
    var dataReadOnly;

    //Check what is loaded via editor
    if (typeof (gcaninsert) !== 'undefined' && gcaninsert === true) {

        if (typeof (window.parent.tinyMCE) !== 'undefined') {

            if (window.parent.tinyMCE.activeEditor == null) {
                return;
            }
            var content = window.parent.tinyMCE.activeEditor.selection.getContent();
            exp = '<img.*data\-wptmtable="([0-9]+)".*?>';
            var table = content.match(exp);
            Wptm.selection = new Array();
            Wptm.selection.content = content;
            if (table !== null) {
                if ($categoriesList.find('.wptmtable[data-id-table=' + table[1] + ']')) {
                    $categoriesList.find('.wptmtable[data-id-table=' + table[1] + ']').addClass('active');
                    updatepreview(table[1]);

                    exp2 = '<img.*data\-wptm\-chart="([0-9]+)".*?>';
                    table2 = content.match(exp2);
                    if (table2 !== null) {
                        Wptm.chart_id = table2[1];
                    }
                } else {
                    $categoriesList.find('li.hasRole:first').addClass('active');
                    updatepreview();
                    exp2 = '<img.*data\-wptm\-chart="([0-9]+)".*?>';
                    table2 = content.match(exp2);
                    if (table2 !== null) {
                        Wptm.chart_id = table2[1];
                    }
                }
            }
            else {
                updatepreview();
            }
        }
        //DropEditor
        else if (typeof window.parent.CKEDITOR != 'undefined') {
            var ckEditor = window.parent.CKEDITOR.instances[e_name];
            imgElement = ckEditor.getSelection().getSelectedElement();
            if (typeof imgElement != "undefined" && imgElement != null) {
                table_id = imgElement.getAttribute('data-wptmtable');
                if (table_id !== null) {
                    $categoriesList.find('.wptmtable[data-id-table=' + table_id + ']').addClass('active');
                    updatepreview(table_id);
                    chart_id = imgElement.getAttribute('data-wptm-chart');
                    if (chart_id !== null) {
                        Wptm.chart_id = chart_id;
                    }
                } else {
                    updatepreview();
                }
            } else {
                updatepreview();
            }
        } //end DropEditor
    } else {
        /* Load gallery */
        if (typeof idTable === 'undefined') {
            return false;
        } else if (idTable) {
            updatepreview(idTable);
        } else {
            updatepreview();
        }

    }

    /** Check new version **/
    /*
     $.getJSON(wptm_ajaxurl+"task=update.check", function(data) {
     if (data !== false) {
     $('#updateGroup').show().find('span.versionNumber').html(data);
     }
     }); */

    $('#hideUpdateBtn').click(function (e) {
        e.preventDefault();
        var today = new Date(), expires = new Date();
        expires.setTime(today.getTime() + (7 * 24 * 60 * 60 * 1000));
        document.cookie = "com_wptm_noCheckUpdates =true; expires=" + expires.toGMTString();
        $('#updateGroup').hide();
    });

    if ($('#headMainCss').length === 0) {
        $('head').append('<style id="headMainCss"></style>');
    }
    var styleToRender = [];
    if ($('#headCss').length === 0) {
        $('head').append('<style id="headCss"></style>');
    }

    /**
     * Reload a category preview
     */
    function updatepreview(id, ajaxCallBack) {
        var selectedElem;
        var $tableContainer = $('#tableContainer');
        if (typeof (id) !== "undefined") {
            $categoriesList.find('.dd-item').removeClass('active');
            selectedElem = $categoriesList.find('ul.wptm-tables-list li[data-id-table=' + id + ']');
            selectedElem.addClass('active');
            selectedElem.parents('.dd-item').first().addClass('active');
        } else {
            id = $categoriesList.find('li.active > ul.wptm-tables-list li:first').data('id-table');
            $categoriesList.find('li.active > ul.wptm-tables-list li:first').addClass('active');
            selectedElem = '';
        }

        // check role user edit/own
        if ($(userRole).length > 0){
            var id_category = $categoriesList.find('.hasRole.active').data('id-category');
            listUserEdit = JSON.stringify(listRoleCategory[id_category]);

            if (typeof listUserEdit === 'undefined' || listUserEdit === 'undefined') {
                listUserEdit = '{"0": "-1"}';
            }
            $('#category-own-select').attr('data-list', listUserEdit);
        }


        if (typeof (id) === 'undefined' && typeof (selectedElem) !== 'undefined' && selectedElem.length === 0) {
            $('#titleError,#rightcol').hide();
            $tableContainer.html(wptmText.LAYOUT_WPTM_SELECT_ONE);
            return;
        }
        $('#titleError,#rightcol').show();
        $tableContainer.empty();
        $tableContainer.handsontable('destroy');

        // make the Table tab active
        $('ul#mainTable li a:first').tab('show');

        loading('#wpreview');
        url = wptm_ajaxurl + "view=table&format=json&id=" + id;
        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
        }).done(function (data) {
            Wptm.id = id;
            Wptm.container = $tableContainer;
            cols = [];
            rows = [];

            if (data.datas === "") {
                var tableData = [
                    ["", "", "", "", "", "", "", "", "", ""],
                    ["", "", "", "", "", "", "", "", "", ""],
                    ["", "", "", "", "", "", "", "", "", ""],
                    ["", "", "", "", "", "", "", "", "", ""],
                    ["", "", "", "", "", "", "", "", "", ""],
                    ["", "", "", "", "", "", "", "", "", ""],
                    ["", "", "", "", "", "", "", "", "", ""],
                    ["", "", "", "", "", "", "", "", "", ""]
                ];
                delete Wptm.style;
            } else {
                try {
                    tableData = $.parseJSON(data.datas);
                } catch (err) {
                    var tableData = [
                        ["", "", "", "", "", "", "", "", "", ""],
                        ["", "", "", "", "", "", "", "", "", ""],
                        ["", "", "", "", "", "", "", "", "", ""],
                        ["", "", "", "", "", "", "", "", "", ""],
                        ["", "", "", "", "", "", "", "", "", ""],
                        ["", "", "", "", "", "", "", "", "", ""],
                        ["", "", "", "", "", "", "", "", "", ""],
                        ["", "", "", "", "", "", "", "", "", ""]
                    ];
                }

                Wptm.style = $.parseJSON(data.style);
                Wptm.css = data.css.replace(/\\n/g, "\n");
            }

            dataReadOnly = false;
            $('#rightcol').find('.table-styles').show();
            $('#rightcol').find('.spreadsheet_sync').show();
            Wptm.hyperlink = {};
            if (data.params === "" || data.params === null || data.params.length == 0) {
                mergeCellsSetting = true;
            } else {
                if (typeof (data.params) == 'string') {
                    data.params = $.parseJSON(data.params);
                }
                if (typeof data.params.hyperlink !== 'undefined') {
                    if (typeof data.params.hyperlink === 'string') {
                        data.params.hyperlink = $.parseJSON(data.params.hyperlink);
                    }
                    Wptm.hyperlink = data.params.hyperlink;
                    $.each(data.params.hyperlink, function (index, value) {
                        var rowCol = index.split("!");
                        tableData[rowCol[0]][rowCol[1]] = '<a target="_blank" href="' + value.hyperlink + '">' + value.text + '</a>';
                    });
                }

                try {
                    mergeCellsSetting = $.parseJSON(data.params.mergeSetting);
                } catch (e) {
                    console.log(data.params.mergeSetting, e);
                    mergeCellsSetting = [];
                }
                if (mergeCellsSetting == null) mergeCellsSetting = [];

                if (typeof data.params.table_type != 'undefined' && data.params.table_type == 'mysql') {
                    dataReadOnly = true;

                    $('#rightcol').find('.table-styles').hide();
                    $('#rightcol').find('.spreadsheet_sync').hide();
                }
                $(".dbtable_params").show();
            }

            if (typeof (Wptm.style) === 'undefined' || Wptm.style === null) {
                $.extend(Wptm, {
                    style: {
                        table: {},
                        rows: {},
                        cols: {},
                        cells: {}
                    },
                    css: ''
                });
            }

            if (typeof (Wptm.style.rows) === 'undefined' || Wptm.style.rows === null) {
                Wptm.style.rows = {};
            }

            if (typeof (Wptm.style.cols) === 'undefined' || Wptm.style.cols === null) {
                Wptm.style.cols = {};
            }

            if (typeof (Wptm.style.cells) === 'undefined' || Wptm.style.cells === null) {
                Wptm.style.cells = {};
            }

            $defaultParams = {
                'use_sortable': '0',
                'default_sortable': '0',
                'default_order_sortable': '0',
                'table_align': 'center',
                'responsive_type': 'scroll',
                'freeze_col': 0,
                'freeze_row': 0,
                'enable_filters': 0,
                'spreadsheet_url': '',
                'spreadsheet_style': 0,
                'download_button': 0,
                'date_formats': default_value.date_formats,
                'symbol_position': parseInt(default_value.symbol_position),
                'currency_symbol': default_value.currency_symbol,
                'decimal_symbol': default_value.decimal_symbol,
                'decimal_count': parseInt(default_value.decimal_count),
                'thousand_symbol': default_value.thousand_symbol
            };
            Wptm.style.table = $.extend({}, $defaultParams, Wptm.style.table);
            if (typeof selectFetch.auto_sync !== 'undefined') {
                Wptm.style.table.auto_sync = selectFetch.auto_sync;
                Wptm.style.table.spreadsheet_style = selectFetch.spreadsheet_style;
                Wptm.style.table.spreadsheet_url = selectFetch.spreadsheet_url;
                selectFetch = {};
            }
            Wptm.style.table.default_order_sortable = (typeof Wptm.style.table.default_order_sortable === 'undefined') ? '0' : Wptm.style.table.default_order_sortable;
            Wptm.style.table.default_sortable = (typeof Wptm.style.table.default_sortable === 'undefined') ? '0' : Wptm.style.table.default_sortable;

            string_currency_symbols = Wptm.style.table.currency_symbol.replace(/ /g, "");
            // create string reg currency symbols
            replace_unit = new RegExp('[' + string_currency_symbols.replace(/,/g, "|") + ']', "g");

            // create string reg have not currency symbols
            text_replace_unit = '[^a-zA-Z|' + string_currency_symbols.replace(/,/g, "|^") + ']';
            text_replace_unit = new RegExp(text_replace_unit, "g");
            date_format = Wptm.style.table.date_formats.match(/[a-zA-Z|\\]+/g);

            $('#jform_css').val(Wptm.css);
            $('#jform_css').change();
            parseCss();

            if (typeof (Wptm.style.table.alternateColorValue) === 'undefined' || typeof Wptm.style.table.alternateColorValue[0] === 'undefined') {
                var styleRows = null;
                alternating.setAlternateColor(styleRows);
            }

            oldAlternate = {};
            if (_.size(oldAlternate) < 1) {
                oldAlternate = $.extend({}, Wptm.style.table.alternateColorValue);
            }
            checkChangeAlternate = [];

            initHandsontable(tableData);

            $(".wptm_warning").remove();

            $('#tableTitle').html(data.title);
            if (typeof Wptm.style.table.spreadsheet_url != 'undefined' && Wptm.style.table.spreadsheet_url != "" && typeof Wptm.style.table.auto_sync != 'undefined' && Wptm.style.table.auto_sync != "0") {
                $('h3#titleError').after('<div class="wptm_warning"><p>' + wptmText.notice_msg_table_syncable + '</p></div>');
            }

            if (dataReadOnly) {
                $('h3#titleError').after('<div class="wptm_warning"><p>' + wptmText.notice_msg_table_database + '</p></div>');
            }

            initBtnPosition();

            $(Wptm.container).handsontable('render');
            $(Wptm.container).handsontable('selectCell', 0, 0);
            resizeTable();

            $("#fetch_spreadsheet").unbind('click').click(function (e) {
                e.preventDefault();

                tableId = $('li.wptmtable.active').data('id-table');
                spreadsheet_url = $("#jform_spreadsheet_url").val();

                loading('#wpreview');
                var auto_sync, spreadsheet_style;

                if (Wptm.style.table.auto_sync != '1') {
                    auto_sync = 0;
                } else {
                    auto_sync = 1;
                }

                if (Wptm.style.table.spreadsheet_style != '1') {
                    spreadsheet_style = 0;
                } else {
                    spreadsheet_style = 1;
                }

                url = wptm_ajaxurl + "task=excel.fetchSpreadsheet&id=" + tableId;
                var jsonVar = {
                    spreadsheet_url: encodeURI(spreadsheet_url),
                    id: Wptm.id,
                    sync: auto_sync,
                    style: spreadsheet_style
                };
                $.ajax({
                    url: url,
                    type: "POST",
                    data: jsonVar
                }).done(function (data) {
                    var result = jQuery.parseJSON(data);
                    if (result.response === true) {
                        selectFetch.auto_sync = result.datas.sync;
                        selectFetch.spreadsheet_style = result.datas.style;
                        selectFetch.spreadsheet_url = Wptm.style.table.spreadsheet_url;
                        updatepreview(tableId);
                    }
                    rloading('#wpreview');
                });
            });

            if (typeof ajaxCallBack == "function") {
                ajaxCallBack();
            }

            rloading('#wpreview');

            if ($(userRole).length > 0 && parseInt(data.author) !== 0){
                $('#table-own-select').attr('data-list', '{"0": "' + parseInt(data.author) + '"}');
            }

            //set position for color picker
            $('.wp-picker-container button').on('click', function (e) {
                var top = $(this).position().top;
                $(this).siblings('.wp-picker-holder').css({ top: top + 'px' });
            });


            /*set event show/hide alternating_color*/

            $('#select_alternating_color').find('input').on('click', function () {
                $('#parent_configTable').hide();
                $('#alternating_color').show();
                if (_.size(oldAlternate) < 1 || typeof Wptm.style.table.alternateColorValue === 'undefined') {
                    var valueRange = 'a1:';
                    valueRange += String.fromCharCode(97 + _.size(Wptm.style.cols) -1);
                    valueRange += _.size(Wptm.style.rows);
                    $('#alternating_color').find('#cellRangeLabelAlternate').val(valueRange);
                    $('#alternating_color').find('#cellRangeLabelAlternate').trigger('change');
                } else {
                    var selection = $(Wptm.container).handsontable('getSelected');

                    if (!selection) {
                        return;
                    }

                    if (selection[0] > selection[2]) {
                        selection = [selection[2], selection[3], selection[0], selection[1]];
                    }
                    getSelectedVal(selection, $('#cellRangeLabelAlternate'));
                }
            });
            $('#alternating_color').find('.alternating_color_top .cancel').on('click', function () {
                $('#parent_configTable').show();
                $('#alternating_color').hide();
            });
            $("body").click(function (event) {
                var check = $(event.target).parents('#alternating_color').length;
                check = $(event.target).parents('#select_alternating_color').length > 0 ? 1 : check;
                check = $(event.target).parents('.referCell').length > 0 ? 1 : check;
                check = $(event.target).parents('#mainTabContent').length > 0 ? 1 : check;
                if (check < 1) {
                    $('#parent_configTable').show();
                    $('#alternating_color').hide();
                }
            });
        });
    }

    /*print getSelect cell to element*/
    function getSelectedVal(dataSelect, element) {
        var valueRange = '';

        valueRange += String.fromCharCode(65 + dataSelect[1]);
        valueRange += dataSelect[0] + 1;

        valueRange += ':';

        valueRange += String.fromCharCode(65 + dataSelect[3]);
        valueRange += dataSelect[2] + 1;
        element.val(valueRange);
    }

    initHandsontable = function (tableData) {
        var needSaveAfterRender = false;

        Wptm.container.handsontable({
            data: tableData,
            startRows: 5,
            startCols: 5,
            renderAllRows: true,
            colHeaders: true,
            rowHeaders: true,
            search: true,
            fixedRowsTop: (Wptm.style.table.responsive_type == 'scroll' ) ? parseInt(Wptm.style.table.freeze_row) : 0,
            fixedColumnsLeft: (Wptm.style.table.responsive_type == 'scroll' ) ? parseInt(Wptm.style.table.freeze_col) : 0,
            manualColumnResize: (Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author)),
            manualRowResize: (Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author)),
            outsideClickDeselects: false,
            renderer: customRenderer,
            columnSorting: false,
            undo: true,
            mergeCells: mergeCellsSetting,
            readOnly: ( ((Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author)) && !dataReadOnly) ? false : true),
            contextMenu: ( ((Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author)) && !dataReadOnly) ? ["row_above", "row_below", "---------", "col_left", "col_right", "---------", "remove_row", "remove_col", "---------", "undo", "---------", "mergeCells"] : false),
            editor: CustomEditor,
            beforeChange: function (changes, source) {
                for (var i = changes.length - 1; i >= 0; i--) {

                    if (!validateCharts(changes[i])) {
                        bootbox.alert(wptmText.CHANGE_INVALID_CHART_DATA, wptmText.Ok);
                        return false;
                    }

                }
            },
            afterChange: function (change, source) {
                if (wptm_isAdmin) {
                    loadTableContructor();
                }
                loadCharts();
                default_sortable(tableData);
                if (source === 'loadData' || !(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
                    return; //don't save this change
                }
                clearTimeout(autosaveNotification);
                saveChanges();
            },
            beforeKeyDown: function (e) {
                if ($categoriesList.find("span.title.editable").length > 0) {
                    e.stopImmediatePropagation();
                }
            },
            afterColumnResize: function (width, col) {
                saveChanges();
            },
            afterRowResize: function (height, row) {
                saveChanges();
            },
            beforeRender: function () {
                styleToRender = '';
            },
            afterRender: function () {
                var parser = new (less.Parser);
                content = '#preview .handsontable .ht_master .htCore {' + styleToRender + '}';
                if (Wptm.style.table.responsive_type == 'scroll' && Wptm.style.table.freeze_row) {
                    content += ' #preview .handsontable .ht_clone_top .htCore {' + styleToRender + '}';
                }
                if (Wptm.style.table.responsive_type == 'scroll' && Wptm.style.table.freeze_col) {
                    content += ' #preview .handsontable .ht_clone_left .htCore {' + styleToRender + '}';
                }
                if (Wptm.style.table.responsive_type == 'scroll' && Wptm.style.table.freeze_row && Wptm.style.table.freeze_col) {
                    content += ' #preview .handsontable .ht_clone_corner .htCore {' + styleToRender + '}';
                }

                parser.parse(content, function (err, tree) {
                    if (err) {
                        //Here we can throw the erro to the user
                        return false;
                    } else {
                        Wptm.css = $('#jform_css').val();
                        if ($('#headMainCss').length === 0) {
                            $('head').append('<style id="headMainCss"></style>');
                        }
                        $('#headMainCss').text(tree.toCSS());
                        return true;
                    }
                });
                $('table.htCore a').unbind('click').click(function (e) {
                    e.preventDefault();
                });
                pushDims();
                if (needSaveAfterRender === true) {
                    saveChanges();
                    needSaveAfterRender = false;
                }
                initBtnPosition();
                //fix row height of overlay table
                var $tableContainer = $('#tableContainer');
                var i;
                for (i = 0; i < $tableContainer.find('.ht_master .htCore tr').length; i++) {
                    var h = $tableContainer.find('.ht_master .htCore tr').eq(i).height();
                    $tableContainer.find('.ht_clone_left .htCore tr').eq(i).height(h);
                }
            },
            afterCreateRow: function (index, amount) {
                selection = $(Wptm.container).handsontable('getSelected');
                if (typeof (Wptm.style.cells) !== 'undefined') {
                    var newCells = {};
                    for (cell in Wptm.style.cells) {
                        if (Wptm.style.cells[cell][0] < index) {
                            //no changes to cells
                            newCells[cell] = clone(Wptm.style.cells[cell]);
                        } else if (Wptm.style.cells[cell][0] === index) {

                            if (index === selection[0]) {
                                //inserted before
                                newCells[cell] = clone(Wptm.style.cells[cell]);
                                newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]] = [Wptm.style.cells[cell][0] + amount, Wptm.style.cells[cell][1], clone(Wptm.style.cells[(Wptm.style.cells[cell][0]) + '!' + Wptm.style.cells[cell][1]][2])];
                            } else {
                                //inserted after
                                newCells[cell] = [Wptm.style.cells[cell][0], Wptm.style.cells[cell][1], clone(Wptm.style.cells[(Wptm.style.cells[cell][0] - amount) + '!' + Wptm.style.cells[cell][1]][2])];
                                newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]] = [Wptm.style.cells[cell][0] + amount, Wptm.style.cells[cell][1], clone(Wptm.style.cells[(Wptm.style.cells[cell][0]) + '!' + Wptm.style.cells[cell][1]][2])];
                            }

                        } else {
                            newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]] = [Wptm.style.cells[cell][0] + amount, Wptm.style.cells[cell][1], clone(Wptm.style.cells[cell][2])];
                        }
                        //check exist cell have AlternateColor
                        if (typeof newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]] !== 'undefined') {
                            if (typeof (newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]][2].AlternateColor) !== 'undefined') {
                                if (Wptm.style.cells[cell][0] + amount
                                    > oldAlternate[newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]][2].AlternateColor].selection[2])
                                {
                                    oldAlternate[newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]][2].AlternateColor].selection[2] = Wptm.style.cells[cell][0] + amount;
                                } else if (Wptm.style.cells[cell][0] + amount
                                    < oldAlternate[newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]][2].AlternateColor].selection[0])
                                {
                                    oldAlternate[newCells[(Wptm.style.cells[cell][0] + amount) + '!' + Wptm.style.cells[cell][1]][2].AlternateColor].selection[0] = Wptm.style.cells[cell][0] + amount;
                                }
                            }
                        }
                    }
                    if ($(Wptm.container).handsontable('countRows') === index + amount) {
                        //row added at the bottom
                        for (ij = 0; ij < $(Wptm.container).handsontable('countCols'); ij++) {
                            if (typeof (Wptm.style.cells[selection[0] + '!' + ij]) !== 'undefined') {
                                newCells[index + '!' + ij] = [index, ij, clone(Wptm.style.cells[selection[0] + '!' + ij][2])];
                                //check exist cell have AlternateColor
                                if (typeof (newCells[index + '!' + ij][2].AlternateColor) !== 'undefined') {
                                    if (index > oldAlternate[newCells[index + '!' + ij][2].AlternateColor].selection[2]) {
                                        oldAlternate[newCells[index + '!' + ij][2].AlternateColor].selection[2] = index;
                                    } else if (index < oldAlternate[newCells[index + '!' + ij][2].AlternateColor].selection[0]) {
                                        oldAlternate[newCells[index + '!' + ij][2].AlternateColor].selection[0] = index;
                                    }
                                }
                            }
                        }
                    }
                    Wptm.style.cells = clone(newCells);
                    // update merged row index
                    var ht = Wptm.container.handsontable('getInstance');
                    var mergeSetting = ht.mergeCells.mergedCellInfoCollection;
                    if ($.isArray(mergeSetting) !== false && mergeSetting.length > 0) {
                        var newMergeSetting = [];
                        var hasChange = false;
                        mergeSetting.forEach(function (e) {
                            var object2 = Object.assign({}, e);
                            if (e.row > index) {
                                object2.row = e.row + amount;
                                hasChange = true;
                                newMergeSetting.push(object2);
                            } else if (e.row == index) {
                                if (index === selection[0]) {
                                    //inserted before
                                    object2.row = e.row + amount;
                                } else {
                                    //inserted after
                                    object2.rowspan = e.rowspan + amount;
                                }
                                hasChange = true;
                                newMergeSetting.push(object2);
                            } else {
                                newMergeSetting.push(object2);
                            }
                        })
                        if (hasChange) {
                            updateMergeSetting(newMergeSetting);
                        }
                    }
                }
                needSaveAfterRender = true;
            },
            afterCreateCol: function (index, amount) {
                var selection = $(Wptm.container).handsontable('getSelected');
                if (typeof (Wptm.style.cells) !== 'undefined') {
                    var newCells = {};
                    var cell;
                    for (cell in Wptm.style.cells) {
                        if (Wptm.style.cells[cell][1] < index) {
                            //no changes to cells
                            newCells[cell] = clone(Wptm.style.cells[cell]);
                        } else if (Wptm.style.cells[cell][1] === index) {

                            if (index === selection[1]) {
                                //inserted before
                                newCells[cell] = clone(Wptm.style.cells[cell]);
                                newCells[Wptm.style.cells[cell][0] + '!' + (Wptm.style.cells[cell][1] + amount)] = [Wptm.style.cells[cell][0], Wptm.style.cells[cell][1] + amount, clone(Wptm.style.cells[(Wptm.style.cells[cell][0]) + '!' + Wptm.style.cells[cell][1]][2])];
                            } else {
                                //inserted after
                                newCells[cell] = [Wptm.style.cells[cell][0], Wptm.style.cells[cell][1], clone(Wptm.style.cells[Wptm.style.cells[cell][0] + '!' + (Wptm.style.cells[cell][1] - amount)][2])];
                                newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)] = [Wptm.style.cells[cell][0], Wptm.style.cells[cell][1] + amount, clone(Wptm.style.cells[(Wptm.style.cells[cell][0]) + '!' + Wptm.style.cells[cell][1]][2])];
                            }
                        } else {
                            newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)] = [Wptm.style.cells[cell][0], Wptm.style.cells[cell][1] + amount, clone(Wptm.style.cells[cell][2])];
                        }
                        //check exist cell have AlternateColor
                        if (typeof newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)] !== 'undefined') {
                            if (typeof (newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)][2].AlternateColor) !== 'undefined') {
                                if (Wptm.style.cells[cell][1] + amount
                                    > oldAlternate[newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)][2].AlternateColor].selection[3])
                                {
                                    oldAlternate[newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)][2].AlternateColor].selection[3] = Wptm.style.cells[cell][1] + amount;
                                } else if (Wptm.style.cells[cell][1] + amount
                                    < oldAlternate[newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)][2].AlternateColor].selection[1])
                                {
                                    oldAlternate[newCells[(Wptm.style.cells[cell][0]) + '!' + (Wptm.style.cells[cell][1] + amount)][2].AlternateColor].selection[1] = Wptm.style.cells[cell][1] + amount;
                                }
                            }
                        }
                    }

                    if ($(Wptm.container).handsontable('countCols') === index + amount) {
                        //col added at the right
                        var ij;
                        for (ij = 0; ij < $(Wptm.container).handsontable('countRows'); ij++) {
                            if (typeof (Wptm.style.cells[ij + '!' + selection[1]]) !== 'undefined') {
                                newCells[ij + '!' + index] = [ij, index, clone(Wptm.style.cells[ij + '!' + selection[1]][2])];
                                //check exist cell have AlternateColor
                                if (typeof (newCells[ij + '!' + index][2].AlternateColor) !== 'undefined') {
                                    if (index > oldAlternate[newCells[ij + '!' + index][2].AlternateColor].selection[3]) {
                                        oldAlternate[newCells[ij + '!' + index][2].AlternateColor].selection[3] = index;
                                    } else if (index < oldAlternate[newCells[ij + '!' + index][2].AlternateColor].selection[1]) {
                                        oldAlternate[newCells[ij + '!' + index][2].AlternateColor].selection[1] = index;
                                    }
                                }
                            }
                        }
                    }
                    Wptm.style.cells = clone(newCells);
                }
                needSaveAfterRender = true;
            },
            afterRemoveRow: function (index, amount) {
                if (typeof (Wptm.style.cells) !== 'undefined') {
                    var newCells = {};
                    var cell;
                    for (cell in Wptm.style.cells) {
                        if (Wptm.style.cells[cell][0] > index) {
                            newCells[parseInt(Wptm.style.cells[cell][0] - amount) + '!' + Wptm.style.cells[cell][1]] = [Wptm.style.cells[cell][0] - amount, Wptm.style.cells[cell][1], $.extend({}, Wptm.style.cells[cell][2])];
                        } else if (Wptm.style.cells[cell][0] === index) {

                        } else {
                            newCells[Wptm.style.cells[cell][0] + '!' + Wptm.style.cells[cell][1]] = $.extend([], Wptm.style.cells[cell]);
                        }
                    }
                    Wptm.style.cells = newCells;
                    // update merged row index
                    var ht = Wptm.container.handsontable('getInstance');
                    var mergeSetting = ht.mergeCells.mergedCellInfoCollection;
                    if ($.isArray(mergeSetting) !== false && mergeSetting.length > 0) {
                        var newMergeSetting = [];
                        var hasChange = false;
                        mergeSetting.forEach(function (e) {
                            var object2 = Object.assign({}, e);
                            if (e.row > index) {
                                object2.row = e.row - amount;
                                hasChange = true;
                                newMergeSetting.push(object2);
                            } else if (e.row == index) {
                                var object2 = Object.assign({}, e);
                                object2.rowspan = e.rowspan - amount;
                                if (object2.rowspan >= 1) {
                                    hasChange = true;
                                    newMergeSetting.push(object2);
                                }
                            } else {
                                newMergeSetting.push(object2);
                            }
                        })
                        if (hasChange) {
                            updateMergeSetting(newMergeSetting);
                        }
                    }
                }
                $.each(oldAlternate, function (i, v) {
                    if (typeof v !== 'undefined' && typeof v.selection !== 'undefined') {
                        if (v.selection[2] >= index) {
                            v.selection[2] = v.selection[2] - 1;
                        }
                        if (v.selection[0] > index) {
                            v.selection[0] = v.selection[0] - 1;
                        }
                    }
                });
                needSaveAfterRender = true;
            },
            afterRemoveCol: function (index, amount) {
                if (typeof (Wptm.style.cells) !== 'undefined') {
                    var newCells = {};
                    var cell;
                    for (cell in Wptm.style.cells) {
                        if (Wptm.style.cells[cell][1] > index) {
                            newCells[Wptm.style.cells[cell][0] + '!' + parseInt(Wptm.style.cells[cell][1] - amount)] = [Wptm.style.cells[cell][0], Wptm.style.cells[cell][1] - amount, $.extend({}, Wptm.style.cells[cell][2])];
                        } else if (Wptm.style.cells[cell][1] === index) {

                        } else {
                            newCells[Wptm.style.cells[cell][0] + '!' + Wptm.style.cells[cell][1]] = $.extend([], Wptm.style.cells[cell]);
                        }
                    }
                    Wptm.style.cells = newCells;
                }
                $.each(oldAlternate, function (i, v) {
                    if (typeof v !== 'undefined' && typeof v.selection !== 'undefined') {
                        if (v.selection[3] >= index) {
                            v.selection[3] = v.selection[3] - 1;
                        }
                        if (v.selection[1] > index) {
                            v.selection[1] = v.selection[1] - 1;
                        }
                    }
                });
                needSaveAfterRender = true;
            },
            afterSelection: function (r, c, r2, c2, preventScrolling, selectionLayerLevel) {
                isSelectionProcess = true;
                loadSelection();
                isSelectionProcess = false;
                if ($('#alternating_color').is(":visible")) {
                    $alternating_color.find('.pane-color-tile.active').removeClass('active');

                    /*check exist oldAlternate[count]--> set header/footer checkbox*/
                    var selection = $(Wptm.container).handsontable('getSelected');

                    if (!selection) {
                        return;
                    }

                    if (selection[0] > selection[2]) {
                        selection = [selection[2], selection[3], selection[0], selection[1]];
                    }
                    if (selection[1] > selection[3]) {
                        selection = [selection[0], selection[3], selection[2], selection[1]];
                    }
                    /*check exist oldAlternate[count]--> set header/footer checkbox*/
                    var count = alternating.setNumberAlternate(selection);
                    if (typeof oldAlternate[count] !== 'undefined') {
                        if (oldAlternate[count].header !== '') {
                            $alternating_color.find('.banding-header-checkbox').prop("checked", true);
                        } else {
                            $alternating_color.find('.banding-header-checkbox').prop("checked", false);
                        }
                        if (oldAlternate[count].footer !== '') {
                            $alternating_color.find('.banding-footer-checkbox').prop("checked", true);
                        } else {
                            $alternating_color.find('.banding-footer-checkbox').prop("checked", false);
                        }
                        alternating.getActiveFormatColor(oldAlternate[count]);
                    }

                    getSelectedVal(selection, $('#cellRangeLabelAlternate'));
                }
                $('#rightcol').find('.referCell a').trigger('click');
            },
            afterScrollVertically: function () {
                if ($tableContainer.find('.htContainer').find('.isHtmlCell.current').length > 0) {
                    $tableContainer.find('.handsontableInputHolder').css('visibility', 'hidden');
                    setTimeout(function () {
                        var offset = $tableContainer.find('.htContainer').find('.isHtmlCell.current').offset();
                        var width  = $tableContainer.find('.htContainer').find('.isHtmlCell.current').width();
                        if (typeof offset !== 'undefined') {
                            var position = {top: offset.top - $(window).scrollTop(), left: offset.left + width - $(window).scrollLeft(), position: 'fixed'};
                            $tableContainer.find('.handsontableInputHolder').css(position).css('visibility', 'visible');
                        }
                    }, 500);
                }
            },
            colWidths: function (index) {
                if (checkObjPropertyNested(Wptm.style,'cols',index,1,'width')) {
                    return Wptm.style.cols[index][1].width;
                }
            },
            rowHeights: function (index) {
                if (checkObjPropertyNested(Wptm.style,'rows',index,1,'height')) {
                    return Wptm.style.rows[index][1].height;
                } else {
                    var h = jQuery('#tableContainer .ht_master .htCore tr').eq(index + 1).height();
                    return h;
                }
            },
            undoChangeStyle: function (oldStyle) {
                //alert('after change style');
                Wptm.style = oldStyle;
                var selection = $(Wptm.container).handsontable('getSelected');

                needSaveAfterRender = true;
                $(Wptm.container).handsontable('render');
                $(Wptm.container).handsontable("selectCell", selection[0], selection[1], selection[2], selection[3]);
                resizeTable();
            }
        });

        // search key
        $('#dp-form-search').keypress(function(event) {
            if (event.key === "Enter") {
                $('#search_term').find('.dashicons-search').trigger('click');
            }
        });

        $('#search_term').find('.dashicons-search').click(function () {
            var textSearch = $('#search_term').find('#dp-form-search');
            var queryResult = $(Wptm.container).data('handsontable').search.query(textSearch.val());
            $(Wptm.container).data('handsontable').render();
        });
    }

    updateMergeSetting = function (newMergeSetting) {
        var ht = Wptm.container.handsontable('getInstance');
        ht.mergeCells = new Handsontable.MergeCells(newMergeSetting);
        ht.updateSettings({mergeCells: newMergeSetting});
    }

    //change size parrent table
    resizeTable = function () {
        var offset = $('#tableContainer').offset();
        availableWidth = $(window).width() - offset.left + $(window).scrollLeft() - 310 + (getUrlVar('caninsert') && 15);

        // $('#tableContainer').width(availableWidth);
        $('#tableContainer').width($('#pwrapper').width());
        resizeBtnPosition();
        $(window).scrollTop($(window).scrollTop() + 1); //trigger window scroll event

        $('#tableContainer .htContainer').find('.ht_clone_top').addClass('wpju_scroll_left wpScrollContent');
        $('#tableContainer .htContainer').find('.ht_clone_left').addClass('wpju_scroll_top wpScrollContent');
        $('#tableContainer').wpjuscroll({content: $('#tableContainer .htContainer .ht_master'), option: {width: parseInt($('#tableContainer').width()) -50, height : parseInt($('#tableContainer').height()) - 100}});
    };

    $(window).smartresize(function () {
        resizeTable();
        var $wptmmodal = $(window.parent.document.getElementById('wptmmodal'));
        if ($wptmmodal.length > 0) {
            $wptmmodal.css({'margin': 'auto 5% auto 5%', 'left' : 'unset'});
        }
    });

    $(document).on('wp-collapse-menu', function () {
        resizeTable();
    });

    initBtnPosition = function () {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return;
        }
        if ($('#insertColBtn').length === 0) {
            var btnCol = $('<a href="#" id="insertColBtn">+</a>');
            btnCol.insertBefore($('#tableContainer'));

            $('#insertColBtn')
                .bind("contextmenu", function (e) {
                    e.preventDefault();
                    return false;
                }).unbind('click').bind('click', function () {
                var nbCols = $(Wptm.container).handsontable('countCols');
                var ht = $(Wptm.container).handsontable('getInstance');
                ht.selectCell(1, 1);
                var selection;
                if (nbCols === 0) {
                    $(Wptm.container).handsontable('loadData', [[""]]);
                } else {
                    selection = $(Wptm.container).handsontable('getSelected');
                    $(Wptm.container).handsontable('selectCell', selection[0], nbCols - 1);
                    $(Wptm.container).handsontable('alter', 'insert_col', nbCols);
                }
                saveChanges();
                return false;
            });

        }
        if ($('#insertRowBtn').length === 0) {
            var btnRow = $('<a href="#" id="insertRowBtn">+</a>');
            btnRow.insertAfter($('#tableContainer'));

            $('#insertRowBtn').bind("contextmenu", function (e) {
                e.preventDefault();
                return false;
            }).unbind('click').bind('click', function () {
                var nbRows = $(Wptm.container).handsontable('countRows');
                var ht = $(Wptm.container).handsontable('getInstance');
                ht.selectCell(1, 1);
                var selection;
                if (nbRows === 0) {
                    $(Wptm.container).handsontable('loadData', [[""]]);
                } else {
                    selection = $(Wptm.container).handsontable('getSelected');
                    $(Wptm.container).handsontable('selectCell', nbRows - 1, selection[1]);
                    $(Wptm.container).handsontable('alter', 'insert_row', nbRows);
                }
                // saveChanges(); duplicate saveChange
                return false;
            });

        }
    };

    resizeBtnPosition = function () {
        $('#insertRowBtn')
            .css('left', parseInt(parseInt($('#tableContainer').width())) / 2 - 15 + "px");
        $('#insertColBtn')
            .css('top', parseInt(parseInt($('#tableContainer').height()) / 2 + 60) + "px");
    }

    $("#saveTable").click(function (e) {
        e.preventDefault();
        saveChanges(true);
    });

    function saveChanges(autosave, ajaxCallback) {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return;
        }

        //check save calculate date
        if (check_value_data === false) {
            setTimeout(function () {
                $('#saveError').fadeIn(200).delay(3000).fadeOut(1000);
            }, 1000);
            check_value_data = true;
            return;
        }

        if (typeof autosave == 'undefined' && !enable_autosave) {
            return;
        }

        /*check have the change Alternate*/
        if (_.size(checkChangeAlternate) > 0) {
            Wptm.style.cells = jQuery.extend({}, alternating.reAlternateColor());
        }

        var ht = Wptm.container.handsontable('getInstance');
        var mergeSetting = ht.mergeCells.mergedCellInfoCollection;

        var jsonVar = {
            jform: {
                datas: (dataReadOnly) ? '' : JSON.stringify(Wptm.container.handsontable('getData')),
                style: JSON.stringify(Wptm.style),
                css: Wptm.css,
                params: (dataReadOnly) ? '' : {"mergeSetting": JSON.stringify(mergeSetting), "hyperlink": JSON.stringify(Wptm.hyperlink)}
            },
            id: Wptm.id
        };
        //jsonVar[Wptm.token] = "1";

        Wptm.style = cleanStyle(Wptm.style, $(Wptm.container).handsontable('countRows'), $(Wptm.container).handsontable('countCols'));
        $.ajax({
            url: wptm_ajaxurl + "task=table.save",
            dataType: "json",
            type: "POST",
            data: jsonVar,
            success: function (datas) {
                if (datas.response === true) {
                    autosaveNotification = setTimeout(function () {
                        $('#savedInfo').fadeIn(200).delay(2000).fadeOut(1000);
                    }, 1000);
                } else {
                    bootbox.alert(datas.response, wptmText.Ok);
                }
                if (typeof ajaxCallback == 'function') {
                    ajaxCallback(Wptm.id)
                }
            },
            error: function (jqxhr, textStatus, error) {
                bootbox.alert(textStatus + " : " + error, wptmText.Ok);
            }
        });
    }

    /**
     * Click on new category btn
     */
    $('#newcategory').on('click', function (e) {
        e.preventDefault();
        if (!(Wptm.can.create)) {
            return;
        }
        if (!wptm_permissions.can_create_category) {
            bootbox.alert(wptm_permissions.translate.wptm_create_category, wptmText.Ok);
            return false;
        }
        e.preventDefault();
        $.ajax({
            url: wptm_ajaxurl + "task=category.addCategory",
            type: 'POST'
            // data: Wptm.token + '=1'
        }).done(function (data) {
            var result;
            try {
                result = jQuery.parseJSON(data);
            } catch (err) {
                bootbox.alert('<div>' + data + '</div>', wptmText.Ok);
            }
            if (result.response === true) {
                var link = '' +
                    '<li class="dd-item hasRole dd-collapsed dd3-item" data-id-category="' + result.datas.id_category + '">' +
                    '<div class="dd-handle dd3-handle"></div>' +
                    '<div class="dd-content hasRole dd3-content ui-droppable">' +
                    '<div class="content_list_options">' +
                    '<a class="trash"><i class="icon-trash"></i>Delete category</a>' +
                    '<a class="edit"><i class="icon-edit-category"></i>Edit title</a>' +
                    '</div>' +
                    '<a href="" class="t">' +
                    '<span class="title dd-handle">' + result.datas.title + '</span>' +
                    '</a>' +
                    '<a href="" class="list_options">' +
                    '<i class="dashicons dashicons-editor-ul"></i>' +
                    '</a>' +
                    '</div>' +
                    '<ul class="wptm-tables-list">' +
                    '<li><a class="newTable" href="#"><i class="icon-plus"></i> ' + wptmText.VIEW_WPTM_TABLE_ADD + '</a></li>' +
                    '</ul>' +
                    '</li>';
                $(link).appendTo('#categorieslist');

                //add category role own for user
                listRoleCategory[result.datas.id_category] = {0: idUser.toString()};

                initMenu();
                $categoriesList.find('li[data-id-category=' + result.datas.id_category + '] .dd-content >a .title').trigger('click');
                setTimeout(function () {
                    initTableNew();
                    $categoriesList.find('li.dd3-item.active>ul>li>a.newTable').trigger('click');
                }, 300);
                $('#insertcategory').show();
            } else {
                bootbox.alert(result.response, wptmText.Ok);
            }
        });
    });

    /*create new table*/
    (initTableNew = function () {
        $categoriesList.find('a.newTable').on('click', function (e) {
            e.preventDefault();
            if (!(Wptm.can.create)) {
                return;
            }

            if (!wptm_permissions.can_create_tables) {
                bootbox.alert(wptm_permissions.translate.wptm_create_tables, wptmText.Ok);
                return false;
            }

            var id_category = $(this).parents('.dd-item').data('id-category');
            var that = this;
            $.ajax({
                url: wptm_ajaxurl + "task=table.add&id_category=" + id_category,
                type: "POST",
                dataType: "json",
                success: function (datas) {
                    if (datas.response === true) {
                        $(that).parent().before('<li class="wptmtable" data-id-table="' + datas.datas.id + '"><a href="#"><i class="icon-database"></i> <span class="title">' + datas.datas.title + '</span></a><a class="edit"><i class="icon-edit"></i></a><a class="copy"><i class="icon-copy"></i></a><a class="trash"><i class="icon-trash"></i></a></li>');
                        initMenu();
                        $categoriesList.find('.wptm-tables-list li[data-id-table="' + datas.datas.id + '"] a:not(".newTable,.trash,.edit,.copy")').click();
                    } else {
                        bootbox.alert(datas.response, wptmText.Ok);
                    }
                },
                error: function (jqxhr, textStatus, error) {
                    bootbox.alert(textStatus + " : " + error, wptmText.Ok);
                }
            });
            return false;
        });
    })();

    /* Title edition */
    function initMenu() {
        /**
         *  click, unclick icon list_options(trash, edit)
         */
        $categoriesList.find('.dd-content .list_options').unbind('click').on('click', function () {
            $categoriesList.find('.content_list_options').removeClass('show');
            $(this).siblings('.content_list_options').addClass('show');
            return false;
        });

        $('body').contents().unbind('click').on('click', function () {
            if (!$(this).hasClass('list_options')) {
                $categoriesList.find('.content_list_options').removeClass('show');
            }
        });

        $wptm_create_new.find('#newtable').unbind('click').on('click', function(e) {
            e.preventDefault();
            $categoriesList.find('li.dd3-item.active>ul>li>a.newTable').trigger('click');
        });
        /**
         * Click on delete category btn
         */
        $categoriesList.find('.dd-content .trash').unbind('click').on('click', function () {
            if (!(Wptm.can.delete)) {
                return;
            }

            if (!wptm_permissions.can_delete_category) {
                bootbox.alert(wptm_permissions.translate.wptm_delete_category, wptmText.Ok);
                return false;
            }

            $(this).parent().removeClass('show');

            var id_category = $(this).closest('li').data('id-category');
            bootbox.confirm(wptmText.JS_WANT_DELETE + "\"" + $(this).parent().find('.title').text().trim() + '"?', wptmText.Cancel, wptmText.Ok, function (result) {
                if (result === true) {
                    $.ajax({
                        url: wptm_ajaxurl + "task=categories.delete&id_category=" + id_category,
                        type: 'POST',
                        // data: Wptm.token + '=1',
                        success: function (datas) {
                            var resultdata;
                            resultdata = jQuery.parseJSON(datas);
                            if (resultdata.response === true) {
                                var first;
                                $categoriesList.find('li[data-id-category=' + id_category + ']').remove();
                                first = $categoriesList.find('.dd-content>a .title').first();
                                if (first.length > 0) {
                                    first.click();
                                } else {
                                    $('#insertcategory').hide();
                                }
                            } else {
                                bootbox.alert(resultdata.response, wptmText.Ok);
                            }
                        },
                        error: function (jqxhr, textStatus, error) {
                            bootbox.alert(textStatus + " : " + error, wptmText.Ok);
                        }
                    });
                }
            });
            return false;
        });

        (initTableDelete = function () {
            $categoriesList.find('.wptm-tables-list a.trash').unbind('click').click(function (e) {
                if (!(Wptm.can.delete)) {
                    return false;
                }

                if (!wptm_permissions.can_delete_tables) {
                    bootbox.alert(wptm_permissions.translate.wptm_delete_tables, wptmText.Ok);
                    return false;
                }

                var that = this;
                bootbox.confirm(wptmText.JS_WANT_DELETE + "\"" + $(this).parent().find('.title').text().trim() + '"?', wptmText.Cancel, wptmText.Ok, function (result) {
                    if (result === true) {
                        var id = $(that).parent().data('id-table');
                        $.ajax({
                            url: wptm_ajaxurl + "task=table.delete&id=" + id,
                            type: "POST",
                            dataType: "json",
                            success: function (datas) {
                                if (datas.response === true) {
                                    $(that).parent().remove();
                                    if ($categoriesList.find('li.wptmtable.active').length > 0) {
                                        updatepreview($categoriesList.find('li.wptmtable.active').data('id-table'));
                                    } else {
                                        updatepreview();
                                    }
                                } else {
                                    bootbox.alert(datas.response, wptmText.Ok);
                                }
                            },
                            error: function (jqxhr, textStatus, error) {
                                bootbox.alert(textStatus, wptmText.Ok);
                            }
                        });
                        return false;
                    }
                });
            });
        })();

        (initTableCopy = function () {
            $categoriesList.find('.wptm-tables-list a.copy').unbind('click').click(function (e) {
                if (!(Wptm.can.create)) {
                    return false;
                }

                if (!wptm_permissions.can_create_tables) {
                    bootbox.alert(wptm_permissions.translate.wptm_create_tables, wptmText.Ok);
                    return false;
                }

                var that = this;
                var id = $(that).parent().data('id-table');
                $.ajax({
                    url: wptm_ajaxurl + "task=table.copy&id=" + id,
                    type: "POST",
                    dataType: "json",
                    success: function (datas) {
                        if (datas.response === true) {
                            $(that).parents('.wptm-tables-list').find('li').last().before('<li class="wptmtable" data-id-table="' + datas.datas.id + '"><a href="#"><i class="icon-database"></i> <span class="title">' + datas.datas.title + '</span></a><a class="edit"><i class="icon-edit"></i></a><a class="copy"><i class="icon-copy"></i></a><a class="trash"><i class="icon-trash"></i></a></li>');
                            initMenu();
                        } else {
                            bootbox.alert(datas.response, wptmText.Ok);
                        }
                    },
                    error: function (jqxhr, textStatus, error) {
                        bootbox.alert(textStatus, wptmText.Ok);
                    }
                });
                return false;
            });
        })();

        (initTablesLinks = function () {
            $categoriesList.find('.wptm-tables-list a:not(".newTable,.trash,.edit,.copy")').unbind('click').click(function (e) {

                if ($(this).parent().hasClass('sorting')) return false;
                var id = $(this).parent().data('id-table');
                $categoriesList.find('.wptm-tables-list li').removeClass('active');
                $(this).parent().addClass('active');
                updatepreview(id);

                return false;
            });
        })();

        /* Set the active category on menu click */
        (initCategoriesClick = function () {
            $categoriesList.find('.dd-content>a .title').unbind('click').click(function (e) {
                if ($(this).parents('li').first().hasClass('hasRole')) {
                    $categoriesList.find('li').removeClass('active');
                    $(this).parent().parent().parent().addClass('active');
                    updatepreview();
                }

                return false;
            });
        })();

        $categoriesList.find('a.edit').unbind().click(function (e) {
            e.stopPropagation();
            if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
                return false;
            }

            var link = ($(this).parent().hasClass('content_list_options')) ? $(this).parent().siblings('a.t').find('span.title') : $(this).parent().find('a span.title');
            $(link).parent().siblings('.content_list_options').removeClass('show');

            if ($(link).parents('.wptm-tables-list').length === 0 && typeof listRoleCategory[$(link).parents('li').first().data('id-category')] === 'undefined') {
                return false;
            }

            var oldTitle = link.text();
            $(link).attr('contentEditable', true);
            $(link).addClass('editable');
            $(link).selectText();

            $categoriesList.find('a span.editable').bind('click.mm', hstop);  //let's click on the editable object
            $(link).bind('keypress.mm', hpress); //let's press enter to validate new title'
            $('*').not($(link)).bind('click.mm', houtside);

            function unbindall() {
                $categoriesList.find('a span').unbind('click.mm', hstop);  //let's click on the editable object
                $(link).unbind('keypress.mm', hpress); //let's press enter to validate new title'
                $('*').not($(link)).unbind('click.mm', houtside);
            }

            //Validation
            function hstop(event) {
                event.stopPropagation();
                return false;
            }

            //Press enter
            function hpress(e) {
                if (e.which == 13) {
                    e.preventDefault();
                    unbindall();
                    updateTitle($(link).text());
                    $(link).removeAttr('contentEditable');
                    $(link).removeClass('editable');
                }
            }

            //click outside
            function houtside(e) {
                unbindall();
                updateTitle($(link).text());
                $(link).removeAttr('contentEditable');
                $(link).removeClass('editable');
            }


            function updateTitle(title) {
                var id, url, type;
                if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
                    return false;
                }
                if ($(link).parents('.wptm-tables-list').length === 0) {
                    id = $(link).parents('li').data('id-category');
                    url = wptm_ajaxurl + "task=category.setTitle&id_category=" + id + '&title=' + title;
                    type = 'category';
                } else {
                    id = $(link).parents('li').data('id-table');
                    url = wptm_ajaxurl + "task=table.setTitle&id=" + id + '&title=' + title;
                    type = 'table';
                }

                if (title !== '') {
                    $.ajax({
                        url: url,
                        type: "POST",
                        dataType: "json",
                        success: function (datas) {
                            if (datas.response === true) {
                                if (type === 'table' && Wptm.id == id) {
                                    $('#tableTitle').html(title);
                                }
                            } else {
                                $(link).text(oldTitle);
                                bootbox.alert(datas.response, wptmText.Ok);
                            }
                        },
                        error: function (jqxhr, textStatus, error) {
                            $(link).text(oldTitle);
                            bootbox.alert(textStatus, wptmText.Ok);
                        }
                    });
                } else {
                    $(link).text(oldTitle);
                    return false;
                }
                $(link).parent().css('white-space', 'normal');
                setTimeout(function () {
                    $(link).parent().css('white-space', '');
                }, 200);

            }
        });
    }


    (initStyles = function () {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return false;
        }
        $('#rightcol .table-styles a').click(function () {
            var id = $(this).data('id');
            var cellsData = $(Wptm.container).handsontable('getData');
            var ret = true;
            var nbCols = 0;
            var nbRows = cellsData.length;
            $.each(cellsData, function (index, value) {
                nbCols = value.length;
                $.each(value, function (i, v) {
                    if (v && v.toString().trim() !== '') {
                        ret = false;
                        return false;
                    }
                });
            });

            var $defaultParams = {
                'date_formats': default_value.date_formats,
                'symbol_position': parseInt(default_value.symbol_position),
                'currency_symbol': default_value.currency_symbol,
                'decimal_symbol': default_value.decimal_symbol,
                'decimal_count': parseInt(default_value.decimal_count),
                'thousand_symbol': default_value.thousand_symbol
            };

            if (ret == false) {

                bootbox.confirm(wptmText.WARNING_CHANGE_THEME, wptmText.Cancel, wptmText.Ok, function (result) {
                    if (result) {
                        $.ajax({
                            url: wptm_ajaxurl + "view=style&format=json&id=" + id,
                            type: 'POST',
                            dataType: 'json',
                        }).done(function (data) {
                            var datas;
                            var style;
                            if (typeof (data) === 'object') {
                                cellsData = $(Wptm.container).handsontable('getData');
                                var ret = true;
                                nbCols = 0;
                                nbRows = cellsData.length;

                                if (ret === true) {
                                    datas = JSON.parse(data.data);
                                    $(Wptm.container).handsontable('loadData', datas);
                                }
                                //backup old style
                                var oldStyle = JSON.parse(JSON.stringify(Wptm.style));

                                //Apply cols and row style to cells
                                style = $.parseJSON(data.style);
                                $('#jform_css').val(data.css.replace(/\\n/g, "\n"));
                                $('#jform_css').change();
                                Wptm.style = {table: style.table, rows: {}, cols: {}, cells: style.cells};

                                $.each(datas, function (row, rValue) {
                                    $.each(rValue, function (col, cValue) {
                                        if (typeof (Wptm.style.cells[row + '!' + col]) === 'undefined') {
                                            Wptm.style.cells[row + '!' + col] = [row, col, {}];
                                        }
                                        if (typeof (style.cols[col]) !== 'undefined' && Object.keys(style.cols[col][1]).length !== 0) {
                                            for (var attr in style.cols[col][1]) {
                                                if (typeof (Wptm.style.cells[row + '!' + col][2][attr]) === 'undefined') {
                                                    Wptm.style.cells[row + '!' + col][2][attr] = style.cols[col][1][attr];
                                                }
                                            }
                                        }
                                        if (typeof (style.rows[row]) !== 'undefined' && Object.keys(style.rows[row][1]).length !== 0) {
                                            for (var attr in style.rows[row][1]) {
                                                if (typeof (Wptm.style.cells[row + '!' + col][2][attr]) === 'undefined') {
                                                    Wptm.style.cells[row + '!' + col][2][attr] = style.rows[row][1][attr];
                                                }
                                            }

                                        }
                                    });
                                });

                                //re-apply responsive parameters
                                if (typeof oldStyle.table.responsive_type !== "undefined") {
                                    Wptm.style.table.responsive_type = oldStyle.table.responsive_type;
                                }

                                // add default val to wptm.style.table
                                Wptm.style.table = $.extend({}, $defaultParams, Wptm.style.table);

                                if (typeof (Wptm.style.table.alternateColorValue) === 'undefined' || typeof Wptm.style.table.alternateColorValue[0] === 'undefined') {
                                    alternating.setAlternateColor(style.rows);
                                }

                                oldAlternate = {};
                                if (_.size(oldAlternate) < 1) {
                                    oldAlternate = $.extend({}, Wptm.style.table.alternateColorValue);
                                }
                                checkChangeAlternate = [];
                                var colIndex, col, row;
                                for (col in style.cols) {
                                    colIndex = style.cols[col][0];
                                    if (typeof oldStyle.cols[colIndex] !== "undefined" && typeof oldStyle.cols[colIndex][1]["res_priority"] !== "undefined") {
                                        if (typeof Wptm.style.cols[colIndex] == "undefined") {
                                            Wptm.style.cols[colIndex] = [colIndex, {}];
                                        }
                                        Wptm.style.cols[colIndex][1]["res_priority"] = oldStyle.cols[colIndex][1]["res_priority"];
                                    }
                                }

                                //If no content we can set our own cols and rows size
                                if (ret === true) {
                                    for (row in style.rows) {
                                        if (typeof (style.rows[row]) !== 'undefined' && (typeof (style.rows[row][1].height) !== 'undefined')) {
                                            if (typeof (Wptm.style.rows[style.rows[row][0]]) === 'undefined') {
                                                Wptm.style.rows[style.rows[row][0]] = [row, {}];
                                            }
                                            Wptm.style.rows[style.rows[row][0]][1].height = style.rows[row][1].height;
                                        }
                                    }
                                    for (col in style.cols) {
                                        if (typeof (style.cols[col]) !== 'undefined' && (typeof (style.cols[col][1].width) !== 'undefined')) {
                                            if (typeof (Wptm.style.cols[style.cols[col][0]]) === 'undefined') {
                                                Wptm.style.cols[style.cols[col][0]] = [col, {}];
                                            }
                                            Wptm.style.cols[style.cols[col][0]][1].width = style.cols[col][1].width;
                                        }
                                    }
                                    pullDims();
                                }

                                $(Wptm.container).handsontable('render');
                                loadSelection();
                                parseCss();
                                saveChanges();
                                default_sortable(datas);
                            } else {
                                bootbox.alert(data, wptmText.Ok);
                            }
                            $(Wptm.container).handsontable('render');
                        });
                    }
                });
            } else {

                $.ajax({
                    url: wptm_ajaxurl + "view=style&format=json&id=" + id,
                    type: 'POST',
                    dataType: 'json',
                }).done(function (data) {
                    var datas;
                    var style;
                    if (typeof (data) === 'object') {
                        cellsData = $(Wptm.container).handsontable('getData');
                        var ret = true;
                        nbCols = 0;
                        nbRows = cellsData.length;

                        if (ret === true) {
                            datas = JSON.parse(data.data);
                            $(Wptm.container).handsontable('loadData', datas);
                        }
                        //backup old style
                        var oldStyle = JSON.parse(JSON.stringify(Wptm.style));

                        //Apply cols and row style to cells
                        style = $.parseJSON(data.style);
                        $('#jform_css').val(data.css.replace(/\\n/g, "\n"));
                        $('#jform_css').change();
                        Wptm.style = {table: style.table, rows: {}, cols: {}, cells: style.cells};
                        $.each(datas, function (row, rValue) {
                            $.each(rValue, function (col, cValue) {
                                if (typeof (Wptm.style.cells[row + '!' + col]) === 'undefined') {
                                    Wptm.style.cells[row + '!' + col] = [row, col, {}];
                                }
                                if (typeof (style.cols[col]) !== 'undefined' && Object.keys(style.cols[col][1]).length !== 0) {
                                    for (var attr in style.cols[col][1]) {
                                        if (typeof (Wptm.style.cells[row + '!' + col][2][attr]) === 'undefined') {
                                            Wptm.style.cells[row + '!' + col][2][attr] = style.cols[col][1][attr];
                                        }
                                    }
                                }
                                if (typeof (style.rows[row]) !== 'undefined' && Object.keys(style.rows[row][1]).length !== 0) {
                                    for (var attr in style.rows[row][1]) {
                                        if (typeof (Wptm.style.cells[row + '!' + col][2][attr]) === 'undefined') {
                                            Wptm.style.cells[row + '!' + col][2][attr] = style.rows[row][1][attr];
                                        }
                                    }

                                }
                            });
                        });

                        //re-apply responsive parameters
                        if (typeof oldStyle.table.responsive_type !== "undefined") {
                            Wptm.style.table.responsive_type = oldStyle.table.responsive_type;
                        }

                        // add default val to wptm.style.table
                        Wptm.style.table = $.extend({}, $defaultParams, Wptm.style.table);

                        alternating.setAlternateColor(style.rows);

                        oldAlternate = {};
                        if (_.size(oldAlternate) < 1) {
                            oldAlternate = $.extend({}, Wptm.style.table.alternateColorValue);
                        }
                        checkChangeAlternate = [];
                        var colIndex, row, col;
                        for (col in style.cols) {
                            colIndex = style.cols[col][0];
                            if (typeof oldStyle.cols[colIndex] !== "undefined" && typeof oldStyle.cols[colIndex][1]["res_priority"] !== "undefined") {
                                if (typeof Wptm.style.cols[colIndex] == "undefined") {
                                    Wptm.style.cols[colIndex] = [colIndex, {}];
                                }
                                Wptm.style.cols[colIndex][1]["res_priority"] = oldStyle.cols[colIndex][1]["res_priority"];
                            }
                        }

                        //If no content we can set our own cols and rows size
                        if (ret === true) {
                            for (row in style.rows) {
                                if (typeof (style.rows[row]) !== 'undefined' && (typeof (style.rows[row][1].height) !== 'undefined')) {
                                    if (typeof (Wptm.style.rows[style.rows[row][0]]) === 'undefined') {
                                        Wptm.style.rows[style.rows[row][0]] = [row, {}];
                                    }
                                    Wptm.style.rows[style.rows[row][0]][1].height = style.rows[row][1].height;
                                }
                            }
                            for (col in style.cols) {
                                if (typeof (style.cols[col]) !== 'undefined' && (typeof (style.cols[col][1].width) !== 'undefined')) {
                                    if (typeof (Wptm.style.cols[style.cols[col][0]]) === 'undefined') {
                                        Wptm.style.cols[style.cols[col][0]] = [col, {}];
                                    }
                                    Wptm.style.cols[style.cols[col][0]][1].width = style.cols[col][1].width;
                                }
                            }
                            pullDims();
                        }
                        $(Wptm.container).handsontable('render');
                        loadSelection();
                        parseCss();
                        saveChanges();
                        default_sortable(datas);
                    } else {
                        bootbox.alert(data, wptmText.Ok);
                    }
                    $(Wptm.container).handsontable('render');
                });
            }


            return false;
        });
    })();

    /* set count by compare selection vs exist oldAlternate[count] */
    (compareAlternateColor = function (selection) {
        var count = _.size(oldAlternate);

        var WptmAlternateColor = $.extend({}, oldAlternate);
        if (typeof selection !== 'undefined' && typeof (Wptm.style.cells[selection[0] + "!" + selection[1]]) !== 'undefined') {
            if (typeof Wptm.style.cells[selection[0] + "!" + selection[1]][2].AlternateColor !== 'undefined') {
                var countOld = Wptm.style.cells[selection[0] + "!" + selection[1]][2].AlternateColor;
                for (var i = 0; i < _.size(selection); i ++) {
                    if (typeof WptmAlternateColor[countOld] !== 'undefined'
                        && typeof WptmAlternateColor[countOld].selection !== 'undefined'
                        && WptmAlternateColor[countOld].selection[i] !== selection[i]
                    ) {
                        countOld = count;
                        break;
                    }
                }
                count = countOld;
            }
        }
        return count;
    });

    //alternate color handling functions
    (alternating = {
        setNumberAlternate : function (selection) {
            var count = _.size(oldAlternate);

            $.each(oldAlternate, function (i, v) {
                if (typeof v.selection !== 'undefined') {
                    if (v.selection[0] == selection[0] &&
                        v.selection[1] == selection[1] &&
                        v.selection[2] == selection[2] &&
                        v.selection[3] == selection[3]) {
                        count = i;
                    }
                } else {
                    return count;
                }
            });

            return count;
        },
        selectAlternatingColor : function (value, selection, count) {//when select alternating_color
            //ij is row, ik is coll
            var beforeChange = jQuery.extend({}, Wptm.style.cells);
            var size = _.size(checkChangeAlternate);
            checkChangeAlternate[size] = {};
            for (ij = selection[0]; ij <= selection[2]; ij++) {
                for (ik = selection[1]; ik <= selection[3]; ik++) {
                    if (typeof (beforeChange[ij + "!" + ik]) !== 'undefined') {
                        checkChangeAlternate[size][ij + "!" + ik] = beforeChange[ij + "!" + ik][2].AlternateColor;
                        beforeChange[ij + "!" + ik][2] = jQuery.extend(beforeChange[ij + "!" + ik][2], {AlternateColor: count});
                    } else {
                        beforeChange[ij + "!" + ik] = [ij, ik, {}];
                        beforeChange[ij + "!" + ik][2] = {AlternateColor: count};
                    }
                    if (typeof checkChangeAlternate[size][ij + "!" + ik] === 'undefined' || checkChangeAlternate[size][ij + "!" + ik] === null) {
                        checkChangeAlternate[size][ij + "!" + ik] = -1;
                    }
                }
            }
            Wptm.style.cells = beforeChange;

            var listChangeAlternate = [];
            var i = count, oldCount = count;
            changeAlternate = [];
            for (var ii = count - 1; ii >= 0; ii--) {
                var check = 1;
                if(value[ii].selection[0] >= value[i].selection[0]) {
                    check++;
                }

                if(value[ii].selection[1] >= value[i].selection[1]) {
                    check++;
                }

                if(value[ii].selection[2] <= value[i].selection[2]) {
                    check++;
                }

                if(value[ii].selection[3] <= value[i].selection[3]) {
                    check++;
                }

                if (check === 5) {
                    value[ii] = value[count];
                    listChangeAlternate[ii] = count;
                    if (typeof listChangeAlternate[count] !== 'undefined') {
                        listChangeAlternate[ii] = listChangeAlternate[count];
                        delete listChangeAlternate[count];
                    }
                    delete value[count];
                    count--;
                    i = ii;
                }
            }
            for (var j = 0; j < oldCount; j ++) {
                if (typeof listChangeAlternate[j] !== 'undefined') {
                    changeAlternate[listChangeAlternate[j]] = j;
                }
            }
        },
        reAlternateColor : function () {
            var styleCells = {};
            styleCells = jQuery.extend({}, Wptm.style.cells);

            for (var i = _.size(checkChangeAlternate) - 1; i >= 0; i--) {
                $.map(checkChangeAlternate[i], function (v, ii) {
                    if (typeof styleCells[ii] !== 'undefined') {
                        if (v !== -1) {
                            styleCells[ii][2].AlternateColor = v;
                        } else {
                            delete styleCells[ii][2].AlternateColor;
                        }
                    }
                });
            }

            checkChangeAlternate = [];
            return styleCells;
        },
        setAlternateColor : function (styleRows) { //set automatic style color by alternate_row_odd_color, var styleRows = style.rows
            Wptm.style.table.alternateColorValue = {};
            var countCols = Wptm.container.handsontable('countCols');
            var countRows = Wptm.container.handsontable('countRows');

            /*get count cols, rows when handsontable not activated*/
            if (typeof countRows === 'undefined') {
                var keys = Object.keys(Wptm.style.cells);
                countCols = 0;
                countRows = 0;
                keys.map(function (key) {
                    var rowCol = key.split('!');
                    if (countRows < parseInt(rowCol[0])) {
                        countRows = parseInt(rowCol[0]);
                    }

                    if (countCols < parseInt(rowCol[1])) {
                        countCols = parseInt(rowCol[1]);
                    }
                });
            }
            var checkExistAlternateOld = 0;
            var checkExistAlternateEven = 0;

            if (checkObjPropertyNested(Wptm.style.table, 'alternate_row_odd_color') && Wptm.style.table.alternate_row_odd_color) {
                checkExistAlternateOld = 1;
                // Wptm.style.table.alternate_row_odd_color;
            }
            if (checkObjPropertyNested(Wptm.style.table, 'alternate_row_even_color') && Wptm.style.table.alternate_row_even_color) {
                checkExistAlternateEven = 1;
                // Wptm.style.table.alternate_row_even_color;
            }

            var header = '';
            if (styleRows !== null && typeof styleRows[0][1].cell_background_color !== 'undefined') {
                header = styleRows[0][1].cell_background_color;
            }

            if (checkExistAlternateEven + checkExistAlternateOld > 0) {
                Wptm.style.table.alternateColorValue[0] = {};
                Wptm.style.table.alternateColorValue[0].selection = [0, 0, countRows, countCols];
                Wptm.style.table.alternateColorValue[0].footer = '';
                Wptm.style.table.alternateColorValue[0].even = checkExistAlternateEven === 1 ? Wptm.style.table.alternate_row_even_color : '#ffffff';
                Wptm.style.table.alternateColorValue[0].header = header;
                Wptm.style.table.alternateColorValue[0].old = checkExistAlternateOld === 1 ? Wptm.style.table.alternate_row_odd_color : '#ffffff';
                Wptm.style.table.alternateColorValue[0].default = '' + header + '|' + Wptm.style.table.alternateColorValue[0].even + '|' + Wptm.style.table.alternateColorValue[0].old + '|' + '';
            }

            if (typeof Wptm.style.table.alternateColorValue[0] !== 'undefined') {
                for (ij = 0; ij <= countRows; ij++) {
                    for (ik = 0; ik <= countCols; ik++) {
                        if (typeof (Wptm.style.cells[ij + "!" + ik]) !== 'undefined') {
                            Wptm.style.cells[ij + "!" + ik][2].AlternateColor = 0;
                        } else {
                            Wptm.style.cells[ij + "!" + ik] = [ij, ik, {}];
                            Wptm.style.cells[ij + "!" + ik][2] = jQuery.extend({}, {AlternateColor: 0});
                        }
                        if (header !== '' && ij === 0) {
                            delete Wptm.style.cells[ij + "!" + ik][2].cell_background_color;
                        }
                    }
                }
            }
        },
        applyAlternate : function () {
            Wptm.style.table.alternateColorValue = $.extend({}, oldAlternate);
            var cellPosition = $alternating_color.find('#cellRangeLabelAlternate').val();
            var string = '';
            if (typeof cellPosition === 'undefined') {
                string = 'A1:A1';
            } else {
                string = cellPosition[0] + cellPosition[1] + ':' + cellPosition[0] + cellPosition[1];
            }

            //remove the selector cells
            $alternating_color.find('#cellRangeLabelAlternate').val(string);
            alternating.affterRangeLabe();
            checkChangeAlternate = [];
            saveChanges();
            $('#alternating_color').find('.alternating_color_top .cancel').trigger('click');
        },
        affterRangeLabe : function () { //set the change options of altenate when selector cell/change rangeLabe
            var rangeLabel = $alternating_color.find('#cellRangeLabelAlternate').val();
            rangeLabel = rangeLabel.replace(/[ ]+/g, "").toUpperCase();
            var arrayRange = rangeLabel.split(":");
            if (arrayRange.length > 1) {
                var selection = [];
                selection.push(parseInt(arrayRange[0].split(/[ |A-Za-z]+/g)[1]) - 1);
                selection.push(arrayRange[0].charCodeAt(0) - 65);
                selection.push(parseInt(arrayRange[1].split(/[ |A-Za-z]+/g)[1]) - 1);
                selection.push(arrayRange[1].charCodeAt(0) - 65);
                $(Wptm.container).handsontable("selectCell", selection[0], selection[1], selection[2], selection[3]);
            }
        },
        getActiveFormatColor: function (format) {
            $alternating_color.find('.formatting_style .pane-color-tile').each(function () {
                if ($(this).find('.pane-color-tile-1').data('value') === format.even) {
                    if ($(this).find('.pane-color-tile-2').data('value') === format.old) {
                        var check = 0;
                        if (format.header !== '') {
                            check = format.header === $(this).find('.pane-color-tile-header').data('value') ? 1 : -1;
                        } else {
                            check = 1;
                        }

                        if (format.footer !== '') {
                            check = format.footer === $(this).find('.pane-color-tile-footer').data('value') ? check : -1;
                        }

                        switch (check) {
                            case 1:
                                $(this).addClass('active');
                                break;
                            case -1:
                                // No active
                                break;
                        }
                    }
                }
            });
        },
        renderCell : function () { //render cells
            $(Wptm.container).handsontable('render');
        }
    });

    (initObserver = function () {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return false;
        }

        /*change range label alternate*/
        $alternating_color.find('#cellRangeLabelAlternate').on('change', function () {
            alternating.affterRangeLabe();
        });

        $alternating_color.find('.formatting_style .pane-color-tile').on('click', function () {
            var selection = $(Wptm.container).handsontable('getSelected');

            if (!selection) {
                return;
            }

            if (selection[0] > selection[2]) {
                selection = [selection[2], selection[3], selection[0], selection[1]];
            }

            var count = alternating.setNumberAlternate(selection);

            /*create/reset oldAlternate[count]*/
            oldAlternate[count] = {};
            oldAlternate[count].selection = selection;
            oldAlternate[count].even = $(this).find('.pane-color-tile-1').data('value');
            oldAlternate[count].old = $(this).find('.pane-color-tile-2').data('value');

            if ($alternating_color.find('.banding-header-checkbox:checked').length > 0) {
                oldAlternate[count].header = $(this).find('.pane-color-tile-header').data('value');
            } else {
                oldAlternate[count].header = '';
            }

            if ($alternating_color.find('.banding-footer-checkbox:checked').length > 0) {
                oldAlternate[count].footer = $(this).find('.pane-color-tile-footer').data('value');
            } else {
                oldAlternate[count].footer = '';
            }

            oldAlternate[count].default = '' + $(this).find('.pane-color-tile-header').data('value') + '|' + oldAlternate[count].even + '|' + oldAlternate[count].old + '|' + $(this).find('.pane-color-tile-footer').data('value');

            alternating.selectAlternatingColor(oldAlternate, selection, count),
                alternating.renderCell();

            $alternating_color.find('.pane-color-tile.active').removeClass('active');
            $(this).addClass('active');
        });

        $alternating_color.find('.banding-header-footer-checkbox-wrapper input').on('click', function () {
            var selection = $(Wptm.container).handsontable('getSelected');

            if (!selection) {
                return false;
            }

            if (selection[0] > selection[2]) {
                selection = [selection[2], selection[3], selection[0], selection[1]];
            }
            var oldCount = _.size(oldAlternate);
            var count = alternating.setNumberAlternate(selection);
            if (oldCount !== count) {
                var defaultStyle = [];
                if (typeof oldAlternate[count].default !== 'undefined') {
                    defaultStyle = oldAlternate[count].default.split("|");
                } else {
                    if ($alternating_color.find('.pane-color-tile.active').length > 0) {
                        defaultStyle[0] = $alternating_color.find('.pane-color-tile.active').find('.pane-color-tile-header').data('value');
                        defaultStyle[3] = $alternating_color.find('.pane-color-tile.active').find('.pane-color-tile-footer').data('value');
                    } else {
                        defaultStyle[0] = oldAlternate[count].header;
                        defaultStyle[3] = oldAlternate[count].footer;
                    }
                }

                if ($alternating_color.find('.banding-header-checkbox:checked').length > 0) {
                    oldAlternate[count].header = defaultStyle[0];
                } else {
                    oldAlternate[count].header = '';
                }

                if ($alternating_color.find('.banding-footer-checkbox:checked').length > 0) {
                    oldAlternate[count].footer = defaultStyle[3];
                } else {
                    oldAlternate[count].footer = '';
                }

                oldAlternate[count].default = '' + defaultStyle[0] + '|' + oldAlternate[count].even + '|' + oldAlternate[count].old + '|' + defaultStyle[3];

                alternating.selectAlternatingColor(oldAlternate, selection, count),
                    alternating.renderCell();
            }
        });

        /*active selected alternating color*/
        $alternating_color.find('#alternate_color_done').click(function (e) {
            e.preventDefault();
            alternating.applyAlternate();
        });

        $('#alternate_color_cancel').click(function (e) {
            e.preventDefault();
            if (_.size(checkChangeAlternate) > 0) {
                Wptm.style.cells = jQuery.extend({}, alternating.reAlternateColor());
                oldAlternate = $.extend({}, Wptm.style.table.alternateColorValue);
                alternating.renderCell();
            } else {
                $('#alternating_color').find('.alternating_color_top .cancel').trigger('click');
            }
        });

        $('.observeChangesCol').on('change click', function (e) {

            switch ($(this).attr('name')) {
                case 'jform[jform_responsive_col]':
                    //populate jform_responsive_priority
                    var col = $(this).val();
                    if (typeof (Wptm.style.cols) !== 'undefined' && typeof (Wptm.style.cols[col]) !== 'undefined' && typeof (Wptm.style.cols[col][1]) !== 'undefined' && typeof (Wptm.style.cols[col][1].res_priority) !== 'undefined') {
                        var res_priority = Wptm.style.cols[col][1].res_priority;
                        $('#jform_responsive_priority').val(res_priority);
                        $('#jform_responsive_priority').trigger('liszt:updated');
                    } else {
                        $('#jform_responsive_priority').val(0);
                        $('#jform_responsive_priority').trigger('liszt:updated');
                    }
                    break;
            }
        });

        $('.observeChanges').on('change click', function (e) {
            if (isSelectionProcess === true) {
                return;
            }
            var selection = $(Wptm.container).handsontable('getSelected');
            if (!selection) {
                return;
            }
            if (selection[0] > selection[2]) {
                selection = [selection[2], selection[3], selection[0], selection[1]];
            }

            //for undo
            var oldStyle = JSON.parse(JSON.stringify(Wptm.style));
            //for mergecells
            var ht = Wptm.container.handsontable('getInstance');
            var ij, ik;
            var val;
            var $jform_cell_border_width = $('#jform_cell_border_width');
            var $jform_cell_border_type = $('#jform_cell_border_type');
            var $jform_cell_border_color = $('#jform_cell_border_color');

            switch ($(this).attr('name')) {
                case 'jform[jform_use_sortable]':
                    Wptm.style.table.use_sortable = $(this).val();
                    break;
                case 'jform[jform_default_sortable]':
                    Wptm.style.table.default_sortable = $(this).val();
                    break;
                case 'jform[jform_default_order_sortable]':
                    Wptm.style.table.default_order_sortable = $(this).val();
                    break;
                case 'jform[jform_enable_filters]':
                    Wptm.style.table.enable_filters = $(this).val();
                    break;
                case 'jform[enable_pagination]':
                    Wptm.style.table.enable_pagination = $(this).val();
                    break;
                case 'jform[limit_rows]':
                    Wptm.style.table.limit_rows = $(this).val();
                    break;
                case 'jform[jform_table_align]':
                    Wptm.style.table.table_align = $(this).val();
                    break;
                case 'jform[jform_responsive_type]':
                    Wptm.style.table.responsive_type = $(this).val();

                    if (Wptm.style.table.responsive_type == 'scroll') {
                        $('#jform_responsive_col').parents('.control-group:first').hide();
                        $('#jform_responsive_priority').parents('.control-group:first').hide();
                        $("#freeze_options").show();
                    } else {
                        $('#jform_responsive_col').parents('.control-group:first').show();
                        $('#jform_responsive_priority').parents('.control-group:first').show();
                        $("#freeze_options").hide();
                    }
                    break;
                case 'freeze_row':
                    Wptm.style.table.freeze_row = $(this).val();
                    if (Wptm.style.table.freeze_row == "0") {
                        Wptm.style.table.table_height = "";
                        $("#jform_table_height").val("");
                        $("#table_height_container").hide();
                    } else {
                        $("#table_height_container").show();
                    }
                    break;
                case 'freeze_col':
                    Wptm.style.table.freeze_col = $(this).val();
                    break;
                case 'jform[table_height]':
                    Wptm.style.table.table_height = $(this).val();
                    break;
                case 'jform[jform_spreadsheet_url]':
                    Wptm.style.table.spreadsheet_url = $(this).val();
                    break;
                case 'auto_sync':
                    Wptm.style.table.auto_sync = $(this).val();
                    $(".wptm_warning").remove();
                    if (typeof Wptm.style.table.spreadsheet_url != 'undefined' && Wptm.style.table.spreadsheet_url != "" && typeof Wptm.style.table.auto_sync != 'undefined' && Wptm.style.table.auto_sync != "0") {
                        $('h3#titleError').after('<div class="wptm_warning"><p><span class="dashicons dashicons-warning"></span>' + wptmText.notice_msg_table_syncable + '</p></div>');
                    }

                    break;
                case 'spreadsheet_style':
                    Wptm.style.table.spreadsheet_style = $(this).val();
                    break;
                case 'download_button':
                    Wptm.style.table.download_button = $(this).val();
                    break;
                case 'jform[jform_responsive_priority]':
                    var col = $('#jform_responsive_col').val();
                    Wptm.style.cols = fillArray(Wptm.style.cols, {res_priority: $('#jform_responsive_priority').val()}, col);

                case 'jform[jform_cell_type]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            if ($(this).val() === '') {
                                Wptm.style.cells = fillArray(Wptm.style.cells, {cell_type: null}, ij, ik);
                            } else {
                                Wptm.style.cells = fillArray(Wptm.style.cells, {cell_type: $(this).val()}, ij, ik);
                            }
                        }
                    }
                    break;
                case 'jform[jform_cell_background_color]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            if ($(this).val() === '') {
                                Wptm.style.cells = fillArray(Wptm.style.cells, {cell_background_color: null}, ij, ik);
                            } else {
                                Wptm.style.cells = fillArray(Wptm.style.cells, {cell_background_color: $(this).val()}, ij, ik);
                            }
                        }
                    }
                    break;
                case 'jform[jform_cell_border_top]':
                    for (ik = selection[1]; ik <= selection[3]; ik++) {
                        if ($jform_cell_border_width.val()) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_top: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[0], ik);
                        } else {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_top: null});
                        }
                    }
                    break;
                case 'jform[jform_cell_border_right]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        if ($jform_cell_border_width.val()) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_right: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, ij, selection[3]);
                        } else {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_right: null});
                        }
                    }

                    //check if selection[0], selection[1] is merged cell then fill cell_border_right
                    var info = ht.mergeCells.mergedCellInfoCollection.getInfo(selection[0], selection[1]);
                    if (info) {
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_right: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[0], selection[1]);
                    }

                    break;
                case 'jform[jform_cell_border_bottom]':
                    for (ik = selection[1]; ik <= selection[3]; ik++) {
                        if ($jform_cell_border_width.val()) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[2], ik);
                        } else {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: null});
                        }
                    }

                    //check if selection[0], selection[1] is merged cell then fill cell_border_bottom
                    var info = ht.mergeCells.mergedCellInfoCollection.getInfo(selection[0], selection[1]);
                    if (info) {
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[0], selection[1]);
                    }
                    break;
                case 'jform[jform_cell_border_left]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        if ($jform_cell_border_width.val()) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_left: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, ij, selection[1]);
                        } else {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_left: null});
                        }
                    }
                    break;
                case 'jform[jform_cell_border_all]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            if ($jform_cell_border_width.val()) {
                                val = $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val();
                            } else {
                                val = null;
                            }
                            Wptm.style.cells = fillArray(Wptm.style.cells, {
                                cell_border_left: val,
                                cell_border_top: val,
                                cell_border_right: val,
                                cell_border_bottom: val
                            }, ij, ik);
                        }
                    }
                    //check if selection[0], selection[1] is merged cell then fill cell_border_bottom, cell_border_right
                    var info = ht.mergeCells.mergedCellInfoCollection.getInfo(selection[0], selection[1]);
                    if (info) {
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_right: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[0], selection[1]);
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[0], selection[1]);
                    }
                    break;
                case 'jform[jform_cell_border_inside]':
                    if ($jform_cell_border_width.val()) {
                        val = $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val();
                    } else {
                        val = null;
                    }
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1] + 1; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_left: val}, ij, ik);
                        }
                    }
                    for (ij = selection[0]; ij < selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: val}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_border_outline]':
                    if ($jform_cell_border_width.val()) {
                        val = $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val();
                    } else {
                        val = null;
                    }

                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_left: val}, ij, selection[1]);
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_right: val}, ij, selection[3]);

                    }
                    for (ik = selection[1]; ik <= selection[3]; ik++) {
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_top: val}, selection[0], ik);
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: val}, selection[2], ik);
                    }
                    //check if selection[0], selection[1] is merged cell then fill cell_border_bottom, cell_border_right
                    var info = ht.mergeCells.mergedCellInfoCollection.getInfo(selection[0], selection[1]);
                    if (info) {
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_right: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[0], selection[1]);
                        Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val()}, selection[0], selection[1]);
                    }
                    break;
                case 'jform[jform_cell_border_vertical]':
                    if ($jform_cell_border_width.val()) {
                        val = $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val();
                    } else {
                        val = null;
                    }
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1] + 1; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_left: val}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_border_horizontal]':
                    if ($jform_cell_border_width.val()) {
                        val = $jform_cell_border_width.val() + "px " + $jform_cell_border_type.val() + " " + $jform_cell_border_color.val();
                    } else {
                        val = null;
                    }
                    for (ij = selection[0]; ij < selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_border_bottom: val}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_border_remove]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {
                                cell_border_left: null,
                                cell_border_top: null,
                                cell_border_right: null,
                                cell_border_bottom: null
                            }, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_font_size]':
                case 'jform[jform_cell_font_family]':
                case 'jform[jform_cell_font_color]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {
                                cell_font_family: $('#jform_cell_font_family').val(),
                                cell_font_size: $('#jform_cell_font_size').val(),
                                cell_font_color: $('#jform_cell_font_color').val()
                            }, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_font_bold]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = toggleArray(Wptm.style.cells, {cell_font_bold: true}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_font_italic]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = toggleArray(Wptm.style.cells, {cell_font_italic: true}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_font_underline]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = toggleArray(Wptm.style.cells, {cell_font_underline: true}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_align_left]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_text_align: 'left'}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_align_right]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_text_align: 'right'}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_align_center]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_text_align: 'center'}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_align_justify]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_text_align: 'justify'}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_vertical_align_middle]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_vertical_align: 'middle'}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_vertical_align_bottom]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_vertical_align: 'bottom'}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_vertical_align_top]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_vertical_align: 'top'}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_padding_left]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_padding_left: $('#jform_cell_padding_left').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_padding_top]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_padding_top: $('#jform_cell_padding_top').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_padding_right]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_padding_right: $('#jform_cell_padding_right').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_padding_bottom]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_padding_bottom: $('#jform_cell_padding_bottom').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_background_radius_left_top]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_background_radius_left_top: $('#jform_cell_background_radius_left_top').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_background_radius_right_top]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_background_radius_right_top: $('#jform_cell_background_radius_right_top').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_background_radius_right_bottom]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_background_radius_right_bottom: $('#jform_cell_background_radius_right_bottom').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_cell_background_radius_left_bottom]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {cell_background_radius_left_bottom: $('#jform_cell_background_radius_left_bottom').val()}, ij, ik);
                        }
                    }
                    break;
                case 'jform[jform_row_height]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        Wptm.style.rows = fillArray(Wptm.style.rows, {height: $('#jform_row_height').val()}, ij);
                    }
                    pullDims();
                    break;
                case 'jform[jform_col_width]':
                    for (ij = selection[1]; ij <= selection[3]; ij++) {
                        Wptm.style.cols = fillArray(Wptm.style.cols, {width: $('#jform_col_width').val()}, ij);
                    }
                    pullDims();
                    break;

                case 'jform[jform_tooltip_width]':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {tooltip_width: $('#jform_tooltip_width').val()}, ij, ik);
                        }
                    }
                    break;
                case 'tooltip_content':
                    for (ij = selection[0]; ij <= selection[2]; ij++) {
                        for (ik = selection[1]; ik <= selection[3]; ik++) {
                            Wptm.style.cells = fillArray(Wptm.style.cells, {tooltip_content: $('#tooltip_content').val()}, ij, ik);
                        }
                    }

                    break;
                case 'jform[jform_date_format]':
                    Wptm.style.table.date_formats = $(this).val();
                    break;
                case 'jform[jform_symbol_position]':
                    Wptm.style.table.symbol_position = $(this).val();
                    break;
                case 'jform[jform_currency_sym]':
                    Wptm.style.table.currency_symbol = $(this).val();
                    break;
                case 'jform[jform_decimal_sym]':
                    Wptm.style.table.decimal_symbol = $(this).val();
                    break;
                case 'jform[jform_decimal_count]':
                    Wptm.style.table.decimal_count = $(this).val();
                    break;
                case 'jform[jform_thousand_sym]':
                    Wptm.style.table.thousand_symbol = $(this).val();
                    break;
            }

            if (typeof Wptm.style.table.currency_symbol === 'undefined') {
                Wptm.style.table.currency_symbol = default_value.currency_symbol;
            }

            if (typeof Wptm.style.table.date_formats === 'undefined') {
                Wptm.style.table.date_formats = default_value.date_formats;
            }

            string_currency_symbols = Wptm.style.table.currency_symbol.replace(/ /g, "");
            // create string reg currency symbols
            replace_unit = new RegExp('[' + string_currency_symbols.replace(/,/g, "|") + ']', "g");

            // create string reg have not currency symbols
            text_replace_unit = '[^a-zA-Z|' + string_currency_symbols.replace(/,/g, "|^") + ']';
            text_replace_unit = new RegExp(text_replace_unit, "g");
            date_format = Wptm.style.table.date_formats.match(/[a-zA-Z|\\]+/g);

            if (e.type == "change" && ($(this).attr('name') == 'freeze_row' || $(this).attr('name') == 'freeze_col')) {

                var ht = $(Wptm.container).handsontable('getInstance');
                var htContents = ht.getData();
                var selection = $(Wptm.container).handsontable('getSelected');
                $('#tableContainer').handsontable('destroy');
                initHandsontable(htContents);
                $(Wptm.container).handsontable('render');
                $(Wptm.container).handsontable("selectCell", selection[0], selection[1], selection[2], selection[3]);
                $('#rightcol .referTable a').trigger('click');
            } else {
                $(Wptm.container).handsontable('render');
            }
            saveChanges();


            //undo
            var ht = $(Wptm.container).handsontable('getInstance');

            if (JSON.stringify(Wptm.style) != JSON.stringify(oldStyle)) {
                ht.runHooks('afterChangeStyle', true, oldStyle);
            }
        });
    })();

    (initCssObserver = function () {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return false;
        }
        var cssChangeWait;
        $('#jform_css').bind('input propertychange', function () {
            clearTimeout(cssChangeWait);
            cssChangeWait = setTimeout(function () {
                parseCss();
                saveChanges();
            }, 1000);
        });
    })();

    parseCss = function () {
        var parser = new (less.Parser);
        content = '#preview .handsontable .ht_master .wtHider .wtSpreader .htCore tbody {' + $('#jform_css').val() + '}';
        content += '.reset {background-color: rgb(238, 238, 238);border-bottom-color: rgb(204, 204, 204);border-bottom-style: solid;border-bottom-width: 1px;border-collapse: collapse;border-left-color: rgb(204, 204, 204);border-left-style: solid;border-left-width: 1px;border-right-color: rgb(204, 204, 204);border-right-style: solid;border-right-width: 1px;border-top-color: rgb(204, 204, 204);border-top-style: solid;border-top-width: 1px;box-sizing: content-box;color: rgb(34, 34, 34);display: table-cell;empty-cells: show;font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;font-size: 13px;font-weight: normal;line-height: 21px;outline-width: 0px;overflow-x: hidden;overflow-y: hidden;padding-bottom: 0px;padding-left: 4px;padding-right: 4px;padding-top: 0px;text-align: center;vertical-align: top;white-space: nowrap;position: relative;}';
        content += '#preview .handsontable .ht_master .wtHider .wtSpreader .htCore tbody tr th {.reset() !important;}'
        parser.parse(content, function (err, tree) {
            if (err) {
                //Here we can throw the erro to the user
                return false;
            } else {
                Wptm.css = $('#jform_css').val();
                if ($('#headCss').length === 0) {
                    $('head').append('<style id="headCss"></style>');
                }
                $('#headCss').text(tree.toCSS());
                return true;
            }
        });
    };

    function pullDims() {
        var cols = [];
        var rows = [];
        var row, lengthRows, lengthCols, col;

        // get count of Wptm.style.rows
        if (typeof Wptm.style.rows.length !== 'undefined') {
            lengthRows = Wptm.style.rows.length;
        } else {
            lengthRows = Object.keys(Wptm.style.rows).length;
        }

        for (row = 0; row < lengthRows; row++) {
            if (checkObjPropertyNested(Wptm.style.rows[row], 1, 'height')) {
                rows[row] = Wptm.style.rows[row][1].height;
            } else {
                rows[row] = null;
            }
        }

        // get count of Wptm.style.cols

        if (typeof Wptm.style.cols.length !== 'undefined') {
            lengthCols = Wptm.style.cols.length;
        } else {
            lengthCols = Object.keys(Wptm.style.cols).length;
        }

        for (col = 0; col < lengthCols; col++) {
            if (checkObjPropertyNested(Wptm.style.cols[col], 1, 'width')) {
                cols[col] = Wptm.style.cols[col][1].width;
            } else {
                cols[col] = null;
            }
        }

        $(Wptm.container).handsontable('updateSettings', {colWidths: cols, rowHeights: rows});
    }

    function pushDims() {
        var rows = $(Wptm.container).handsontable('countRows');
        var tableHeight = 0;
        var rowHeight;
        for (var ij = 0; ij < rows; ij++) {
            var h = $('#tableContainer').handsontable('getRowHeight', ij);
            if (!h) {
                if (typeof (Wptm.style.rows[ij]) !== 'undefined' && (typeof (Wptm.style.rows[ij][1].height) !== 'undefined')) {
                    h = parseInt(Wptm.style.rows[ij][1].height);
                } else {
                    h = null;
                }
            }
            if (!h) {
                h = 22;
            }
            rowHeight = h;
            tableHeight += parseInt(rowHeight);
            Wptm.style.rows = fillArray(Wptm.style.rows, {height: parseInt(rowHeight)}, ij);
        }

        var cols = $(Wptm.container).handsontable('countCols');
        var tableWidth = 0;
        var colWidth;
        for (var ij = 0; ij < cols; ij++) {
            colWidth = $('#preview .handsontable .ht_master .htCore colgroup col:nth-child(' + parseInt(ij + 2) + ')').outerWidth();
            tableWidth += parseInt(colWidth);
            Wptm.style.cols = fillArray(Wptm.style.cols, {width: parseInt(colWidth)}, ij);
        }
        Wptm.style.table.width = tableWidth;
        var configHeight = parseInt(Wptm.style.table.table_height);
        if (configHeight > 0 && tableHeight > configHeight) {
            tableHeight = configHeight + 100;
        } else {
            var offset = $('#tableContainer').offset();
            availableHeight = $(window).height() - offset.top + $(window).scrollTop() - 150;//- (getUrlVar('caninsert') && 50);
            if (tableHeight > availableHeight) {
                tableHeight = availableHeight + 100;
            } else {
                tableHeight = $('#tableContainer .htContainer .ht_master').height();
            }
        }

        $('#tableContainer').height(tableHeight).trigger('resize');

        $(window).scrollTop($(window).scrollTop() - 1); //trigger window scroll event
    }

    // set value for an html input element from cellStyle object
    function updateParamFromStyleObject(styleObj, prop, paramId, defaultValue) {
        if (checkObjPropertyNested(styleObj,prop)) {
            $('#'+paramId).val(styleObj[prop]);
        } else {
            $('#'+paramId).val(defaultValue);
        }
    }

    // check for existence of nested object key
    // example: var test = {level1:{level2:{level3:'level3'}} };
    // checkObjPropertyNested(test, 'level1', 'level2', 'level3'); // true
    function checkObjPropertyNested(obj /*, level1, level2, ... levelN*/) {
        var args = Array.prototype.slice.call(arguments, 1);

        for (var i = 0; i < args.length; i++) {
            if (!obj || !obj.hasOwnProperty(args[i])) {
                return false;
            }
            obj = obj[args[i]];
        }
        return true;
    }


    // check for existence of nested object key and check value not empty
    // example: var test = {level1:{level2:{level3:'level3'}} };
    // checkObjPropertyNestedNotEmpty(test, 'level1', 'level2', 'level3'); // true
    function checkObjPropertyNestedNotEmpty(obj /*, level1, level2, ... levelN*/) {
        var args = Array.prototype.slice.call(arguments, 1);

        for (var i = 0; i < args.length; i++) {
            if (!obj || !obj.hasOwnProperty(args[i]) || !obj[args[i]]) {
                return false;
            }
            obj = obj[args[i]];
        }
        return true;
    }

    var customRenderer = function (instance, td, row, col, prop, value, cellProperties) {
        var css = {};
        var celltype = '';
        if (typeof (Wptm.style.cells) !== 'undefined') {
            //Cells rendering
            if (typeof (Wptm.style.cells[row + "!" + col]) !== 'undefined') {
                var cellStyle = Wptm.style.cells[row + "!" + col];
                if (typeof cellStyle[2].AlternateColor !== 'undefined') {
                    css["background-color"] = '';
                    var Value = oldAlternate[cellStyle[2].AlternateColor];
                    if (typeof Value === 'undefined') {
                        if (typeof changeAlternate[cellStyle[2].AlternateColor] !== 'undefined') {
                            cellStyle[2].AlternateColor = changeAlternate[cellStyle[2].AlternateColor];
                            Value = oldAlternate[cellStyle[2].AlternateColor];
                        }
                    }

                    if (typeof Value !== 'undefined') {
                        var numberRow = 0;
                        if (Value.header === '') {
                            numberRow = -1;
                        }
                        switch (row) {
                            case Value.selection[0]:
                                if (Value.header === '') {
                                    css["background-color"] = Value.even;
                                } else {
                                    css["background-color"] = Value.header;
                                }
                                break;
                            case Value.selection[2]:
                                if (Value.footer === '') {
                                    if ((row - parseInt(Value.selection[0] + numberRow)) % 2) {
                                        css["background-color"] = Value.even;
                                    } else {
                                        css["background-color"] = Value.old;
                                    }
                                } else {
                                    css["background-color"] = Value.footer;
                                }
                                break;
                            default:
                                if ((row - parseInt(Value.selection[0] + numberRow)) % 2) {
                                    css["background-color"] = Value.even;
                                } else {
                                    css["background-color"] = Value.old;
                                }
                                break;
                        }
                    } else {
                        delete cellStyle[2].AlternateColor;
                    }
                }

                if (checkObjPropertyNestedNotEmpty(cellStyle,2,'cell_type')) {
                    celltype = cellStyle[2].cell_type;
                }
                if (checkObjPropertyNestedNotEmpty(cellStyle,2,'cell_background_color')) {
                    css["background-color"] = cellStyle[2].cell_background_color;
                }
                if (checkObjPropertyNestedNotEmpty(cellStyle,2,'cell_border_top')) {
                    css["border-top"] = cellStyle[2].cell_border_top;
                }
                if (checkObjPropertyNestedNotEmpty(cellStyle,2,'cell_border_right')) {
                    css["border-right"] = cellStyle[2].cell_border_right;
                }
                if (checkObjPropertyNestedNotEmpty(cellStyle,2,'cell_border_bottom')) {
                    css["border-bottom"] = cellStyle[2].cell_border_bottom;
                }
                if (checkObjPropertyNestedNotEmpty(cellStyle,2,'cell_border_left')) {
                    css["border-left"] = cellStyle[2].cell_border_left;
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_font_bold') && cellStyle[2].cell_font_bold === true) {
                    css["font-weight"] = "bold";
                } else {
                    delete css["font-weight"];
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_font_italic') && cellStyle[2].cell_font_italic === true) {
                    css["font-style"] = "italic";
                } else {
                    delete css["font-style"];
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_font_underline') && cellStyle[2].cell_font_underline === true) {
                    css["text-decoration"] = "underline";
                } else {
                    delete css["text-decoration"];
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_text_align')) {
                    css["text-align"] = cellStyle[2].cell_text_align;
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_vertical_align')) {
                    css["vertical-align"] = cellStyle[2].cell_vertical_align;
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_font_family')) {
                    css["font-family"] = cellStyle[2].cell_font_family;
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_font_size')) {
                    css["font-size"] = cellStyle[2].cell_font_size + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_font_color')) {
                    css["color"] = cellStyle[2].cell_font_color;
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_padding_left')) {
                    css["padding-left"] = cellStyle[2].cell_padding_left + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_padding_top')) {
                    css["padding-top"] = cellStyle[2].cell_padding_top + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_padding_right')) {
                    css["padding-right"] = cellStyle[2].cell_padding_right + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_padding_bottom')) {
                    css["padding-bottom"] = cellStyle[2].cell_padding_bottom + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_background_radius_left_top')) {
                    css["border-top-left-radius"] = cellStyle[2].cell_background_radius_left_top + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_background_radius_right_top')) {
                    css["border-top-right-radius"] = cellStyle[2].cell_background_radius_right_top + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_background_radius_right_bottom')) {
                    css["border-bottom-right-radius"] = cellStyle[2].cell_background_radius_right_bottom + "px";
                }
                if (checkObjPropertyNested(cellStyle,2,'cell_background_radius_left_bottom')) {
                    css["border-bottom-left-radius"] = cellStyle[2].cell_background_radius_left_bottom + "px";
                }
            }
            //$(td).css(css);
            if (Object.keys(css).length > 0) {
                styleToRender += '.dtr' + row + '.dtc' + col + '{';
                $.each(css, function (index, value) {
                    styleToRender += index + ':' + value + ';';
                });
                styleToRender += '}';
            }
        }

        switch (celltype) {
            case 'html':
                var escaped = Handsontable.helper.stringify(value);
                //escaped = strip_tags(escaped, '<div><span><img><em><b><a>'); //be sure you only allow certain HTML tags to avoid XSS threats (you should also remove unwanted HTML attributes)
                td.innerHTML = escaped;
                $(td).addClass('isHtmlCell');
                break;
            default:
                $(td).removeClass('isHtmlCell');
                Handsontable.renderers.TextRenderer.apply(this, arguments);
                break;
        }


        /* Calculs rendering */
        if (typeof Wptm.style.table.date_formats === 'undefined') {
            Wptm.style.table.date_formats = default_value.date_formats;
        }

        if (typeof (value) === 'string' && value[0] === '=') {
            evaluateFormulas(td, value);
        }

        $(td).addClass('dtr' + row + ' dtc' + col);
        return td;
    };

    // Evaluate formula in cell then set value to td
    function evaluateFormulas (td, value) {
        check_value_data = true;
        if (typeof (value) === 'string' && value[0] === '=') {
            var error = false;
            var result = regex.exec(value);
            var v;
            if (result !== null) {
                // get function caculate cells
                // caculater(result, );
                var cells = result[2].split(";");
                var values = [];
                var rCells = [];
                var val1, val2, number;
                // check calculate date
                var check_isDay = (result[1].split("DAY").length === 2);
                var value_unit;
                for (var ij = 0; ij < cells.length; ij++) {
                    var vals = cells[ij].split(":");
                    var datas = '';
                    var data = '';

                    if (vals.length === 1) { //single cell
                        //cut vals[0] when number cell, ex: B1 >= B2
                        var val1s = vals[0].match(regex3);
                        if (val1s !== null) {
                            for (var i = 0; i < val1s.length; i++) {
                                //ex: B1 -> B, 1, B1
                                val1 = regex2.exec(val1s[i]);
                                if (val1 !== null) {
                                    datas = $tableContainer.handsontable('getDataAtCell', val1[2] - 1, convertAlpha(val1[1]) - 1);
                                    if (datas !== null && typeof datas !== 'undefined') {
                                        if (i === 0) {
                                            data = vals[0].replace(val1s[i], datas);
                                        } else {
                                            data = data.replace(val1s[i], datas);
                                        }
                                    } else {
                                        datas = data = '';
                                    }
                                } else {
                                    check_value_data = false;
                                }
                            }
                        } else {
                            data = datas = vals[0];
                        }

                        if (val1 !== null && data !== null) {
                            // removed currency symbols
                            if (result[1].toUpperCase() !== 'CONCAT') {
                                var math1 = data.replace(replace_unit, "");
                                //remove thousand symbols, change decimal symbols
                                if (check_isDay === false) {
                                    math1 = (Wptm.style.table.thousand_symbol === ',')
                                        ? math1.replace(/,/g, "")
                                        : (Wptm.style.table.thousand_symbol === '.'
                                            ? math1.replace(/\./g, "")
                                            : math1);
                                    math1 = (Wptm.style.table.decimal_symbol === ',')
                                        ? math1.replace(/,/, ".")
                                        : math1;
                                }
                            } else {
                                var math1 = data;
                            }
                            // cut math1 when have <= || ...
                            var math2 = math1.match(/<=|>=|!=|>|<|=/g);

                            if (math2 !== null) {
                                math1 = math1.replace(/[ |A-Za-z]+/g, "");
                                switch (math2[0]) {
                                    case '<=':
                                        number = Number(math1.split('<=')[0]) <= Number(math1.split('<=')[1]);
                                        break;
                                    case '>=':
                                        number = Number(math1.split('>=')[0]) >= Number(math1.split('>=')[1]);
                                        break;
                                    case '=':
                                        number = Number(math1.split('=')[0]) === Number(math1.split('=')[1]);
                                        break;
                                    case '!=':
                                        number = Number(math1.split('!=')[0]) !== Number(math1.split('!=')[1]);
                                        break;
                                    case '<':
                                        number = Number(math1.split('<')[0]) < Number(math1.split('<')[1]);
                                        break;
                                    case '>':
                                        number = Number(math1.split('>')[0]) > Number(math1.split('>')[1]);
                                        break;
                                    default :
                                        number = math1;
                                        break;
                                }
                            } else {
                                number = math1;
                            }
                            values.push(number);
                        }
                    } else { //range
                        val1 = regex2.exec(vals[0]);
                        val2 = regex2.exec(vals[1]);
                        if (val1 !== null && val2 !== null) {
                            rCells = $tableContainer.handsontable('getData', val1[2] - 1, convertAlpha(val1[1]) - 1, val2[2] - 1, convertAlpha(val2[1]) - 1);
                            for (var il = 0; il < rCells.length; il++) {
                                for (var ik = 0; ik < rCells[il].length; ik++) {
                                    if (rCells[il][ik] !== null && typeof rCells[il][ik] !== 'undefined') {
                                        if (result[1].toUpperCase() !== 'CONCAT') {
                                            number = rCells[il][ik].replace(replace_unit, "");
                                            if (check_isDay === false) {
                                                number = (Wptm.style.table.thousand_symbol === ',')
                                                    ? number.replace(/,/g, "")
                                                    : (Wptm.style.table.thousand_symbol === '.'
                                                        ? number.replace(/\./g, "")
                                                        : number);
                                                number = (Wptm.style.table.decimal_symbol === ',')
                                                    ? number.replace(/,/g, ".")
                                                    : number;
                                            }
                                        } else {
                                            number = rCells[il][ik];
                                        }
                                        values.push(number);
                                        datas = rCells[il][ik];
                                    } else {
                                        values.push('');
                                    }
                                }
                            }
                            if (check_isDay === true && Number(val2[2]) < Number(val1[2])) {
                                var data_values = values[0];
                                values[0] = values[1];
                                values[1] = data_values;
                            }
                        } else {
                            if (convertDate(date_format, cells[ij].match(/[a-zA-Z0-9|+|-|\\]+/g), true) !== false) {
                                values.push(cells[ij]);
                            } else {
                                check_value_data = false;
                            }
                        }
                    }
                    value_unit = (typeof datas !== 'undefined') ? datas.toString().replace(text_replace_unit, "") : '';
                }
                if (check_value_data === true) {
                    var resultCalc;
                    var date1 = 0;
                    switch (result[1].toUpperCase()) {
                        case 'DATE':
                            if (values !== []) {
                                if (values.length === 1) {
                                    values = values[0].match(/[a-zA-Z0-9|+|-|\\]+/g);
                                }
                                //convert values --> (string) date pursuant date_format not have timezone
                                var date_string = convertDate(date_format, values, false);
                                date_string = date_string !== false ? new Date(date_string) : check_value_data = false;

                                //convert values --> (string) date pursuant date_format have timezone
                                var date_string_timezone = convertDate(date_format, values, true);
                                date_string_timezone = date_string_timezone !== false ? new Date(date_string_timezone) : check_value_data = false;
                                if (date_string_timezone && date_string_timezone.getDate() > 0 && check_value_data !== false) {
                                    var format_resultCalc = Wptm.style.table.date_formats.split(/[a-zA-Z|\\]+/g);
                                    var date = [];

                                    date['month'] = date_string.getMonth();
                                    date['date'] = date_string.getDate();
                                    date['day'] = date_string.getDay();
                                    date['year'] = date_string.getUTCFullYear();

                                    date['D'] = D_name[date['day']];
                                    date['l'] = l_name[date['day']];
                                    date['j'] = date['date'];
                                    date['d'] = (Number(date['date']) < 10) ? '0' + date['date'] : date['date'];
                                    date['F'] = F_name[date['month']];
                                    date['M'] = M_name[date['month']];
                                    date['n'] = Number(date['month']) + 1;
                                    date['m'] = (Number(date['month']) < 10) ? '0' + (Number(date['month']) + 1) : Number(date['month']) + 1;
                                    date['Y'] = date['year'];
                                    date['y'] = Number(date['year']) % 100;

                                    resultCalc = format_resultCalc[0];
                                    $.each(date_format, function (i, v) {
                                        if (v.indexOf("\\") !== -1 || $.inArray(v, ["a", "A", "g", "G", "h", "H", "i", "s", "T"]) !== -1) {
                                            date[v] = values[i];
                                        }
                                        resultCalc += date[v] + format_resultCalc[i + 1];
                                    });
                                } else {
                                    resultCalc = 'NaN';
                                    check_value_data = false;
                                }
                            } else {
                                resultCalc = 'NaN';
                                check_value_data = false;
                            }
                            break;
                        case 'DAY':
                            resultCalc = 0;
                            if (check_value_data !== false) {
                                values.map(function (foo) {
                                    if (foo !== false) {
                                        v = foo.replace(val1s, datas);
                                        var number = v.match(/[a-zA-Z0-9|+|-|\\]+/g);
                                        var string_day = convertDate(date_format, number, false);
                                        if (string_day !== false) {
                                            date1 = new Date(string_day);
                                            if (!isNaN(date1.getTime())) {
                                                resultCalc = date1.getDate();
                                            } else {
                                                resultCalc = 'NaN';
                                                check_value_data = false;
                                            }
                                        } else {
                                            resultCalc = 'NaN';
                                            check_value_data = false;
                                        }
                                    } else {
                                        resultCalc = 'NaN';
                                        check_value_data =false;
                                    }
                                });
                            } else {
                                resultCalc = 'NaN';
                            }
                            break;
                        case 'DAYS':
                            resultCalc = 0;
                            if (check_value_data !== false) {
                                values.map(function (foo) {
                                    if (foo !== false) {
                                        v = foo;
                                        var number = v.match(/[a-zA-Z0-9|+|-|\\]+/g);
                                        var string_day = convertDate(date_format, number, true);
                                        if (string_day !== false) {
                                            date1 = new Date(string_day);
                                            if (!isNaN(date1.getTime())) {
                                                date1 = -1 * (date1 / (3600 * 1000 * 24));
                                                resultCalc = date1 - resultCalc;
                                            } else {
                                                resultCalc = 'NaN';
                                                check_value_data = false;
                                            }
                                        } else {
                                            resultCalc = 'NaN';
                                            check_value_data = false;
                                        }
                                    } else {
                                        resultCalc = 'NaN';
                                        check_value_data = false;
                                    }
                                });
                            } else {
                                resultCalc = 'NaN';
                            }
                            if (resultCalc !== 'NaN') {
                                resultCalc = (Math.floor(resultCalc) < 0 && resultCalc !== Math.floor(resultCalc)) ? Math.floor(resultCalc) + 1 : Math.floor(resultCalc);
                            }
                            break;
                        case 'DAYS360':
                            resultCalc = 0;
                            if (check_value_data !== false) {
                                var month = [];
                                var year = [];
                                var days = [];
                                values.map(function (foo) {
                                    if (foo !== false) {
                                        resultCalc++;
                                        v = foo;
                                        var number = v.match(/[a-zA-Z0-9|+|-|\\]+/g);
                                        var string_day = convertDate(date_format, number, true);
                                        if (string_day !== false) {
                                            date1 = new Date(string_day);
                                            if (!isNaN(date1.getTime())) {
                                                days[resultCalc] = date1.getDate();
                                                month[resultCalc] = date1.getMonth();
                                                year[resultCalc] = date1.getFullYear();
                                            }
                                        }
                                    }
                                });
                                if (year.length > 1) {
                                    if (year[2] < year[1]) {
                                        year[2] = year[1] + year[2];
                                        year[1] = year[2] - year[1];
                                        year[2] = year[2] - year[1];
                                        month[2] = month[1] + month[2];
                                        month[1] = month[2] - month[1];
                                        month[2] = month[2] - month[1];
                                        days[2] = days[1] + days[2];
                                        days[1] = days[2] - days[1];
                                        days[2] = days[2] - days[1];
                                        year[4] = -1;
                                    } else {
                                        year[4] = 1;
                                    }
                                    year[3] = 0;

                                    for (i = year[1]; i < year[2]; i++) {
                                        year[3] += 1;
                                    }
                                    days[1] = (days[1] === 31) ? 30 : days[1];
                                    days[2] = (days[2] === 31) ? 30 : days[2];
                                    resultCalc = year[4] * (((year[3] - 1) * 360) + ((13 - month[1]) * 30 - days[1]) + ((month[2] - 1) * 30 + days[2]));
                                    check_value_data = !isNaN(resultCalc);
                                } else {
                                    resultCalc = 'NaN';
                                    check_value_data = false;
                                }
                            } else {
                                resultCalc = 'NaN';
                            }
                            break;
                        case 'AND':
                            resultCalc = 1;
                            values.map(function (foo) {
                                v = Number(foo);
                                resultCalc = resultCalc * v;
                            });
                            resultCalc = (resultCalc === 1) ? 'true' : 'false';
                            break;
                        case 'OR':
                            resultCalc = 0;
                            values.map(function (foo) {
                                v = Number(foo);
                                resultCalc += v;
                            });
                            resultCalc = (resultCalc > 0) ? 'true' : 'false';
                            break;
                        case 'XOR':
                            resultCalc = 2;
                            values.map(function (foo) {
                                v = Number(foo);
                                resultCalc += v;
                            });
                            resultCalc = ((resultCalc % 2) === 1) ? 'true' : 'false';
                            break;
                        case 'SUM':
                            resultCalc = 0;
                            values.map(function (foo) {
                                if (foo !== false) {
                                    foo = foo.replace(/[ |A-Za-z]+/g, "");
                                    v = Number(foo);
                                    if (!isNaN(v)) {
                                        resultCalc = resultCalc + v;
                                    }
                                }
                            });
                            resultCalc = formatSymbols(
                                resultCalc,
                                Wptm.style.table.decimal_count,
                                Wptm.style.table.thousand_symbol,
                                Wptm.style.table.decimal_symbol,
                                Wptm.style.table.symbol_position,
                                value_unit
                            );
                            break;
                        case 'COUNT':
                            resultCalc = 0;
                            values.map(function (foo) {
                                if (foo !== false) {
                                    foo = foo.replace(/[ |A-Za-z]+/g, "");
                                    v = Number(foo);
                                    if (!isNaN(v) && foo !== '') {
                                        resultCalc = resultCalc + 1;
                                    }
                                }
                            });
                            break;
                        case 'MIN':
                            resultCalc = null;
                            values.map(function (foo) {
                                if (foo !== false) {
                                    foo = foo.replace(/[ |A-Za-z]+/g, "");
                                    v = Number(foo);
                                    if (!isNaN(v) && foo !== '') {
                                        if (resultCalc === null || resultCalc > v) {
                                            resultCalc = v;
                                        }
                                    }
                                }
                            });
                            resultCalc = formatSymbols(
                                resultCalc,
                                Wptm.style.table.decimal_count,
                                Wptm.style.table.thousand_symbol,
                                Wptm.style.table.decimal_symbol,
                                Wptm.style.table.symbol_position,
                                value_unit
                            );
                            break;
                        case 'MAX':
                            resultCalc = null;
                            values.map(function (foo) {
                                if (foo !== false) {
                                    foo = foo.replace(/[ |A-Za-z]+/g, "");
                                    v = Number(foo);
                                    if (!isNaN(v)) {
                                        if (resultCalc === null || resultCalc < v) {
                                            resultCalc = v;
                                        }
                                    }
                                }
                            });
                            resultCalc = formatSymbols(
                                resultCalc,
                                Wptm.style.table.decimal_count,
                                Wptm.style.table.thousand_symbol,
                                Wptm.style.table.decimal_symbol,
                                Wptm.style.table.symbol_position,
                                value_unit
                            );
                            break;
                        case 'AVG':
                            resultCalc = 0;
                            var n = 0;
                            values.map(function (foo) {
                                if (foo !== false) {
                                    foo = foo.replace(/[ |A-Za-z]+/g, "");
                                    v = Number(foo);
                                    if (!isNaN(v) && foo !== '') {
                                        resultCalc = resultCalc + v;
                                        n++;
                                    }
                                }
                            });
                            if (n > 0) {
                                resultCalc = resultCalc / n;
                            }
                            resultCalc = formatSymbols(
                                resultCalc,
                                Wptm.style.table.decimal_count,
                                Wptm.style.table.thousand_symbol,
                                Wptm.style.table.decimal_symbol,
                                Wptm.style.table.symbol_position,
                                value_unit
                            );
                            break;
                        case 'CONCAT':
                            resultCalc = '';
                            values.map(function (foo) {
                                resultCalc = resultCalc + foo;
                            });
                            break;
                    }
                }
            }
            if (check_value_data === true) {
                $(td).text(resultCalc);
            }
        }
    };

    (formatSymbols = function (resultCalc, decimal_count, thousand_symbols, decimal_symbols, symbol_position, value_unit) {
        decimal_count = parseInt(decimal_count);
        if (typeof resultCalc === 'undefined') {
            return;
        }
        var negative = resultCalc < 0 ? "-" : "",
            i = parseInt(resultCalc = Math.abs(+resultCalc || 0).toFixed(decimal_count), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;

        resultCalc = (j ? i.substr(0, j) + thousand_symbols : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand_symbols) + (decimal_count ? decimal_symbols + Math.abs(resultCalc - i).toFixed(decimal_count).slice(2) : "");

        resultCalc = Number(symbol_position) === 0
            ? ((negative === "-") ? negative + value_unit : value_unit) + resultCalc
            : negative + resultCalc + ' ' + value_unit;

        return resultCalc;
    });

    // Convert date in date_format to the format: m/d/Y /a/t g:i A
    // example: number -> array(2016,03,26)
    (convertDate = function (date_format, number, timezone) {
        var date_array = [];
        number = (!!number) ? number : [];
        if (date_format.length !== number.length) {
            return false;
        }
        if (typeof timezone === 'undefined') {
            timezone = true;
        }
        for (var n = 0; n < date_format.length; n++) {
            number[n] = (!!number[n]) ? number[n] : '';
            if (date_format[n] === 'd' || date_format[n] === 'j') {
                date_array[2] = (number[n] !== '') ? number[n] : '';
            } else if (date_format[n] === 'S' || date_format[n] === 'jS' || date_format[n] === 'dS') {
                date_array[2] = number[n].match(/[0-9]+/g);
            } else if (date_format[n] === 'm' || date_format[n] === 'n') {
                date_array[1] = number[n];
            } else if (date_format[n] === 'F') {
                date_array[1] = F_name.indexOf(number[n].toLowerCase()) + 1;
            } else if (date_format[n] === 'M') {
                date_array[1] = M_name.indexOf(number[n].toLowerCase()) + 1;
            } else if (date_format[n].toLowerCase() === 'y') {
                date_array[3] = number[n];
            } else if (date_format[n].toLowerCase() === 'g' || date_format[n].toLowerCase() === 'h') {
                date_array[4] = Number(number[n]);
            } else if (date_format[n].toLowerCase() === 'ga' || date_format[n].toLowerCase() === 'ha') {
                date_array[4] = number[n].match(/[0-9]+/g);
                date_array[4] = (number[n].toLowerCase().match(/[a-z]+/g) === 'am') ? date_array[4] : date_array[4] + 12;
            } else if (date_format[n].toLowerCase() === 'a') {
                date_array[7] = number[n];
            } else if (date_format[n].toLowerCase() === 'i' || date_format[n].toLowerCase() === 'ia') {
                date_array[5] = number[n].match(/[0-9]+/g);
            } else if (date_format[n].toLowerCase() === 's' || date_format[n].toLowerCase() === 'sa') {
                date_array[6] = number[n].match(/[0-9]+/g);
            } else if (date_format[n] === 'T') {
                date_array[8] = number[n];
            } else if (date_format[n] === 'r') {
                if (M_name.indexOf(number[2].toLowerCase()) + 1 > 0) {
                    date_array[1] = M_name.indexOf(number[2].toLowerCase()) + 1;
                } else {
                    date_array[1] = F_name.indexOf(number[2].toLowerCase()) + 1;
                }
                return date_array[1] + '/' + number[1] + '/' + number[3] + ' ' + number[4] + ':' + number[5] + ':' + number[6] + ' ' + number[7];
            }
        }
        date_array[4] = (!!date_array[4]) ? date_array[4] : 0;
        date_array[5] = (!!date_array[5]) ? date_array[5] : '00';
        date_array[6] = (!!date_array[6]) ? date_array[6] : '00';
        date_array[7] = (!!date_array[7]) ? ' ' + date_array[7] : '';
        date_array[8] = (!!date_array[8]) ? ' ' + date_array[8] : '';
        date_array[8] = (timezone === true) ? ' ' + date_array[8] : '';

        if (date_array[7] !== 'undefined' && date_array[7] !== '' && date_array[4] > 12) {
            date_array[4] = date_array[4] - 12;
        }
        if (date_array[1] === 0 || date_array[2] > 31 || date_array[1] > 12) {
            return false;
        }
        return date_array[1] + '/' + date_array[2] + '/' + date_array[3] + ' ' + date_array[4] + ':' + date_array[5] + ':' + date_array[6] + date_array[7] + date_array[8];
    });

    // Update cell parameters when selected
    (loadSelection = function () {
        var selection = $(Wptm.container).handsontable('getSelected');
        if (!selection) {
            return;
        }
        var col;

        if (checkObjPropertyNested(Wptm.style,'table')) {
            var tableStyle = Wptm.style.table;

            updateParamFromStyleObject(tableStyle,'use_sortable','jform_use_sortable','');
            $('#jform_use_sortable').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'enable_filters','jform_enable_filters','0');
            $('#jform_enable_filters').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'spreadsheet_url','jform_spreadsheet_url','');

            updateParamFromStyleObject(tableStyle,'auto_sync','jform_auto_sync','0');
            $('#jform_auto_sync').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'spreadsheet_style','jform_spreadsheet_style','0');
            $('#jform_spreadsheet_style').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'download_button','jform_download_button','0');
            $('#jform_download_button').trigger('liszt:updated');

            var $jform_freeze_row = $('#jform_freeze_row');
            if (checkObjPropertyNested(tableStyle,'freeze_row')) {
                $jform_freeze_row.val(tableStyle.freeze_row);
                if (tableStyle.freeze_row && tableStyle.freeze_row != "0") {
                    if (checkObjPropertyNested(tableStyle,'table_height') && tableStyle.table_height) {
                        $('#jform_table_height').val(tableStyle.table_height);
                    } else {
                        tableStyle.table_height = 500;
                        $('#jform_table_height').val('500');
                    }
                    $("#table_height_container").show();
                } else {
                    $('#jform_table_height').val("");
                    $("#table_height_container").hide();
                }
            } else {
                $jform_freeze_row.val("0");
                $('#jform_table_height').val("");
                $("#table_height_container").hide();
            }
            $('#jform_freeze_row').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'freeze_col','jform_freeze_col','0');
            $('#jform_freeze_col').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'table_align','jform_table_align','');
            $('#jform_table_align').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'usergroup','jform_usergroup','');
            $('#jform_usergroup').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'enable_pagination','jform_enable_pagination','');
            $('#jform_enable_pagination').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'limit_rows','jform_limit_rows','');
            $('#jform_limit_rows').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'default_sortable','jform_default_sortable','');
            $('#jform_default_sortable').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'default_order_sortable','jform_default_order_sortable','');
            $('#jform_default_order_sortable').trigger('liszt:updated');

            updateParamFromStyleObject(tableStyle,'date_formats','jform_date_format','');
            updateParamFromStyleObject(tableStyle,'currency_symbol','jform_currency_sym','');
            updateParamFromStyleObject(tableStyle,'symbol_position','jform_symbol_position','');
            $('#jform_symbol_position').trigger('liszt:updated');
            updateParamFromStyleObject(tableStyle,'decimal_symbol','jform_decimal_sym','');
            updateParamFromStyleObject(tableStyle,'decimal_count','jform_decimal_count','');
            updateParamFromStyleObject(tableStyle,'thousand_symbol','jform_thousand_sym','');

            //populate jform_responsive_type
            if (checkObjPropertyNested(tableStyle, 'responsive_type')) {
                $('#jform_responsive_type').val(tableStyle.responsive_type);
                if (tableStyle.responsive_type == 'scroll') {
                    $('#jform_responsive_col').parents('.control-group:first').hide();
                    $('#jform_responsive_priority').parents('.control-group:first').hide();
                    $("#freeze_options").show();
                } else {
                    $('#jform_responsive_col').parents('.control-group:first').show();
                    $('#jform_responsive_priority').parents('.control-group:first').show();
                    $("#freeze_options").hide();
                }
            } else {
                $('#jform_responsive_type').val("scroll");
            }
            $('#jform_responsive_type').trigger('liszt:updated');
        } else {
            $('#jform_spreadsheet_url').val("");
            $('#jform_auto_sync').val("0");
            $('#jform_spreadsheet_style').val("0");
        }

        if (typeof (Wptm.style) !== 'undefined' && typeof (Wptm.style.cells) !== 'undefined') {
            var cellIndex = selection[0] + "!" + selection[1];

            if (checkObjPropertyNested(Wptm.style,'cells',cellIndex,2)) {
                var cellStyle = Wptm.style.cells[cellIndex][2];

                updateParamFromStyleObject(cellStyle,'cell_type','jform_cell_type','');
                $('#jform_cell_type').trigger('liszt:updated');

                updateParamFromStyleObject(cellStyle,'cell_background_color','jform_cell_background_color','');
                if (checkObjPropertyNested(cellStyle,'cell_background_color') && cellStyle.cell_background_color) {
                    $('#jform_cell_background_color').wpColorPicker('color', cellStyle.cell_background_color);
                } else {
                    $('#jform_cell_background_color').parents('.wp-picker-input-wrap').find('.wp-picker-clear').trigger('click');
                }
                updateParamFromStyleObject(cellStyle,'cell_font_size','jform_cell_font_size',13);

                updateParamFromStyleObject(cellStyle,'cell_font_color','jform_cell_font_color','');
                if (checkObjPropertyNested(cellStyle,'cell_font_color') && cellStyle.cell_font_color) {
                    $('#jform_cell_font_color').wpColorPicker('color', cellStyle.cell_font_color);
                } else {
                    $('#jform_cell_font_color').parents('.wp-picker-input-wrap').find('.wp-picker-clear').trigger('click');
                }
                updateParamFromStyleObject(cellStyle,'cell_font_family','jform_cell_font_family','Arial');
                $('#jform_cell_font_family').trigger('liszt:updated');

                updateParamFromStyleObject(cellStyle,'cell_padding_left','jform_cell_padding_left',0);
                updateParamFromStyleObject(cellStyle,'cell_padding_top','jform_cell_padding_top',0);
                updateParamFromStyleObject(cellStyle,'cell_padding_right','jform_cell_padding_right',0);
                updateParamFromStyleObject(cellStyle,'cell_padding_bottom','jform_cell_padding_bottom',0);

                updateParamFromStyleObject(cellStyle,'cell_background_radius_left_top','jform_cell_background_radius_left_top',0);
                updateParamFromStyleObject(cellStyle,'cell_background_radius_right_top','jform_cell_background_radius_right_top',0);
                updateParamFromStyleObject(cellStyle,'cell_background_radius_right_bottom','jform_cell_background_radius_right_bottom',0);
                updateParamFromStyleObject(cellStyle,'cell_background_radius_left_bottom','jform_cell_background_radius_left_bottom',0);

                var $tooltip_content = $('#tooltip_content');
                if ($tooltip_content.length > 0) {
                    updateParamFromStyleObject(cellStyle, 'tooltip_width', 'jform_tooltip_width', 0);

                    tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'tooltip_content');
                    updateParamFromStyleObject(cellStyle, 'tooltip_content', 'tooltip_content', "");
                    var contenNeedToset = $('#tooltip_content').val();

                    var initTT = tinymce.extend({}, tinyMCEPreInit.mceInit['tooltip_content']);
                    try {
                        tinymce.init(initTT);
                    } catch (e) {
                    }

                    //add tinymce to this
                    tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'tooltip_content');
                    if (tinyMCE.EditorManager.get('tooltip_content') != null) {
                        var ttEditor = tinyMCE.EditorManager.get('tooltip_content');
                        if (ttEditor && ttEditor.getContainer()) {
                            ttEditor.setContent(contenNeedToset);
                        }
                    }

                }
            }
        }

        if (checkObjPropertyNested(Wptm.style,'rows',selection[0],1,'height')) {
            $('#jform_row_height').val(Wptm.style.rows[selection[0]][1].height);
        }
        if (checkObjPropertyNested(Wptm.style,'cols',selection[1],1,'width')) {
            $('#jform_col_width').val(Wptm.style.cols[selection[1]][1].width);
        }

        //populate jform_responsive_col
        var $jform_responsive_col = $("#jform_responsive_col");
        $jform_responsive_col.html("");
        for (col in Wptm.style.cols) {
            $jform_responsive_col.append('<option value="' + col + '">' + Handsontable.helper.spreadsheetColumnLabel(parseInt(col)) + '</option>');
        }
        $jform_responsive_col.trigger('liszt:updated');

        //populate jform_responsive_priority
        var $jform_responsive_priority = $("#jform_responsive_priority");
        $jform_responsive_priority.html("");
        for (col in Wptm.style.cols) {
            $jform_responsive_priority.append('<option value="' + col + '">' + col + '</option>');
        }
        $jform_responsive_priority.append('<option value="persistent">Persistent</option>');

        if (checkObjPropertyNested(Wptm.style,'cols',0,1,'res_priority')) {
            $jform_responsive_priority.val(Wptm.style.cols[0][1].res_priority);
        }
        $jform_responsive_priority.trigger('liszt:updated');
    });

    string_replace = function (arr, unit) {
        var thousand_symbol = (!Wptm.style.table.thousand_symbol) ? default_value.thousand_symbol : Wptm.style.table.thousand_symbol;
        var decimal_symbol = (!Wptm.style.table.decimal_symbol) ? default_value.decimal_symbol : Wptm.style.table.decimal_symbol;
        if (typeof arr === 'number') {
            return arr;
        }
        if (typeof arr !== 'undefined' && arr !== '' && arr !== null) {
            if (unit === true) {
                if (typeof arr !== 'string') {
                    arr = arr.toString();
                }
                arr = arr.replace(text_replace_unit, "");
            } else {
                arr = arr.replace(replace_unit, "");
                arr = (thousand_symbol === ',') ? arr.replace(/,/g, "") : (thousand_symbol === '.' ? arr.replace(/\./g, "") : arr);
                arr = (decimal_symbol === ',') ? arr.replace(/,/g, ".") : arr;
            }
        } else {
            arr = '';
        }
        return arr;
    };

    var $jform_cell_border_width = $('#jform_cell_border_width');
    $('#cell_border_width_incr').click(function () {
        $jform_cell_border_width.val((parseInt($jform_cell_border_width.val() || 0)) + 1);
    });
    $('#cell_border_width_decr').click(function () {
        if ($jform_cell_border_width.val() === '0')
            return;
        $jform_cell_border_width.val(Math.abs(parseInt($jform_cell_border_width.val() || 1) - 1));
    });

    var $jform_cell_font_size = $('#jform_cell_font_size');
    $('#cell_font_size_incr').click(function () {
        $jform_cell_font_size.val((parseInt($jform_cell_font_size.val() || 0)) + 1).trigger('change');
    });
    $('#cell_font_size_decr').click(function () {
        if ($jform_cell_font_size.val() === '0')
            return;
        $jform_cell_font_size.val(Math.abs(parseInt($jform_cell_font_size.val() || 1) - 1)).trigger('change');
    });

    // Build column selection for default sort parameter
    function default_sortable(tableData) {
        if (tableData && typeof tableData[0] !== 'undefined') {
            var $jform_default_sortable = $('#tableTabContent').find('#jform_default_sortable');
            $jform_default_sortable.contents('option').remove();
            var html = '';
            var ii = 0;
            $.each(tableData[0], function (i, e) {
                html += '<option value="' + ii + '">' + $tableContainer.find('table thead th:eq(' + (ii + 1) + ') .relative span').text() + '</option>';
                ii++;
            });
            $(html).appendTo($jform_default_sortable);
            $jform_default_sortable.trigger("liszt:updated");
        }
    }

    function loading(e) {
        $(e).addClass('dploadingcontainer');
        $(e).append('<div class="dploading"></div>');
    }

    function rloading(e) {
        $(e).removeClass('dploadingcontainer');
        $(e).find('div.dploading').remove();
    }

    function cleanStyle(style, nbRows, nbCols) {
        for (var col in style.cols) {
            if (!style.cols[col] || style.cols[col][0] >= nbCols) {
                delete style.cols[col];
            }
        }
        for (var row in style.rows) {
            if (!style.rows[row] || style.rows[row][0] >= nbRows) {
                delete style.rows[row];
            }
        }
        for (var cell in style.cells) {
            if (style.cells[cell][0] >= nbRows || style.cells[cell][1] >= nbCols) {
                delete style.cells[cell];
            }
        }
        var propertiesPos, cells;
        for (var obj in style) {
            for (cells in style[obj]) {
                propertiesPos = style[obj][cells].length - 1;
                for (var property in style[obj][cells][propertiesPos]) {
                    if (style[obj][cells][propertiesPos][property] === null) {
                        delete style[obj][cells][propertiesPos][property];
                    }
                }
            }
        }
        return style;
    }

    var CustomEditor = Handsontable.editors.TextEditor.prototype.extend();

    CustomEditor.prototype.init = function () {
        //Call the original createElements method
        Handsontable.editors.TextEditor.prototype.init.apply(this, arguments);
    };

    CustomEditor.prototype.open = function () {
        $(this.TEXTAREA).attr('id', 'editor1');
        var offset, width;
        var position;
        if (typeof (Wptm.style.cells[this.row + '!' + this.col]) !== 'undefined' && typeof (Wptm.style.cells[this.row + '!' + this.col][2].cell_type) !== 'undefined' && Wptm.style.cells[this.row + '!' + this.col][2].cell_type === 'html') {

            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'editor1');
            var init = tinymce.extend({}, tinyMCEPreInit.mceInit['editor1']);
            try {
                tinymce.init(init);
            } catch (e) {
            }

            //add tinymce to this
            tinyMCE.EditorManager.execCommand('mceAddEditor', true, 'editor1');
        } else {
            tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'editor1');
            //if (typeof (CKEDITOR.instances.editor1) !== 'undefined') {
            // CKEDITOR.instances.editor1.destroy();
            // }
        }
        Handsontable.editors.TextEditor.prototype.open.apply(this, arguments);

        // change width of popup when click html cell
        if ($('#tableContainer').find('.mce-tinymce.mce-container').length > 0) {
            setTimeout(function () {
                offset = $tableContainer.find('.htContainer').find('.isHtmlCell.current').offset();
                width  = $tableContainer.find('.htContainer').find('.isHtmlCell.current').width();
                position = {top: offset.top - $(window).scrollTop(), left: offset.left + width - $(window).scrollLeft(), position: 'fixed'};
                $tableContainer.find('.handsontableInputHolder').css(position).css('visibility', 'visible');
                $tableContainer.find('.mce-tinymce.mce-container').css('width', $tableContainer.width() / 3 + 'px').parents('.handsontableInputHolder').css(position).css('visibility', 'visible');
            }, 500);
        } else {
            $('#tableContainer').find('.handsontableInputHolder').css('position', 'absolute').css('visibility', 'visible');
        }
    };

    CustomEditor.prototype.getValue = function () {
        if (typeof (tinyMCE) !== 'undefined' && tinyMCE.EditorManager.get('editor1')) {
            return tinyMCE.EditorManager.get('editor1').getContent();
        } else {
            return Handsontable.editors.TextEditor.prototype.getValue.apply(this, arguments);
        }
    };

    CustomEditor.prototype.setValue = function (newValue) {
        if (typeof (tinyMCE) !== 'undefined' && tinyMCE.EditorManager.get('editor1')) {
            tinyMCE.EditorManager.get('editor1').setContent(newValue);
        } else {
            return Handsontable.editors.TextEditor.prototype.setValue.apply(this, arguments);
        }
    };

    CustomEditor.prototype.close = function () {

        if (checkObjPropertyNested(Wptm.style.cells[this.row + '!' + this.col], 2, 'cell_type') && Wptm.style.cells[this.row + '!' + this.col][2].cell_type === 'html') {
            // updateDimession();
        }
        $tableContainer.find('.handsontableInputHolder').css('visibility', 'hidden');
        return Handsontable.editors.TextEditor.prototype.close.apply(this, arguments);
    };

    //codemirror

    var myTextArea = document.getElementById("jform_css");
    var myCssEditor = CodeMirror.fromTextArea(myTextArea, {mode: "css", lineNumbers: true, theme: '3024-night'});
    var ww = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    var wh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    if (window.parent) {
        var ww = window.parent.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var wh = window.parent.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
    }

    myCssEditor.setSize(ww * 70 / 100, wh - 250);
    $('#customCssbtn').wptm_leanModal({
        top: 100, closeButton: '#cancelCssbtn', modalShow: function () {
            myCssEditor.refresh();
        }
    });

    $(myTextArea).on('change', function () {
        myCssEditor.setValue($(myTextArea).val().replace(/\\n/g, "\n"));
    });

    // myCssEditor.on("blur", function() {
    $("#saveCssbtn").click(function () {
        myCssEditor.save();
        $(myTextArea).trigger("propertychange");
        //close leanModal
        $("#lean_overlay").fadeOut(200);
        $("#wptm_customCSS").css({"display": "none"})
    });

    // click button toolTip
    $('#editToolTip').wptm_leanModal({
        top: 100, closeButton: '#cancelToolTipbtn', modalShow: function () {
        }
    });
    $("#saveToolTipbtn").click(function () {
        var ttEditor = tinyMCE.EditorManager.get('tooltip_content');
        ttEditor.save();
        $("#tooltip_content").trigger("change");
        //close leanModal
        $("#lean_overlay").fadeOut(200);
        $("#wptm_editToolTip").css({"display": "none"})
    })

    // click button select category own

    //Import Excel
    //Init call back when file is uploaded successful
    Dropzone.options.procExcel = {
        maxFiles: 1,
        //acceptedFiles: 'xls,xlsx',
        init: function () {
            //Update form action
            this.on("addedfile", function (file) {
                var dotPos = file.name.lastIndexOf('.') + 1;
                var ext = file.name.substr(dotPos, file.name.length - dotPos);

                if (ext !== 'xls' && ext !== 'xlsx') {
                    bootbox.alert(wptmText.CHOOSE_EXCEL_FIE_TYPE, wptmText.Ok);
                    this.options.autoProcessQueue = false;
                    this.removeFile(file);
                    //return false;
                }
                else {
                    if (this.options.autoProcessQueue === false) {
                        this.options.autoProcessQueue = true;
                    }
                }

            });

            this.on("sending", function (file, xhr, formData) {
                //Add table id to formData
                var tableId = $('li.wptmtable.active').data('id-table');
                $("#jform_id_table").val(tableId);
                formData.append('id_table', tableId);

                if ($("#jform_import_style").val()) {
                    formData.append('onlydata', 0);
                } else {
                    formData.append('onlydata', 1);
                }
                // Show the total progress bar when upload starts
                //this.options.uploadprogress(file);
                $(".progress").show();
                $(".progress-bar-success").css('width', 30 + '%');
                $(".progress-bar-success").css('opacity', 1);
                // And disable the start button
                //file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
            });

            this.on("success", function (file, responseText) {
                var tableId = $('li.wptmtable.active').data('id-table');
                $(".progress").fadeOut(1000);
                var responseObj = JSON.parse(responseText);
                if (responseObj.response === true) {
                    if (typeof responseObj.datas.too_large !== 'undefined') {

                        bootbox.confirm(responseObj.datas.msg, wptmText.Cancel, wptmText.Ok, function (result) {

                            if (result === true) {
                                var jsonVar = {
                                    id_table: responseObj.datas.id,
                                    onlydata: responseObj.datas.onlydata,
                                    file: encodeURI(responseObj.datas.file),
                                    ignoreCheck: 1
                                };
                                $.ajax({
                                    url: wptm_ajaxurl + "task=excel.import",
                                    type: 'POST',
                                    data: jsonVar,
                                    success: function (datas) {
                                        updatepreview(tableId, updateDimession);
                                    }
                                })
                            } else {
                                //do nothing
                            }
                        });

                    } else {
                        updatepreview(tableId, updateDimession);
                    }
                } else {
                    bootbox.alert(responseObj.response, wptmText.Ok);
                }

            });

            this.on('complete', function (file) {
                this.removeFile(file);
                setTimeout(function () {
                    $(".progress-bar-success").css('width', 0);
                }, 6000);
            });
            // Update the total progress bar
            this.on("uploadprogress", function (file, progress) {
                $(".progress-bar-success").css('width', progress + "%");
            });
        }
    };

    //Export-excel
    $('#export-excel').bind('click', function (e) {
        //e.preventDefault();
        var tableId = $('li.wptmtable.active').data('id-table');
        var format = $(this).data('format-excel');
        var url = wptm_ajaxurl + 'task=excel.export&id=' + tableId + '&format_excel=' + format;

        if ($("#jform_import_style").val()) {
            url = url + '&onlydata=0';
        } else {
            url = url + '&onlydata=1';
        }

        $.fileDownload(url, {
            failCallback: function (html, url) {
                bootbox.alert(html, wptmText.Ok);
            }
        });
    });

    tinyMCEPreInit.mceInit['editor1'] = tinyMCEPreInit.mceInit['wptmditor'];
    tinyMCEPreInit.mceInit['tooltip_content'] = tinyMCEPreInit.mceInit['wptm_tooltip'];
    tinyMCE.EditorManager.execCommand('mceRemoveEditor', true, 'wptmditor');
    $('#wp-wptmditor-wrap').hide();
});

(function ($, sr) {

    // debouncing function from John Hann
    // http://unscriptable.com/index.php/2009/03/20/debouncing-javascript-methods/
    var debounce = function (func, threshold, execAsap) {
        var timeout;

        return function debounced() {
            var obj = this, args = arguments;

            function delayed() {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            };

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100);
        };
    }
    // smartresize
    jQuery.fn[sr] = function (fn) {
        return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr);
    };

})(jQuery, 'smartresize');

function updateDimession() {

    var rows = [];
    var i = 0;
    for (var row in Wptm.style.rows) {
        var h = jQuery('#tableContainer .ht_master .htCore tr').eq(i + 1).height();
        rows[row] = h;
        i++;
    }

    jQuery(Wptm.container).handsontable('updateSettings', {rowHeights: rows});

    var ht = jQuery(Wptm.container).handsontable('getInstance');
    ht.runHooks('afterRowResize');
}
/**
 * Insert the current table into a content editor
 */
function insertTable() {
    var id = jQuery('#categorieslist li.wptmtable.active').data('id-table');
    var code = '<img src="' + wptm_dir + '/app/admin/assets/images/t.gif"' +
        'data-wptmtable="' + id + '"' +
        'style="background: url(' + wptm_dir + '/app/admin/assets/images/spreadsheet.png) no-repeat scroll center center #D6D6D6;' +
        'border: 2px dashed #888888;' +
        'height: 150px;' +
        'border-radius: 10px;' +
        'width: 99%;" />';
    window.parent.tinyMCE.execCommand('mceInsertContent', false, code);
    jQuery("#lean_overlay", window.parent.document).fadeOut(300);
    jQuery('#wptmmodal', window.parent.document).fadeOut(300);
    return false;

}

//From http://jquery-howto.blogspot.fr/2009/09/get-url-parameters-values-with-jquery.html
function getUrlVars() {
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function getUrlVar(v) {
    if (typeof (getUrlVars()[v]) !== "undefined") {
        return getUrlVars()[v];
    }
    return null;
}

function preg_replace(array_pattern, array_pattern_replace, my_string) {
    var new_string = String(my_string);
    for (var i = 0; i < array_pattern.length; i++) {
        var reg_exp = RegExp(array_pattern[i], "gi");
        var val_to_replace = array_pattern_replace[i];
        new_string = new_string.replace(reg_exp, val_to_replace);
    }
    return new_string;
}

//https://gist.github.com/ncr/399624
jQuery.fn.single_double_click = function (single_click_callback, double_click_callback, timeout) {
    return this.each(function () {
        var clicks = 0, self = this;
        jQuery(this).click(function (event) {
            clicks++;
            if (clicks == 1) {
                setTimeout(function () {
                    if (clicks == 1) {
                        single_click_callback.call(self, event);
                    } else {
                        double_click_callback.call(self, event);
                    }
                    clicks = 0;
                }, timeout || 300);
            }
        });
    });
}


function fillArray(array, val, val1, val2) {
    if (typeof (val2) === 'undefined') {
        if (typeof (array[val1]) !== 'undefined') {
            array[val1][1] = jQuery.extend(array[val1][1], val);
        } else {
            array[val1] = [val1, {}];
            array[val1][1] = val;
        }
    } else {
        if (typeof (array[val1 + "!" + val2]) !== 'undefined') {
            array[val1 + "!" + val2][2] = jQuery.extend(array[val1 + "!" + val2][2], val);
        } else {
            array[val1 + "!" + val2] = [val1, val2, {}];
            array[val1 + "!" + val2][2] = val;
        }
    }
    return array;
}

function toggleArray(array, val, val1, val2) {
    if (typeof (val2) === 'undefined') {
        if (typeof (array[val1]) !== 'undefined') {
            if (typeof (val) === 'object') {
                for (var key in val) {
                    if (typeof (array[val1][1][key] !== 'undefined')) {
                        array[val1][1][key] = !array[val1][1][key];
                    } else {
                        array[val1][1][key] = val[key];
                    }
                }
            } else {
                array[val1][1] = jQuery.extend(array[val1][1], val);
            }
        } else {
            array[val1] = [val1, {}];
            array[val1][1] = val;
        }
    } else {
        if (typeof (array[val1 + "!" + val2]) !== 'undefined') {
            if (typeof (val) === 'object') {
                for (var key in val) {
                    if (typeof (array[val1 + "!" + val2][2][key] !== 'undefined')) {
                        array[val1 + "!" + val2][2][key] = !array[val1 + "!" + val2][2][key];
                    } else {
                        array[val1 + "!" + val2][2][key] = val[key];
                    }
                }
            } else {
                array[val1 + "!" + val2][2] = jQuery.extend(array[val1 + "!" + val2][2], val);
            }
        } else {
            array[val1 + "!" + val2] = [val1, val2, {}];
            array[val1 + "!" + val2][2] = val;
        }
    }

    return array;
}

//Code from http://stackoverflow.com/questions/9905533/convert-excel-column-alphabet-e-g-aa-to-number-e-g-25
var convertAlpha = function (val) {
    var base = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', i, j, result = 0;

    for (i = 0, j = val.length - 1; i < val.length; i += 1, j -= 1) {
        result += Math.pow(base.length, j) * (base.indexOf(val[i]) + 1);
    }

    return result;
};

function strip_tags(input, allowed) {
    //  discuss at: http://phpjs.org/functions/strip_tags/
    // original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // improved by: Luke Godfrey
    // improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

    allowed = (((allowed || '') + '')
        .toLowerCase()
        .match(/<[a-z][a-z0-9]*>/g) || [])
        .join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
    return input.replace(commentsAndPhpTags, '')
        .replace(tags, function ($0, $1) {
            return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
        });
}

clone = function (v) {
    return JSON.parse(JSON.stringify(v));
}

/* Chart functions */
var DropChart = {};
DropChart.default = {
    "dataUsing": "row",
    "switchDataUsing": true,
    "useFirstRowAsLabels": true,
    "width": 500,
    "height": 375,
    "chart_align": "center",
    "scaleShowGridLines": false
};
DropChart.default.colors = "#DCDCDC,#97BBCD,#4C839E";
DropChart.default.pieColors = "#F7464A,#46BFBD,#FDB45C,#949FB1,#4D5360";
var doNotSave = false;

jQuery(document).ready(function ($) {
    Chart.defaults.global.multiTooltipTemplate = "<%= datasetLabel %>: <%= value %>";

    // add chart
    $('#newchart').click(function (e) {
        e.preventDefault();
        var selection = validateChartData();
        if (selection) {

            var id_table = $('#mycategories li.wptmtable.active').data('id-table');

            //create new chart & insert into db
            $.ajax({
                url: wptm_ajaxurl + "task=chart.add&id_table=" + id_table,
                type: "POST",
                dataType: "json",
                data: {datas: JSON.stringify(selection)},
                success: function (datas) {
                    if (datas.response === true) {
                        addChart(datas.datas);
                    } else {
                        bootbox.alert(datas.response, wptmText.Ok);
                    }
                },
                error: function (jqxhr, textStatus, error) {
                    bootbox.alert(textStatus + " : " + error, wptmText.Ok);
                }
            });


        } else {
            bootbox.alert(wptmText.CHART_INVALID_DATA, wptmText.GOT_IT);
        }
    });

    // action changer cell for chart
    $('#chartTabContent #changerChart').on('click', function () {
        var dataCell = validateChartData();
        var $mainTabContent = $('#pwrapper #mainTabContent');
        if (dataCell === false) {
            bootbox.alert(wptmText.CHART_INVALID_DATA, wptmText.GOT_IT);
        } else {
            var data = {};
            data.id     = $mainTabContent.find('.tab-pane.active').data('id-chart');
            data.title  = $mainTabContent.find('.tab-pane.active').data('title');
            data.type  = 'Line';
            data.config = $mainTabContent.find('.tab-pane.active').data('configs');
            data.datas = JSON.stringify(dataCell);
            data.changer = true;

            $('#mainTable').find('li.active[data-id-chart="' + data.id + '"]').remove();
            $mainTabContent.find('.tab-pane.active').remove();

            addChart(data);
        }
    });

    (initChartStyles = function () {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return false;
        }
        $('#rightcol2 .chart-styles a').click(function () {
            var id = $(this).data('id');

            //add active class
            $('#rightcol2 .chart-styles a').each(function (index, e) {
                $(e).removeClass("active");
            });
            $(this).addClass("active");

            $.ajax({
                url: wptm_ajaxurl + "view=charttype&format=json&id=" + id,
                type: 'POST'
            }).done(function (data) {

                if (typeof (data) === 'object') {

                    DropChart.type = data.name;
                    $.extend(DropChart.config, $.parseJSON(data.config));

                    //local save
                    $("#chart_" + DropChart.id).data("configs", DropChart.config);
                    var datas = $("#chart_" + DropChart.id).data("datas");
                    datas.type = DropChart.type;
                    $("#chart_" + DropChart.id).data("datas", datas);

                    populateChartConfig(DropChart.id);

                    //re - draw
                    DropChart.render();
                    //save change
                    DropChart.save();

                }
            });
            return false;
        });
    })();

    (initChartObserver = function () {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return false;
        }
        $('.observeChanges2').on('change click', function (e) {

            var chartConfig = $("#chart_" + DropChart.id).data("configs");

            switch ($(this).attr('name')) {
                case 'jform[dataUsing]':
                    chartConfig.dataUsing = $(this).val();
                    var dataSets = getDataSets(DropChart.cells, chartConfig.dataUsing);
                    DropChart.datasets = addChartStyles(dataSets[0], chartConfig.colors);  // dataSets[0];
                    if (chartConfig.useFirstRowAsLabels) {
                        DropChart.labels = dataSets[1];
                    } else {
                        DropChart.labels = DropChart.helper.getEmptyArray(dataSets[1].length);
                    }

                    break;
                case 'jform[useFirstRowAsLabels]':
                    chartConfig.useFirstRowAsLabels = ($(this).val() == "yes") ? true : false;
                    var dataSets = getDataSets(DropChart.cells, chartConfig.dataUsing);
                    DropChart.datasets = addChartStyles(dataSets[0], chartConfig.colors);  // dataSets[0];
                    if (chartConfig.useFirstRowAsLabels) {
                        DropChart.labels = dataSets[1];
                    } else {
                        DropChart.labels = DropChart.helper.getEmptyArray(dataSets[1].length);
                    }

                    break;
                case 'jform[chart_width]':
                    chartConfig.width = $(this).val();
                    break;
                case 'jform[chart_height]':
                    chartConfig.height = $(this).val();
                    break;

                case 'jform[chart_align]':
                    chartConfig.chart_align = $(this).val();
                    break;

                case 'jform[dataset_color]':
                    var index = parseInt($("#jform_dataset_select").val());
                    if (DropChart.type == "Line" || DropChart.type == "Bar" || DropChart.type == "Radar") {
                        var colors = chartConfig.colors.split(",");
                        if (colors.length > index) {
                            colors[index] = $(this).val();
                        }
                        chartConfig.colors = colors.join(",");
                        var dataSets = getDataSets(DropChart.cells, chartConfig.dataUsing);
                        DropChart.datasets = addChartStyles(dataSets[0], chartConfig.colors);
                    } else {
                        var pieColors = chartConfig.pieColors.split(",");
                        if (pieColors.length > index) {
                            pieColors[index] = $(this).val();
                        }
                        chartConfig.pieColors = pieColors.join(",");
                    }

                    break;
            }

            //local save
            $("#chart_" + DropChart.id).data("configs", chartConfig);
            DropChart.config = chartConfig;
            //re - draw
            DropChart.render();
            // save change
            DropChart.save();

        });

        $('.observeChanges3').on('change click', function (e) {

            var chartConfig = $("#chart_" + DropChart.id).data("configs");
            switch ($(this).attr('name')) {
                case 'jform[dataset_select]':
                    var index = parseInt($(this).val());
                    if (DropChart.type == "Line" || DropChart.type == "Bar" || DropChart.type == "Radar") {
                        if (chartConfig.colors.split(",").length > index) {
                            $('#jform_dataset_color').wpColorPicker('color', chartConfig.colors.split(",")[index]);
                        } else {
                            $('#jform_dataset_color').wpColorPicker('color', "");
                        }
                    } else {
                        if (chartConfig.pieColors.split(",").length > index) {
                            $('#jform_dataset_color').wpColorPicker('color', chartConfig.pieColors.split(",")[index]);
                        } else {
                            $('#jform_dataset_color').wpColorPicker('color', "");
                        }
                    }

                    break;
            }

        });

    })();

    DropChart.render = function () {
        var containerID = "chart_" + DropChart.id;
        var chartConfig = DropChart.config;

        //destroy old chart version
        if (DropChart.chart) {
            DropChart.chart.clear();
            DropChart.chart.destroy();
        }

        //re-create cavans
        var $chartContainer = $("#" + containerID);
        $chartContainer.find(".canvas").remove();
        $chartContainer.find(".chartContainer").append('<canvas class="canvas" width="' + chartConfig.width + '" height="' + chartConfig.height + '"   ><canvas>');
        var ctx = $chartContainer.find(".canvas").get(0).getContext("2d");
        var chartData = {};
        chartData.labels = DropChart.labels;
        chartData.datasets = DropChart.datasets;

        if (chartData.datasets.length == 0) {
            return false;
        }

        if (DropChart.datasets.length > 0) {
            var value_unit = Wptm.value_unit_chart[DropChart.id];
            var symbol_position = (!!Wptm.style.table.symbol_position) ? Wptm.style.table.symbol_position : $('#jform_symbol_position').val();
            var thousand_symbol = (!Wptm.style.table.thousand_symbol) ? $('input#jform_thousand_sym').val() : Wptm.style.table.thousand_symbol;
            var decimal_symbol = (!Wptm.style.table.decimal_symbol) ? $('input#jform_decimal_sym').val() : Wptm.style.table.decimal_symbol;
            var decimal_count = (!!Wptm.style.table.decimal_count) ? Wptm.style.table.decimal_count : $('#jform_decimal_count').val();
            var string = (parseInt(symbol_position) === 1)
                ? "(Number(value).toFixed(" + decimal_count + ")).toString().replace(/\\./g, '" + decimal_symbol + "').replace(/\\B(?=(\\d{3})+(?!\\d))/g, '" + thousand_symbol + "') + ' " + value_unit + "'"
                : "'" + value_unit + "' + (Number(value).toFixed(" + decimal_count + ")).toString().replace(/\\./g, '" + decimal_symbol + "').replace(/\\B(?=(\\d{3})+(?!\\d))/g, '" + thousand_symbol + "')";
            if (value_unit === '') {
                string = "Number(value)";
            }
            chartConfig.scaleLabel = "<%= " + string + "%>";
            chartConfig.tooltipTemplate = "<%if (label){%><%=label%>: <%}%><%= " + string + "%>";
            chartConfig.multiTooltipTemplate = "<%= datasetLabel %>: <%= " + string + "%>";

        }
        var selectedCellsLabels = $("#" + containerID + " .selectedCells").val();

        $('#rightcol2').find(".cellRangeLabel").val(selectedCellsLabels);

        switch (DropChart.type) {
            case 'PolarArea':
                DropChart.chart = new Chart(ctx).PolarArea(convertForPie(chartData, chartConfig.pieColors), chartConfig);
                break;

            case 'Pie':
                DropChart.chart = new Chart(ctx).Pie(convertForPie(chartData, chartConfig.pieColors), chartConfig);
                break;

            case 'Doughnut':
                DropChart.chart = new Chart(ctx).Doughnut(convertForPie(chartData, chartConfig.pieColors), chartConfig);
                break;

            case 'Bar':
                DropChart.chart = new Chart(ctx).Bar(chartData, chartConfig);
                break;

            case 'Radar':
                DropChart.chart = new Chart(ctx).Radar(chartData, chartConfig);
                break;

            case 'Line':
            default:
                DropChart.chart = new Chart(ctx).Line(chartData, chartConfig);
                break;
        }
    };

    DropChart.save = function () {
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return;
        }
        if (doNotSave) {
            return;
        }
        var $ = jQuery;
        var jsonVar = {
            jform: {
                type: DropChart.type,
                config: JSON.stringify(DropChart.config)
            },
            id: DropChart.id
        };

        if (DropChart.changer === true) {
            jsonVar.jform.datas = JSON.stringify(DropChart.cells);
        }
        //jsonVar[Wptm.token] = "1";

        $.ajax({
            url: wptm_ajaxurl + "task=chart.save",
            dataType: "json",
            type: "POST",
            data: jsonVar,
            success: function (datas) {

                if (datas.response === true) {
                    autosaveNotification = setTimeout(function () {
                        $('#savedInfo').fadeIn(200).delay(2000).fadeOut(1000);
                    }, 1000);
                } else {
                    bootbox.alert(datas.response, wptmText.Ok);
                }
            },
            error: function (jqxhr, textStatus, error) {
                bootbox.alert(textStatus + " : " + error, wptmText.Ok);
            }
        });
    }
});


/**
 * Insert the current table into a content editor
 */
function insertChart() {
    var table_id = jQuery('#categorieslist li.wptmtable.active').data('id-table');
    var chart_id = jQuery('ul#mainTable li.active').data('id-chart');
    var dir = decodeURIComponent(getUrlVar('path'));
    code = '<img src="' + wptm_dir + '/app/admin/assets/images/t.gif"' +
        ' data-wptmtable="' + table_id + '"' +
        ' data-wptm-chart="' + chart_id + '"' +
        'style="background: url(' + wptm_dir + '/app/admin/assets/images/chart.png) no-repeat scroll center center #D6D6D6;' +
        'border: 2px dashed #888888;' +
        'height: 150px;' +
        'border-radius: 10px;' +
        'width: 99%;" />';


    window.parent.tinyMCE.execCommand('mceInsertContent', false, code);
    jQuery("#lean_overlay", window.parent.document).fadeOut(300);
    jQuery('#wptmmodal', window.parent.document).fadeOut(300);

}

//switch config pane between table and chart
function switchConfigPane(e) {
    var $ = jQuery;
    if (($(e).attr("href") != "#dataTable") && ($(e).attr("href").indexOf("dataSource") == -1)) { //chart tab
        $(Wptm.container).handsontable('deselectCell');
        $("#rightcol").hide();
        $("#newchart").hide();
        $("#rightcol2").show();
        DropChart.id = $(e).parent().data('id-chart');
        doNotSave = true;
        populateChartConfig(DropChart.id);
        doNotSave = false;
    } else if ($(e).attr("href").indexOf("dataSource") != -1) { //data source tab
        $(Wptm.container).handsontable('deselectCell');
        $("#rightcol").hide();
        $("#newchart").hide();
        $("#rightcol2").hide();
    }
    else { //table
        $(window).trigger('resize');
        $("#rightcol2").hide();
        $("#rightcol").show();
        $("#newchart").show();
    }

}

//populate chart config data for current (active) tab
function populateChartConfig(chart_id) {
    var $ = jQuery;
    var chartConfig = $("#chart_" + chart_id).data("configs");

    if (chartConfig.dataUsing !== undefined) {
        $("#jform_dataUsing").val(chartConfig.dataUsing);
    }

    if (chartConfig.switchDataUsing === undefined || chartConfig.switchDataUsing == false) {
        $("#jform_dataUsing").prop('disabled', true);
    } else {
        $("#jform_dataUsing").prop('disabled', false);
    }
    $('#jform_dataUsing').trigger('liszt:updated');


    if (chartConfig.useFirstRowAsLabels !== undefined && chartConfig.useFirstRowAsLabels == true) {
        $("#jform_useFirstRowAsLabels").val("yes");
    } else {
        $("#jform_useFirstRowAsLabels").val("no");
    }
    $('#jform_useFirstRowAsLabels').trigger('liszt:updated');

    $("#jform_chart_width").val(chartConfig.width);
    $("#jform_chart_height").val(chartConfig.height);

    if (chartConfig.chart_align !== undefined) {
        $("#jform_chart_align").val(chartConfig.chart_align);
    }
    $('#jform_chart_align').trigger('liszt:updated');

    var data = $("#chart_" + DropChart.id).data("datas");
    var cells = $.parseJSON(data.datas);

    $("#rightcol2 .chart-styles li a").each(function (index, e) {
        if ($(e).attr("title") == data.type) {
            $(e).addClass("active");
        } else {
            $(e).removeClass("active");
        }
    });

    DropChart.cells = cells;
    DropChart.type = data.type;
    var dataSets = getDataSets(DropChart.cells, chartConfig.dataUsing);
    if (dataSets.length < 3) {
        return false;
    }
    DropChart.datasets = addChartStyles(dataSets[0], chartConfig.colors);

    if (chartConfig.useFirstRowAsLabels) {
        DropChart.labels = dataSets[1];
    } else {
        DropChart.labels = DropChart.helper.getEmptyArray(dataSets[1].length);
    }

    // dataset_select
    $("#jform_dataset_select").html("");
    if (DropChart.type == "Line" || DropChart.type == "Bar" || DropChart.type == "Radar") {
        for (var i = 0; i < DropChart.datasets.length; i++) {
            $("#jform_dataset_select").append('<option value="' + i + '">' + DropChart.datasets[i].label + '</option>');
        }
        $('#jform_dataset_select').trigger('liszt:updated');
        $('#jform_dataset_color').wpColorPicker('color', chartConfig.colors.split(",")[0]);
    } else {
        var chartData = {};
        chartData.datasets = DropChart.datasets;
        chartData.labels = dataSets[1];
        var pieDatas = convertForPie(chartData, chartConfig.pieColors);

        for (var i = 0; i < pieDatas.length; i++) {
            $("#jform_dataset_select").append('<option value="' + i + '">' + pieDatas[i].label + '</option>');
        }
        $('#jform_dataset_select').trigger('liszt:updated');
        $('#jform_dataset_color').wpColorPicker('color', pieDatas[0].color);
    }

    DropChart.config = chartConfig;
}

//active some function for elements in tab content
function activeTabs() {

    var $ = jQuery;
    $('#mainTable a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        switchConfigPane(e.target);
    });

    $('#mainTabContent a.edit').unbind().click(function (e) {
        e.stopPropagation();
        if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
            return false;
        }
        var link = $(this).parent().find('span.chartTitle');
        var oldTitle = link.text();
        $(link).attr('contentEditable', true);
        $(link).addClass('editable');
        $(link).selectText();

        $('#mainTabContent span.editable').bind('click.mm', hstop);  //let's click on the editable object
        $(link).bind('keypress.mm', hpress); //let's press enter to validate new title'
        $('*').not($(link)).bind('click.mm', houtside);

        function unbindall() {
            $('#mainTabContent span.editable').unbind('click.mm', hstop);  //let's click on the editable object
            $(link).unbind('keypress.mm', hpress); //let's press enter to validate new title'
            $('*').not($(link)).unbind('click.mm', houtside);
        }

        //Validation
        function hstop(event) {
            event.stopPropagation();
            return false;
        }

        //Press enter
        function hpress(e) {
            if (e.which == 13) {
                e.preventDefault();
                unbindall();
                updateTitle($(link).text());
                $(link).removeAttr('contentEditable');
                $(link).removeClass('editable');
            }
        }

        //click outside
        function houtside(e) {
            unbindall();
            updateTitle($(link).text());
            $(link).removeAttr('contentEditable');
            $(link).removeClass('editable');
        }


        function updateTitle(title) {
            if (!(Wptm.can.edit || (Wptm.can.editown && data.author === Wptm.author))) {
                return false;
            }

            if (title.trim() !== '') {
                var id = $(link).parents('div.tab-pane.active').data('id-chart');
                var url = wptm_ajaxurl + "task=chart.setTitle&id=" + id + '&title=' + title;

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "json",
                    success: function (datas) {
                        if (datas.response === true) {
                            $("#tabChart" + id).text(title);
                        } else {
                            $(link).text(oldTitle);
                            bootbox.alert(datas.response, wptmText.Ok);
                        }
                    },
                    error: function (jqxhr, textStatus, error) {
                        $(link).text(oldTitle);
                        bootbox.alert(textStatus, wptmText.Ok);
                    }
                });
            } else {
                $(link).text(oldTitle);
                return false;
            }
            $(link).parent().css('white-space', 'normal');
            setTimeout(function () {
                $(link).parent().css('white-space', '');
            }, 200);

        }
    });

    $('#mainTabContent  a.trash').unbind('click').click(function (e) {
        var that = this;
        bootbox.confirm(wptmText.JS_WANT_DELETE + "\"" + $(this).parent().find('.chartTitle').text().trim() + '"?', wptmText.Cancel, wptmText.Ok, function (result) {
            if (result === true) {

                var id = $(that).parents('div.tab-pane.active').data('id-chart');
                $.ajax({
                    url: wptm_ajaxurl + "task=chart.delete&id=" + id,
                    type: "POST",
                    dataType: "json",
                    success: function (datas) {
                        if (datas.response === true) {
                            $(that).parent().remove();
                            $("#mainTable").find("li.active").remove();
                            // back to the table tab
                            $('#mainTable').find('li a:first').tab('show');
                        } else {
                            bootbox.alert(datas.response, wptmText.Ok);
                        }
                    },
                    error: function (jqxhr, textStatus, error) {
                        bootbox.alert(textStatus, wptmText.Ok);
                    }
                });
                return false;
            }
        });
    });

}


function loadTableContructor() {
    var $ = jQuery;
    var id_table = $('li.wptmtable.active').data('id-table');
    var table_type = $('li.wptmtable.active').data('table-type');
    var $mainTable = $("#mainTable");
    $mainTable.find(".tabDataSource").hide();
    $mainTable.find(".groupTable" + id_table).show();
    if (table_type == 'mysql') {
        if ($("#tabDataSource_" + id_table).length == 0) {
            var firstTab = $mainTable.find('li').get(0);
            $(firstTab).after('<li><a data-toggle="tab" id="tabDataSource_' + id_table + '" class="tabDataSource groupTable' + id_table + '" href="#dataSource_' + id_table + '">Data Source</a></li>');
            $('#mainTabContent.tab-content').append('<div class="db_table tab-pane" id="dataSource_' + id_table + '">' +
                '<div class="dataSourceContainer" style="padding-top:10px" ></div></div>');

            $.ajax({
                url: wptm_ajaxurl + "view=dbtable&id_table=" + id_table,
                type: "GET"
            }).done(function (data) {
                $("#dataSource_" + id_table).html(data);
            });
        }
    }
    //do nothing
}

function loadCharts() {

    var $ = jQuery;
    var id_table = $('li.wptmtable.active').data('id-table');
    //generate shortcode
    $("#shortcode_table").val('[wptm id=' + id_table + ']');
    $("#shortcode_charts").html("");

    var $mainTable = $("#mainTable");
    $mainTable.find(".graphTab").hide();
    $mainTable.find(".groupTable" + id_table).show();
    url = wptm_ajaxurl + "view=charts&format=json&id_table=" + id_table;
    $.ajax({
        url: url,
        type: "POST",
        dataType: "json"
    }).done(function (data) {
        //generate shortcode
        var shortcode_charts = "";
        for (var i = 0; i < data.length; i++) {
            shortcode_charts += '<br/>' + '<input readonly="readonly" value="[wptm id-chart=' + data[i].id + ']" type="text">';
            initChart(data[i]);
        }
        if (shortcode_charts != "") {
            $("#shortcode_charts").html('Charts ' + shortcode_charts);
        }

        activeTabs();
        if (typeof(Wptm.chart_id) !== 'undefined') {
            $('a#tabChart' + Wptm.chart_id).tab('show');
            Wptm.chart_id = 0;
        }
        //}
    });


}

function initChart(data) {
    initTabChart(data);

    drawChart(data);
}

function initTabChart(data) {

    var $ = jQuery;
    var btnAdd = $('#newchart').get(0);
    var id_table = $('li.wptmtable.active').data('id-table');
    var chartConfig;
    try{
        chartConfig = $.extend({}, DropChart.default, $.parseJSON(data.config));
    } catch (e) {
        chartConfig = $.extend({}, DropChart.default, data.config);
    }
    var cells = $.parseJSON(data.datas);
    var firstCell = cells[0][0];
    var lastRow = cells[cells.length - 1];
    var lastCell = lastRow[lastRow.length - 1];
    var cellsData = $(Wptm.container).handsontable('getData', firstCell.split(":")[0], firstCell.split(":")[1], lastCell.split(":")[0], lastCell.split(":")[1]);
    var canSwitch = DropChart.helper.canSwitchRowCol(cellsData);
    if (canSwitch == 3) { //both row and col data valid
        chartConfig.switchDataUsing = true;
    } else {
        chartConfig.switchDataUsing = false;
        if (canSwitch == 2) {  //only row data valid
            chartConfig.dataUsing = "row";
        } else { //only row data valid
            chartConfig.dataUsing = "column";
        }
    }

    if ($("#tabChart" + data.id).length === 0) {
        $('#mainTable').append('<li data-id-chart="' + data.id + '"><a data-toggle="tab" id="tabChart' + data.id + '" class="graphTab groupTable' + id_table + '" href="#chart_' + data.id + '"><i class="dashicons dashicons-chart-area"></i> ' + data.title + '</a></li>');
        $('#mainTabContent.tab-content').append('<div class="tab-pane" data-id-chart="' + data.id + '" id="chart_' + data.id + '" data-title="' + data.title + '">'
            + '<div style="padding-top:10px" ><div class="span8"><span class="chartTitle">' + data.title + '</span>'
            + '<a class="edit"><i class="icon-edit"></i></a>'
            + '<a class="trash"><i class="icon-trash"></i></a></div>'
            // + '<div class="span4" style="text-align:center"><label>Data - Selected Range</label>'
            + '<input type="hidden" name="selectedCells" readOnly="true" class="observeChanges2 input-mini selectedCells" size="8" value="" /></div>'
            // + '</div>'
            + '<div class="chartContainer"><canvas class="canvas" height="' + chartConfig.height + '" width="' + chartConfig.width + '"></canvas></div></div>');
    }

    $("#chart_" + data.id).data("configs", chartConfig);
    $("#chart_" + data.id).data("datas", data);
}

function addChart(data) {
    if (data.changer === true) {
        DropChart.changer = data.changer;
    } else {
        DropChart.changer = false;
    }

    var $ = jQuery;
    //init tab
    initTabChart(data);

    //draw chart
    var check = drawChart(data);
    if (!check) { // Switch to column
        data.config = '{ "dataUsing": "column" }';
        var chartConfig = $.extend({}, DropChart.default, $.parseJSON(data.config));
        $("#chart_" + data.id).data("configs", chartConfig);
        drawChart(data);
    }

    activeTabs();
    // make the new tab active
    $('#mainTable a.graphTab:last').tab('show');
    //save change
    DropChart.id = data.id;
    //save change
    DropChart.save();
    // discard chart change
    data.changer = false;
}

//Draw new chart
function drawChart(data) {
    var $ = jQuery;
    var containerID = "chart_" + data.id;
    var cells = $.parseJSON(data.datas);
    if ($.isArray(cells) === false || cells.length === 0)
        return;

    DropChart.cells = cells;
    var selectedCellsLabels = DropChart.helper.getCellRangeLabel(cells);
    $("#" + containerID + " .selectedCells").val(selectedCellsLabels);
    var chartConfig;
    try{
        chartConfig = $.extend({}, DropChart.default, $.parseJSON(data.config));
    } catch (e) {
        chartConfig = $.extend({}, DropChart.default, data.config);
    }

    DropChart.config = chartConfig;
    var chartData = {};
    var dataSets = getDataSets(cells, DropChart.config.dataUsing);

    if (dataSets.length == 0) {
        return false;
    }

    chartData.datasets = addChartStyles(dataSets[0], chartConfig.colors);
    if (chartData.datasets.length === 0) {
        return false;
    }

    if (DropChart.config.useFirstRowAsLabels) {
        chartData.labels = dataSets[1];
    } else {
        chartData.labels = DropChart.helper.getEmptyArray(dataSets[1].length);
    }

    // Get the context of the canvas element we want to select
    // Get context with jQuery - using jQuery's .get() method.
    var ctx = $("#" + containerID + " .canvas").get(0).getContext("2d");
    DropChart.labels = chartData.labels;
    DropChart.datasets = chartData.datasets;

    if (DropChart.datasets.length > 0) {
        var cellsData = DropChart.helper.getRangeData(cells);
        var value_unit = '';
        for (var i = 0; i < cellsData.length; i++) {
            for (var j = 0; j < cellsData[i].length; j++) {
                var unit = string_replace(cellsData[i][j], true);
                if (typeof cellsData[i][j] !== 'undefined' && typeof cellsData[i][j] !== 'string' && cellsData[i][j] !== null) {
                    cellsData[i][j] = cellsData[i][j].toString();
                } else if (cellsData[i][j] === null) {
                    cellsData[i][j] = '0';
                }
                if (
                    typeof cellsData[i][j] !== 'undefined'
                    && unit === Wptm.style.table.currency_symbol
                    && cellsData[i][j] !== null
                    && !isNaN(parseInt(cellsData[i][j].replace(unit, '')))
                ) {
                    value_unit = Wptm.style.table.currency_symbol;
                }
            }
        }
        var symbol_position = (!!Wptm.style.table.symbol_position) ? Wptm.style.table.symbol_position : $('#jform_symbol_position').val();
        var thousand_symbol = (!Wptm.style.table.thousand_symbol) ? $('#jform_thousand_sym').val() : Wptm.style.table.thousand_symbol;
        var decimal_symbol = (!Wptm.style.table.decimal_symbol) ? $('#jform_decimal_sym').val() : Wptm.style.table.decimal_symbol;
        var decimal_count = (!Wptm.style.table.decimal_count) ? $('#jform_decimal_count').val() : Wptm.style.table.decimal_count;
        var string = (parseInt(symbol_position) === 1)
            ? "(Number(value).toFixed(" + decimal_count + ")).toString().replace(/\\./g, '" + decimal_symbol + "').replace(/\\B(?=(\\d{3})+(?!\\d))/g, '" + thousand_symbol + "') + ' " + value_unit + "'"
            : "'" + value_unit + "' + (Number(value).toFixed(" + decimal_count + ")).toString().replace(/\\./g, '" + decimal_symbol + "').replace(/\\B(?=(\\d{3})+(?!\\d))/g, '" + thousand_symbol + "')";
        if (value_unit === '') {
            string = "Number(value)";
        }
        DropChart.config.scaleLabel = "<%= " + string + "%>";
        chartConfig.tooltipTemplate = "<%if (label){%><%=label%>: <%}%><%= " + string + "%>";
        chartConfig.multiTooltipTemplate = "<%= datasetLabel %>: <%= " + string + "%>";
        DropChart.config.tooltipTemplate = "<%if (label){%><%=label%>: <%}%><%= " + string + "%>";
        DropChart.config.multiTooltipTemplate = "<%= datasetLabel %>: <%= " + string + "%>";
    }
    Wptm.value_unit_chart[data.id] = value_unit;

    DropChart.type = data.type;

    switch (DropChart.type) {
        case 'PolarArea':
            DropChart.chart = new Chart(ctx).PolarArea(convertForPie(chartData, DropChart.config.pieColors), DropChart.config);
            break;

        case 'Pie':
            DropChart.chart = new Chart(ctx).Pie(convertForPie(chartData, DropChart.config.pieColors), DropChart.config);
            break;

        case 'Doughnut':
            DropChart.chart = new Chart(ctx).Doughnut(convertForPie(chartData, DropChart.config.pieColors), DropChart.config);
            break;

        case 'Bar':
            DropChart.chart = new Chart(ctx).Bar(chartData, DropChart.config);
            break;

        case 'Radar':
            DropChart.chart = new Chart(ctx).Radar(chartData, DropChart.config);
            break;

        case 'Line':
        default:
            DropChart.chart = new Chart(ctx).Line(chartData, DropChart.config);
            break;
    }


}

function addChartStyles(dataSets, colors) {

    var result = [];
    var dataset, styleSet;
    for (var i = 0; i < dataSets.length; i++) {
        dataset = dataSets[i];
        styleSet = getStyleSet(i, colors);
        jQuery.extend(dataset, styleSet);
        result.push(dataset);
    }

    return result;
}

// check column is int
function replaceCell(cellsData, currency_symbol) {
    var data1 = [];
    var i = 0;
    var data2 = -1;
    var v1 = '';
    currency_symbol = new RegExp('[0-9|\.|\,|\\-|' + currency_symbol + ']', "g");

    $.each(cellsData, function (k, v) {
        v = v.toString();
        v1 = v.replace(currency_symbol, '');
        if (v1 === '') {
            data1[i] = k;
            i++;
        } else if (v !== '') {
            data2 = k;
        }
    });
    var data = [];
    data[1] = '';
    data[0] = data1;
    if (data2 !== -1) {
        data[1] = data2;
    }
    return data;
}

function getDataSets(cells, dataUsing) {
    var currency_symbol = typeof Wptm.style.table.currency_symbol === 'undefined'
        ? default_value.currency_symbol
        : Wptm.style.table.currency_symbol;
    var datasets = [];
    var axisLabels = [];
    var grapLabels = [];
    var dataset = {};

    if (!dataUsing) {
        dataUsing = "row";
    }
    var cellsData = DropChart.helper.getRangeData(cells);

    if (cellsData.length === 0) {
        return false;
    }

    if (dataUsing === "row") {
        if (DropChart.config.useFirstRowAsLabels) {
            axisLabels = cellsData[0];
        }
        //get valid chart data area
        var result = getValidChartData(cellsData);
        // axis X label
        if (DropChart.config.useFirstRowAsLabels) {
            var tempArr = [];
            for (var j=0; j < result[1].length; j++) {
                tempArr.push(axisLabels[result[1][j]]);
            }
            axisLabels = tempArr;
        } else {
            axisLabels = DropChart.helper.getEmptyArray(result[1].length);
        }
        // dataset label
        for (var j=0; j < result[2].length; j++) {
            grapLabels.push(cellsData[result[2][j]][0]);
        }
    } else {
        var rCellsData = DropChart.helper.transposeArr(cellsData);
        if (DropChart.config.useFirstRowAsLabels) {
            axisLabels = rCellsData[0];
        }
        //get valid chart data area
        var result = getValidChartData(rCellsData);

        // axis X label
        if (DropChart.config.useFirstRowAsLabels) {
            var tempArr = [];
            for (var j=0; j < result[1].length; j++) {
                tempArr.push(axisLabels[result[1][j]]);
            }
            axisLabels = tempArr;
        } else {
            axisLabels = DropChart.helper.getEmptyArray(result[1].length);
        }
        // dataset label
        for (var j=0; j < result[2].length; j++) {
            grapLabels.push(rCellsData[result[2][j]][0]);
        }
    }

    for (var i = 0; i < result[0].length; i++) {
        dataset = {};
        dataset.data = DropChart.helper.convertToNumber(result[0][i]);
        dataset.label = grapLabels[i];
        datasets.push(dataset);
    }

    return [datasets, axisLabels, grapLabels];
}

//get valid chart data area
// return: valid data , col indexes, row indexes
function getValidChartData (cellsData) {
    var i, tempIndexes;
    var results = [];
    var resultIndexes = [];
    var rowIndexes = [];
    for (i = 0; i < cellsData[0].length; i++) {
        resultIndexes.push(i);
    }

    for(i = 0; i < cellsData.length; i++) {
        if (DropChart.helper.isValidRow(cellsData[i])) {
            results.push(cellsData[i]);
            rowIndexes.push(i);
            tempIndexes = DropChart.helper.getValidIndexes(cellsData[i]);
            resultIndexes = DropChart.helper.intersection(tempIndexes, resultIndexes);
        }
    }
    var tempArr = [];

    for(i = 0; i < results.length; i++) {
        tempArr = [];
        for (var j=0; j < tempIndexes.length; j++) {
            tempArr.push(results[i][tempIndexes[j]]);
        }
        results[i] = tempArr;
    }

    return [results, resultIndexes, rowIndexes]
}

function getCellData(cellPos) {

    var pos = cellPos.split(":");
    var value = jQuery("#tableContainer").handsontable('getDataAtCell', parseInt(pos[0]), parseInt(pos[1]));

    return value;
}


//We need at least 1 row or 1 column is numberic
function validateChartData() {

    var rValid = true;
    var cValid = true;
    var $ = jQuery;

    var selection = $(Wptm.container).handsontable('getSelected');

    // get selector when change range label
    if (typeof selection === 'undefined' || selection === null) {
        var rangeLabel = $('#jform_dataSelected-lbl .cellRangeLabel').val();
        rangeLabel = rangeLabel.replace(/[ ]+/g, "").toUpperCase();
        var arrayRange = rangeLabel.split(":");
        if (arrayRange.length > 1) {
            var selection = [];
            selection.push(parseInt(arrayRange[0].split(/[ |A-Za-z]+/g)[1]) - 1);
            selection.push(arrayRange[0].charCodeAt(0) - 65);
            selection.push(parseInt(arrayRange[1].split(/[ |A-Za-z]+/g)[1]) - 1);
            selection.push(arrayRange[1].charCodeAt(0) - 65);
        }
    }

    if (typeof selection == "undefined" || selection.length < 2) {
        return false;
    }
    var iMin = selection[0] < selection[2] ? selection[0] : selection[2];
    var iMax = selection[0] > selection[2] ? selection[0] : selection[2];
    var jMin = selection[1] < selection[3] ? selection[1] : selection[3];
    var jMax = selection[1] > selection[3] ? selection[1] : selection[3];

    //no cell selected or only one cell
    if (selection.length == 0 || (iMin == iMax && jMin == jMax)) {
        return false;
    }

    var cellRange = new Array();
    var Cells = $(Wptm.container).handsontable('getData', iMin, jMin, iMax, jMax);
    //Check row
    rValid = DropChart.helper.hasNumbericRow(Cells);
    var rCells;
    if (!rValid) {
        //check column
        rCells = DropChart.helper.transposeArr(Cells);
        cValid = DropChart.helper.hasNumbericRow(rCells);
        if (!cValid) { //ignore first row and column
            cValid = DropChart.helper.hasNumbericRowCol(rCells[0]);
            if (!cValid) {
                cValid = DropChart.helper.hasNumbericRowCol(rCells[1]);
            }
            var subCells = DropChart.helper.removeFirstRowColumn(rCells);
            if (subCells.length <= 0) return false;
        }
    }

    if (rValid || cValid) {
        //read data
        for (var r = 0; r < Cells.length; r++) {
            cellRange[r] = new Array();
            for (var c = 0; c < Cells[r].length; c++) {
                cellRange[r][c] = (iMin + r) + ":" + (jMin + c);
            }
        }
        return cellRange;
    } else {
        return false;
    }
}

function validateCharts(change) {

    var result = true;
    var $ = jQuery;
    var id_table = $('li.wptmtable.active').data('id-table');
    var editCell = change[0] + ":" + change[1];

    $('ul#mainTable a.groupTable' + id_table).each(function (index, e) {
        chart_id = $(this).parent().data('id-chart');

        if (chart_id) {
            var data = $("#chart_" + chart_id).data("datas");
            var cells = $.parseJSON(data.datas);
            if (DropChart.helper.inArrays(editCell, cells)) {
                var cellsData = [];
                for (var i = 0; i < cells.length; i++) {
                    var rowData = [];
                    for (var j = 0; j < cells[i].length; j++) {
                        if (cells[i][j] != editCell) {
                            rowData[j] = getCellData(cells[i][j]);
                        } else {
                            rowData[j] = change[3];//new value
                        }
                    }
                    cellsData[i] = rowData;
                }

                if (!validateDataForChart(cellsData)) {
                    result = false;
                }
            }
        }
    });

    return result;
}

function validateDataForChart(Cells) {
    //Check row
    rValid = DropChart.helper.hasNumbericRow(Cells);
    if (!rValid) {
        //check column
        rCells = DropChart.helper.transposeArr(Cells);
        cValid = DropChart.helper.hasNumbericRow(rCells);
        if (!cValid) { //ignore first row and column

            subCells = DropChart.helper.removeFirstRowColumn(rCells);
            if (subCells.length <= 0) return false;

            rValid = DropChart.helper.hasNumbericRow(subCells);
            if (!rValid) {
                rsubCells = DropChart.helper.transposeArr(subCells);
                cValid = DropChart.helper.hasNumbericRow(rsubCells);
            }
        }
    }

    return (rValid || cValid);
}
function convertForPie(lineChartData, colors) {

    if (lineChartData.datasets.length == 0) {
        return false;
    }
    var datas = [];
    var dataset = lineChartData.datasets[0].data;


    for (var i = 0; i < dataset.length; i++) {
        var data = {};
        data.value = Number(dataset[i]);
        data.label = lineChartData.labels[i];
        data.color = getColor(i, colors);
        data.highlight = DropChart.helper.ColorLuminance(data.color, 0.3);
        datas[i] = data;
    }

    return datas;
}

function getColor(i, colors) {
    var result = "";
    var arrColors = colors.split(",");
    var len = arrColors.length;
    if (len > 0) {
        result = arrColors[i % len];
    }

    return result;
}

function getStyleSet(i, colors) {
    var styleSet = {};

    var color = getColor(i, colors);
    if (color != "") {
        styleSet.fillColor = DropChart.helper.convertHex(color, 20);
        styleSet.strokeColor = DropChart.helper.convertHex(color, 50);
        styleSet.pointColor = DropChart.helper.convertHex(color, 100);
        styleSet.pointColor = "#fff";
        styleSet.pointHighlightFill = "#fff";
        styleSet.pointColor = DropChart.helper.convertHex(color, 100);
    }

    return styleSet;
}


DropChart.helper = {}

//get index of valid number in the array
DropChart.helper.getValidIndexes = function (arr) {
    var currency_symbol = typeof Wptm.style.table.currency_symbol === 'undefined'
        ? default_value.currency_symbol
        : Wptm.style.table.currency_symbol;
    var i, v, x1;
    var result = [];
    for(i = 0; i < arr.length; i++) {

        v = arr[i]? arr[i].toString() : "";
        x1 = v.replace(currency_symbol, '');
        x1 = x1.replace(/[\\.|+|,| ]/g, '');
        x1 = x1.replace(/-/g, '');
        x1 = x1.replace(/[0-9]/g, '');
        if (x1 === '') {
            result.push(i);
        }
    }
    return result;
}

//get intersection values of two array
DropChart.helper.intersection = function (a, b) {
    var rs = [];
    for (var i=0; i < a.length; i++) {
        if (b.indexOf(a[i])!=-1) {
            rs.push(a[i]);
        }
    }
    return rs;
};


DropChart.helper.isNumbericArray = function (arr) {

    var valid = true;
    for (var c = 0; c < arr.length; c++) {
        if (isNaN(arr[c])) {
            valid = false;
        }
    }

    return valid;
};

DropChart.helper.convertToNumber = function (arr) {
    var result = [];
    for (var c = 0; c < arr.length; c++) {
        // if (!isNaN(arr[c])) {
        if (typeof arr[c] === 'string') {
            arr[c] = string_replace(arr[c], false);
        }
        result.push(arr[c]);
        // }
    }
    return result;
};

DropChart.helper.transposeArr = function (arr) {
    if (typeof arr === "undefined" || arr.length === 0) {
        return [];
    }
    return Object.keys(arr[0]).map(function (c) {
        return arr.map(function (r) {
            return r[c];
        });
    });
}
DropChart.helper.inArrays = function (c, cells) {
    var result = false;
    for (var r = 0; r < cells.length; r++) {
        if (cells[r].indexOf(c) > -1) {
            result = true;
        }
    }

    return result;
}


// there is at least 2 number
DropChart.helper.isValidRow = function (arr) {
    var currency_symbol = typeof Wptm.style.table.currency_symbol === 'undefined'
        ? default_value.currency_symbol
        : Wptm.style.table.currency_symbol;
    var i, v, x1, count = 0;
    for(i = 0; i < arr.length; i++) {
        v = arr[i]? arr[i].toString() : "";
        x1 = v.replace(currency_symbol, '');
        x1 = x1.replace(/[\\.|+|,| ]/g, '');
        x1 = x1.replace(/-/g, '');
        x1 = x1.replace(/[0-9]/g, '');
        if (x1 === '') {
            count++;
        }
    }
    return (count > 1);
}

DropChart.helper.hasNumbericRow = function (Cells) {
    var rValid = false;
    if (typeof Cells === "undefined") {
        return false;
    }
    for (var r = 0; r < Cells.length; r++) {
        if (DropChart.helper.isValidRow(Cells[r])) {
            rValid = true;
            break;
        }
    }
    return rValid;
}

// check val int cel in row
DropChart.helper.hasNumbericRowCol = function (Cells) {
    var rValid = true;
    var rNaN = 0;
    if (typeof Cells === "undefined") {
        return false;
    }
    for (var r = 0; r < Cells.length; r++) {
        var valid = true;
        if (typeof(Cells[r]) === 'string' && isNaN(parseInt(string_replace(Cells[r], false)))) {
            valid = false;
        }

        if (!valid) {
            rNaN++;
        }
    }

    if (rNaN === Cells.length) {
        rValid = false;
    }
    return rValid;
}

DropChart.helper.getRowData = function (row) {
    var data = [];
    for (var j = 0; j < row.length; j++) {
        data[j] = getCellData(row[j]);
    }

    return data;
}

DropChart.helper.getRangeData = function (cells) {
    var datas = [];
    for (var i = 0; i < cells.length; i++) {
        datas[i] = DropChart.helper.getRowData(cells[i]);
    }

    return datas;
}

DropChart.helper.getCellRangeLabel = function (cells) {

    var result = "";
    var firstCell = cells[0][0];
    var lastRow = cells[cells.length - 1];
    var lastCell = lastRow[lastRow.length - 1];

    var pos = firstCell.split(":");
    result += Handsontable.helper.spreadsheetColumnLabel(parseInt(pos[1])) + '' + (parseInt(pos[0]) + 1);

    pos = lastCell.split(":");
    result += ":" + Handsontable.helper.spreadsheetColumnLabel(parseInt(pos[1])) + '' + (parseInt(pos[0]) + 1);
    return result;
}

DropChart.helper.canSwitchRowCol = function (cellsData) {
    var result = -1;
    var rValid = false;
    var cValid = false;
    if (DropChart.helper.hasNumbericRow(cellsData)) {
        rValid = true;
    }
    var rCellsData = DropChart.helper.transposeArr(cellsData);
    if (DropChart.helper.hasNumbericRow(rCellsData)) {
        cValid = true;
    }

    if (rValid && cValid) {
        result = 3;
    } else if (rValid) {
        result = 2;
    } else if (cValid) {
        result = 1;
    } else {
        // invalid data
        result = -1;
    }

    return result;
}

DropChart.helper.removeFirstRowColumn = function (cells) {
    cells.shift();
    if (cells.length > 0) {
        cells = DropChart.helper.transposeArr(cells);
        cells.shift();
    }

    return cells;
}
DropChart.helper.getEmptyArray = function (len) {
    var result = [];
    for (var i = 0; i < len; i++) {
        result[i] = "    ";
    }
    return result;
}

DropChart.helper.convertHex = function (hex, opacity) {
    hex = hex.replace('#', '');
    r = parseInt(hex.substring(0, 2), 16);
    g = parseInt(hex.substring(2, 4), 16);
    b = parseInt(hex.substring(4, 6), 16);

    result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
    return result;
}

DropChart.helper.ColorLuminance = function (hex, lum) {

    // validate hex string
    hex = String(hex).replace(/[^0-9a-f]/gi, '');
    if (hex.length < 6) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    lum = lum || 0;

    // convert to decimal and change luminosity
    var rgb = "#", c, i;
    for (i = 0; i < 3; i++) {
        c = parseInt(hex.substr(i * 2, 2), 16);
        c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
        rgb += ("00" + c).substr(c.length);
    }

    return rgb;
}