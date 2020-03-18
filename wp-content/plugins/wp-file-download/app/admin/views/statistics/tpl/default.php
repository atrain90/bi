<?php
/**
 * WP File Download
 *
 * @package WP File Download
 * @author  Joomunited
 * @version 1.0W
 */


use Joomunited\WPFramework\v1_0_5\Application;
use Joomunited\WPFramework\v1_0_5\Utilities;

// No direct access.
defined('ABSPATH') || die();
// Checking for nonce here
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['wpfd_statistics_nonce']) ||
        !wp_verify_nonce($_POST['wpfd_statistics_nonce'], 'wpfd_statistics')) {
        wp_die(esc_html__('You don\'t have permission to perform this action!', 'wpfd'));
    }
}

wp_enqueue_style(
    'wpfd-datetimepicker',
    plugins_url('app/site/assets/css/jquery.datetimepicker.css', WPFD_PLUGIN_FILE),
    array(),
    WPFD_VERSION
);
wp_enqueue_script(
    'wpfd-datetimepicker',
    plugins_url('app/site/assets/js/jquery.datetimepicker.js', WPFD_PLUGIN_FILE),
    array(),
    WPFD_VERSION
);
wp_enqueue_style(
    'wpfd-statistics',
    plugins_url('app/admin/assets/ui/css/statistics.css', WPFD_PLUGIN_FILE),
    array(),
    WPFD_VERSION
);
wp_enqueue_style(
    'wpfd-chosen',
    plugins_url('app/admin/assets/css/chosen.css', WPFD_PLUGIN_FILE),
    array(),
    WPFD_VERSION
);
wp_enqueue_script(
    'wpfd-chosen',
    plugins_url('app/admin/assets/js/chosen.jquery.min.js', WPFD_PLUGIN_FILE),
    array(),
    WPFD_VERSION
);
wp_enqueue_script(
    'wpfd-statistics',
    plugins_url('app/admin/assets/js/statistics.js', WPFD_PLUGIN_FILE),
    array(),
    WPFD_VERSION
);
$isNotFound = false;

?>

