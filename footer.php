		<?php wpbp_footer_before(); ?>
		<footer id="footer" role="contentinfo">
			<?php wpbp_footer_inside_before(); ?>
			<div class="<?php wpbp_container_class(); ?>">
                <div class="grid_8 mobile-center">
                    <nav id="footer-nav">
                        <?php wp_nav_menu(array( 'theme_location' => 'secondary_navigation' )); ?>
                        <div class="clear"></div>
                    </nav>
                </div>
                <div class="grid_4 text-right mobile-center">
                    <div id="copy">
                        &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
                    </div>
                </div>
			</div>
			<?php wpbp_footer_inside_after(); ?>
		</footer>
		<?php wpbp_footer_after(); ?>
	</div>

    <div id="wpbp-modal-blanket"></div>

    <?php wp_footer(); ?>
    <?php wpbp_footer(); ?>

</body>
</html>
<?php wpbp_after_html(); ?>