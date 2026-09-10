<?php
/**
 * Homepage.
 *
 * @package DWC_Group
 */

get_header();
?>

<main id="main" class="site-main site-main--home">
	<?php
	get_template_part( 'template-parts/home/hero' );
	get_template_part( 'template-parts/home/intro' );
	get_template_part( 'template-parts/home/services' );
	get_template_part( 'template-parts/home/why-dwc' );
	get_template_part( 'template-parts/home/fraud-audit-ai' );
	get_template_part( 'template-parts/home/publications' );
	get_template_part( 'template-parts/home/final-cta' );
	?>
</main>

<?php
get_footer();
