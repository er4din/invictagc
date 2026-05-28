<?php

global $post, $product;
$i = 0;
while ( $loop->have_posts() ) : $loop->the_post();
$i++;

$product = wc_get_product( get_the_ID() );

$total_sales = (int)get_post_meta( get_the_ID(), 'total_sales', true );
$total_sales = number_format( $total_sales );

 // Compatibility for WC versions from 2.5.x to 3.0+
        if ( method_exists( $product, 'get_stock_status' ) ) {
            $stock_status = $product->get_stock_status(); // For version 3.0+
        } else {
            $stock_status = $product->stock_status; // Older than version 3.0
        }
        switch($stock_status)
        {
            case 'instock':
                $stock_state = __('In Stock','profilegrid-mycred-integration');
                break;
            case 'outofstock':
                $stock_state = __('Out of stock','profilegrid-mycred-integration');
                break;
            default:
                 $stock_state = $stock_status;
                break;
        }
?>

<div class="pg-mycred">
    <div class="pg-mycred-product">
        <div class="pg-mycred-img">
            <?php
                    $product_link = get_permalink( get_the_ID() );
                    if ( has_post_thumbnail( get_the_ID() ) ) {
                        $image = get_the_post_thumbnail( get_the_ID());
                        echo sprintf( __('<a href="%s" class="pg-mycred-imgsrc">%s</a>','profilegrid-mycred-integration'), $product_link, $image );
                    } 
                    else 
                    {
                        echo sprintf( __('<img src="%s" alt="%s" class="pg-mycred-imgsrc" />','profilegrid-mycred-integration'), wc_placeholder_img_src(), __( 'Placeholder', 'profilegrid-mycred-integration' ) );
                    }
            ?>

        </div>
		
		<span class="pg-mycred-title">
                    <a href="<?php echo $product_link; ?>"><span><?php the_title(); ?></span></a>
                </span>
		<span class="pg-mycred-price"><?php echo $product->get_price_html(); ?></span>
		<span class="pg-mycred-meta">
<!--			<span class="pg-mycred-salescount" title="<?php _e('Total Sales','profilegrid-mycred-integration'); ?>"><i class="pg-favicon-shopping-cart"></i><?php echo $total_sales; ?></span>-->
			<span class="pg-mycred-stock_state <?php echo 'pg_'.$stock_status;?>"><?php echo $stock_state; ?></span>
		</span>

	</div>
</div>


<?php endwhile; wp_reset_postdata(); ?>

<?php if ( !$i ) { ?>

<div class="pg-info"><span><?php echo ( $uid == get_current_user_id() ) ? __('You did not purchase any product yet.','profilegrid-mycred-integration') : sprintf(__('%s did not purchase any product yet.','profilegrid-mycred-integration'),$pmrequests->pm_get_display_name($uid)); ?></span></div>

<?php } ?>