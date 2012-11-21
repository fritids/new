<?php get_header(); ?>

<?php if(is_user_logged_in()){ ?>
Home content goes here - for user that is logged in
<?php }else{ ?>
Home content goes here - for user that is not logged in
<?php } ?>
<?php get_footer(); ?>