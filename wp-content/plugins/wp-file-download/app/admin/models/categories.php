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
 * Class WpfdModelCategories
 */
class WpfdModelCategories extends Model
{

    /**
     * Get categories
     *
     * @return array
     */
    public function getCategories()
    {
        $results       = array();
        $root          = new stdClass();
        $root->level   = -1;
        $root->term_id = 0;
        $this->getCategoriesRecursive($root, $results);

        $results = $this->extractOwnCategories($results);

        return $results;
    }

    /**
     * Get categories recursive
     *
     * @param WP_Taxonomy $cat     Category
     * @param array       $results Return results
     *
     * @return void
     */
    public function getCategoriesRecursive($cat, &$results)
    {
        if (!is_array($results)) {
            $results = array();
        }
        $categories = get_terms(
            'wpfd-category',
            'orderby=term_group&hierarchical=1&hide_empty=0&parent=' . $cat->term_id
        );
        if ($categories) {
            foreach ($categories as $category) {
                $category->level = $cat->level + 1;
                $results[]       = $category;
                $this->getCategoriesRecursive($category, $results);
            }
        }
    }

    /**
     * Get sub categories by parent category
     *
     * @param integer $parent Category parent id
     *
     * @return array|integer|WP_Error
     */
    public function getSubCategories($parent)
    {
        $categories = get_terms('wpfd-category', 'orderby=term_group&hierarchical=1&hide_empty=0&parent=' . $parent);

        return $categories;
    }

    /**
     * Get categories old
     *
     * @return boolean|mixed
     */
    public function getCategoriesOld()
    {
        global $wpdb;
        $query = 'SELECT c.* FROM ' . $wpdb->prefix . 'wpfd_categories as c WHERE c.level >0 ORDER BY c.lft ASC';
        // phpcs:ignore WordPress.Security.EscapeOutput.NotPrepared -- Select query only
        $result = $wpdb->query($query);
        if ($result === false) {
            return false;
        }

        // phpcs:ignore WordPress.Security.EscapeOutput.NotPrepared -- Select query only
        return stripslashes_deep($wpdb->get_results($query, OBJECT));
    }

    /**
     * Extract categories for the user having own category permission
     *
     * @param array $items Categories
     *
     * @return array
     */
    public function extractOwnCategories($items)
    {
        $user_id         = get_current_user_id();
        $is_edit_all     = false;
        $user_categories = array();
        if (user_can($user_id, 'wpfd_edit_category')) {
            // Allows edit all categories
            $is_edit_all = true;
        } else {
            $user_categories = (array) get_user_meta($user_id, 'wpfd_user_categories', true);
        }
        if (!empty($items) && !$is_edit_all) {
            $parent             = $this->getParentIds($items, $user_categories);
            $visible_categories = $user_categories;

            while (!empty($parent)) {
                $visible_categories = array_merge($parent, $visible_categories);
                $parent             = $this->getParentIds($items, $parent);
            }
            foreach ($items as $key_cat => $cat) {
                if (!in_array($cat->term_id, $visible_categories)) {
                    unset($items[$key_cat]);
                } elseif (!in_array($cat->term_id, $user_categories)) {
                    $items[$key_cat]->disable = true;
                }
            }
            //reset index array
            $items = array_values($items);
        }

        return $items;
    }

    /**
     * Get parent categories
     *
     * @param array $items           Categories
     * @param array $user_categories User categories
     *
     * @return array
     */
    public function getParentIds($items, $user_categories)
    {
        $parent = array();
        foreach ($items as $key_cat => $cat) {
            if (in_array($cat->term_id, $user_categories)) {
                if ($cat->parent && !in_array($cat->parent, $parent)) {
                    $parent[] = $cat->parent;
                }
            }
        }

        return $parent;
    }
}
