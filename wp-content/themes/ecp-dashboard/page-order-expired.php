<?php get_header('home'); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); global $post; ?>

<h1><?php the_title(); ?></h1>
	<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
		<?php the_content(); ?>
	</div>
<?php endwhile; endif; ?>

<div class="goto-cart-container">
	<a href="http://edgeincollegeprep.com/enroll/cart/" id="goto-cart">GO TO CART</a>
</div>

<style>
	p { font-size: 20px; margin-top: 50px; }
</style>

<?php get_footer(); ?>