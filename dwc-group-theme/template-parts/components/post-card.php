<?php
/**
 * Generic post card used by the blog index/archive/search results.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="post-card__media">
			<?php the_post_thumbnail( 'dwc-card', array( 'loading' => 'lazy' ) ); ?>
		</a>
	<?php endif; ?>
	<div class="post-card__body">
		<p class="post-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
		<h2 class="post-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p class="post-card__excerpt"><?php echo esc_html( dwc_group_trim( get_the_excerpt(), 20 ) ); ?></p>
		<a class="btn btn--text" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'dwc-group' ); ?> <span aria-hidden="true">&rarr;</span></a>
	</div>
</article>
