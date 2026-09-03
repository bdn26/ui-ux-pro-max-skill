<?php
/**
 * Homepage. Assembles the editorial sections in order — each section is
 * a standalone template-part so sections can be reordered, removed, or
 * A/B tested independently.
 */

get_header();
?>

<?php get_template_part( 'template-parts/hero' ); ?>
<?php get_template_part( 'template-parts/category-grid' ); ?>
<?php get_template_part( 'template-parts/best-sellers' ); ?>
<?php get_template_part( 'template-parts/shop-by-concern' ); ?>
<?php get_template_part( 'template-parts/featured-product' ); ?>
<?php get_template_part( 'template-parts/brand-story' ); ?>
<?php get_template_part( 'template-parts/ingredient-story' ); ?>
<?php get_template_part( 'template-parts/social-proof' ); ?>

<?php
get_footer();
