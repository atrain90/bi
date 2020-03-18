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
    var sourcefiles = $("#wpfd-template-ggd-files").html();
    var sourcecategories = $("#wpfd-template-ggd-categories").html();
    var sourcefile = $("#wpfd-template-ggd-box").html();
    var gdd_hash = window.location.hash;
    var ggd_cParents = {};
    var ggd_tree = $('.wpfd-foldertree-ggd');
    var ggd_root_cat = $('.wpfd-content-ggd').data('category');

    $(".wpfd-content-ggd").each(function () {
        var ggd_topCat = $(this).data('category');
        ggd_cParents[ggd_topCat] = {parent: 0, term_id: ggd_topCat, name: $(this).find("h2").text()};

        $(this).find(".wpfdcategory.catlink").each(function () {
            var tempidCat = $(this).data('idcat');
            ggd_cParents[tempidCat] = {parent: ggd_topCat, term_id: tempidCat, name: $(this).text()};
        })
    });

    Handlebars.registerHelper('bytesToSize', function (bytes) {
        return bytes === 'n/a' ? bytes : bytesToSize(bytes);
    });

    initClickFile();

    function ggd_initClick() {
        $('.wpfd-content-ggd .catlink').unbind('click').click(function (e) {
            e.preventDefault();
            load($(this).parents('.wpfd-content-ggd').data('category'), $(this).data('idcat'));
        });
    }

    ggd_initClick();


    gdd_hash = gdd_hash.replace('#', '');
    if (gdd_hash !== '') {
        var hasha = gdd_hash.split('-');
        var re = new RegExp("^(p[0-9]+)$");
        var page = null;
        var stringpage = hasha.pop();

        if (re.test(stringpage)) {
            page = stringpage.replace('p', '');
        }

        var hash_category_id = hasha[0];
        if (!parseInt(hash_category_id)) {
            //todo
        } else {
            setTimeout(function () {
                load($('.wpfd-content-ggd').data('category'), hash_category_id, page);
            }, 100)
        }
    }


    function initClickFile() {
        $('.wpfd-content .wpfd-file-link').unbind('click').click(function (e) {
            var atthref = $(this).attr('href');
            if (atthref !== '#') {
                return;
            }
            e.preventDefault();
            var fileid = $(this).data('id');
            var categoryid = $(this).data('category_id');
            $.ajax({
                url: wpfdparams.wpfdajaxurl + "task=file.display&view=file&id=" + fileid + "&categoryid=" + categoryid + "&rootcat=" + ggd_root_cat,
                dataType: "json"
            }).done(function (file) {
                var template = Handlebars.compile(sourcefile);
                var html = template(file);
                var box = $("#wpfd-ggd-box");
                if (box.length === 0) {
                    $('body').append('<div id="wpfd-ggd-box" style="display: none;"></div>');
                    box = $("#wpfd-ggd-box");
                }
                box.empty();
                box.prepend(html);
                box.click(function (e) {
                    if ($(e.target).is('#wpfd-ggd-box')) {
                        box.hide();
                    }
                    $('#wpfd-ggd-box').unbind('click.box').bind('click.box', function (e) {
                        if ($(e.target).is('#wpfd-ggd-box')) {
                            box.hide();
                        }
                    });
                });
                $('#wpfd-ggd-box .wpfd-close').click(function () {
                    box.hide();
                });

                box.show();

                var dropblock = box.find('.dropblock');
                if ($(window).width() < 400) {
                    dropblock.css('margin-top', '0');
                    dropblock.css('margin-left', '0');
                    dropblock.css('top', '0');
                    dropblock.css('left', '0');
                    dropblock.height($(window).height() - parseInt(dropblock.css('padding-top'), 10) - parseInt(dropblock.css('padding-bottom'), 10));
                    dropblock.width($(window).width() - parseInt(dropblock.css('padding-left'), 10) - parseInt(dropblock.css('padding-right'), 10));
                } else {
                    dropblock.css('margin-top', (-(dropblock.height() / 2) - 20) + 'px');
                    dropblock.css('margin-left', (-(dropblock.width() / 2) - 20) + 'px');
                    dropblock.css('height', '');
                    dropblock.css('width', '');
                    dropblock.css('top', '');
                    dropblock.css('left', '');
                }

                if (typeof wpfdColorboxInit !== 'undefined') {
                    wpfdColorboxInit();
                }
                wpfdTrackDownload();
            });
        });
    }

    function load(sourcecat, catid, page) {
        var pathname = window.location.href.replace(window.location.hash, '');
        var container = $(".wpfd-content-ggd.wpfd-content-multi[data-category=" + sourcecat + "]");
        container.find('#current_category').val(catid);
        container.next('.wpfd-pagination').remove();
        $(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd").empty();
        $(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd").html($('#wpfd-loading-wrap').html());

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

            var template = Handlebars.compile(sourcecategories);
            var html = template(categories);
            $(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd").prepend(html);

            for (var i = 0; i < categories.categories.length; i++) {
                ggd_cParents[categories.categories[i].term_id] = categories.categories[i];
            }

            ggd_breadcrum(sourcecat, catid, categories.category);

            if (ggd_tree.length) {
                var currentTree = container.find('.wpfd-foldertree-ggd');
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

        });

        //Get files
        $.ajax({
            url: wpfdparams.wpfdajaxurl + "task=files.display&view=files&id=" + catid + "&rootcat=" + ggd_root_cat + "&page=" + page,
            dataType: "json"
        }).done(function (content) {

            if (content.files.length) {
                $(".wpfd-content-ggd.wpfd-content-multi[data-category=" + sourcecat + "]  .ggd-download-category").removeClass("display-download-category");
            } else {
                $(".wpfd-content-ggd.wpfd-content-multi[data-category=" + sourcecat + "]  .ggd-download-category").addClass("display-download-category");
            }

            $(".wpfd-content-ggd[data-category=" + sourcecat + "]").after(content.pagination);
            delete content.pagination;
            var template = Handlebars.compile(sourcefiles);
            var html = template(content);
            html = $('<textarea/>').html(html).val();
            $(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd").append(html);
            initClickFile();


            gdd_init_pagination($('.wpfd-pagination'));

            wpfd_remove_loading($(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd"));
        });

    }

    function ggd_breadcrum(ggd_topCat, catid, category) {
        var links = [];
        var current_Cat = ggd_cParents[catid];
        if (!current_Cat) {
            $(".wpfd-content-ggd[data-category=" + ggd_topCat + "] .ggd-download-category").attr('href', category.linkdownload_cat);
            return false;
        }
        links.unshift(current_Cat);
        if (current_Cat.parent !== 0) {
            while (ggd_cParents[current_Cat.parent]) {
                current_Cat = ggd_cParents[current_Cat.parent];
                links.unshift(current_Cat);
            }
        }

        var html = '';
        for (var i = 0; i < links.length; i++) {
            if (i < links.length - 1) {
                html += '<li><a class="catlink" data-idcat="' + links[i].term_id + '" href="javascript:void(0)">';
                html += links[i].name + '</a><span class="divider"> &gt; </span></li>';
            } else {
                html += '<li><span>' + links[i].name + '</span></li>';
            }
        }
        $(".wpfd-content-ggd[data-category=" + ggd_topCat + "] .wpfd-breadcrumbs-ggd li").remove();
        $(".wpfd-content-ggd[data-category=" + ggd_topCat + "] .wpfd-breadcrumbs-ggd").append(html);

        $(".wpfd-content-ggd[data-category=" + ggd_topCat + "] .catlink").click(function (e) {
            e.preventDefault();
            load(ggd_topCat, $(this).data('idcat'));
            initClickFile();
        });
        $(".wpfd-content-ggd[data-category=" + ggd_topCat + "] .ggd-download-category").attr('href', category.linkdownload_cat);
    }

    if (ggd_tree.length) {
        ggd_tree.each(function () {
            var ggd_topCat = $(this).parents('.wpfd-content-ggd.wpfd-content-multi').data('category');
            $(this).jaofiletree({
                script: wpfdparams.wpfdajaxurl + 'task=categories.getCats',
                usecheckboxes: false,
                root: ggd_topCat,
                showroot: ggd_cParents[ggd_topCat].name,
                onclick: function (elem, file) {
                    var ggd_topCat = $(elem).parents('.wpfd-content-ggd.wpfd-content-multi').data('category');
                    if (ggd_topCat !== file) {

                        $(elem).parents('.directory').each(function () {
                            var $this = $(this);
                            var category = $this.find(' > a');
                            var parent = $this.find('.icon-open-close');
                            if (parent.length > 0) {
                                if (typeof ggd_cParents[category.data('file')] === 'undefined') {
                                    ggd_cParents[category.data('file')] = {
                                        parent: parent.data('parent_id'),
                                        term_id: category.data('file'),
                                        name: category.text()
                                    };
                                }
                            }
                        });

                    }

                    load(ggd_topCat, file);
                }
            });
        })
    }

    $('.wpfd-pagination').each(function () {
        var $this = $(this);
        gdd_init_pagination($this);
    });

    function gdd_init_pagination($this) {

        var number = $this.find(':not(.current)');

        var wrap = $this.prev('.wpfd-content-ggd');

        var current_category = wrap.find('#current_category').val();
        var sourcecat = wrap.data('category');

        number.unbind('click').bind('click', function () {
            var page_number = $(this).attr('data-page');
            if (typeof page_number !== 'undefined') {
                var pathname = window.location.href.replace(window.location.hash, '');
                var category = $(".wpfd-content-ggd[data-category=" + sourcecat + "]").find('#current_category').val();
                var category_slug = $(".wpfd-content-ggd[data-category=" + sourcecat + "]").find('#current_category_slug').val();

                window.history.pushState('', document.title, pathname + '#' + category + '-' + category_slug + '-p' + page_number);

                $(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd .wpfd-file-link").remove();
                $(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd").append($('#wpfd-loading-wrap').html());
                //Get files
                $.ajax({
                    url: wpfdparams.wpfdajaxurl + "?action=wpfd&task=files.display&view=files&id=" + current_category + "&rootcat=" + sourcecat + "&page=" + page_number,
                    dataType: "json",
                    beforeSend: function () {
                        $('html, body').animate({scrollTop: $('.wpfd-content-ggd').offset().top}, 'fast');
                    }
                }).done(function (content) {

                    delete content.category;
                    wrap.next('.wpfd-pagination').remove();
                    wrap.after(content.pagination);
                    delete content.pagination;

                    var template = Handlebars.compile(sourcefiles);
                    var html = template(content);

                    $(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd").append(html);
                    initClickFile();

                    gdd_init_pagination(wrap.next('.wpfd-pagination'));
                    wpfd_remove_loading($(".wpfd-content-ggd[data-category=" + sourcecat + "] .wpfd-container-ggd"));
                });
            }
        });

    }

});