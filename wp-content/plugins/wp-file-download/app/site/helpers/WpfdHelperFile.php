<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\Model;

//-- No direct access
defined('ABSPATH') || die();

/**
 * Class WpfdHelperFile
 */
class WpfdHelperFile
{
    /**
     * Convert bytes to size
     *
     * @param integer $bytes     Bytes
     * @param integer $precision Decimal fraction
     *
     * @return string
     */
    public static function bytesToSize($bytes, $precision = 2)
    {
        $sz     = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        $factor = floor((strlen($bytes) - 1) / 3);
        // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText -- This is not problem
        return sprintf('%.' . $precision . 'f', $bytes / pow(1024, $factor)) . ' ' . esc_html__($sz[$factor], 'wpfd');
    }

    /**
     * Get preview url
     *
     * @param string  $id    File id
     * @param integer $catid Category id
     * @param string  $token Token key
     *
     * @return string
     */
    public static function getViewerUrl($id, $catid, $token = '')
    {
        $app = Application::getInstance('Wpfd');
        $url = wpfd_sanitize_ajax_url($app->getAjaxUrl()) . 'task=file.download&wpfd_category_id=' . $catid . '&wpfd_file_id=';
        $url .= $id . '&token=' . $token . '&preview=1';
        /**
         * Filter to change preview service url
         *
         * @param string Preview url with %s placeholder for url
         *
         * @return string
         */
        $previewServiceUrl = apply_filters('wpfd_preview_service_url', 'https://docs.google.com/viewer?url=%s&embedded=true');
        /**
         * Filter to change preview url
         *
         * @param string Output url
         * @param string Preview url with %s placeholder for file encoded url
         * @param string Ajax Url to preview file
         *
         * @return string
         */
        return apply_filters('wpfd_preview_url', sprintf($previewServiceUrl, urlencode($url)), $previewServiceUrl, $url);
    }

    /**
     * Get url to open pdf in browser
     *
     * @param string  $id    File id
     * @param integer $catid Category id
     * @param string  $token Token key
     *
     * @return string
     */
    public static function getPdfUrl($id, $catid, $token = '')
    {
        $app = Application::getInstance('Wpfd');
        $url = wpfd_sanitize_ajax_url($app->getAjaxUrl()) . 'task=file.download&wpfd_category_id=' . $catid . '&wpfd_file_id=';
        $url .= $id . '&token=' . $token . '';

        return $url;
    }

    /**
     * Get media viewer url
     *
     * @param string  $id    File id
     * @param integer $catid Category id
     * @param string  $ext   Extension
     *
     * @return string
     */
    public static function getMediaViewerUrl($id, $catid, $ext = '')
    {
        $app = Application::getInstance('Wpfd');

        $imagesType = array('jpg', 'png', 'gif', 'jpeg', 'jpe', 'bmp', 'ico', 'tiff', 'tif', 'svg', 'svgz');
        $videoType  = array(
            'mp4',
            'mpeg',
            'mpe',
            'mpg',
            'mov',
            'qt',
            'rv',
            'avi',
            'movie',
            'flv',
            'webm',
            'ogv'
        );//,'3gp'
        $audioType  = array(
            'mid',
            'midi',
            'mp2',
            'mp3',
            'mpga',
            'ram',
            'rm',
            'rpm',
            'ra',
            'wav'
        );  // ,'aif','aifc','aiff'
        if (in_array($ext, $imagesType)) {
            $type = 'image';
        } elseif (in_array($ext, $videoType)) {
            $type = 'video';
        } elseif (in_array($ext, $audioType)) {
            $type = 'audio';
        } else {
            $type = '';
        }

        $return = wpfd_sanitize_ajax_url($app->getAjaxUrl()) . 'task=frontviewer.display&view=frontviewer&id=' . $id . '&catid=';

        return $return . $catid . '&type=' . $type . '&ext=' . $ext;
    }

    /**
     * Check if it is media file
     *
     * @param string $ext Extension
     *
     * @return boolean
     */
    public static function isMediaFile($ext)
    {
        $media_arr = array(
            'mid',
            'midi',
            'mp2',
            'mp3',
            'mpga',
            'ram',
            'rm',
            'rpm',
            'ra',
            'wav', //,'aif','aifc','aiff'
            'mp4',
            'mpeg',
            'mpe',
            'mpg',
            'mov',
            'qt',
            'rv',
            'avi',
            'movie',
            'flv',
            'webm',
            'ogv', //'3gp',
            'jpg',
            'png',
            'gif',
            'jpeg',
            'jpe',
            'bmp',
            'ico',
            'tiff',
            'tif',
            'svg',
            'svgz'
        );
        if (in_array(strtolower($ext), $media_arr)) {
            return true;
        }

        return false;
    }


