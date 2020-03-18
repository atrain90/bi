<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */

use Joomunited\WPFramework\v1_0_5\Controller;
use Joomunited\WPFramework\v1_0_5\Utilities;

defined('ABSPATH') || die();

/**
 * Class WpfdControllerCategories
 */
class WpfdControllerCategories extends Controller
{
    /**
     * Get subs categories
     *
     * @return void
     */
    public function getSubs()
    {

        $modelCats = $this->getModel('categories');
        $cats      = $modelCats->getCategories(Utilities::getInt('dir'));
        // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
        if (is_countable($cats) && count($cats)) {
            foreach ($cats as $cat) {
                $cat->count_child = $modelCats->getSubCategoriesCount($cat->term_id);
            }
        }
        echo json_encode($cats);
        die();
    }

    /**
     * Get Categories
     *
     * @return void
     */
    public function getCats()
    {
        $term  = array();
        $catId = Utilities::getInt('dir');
        if ($catId === 0) {
            wp_send_json(array(
                'success' => false,
                'message' => esc_html__('Missing parameter in url', 'wpfd')
            ));
        }
        $catModel             = $this->getModel('categories');
        $currentCat           = get_term($catId, 'wpfd-category');
        $currentCat->children = array();
        if (!is_wp_error($currentCat)) {
            $hierarchy = $catModel->getCategoriesHierarchy($catId);
            // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
            if (is_countable($hierarchy) && ($hierarchy)) {
                $currentCat->children = $hierarchy;
            }
            $term[$catId] = $currentCat;
        }
        // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
        if (is_countable($term) && count($term) > 0) {
            wp_send_json(array(
                'success' => true,
                'data'    => $term
            ));
        } else {
            wp_send_json(array(
                'success' => false,
                'message' => esc_html__('No category found', 'wpfd')
            ));
        }
    }

    /**
     * Get parents categories
     *
     * @return void
     */
    public function getParentsCats()
    {
        $modelCats = $this->getModel('categories');
        $cats      = $modelCats->getParentsCat(Utilities::getInt('id'), Utilities::getInt('displaycatid'));
        $cats      = array_reverse($cats);
        echo json_encode($cats);
        die();
    }
}
