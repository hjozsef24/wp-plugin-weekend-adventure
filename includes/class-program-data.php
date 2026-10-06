<?php

if (! defined('ABSPATH')) {
	exit;
}

class WA_Program_Data
{

	private string $file_path;

	public function __construct()
	{
		$this->file_path = WA_PLUGIN_DIR . 'data/programs.json';
	}

	public function get_data(): array
	{
		if (!file_exists($this->file_path)) {
			return [];
		}

		$json = file_get_contents($this->file_path);

		if (false === $json) {
			return [];
		}

		$data = json_decode($json, true);

		if (!is_array($data)) {
			return [];
		}

		return $data;
	}

	public function get_programs(): array
	{
		$data = $this->get_data();

		if (!isset($data['programs']) || !is_array($data['programs'])) {
			return [];
		}

		return $data['programs'];
	}

	public function get_reference_time(): ?string
	{
		$data = $this->get_data();

		if (empty($data['reference_time']) || !is_string($data['reference_time'])) {
			return null;
		}

		return $data['reference_time'];
	}
}
