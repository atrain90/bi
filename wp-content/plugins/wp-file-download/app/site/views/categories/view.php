<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\View;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WpfdViewCategories
 */
class WpfdViewCategories extends View
{
    /**
     * Display categories
     *
     * @param string $tpl Template name
     *
     * @return void
     */
    public function render($tpl = null)
    {
        $modelCats         = $this->getModel('categories');
        $modelCat          = $this->getModel('category');
        $content           = new stdClass();
        $content->category = new stdClass();

        $content->categories = $modelCats->getCategories(Utilities::getInt('id'));
        $category            = $modelCat->getCategory(Utilities::getInt('id'));
        $app                 = Application::getInstance('Wpfd');
        $path_wpfdhelper     = $app->getPath() . DIRECTORY_SEPARATOR . 'site' . DIRECTORY_SEPARATOR . 'helpers';
        $path_wpfdhelper     .= DIRECTORY_SEPARATOR . 'WpfdHelper.php';
        require_once $path_wpfdhelper;
        if (WpfdHelper::checkCategoryAccess($category)) {
            $content->category = $category;
        }
        if (Utilities::getInt('id') === Utilities::getInt('top')) {
            $content->category->parent = false;
        }
        echo wp_json_encode($content);
        die();
    }
}
