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
 * Class WpfdModelFiles
 */
class WpfdModelFiles extends Model
{

    /**
     * Get files by ordering
     *
     * @param integer $category     Category id
     * @param string  $ordering     Ordering
     * @param string  $ordering_dir Ordering direction
     * @param array   $listIdFiles  List id files
     *
     * @return array
     */
    public function getFiles($category, $ordering = 'menu_order', $ordering_dir = 'ASC', $listIdFiles = array())
    {
        $modelCat    = $this->getInstance('category');
        $categorys   = $modelCat->getCategory($category);
        $modelConfig = $this->getInstance('config');
        $params      = $modelConfig->getGlobalConfig();
        $user        = wp_get_current_user();
        $roles       = array();
        foreach ($user->roles as $role) {
            $roles[] = strtolower($role);
        }
        $rmdownloadext = (int) WpfdBase::loadValue($params, 'rmdownloadext', 1) === 1;

        $modelTokens = Model::getInstance('tokens');
        $token       = $modelTokens->getOrCreateNew();
        if ($ordering === 'ordering') {
            $ordering = 'menu_order';
        } elseif ($ordering === 'created_time') {
            $ordering = 'date';
        } elseif ($ordering === 'modified_time') {
            $ordering = 'modified';
        }

        $args    = array(
            'posts_per_page'   => -1,
            'post_type'        => 'wpfd_file',
            'orderby'          => $ordering,
            'order'            => $ordering_dir,
            'tax_query'        => array(
                array(
                    'taxonomy'         => 'wpfd-category',
                    'terms'            => (int) $category,
                    'include_children' => false
                )
            ),
            'suppress_filters' => false
        );
        // Fix conflict plugin Go7 Pricing Table
        remove_all_filters('posts_fields');
        $results = get_posts($args);
        $files   = array();

        $viewer_type           = WpfdBase::loadValue($params, 'use_google_viewer', 'lightbox');
        $extension_viewer_list = 'pdf,ppt,doc,xls,dxf,ps,eps,xps,psd,tif,tiff,bmp,svg,pages,ai,dxf,ttf,txt,mp3,mp4';
        $extension_viewer      = explode(',', WpfdBase::loadValue($params, 'extension_viewer', $extension_viewer_list));
        $extension_viewer      = array_map('trim', $extension_viewer);
        $user                  = wp_get_current_user();
        $user_id               = $user->ID;
        $site_url              = get_site_url();
        $wpfd_lang             = '';
        global $sitepress;
        if (!empty($sitepress)) {
            $language_negotiation_type = $sitepress->get_setting('language_negotiation_type');
            if ((int) $language_negotiation_type === 1) {
                /**
                 * Filters to get current language from WP Multi Lang
                 *
                 * @return string
                 *
                 * @ignore
                 */
                $current_lang = apply_filters('wpml_current_language', null);
                $default_lang = $sitepress->get_default_language();
                $setting_urls = $sitepress->get_setting('urls');
                if (!empty($current_lang) && ($current_lang !== $default_lang)) {
                    $wpfd_lang = '/' . $current_lang;
                } elseif (($current_lang === $default_lang) && !empty($setting_urls['directory_for_default_language'])) {
                    $wpfd_lang = '/' . $current_lang;
                }
            }
        }

        foreach ($results as $result) {
            if (!empty($listIdFiles) && is_array($listIdFiles)) {
                if (!in_array($result->ID, $listIdFiles)) {
                    continue;
                }
            }
            $ob       = new stdClass();
            $metaData = get_post_meta($result->ID, '_wpfd_file_metadata', true);
            if ((int) WpfdBase::loadValue($params, 'restrictfile', 0) === 1) {
                $canview = isset($metaData['canview']) ? $metaData['canview'] : 0;
                $canview = array_map('intval', explode(',', $canview));
                if (!in_array($user_id, $canview) && !in_array(0, $canview)) {
                    continue;
                }
            }
            if (isset($file_meta) && isset($file_meta['remote_url'])) {
                $remote_url = $file_meta['remote_url'];
            } else {
                $remote_url = false;
            }

            $ob->ID            = $result->ID;
            /**
             * Filter to change file title
             *
             * @param string  File title
             * @param integer File id
             *
             * @return string
             *
             * @ignore
             */
            $ob->post_title    = apply_filters('wpfd_file_title', $result->post_title, $result->ID);
            $ob->post_name     = $result->post_name;
            $ob->ext           = isset($metaData['ext']) ? $metaData['ext'] : '';
            $ob->hits          = isset($metaData['hits']) ? (int) $metaData['hits'] : 0;
            $ob->versionNumber = isset($metaData['version']) ? $metaData['version'] : '';
            $ob->version       = '';
            $ob->description   = $result->post_excerpt;
            $ob->size          = isset($metaData['size']) ? $metaData['size'] : 0;
            $ob->created_time     = get_date_from_gmt($result->post_date_gmt);
            $ob->modified_time    = get_date_from_gmt($result->post_modified_gmt);
            $ob->created       = mysql2date(
                WpfdBase::loadValue($params, 'date_format', get_option('date_format')),
                get_date_from_gmt($result->post_date_gmt)
            );
            $ob->modified      = mysql2date(
                WpfdBase::loadValue($params, 'date_format', get_option('date_format')),
                get_date_from_gmt($result->post_modified_gmt)
            );
            $term_list            = wp_get_post_terms($result->ID, 'wpfd-category', array('fields' => 'ids'));
            $wpfd_term            = get_term($term_list[0], 'wpfd-category');
            $ob->catname          = sanitize_title($wpfd_term->name);
            $ob->cattitle         = $wpfd_term->name;
            $ob->file_custom_icon = isset($metaData['file_custom_icon']) && !empty($metaData['file_custom_icon']) ?
                $site_url . $metaData['file_custom_icon'] : '';
            if (!is_wp_error($term_list)) {
                $ob->catid = $term_list[0];
            } else {
                $ob->catid = 0;
            }

            if ($viewer_type !== 'no' &&
                in_array(strtolower($ob->ext), $extension_viewer)
                && ($remote_url === false)) {
                $ob->viewer_type = $viewer_type;
                $ob->viewerlink  = WpfdHelperFile::isMediaFile($ob->ext) ?
                    WpfdHelperFile::getMediaViewerUrl(
                        $result->ID,
                        $ob->catid,
                        $ob->ext
                    ) : WpfdHelperFile::getViewerUrl($result->ID, $ob->catid, $token);
            }

            $open_pdf_in = WpfdBase::loadValue($params, 'open_pdf_in', 0);

            if ((int) $open_pdf_in === 1 && strtolower($ob->ext) === 'pdf') {
                $ob->openpdflink = WpfdHelperFile::getPdfUrl($result->ID, $ob->catid, $token) . '&preview=1';
            }
            $config = get_option('_wpfd_global_config');
            if (empty($config) || empty($config['uri'])) {
                $seo_uri = 'download';
            } else {
                $seo_uri = $config['uri'];
            }
            $ob->seouri    = $seo_uri;
            $perlink       = get_option('permalink_structure');
            $rewrite_rules = get_option('rewrite_rules');

            if (!empty($rewrite_rules)) {
                if (strpos($perlink, 'index.php')) {
                    $linkdownload     = $site_url . $wpfd_lang . '/index.php/' . $seo_uri . '/' . $ob->catid;
                    $linkdownload     .= '/' . $ob->catname . '/' . $result->ID . '/' . $result->post_name;
                    $ob->linkdownload = $linkdownload;
                } else {
                    $linkdownload     = $site_url . $wpfd_lang . '/' . $seo_uri . '/' . $ob->catid . '/' . $ob->catname;
                    $linkdownload     .= '/' . $result->ID . '/' . $result->post_name;
                    $ob->linkdownload = $linkdownload;
                }
                if ($ob->ext && !$rmdownloadext) {
                    $ob->linkdownload .= '.' . $ob->ext;
                };
            } else {
                $linkdownload     = admin_url('admin-ajax.php') . '?juwpfisadmin=false&action=wpfd&task=file.download';
                $linkdownload     .= '&wpfd_category_id=' . $ob->catid . '&wpfd_file_id=' . $result->ID;
                $ob->linkdownload = $linkdownload;
            }
            // Crop file titles
            $ob->crop_title = WpfdBase::cropTitle($categorys->params, $categorys->params['theme'], $result->post_title);
            /**
             * Filter to change file title
             *
             * @param string  File title
             * @param integer File id
             *
             * @return string
             *
             * @ignore
             */
            $ob->crop_title = apply_filters('wpfd_file_title', $ob->crop_title, $result->ID);

            /**
             * Filter file info in front
             *
             * @param object File object
             *
             * @return object
             *
             * @ignore
             */
            $ob = apply_filters('wpfd_file_info', $ob);
            $files[]        = $ob;
        }
        wp_reset_postdata();
        $reverse = strtoupper($ordering_dir) === 'DESC' ? true : false;

        if ($ordering === 'size') {
            $files = wpfd_sort_by_property($files, 'size', 'ID', $reverse);
        } elseif ($ordering === 'version') {
            $files = wpfd_sort_by_property($files, 'version', 'ID', $reverse);
        } elseif ($ordering === 'hits') {
            $files = wpfd_sort_by_property($files, 'hits', 'ID', $reverse);
        } elseif ($ordering === 'ext') {
            $files = wpfd_sort_by_property($files, 'ext', 'ID', $reverse);
        } elseif ($ordering === 'description') {
            $files = wpfd_sort_by_property($files, 'description', 'ID', $reverse);
        }

        return $files;
    }
}
