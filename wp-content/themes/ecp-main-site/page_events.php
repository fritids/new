<?php
/* 
  Template Name: Page Events
*/ 
?>

<?php get_header(); ?>
<div class="leftcolumn">
  <?php if (have_posts()) : ?>
         <?php while (have_posts()) : the_post(); ?>    
          <div class="post page">
          <div class="title">
                      <h1><?php the_title(); ?></h1>
                  </div>
                  <div class="entry">
                    <?php the_content(); ?>
                  </div> 
        </div>
         <?php endwhile; ?>
  <?php endif; ?>
  <div id="az_pages" class="az_pages">
    <?php echo AzCalendar::getEventListDetails(); ?>
    <div id='az_pagination' class='az_pagination'></div>
  </div>
</div>
<div class="sidebar">
<?php new Sidebar("Sidebar2"); ?>
</div>
<?php get_footer(); ?>

<script type="text/javascript">
jQuery(function($){

  var container_children = 5;
  var hash = getHash();
  if(hash != null)
  {
    var page_id = 0;
    var index = $("#" + hash).parents("li").index();

    if(index != -1)
    {
       page_id = Math.floor(index / container_children);
    }

    $('#az_pages').pajinate({
      start_page: page_id,
      num_page_links_to_display: 5,
      items_per_page : container_children,
      item_container_id : '#az_paging_content',
      nav_panel_id : '#az_pagination',
      nav_label_first: "|<",
      nav_label_last: ">|",
      nav_label_prev: "<",
      nav_label_next: ">"
    });
  }
  function getHash() 
  {
    var hash = window.location.hash;
    if(hash != "")
    {
      return hash.substring(1); // remove #
    }
    return null;
  }
});
</script>