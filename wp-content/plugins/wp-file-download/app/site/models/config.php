<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Model;

defined('ABSPATH') || die();

/**
 * Class WpfdModelConfig
 */
class WpfdModelConfig extends Model
{
    /**
     * Get global configuration
     *
     * @return array
     */
    public function getGlobalConfig()
    {
        $allowedext_str                           = '7z,ace,bz2,dmg,gz,rar,tgz,zip,csv,doc,docx,html,key,keynote,odp,ods,odt,pages,pdf,pps,'
                                                    . 'ppt,pptx,rtf,tex,txt,xls,xlsx,xml,bmp,exif,gif,ico,jpeg,jpg,png,psd,tif,tiff,aac,aif,aiff,alac,amr,au,'
                                                    . 'cdda,flac,m3u,m4a,m4p,mid,mp3,mp4,mpa,ogg,pac,ra,wav,wma,3gp,asf,avi,flv,m4v,mkv,mov,mpeg,mpg,rm,swf,'
                                                    . 'vob,wmv,css,img';
        $extension_viewer                         = 'png,jpg,pdf,ppt,doc,xls,dxf,ps,eps,xps,psd,tif,tiff,bmp,svg,pages,ai,dxf,ttf,txt,mp3,mp4';
        $defaultConfig                            = array('allowedext' => $allowedext_str);
        $defaultConfig['maxinputfile']            = 10;
        $defaultConfig['deletefiles']             = 0;
        $defaultConfig['catparameters']           = 1;
        $defaultConfig['defaultthemepercategory'] = 'default';
        $defaultConfig['date_format']             = 'd-m-Y';
        $defaultConfig['use_google_viewer']       = 'lightbox';
        $defaultConfig['extension_viewer']        = $extension_viewer;
        $defaultConfig['show_file_import']        = 0;
        $defaultConfig['uri']                     = 'download';
        $defaultConfig['rmdownloadext']           = 0;
        $defaultConfig['ga_download_tracking']    = 0;
        $defaultConfig['plain_text_search']       = 0;
        $defaultConfig['useeditor']               = 0;
        $defaultConfig['restrictfile']            = 0;
        $defaultConfig['enablewpfd']              = 0;
        $defaultConfig['shortcodecat']            = 1;
        $defaultConfig['paginationnunber']        = 100;
        $defaultConfig['open_pdf_in']             = 0;
        $defaultConfig['custom_icon']             = 1;
        $defaultConfig['download_category']       = 0;

        $config                                   = get_option('_wpfd_global_config', $defaultConfig);
        $config                                   = array_merge($defaultConfig, $config);
        return (array)$config;
    }

    /**
     * Get config of a theme
     *
     * @param string $theme_name Theme name
     *
     * @return mixed
     */
    public function getConfig($theme_name = '')
    {

        if ($theme_name !== '') {
            $theme = $theme_name;
        } else {
            $theme = get_option('_wpfd_theme', 'default');
        }
        $default_config = '{"marginleft":"10","marginright":"10", "margintop":"10", "marginbottom":"10",';
        $default_config .= '"showsize":"1","showtitle":"1","croptitle":"0","showdescription":"1","showversion":"1",';
        $default_config .= '"showhits":"1","showdownload":"1","bgdownloadlink":"#76bc58",';
        $default_config .= '"colordownloadlink":"#ffffff","showdateadd":"1","showdatemodified":"0",';
        $default_config .= '"showsubcategories":"1","showcategorytitle":"1","showbreadcrumb":"1","showfoldertree":"0"}';

        $ggd_config = '{"ggd_marginleft":"10", "ggd_marginright":"10", "ggd_margintop":"10", "ggd_marginbottom":"10",';
        $ggd_config .= '"ggd_croptitle":"0", "ggd_showsize":"1", "ggd_showtitle":"1", "ggd_showdescription":"1",';
        $ggd_config .= '"ggd_showversion":"1", "ggd_showhits":"1", "ggd_showdownload":"1",';
        $ggd_config .= '"ggd_bgdownloadlink":"#76bc58", "ggd_colordownloadlink":"#ffffff", "ggd_showdateadd":"1",';
        $ggd_config .= '"ggd_showdatemodified":"0", "ggd_showsubcategories":"1", "ggd_showcategorytitle":"1",';
        $ggd_config .= '"ggd_showbreadcrumb":"1", "ggd_showfoldertree":"0", "ggd_download_popup":"1"}';

        $table_config = '{"table_styling":"1","table_stylingmenu":"1","table_showsize":"1","table_showtitle":"1",';
        $table_config .= '"table_showdescription":"1","table_showversion":"1","table_showhits":"1",';
        $table_config .= '"table_showdownload":"1","table_croptitle":"0","table_bgdownloadlink":"#76bc58",';
        $table_config .= '"table_colordownloadlink":"#ffffff","table_showdateadd":"1","table_showdatemodified":"0",';
        $table_config .= '"table_showsubcategories":"1","table_showcategorytitle":"1","table_showbreadcrumb":"1",';
        $table_config .= '"table_showfoldertree":"0"}';

        $tree_config = '{"tree_showbgtitle":"1", "tree_showtreeborder":"1", "tree_showsize":"1", "tree_croptitle":"0",';
        $tree_config .= '"tree_showtitle":"1", "tree_showdescription":"1", "tree_showversion":"1",';
        $tree_config .= ' "tree_showhits":"1","tree_showdownload":"1", "tree_bgdownloadlink":"#76bc58",';
        $tree_config .= '"tree_colordownloadlink":"#ffffff","tree_showdateadd":"1", "tree_showdatemodified":"0",';
        $tree_config .= '"tree_showsubcategories":"1", "tree_showcategorytitle":"1", "tree_download_popup":"1"}';
        $deaults = array(
            'default' => $default_config,
            'ggd'     => $ggd_config,
            'table'   => $table_config,
            'tree'    => $tree_config
        );
        if (!isset($deaults[$theme])) {
            $deaults[$theme] = '';
        }
        $theme_params = get_option('_wpfd_' . $theme . '_config', $deaults[$theme]);
        if (is_string($theme_params)) {
            $theme_params = json_decode($theme_params, true);
        }
        return $theme_params;
    }

    /**
     * Get config for single file
     *
     * @return array
     */
    public function getFileConfig()
    {
        $defaultConfig = array(
            'singlebg'        => '#444444',
            'singlehover'     => '#888888',
            'singlefontcolor' => '#ffffff',
        );
        $config = get_option('_wpfd_global_file_config', $defaultConfig);
        return (array)$config;
    }

    /**
     * Get search config
     *
     * @return array
     */
    public function getSearchConfig()
    {
        $defaultConfig = array(
            'search_page'           => (int) get_option('_wpfd_search_page_id'),
            'plain_text_search'     => 0,
            'cat_filter'            => 1,
            'tag_filter'            => 1,
            'display_tag'           => 'searchbox',
            'create_filter'         => 1,
            'update_filter'         => 1,
            'file_per_page'         => 15,
            'include_global_search' => 1,
            'shortcode'             => '[wpfd_search]'
        );
        $config = get_option('_wpfd_global_search_config', $defaultConfig);
        return (array)$config;
    }
}
