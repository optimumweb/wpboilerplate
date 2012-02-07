	<?php global $wpbp_options; ?>
		<?php wpbp_footer_before(); ?>
		<footer id="footer" role="contentinfo">
			<?php wpbp_footer_inside(); ?>
			<div class="container <?php echo $wpbp_options['container_class']; ?>">
                <div id="footer-nav">
                    <?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation' ) ); ?>
            		<div class="clear"></div>
                </div>
				<div id="copy">
                    <small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></small>
                </div>
				<div class="clear"></div>
			</div>
		</footer>
		<div class="clear"></div>
		<?php wpbp_footer_after(); ?>
	</div>
	<div class="clear"></div>

<?php wp_footer(); ?>
<?php wpbp_footer(); ?>

</body>
</html>