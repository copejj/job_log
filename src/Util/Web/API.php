<?php
namespace Jeff\Code\Util\Web;

abstract class API
{
	abstract protected function query(string $type, string $action, ?array $params=[]): array;

	/**
	 * Performs a POST to the v2 API
	 * @param string $action The action for the query (relative url portion)
	 * @param array $params The parameters that go with the query
	 * @return array The result of the query
	 */
	public function post(string $action, ?array $params=[]): array
	{
		return $this->query('POST', $action, $params);
	}

	/**
	 * Performs a GET to the v2 API
	 * @param string $action The action for the query (relative url portion)
	 * @param array $params The parameters that go with the query
	 * @return array The result of the query
	 */
	public function get(string $action): array
	{
		return $this->query('GET', $action);
	}

	/**
	 * Performs a PUT to the v2 API
	 * @param string $action The action for the query (relative url portion)
	 * @param array $params The parameters that go with the query
	 * @return array The result of the query
	 */
	public function put(string $action, ?array $params=[]): array
	{
		return $this->query('PUT', $action, $params);
	}

	/**
	 * Performs a DELETE to the v2 API
	 * @param string $action The action for the query (relative url portion)
	 * @param array $params The parameters that go with the query
	 * @return array The result of the query
	 */
	public function delete(string $action, ?array $params=[]): array
	{
		return $this->query('DELETE', $action, $params);
	}

	/**
	 * Performs a PATCH to the v2 API
	 * @param string $action The action for the query (relative url portion)
	 * @param array $params The parameters that go with the query
	 * @return array The result of the query
	 */
	public function patch(string $action, ?array $params=[]): array
	{
		return $this->query('PATCH', $action, $params);
	}
}
