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
    var sourcefiles = $("#wpfd-template-tree-files").html();
    var sourcecategories = $("#wpfd-template-tree-categories").html();
    var sourcefile = $("#wpfd-template-tree-box").html();
    var tree_hash = window.location.hash;

    var ggd_root_cat = $('.wpfd-content-tree').data('category');
    Handlebars.registerHelper('bytesToSize', function (bytes) {
        return bytes === 'n/a' ? bytes : bytesToSize(bytes);
    });

    treeInitClickFile();

    tree_hash = tree_hash.replace('#', '');
    if (tree_hash !== '') {
        var hasha = tree_hash.split('-');
        var hash_category_id = hasha[0];
        if (!parseInt(hash_category_id)) {
            //todo
        } else {
            setTimeout(function () {
                tree_loadcategory(hash_category_id, $('.wpfd-content-tree.wpfd-content-multi').data('category'));
            }, 100)
        }
    }

    $('.wpfd-content-tree a.catlink').unbind('click.cat').bind('click.cat', function (e) {
        e.preventDefault();
        tree_load($(this).parents('.wpfd-content-tree').data('category'), $(this).data('idcat'), $(this));
        $(this).parent().removeClass('collapsed').addClass('expanded');
    });

    function tree_loadcategory($catid, $sourcecat) {
        $.ajax({
            url: wpfdparams.wpfdajaxurl + "task=categories.getParentsCats&id=" + $catid + "&displaycatid=" + $sourcecat,
            dataType: "json"
        }).done(function (ob) {
            tree_load($sourcecat, ob[0], $('.wpfd-content-tree [data-idcat="' + ob[0] + '"]'), ob);
        });
    }

    function treeInitClickFile() {
        $('.wpfd-content-tree .wpfd-file-link').unbind('click').click(function (e) {
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
                var box = $("#tree-wpfd-box");
                if (box.length === 0) {
                    $('body').append('<div id="tree-wpfd-box" style="display: hidden;"></div>');
                    box = $("#tree-wpfd-box");
                }
                box.empty();
                box.prepend(html);
                box.click(function (e) {
                    if ($(e.target).is('#tree-wpfd-box')) {
                        box.hide();
                    }
                    $('#tree-wpfd-box').unbind('click.box').bind('click.box', function (e) {
                        if ($(e.target).is('#tree-wpfd-box')) {
                            box.hide();
                        }
                    });
                });
                $('#tree-wpfd-box .wpfd-close').click(function () {
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

    function wantDelete(item, arr) {
        for (var i = 0; i < arr.length; i++) {
            if (arr[i] === item) {
                arr.splice(i, 1);
                break;
            }
        }
    }

    function tree_load(sourcecat, category, elem, loadcats) {


        if (!jQuery.isEmptyObject(loadcats)) {
            wantDelete(category, loadcats);
        }

        var pathname = window.location.href.replace(window.location.hash, '');

        var ul = elem.parent().children('ul');
        $('.wpfd-content-tree').find('.active').removeClass('active');
        elem.parent().addClass('active');
        if (ul.length > 0) {
            //close cat
            ul.slideUp(400, null, function () {
                $(this).remove();
                elem.parent().removeClass('open expanded').addClass('collapsed');
                elem.parent().removeClass('wpfd-loading-tree');
                elem.parent().find('.wpfd-loading-tree-bg').remove();
            });
            var root_linkdownload_cat = $(".wpfd-content-tree[data-category=" + sourcecat + "] #root_linkdownload_cat").val();
            var root_countfile_cat = $(".wpfd-content-tree[data-category=" + sourcecat + "] #root_countfile_cat").val();
            $(".wpfd-content-tree[data-category=" + sourcecat + "] .tree-download-category").attr('href', root_linkdownload_cat);

            if (root_countfile_cat !== "0") {
                $(".wpfd-content-tree[data-category=" + sourcecat + "] .tree-download-category").removeClass("display-download-category");
            } else {
                $(".wpfd-content-tree[data-category=" + sourcecat + "] .tree-download-category").addClass("display-download-category");
            }

            return;
        } else {
            elem.parent().addClass('wpfd-loading-tree');
            elem.parent().prepend($('#wpfd-loading-tree-wrap').html());
        }
        if ($(elem).hasClass('clicked')) {
            return;
        }
        $(elem).addClass('clicked');
        //Get categories
        $.ajax({
            url: wpfdparams.wpfdajaxurl + "task=categories.display&view=categories&id=" + category,
            dataType: "json"
        }).done(function (categories) {

            window.history.pushState('', document.title, pathname + '#' + category + '-' + categories.category.slug);

            var template = Handlebars.compile(sourcecategories);
            var html = template(categories);
            if (categories.categories.length > 0) {
                elem.parents('li').append('<ul style="display:none;">' + html + '</ul>');
                $(".wpfd-content-tree[data-category=" + sourcecat + "] a.catlink").unbind('click.cat').bind('click.cat', function (e) {
                    e.preventDefault();
                    tree_load($(this).parents('.wpfd-content-tree').data('category'), $(this).data('idcat'), $(this));
                    treeInitClickFile();
                });
            }
            $(".wpfd-content-tree[data-category=" + sourcecat + "] .tree-download-category").attr('href', categories.category.linkdownload_cat);

            //Get files
            $.ajax({
                url: wpfdparams.wpfdajaxurl + "task=files.display&view=files&id=" + category + "&rootcat=" + ggd_root_cat,
                dataType: "json"
            }).done(function (content) {

                if (content.files.length) {
                    $(".wpfd-content-tree[data-category=" + sourcecat + "] .tree-download-category").removeClass("display-download-category");
                } else {
                    $(".wpfd-content-tree[data-category=" + sourcecat + "] .tree-download-category").addClass("display-download-category");
                }

                var template = Handlebars.compile(sourcefiles);
                var html = template(content);
                html = $('<textarea/>').html(html).val();
                if (elem.parent().children('ul').length === 0) {
                    elem.parent().append('<ul style="display:none;">' + html + '</ul>');
                } else {
                    elem.parent().children('ul').append(html);
                }

                treeInitClickFile();
                elem.parent().children('ul').slideDown(400, null, function () {

                    elem.parent().addClass('open expanded');
                    elem.parent().removeClass('wpfd-loading-tree collapsed');
                    elem.parent().find('.wpfd-loading-tree-bg').remove();
                });

                if (!jQuery.isEmptyObject(loadcats)) {
                    var ccat = loadcats[0];
                    tree_load(sourcecat, ccat, $('.wpfd-content-tree [data-idcat="' + ccat + '"]'), loadcats);
                }

            });

            $(elem).removeClass('clicked');

        });


    }
});
