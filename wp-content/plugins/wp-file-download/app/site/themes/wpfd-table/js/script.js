/**
 * Wpfd
 *
 * We developed this code with our hearts and passion.
 * We hope you found it useful, easy to understand and to customize.
 * Otherwise, please feel free to contact us at contact@joomunited.com *
 * @package WP File Download
 * @copyright Copyright (C) 2013 JoomUnited (http://www.joomunited.com). All rights reserved.
 * @copyright Copyright (C) 2013 Damien Barrère (http://www.crac-design.com). All rights reserved.
 * @license GNU General Public License version 2 or later; http://www.gnu.org/licenses/gpl-2.0.html
 */

jQuery(document).ready(function ($) {


    var table_tree = $('.wpfd-foldertree-table');
    var table_hash = window.location.hash;
    var table_root_cat = $('.wpfd-content-table').data('category');
    var table_cParents = {};
    $(".wpfd-content-table").each(function () {
        var table_topCat = $(this).data('category');
        table_cParents[table_topCat] = {parent: 0, term_id: table_topCat, name: $(this).find("h2").text()};
        $(this).find(".wpfdcategory.catlink").each(function () {
            var tempidCat = $(this).data('idcat');
            table_cParents[tempidCat] = {parent: table_topCat, term_id: tempidCat, name: $(this).text()};
        })
    });

    //load media tables
    $('.wpfd-content .mediaTable').mediaTable();

    Handlebars.registerHelper('bytesToSize', function (bytes) {
        return bytes === 'n/a' ? bytes : bytesToSize(parseInt(bytes));
    });

    function table_initClick() {
        $('.wpfd-content-table .catlink').unbind('click').click(function (e) {
            e.preventDefault();
            table_load($(this).parents('.wpfd-content-table').data('category'), $(this).data('idcat'));
        });
    }

    table_initClick();

    table_hash = table_hash.replace('#', '');
    if (table_hash !== '') {
        var hasha = table_hash.split('-');
        var re = new RegExp("^(p[0-9]+)$");
        var page = null;
        var stringpage = hasha.pop();

        if (re.test(stringpage)) {
            page = stringpage.replace('p', '');
        }
        var hash_category_id = hasha[0];
        if (!parseInt(hash_category_id)) {
            // todo
        } else {
            setTimeout(function () {
                table_load($('.wpfd-content-table').data('category'), hash_category_id, page);
            }, 100);
        }
    }


    function table_load(sourcecat, catid, page) {
        var pathname = window.location.href.replace(window.location.hash, '');
        var container = $(".wpfd-content-table.wpfd-content-multi[data-category=" + sourcecat + "]");
        container.find('#current_category').val(catid);
        container.next('.wpfd-pagination').remove();

        $(".wpfd-content-multi[data-category=" + sourcecat + "] table tbody").empty();
        $(".wpfd-content-multi[data-category=" + sourcecat + "] table").after($('#wpfd-loading-wrap').html());
        $(".wpfd-content-multi[data-category=" + sourcecat + "] .wpfd-categories").empty();
        //Get categories
        $.ajax({
            url: wpfdparams.wpfdajaxurl + "task=categories.display&view=categories&id=" + catid + "&top=" + sourcecat,
            dataType: "json"
        }).done(function (categories) {

            if (page !== null && page !== undefined) {
                window.history.pushState('', document.title, pathname + '#' + catid + '-' + categories.category.slug + '-p' + page);
            } else {
                window.history.pushState('', document.title, pathname + '#' + catid + '-' + categories.category.slug);
            }

            container.find('#current_category_slug').val(categories.category.slug);
            var tpltable_sourcecategories = container.parents().find("#wpfd-template-table-categories-" + sourcecat).html();
            var template = Handlebars.compile(tpltable_sourcecategories);
            var html = template(categories);
            $(".wpfd-content-multi[data-category=" + sourcecat + "] .wpfd-categories").prepend(html);

            if (table_tree.length) {
                var currentTree = container.find('.wpfd-foldertree-table');
                currentTree.find('li').removeClass('selected');
                currentTree.find('i.md').removeClass('md-folder-open').addClass("md-folder");

                currentTree.jaofiletree('open', catid, currentTree);

                var el = currentTree.find('a[data-file="' + catid + '"]').parent();
                el.find(' > i.md').removeClass("md-folder").addClass("md-folder-open");

                if (!el.hasClass('selected')) {
                    el.addClass('selected');
                }

                var ps = currentTree.find('.icon-open-close');

                $.each(ps.get().reverse(), function (i, p) {
                    if (typeof $(p).data() !== 'undefined' && $(p).data('id') == Number(hash_category_id)) {
                        hash_category_id = $(p).data('parent_id');
                        $(p).click();
                    }
                });
            }

            //Get files
            $.ajax({
                url: wpfdparams.wpfdajaxurl + "task=files.display&view=files&id=" + catid + "&rootcat=" + table_root_cat + "&page=" + page,
                dataType: "json"
            }).done(function (content) {
                // $.extend(content,categories);

                if (content.files.length) {
                    container.find(".table-download-category").removeClass("display-download-category");
                } else {
                    container.find(".table-download-category").addClass("display-download-category");
                }
                $(".wpfd-content-multi[data-category=" + sourcecat + "]").after(content.pagination);
                delete content.pagination;

                var tpltable_source = container.parents().find("#wpfd-template-table-" + sourcecat).html();
                var template_table = Handlebars.compile(tpltable_source);
                var html = template_table(content);
                //html = $('<textarea/>').html(html).val();
                $(".wpfd-content-multi[data-category=" + sourcecat + "] table tbody").append(html);
                $(".wpfd-content-multi[data-category=" + sourcecat + "] table tbody").trigger('change');
                $(".wpfd-content-multi[data-category=" + sourcecat + "] .mediaTableMenu").find('input').trigger('change');

                for (var i = 0; i < categories.categories.length; i++) {
                    table_cParents[categories.categories[i].term_id] = categories.categories[i];
                }

                table_breadcrum(sourcecat, catid, categories.category);

                table_initClick();
                if (typeof wpfdColorboxInit !== 'undefined') {
                    wpfdColorboxInit();
                }
                wpfdTrackDownload();

                table_init_pagination($('.wpfd-pagination'));
                wpfd_remove_loading($(".wpfd-content-multi"));
            });

        });
    }

    function table_breadcrum(sourcecat, catid, category) {
        var links = [];
        var current_Cat = table_cParents[catid];
        if (!current_Cat) {
            $(".wpfd-content-table[data-category=" + sourcecat + "] .table-download-category").attr('href', category.linkdownload_cat);
            return false;
        }
        links.unshift(current_Cat);

        if (current_Cat.parent !== 0) {
            while (table_cParents[current_Cat.parent]) {
                current_Cat = table_cParents[current_Cat.parent];
                links.unshift(current_Cat);
            }
        }

        var html = '';
        for (var i = 0; i < links.length; i++) {
            if (i < links.length - 1) {
                html += '<li><a class="catlink" data-idcat="' + links[i].term_id + '" href="javascript:void(0)">' + links[i].name + '</a><span class="divider"> &gt; </span></li>';
            } else {
                html += '<li><span>' + links[i].name + '</span></li>';
            }
        }
        $(".wpfd-content-table[data-category=" + sourcecat + "] .wpfd-breadcrumbs-table li").remove();
        $(".wpfd-content-table[data-category=" + sourcecat + "] .wpfd-breadcrumbs-table").append(html);
        $(".wpfd-content-table[data-category=" + sourcecat + "] .table-download-category").attr('href', category.linkdownload_cat);
    }

    if (table_tree.length) {
        table_tree.each(function () {
            var table_topCat = $(this).parents('.wpfd-content-table.wpfd-content-multi').data('category');
            $(this).jaofiletree({
                script: wpfdparams.wpfdajaxurl + 'task=categories.getCats',
                usecheckboxes: false,
                root: table_topCat,
                showroot: table_cParents[table_topCat].name,
                onclick: function (elem, file) {

                    var table_topCat = $(elem).parents('.wpfd-content-table.wpfd-content-multi').data('category');
                    if (table_topCat !== file) {

                        $(elem).parents('.directory').each(function () {
                            var $this = $(this);
                            var category = $this.find(' > a');
                            var parent = $this.find('.icon-open-close');
                            if (parent.length > 0) {
                                if (typeof table_cParents[category.data('file')] === 'undefined') {
                                    table_cParents[category.data('file')] = {
                                        parent: parent.data('parent_id'),
                                        term_id: category.data('file'),
                                        name: category.text()
                                    };
                                }
                            }
                        });

                    }

                    table_load(table_topCat, file);
                }
            });
        })
    }


    $('.wpfd-pagination').each(function () {
        var $this = $(this);
        table_init_pagination($this);
    });

    function table_init_pagination($this) {

        var number = $this.find('a:not(.current)');

        var wrap = $this.prev('.wpfd-content-table');

        var current_category = wrap.find('#current_category').val();
        var sourcecat = wrap.data('category');

        number.unbind('click').bind('click', function () {
            var page_number = $(this).attr('data-page');

            if (typeof page_number !== 'undefined') {
                var pathname = window.location.href.replace(window.location.hash, '');
                var category = $(".wpfd-content-multi[data-category=" + sourcecat + "]").find('#current_category').val();
                var category_slug = $(".wpfd-content-multi[data-category=" + sourcecat + "]").find('#current_category_slug').val();

                window.history.pushState('', document.title, pathname + '#' + category + '-' + category_slug + '-p' + page_number);

                $(".wpfd-content-multi[data-category=" + sourcecat + "] table tbody tr:not(.topheader)").remove();
                $(".wpfd-content-multi[data-category=" + sourcecat + "] table").after($('#wpfd-loading-wrap').html());
                //Get files
                $.ajax({
                    url: wpfdparams.wpfdajaxurl + "task=files.display&view=files&id=" + current_category + "&rootcat=" + sourcecat + "&page=" + page_number,
                    dataType: "json",
                    beforeSend: function () {
                        $('html, body').animate({scrollTop: $('.wpfd-content-table').offset().top}, 'fast');
                    }
                }).done(function (content) {

                    delete content.category;
                    wrap.next('.wpfd-pagination').remove();
                    wrap.after(content.pagination);
                    delete content.pagination;
                    var tpltable_source = wrap.parents().find("#wpfd-template-table-" + sourcecat).html();
                    var template_table = Handlebars.compile(tpltable_source);
                    var html = template_table(content);
                    $(".wpfd-content-multi[data-category=" + sourcecat + "] table tbody").append(html);
                    $(".wpfd-content-multi[data-category=" + sourcecat + "] table tbody").trigger('change');
                    $(".wpfd-content-multi[data-category=" + sourcecat + "] .mediaTableMenu").find('input').trigger('change');

                    if (typeof wpfdColorboxInit !== 'undefined') {
                        wpfdColorboxInit();
                    }
                    table_init_pagination(wrap.next('.wpfd-pagination'));
                    wpfd_remove_loading($(".wpfd-content-multi"));
                });
            }

        });
    }
});