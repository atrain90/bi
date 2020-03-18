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
     * Get all categories by parent category
     *
     * @param integer $idcategory Category id
     *
     * @return array
     */
    public function getCategories($idcategory)
    {
        $user = wp_get_current_user();
        $roles = array();
        foreach ($user->roles as $role) {
            $roles[] = strtolower($role);
        }
        $result = array();
        $categories = get_terms(
            'wpfd-category',
            'orderby=term_group&hierarchical=1&hide_empty=0&parent=' . $idcategory
        );
        if ($categories) {
            foreach ($categories as $category) {
                $category->name = html_entity_decode($category->name);
                $term_meta      = get_option('taxonomy_' . $category->term_id);

                $defaultParams = array(
                    'order' => 'desc',
                    'orderby' => 'title',
                    'roles' => array(),
                    'private' => 0
                );
                /**
                 * Filters allow setup default params for new category
                 *
                 * @param array Default values: order, orderby, roles, private
                 *
                 * @ignore
                 *
                 * @return array
                 */
                $defaultParams = apply_filters('wpfd_default_category_params', $defaultParams);

                $cat_roles      = isset($term_meta['roles']) ? (array) $term_meta['roles'] : $defaultParams['roles'];
                $cat_access     = isset($term_meta['access']) ? (int) $term_meta['access'] : $defaultParams['private'];
                $params         = json_decode($category->description, true);
                $allows_single  = false;

                if (isset($params['canview']) && $params['canview'] !== '') {
                    if (((int)$params['canview'] !== 0) && (int) $params['canview'] === $user->ID) {
                        $allows_single = true;
                    }
                }

                if ((int) $cat_access === 1) {
                    $allows = array_intersect($roles, $cat_roles);
                    if ($allows || $allows_single) {
                        $result[] = $category;
                    }
                } else {
                    $result[] = $category;
                }
            }
        }

        return stripslashes_deep($result);
    }

    /**
     * Count sub categories
     *
     * @param integer $idcategory Parent category id
     *
     * @return array|integer|WP_Error
     */
    public function getSubCategoriesCount($idcategory)
    {
        $count = wp_count_terms(
            'wpfd-category',
            'orderby=term_group&hierarchical=1&hide_empty=0&parent=' . $idcategory
        );
        return $count;
    }

    /**
     * Get level categories
     *
     * @return array
     */
    public function getLevelCategories()
    {

        $results       = array();
        $root          = new stdClass();
        $root->level   = 0;
        $root->term_id = 0;
        $this->getCategoriesRecursive($root, $results);

        $user  = wp_get_current_user();
        $roles = array();
        foreach ($user->roles as $role) {
            $roles[] = strtolower($role);
        }
        if ($results) {
            foreach ($results as $key => $category) {
                $cat = get_term($category->term_id, 'wpfd-category');
                $params = array();
                if ($cat->description !== '') {
                    $params = json_decode($cat->description, true);
                }

                $term_meta = get_option('taxonomy_' . $category->term_id);

                $defaultParams = array(
                    'order' => 'desc',
                    'orderby' => 'title',
                    'roles' => array(),
                    'private' => 0
                );
                /**
                 * Filters allow setup default params for new category
                 *
                 * @param array Default values: order, orderby, roles, private
                 *
                 * @ignore
                 *
                 * @return array
                 */
                $defaultParams = apply_filters('wpfd_default_category_params', $defaultParams);

                $cat_roles = isset($term_meta['roles']) ? (array)$term_meta['roles'] : $defaultParams['roles'];
                $cat_access = isset($term_meta['access']) ? (int)$term_meta['access'] : $defaultParams['private'];
                if (isset($params['canview']) && ($params['canview'] === '')) {
                    $params['canview'] = 0;
                }
                if ((int) $cat_access === 1) {
                    $allows = array_intersect($roles, $cat_roles);
                    // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
                    if (isset($params['canview']) && (int) $params['canview'] !== 0 && is_countable($cat_roles) && !count($cat_roles)) {
                        if ((int) $params['canview'] !== $user->ID) {
                            unset($results[$key]);
                            continue;
                        }
                    } elseif (isset($params['canview']) && (int) $params['canview'] !== 0 && is_countable($cat_roles) && count($cat_roles)) { // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
                        if (!((int)$params['canview'] === $user->ID || !empty($allows))) {
                            unset($results[$key]);
                            continue;
                        }
                    } else {
                        if (empty($allows)) {
                            unset($results[$key]);
                            continue;
                        }
                    }
                }
                $results[$key] = apply_filters('wpfd_level_category', $category);
                $results[$key] = apply_filters('wpfd_level_category_dropbox', $category);
                $results[$key] = apply_filters('wpfd_level_category_onedrive', $category);
            }
        }
        return $results;
    }

    /**
     * Get categories recursive
     *
     * @param object $cat     Category
     * @param array  $results Results
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
                $results[] = $category;
                $this->getCategoriesRecursive($category, $results);
            }
        }
    }

    /**
     * Get child categories
     *
     * @param integer $catid Categoryid
     *
     * @return array
     */
    public function getChildCategories($catid)
    {
        $results = array();

        $categories = get_terms('wpfd-category', 'orderby=term_group&hierarchical=1&hide_empty=0&parent=' . $catid);
        if ($categories) {
            foreach ($categories as $category) {
                $results[] = $category;
                $this->getCategoriesRecursive($category, $results);
            }
        }
        return $results;
    }

    /**
     * Get all parents categories
     *
     * @param integer $catid      Categoryid
     * @param integer $displaycat Display cat
     *
     * @return array
     */
    public function getParentsCat($catid, $displaycat)
    {
        $results = array();
        $results[] = $catid;
        $this->getParentCat($catid, $results, $displaycat);
        return $results;
    }

    /**
     * Get parents categories
     *
     * @param integer $catid      Categoryid
     * @param array   $results    Results
     * @param integer $displaycat Display cat
     *
     * @return void
     */
    public function getParentCat($catid, &$results, $displaycat)
    {

        if ((int) $catid !== 0) {
            $cat = get_term($catid, 'wpfd-category');
            if ($cat->parent !== 0 && $cat->parent !== (int) $displaycat) {
                $results[] = $cat->parent;
                $this->getParentCat($cat->parent, $results, $displaycat);
            }
        }
    }

    /**
     * Get categories Hỉearchy
     *
     * @param integer $parent Category parent Id
     *
     * @return array
     */
    public function getCategoriesHierarchy($parent = 0)
    {
        $taxonomy   = 'wpfd-category';
        $categories = get_terms(array(
            'taxonomy'     => $taxonomy,
            'orderby'      => 'term_group',
            'hierarchical' => 1,
            'hide_empty'   => 0,
            'parent'       => $parent
        ));
        $user = wp_get_current_user();
        $roles = array();
        foreach ($user->roles as $role) {
            $roles[] = strtolower($role);
        }
        $result = array();
        if ($categories) {
            foreach ($categories as $category) {
                $category->name = html_entity_decode($category->name);
                $term_meta = get_option('taxonomy_' . $category->term_id);

                $defaultParams = array(
                    'order' => 'desc',
                    'orderby' => 'title',
                    'roles' => array(),
                    'private' => 0
                );
                /**
                 * Filters allow setup default params for new category
                 *
                 * @param array Default values: order, orderby, roles, private
                 *
                 * @ignore
                 *
                 * @return array
                 */
                $defaultParams = apply_filters('wpfd_default_category_params', $defaultParams);

                $cat_roles = isset($term_meta['roles']) ? (array)$term_meta['roles'] : $defaultParams['roles'];
                $cat_access = isset($term_meta['access']) ? (int)$term_meta['access'] : $defaultParams['private'];
                $params = json_decode($category->description, true);
                $allows_single = false;

                if (isset($params['canview']) && $params['canview'] !== '') {
                    if (((int) $params['canview'] !== 0) && (int) $params['canview'] === (int) $user->ID) {
                        $allows_single = true;
                    }
                }

                if ((int) $cat_access === 1) {
                    $allows = array_intersect($roles, $cat_roles);
                    if ($allows || $allows_single) {
                        $result[] = $category;
                    }
                } else {
                    $result[] = $category;
                }
            }
        }

        $terms = stripslashes_deep($result);
        $results = array();

        if (!empty($terms)) {
            foreach ($terms as $term) {
                $term->children = $this->getCategoriesHierarchy($term->term_id);
                $results[$term->term_id] = $term;
            }
        }
        return $results;
    }
}
