<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Filter;
use Joomunited\WPFramework\v1_0_5\Model;
use Joomunited\WPFramework\v1_0_5\Application;

defined('ABSPATH') || die();

/**
 * Class WpfdFilter
 */
class WpfdFilter extends Filter // phpcs:ignore Squiz.Classes.ValidClassName.NotCamelCaps -- use wpfd prefix
{
    /**
     * Model for full text search
     *
     * @var mixed
     */
    protected $ftsModel;
    /**
     * Error string
     *
     * @var string
     */
    public $index_error = '';

    /**
     * Filter Load
     *
     * @return void
     */
    public function load()
    {
        add_filter('wpfd_index_file', array($this, 'wpfdInjectFileContent'), 3, 2);
    }

    /**
     * Insert Index file when indexer running
     *
     * @param array $index Chunk to be index
     * @param mixed $post  Post object
     *
     * @return array
     */
    public function wpfdInjectFileContent($index, $post)
    {

        if ($post->post_type === 'wpfd_file') {
            $app = Application::getInstance('Wpfd');

            $modelConfig = Model::getInstance('config');
            $modelFile   = Model::getInstance('file');

            $searchConfig = $modelConfig->getSearchConfig();
            $read_content = (int) $searchConfig['plain_text_search'] === 1 ? true : false;
            $file         = $modelFile->getFile($post->ID);

            if (!$file) {
                return $index;
            }

            // TODO: Need optimize for faster and more file type ppt, rtf...
            // txt pdf docx xlsx rtf pptx OK
            // Open Office OK
            // .xls (Office 2003 Format) NOT working well
            // .doc (Office 2003 Format) read WELL in english - utf8 NOT working well
            // .ppt not ok
            $arr_ext = array('doc', 'docx', 'xls', 'xlsx', 'pdf', 'txt', 'pptx', 'rtf');

            if (!isset($file['ext'])) {
                return $index;
            }
            if ($read_content === true && in_array(strtolower($file['ext']), $arr_ext) && $file['size'] < 10 * 1024 * 1024) {
                // get file Content then index it
                $path_wpfdbase = $app->getPath() . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'classes';
                $path_wpfdbase .= DIRECTORY_SEPARATOR . 'WpfdBase.php';
                require_once $path_wpfdbase;
                $filepath = WpfdBase::getFilesPath($file['catid']) . '/' . $file['file'];

                if (!class_exists('WpfdHelperDocument')) {
                    require_once $app->getPath() . '/admin/helpers/WpfdHelperDocument.php';
                }

                $document    = new WpfdHelperDocument($post->ID, $filepath);
                $contentFile = $document->getContent();
                $contentFile = str_replace(array("\r\n", "\r", "\n"), ' ', $contentFile);
                $contentFile = str_replace("\xC2\xA0", ' ', $contentFile);
                $contentFile = html_entity_decode($contentFile);

                $index['post_content'] = $contentFile;
            }
        }

        return $index;
    }
}
