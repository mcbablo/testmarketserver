<?php
/**
 * The footer bottom common.
 *
 * @since   1.0.0
 * @package Kalles
 */
?>

<?php $f_copyright_right = cs_get_option('footer-copyright_right-content-type') ? cs_get_option('footer-copyright_right-content-type') : 'menu'; ?>
<div class="footer__bot pt__20 pb__20 lh__1">
	<div class="container pr tc">
		<div class="row">
			<div class="col-md-6 col-sm-12 col-xs-12 start-md center-sm center-xs">
				<?php pll_e('copyright2'); ?>
			</div>
			<div class="col-md-6 col-sm-12 col-xs-12 end-md center-sm center-xs">
				<?php pll_e('support2'); ?>
			</div>
		</div>
	</div>
</div><!-- .footer__bot -->