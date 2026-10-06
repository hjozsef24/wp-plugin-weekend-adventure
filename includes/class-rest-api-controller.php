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
			$program['status'] = $this->program_status->get_status($program, $reference_time);
			$result[] = $program;
		}

		$status_order = [
			'available'   => 1,
			'few_left'    => 2,
			'full'        => 3,
			'cancelled'   => 4,
			'past'        => 5,
			'unavailable' => 6,
		];

		usort(
			$result,
			function ($a, $b) use ($status_order) {
				$a_order = $status_order[$a['status']['key']];
				$b_order = $status_order[$b['status']['key']];

				if ($a_order === $b_order) {
					return strtotime($a['start_at']) <=> strtotime($b['start_at']);
				}

				return $a_order <=> $b_order;
			}
		);

		foreach ($result as &$program) {
			$program['start_at'] = wp_date('Y. F j. H:i', strtotime($program['start_at']));
			$program['price_huf'] = number_format($program['price_huf'], 0, ',', ' ') . ' Ft';
		}

		unset($program);

		return new WP_REST_Response($result);
	}
}
