<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
if(!is_user_logged_in()){
	wp_redirect(get_bloginfo("url"));
	die();
}else{
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $metadata = get_user_meta($user_id,_IDGL_elem_user_type,true);
    if ( $metadata != 'teacher' ){
        wp_redirect(get_bloginfo("url"));
        die();
    }
}

$student_id = $_GET['std'];
$type = $_GET['t'];
$available_types = array('satact','sat','act');
if ( ! $student_id OR ! $type OR ! in_array($type, $available_types)){
    wp_redirect(get_bloginfo("url"));
    die();
}else{
    $student = new WP_User($student_id);
    if ( ! $student->exists() OR $student->ID === 0 ){
        wp_redirect(get_bloginfo("url"));
        die();
    }
    $user_id = $student->ID;
}

enque_progress_table_styles();
get_header('home'); 

?>
<h5>Student: <i><?php echo $student->get('first_name').' '.$student->get('last_name'); ?></i></h5>
<div class="table-holder" id="onlineSATprogress">
    <ul>
        <?php switch ($type) { 
            case 'satact': ?>
                <li>
                    <a class="main-category"><span class="white-icons books"></span>SAT & ACT</a>
                    <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT and ACT"),$user_id,FALSE);echo $wpm->toString(); ?>
                </li>
                <?php break; ?>
            <?php case 'sat': ?>
                <li>
                    <a class="main-category"><span class="white-icons books"></span>SAT Only</a>
                    <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("SAT Only"),$user_id,FALSE);echo $wpm->toString(); ?>
                </li>
                <?php break; ?>
            <?php case 'act': ?>
                <li>
                    <a class="main-category"><span class="white-icons books"></span>ACT Only</a>
                    <?php $wpm=new Wp_Menu(wp_get_nav_menu_items("ACT Only"),$user_id,FALSE);echo $wpm->toString(); ?>
                </li>
                <?php break; ?>                
        <?php } ?>
    </ul>
</div>

<?php get_footer(); ?>