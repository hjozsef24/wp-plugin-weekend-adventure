<?php

if (! defined('ABSPATH')) {
	exit;
}

class WA_Program_Status
{
	public function get_status(array $program, string $reference_time): array
	{
		if ($program['cancelled']) {
			return [
				'key'      => 'cancelled',
				'label'    => 'Törölve',
				'bookable' => false,
			];
		}

		if ($program['capacity'] <= 0) {
			return [
				'key'      => 'unavailable',
				'label'    => 'Nem elérhető',
				'bookable' => false,
			];
		}

		try {
			$start_at = new DateTimeImmutable($program['start_at']);
			$reference = new DateTimeImmutable($reference_time);
		} catch (Exception $e) {
			return [
				'key'      => 'unavailable',
				'label'    => 'Nem elérhető',
				'bookable' => false,
			];
		}

		if ($start_at <= $reference) {
			return [
				'key'      => 'past',
				'label'    => 'Lejárt',
				'bookable' => false,
			];
		}

		$capacity = (int) $program['capacity'];
		$booked   = (int) $program['booked'];

		if ($booked >= $capacity) {
			return [
				'key'      => 'full',
				'label'    => 'Betelt',
				'bookable' => false,
			];
		}

		$remaining = $capacity - $booked;

		if ($remaining <= $capacity * 0.20) {
			return [
				'key'      => 'few_left',
				'label'    => 'Kevés hely',
				'bookable' => true,
			];
		}

		return [
			'key'      => 'available',
			'label'    => 'Elérhető',
			'bookable' => true,
		];
	}
}