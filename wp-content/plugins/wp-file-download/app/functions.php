<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0
 */


if (!function_exists('wpfd_sort_by_property')) {
    /**
     * Sort items by property
     *
     * @param array   $items    Files list
     * @param string  $property Property type
     * @param string  $key      Sort type
     * @param boolean $reverse  Reverse type
     *
     * @return array
     */
    function wpfd_sort_by_property(array $items, $property, $key = '', $reverse = false)
    {
        $sorted = array();
        $items_bk = $items;
        foreach ($items as $item) {
            $sorted[$item->$key] = $item->$property;
            $items_bk[$item->$key] = $item;
        }
        if ($reverse) {
            arsort($sorted);
        } else {
            asort($sorted);
        }
        $results = array();
        foreach ($sorted as $key2 => $value) {
            $results[] = $items_bk[$key2];
        }
        return $results;
    }
}

if (!function_exists('wpfd_getext')) {
    /**
     * Get extension of file
     *
     * @param string $file File name
     *
     * @return boolean|string
     */
    function wpfd_getext($file)
    {
        $dot = strrpos($file, '.') + 1;
        return substr($file, $dot);
    }
}
if (!function_exists('wpfd_remote_file_size')) {
    /**
     * Get size of file with remote url
     *
     * @param string $url Input url
     *
     * @return mixed|string
     */
    function wpfd_remote_file_size($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_NOBODY => 1,
        ));
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_exec($ch);
        $clen = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);
        if (!$clen || ($clen === -1)) {
            return 'n/a';
        }
        return $clen;
    }
}