    /**
     * Get mime type
     *
     * @param string $ext Extension
     *
     * @return string
     */
    public static function mimeType($ext)
    {
        $mime_types = array(
            //flash
            'swf'   => 'application/x-shockwave-flash',
            'flv'   => 'video/x-flv',
            // images
            'png'   => 'image/png',
            'jpe'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'jpg'   => 'image/jpeg',
            'gif'   => 'image/gif',
            'bmp'   => 'image/bmp',
            'ico'   => 'image/vnd.microsoft.icon',
            'tiff'  => 'image/tiff',
            'tif'   => 'image/tiff',
            'svg'   => 'image/svg+xml',
            'svgz'  => 'image/svg+xml',

            // audio
            'mid'   => 'audio/midi',
            'midi'  => 'audio/midi',
            'mp2'   => 'audio/mpeg',
            'mp3'   => 'audio/mpeg',
            'mpga'  => 'audio/mpeg',
            'aif'   => 'audio/x-aiff',
            'aifc'  => 'audio/x-aiff',
            'aiff'  => 'audio/x-aiff',
            'ram'   => 'audio/x-pn-realaudio',
            'rm'    => 'audio/x-pn-realaudio',
            'rpm'   => 'audio/x-pn-realaudio-plugin',
            'ra'    => 'audio/x-realaudio',
            'wav'   => 'audio/x-wav',
            'wma'   => 'audio/wma',

            //Video
            'mp4'   => 'video/mp4',
            'mpeg'  => 'video/mpeg',
            'mpe'   => 'video/mpeg',
            'mpg'   => 'video/mpeg',
            'mov'   => 'video/quicktime',
            'qt'    => 'video/quicktime',
            'rv'    => 'video/vnd.rn-realvideo',
            'avi'   => 'video/x-msvideo',
            'movie' => 'video/x-sgi-movie',
            '3gp'   => 'video/3gpp',
            'webm'  => 'video/webm',
            'ogv'   => 'video/ogg',
            //doc
            'pdf'   => 'application/pdf'
        );

        if (array_key_exists(strtolower($ext), $mime_types)) {
            return $mime_types[strtolower($ext)];
        } else {
            return 'application/octet-stream';
        }
    }

    /**
     * Get mime type
     *
     * @param string $ext     Extenstion
     * @param string $fileExt Extenstion
     *
     * @return string
     */
    public static function isCorrectMimeType($ext, $fileExt)
    {
        $ext = strtolower($ext);
        if (empty($ext)) {
            return false;
        }

        $mime_types_map = array(
            'application/x-msdownload' => 'exe',
            'application/x-dosexec'    => 'exe'
        );

        if (isset($mime_types_map[$fileExt])) {
            if ($mime_types_map[$fileExt] === $ext) {
                return true;
            } else {
                return false;
            }
        }

        return true;
    }


    /**
     * Check mime file type
     *
     * @param string $file File
     *
     * @return boolean
     */
    public static function checkMimeType($file)
    {
        if (!function_exists('finfo_open') || !function_exists('finfo_file')) {
            return true;
        }
        $ext          = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $file_info    = finfo_open(FILEINFO_MIME_TYPE);
        $mimeFileInfo = finfo_file($file_info, $file);
        finfo_close($file_info);

        // Always return true for mising mimetype
        // Some server or php version always return application/octet-stream
        if (isset($mimeFileInfo) && $mimeFileInfo !== null) {
            return self::isCorrectMimeType($ext, $mimeFileInfo);
        }

        return true;
    }

