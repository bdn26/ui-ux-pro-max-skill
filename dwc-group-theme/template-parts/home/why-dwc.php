<?php
/**
 * "Why DWC" -- four numbered principles.
 *
 * @package DWC_Group
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$numbers = array( '01', '02', '03', '04' );

$why_defaults = array(
	1 => array( __( 'Accuracy', 'dwc-group' ), __( 'Reliable processes, documentation, and financial and administrative support.', 'dwc-group' ) ),
	2 => array( __( 'Transparency', 'dwc-group' ), __( 'Clear processes and well-documented work designed to support organizational accountability.', 'dwc-group' ) ),
	3 => array( __( 'Compliance', 'dwc-group' ), __( 'Solutions aligned with applicable policies, regulations, and reporting requirements.', 'dwc-group' ) ),
	4 => array( __( 'Practical Solutions', 'dwc-group' ), __( 'Consulting designed around real operational needs rather than theory alone.', 'dwc-group' ) ),
);
?>
<section class="section why-dwc" data-dwc-reveal>
	<div class="container">
		<?php
		get_template_part(
			'template-parts/components/section-heading',
			null,
			array(
				'dwc_heading' => array(
					'heading' => get_theme_mod( 'dwc_why_heading', __( 'Built Around Accuracy, Transparency & Accountability', 'dwc-group' ) ),
					'align'   => 'center',
				),
			)
		);
		?>

		<div class="why-dwc__grid">
			<?php foreach ( $numbers as $i => $number ) : ?>
				<?php
				$index = $i + 1;
				get_template_part(
					'template-parts/components/numbered-principle',
					null,
					array(
						'dwc_principle' => array(
							'number' => $number,
							'title'  => get_theme_mod( "dwc_why_{$index}_title", $why_defaults[ $index ][0] ),
							'text'   => get_theme_mod( "dwc_why_{$index}_text", $why_defaults[ $index ][1] ),
						),
					)
				);
				?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
