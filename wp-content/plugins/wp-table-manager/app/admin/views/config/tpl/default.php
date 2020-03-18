<?php
/**
 * WP Table Manager
 *
 * @package WP Table Manager
 * @author  Joomunited
 * @version 1.0
 */
use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\Factory;
?>
<div class="wrap wptm-config">
    <div id="icon-options-general" class="icon32"></div>
    <h2><?php esc_attr_e('WP Table Manager Configuration', 'wptm'); ?></h2>
    <div id="message" class="updated notice notice-success">
        <p></p>
        <i class="notice-dismiss"></i>
    </div>
    <div id="dashboard-widgets-wrap">
        <div id="dashboard-widgets" class="metabox-holder columns-2">
            <div id="wptm-container-config" class="">
                <div class="tab-header">
                    <ul class="nav-tab-wrapper" id="wptm-tabs-config">
                        <a id="tab-main" class="nav-tab active" data-tab-id="main" href="#main">
                            <?php ucfirst(esc_attr_e('Main settings', 'wptm'));?>
                        </a>
                        <a id="tab-jutranslation" class="nav-tab" data-tab-id="jutranslation" href="#jutranslation">
                            <?php ucfirst(esc_attr_e('Translation', 'wptm'));?>
                        </a>
                    </ul>
                </div>
                <div class="tab-content" id="wptm-tabs-content-config">
                    <div id="wptm-main-config" class="tab-pane active">
                        <?php
                            //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- print output from render() of framework
                            echo $this->configform;
                        ?>
                    </div>
                    <div id="wptm-jutranslation-config" class="tab-pane ">
                        <?php
                            //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped -- print output from render() of framework
                            echo \Joomunited\WPTableManager\Jutranslation\Jutranslation::getInput();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    #wptm-tabs,#wptm-tabs-config { margin-bottom: 1px;}
    .wptmparams { }
    #wptm-tabs .nav-tab.active,
    #wptm-tabs-config .nav-tab.active {
        background-color: #FFF;
        color: #464646;
    }
    #wptm-tabs-content,#wptm-tabs-content-config { background: #fff; border-left:1px solid #CCC; padding: 10px 10px 30px 10px}
    #wptm-tabs-content .tab-pane { display: none}
    #wptm-tabs-content-config .tab-pane { display: none}
    #wptm-tabs-content .tab-pane.active { display: block}
    #wptm-tabs-content-config .tab-pane.active { display: block}
    #wptm-tabs-content-config textarea {
        width: 100%;
    }
</style>
<script type="text/javascript" class="xxxxx">
    ajaxurl = "<?php echo esc_url_raw(Application::getInstance('Wptm')->getAjaxUrl()); ?>";
    jQuery(document).ready(function($) {
        var wptm_ajaxurl = "<?php echo esc_url_raw(Factory::getApplication('wptm')->getAjaxUrl()); ?>";
        var $wptm_main_config = $('#wptm-main-config');

        $wptm_main_config.find('.date_formats').closest('.control-group').css('margin-top', '25px');

        $("#wptm-tabs-config .nav-tab").click(function(e) {
            e.preventDefault();
            $("#wptm-tabs-config .nav-tab").removeClass('active');
            id_tab = $(this).data('tab-id');
            $("#tab-"+ id_tab).addClass('active');
            $("#wptm-tabs-content-config .tab-pane").removeClass('active');
            $("#wptm-"+ id_tab + '-config').addClass('active');
            $("#wptm-theme-"+ id_tab ).addClass('active');
            document.cookie = 'active_tab='+id_tab ;
        })

        function setTabFromCookie() {
            active_tab = getCookie('active_tab');
            if(active_tab != "") {
                $("#tab-"+ active_tab).click();
            }
        }

        function getCookie(cname) {
            var name = cname + "=";
            var ca = document.cookie.split(';');
            for(var i=0; i<ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0)==' ') c = c.substring(1);
                if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
            }
            return "";
        }

        setTabFromCookie();
        if($("input[name=dropboxKey]").val() != '' && $("input[name=dropboxSecret]").val() != '' ){
            $('#dropboxAuthor + .help-block').html('');
        }
        else{
            $("#dropboxAuthor").attr('type','hidden');
        }

        $('#wptm-main-config form.wptmparams').submit(function(event) {
            event.preventDefault();
            var url = wptm_ajaxurl + "task=config.saveconfig";
            var jsonVar = {};
            $('#wptm-main-config').find('.control-group .input-block-level').each(function (i, e) {
                jsonVar[$(this).attr('name')] = $(this).val();
            });
            jsonVar['joomunited_nonce_field'] = $('#joomunited_nonce_field').val();
            $.ajax({
                url: url,
                dataType: "json",
                type: "POST",
                data: jsonVar,
                success: function (datas) {
                    if (datas.response === true) {
                        $('#message p').html('<?php esc_html_e('Setting have been saved!', 'wptm'); ?>');
                    } else {
                        $('#message p').html(datas.response);
                    }
                    $('#message').fadeIn(200);

                    $('#message .notice-dismiss').click(function () {
                        $('#message').fadeOut(500);
                    });
                },
                error: function (jqxhr, textStatus, error) {
                    bootbox.alert(textStatus + " : " + error);
                }
            });
        });
    });
</script>