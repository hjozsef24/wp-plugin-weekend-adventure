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
			[],
			WA_VERSION,
			true
		);
	}

	public function render()
	{
		return '<h2 class="wa__title">Hétvégi Kalandmentő</h2>';
	}
}
