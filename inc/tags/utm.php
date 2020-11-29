<script>
    <?php if ( isset( $utms ) && is_array( $utms ) && count( $utms ) > 0 ) : ?>
        <?php foreach ( $utms as $utm => $value ) : ?>
            window.<?= $utm ?> = '<?= $value ?>';
        <?php endforeach; ?>
    <?php endif; ?>
</script>
