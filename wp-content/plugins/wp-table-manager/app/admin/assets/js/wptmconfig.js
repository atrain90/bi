/**
 * Created by USER on 12/03/2018.
 */
jQuery(document).ready(function($) {
    var $controlFormatStyle = $('#control_format_style');
    var $list_format_style  = $controlFormatStyle.find('#list_format_style');
    var $new_format_style   = $controlFormatStyle.find('#new_format_style');
    var $set_color          = $controlFormatStyle.find('#set_color');
    var $save_format_style  = $controlFormatStyle.find('#save_format_style');
    var active_format_color = '';

    //create new .pane-color-tile
    $new_format_style.find('.create_format_style').on('click', function () {
        var number_format = $list_format_style.find('.pane-color-tile').length;
        var $html = $('<div class="pane-color-tile td_' + number_format + '">' +
            '<div class="pane-color-tile-header pane-color-tile-band" style="background-color:#ffffff" data-value="#ffffff"></div>' +
            '<div class="pane-color-tile-1 pane-color-tile-band" style="background-color:#ffffff" data-value="#ffffff"></div>' +
            '<div class="pane-color-tile-2 pane-color-tile-band" style="background-color:#ffffff" data-value="#ffffff"></div>' +
            '<div class="pane-color-tile-footer pane-color-tile-band" style="background-color:#ffffff" data-value="#ffffff"></div>' +
            '</div>');
        $html.appendTo($list_format_style);

        /*active new .pane-color-tile*/
        set_active_format_style();
        $list_format_style.find('.td_' + number_format).trigger('click');
    });

    //remove .pane-color-tile
    $new_format_style.find('.remove_format_style').on('click', function () {
        $controlFormatStyle.find('.active').remove();
        $controlFormatStyle.find('.pane-color-tile:last-child').trigger('click');
        save_format_style();
        reset_color_picket($controlFormatStyle.find('.pane-color-tile:last-child'));
    });

    //click active .pane-color-tile
    var set_active_format_style = function () {
        $controlFormatStyle.find('.pane-color-tile').on('click', function () {
            if (!$save_format_style.hasClass('show')) {
                $new_format_style.find('.hide_set_format_style').trigger('click');
            }
            $controlFormatStyle.find('.active').removeClass('active');
            $(this).addClass('active');
            reset_color_picket($(this));
        });
    };

    set_active_format_style();

    //set color picket when select format style
    var reset_color_picket = function (e) {
        active_format_color = '';
        e.find('.pane-color-tile-band').each(function (i) {
            active_format_color += i === 0 ? '' : '|';
            active_format_color += $(this).data('value');
            $set_color.find('.wp-picker-container:eq(' + i + ') input.wp-color-field').val($(this).data('value')).change();
        });
    };

    var get_color_picket = function (e, v, reset) {
        if (reset === '0' || e.hasClass('pane-set-color-header')) {
            $list_format_style.find('.active').find('.pane-color-tile-header').css('background-color', v).data('value', v);
        }
        if (reset === '1' || e.hasClass('pane-set-color-1')) {
            $list_format_style.find('.active').find('.pane-color-tile-1').css('background-color', v).data('value', v);
        }
        if (reset === '2' || e.hasClass('pane-set-color-2')) {
            $list_format_style.find('.active').find('.pane-color-tile-2').css('background-color', v).data('value', v);
        }
        if (reset === '3' || e.hasClass('pane-set-color-footer')) {
            $list_format_style.find('.active').find('.pane-color-tile-footer').css('background-color', v).data('value', v);
        }
    };

    //select color
    $('.wp-color-field').wpColorPicker({
        width: 180,
        change: function(e, i){
            get_color_picket($(this), i.color.toString(), '');
        },
        clear: function (e) {
            get_color_picket($(this).siblings('label').find('input'), '#ffffff', '');
        }
    });

    /*save the change format style*/
    var save_format_style = function () {
        var dataFormatStyle = '';
        $list_format_style.find('.pane-color-tile').find('.pane-color-tile-band').each(function (i) {
            dataFormatStyle += (i !== 0 ? '|' : '') + $(this).data('value');
        }),
            $('#alternate_color').val(dataFormatStyle);

        active_format_color = '';
    };
    $save_format_style.find('input:eq(0)').on('click', function () {
        save_format_style();
        $new_format_style.find('.create_format_style').trigger('click');
    });

    /*remove the change format style*/
    var cancel_format_style = function () {
        active_format_color.split('|').map(function (color, number) {
            get_color_picket($controlFormatStyle, color, number.toString());
        }),
            reset_color_picket($list_format_style.find('.active'));
    };
    $save_format_style.find('input:eq(1)').on('click', function () {
        if (active_format_color !== '') {
            cancel_format_style();
        }
    });

    $new_format_style.find('.hide_set_format_style').toggle(
        function () {
            $(this).val('Hide');
            $set_color.css('display', 'flex');
            $save_format_style.show().addClass('show');
        },
        function() {
            $(this).val('Show');
            $set_color.hide();
            $save_format_style.hide().removeClass('show');
        }
    );
});