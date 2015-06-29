<script>
    <?php foreach ( $utms as $utm => $value ) : ?>
    window.<?= $utm ?> = '<?= $value ?>';
    <?php endforeach; ?>
</script>