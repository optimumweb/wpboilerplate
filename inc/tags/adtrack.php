<script>
    window.adtrack = {};
    <?php if ( isset( $adtrack_values ) && is_array( $adtrack_values ) && count( $adtrack_values ) > 0 ) : ?>
        <?php foreach ( $adtrack_values as $param => $value ) : ?>
            window.adtrack.<?= $param ?> = '<?= $value ?>';
        <?php endforeach; ?>
    <?php endif; ?>
</script>
