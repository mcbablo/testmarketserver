<?php
/**
 * The Toolbar Mobile
 *
 * @since   1.0.0
 * @package Kalles
 */


?>
<?php if ( class_exists( 'WooCommerce' ) ) :  ?>
  <?php
    $items = cs_get_option( 'general_mobile_toolbar_items' );
  ?>
  <?php if (! empty( $items ) ) : ?>
    <div id="kalles-section-toolbar_mobile">
       <div class="kalles_toolbar kalles_toolbar_label_0 ntpf r__0 l__0 b__0 flex fl_between al_center">
          <div class="kalles_toolbar_item toolbar_icon_event_default">
            <a href="<?php echo get_the_permalink(pll_get_post(get_page_by_path( 'shop' )->ID));?>" class="kalles_toolbar_item_link">
              <i class="toolbar_icon t4_icon_t4-grid"></i>
            </a>
          </div>
          <div class="kalles_toolbar_item toolbar_icon_event_default">
            <a href="<?php echo get_the_permalink(pll_get_post(get_page_by_path( 'cart' )->ID));?>" class="kalles_toolbar_item_link">
              <i class="toolbar_icon t4_icon_t4-shopping-cart"></i>
            </a>
          </div>
          <div class="kalles_toolbar_item toolbar_icon_event_default">
            <a href="<?php echo get_the_permalink(pll_get_post(get_page_by_path( 'my-account' )->ID));?>" class="kalles_toolbar_item_link">
              <i class="toolbar_icon t4_icon_t4-user"></i>
            </a>
          </div>
          <div class="kalles_toolbar_item sf-open" data-open="the4-search-opened">
            <a href target class="kalles_toolbar_item_link"><i class="toolbar_icon t4_icon_t4-search"></i></a>
          </div>
       </div>
    </div>
  <?php endif; ?>
<?php endif; ?>