    /**
     * Search assets
     *
     * @return void
     */
    public static function wpfdAssets()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style(
            'jquery-ui-1.9.2',
            plugins_url('app/admin/assets/css/ui-lightness/jquery-ui-1.9.2.custom.min.css', WPFD_PLUGIN_FILE)
        );
        wp_enqueue_style('dashicons');
        wp_enqueue_script(
            'jquery-ui-1.11.4',
            plugins_url('app/admin/assets/js/jquery-ui-1.11.4.custom.min.js', WPFD_PLUGIN_FILE)
        );
        wp_enqueue_script('wpfd-colorbox', plugins_url('app/site/assets/js/jquery.colorbox-min.js', WPFD_PLUGIN_FILE));
        wp_enqueue_script(
            'wpfd-colorbox-init',
            plugins_url('app/site/assets/js/colorbox.init.js', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
        wp_enqueue_script(
            'wpfd-videojs',
            plugins_url('app/site/assets/js/video.js', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
        wp_localize_script(
            'wpfd-colorbox',
            'wpfdcolorbox',
            array('wpfdajaxurl' => wpfd_sanitize_ajax_url(Application::getInstance('Wpfd')->getAjaxUrl()))
        );

        wp_enqueue_style(
            'wpfd-videojs',
            plugins_url('app/site/assets/css/video-js.css', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
        wp_enqueue_style(
            'wpfd-colorbox',
            plugins_url('app/site/assets/css/colorbox.css', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
        wp_enqueue_style(
            'wpfd-viewer',
            plugins_url('app/site/assets/css/viewer.css', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
    }

    /**
     * Search access
     *
     * @return void
     */
    public static function wpfdAssetsSearch()
    {
        wp_enqueue_style('wpfd-jquery-tagit', plugins_url('app/admin/assets/css/jquery.tagit.css', WPFD_PLUGIN_FILE));
        wp_enqueue_style(
            'wpfd-datetimepicker',
            plugins_url('app/site/assets/css/jquery.datetimepicker.css', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
        wp_enqueue_style(
            'wpfd-search_filter',
            plugins_url('app/site/assets/css/search_filter.css', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );

        wp_enqueue_script('wpfd-jquery-tagit', plugins_url('app/admin/assets/js/jquery.tagit.js', WPFD_PLUGIN_FILE));
        wp_enqueue_script(
            'wpfd-datetimepicker',
            plugins_url('app/site/assets/js/jquery.datetimepicker.js', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
        wp_enqueue_script(
            'wpfd-search_filter',
            plugins_url('app/site/assets/js/search_filter.js', WPFD_PLUGIN_FILE),
            array(),
            WPFD_VERSION
        );
        Application::getInstance('Wpfd');
        $modelConfig  = Model::getInstance('config');
        $searchconfig = $modelConfig->getSearchConfig();
        wp_localize_script(
            'wpfd-search_filter',
            'wpfdvars',
            array('basejUrl' => home_url('?page_id=' . $searchconfig['search_page']))
        );
    }

    /**
     * Download Large File
     *
     * @param string  $filePath         File path
     * @param boolean $deleteWhenFinish Delete file when finish
     *
     * @return void
     */
    public static function downloadLargeFile($filePath, $deleteWhenFinish = false)
    {
        // phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged,WordPress.Security.EscapeOutput.OutputNotEscaped -- not print any error to file content
        @ini_set('error_reporting', E_ALL & ~E_NOTICE);
        @ini_set('zlib.output_compression', 'Off');

        $chunksize = 1 * (1024 * 1024);
        if (file_exists($filePath)) {
            @set_time_limit(0);
            $size = intval(sprintf('%u', filesize($filePath)));
            if ($size > $chunksize) {
                $handle = fopen($filePath, 'rb');
                while (!feof($handle)) {
                    print(@fread($handle, $chunksize));
                    ob_flush();
                    flush();
                }
                fclose($handle);
            } else {
                readfile($filePath);
            }
            if ($deleteWhenFinish) {
                unlink($filePath);
            }
            exit;
        } else {
            exit(sprintf(esc_html('File "%s" does not exist!'), $filePath));
        }
        // phpcs:enable
    }

    /**
     * Send Download File to the browser
     *
     * @param string  $filePath         Absolute path to the file
     * @param string  $fileName         File name return to Browser
     * @param string  $fileExt          File extension for check it mime
     * @param boolean $preview          Is preview
     * @param boolean $openPdfInBrowser Is open in browser
     *
     *
     * Copyright 2012 Armand Niculescu - media-division.com
     * Redistribution and use in source and binary forms, with or without modification,
     * are permitted provided that the following conditions are met:
     * 1. Redistributions of source code must retain the above copyright notice,
     * this list of conditions and the following disclaimer.
     * 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the
     * following disclaimer in the documentation and/or other materials provided with the distribution.
     * THIS SOFTWARE IS PROVIDED BY THE FREEBSD PROJECT "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING,
     * BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
     * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE FREEBSD PROJECT OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,
     * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT
     * OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
     * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
     * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     *
     * @return void
     */
    public static function sendDownload($filePath, $fileName, $fileExt, $preview = false, $openPdfInBrowser = false)
    {
        // phpcs:disable Generic.PHP.NoSilencedErrors.Discouraged,WordPress.Security.EscapeOutput.OutputNotEscaped,WordPress.Security.NonceVerification.NoNonceVerification -- not print any error to file content, output is file content, $_REQUEST['stream'] is checking condition
        while (ob_get_level()) {
            ob_end_clean();
        }
        @ini_set('error_reporting', E_ALL & ~E_NOTICE);
        @ini_set('zlib.output_compression', 'Off');
        $isAttachment = isset($_REQUEST['stream']) ? false : true;
        if ($openPdfInBrowser && strtolower($fileExt) === 'pdf' && $preview) {
            $isAttachment = false;
        }
        // make sure the file exists on server
        if (is_file($filePath)) {
            $fileSize    = filesize($filePath);
            $fileHandler = @fopen($filePath, 'rb');
            if ($fileHandler) {
                // set the headers, prevent caching
                header('Pragma: public');
                header('Expires: -1');
                header('Cache-Control: public, must-revalidate, post-check=0, pre-check=0');
                header('Content-Disposition: attachment; filename="' . $fileName . '"');
                // set appropriate headers for attachment or streamed file
                if ($isAttachment) {
                    header('Content-Disposition: attachment; filename="' . $fileName . '"');
                } else {
                    header('Content-Disposition: inline; filename="' . $fileName . '"');
                }
                header('Content-Type: ' . self::mimeType($fileExt));

                // check if http_range is sent by browser (or download manager)
                // todo: Apply multiple ranges
                if (isset($_SERVER['HTTP_RANGE'])) {
                    list($sizeUnit, $rangeOrig) = explode('=', $_SERVER['HTTP_RANGE'], 2);
                    if ($sizeUnit === 'bytes') {
                        // multiple ranges could be specified at the same time,
                        // but for simplicity only serve the first range
                        // http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
                        list($range, $extraRanges) = explode(',', $rangeOrig, 2);
                    } else {
                        $range = '';
                        header('HTTP/1.1 416 Requested Range Not Satisfiable');
                        exit;
                    }
                } else {
                    $range = '';
                }
                // figure out download piece from range (if set)
                list($seekStart, $seekEnd) = explode('-', $range, 2);
                // set start and end based on range (if set), else set defaults
                // also check for invalid ranges.
                $seekEnd   = (empty($seekEnd)) ? ($fileSize - 1) : min(abs(intval($seekEnd)), ($fileSize - 1));
                $seekStart = (empty($seekStart) || $seekEnd < abs(intval($seekStart))) ?
                    0 : max(abs(intval($seekStart)), 0);
                // Only send partial content header if downloading a piece of the file (IE workaround)
                if ($seekStart > 0 || $seekEnd < ($fileSize - 1)) {
                    header('HTTP/1.1 206 Partial Content');
                    header('Content-Range: bytes ' . $seekStart . '-' . $seekEnd . '/' . $fileSize);
                    header('Content-Length: ' . ($seekEnd - $seekStart + 1));
                } else {
                    header('Content-Length: ' . $fileSize);
                }
                header('Accept-Ranges: bytes');
                @set_time_limit(0);
                fseek($fileHandler, $seekStart);
                while (!feof($fileHandler)) {
                    print(@fread($fileHandler, 1024 * 8));
                    @ob_flush();
                    flush();
                    if (connection_status() !== 0) {
                        @fclose($fileHandler);
                        exit;
                    }
                }
                // File save was a success
                @fclose($fileHandler);
                exit;
            } else {
                // File couldn't be opened
                header('HTTP/1.0 500 Internal Server Error');
                exit;
            }
        } else {
            // File does not exist
            header('HTTP/1.0 404 Not Found');
            exit;
        }
        // phpcs:enable
    }

    /**
     * Santize File Name for download
     *
     * @param string $fileName File name
     *
     * @return string
     */
    public static function santizeFileName($fileName)
    {
        if (function_exists('sanitize_file_name')) {
            return sanitize_file_name($fileName);
        } elseif (function_exists('mb_ereg_replace')) {
            return mb_ereg_replace('([^\w\s\d\-_~,;\[\]\(\).])', '', $fileName);
        } else {
            return preg_replace('([^\w\s\d\-_~,;\[\]\(\).])', '', $fileName);
        }
    }

    /**
     * Check access for single file
     *
     * @param array $file File
     *
     * @return boolean
     */
    public static function checkAccess($file)
    {
        $user = wp_get_current_user();
        Application::getInstance('Wpfd');
        //check access
        $modelCategory = Model::getInstance('category');
        $category      = $modelCategory->getCategory($file['catid']);

        if (empty($category) || is_wp_error($category)) {
            return false;
        }

        if ((int) $category->access === 1) {
            $roles = array();
            foreach ($user->roles as $role) {
                $roles[] = strtolower($role);
            }
            $allows        = array_intersect($roles, $category->roles);
            $allows_single = false;

            if (isset($category->params['canview']) && $category->params['canview'] === '') {
                $category->params['canview'] = 0;
            }
            // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
            if (isset($category->params['canview']) && ((int) $category->params['canview'] !== 0) && is_countable($category->roles) &&
                !count($category->roles)) {
                if ((int) $category->params['canview'] === (int) $user->ID) {
                    $allows_single = true;
                }
                if ($allows_single === false) {
                    return false;
                }
            // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
            } elseif (isset($category->params['canview']) && ((int) $category->params['canview'] !== 0) && is_countable($category->roles) &&
                      count($category->roles)) {
                if ((int) $category->params['canview'] === (int) $user->ID) {
                    $allows_single = true;
                }
                if ($allows_single !== true || empty($allows)) {
                    return false;
                }
            } else {
                if (empty($allows)) {
                    return false;
                }
            }
        }

        return true;
    }
}
