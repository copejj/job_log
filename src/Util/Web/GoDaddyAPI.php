<?php
namespace Jeff\Code\Util\Web;

use Jeff\Code\Util\Config;

class GoDaddyAPI extends API 
{
	private string $key = '';
	private string $secret = '';
	private string $baseUrl = 'https://api.godaddy.com/';

	public function __construct()
	{
		$this->key = Config::get('GODADDY_API_KEY');
		$this->secret = Config::get('GODADDY_API_SECRET');
		$this->baseUrl = Config::get('GODADDY_API_URL');
	}

	public function query(string $type, string $action, ?array $params = []): array
	{
		$ch = curl_init();
		$url = $this->baseUrl . ltrim($action, '/');
		if (!empty($params)) 
		{
			switch ($type)
			{
				case 'POST':
				case 'PUT':
				case 'PATCH':
				case 'DELETE':
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
					break;
				case 'GET':
				default:
					$url .= '?' . http_build_query($params);
					break;
			}
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $type);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Authorization: sso-key ' . $this->key . ':' . $this->secret,
			'Content-Type: application/json',
		]);
		$response = curl_exec($ch);
		if (curl_errno($ch)) {
			curl_close($ch);
			return ['error' => curl_error($ch)];
		}
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return [
			'http_code' => $httpCode,
			'response' => json_decode($response, true),
		];
	}
}