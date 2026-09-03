<?php
/**
 * Default template for static WordPress pages (About, FAQ, Shipping,
 * Returns, Privacy, Terms, etc.) — editorial, single-column, generous
 * whitespace to match the rest of the storefront.
 */

get_header();
?>
<div class="bdna-page bdna-section">
	<div class="bdna-container bdna-page__inner">
		<?php beautydna_breadcrumbs(); ?>
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<header class="bdna-page__header">
				<h1 class="bdna-heading-lg"><?php the_title(); ?></h1>
			</header>
			<div class="bdna-page__content">
				<?php the_content(); ?>
			</div>
			<?php
		endwhile;
		?>
	</div>
</div>
<?php
get_footer();
