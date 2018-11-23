<?php

remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
// remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
// remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );

add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_cus_tom_loop_product_thumbnail', 10);
add_action('woocommerce_shop_loop_item_title', 'woocommerce_cus_tom_loop_product_title', 10);
add_action('woocommerce_after_shop_loop', 'custom_wordp_pagination', 10);
// add_action( 'woocommerce_product_thumbnails', 'custom_image_id', 20 );
// add_action( 'woocommerce_before_single_product_summary', 'custom_thumbnail_check', 20 ); 

/////////////////////
// single product ///
/////////////////////
add_action('woocommerce_single_product_summary', 'custom_sigle_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);

function custom_sigle_title(){
	global $product;
	echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tag">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); 
	echo '<h5>'.get_the_title().'</h5>';

}

////////////////////////////////////////
//  WOOOCOMMMERCE LOOP IMAGE SECTION ///
////////////////////////////////////////
function woocommerce_cus_tom_loop_product_thumbnail(){
	 $output = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); 
	echo '<div class="product-image"><img class="img-responsive" src="'.$output.'"></div>';
}
////////////////////////////////////////
//  WOOOCOMMMERCE LOOP PRODUCT TITLE///
////////////////////////////////////////
function woocommerce_cus_tom_loop_product_title(){
	
	global $product;
	echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tag">' . _n( 'Tag: ', 'Tags: ', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); 
	echo '<a href="'.get_the_permalink().'" class="tittle">'. get_the_title() .'</a>';
}

/////////////////////////////
// custom single product ///
////////////////////////////
function custom_product_function(){
	global $product;
	$args = array();
	$rating_count = $product->get_rating_count();
	$review_count = $product->get_review_count();
	$average      = $product->get_average_rating();
	$thumb = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); 
			
	?>
		<div class="product">
			<article> 
		  
		  <div class="product-image">
		  <img class="img-responsive" src="<?php echo $thumb; ?>" alt="">
			</div>  
			    

		<?php
		  global $post, $product;

			 if ( $product->is_on_sale() ) {
				echo apply_filters( 'woocommerce_sale_flash', '<span class="sale-tag">' . esc_html__( 'Sale!', 'woocommerce' ) . '</span>', $post, $product );  
			 }else{
				 echo apply_filters( 'woocommerce_sale_flash', '<span class="new-tag">' . esc_html__( 'New', 'woocommerce' ) . '</span>', $post, $product ); 
			 }

				 
				echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tag">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>
		  
			  
			   
			   
			  <a href="<?php the_permalink(); ?>" class="tittle"><?php the_title(); ?></a> 
			  <!-- Reviews -->
			  <div class="rev">
			  
			  <?php echo wc_get_rating_html($average); ?>
			  
			  </div>
			  
			  <div class="price"><?php echo $product->get_price_html(); ?></div>
			  <?php
				echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
					sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
						esc_attr( isset( $args['class'] ) ? $args['class'] : 'cart-btn' ),
						isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
						'<i class="icon-basket-loaded"></i>'
					),
				$product, $args );
				?>
			  </article>
		  </div>
	<?php
}



////////////////////////////////////////
//  WOOOCOMMMERCE custom  breadcrumb///
////////////////////////////////////////
add_filter('woocommerce_breadcrumb_defaults', 'my_woocommerce_breadcrumb_defaults');
function my_woocommerce_breadcrumb_defaults(){
	$breadcrumb = array(
		'delimiter'   =>   '  ',
		'wrap_before'   =>   '<div class="linking"><div class="container"><ol class="breadcrumb">',
		'wrap_after'   =>   '</ol></div></div>',
		'before'   =>   '<li class="active">',
		'after'   =>   '</li>',
		'home'   =>   _x('home ', 'breadcrumb', 'woocommerce'),
	);
	return $breadcrumb;
}     
////////////////////////////////////////
//  WOOOCOMMMERCE   paginnation///
////////////////////////////////////////
function custom_wordp_pagination(){
	global $wp_query;
	
	$big =  99999999;
	$pages = paginate_links(array(
		'base'  => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
		'format'  =>  '?paged=%#%',
		'current' => max(1, get_query_var('paged')),
		'total'   => $wp_query->max_num_pages,
		'type'    => 'array',
		'prev_text' => __('<'),
		'next_text' => __('>')
	));
	
	if(is_array($pages)){
		$paged = ( get_query_var('paged')  == 0 ) ? 1 : get_query_var('paged');
		
		echo '<ul class="pagination">';
		foreach($pages as $page){
			echo '<li>'.$page.'</li>';		
		}
	     echo '</ul>';
	}
	return $pages;
}




