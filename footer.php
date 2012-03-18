<?php global $wpbp_options; ?>
		<?php wpbp_footer_before(); ?>
		<footer id="footer" role="contentinfo">
			<?php wpbp_footer_inside(); ?>
			<div class="container <?php echo $wpbp_options['container_class']; ?>">
				<nav id="footer-nav">
					<?php wp_nav_menu( array( 'theme_location' => 'secondary_navigation' ) ); ?>
					<div class="clear"></div>
				</nav>
				<div id="copy">
					&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>
				</div>
			</div>
		</footer>
		<?php wpbp_footer_after(); ?>
	</div>

<?php wp_footer(); ?>
<?php wpbp_footer(); ?>

</body>
</html>
