function initDatepicker() {
    initDateRange('cfrom', 'cto');
    initDateRange('ufrom', 'uto');
    initDateRange('widget_cfrom', 'widget_cto');
    initDateRange('widget_ufrom', 'widget_uto');
}

function initDateRange(fromId, toId) {
    jQuery("#" + fromId).datetimepicker({
        format: wpfdvars.dateFormat,
        validateOnBlur: true,
        scrollMonth: false,
        scrollDate: false,
        scrollInput: false,
        onShow: function (ct) {
            this.setOptions({
                maxDate: 0
            })
        },
        onSelectDate: function (ct, $i) {
            var toDate = jQuery("#" + toId).val() ? jQuery("#" + toId).val() : false;
            if (toDate && ct.dateFormat(wpfdvars.dateFormat) > toDate) {
                alert('Please select dates smaller than ' + toDate);
                $i.val('');
            }
        },
        closeOnDateSelect: true,
        timepicker: false
    });

    jQuery("#" + toId).datetimepicker({
        format: wpfdvars.dateFormat,
        validateOnBlur: true,
        scrollMonth: false,
        scrollDate: false,
        scrollInput: false,
        onShow: function (ct) {
            this.setOptions({
                maxDate: 0
            })
        },
        onSelectDate: function (ct, $i) {
            var fromDate = jQuery("#" + fromId).val() ? jQuery("#" + fromId).val() : false;
            if (fromDate && ct.dateFormat(wpfdvars.dateFormat) < fromDate) {
                alert('Please select a date greater than ' + fromDate);
                $i.val('');
            }
        },
        closeOnDateSelect: true,
        timepicker: false
    });
}

function initSorting() {
    jQuery('.orderingCol').click(function (e) {
        e.preventDefault();
        var ordering = jQuery(this).data('ordering');
        var direction = jQuery(this).data('direction');
        ajaxSearch(ordering, direction);
    });

    jQuery(".list-results #limit").change(function (e) {
        e.preventDefault();
        ajaxSearch();
        return false;
    });
}

function initTags() {
    var $ = jQuery;
    if ($("#input_tags").length > 0) {
        var taglist = $("#input_tags").val();
        var tagArr = taglist.split(",");
        $('.chk_ftags').each(function () {
            var temp = $(this).val();
            if (tagArr.indexOf(temp) > -1) {
                $(this).prop('checked', true);
            }
        });
    }
    if ($("#filter_catid").length > 0) {
        catChange("filter_catid");
    }
}
function showDefautTags() {
    var $ = jQuery;
    if (typeof defaultAllTags !== 'undefined' && defaultAllTags.length > 0) {
        $('.chk-tags-filtering ul').empty();
        defaultAllTags.sort(function (a, b) {
            if (a > b) {
                return 1
            }
            if (a < b) {
                return -1
            }
            return 0;
        });
        $.each(defaultAllTags, function (index, tag) {
            var element = $('<li><input title="" type="checkbox" name="chk_ftags[]" onclick="fillInputTags();" class="chk_ftags" id="ftags' + index + '" value="' + tag + '"> <span>' + tag.replace(/-/g, ' ') + '</span></li>');
            $('.chk-tags-filtering ul').append(element);
        });
        $("#input_tags").val("");
    }
}
function catChange(filterCat) {
    var $ = jQuery;
    var catId = $("#" + filterCat).val();

    if (catId === "") {
        showDefautTags();
        $('.chk_ftags').parent().show();
        return;
    }
    if ($('.chk-tags-filtering ul').length === 0) {
        return;
    }

    $.ajax({
        type: "GET",
        url: wpfdajaxurl + "task=search.getTagByCatId",
        data: {catId: catId}
    }).done(function (tags) {
        //var tags = JSON.parse(tags);
        if (tags.success === true) {
            $('.chk-tags-filtering ul').empty();
            $.each(tags.tags, function(index, tag) {
                var element = $('<li><input title="" type="checkbox" name="chk_ftags[]" onclick="fillInputTags();" class="chk_ftags" id="ftags'+index+'" value="'+tag['slug']+'"> <span>'+tag['name'].replace(/-/g, ' ')+'</span></li>');
                $('.chk-tags-filtering ul').append(element);
            });
            $("#input_tags").val("");
        } else {
            $('.chk-tags-filtering ul').empty();
            var message = $('<li>'+tags.message+'</li>');
            $('.chk-tags-filtering ul').append(message);
            $("#input_tags").val("");
        }
    });
}

function fillInputTags() {
    var tagVal = [];
    jQuery('.chk_ftags').each(function () {
        if (this.checked && jQuery(this).is(":visible")) {
            tagVal.push(jQuery(this).val());
        }
    });
    if (tagVal.length > 0) {
        jQuery("#input_tags").val(tagVal.join(","));
    } else {
        jQuery("#input_tags").val("");
    }
}

function getSearchParams(k) {
    var p = {};
    location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (s, k, v) {
        p[k] = v
    });
    return k ? p[k] : p;
}

