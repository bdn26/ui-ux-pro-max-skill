<?php
/**
 * Accessible search form.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dwc_search_id = 'search-form-' . wp_unique_id();
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $dwc_search_id ); ?>" class="screen-reader-text"><?php esc_html_e( 'Search for:', 'dwc-group' ); ?></label>
	<input type="search" id="<?php echo esc_attr( $dwc_search_id ); ?>" class="search-form__field" placeholder="<?php esc_attr_e( 'Search the site&hellip;', 'dwc-group' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-form__submit">
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'dwc-group' ); ?></span>
		<span aria-hidden="true">&rarr;</span>
	</button>
</form>
