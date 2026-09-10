<?php
/**
 * Minimal, styled comments template.
 *
 * @package DWC_Group
 */

if ( post_password_required() ) {
	return;
}
?>
<div id="comments" class="comments-area">

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			printf(
				/* translators: %s: comment count */
				esc_html( _n( '%s Comment', '%s Comments', get_comments_number(), 'dwc-group' ) ),
				esc_html( number_format_i18n( get_comments_number() ) )
			);
			?>
		</h2>
		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'      => 'ol',
					'short_ping' => true,
				)
			);
			?>
		</ol>
		<?php the_comments_navigation(); ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'dwc-group' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'class_submit' => 'btn btn--primary',
		)
	);
	?>
</div>
