<ul id='az_paging_content' class="az_paging_content">
<?php foreach ($params as $param) : ?>
	<li>
		<div class='eventWraper'>
			<a id='evid_<?php echo $param -> event_id ?>'></a>
			
			<h2><?php echo $param -> event_title ?></h2>
			<?php if($param -> event_image != ""): ?> 
				<div class='eventImgWraper'><img src='<?php echo $param -> event_image ?>' alt='<?php echo $param -> event_title ?>' /></div> 
			<?php endif; ?>
			<small><?php echo date("d  F  Y", strtotime($param -> event_date)) . " - " . date("d  F  Y", strtotime($param -> event_end_date)) ?></small>
			<div class="az_event_content">
				<?php echo $param -> event_content ?>
			</div>
		</div>
	</li>
<?php endforeach; ?>
</ul>
