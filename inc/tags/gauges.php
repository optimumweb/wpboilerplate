<?php if ( !empty($site_id) ) : ?>
<script>
    var _gauges = _gauges || [];
    (function() {
        var t   = document.createElement('script');
        t.type  = 'text/javascript';
        t.async = true;
        t.id    = 'gauges-tracker';
        t.setAttribute('data-site-id', '<?php echo $site_id; ?>');
        t.setAttribute('data-track-path', 'https://track.gaug.es/track.gif');
        t.src = 'https://d36ee2fcip1434.cloudfront.net/track.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(t, s);
    })();
</script>
<?php endif; ?>
