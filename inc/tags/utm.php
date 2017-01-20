<script>
    <?php if ( !empty($utms) ) : ?>
        <?php foreach ( $utms as $utm => $value ) : ?>
            window.<?= $utm ?> = '<?= $value ?>';
        <?php endforeach; ?>
    <?php endif; ?>
</script>
s