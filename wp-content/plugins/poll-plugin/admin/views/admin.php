<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Weblator Polling
 * @author    Weblator <daniel.prior@weblator.com>
 * @link      http://weblator.com
 * @copyright 2014 Weblator
 */


?>

<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?> <?php echo sprintf('<a href="?page=%s&action=%s" class="add-new-h2">Add New</a>',$_REQUEST['page'],'add'); ?></h2>

    <?php



        $w_table = new Weblator_Tables;
        $w_table->prepare_items();
    ?>

    <form method="post">
        <?php
            $w_table->search_box( 'search', 'search_id' );
            $w_table->display();
        ?>
    </form>
    
    <p class="message">If you have any questions regarding the Responsive Poll plugin please contact us via <strong><a href="mailto:plugin-poll@weblator.com">plugin-poll@weblator.com</a></strong></p>
    
 
</div>
