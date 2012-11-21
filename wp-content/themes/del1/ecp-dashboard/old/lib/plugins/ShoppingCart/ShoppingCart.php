<?php
if( !isset( $_SESSION ) ) { session_start();}
wp_enqueue_script( 'jquery' );
wp_register_script('azcart', IDGL_THEME_URL . '/lib/plugins/ShoppingCart/js/shcart.js');
wp_enqueue_script('azcart');

PostType::register(IDGL_File::getFileList(dirname(__FILE__)."/models/"));


add_filter('rewrite_rules_array','auzz_card_insert_rewrite_rules');
add_filter('query_vars','auzz_card_insert_rewrite_query_vars');
add_filter('init','auzz_card_flush_rules');

function auzz_card_flush_rules(){
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
}
function auzz_card_insert_rewrite_rules($rules)
{
	$newrules = array();
	$newrules['(cart)'] = 'index.php?cart_action=cart';
	$newrules['(checkout)'] = 'index.php?cart_action=checkout';
	return $newrules + $rules;
}
function auzz_card_insert_rewrite_query_vars($vars)
{
    array_push($vars, 'cart_action');
    return $vars;
}
add_action( "parse_query", "auzz_card_process_reqests");

function auzz_card_process_reqests(){
	global $wp_query;
	$cart_action = $wp_query->query_vars['cart_action'];
	if(isset($cart_action)){
		function cart_action() {
			global $wp_query;
			$cart_action = $wp_query->query_vars['cart_action'];
			include dirname(__FILE__)."/".$cart_action.".php";
			exit;
		}
		add_action('template_redirect', 'cart_action');
	}
}

	
	
add_action('admin_menu', 'auzz_card_addMenuItems');
function auzz_card_addMenuItems(){
	if(isset($_GET["post_type"])){
		$currEditType=$_GET["post_type"];
	}else{
		$currEditType=get_post_type($_GET["post"]);
	}
	if($currEditType!="Product" && $currEditType!="Ebooks" && $currEditType!="Coaching"){ return; }
	
	$postOptions=IDGL_Config::getConfig(dirname(__FILE__)."/models/".$currEditType.".xml");
	foreach($postOptions as $page){
		$name=$page["name"];
		$title=$page["title"];
		IDGL_PostOptionManages::IDGL_addPostOptionFunction($name,$page);
		add_meta_box( $name, __( $title, $title ), "IDGL_fx_".$name, $currEditType, 'advanced','high' );
	}
}

$labels = array(
    'name' => _x( 'Product Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Product Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Product Categories' ),
    'popular_items' => __( 'Popular Product Categories' ),
    'all_items' => __( 'All Product Categories' ),
    'parent_item' => __( 'Parent Product Categories' ),
    'parent_item_colon' => __( 'Parent Product Categories:' ),
    'edit_item' => __( 'Edit Product Categories' ), 
    'update_item' => __( 'Update Product Categories' ),
    'add_new_item' => __( 'Add New Product Categories' ),
    'new_item_name' => __( 'New Genre Product Categories' ),
);
register_taxonomy('Product_Categories',array('Product'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'product-category' ),
));

function my_excerpts($content = false) {
	global $post;
	if(get_post_type($post->ID)=="Product" || get_post_type($post->ID)=="Ebooks" || get_post_type($post->ID)=="Coaching"){
		return $content.'<div class="add_to_cart_wrap">
				Price: <strong>$'.getPostMeta($post->ID,"ProductPrice").' </strong>
				<input type="hidden" name="prioduct_id"  class="add_to_cart_id" value="'.$post->ID.'" />
				<input type="button" class="add_to_cart" id="id_'.$post->ID.'" value="add to cart" />
			</div>';
	}else{
		return $content;
	}
	
}
add_filter('the_content', 'my_excerpts');


add_action('wp_head','auzz_card_add_scripts');
function auzz_card_add_scripts(){
	echo '<link type="text/css" rel="stylesheet" href="'.IDGL_THEME_URL.'/lib/plugins/ShoppingCart/css/style.css" />';
}



add_action('wp_ajax_auzz_card_add', 'auzz_card_add' );
add_action('wp_ajax_nopriv_auzz_card_add', 'auzz_card_add');

function auzz_card_add(){
	ShoppingCart::addToCart($_POST["productId"],1);
	echo ShoppingCart::render();
	die();
}