//////////////////////////////////////////////
//  WOOOCOMMMERCE Removed  default dropdown///
/////////////////////////////////////////////
function woocommerce_catalog_page_ordering_custom(){
	?>
	<div class="btn-group bootstrap-select open">
	<form action="" method="post" name="results" class="f1d inline">
		
		<select name="woocommerce-sort-by-columns" id="woocommerce-sort-by-columns" class="sortby selectpicker" onchange="this.form.submit()" tabindex="-98">
			<?php
		if(isset($_POST['woocommerce-sort-by-columns']) && ($_COOKIE['shop_pageResults'] <> $_POST['woocommerce-sort-by-columns']) ){
			$numberOfProductsPerPage = $_POST['woocommerce-sort-by-columns'];
		}else{
			$numberOfProductsPerPage = $_COOKIE['shop_pageResults'];
		}	
		$shopCatalog_orderby = apply_filters('woocommerce_sortby_page', array(
		'20'    => __('20', 'woocommerce'),
		'15'    => __('15', 'woocommerce'),
		'10'    => __('10', 'woocommerce'),
		'5'    => __('5', 'woocommerce'),
		'3'    => __('3', 'woocommerce'),
		'-1'    => __('all', 'woocommerce'),
		));	
		foreach($shopCatalog_orderby as $sort_id => $sort_name)
			echo '<option value="'.$sort_id.'" '.selected($numberOfProductsPerPage, $sort_id, true ).' >Show ' .$sort_name.'</option>';	
		?>	
		</select>
	</form>
	</div>
	<?php	
}
function di_sort_by_page($count){
	if(isset($_COOKIE['shop_pageResults'])){
		$count = $_COOKIE['shop_pageResults'];
	}
	if(isset($_POST['woocommerce-sort-by-columns'])){
		setcookie('shop_pageResults', $_POST['woocommerce-sort-by-columns'], time() + 1209600, '/', 'www.your-domain-goes-here.com', false);
		$count = $_POST['woocommerce-sort-by-columns'];
	}
	return $count;
}
add_filter('loop_shop_per_page', 'di_sort_by_page');

//////////////////////////////////////////////
//  WOOOCOMMMERCE Sorting default catalog  ///
/////////////////////////////////////////////
// for passing arguments



function custom_product_categories( $args = array() ) {
	?>
	<div class="btn-group bootstrap-select"><button type="button" class="btn dropdown-toggle btn-default" data-toggle="dropdown" role="button" title="All Categories" aria-expanded="true"><span class="filter-option pull-left"> All Categories</span>&nbsp;<span class="bs-caret"><span class="caret"></span></span></button>
	<div class="dropdown-menu open" role="combobox" style="max-height: 421.033px; overflow: hidden; min-height: 82px;">
	<?php
    ///// now add this code in your function 
$parentid = get_queried_object_id();       
$args = array(
//    'parent' => $parentid
    'name_num' => $parentid
);
$terms = get_terms( 'product_cat', $args );
if ( $terms ) {      
    echo '<ul class="dropdown-menu inner" role="listbox" aria-expanded="true" style="max-height: 419.033px; overflow-y: auto; min-height: 80px;">';
        foreach ( $terms as $term ) {
                         
            echo '<li data-original-index="1">';                      
//                woocommerce_subcategory_thumbnail( $term );
                    echo '<a tabindex="0" data-tokens="null" role="option"  aria-disabled="false" aria-selected="true" href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">';
                        echo $term->name;
                    echo '</a>';                                                          
            echo '</li>';
    }
    echo '</ul>';
}
?>
		</div>
<?php
	echo '</div>';
}


// get product_tags of the current product
$current_tags = get_the_terms( get_the_ID(), 'product_tag' );

//only start if we have some tags
if ( $current_tags && ! is_wp_error( $current_tags ) ) { 

    //create a list to hold our tags
    echo '<ul class="product_tags">';

    //for each tag we create a list item
    foreach ($current_tags as $tag) {

        $tag_title = $tag->name; // tag name
        $tag_link = get_term_link( $tag );// tag archive link

        echo '<li><a href="'.$tag_link.'">'.$tag_title.'</a></li>';
    }

    echo '</ul>';
}






























