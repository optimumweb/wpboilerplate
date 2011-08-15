	<?php global $wpbp_options; ?>
	<?php wpbp_footer_before(); ?>
		<footer id="content-info" role="contentinfo">
			<?php wpbp_footer_inside(); ?>
			<div id="footer">
				<div class="container <?php echo $wpbp_options['container_class']; ?>">
					<?php dynamic_sidebar("Footer"); ?>
					<div class="clear"></div>
				</div>
			</div>
			<div id="subfooter">
				<div class="container <?php echo $wpbp_options['container_class']; ?>">
					<div id="copy"><small>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></small></div>
					<div class="clear"></div>
				</div>
			</div>
		</footer>
		<div class="clear"></div>
		<?php wpbp_footer_after(); ?>
	</div>
	<div class="clear"></div>

<?php wp_footer(); ?>
<?php wpbp_footer(); ?>

	<!--[if lt IE 7]>
		<script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
		<script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
	<![endif]-->

</body>
</html>