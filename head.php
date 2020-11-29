<?php global $post; ?>
<?php wpbp_before_html(); ?>
<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<head>

<?php wpbp_head_inside_before(); ?>

<meta charset="<?php bloginfo( 'charset' ); ?>">

<?php if ( in_array( wpbp_get_option( 'responsive' ), array( 'responsive', 'mobile-responsive' ) ) ) : ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php endif; ?>

<title><?php wp_title( '' ); ?></title>

<?php wp_head(); ?>

<?php wpbp_head(); ?>

<?php wpbp_head_inside_after(); ?>

</head>

<body <?php body_class( array( $post->post_name, wpbp_get_option( 'css_framework' ), wpbp_get_option( 'responsive' ) ) ); ?>>
