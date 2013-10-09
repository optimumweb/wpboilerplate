<?php

add_action('init', 'wpbp_register_lib');
add_action('wpbp_head', 'wpbp_google_analytics_content_experiment');
add_action('wpbp_head', 'wpbp_google_analytics');
add_action('wpbp_head', 'wpbp_google_tag_manager');
add_action('wpbp_head', 'wpbp_custom_css');
add_action('wpbp_head', 'wpbp_favicon');
add_action('wpbp_footer', 'wpbp_custom_js');
add_action('wpbp_footer', 'wpbp_add_post_js');
add_action('wpbp_loop_after', 'wpbp_clear');

function wpbp_google_analytics()
{
    $id = wpbp_get_option('google_analytics_id');
    if ( $id !== '' ) : ?>
<!-- Google Analytics -->
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '<?php echo $id; ?>']);
    _gaq.push(['_trackPageview']);
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
<!-- End Google Analytics -->
<?php endif;
}

function wpbp_google_analytics_content_experiment()
{
    set_post_ID($post_ID);
    $test_key = get_post_meta($post_ID, 'ga_test_key', true);
    if ( $test_key ) : ?>
<!-- Google Analytics Content Experiment code -->
<script>function utmx_section(){}function utmx(){}(function(){var
k='<?php echo $test_key; ?>',d=document,l=d.location,c=d.cookie;
if(l.search.indexOf('utm_expid='+k)>0)return;
function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
</script><script>utmx('url','A/B');</script>
<!-- End of Google Analytics Content Experiment code -->
<?php endif;
}

function wpbp_google_tag_manager()
{
    $id = esc_attr( wpbp_get_option('google_tag_manager_id') );
    if ( $id !== '' ) : ?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=<?php echo $id; ?>" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo $id; ?>');</script>
<!-- End Google Tag Manager -->
    <?php endif;
}

function wpbp_custom_css()
{
    if ( wpbp_get_option('custom_css') ) :
?>
<style type="text/css">
<?php wpbp_option('custom_css'); ?>
</style>
<?php
    endif;
}

function wpbp_custom_js()
{
    if ( wpbp_get_option('custom_js') ) :
?>
<script type="text/javascript">
<?php wpbp_option('custom_js'); ?>
</script>
<?php
    endif;
}

function wpbp_add_post_js()
{
    set_post_ID($post_ID);
    if ( get_post_meta($post_ID, 'js', true) ) :
?>
    <script type="text/javascript">
        <?php echo get_post_meta($post_ID, 'js', true); ?>
    </script>
<?php
    endif;
}

function wpbp_favicon()
{
    if ( wpbp_get_option('favicon') ) {
        echo '<link rel="icon" type="image/png" href="' . wpbp_get_option('favicon') . '">';
    }
}

function wpbp_clear()
{
	echo '<div class="clear"></div>';
}
