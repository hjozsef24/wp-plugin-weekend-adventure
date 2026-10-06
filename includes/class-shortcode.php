<?php

if (! defined('ABSPATH')) {
	exit;
}

class WA_Shortcode
{

	public function __construct()
	{
		add_shortcode('hetvegi_kalandmento', [$this, 'render']);
		add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
	}

	public function enqueue_assets()
	{
		wp_enqueue_style(
			'wa-frontend',
			WA_PLUGIN_URL . 'assets/css/frontend.css',
			[],
			WA_VERSION
		);

		wp_enqueue_script(
			'wa-frontend',
			WA_PLUGIN_URL . 'assets/js/frontend.js',
			['jquery'],
			WA_VERSION,
			true
		);
	}

	public function render()
	{
		return '
		<section class="wa" aria-labelledby="wa-title">
			<h2 id="wa-title" class="wa__title">Hétvégi Kalandmentő</h2>

			<div class="wa__programs js-programs-container">
				<p class="wa__loading">Programok betöltése...</p>
			</div>
		</section>
	';
	}
}
