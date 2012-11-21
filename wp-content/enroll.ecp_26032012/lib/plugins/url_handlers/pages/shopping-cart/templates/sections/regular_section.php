<?php
$class = "";
switch($params["template"]["tax"] -> slug)
{
	case "online-sat-course":
		$class = "cp_period";
		break;
	case "college-board-official-practice-tests-explanations":
		$class = "long_description";
		break;
}
?>
<div class='section <?php echo $params["template"]["tax"] -> description ?>'>
	<div class='selection'>
		<h3><?php echo $params["template"]["tax"] -> name ?></h3>
		<ul>
<?php 
switch ($params["template"]["tax"] -> slug)
{
	case "essay-grading":
		?>
		<li>
			<select class='main_select' id="main_select">
			<option value="0">Select</option>
			<?php 
				foreach($params["template"]["data"] as $post_data)
				{
					?>
						<option value='<?php echo $post_data -> post_title ?>' title="<?php echo $post_data -> ID; ?>"><?php echo $post_data -> post_title ?></option>";
					<?php 
				}
			?>
			</select>
			<span class="description">
				<a href='#prod_<?php echo $post_data -> ID; ?>' class='learn_more'>learn more</a>
					<span class='price'>
						<span>$</span> 
						<span class="int_price">0</span>
					</span>
				<input type="hidden" name="products_ids[]" value="" />
				<div style="display:none">
					<div id="prod_<?php echo $post_data -> ID; ?>"><?php echo $post_data -> post_content; ?></div>
				</div>
			</span>
		</li>
		<?php 
	break;
	default:
		
		foreach($params["template"]["data"] as $post_data)
		{
			$post_meta = getECPProductMeta($post_data -> ID);
			?>
			<li>
				<?php if($params["template"]["tax"] -> slug == "video-explanations"): echo '<div class="coming_soon"></div>'; endif;?>
                <a href='#' class='main_select <?php echo $class ?>' rel="<?php echo $post_data -> ID; ?>"><?php echo $post_data -> post_title ?><span class="cart_added"></span></a>
                <a href='#prod_<?php echo $post_data -> ID; ?>' class='learn_more'>learn more</a>
                <span class='price'>
	                <span>$</span> 
	                <span class="int_price"><?php echo $post_meta["price"] ?></span>
                </span>
                <?php if(strpos($params["template"]["tax"] -> slug, "essay" ) !== false): ?>	
						<!-- <div class="ecp_quantity"><span>Quantity:</span><input type="text" name="<?php echo $post_data -> ID; ?>_quantity" /></div> -->						
				<?php endif; ?>
				 
                <input type="hidden" name="products_ids[]" value="" />
				<div style="display:none"><div id="prod_<?php echo $post_data -> ID; ?>"><?php echo $post_data -> post_content; ?></div></div>
	        </li>
	       <?php 
		}
	break;
}
?>
		</ul>
		<div class="errornote"><span></span></div>
		<span class="selection_price">
			<span>$</span> 
			<span class="int_selection_price">0</span>
		</span>
	</div>
</div>
