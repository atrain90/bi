<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "site-content" div and all content after.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
?>

	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<?php if ( is_active_sidebar( 'sidebar-2' )  ) : ?>

 <div class="widget-area" role="complementary">
 
  <?php dynamic_sidebar( 'sidebar-2' ); ?>
 
 </div>
 
<?php endif; ?>
		

<?php wp_footer(); ?>
</body>
</html>
