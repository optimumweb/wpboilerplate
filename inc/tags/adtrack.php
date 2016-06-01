<script>
    window.adtrack = {};
    <?php if ( !empty($adtrack_values) ) : ?>
        <?php foreach ( $adtrack_values as $param => $value ) : ?>
            window.adtrack.<?= $param ?> = '<?= $value ?>';
        <?php endforeach; ?>
    <?php endif; ?>
</script>