<div id="wpfd_statistics" class="download-statistics">
    <h3><?php esc_html_e('Download Statistics', 'wpfd'); ?></h3>
    <form action="" class="dropfilesparams" id="adminForm" name="adminForm" method="post">
        <input type="hidden" value="com_dropfiles" name="option">
        <input type="hidden" value="statistics" name="view">
        <?php wp_nonce_field('wpfd_statistics', 'wpfd_statistics_nonce'); ?>
        <div class="row-fluid">
            <div class="col selection input-append">
                <?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escape on wpfd_select()
                echo wpfd_select(
                    array(
                        ''         => esc_html__('Total downloads', 'wpfd'),
                        'category' => esc_html__('Category', 'wpfd'),
                        'files'    => esc_html__('Files', 'wpfd'),
                    ),
                    'selection',
                    esc_html(Utilities::getInput('selection', 'POST', 'string')),
                    'id="selection" class="ju-input"'
                );
                ?>
            </div>
            <?php if (Utilities::getInput('selection', 'POST', 'string') !== '') { ?>
            <div class="col selection_value">
                <div class="ju-input">
                <?php
                // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
                $selection_value = (is_countable($this->selectionValues) && count($this->selectionValues)) ? $this->selectionValues : array();
                $select          = Utilities::getInput('selection_value', 'POST', 'none');
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escape on wpfd_select()
                echo wpfd_select(
                    $selection_value,
                    'selection_value[]',
                    $select,
                    'class="ju-input chosen" multiple="true"'
                );
                ?>
                </div>
            </div>
            <?php } ?>
            <div class="col from-date">
                <span class="lbl-date"><?php esc_html_e('From', 'wpfd'); ?> </span>
                <div class="input-append">
                    <input title="" type="text" name="fdate" id="fdate"
                           value="<?php echo esc_attr(Utilities::getInput('fdate', 'POST', 'string')); ?>" maxlength="45"
                           class="ju-input">
                    <button type="button" class="ju-material-button" id="fdate_img"><i class="material-icons">calendar_today</i></button>
                </div>
            </div>
            <div class="col to-date">
                <span class="lbl-date"><?php esc_html_e('To', 'wpfd'); ?></span>
                <div class="input-append">
                    <input title="" type="text" name="tdate" id="tdate"
                           value="<?php echo esc_attr(Utilities::getInput('tdate', 'POST', 'string')); ?>" maxlength="45"
                           class="ju-input">
                    <button type="button" class="ju-material-button" id="tdate_img"><i class="material-icons">calendar_today</i></button>
                </div>
            </div>
            <button class="col ju-material-button dropfiles-search-btn" type="submit">
                <i class="material-icons">search</i>
                <?php esc_html_e('Apply filter', 'wpfd'); ?>
            </button>
        </div>
        <div id="chart_div"></div>
        <div class="row-fluid">
            <?php if (Utilities::getInput('selection', 'POST', 'string') !== '') { ?>
                <div class="span6">
                    <div class="input-append">
                        <input title="" class="ju-input" id="query" type="text" name="query"
                               value="<?php echo esc_attr(Utilities::getInput('query', 'POST', 'string')); ?>">
                        <button class="ju-material-button" type="submit"><i class="material-icons">search</i>
                        </button>

                    </div>
                    <button class="ju-button btn-reset" type="reset">
                        <?php esc_html_e('Reset', 'wpfd'); ?>
                    </button>
                </div>
                <div class="span6 text-right">
                    <label for="file_per_page"><?php esc_html_e('File per page', 'wpfd'); ?>
                        <?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escape on wpfd_select()
                        echo wpfd_select(
                            array(
                                '5'  => 5,
                                '10' => 10,
                                '15' => 15,
                                '20' => 20,
                                '25' => 25,
                                '30' => 30,
                                '-1' => 'All',
                            ),
                            'limit',
                            Utilities::getInput('limit', 'POST', 'string'),
                            'id="file_per_page" onchange="this.form.submit();" style="width: 150px;" class="ju-input"'
                        );
                        ?>
                    </label>
                </div>
            <?php } ?>
        </div>
        <?php
        // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php
        if (is_countable($this->files) && count($this->files)) {
            $items_thead = array(
                'post_title' => esc_html__('File title', 'wpfd'),
                'cattitle'   => esc_html__('Category', 'wpfd'),
                'count_hits' => esc_html__('Download Count', 'wpfd'),
            );
            ?>

            <table class="table">
                <thead>
                <tr>
                    <?php
                    foreach ($items_thead as $thead_key => $thead_text) {
                        $icon = '';
                        $dir  = $this->orderingdir;
                        if ($thead_key === $this->ordering) {
                            $icon = '<i class="dashicons dashicons-arrow-';
                            $icon .= ($this->orderingdir === 'asc' ? 'up' : 'down') . '"></i>';
                            if ($dir === 'asc') {
                                $dir = 'desc';
                            } elseif ($dir === 'desc') {
                                $dir = 'asc';
                            }
                        }
                        $currentOrderingCol = $this->ordering === $thead_key ? 'currentOrderingCol' : '';
                        ?>
                        <th class="<?php echo esc_attr($thead_text); ?>">
                            <a href="#"
                               class="wpfd-ordering <?php echo esc_attr($currentOrderingCol); ?>"
                               data-ordering="<?php echo esc_attr($thead_key); ?>"
                               data-direction="<?php echo esc_attr($dir); ?>"><?php echo esc_html($thead_text); ?>
                                <?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- nothing need to be escape
                                echo $icon;
                                ?>
                            </a>
                        </th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($this->files as $file) {
                    ?>
                    <tr class="file">
                        <td class=""><?php echo esc_html($file->post_title); ?></td>
                        <td class=""><?php echo esc_html($file->cattitle); ?></td>
                        <td class=""><?php echo esc_html($file->count_hits); ?></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        <?php } else { ?>
            <?php if (Utilities::getInput('selection', 'POST', 'string') === ''
                      && Utilities::getInput('query', 'POST', 'string') === ''
                      && Utilities::getInput('fdate', 'POST', 'string') === ''
                      && Utilities::getInput('tdate', 'POST', 'string') === ''
            ) { ?>
            <?php } else {
                $isNotFound = true;
                ?>
                <h3 class="message"><?php esc_html_e("There's no statistics available for the filters selected", 'wpfd'); ?></h3>
            <?php } ?>
        <?php } ?>
        <?php if (is_countable($this->files) && count($this->files)) { ?>
            <?php if (Utilities::getInput('selection', 'POST', 'string') !== '') { ?>
                <div class="" style="text-align: right">
                    <?php
                    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escape on wpfd_pagination()
                    echo wpfd_pagination(array(
                        'base'    => '',
                        'format'  => '',
                        'current' => max(1, (int) Utilities::getInput('paged', 'POST', 'string')),
                        'total'   => (int) $this->total
                    ), 'adminForm');
                    ?>
                </div>
            <?php } ?>
        <?php } ?>
        <input type="hidden" name="paged" value="0">
        <input type="hidden" name="filter_order" value="<?php echo esc_attr($this->ordering); ?>"/>
        <input type="hidden" name="filter_order_Dir" value="<?php echo esc_attr($this->orderingdir); ?>"/>
    </form>

</div>
<script type="text/javascript">
    wpfdajaxurl = "<?php echo wpfd_sanitize_ajax_url(Application::getInstance('Wpfd')->getAjaxUrl()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- keep this, if not it error ?>";
</script>
<?php
// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript -- external javascript file
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js?ver=version"></script>
<script type="text/javascript">
    google.charts.load('current', {packages: ['annotationchart']});
    <?php if (!$isNotFound) { ?>
    google.charts.setOnLoadCallback(drawchart);
    <?php } ?>
    function drawchart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        <?php if (is_countable($this->files) && count($this->files)) { // phpcs:ignore PHPCompatibility.PHP.NewFunctions.is_countableFound -- is_countable() was declared in functions.php ?>
        <?php foreach ($this->files as $file) { ?>
        data.addColumn('number', '<?php echo esc_html($file->post_title);?>');
        <?php } ?>
        <?php foreach ($this->dateFiles as $date => $columns) {
            $date = new DateTime($date);
        ?>
        data.addRow([new Date(
            <?php echo esc_attr($date->format('Y'));?>,
            <?php echo esc_attr(((int) $date->format('m') - 1));?> ,
            <?php echo esc_attr($date->format('d'));?>),
            <?php echo esc_html(implode(',', $columns));?>]
        );
        <?php } ?>
        <?php } else { ?>
        data.addColumn('number', '<?php esc_html_e('Global Download', 'wpfd'); ?>');
        <?php
        foreach ($this->allCount as $item) {
            $date = new DateTime($item->date);
        ?>
        data.addRow([new Date(
            <?php echo esc_attr($date->format('Y'));?>,
            <?php echo esc_attr(((int) $date->format('m') - 1));?> ,
            <?php echo esc_attr($date->format('d'));?>),
            <?php echo esc_attr($item->count);?>]
        );
        <?php } ?>
        <?php } ?>
        var options = {
            hAxis: {
                title: '<?php esc_html_e('Date', 'wpfd'); ?>'
            },
            vAxis: {
                title: '<?php esc_html_e('Download Count', 'wpfd'); ?>'
            },
            height: 400,
            backgroundColor: '#F7F9FA'
        };
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }
</script>