if (!function_exists('wpfd_num')) {
    /**
     * Display select pages number
     *
     * @param integer $paged Page number
     *
     * @return void
     */
    function wpfd_num($paged = 5)
    {
        ?>
        <div class="wpfd-num">
            <?php
            $p_number = array(5, 10, 15, 20, 25, 30, 50, 100, -1);
            ?>
            <div class="limit pull-right">
                <?php esc_html_e('Display #', 'wpfd'); // phpcs:ignore ?>
                <select title="" id="limit" name="limit" class="" size="1">
                    <?php
                    foreach ($p_number as $num) {
                        $selected = $num === (int)$paged ? ' selected="selected"' : '';
                        ?>
                        <option value="<?php echo $num; // phpcs:ignore ?>"
                            <?php echo $selected; // phpcs:ignore ?>><?php echo $num === -1 ? esc_html__('All', 'wpfd') : $num; ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('wpfd_select')) {
    /**
     * Render a select html
     *
     * @param array   $options  Options array
     * @param string  $name     Name
     * @param string  $select   Select
     * @param string  $attr     Attr
     * @param boolean $disabled Disable
     *
     * @return string
     */
    function wpfd_select(array $options = array(), $name = '', $select = '', $attr = '', $disabled = false)
    {
        $html = '';
        $html .= '<select';
        if ($name !== '') {
            $html .= ' name="' . esc_attr($name) . '"';
        }
        if ($attr !== '') {
            $html .= ' ' . $attr;
        }
        $html .= '>';
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $select_option = '';
                if (is_array($select)) {
                    if (in_array($key, $select)) {
                        $select_option = 'selected="selected"';
                    } elseif ((string)$key === (string)$disabled) {
                        $select_option = disabled($disabled, $key, false);
                    } else {
                        $select_option = '';
                    }
                } else {
                    if ($disabled) {
                        $select_option = disabled($disabled, $key, false);
                    } else {
                        $select_option = selected($select, $key, false);
                    }
                }
                $html .= '<option value="' . esc_attr($key) . '" ' . $select_option . '>' . $value . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
}
if (!function_exists('wpfd_pagination')) {
    /**
     * Display a custom pagination
     *
     * @param array  $args      Options args
     * @param string $form_name Form name
     *
     * @return array|string|boolean
     */
    function wpfd_pagination(array $args = array(), $form_name = '')
    {
        global $wp_query, $wp_rewrite;
        // Setting up default values based on the current URL.
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $url_parts = explode('?', $pagenum_link);
        // Get max pages and current page out of the current query, if available.
        $total = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
        $current = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        // Append the format placeholder to the base URL.
        $pagenum_link = trailingslashit($url_parts[0]) . '%_%';
        // URL base depends on permalink settings.
        $pagination_base = user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged');
        $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? $pagination_base : '?paged=%#%';

        $defaults = array(
            'base' => $pagenum_link,
            // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
            'format' => $format,
            // ?page=%#% : %#% is replaced by the page number
            'total' => $total,
            'current' => $current,
            'show_all' => false,
            'prev_next' => true,
            'prev_text' => esc_html__('&laquo; Previous', 'wpfd'),
            'next_text' => esc_html__('Next &raquo;', 'wpfd'),
            'end_size' => 1,
            'mid_size' => 2,
            'type' => 'plain',
            'add_args' => array(),
            // array of query args to add
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );

        $args = wp_parse_args($args, $defaults);
        if (!is_array($args['add_args'])) {
            $args['add_args'] = array();
        }
        // Merge additional query vars found in the original URL into 'add_args' array.
        if (isset($url_parts[1])) {
            // Find the format argument.
            $format = explode('?', str_replace('%_%', $args['format'], $args['base']));
            $format_query = isset($format[1]) ? $format[1] : '';
            wp_parse_str($format_query, $format_args);
            // Find the query args of the requested URL.
            wp_parse_str($url_parts[1], $url_query_args);
            // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
            foreach ($format_args as $format_arg => $format_arg_value) {
                unset($url_query_args[$format_arg]);
            }
            $args['add_args'] = array_merge($args['add_args'], urlencode_deep($url_query_args));
        }
        // Who knows what else people pass in $args
        $total = (int)$args['total'];
        if ($total < 2) {
            return false;
        }
        $current = (int)$args['current'];
        $end_size = (int)$args['end_size']; // Out of bounds?  Make it the default.
        if ($end_size < 1) {
            $end_size = 1;
        }
        $mid_size = (int)$args['mid_size'];
        if ($mid_size < 0) {
            $mid_size = 2;
        }
        $add_args = $args['add_args'];
        $r = '';
        $page_links = array();
        $dots = false;
        if ($args['prev_next'] && $current && 1 < $current) :
            $link = str_replace('%_%', 2 === $current ? '' : $args['format'], $args['base']);
            $link = str_replace('%#%', $current - 1, $link);
            if ($add_args) {
                $link = add_query_arg($add_args, $link);
            }
            $link .= $args['add_fragment'];
            /**
             * Filter the paginated links for the given archive pages.
             *
             * @since 3.0.0
             *
             * param string $link The paginated link URL.
             */
            $page_link = "<a class='prev page-numbers' onclick='document." . esc_attr($form_name) . '.paged.value=';
            $page_link .= ($current - 1) . '; document.' . esc_attr($form_name) . ".submit();'>" . $args['prev_text'] . '</a>';
            $page_links[] = $page_link;
        endif;
        for ($n = 1; $n <= $total; $n++) :
            if ($n === $current) :
                $page_link = "<span class='page-numbers current'>" . $args['before_page_number'];
                $page_link .= number_format_i18n($n) . $args['after_page_number'] . '</span>';
                $page_links[] = $page_link;
                $dots = true;
            else :
                if ($args['show_all'] ||
                    ($n <= $end_size || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size)
                        || $n > $total - $end_size)) :
                    $link = str_replace('%_%', 1 === $n ? '' : $args['format'], $args['base']);
                    $link = str_replace('%#%', $n, $link);
                    if ($add_args) {
                        $link = add_query_arg($add_args, $link);
                    }
                    $link .= $args['add_fragment'];
                    /**
 * This filter is documented in wp-includes/general-template.php
*/
                    $page_link = "<a class='page-numbers' onclick='document." . esc_attr($form_name) . '.paged.value=';
                    $page_link .= $n . '; document.' . esc_attr($form_name) . ".submit();'>" . $args['before_page_number'];
                    $page_link .= number_format_i18n($n) . $args['after_page_number'] . '</a>';
                    $page_links[] = $page_link;
                    $dots = true;
                elseif ($dots && !$args['show_all']) :
                    $page_links[] = '<span class="page-numbers dots">' . esc_html__('&hellip;', 'wpfd') . '</span>';
                    $dots = false;
                endif;
            endif;
        endfor;
        if ($args['prev_next'] && $current && ($current < $total || -1 === $total)) :
            $link = str_replace('%_%', $args['format'], $args['base']);
            $link = str_replace('%#%', $current + 1, $link);
            if ($add_args) {
                $link = add_query_arg($add_args, $link);
            }
            $link .= $args['add_fragment'];

            /**
 * This filter is documented in wp-includes/general-template.php
*/
            $page_link = "<a class='next page-numbers' onclick='document." . esc_attr($form_name) . '.paged.value=';
            $page_link .= ($current + 1) . '; document.' . esc_attr($form_name) . ".submit();'>" . $args['next_text'] . '</a>';
            $page_links[] = $page_link;
        endif;
        switch ($args['type']) {
            case 'array':
                return $page_links;
            case 'list':
                $r .= "<ul class='page-numbers'>\n\t<li>";
                $r .= join("</li>\n\t<li>", $page_links);
                $r .= "</li>\n</ul>\n";
                break;
            default:
                $r = join("\n", $page_links);
                break;
        }
        return $r;
    }
}

if (!function_exists('wpfd_category_pagination')) {
    /**
     * Display a custom pagination
     *
     * @param array  $args      Option args
     * @param string $form_name Form name
     *
     * @return array|string|boolean
     */
    function wpfd_category_pagination(array $args = array(), $form_name = '')
    {
        global $wp_query, $wp_rewrite;
        // Setting up default values based on the current URL.
        $pagenum_link = html_entity_decode(get_pagenum_link());
        $url_parts = explode('?', $pagenum_link);
        // Get max pages and current page out of the current query, if available.
        $total = isset($wp_query->max_num_pages) ? $wp_query->max_num_pages : 1;
        $current = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
        // Append the format placeholder to the base URL.
        $pagenum_link = trailingslashit($url_parts[0]) . '%_%';
        // URL base depends on permalink settings.
        $pagination_base = user_trailingslashit($wp_rewrite->pagination_base . '/%#%', 'paged');
        $format = $wp_rewrite->using_index_permalinks() && !strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
        $format .= $wp_rewrite->using_permalinks() ? $pagination_base : '?paged=%#%';
        $defaults = array(
            'base' => $pagenum_link,
            // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
            'format' => $format,
            // ?page=%#% : %#% is replaced by the page number
            'total' => $total,
            'current' => $current,
            'show_all' => false,
            'prev_next' => true,
            'prev_text' => esc_html__('&laquo; Previous', 'wpfd'),
            'next_text' => esc_html__('Next &raquo;', 'wpfd'),
            'end_size' => 1,
            'mid_size' => 2,
            'type' => 'plain',
            'add_args' => array(),
            // array of query args to add
            'add_fragment' => '',
            'before_page_number' => '',
            'after_page_number' => ''
        );
        $args = wp_parse_args($args, $defaults);
        if (!is_array($args['add_args'])) {
            $args['add_args'] = array();
        }
        // Merge additional query vars found in the original URL into 'add_args' array.
        if (isset($url_parts[1])) {
            // Find the format argument.
            $format = explode('?', str_replace('%_%', $args['format'], $args['base']));
            $format_query = isset($format[1]) ? $format[1] : '';
            wp_parse_str($format_query, $format_args);
            // Find the query args of the requested URL.
            wp_parse_str($url_parts[1], $url_query_args);
            // Remove the format argument from the array of query arguments, to avoid overwriting custom format.
            foreach ($format_args as $format_arg => $format_arg_value) {
                unset($url_query_args[$format_arg]);
            }
            $args['add_args'] = array_merge($args['add_args'], urlencode_deep($url_query_args));
        }
        // Who knows what else people pass in $args
        $total = (int)$args['total'];
        if ($total < 2) {
            return false;
        }
        $current = (int)$args['current'];
        $end_size = (int)$args['end_size']; // Out of bounds?  Make it the default.
        if ($end_size < 1) {
            $end_size = 1;
        }
        $mid_size = (int)$args['mid_size'];
        if ($mid_size < 0) {
            $mid_size = 2;
        }
        $add_args = $args['add_args'];
        $r = '';
        $page_links = array();
        $dots = false;

        if ($args['prev_next'] && $current && 1 < $current) :
            $link = str_replace('%_%', 2 === $current ? '' : $args['format'], $args['base']);
            $link = str_replace('%#%', $current - 1, $link);
            if ($add_args) {
                $link = add_query_arg($add_args, $link);
            }
            $link .= $args['add_fragment'];

            /**
             * Filter the paginated links for the given archive pages.
             *
             * @since 3.0.0
             *
             * param string $link The paginated link URL.
             */
            $page_link = "<a class='prev page-numbers' data-page='" . ($current - 1) . "'>";
            $page_link .= $args['prev_text'] . '</a>';
            $page_links[] = $page_link;
        endif;
        for ($n = 1; $n <= $total; $n++) :
            if ($n === $current) :
                $page_link = "<span class='page-numbers current'>" . $args['before_page_number'];
                $page_link .= number_format_i18n($n) . $args['after_page_number'] . '</span>';
                $page_links[] = $page_link;
                $dots = true;
            else :
                if ($args['show_all'] ||
                    ($n <= $end_size || ($current && $n >= $current - $mid_size && $n <= $current + $mid_size) ||
                        $n > $total - $end_size)) :
                    $link = str_replace('%_%', 1 === $n ? '' : $args['format'], $args['base']);
                    $link = str_replace('%#%', $n, $link);
                    if ($add_args) {
                        $link = add_query_arg($add_args, $link);
                    }
                    $link .= $args['add_fragment'];

                    /**
                     * This filter is documented in wp-includes/general-template.php
                    */
                    $page_link = "<a class='page-numbers' data-page='" . $n . "'>" . $args['before_page_number'];
                    $page_link .= number_format_i18n($n) . $args['after_page_number'] . '</a>';
                    $page_links[] = $page_link;
                    $dots = true;
                elseif ($dots && !$args['show_all']) :
                    $page_links[] = '<span class="page-numbers dots">' . esc_html__('&hellip;', 'wpfd') . '</span>';
                    $dots = false;
                endif;
            endif;
        endfor;
        if ($args['prev_next'] && $current && ($current < $total || -1 === $total)) :
            $link = str_replace('%_%', $args['format'], $args['base']);
            $link = str_replace('%#%', $current + 1, $link);
            if ($add_args) {
                $link = add_query_arg($add_args, $link);
            }
            $link .= $args['add_fragment'];

            /**
             * This filter is documented in wp-includes/general-template.php
            */
            $page_link = "<a class='next page-numbers' data-page='" . ($current + 1) . "'>";
            $page_link .= $args['next_text'] . '</a>';
            $page_links[] = $page_link;
        endif;
        switch ($args['type']) {
            case 'array':
                return $page_links;
            case 'list':
                $r .= "<ul class='page-numbers'>\n\t<li>";
                $r .= join("</li>\n\t<li>", $page_links);
                $r .= "</li>\n</ul>\n";
                break;
            default:
                $r .= "<div class='wpfd-pagination'>\n\t";
                $r .= join("\n", $page_links);
                $r .= "\n</div>\n";
                break;
        }
        return $r;
    }
}

if (!function_exists('wpfd_esc_desc')) {
    /**
     * Escaping for HTML description blocks.
     *
     * @param string $text Text
     *
     * @return string
     */
    function wpfd_esc_desc($text)
    {
        $safe_text = wp_check_invalid_utf8($text);
        // Remove <script>
        $safe_text = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $safe_text);
        return apply_filters('wpfd_esc_desc', $safe_text, $text);
    }
}

if (PHP_VERSION_ID < 70300) {
    if (!function_exists('is_countable')) {
        /**
         * Check countable variables from php version 7.3.0
         *
         * @param mixed $var Variable to check
         *
         * @return boolean
         */
        function is_countable($var)
        {
            return is_array($var) || $var instanceof Countable || $var instanceof ResourceBundle || $var instanceof SimpleXmlElement;
        }
    }
}

if (!function_exists('wpfd_sanitize_ajax_url')) {
    /**
     * Sanitize wp file download ajax url
     *
     * @param string $url Ajax url
     *
     * @return string
     */
    function wpfd_sanitize_ajax_url($url)
    {
        if (preg_match('/Wpfd/i', $url)) {
            $url = str_replace('action=Wpfd', 'action=wpfd', $url);
        }

        return apply_filters('wpfd_sanitize_ajax_url', $url);
    }
}
if (!function_exists('wpfd_locate_template')) {
    /**
     * Locate a template and return the path for inclusion.
     *
     * Loader order:
     *
     * wp-content/$content_path/$template_name
     * $default_path/app/site/themes/$template_name
     *
     * @param string $template_name Template name
     * @param string $content_path  Template path dir
     * @param string $default_path  Default path dir
     *
     * @return string
     */
    function wpfd_locate_template($template_name, $content_path = '', $default_path = '')
    {

        if (!$content_path) {
            $content_path = WP_CONTENT_DIR . '/wp-file-download/templates/';
        }

        if (!$default_path) {
            $default_path = plugin_dir_path(WPFD_PLUGIN_FILE) . '/app/site/themes/templates/';
        }

        // Look into wp-content directory for template file - this is priority.
        $template = file_exists(trailingslashit($content_path) . $template_name) ? trailingslashit($content_path) . $template_name : '';

        // Get default template.
        if (!$template) {
            $template = trailingslashit($default_path) . $template_name;
        }

        /**
         * Filter on return found template path
         *
         * @param string Template path
         * @param string Template name
         * @param string Template path dir
         */
        return apply_filters('wpfd_locate_template', $template, $template_name, $content_path);
    }
}
if (!function_exists('wpfd_get_template')) {
    /**
     * Get templates
     *
     * @param string $template_name Template name.
     * @param array  $args          Template arguments
     * @param string $content_path  Template path dir
     * @param string $default_path  Default path dir
     *
     * @return void
     */
    function wpfd_get_template($template_name, $args = array(), $content_path = '', $default_path = '')
    {
        if (!empty($args) && is_array($args)) {
            // phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- use extract is ok
            extract($args);
        }

        $located = wpfd_locate_template($template_name, $content_path, $default_path);

        if (!file_exists($located)) {
            return;
        }

        /**
         * Allow 3rd party plugin filter
         *
         * @param string Template path
         * @param string Template name
         * @param array  Template variables
         * @param string Template path dir
         * @param string Default path dir
         *
         * @return string
         */
        $located = apply_filters('wpfd_get_template', $located, $template_name, $args, $content_path, $default_path);

        /**
         * Action fire before a template part called
         *
         * @param string Template name
         * @param string Template path dir
         * @param string Template path
         * @param array  Template variables
         */
        do_action('wpfd_before_template_part', $template_name, $content_path, $located, $args);

        include $located;

        /**
         * Action fire after a template part called
         *
         * @param string Template name
         * @param string Template path dir
         * @param string Template path
         * @param array  Template variables
         */
        do_action('wpfd_after_template_part', $template_name, $content_path, $located, $args);
    }
}

if (!function_exists('wpfd_get_template_html')) {
    /**
     * Get templates and return html
     *
     * @param string $template_name Template name
     * @param array  $args          Template arguments
     * @param string $content_path  Template path dir
     * @param string $default_path  Default path dir
     *
     * @return false|string
     */
    function wpfd_get_template_html($template_name, $args = array(), $content_path = '', $default_path = '')
    {
        ob_start();
        wpfd_get_template($template_name, $args, $content_path, $default_path);
        return ob_get_clean();
    }
}
/**
 * Locate theme file path
 *
 * @param string $theme        Theme name
 * @param string $file         Theme file to locate
 * @param string $content_path Template path dir
 * @param string $default_path Template default path dir
 * @param string $upload_path  Template old path dir
 *
 * @return mixed
 */
function wpfd_locate_theme($theme, $file, $content_path = '', $default_path = '', $upload_path = '')
{
    $ds = DIRECTORY_SEPARATOR;
    if ($content_path === '') {
        $content_path = WP_CONTENT_DIR . $ds .'wp-file-download' . $ds . 'themes' . $ds . 'wpfd-' . $theme . $ds;
    }
    if (!$upload_path) {
        $dir = wp_upload_dir();
        $upload_path = $dir['basedir'] . $ds . 'wpfd-themes' . $ds . 'wpfd-' . $theme . $ds;
    }
    if (!$default_path) {
        $default_path = plugin_dir_path(WPFD_PLUGIN_FILE) . $ds . 'app' . $ds . 'site' . $ds . 'themes' . $ds . 'wpfd-' . $theme . $ds;
    }

    if (file_exists(trailingslashit($content_path) . $file)) {
        $template = trailingslashit($content_path) . $file;
    } elseif (file_exists(trailingslashit($upload_path) . $file)) {
        $template = trailingslashit($upload_path) . $file;
    }

    // Get default template.
    if (!isset($template)) {
        $template = trailingslashit($default_path) . $file;
    }

    /**
     * Filter on return found template path
     *
     * @param string Template path
     * @param string Template name
     * @param string Template path dir
     */
    return apply_filters('wpfd_locate_theme', $template, $theme, $file, $content_path);
}

/**
 * Get theme instance by name
 *
 * @param string $theme        Theme name
 * @param string $content_path Template path dir
 * @param string $default_path Template default path dir
 * @param string $upload_path  Template old path dir
 *
 * @return WpfdTheme{NAME}
 */
function wpfd_get_theme_instance($theme, $content_path = '', $default_path = '', $upload_path = '')
{
    $file = 'theme.php';

    if (!class_exists('wpfdTheme')) {
        $wpfdTheme = plugin_dir_path(WPFD_PLUGIN_FILE) . '/app/site/themes/templates/wpfd-theme.class.php';
        include_once $wpfdTheme;
    }

    $located = wpfd_locate_theme($theme, $file, $content_path, $default_path, $upload_path);

    if (file_exists($located)) {
        include_once $located;
    } else {
        $themefile = plugin_dir_path(WPFD_PLUGIN_FILE) . '/app/site/themes/wpfd-default/theme.php';
        include_once $themefile;
        $theme = 'default';
    }
    $class = 'WpfdTheme' . ucfirst(str_replace('_', '', $theme));

    if (class_exists($class)) {
        $instance = new $class();
    } else {
        $instance = new WpfdThemeDefault;
    }

    return $instance;
}

/**
 * Get supported cloud platform
 *
 * @return array
 */
function wpfd_get_support_cloud()
{
    /**
     * Filter return supported cloud platform
     * Require to detect where categories/files from
     *
     * @param array Cloud platform list
     *
     * @return array
     */
    return apply_filters('wpfd_get_support_cloud', array('googleDrive', 'dropbox', 'onedrive'));
}

/**
 * Get file url from real path
 *
 * @param string $path Real path
 *
 * @return string
 */
function wpfd_abs_path_to_url($path = '')
{
    $url = str_replace(
        wp_normalize_path(untrailingslashit(ABSPATH)),
        site_url(),
        wp_normalize_path($path)
    );

    return esc_url_raw($url);
}
