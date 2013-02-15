<div class="product-category">
<?php switch ($params["template"]["categories"] -> slug): ?>
<?php case "essay-grading":
	break;
	default:
?>
		<h3><?php echo $params["template"]["categories"] -> name ?></h3>
		<ul class="clearfix">
		<? foreach($params["template"]["products"] as $post_data): ?>
			<li>
				<div class="product-item">
					<div class="title"><?php echo $post_data -> post_title ?></div>
					<div class="description">
						<a href=''>learn more</a>
					</div>
					<div class="price">
						<?php if($params["template"]["categories"] -> slug == "free-trial" ): ?>
						FREE!
						<?php else: ?>
						$<?php echo get_post_meta($post_data -> ID, 'universal_price', true); ?>
						<?php endif; ?>
					</div>
				</div>
			</li>
		<?php endforeach; ?> 
		</ul>
	<? break; ?>
<?php endswitch; ?>
</div>