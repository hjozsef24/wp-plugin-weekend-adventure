<?php

if (!defined('ABSPATH')) {
	exit;
}

class WA_REST_API_Controller
{
	public function __construct(
		private WA_Program_Data $program_data,
		private WA_Program_Status $program_status
	) {
		add_action('rest_api_init', [$this, 'register_routes']);
	}

	public function register_routes(): void
	{
		register_rest_route(
			'wa/v1',
			'/programs',
			[
				'methods'  => WP_REST_Server::READABLE,
				'callback' => [$this, 'get_programs'],
				'permission_callback' => '__return_true',
			]
		);
	}

	public function get_programs(): WP_REST_Response
	{
		$programs = $this->program_data->get_programs();
		$reference_time = $this->program_data->get_reference_time();

		$result = [];

		foreach ($programs as $program) {
			$program['start_at'] = wp_date('Y. F j. H:i', strtotime($program['start_at']));

			$program['price_huf'] = number_format($program['price_huf'], 0, ',', ' ') . ' Ft';
			$program['status'] = $this->program_status->get_status($program, $reference_time);

			$result[] = $program;
		}

		return new WP_REST_Response($result);
	}
}
