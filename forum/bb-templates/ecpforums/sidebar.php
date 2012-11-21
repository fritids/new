<div id="hottags">

<div class="logindiv"><?php login_form(); ?>
<div class="clear"></div>
</div>

<h2 class="forumheading">Forums</h2>
<div class="forumlinks_widget">
<?php
global $bbdb;
$query="SELECT * FROM bb_topics WHERE topic_status=0 ORDER BY topic_time DESC";
$results=$bbdb->get_results($query);
foreach ($results as $result) {
echo "<a href='/ecp/forum/topic.php?id=".$result->topic_id."'>".$result->topic_title."</a>";
}
?>
</div>


<?php echo file_get_contents(bb_get_uri()."../?f_side=sidebar"); ?>

</div>