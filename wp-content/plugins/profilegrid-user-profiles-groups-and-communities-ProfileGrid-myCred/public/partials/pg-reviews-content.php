<?php $i = 0; foreach( $comments as $comment ) { $i++;
	
	$rating = intval( get_comment_meta( $comment->comment_ID, 'rating', true ) );
	
	?>

<div class="pg-mycred">
    <div class="pg-mycred-product">
            <div class="pg-mycred-img">
		<?php
			$post = get_post( $comment->comment_post_ID );
                        setup_postdata( $post );
                        $product = wc_get_product( $comment->comment_post_ID );
                        $product_link   = get_permalink( $comment->comment_post_ID );
                        if ( has_post_thumbnail( $comment->comment_post_ID ) ) 
                        {
                            $image = get_the_post_thumbnail( $comment->comment_post_ID );
                            echo sprintf( __('<a href="%s" class="pg-mycred-imgsrc">%s</a>','profilegrid-mycred-integration'), $product_link, $image );
                        } 
                        else 
                        {
                            echo sprintf( __('<img src="%s" alt="%s" class="pg-mycred-imgsrc" />','profilegrid-mycred-integration'), wc_placeholder_img_src(), __( 'Placeholder', 'profilegrid-mycred-integration' ) );
                        }
                ?>
			
		</div>
		
		<span class="pg-mycred-title"><a href="<?php echo $product_link; ?>"><span><?php echo get_the_title( $comment->comment_post_ID ); ?></span></a></span>
		<span class="pg-mycred-price"><?php echo $product->get_price_html(); ?></span>
		<span class="pg-mycred-avg" data-number="5" data-score="<?php echo $rating; ?>"></span>
		<span class="pg-mycred-product-review"><?php echo '&ldquo;' . $comment->comment_content . '&rdquo;'; ?></span>

	</div>

</div>


<?php } wp_reset_postdata(); ?>

<?php if ( !$i ) { ?>

<div class="pg-info"><span><?php echo ( $uid == get_current_user_id() ) ? __('You did not review any products yet.','profilegrid-mycred-integration') : sprintf(__('%s did not review any product yet.','profilegrid-mycred-integration'),$pmrequests->pm_get_display_name($uid)); ?></span></div>

<?php } ?>