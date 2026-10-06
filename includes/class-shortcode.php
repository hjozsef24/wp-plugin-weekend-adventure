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
		$data = new WA_Program_Data();
		$status = new WA_Program_Status();

		$programs = $data->get_programs();
		$reference_time = $data->get_reference_time();

		$result = [];

		foreach ($programs as $program) {
			$result[] = [
				'id'     => $program['id'] ?? null,
				'title'  => $program['title'] ?? null,
				'status' => $status->get_status(
					$program,
					$reference_time
				),
			];
		}

		return sprintf(
			'<pre>%s</pre>',
			esc_html(
				print_r($result, true)
			)
		);
	}
}
