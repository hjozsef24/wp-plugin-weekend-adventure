<?php

if (! defined('ABSPATH')) {
	exit;
}

class WA_Program_Status
{

	public function get_status(array $program, string $reference_time): string
	{
		if ($program['cancelled']) {
			return 'cancelled';
		}

		if ($program['capacity'] <= 0) {
			return 'unavailable';
		}

		try {
			$start_at = new DateTimeImmutable($program['start_at']);
			$reference = new DateTimeImmutable($reference_time);
		} catch (Exception $e) {
			return 'unavailable';
		}

		if ($start_at <= $reference) {
			return 'past';
		}

		$capacity = (int) $program['capacity'];
		$booked   = (int) $program['booked'];

		if ($booked >= $capacity) {
			return 'full';
		}

		$remaining = $capacity - $booked;

		if ($remaining <= $capacity * 0.20) {
			return 'few_left';
		}

		return 'available';
	}
}