function ajaxSearch(ordering, direction) {
    var $ = jQuery;
    var sform = $("#adminForm");
    // get the form data
    var formData = {
        'q': $(sform).find('input[name=q]').val(),
        'catid': $(sform).find('select[name=catid]').val(),
        'ftags': $(sform).find('input[name=ftags]').val(),
        'cfrom': $(sform).find('input[name=cfrom]').val(),
        'cto': $(sform).find('input[name=cto]').val(),
        'ufrom': $(sform).find('input[name=ufrom]').val(),
        'uto': $(sform).find('input[name=uto]').val(),
        'limit': $(sform).find('select[name=limit]').val()
    };

    formData = cleanObj(formData);
    if (jQuery.isEmptyObject(formData)) {
        $("#txtfilename").focus();
        return false;
    }

    if (typeof ordering !== 'undefined') formData.ordering = ordering;
    if (typeof direction !== 'undefined') formData.dir = direction;
    //pagination

    var filter_url = jQuery.param(formData);
    window.history.pushState(formData, "", wpfdvars.basejUrl + '&' + filter_url);

    $.ajax({
        type: "POST",
        url: wpfdajaxurl + "task=search.display",
        data: formData,
        beforeSend: function () {
            $("#wpfd-results").html('');
            $("#wpfd-results").prepend($("#loader").html());
        }
    }).done(function (result) {
        $("#wpfd-results").html(result);
        initSorting();
        if (typeof wpfdColorboxInit !== 'undefined') {
            wpfdColorboxInit();
        }
    });
}

jQuery(document).ready(function ($) {
    initDatepicker();
    initSorting();
    //initTags();
    $(".chk_ftags").click(function () {
        fillInputTags();
    });
    $("#filter_catid").on('change', function () {
        catChange("filter_catid");
    });
    $("#search_catid").change(function () {
        catChange("search_catid");
    });


    $("#adminForm").submit(function (e) {
        e.preventDefault();
        return false;
    });
    jQuery('.icon-date').click(function () {
        var txt = jQuery(this).attr('data-id');
        jQuery('#' + txt).datetimepicker('show');
    });

    jQuery('.feature-toggle').click(function () {
        var container = jQuery(this).parents('.by-feature');
        jQuery(container).find('.feature').slideToggle('slow', function () {
            jQuery(".feature-toggle").toggleClass(function () {
                if (jQuery(this).is(".feature-toggle-up")) {
                    return "feature-toggle-down";
                } else {
                    return "feature-toggle-up";
                }
            });
        });
    });

    //ajax filters
    $("#btnsearchbelow, #btnsearch").click(function (e) {
        e.preventDefault();
        ajaxSearch();
    });

    $("#btnReset").click(function (e) {
        e.preventDefault();
        resetFilters();
        $("#wpfd-results").html("");
    });
    $("#widget_btnReset").click(function (e) {
        e.preventDefault();
        resetFilters('#widget_search');
    });

    jQuery('.list-results table tr td a.file-item').click(function (e) {
        return true;
    });

    resetFilters = function (formSelect) {

        var sform = $("#adminForm");
        if (formSelect !== null && formSelect !== undefined) {
            sform = $(formSelect);
        }
        var inputs = $(sform).find('input, select');
        $.each(inputs, function (i, el) {
            var eType = $(el).attr('type');
            if (eType === 'checkbox') {
                $(el).prop('checked', false);
            } else {
                $(el).val("").trigger('change').trigger("liszt:updated").trigger("chosen:updated");
                if ($(el).hasClass("tagit")) {
                    $(el).tagit("removeAll");
                }
            }

        })
    };

    populateFilters = function (filters) {

        var sform = $("#adminForm");
        $.each(filters, function (f, v) {
            var els = $(sform).find('input[name=' + f + '], select[name=' + f + ']');
            if (els.length > 0) {
                $(els).val(v).trigger('change').trigger("liszt:updated").trigger("chosen:updated");
                if ($(els).hasClass("tagit")) {
                    $(els).tagit("removeAll");
                    if (v !== "") {
                        var tgs = v.split(",");
                        for (var i = 0; i < tgs.length; i++) {
                            $(els).tagit("createTag", tgs[i]);
                        }
                    }

                }
            }
        });
    };

    //Remove propery with empty value
    cleanObj = function (obj) {
        for (var k in obj) {
            if (obj.hasOwnProperty(k)) {
                if (!obj[k]) delete obj[k];
            }
        }
        return obj;
    };

    //back on browser
    jQuery(window).on('popstate', function (event) {
        var state = event.originalEvent.state;
        resetFilters();
        if (state !== null) {
            var formData = state;
            populateFilters(formData);
            formData.view = "frontsearch";
            formData.format = "raw";
            $.ajax({
                type: "POST",
                url: basejUrl + 'index.php?option=wpfd',
                data: formData
            }).done(function (result) {
                $("#wpfd-results").html(result);
            });
        } else {
            $("#wpfd-results").html("");
        }
    });
    var params = getSearchParams();
    if (params.q !== undefined ||
        params.catid !== undefined ||
        params.ftags !== undefined ||
        params.cfrom !== undefined ||
        params.cto !== undefined ||
        params.ufrom !== undefined ||
        params.uto !== undefined
    ) {
        ajaxSearch();
    }
});