add_action('wp_ajax_auzz_card_remove', 'auzz_card_remove' );
add_action('wp_ajax_nopriv_auzz_card_remove', 'auzz_card_remove');
function auzz_card_remove(){
	ShoppingCart::removeFromCart($_POST["productId"]);
	echo ShoppingCart::render();
	die();
}
class ShoppingCart{
	function ShoppingCart(){}
	public static function removeFromCart($id){
		$c=unserialize($_SESSION["cart"]);
		unset($c[$id]);
		$_SESSION["cart"]=serialize($c);
	}
	public static function addToCart($id,$qty){
		$c=unserialize($_SESSION["cart"]);
		if($c==null){
			$c=array();
		}
		if($c[$id]==null){
			$c[$id]=$qty;
		}else{
			$c[$id]++;
		}
		$_SESSION["cart"]=serialize($c);
	}
	public static function render($type=null){
		//print_r($_SESSION);
		$c=unserialize($_SESSION["cart"]);
		$prodNo=count($c);
		if($prodNo==0){
			$prodNo='(empty)';
		}else if($prodNo==1){
			$prodNo.=" product";
		}else{
			$prodNo.=" products";
		}
		switch($type){
			case "min":
				$out='<a href="#">Cart:</a><span class="ajax_cart_no_product">'.$prodNo.'</span>';
				break;
			default:
				//$out='<a href="#">Cart:</a><span class="ajax_cart_no_product">'.$prodNo.'</span>';
				$out="<ul class='cart_content' id='cart_content'>";
				$total=0;
				if(count($c)==0){
					$out.='<li><a href="#">Cart:</a> <span class="ajax_cart_no_product">'.$prodNo.'</span></li>';
				}else{
					if($c!=""){
						foreach($c as $id=>$qty){
							$post=get_post($id);
							$price=getPostMeta($post->ID,"ProductPrice");
							$total+=$qty*$price;
							$out.="<li>
									<span id='qty-".$id."' class='quantity'>".$qty."</span> x <a class='product_title' href='".get_permalink( $post->ID )."'><img width='30px' src='".getPostMeta($post->ID,"productThumb")."' /> ".$post->post_title ."</a> <span class='price'>$ ".($qty*$price)." </span> <a href='#' id='id_".$id."' class='remove_link'></a>
									</li>";
						}
						$out.="<li class='total_wrap'><span>Total</span> <strong class='cart_total'>".$total."</strong> | <a href='".get_bloginfo("url")."/cart/'>view cart</a></li>";
					}else{
						$out.="<li>
									<span class='quantity empty'>Your cart is empty</span>
									</li>";
					}
				}
				$out.="</ul>";
				break;
		}
		return $out;
	}
	public static function render_details($type=null){
		$c=unserialize($_SESSION["cart"]);
		$prodNo=count($c);
		if($prodNo==0){
			$prodNo='(empty)';
		}else if($prodNo==1){
			$prodNo.=" product";
		}else{
			$prodNo.=" products";
		}
		
		$out="<table cellpadding='0' cellspacing='0' border='0' width='100%'>";
				$total=0;
				if(count($c)==0){
					$out.='<tr><td>Your cart is empty</td></tr>';
				}else{
					if($c!=""){
						foreach($c as $id=>$qty){
							$post=get_post($id);
							$price=getPostMeta($post->ID,"ProductPrice");
							$total+=$qty*$price;
							$out.="<tr><td>
									<span id='qty-".$id."' class='quantity'>".$qty."</span> x <a class='product_title' href='".get_permalink( $post->ID )."'>
									<img width='80px' src='".getPostMeta($post->ID,"productThumb")."' /> ".$post->post_title ."</a> <span class='price'>$ ".($qty*$price)." </span> <a href='#' id='id_".$id."' class='remove_link'></a>
									</td></tr>";
						}
						$out.="<tr><td><span>Total</span> <strong class='cart_total'>".$total."</strong></td></tr>";
						$out.="<tr><td><a href='".get_bloginfo("url")."/checkout/'>checkout</a></td></tr>";
					}
				}
				$out.="</table>";
		
		return $out;
	}
}

class auzz_card_widget extends WP_Widget {
	function auzz_card_widget() {
		parent::WP_Widget(false, $name = 'auzz_card_widget');
	}
	function form($instance) {
		$title = esc_attr($instance['title']);
		?>
<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?>
<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
	name="<?php echo $this->get_field_name('title'); ?>" type="text"
	value="<?php echo $title; ?>" /></label></p>
		<?php
	}
	function update($new_instance, $old_instance) {
		return $new_instance;

	}
	function widget($args, $instance) {
		$title = esc_attr($instance['title']);
		echo '<h2>'.$title.'</h2>';
		echo '<div class="abuzz_cart_details">'.ShoppingCart::render().'</div>';
	}
}
function auzz_card_load_widgets() {
	register_widget( 'auzz_card_widget' );
}
add_action( 'widgets_init', 'auzz_card_load_widgets' );