<?php if ( ! empty( $google_analytics_id ) ) : ?>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $google_analytics_id; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?php echo $google_analytics_id; ?>');

        <?php if ( ! empty( $google_ads_id ) ) : ?>
            gtag('config', '<?php echo $google_ads_id; ?>');
        <?php endif; ?>
    </script>
<?php endif; ?>
