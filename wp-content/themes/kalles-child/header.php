<?php
/**
 * The header for our theme.
 *
 * @since   1.0.0
 * @package Kalles
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> itemscope itemtype="http://schema.org/WebPage">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' ); ?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta property="og:title" content="CLICK Market - Официальный интернет магазин" />
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
		<link rel="dns-prefetch" href="https://fonts.googleapis.com" crossorigin>
	    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
	    <link rel="dns-prefetch" href="https://fonts.gstatic.com" crossorigin>
		<script src="//code-ya.jivosite.com/widget/w9CxhgrPnG" async></script>
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript" >
			(function (d, w, c) {
				(w[c] = w[c] || []).push(function() {
					try {
						w.yaCounter86507788 = new Ya.Metrika({
							id:86507788,
							clickmap:true,
							trackLinks:true,
							accurateTrackBounce:true,
							webvisor:true,
							ecommerce:"dataLayer"
						});
					} catch(e) { }
				});

				var n = d.getElementsByTagName("script")[0],
					s = d.createElement("script"),
					f = function () { n.parentNode.insertBefore(s, n); };
				s.type = "text/javascript";
				s.async = true;
				s.src = "https://mc.yandex.ru/metrika/watch.js";

				if (w.opera == "[object Opera]") {
					d.addEventListener("DOMContentLoaded", f, false);
				} else { f(); }
			})(document, window, "yandex_metrika_callbacks");
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/86507788" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-8FLJ8RF34K"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'G-8FLJ8RF34K');
		</script>
		<?php wp_head(); ?>
	</head>
		<body <?php body_class(); ?> <?php the4_kalles_schema_metadata( array( 'context' => 'body' ) ); ?>>
			<?php
				if ( function_exists( 'wp_body_open' ) ) {
				    wp_body_open();
				} else {
				    do_action( 'wp_body_open' );
				}
			?>
        <!-- Top loading bar -->
        <div id="ld_cl_bar" class="op__0 pe_none"></div>
		<div id="the4-wrapper">
			<?php the4_kalles_header(); ?>