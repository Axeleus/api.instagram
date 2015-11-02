<?php
/**
 * Instagram
 * @author Maxim Doronin <maksimdoronin93@gmail.com>
 */
class Instagram
{
	public $userId;
	public $clientId;

	/**
	 * @param $user
	 * @param $clientId
	 */
	function __construct($user, $clientId)
	{
		if (isset($clientId) && !empty($clientId)) {
			$this->clientId = $clientId;
		}
		if (isset($user) && !empty($user)) {
			if (!is_int($user)) {
				$data = $this->getSearch($user);
				$user = $data->data[0]->id;
			}
			$this->userId = $user;
		}
	}

	/**
	 * Get basic information about a user. To get information about the owner of the access token, you can use self instead of the user-id.
	 * @return Array
	 */
	public function getUser()
	{
		$url = "https://api.instagram.com/v1/users/{$this->userId}/";
		$url .= "?client_id=" . $this->clientId;
		return $this->cUrl($url);
	}

	/**
	 * Get the most recent media published by a user. To get the most recent media published by the owner of the access token, you can use self instead of the user-id.
	 * @param $count - Count of media to return.
	 * @param $minId - Return media later than this min_id.
	 * @param $maxId - Return media earlier than this max_id.
	 * @return Array
	 */
	public function getMedia($count = false, $minId = false, $maxId = false)
	{
		$url = "https://api.instagram.com/v1/users/{$this->userId}/media/recent/";
		$url .= "?client_id=" . $this->clientId;
		if ($count) $url .= "&count=" . $count;
		if ($minId) $url .= "&min_id=" . $minId;
		if ($maxId) $url .= "&max_id=" . $maxId;
		return $this->cUrl($url);
	}

	/**
	 * Search for a user by name.
	 * @param $q - A query string.
	 * @return Array
	 */
	public function getSearch($q)
	{
		$url = "https://api.instagram.com/v1/users/search";
		$url .= "?q=" . $q;
		$url .= "&client_id=" . $this->clientId;
		return $this->cUrl($url);
	}

	/**
	 * @param $url - A query url
	 * @return Array
	 */
	private function cUrl($url)
	{
		$ch = curl_init($url); // Инициализируем сессию cURL
		// Устанавливаем параметры cURL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Возвращает веб-страницу
		curl_setopt($ch, CURLOPT_TIMEOUT, 20); // Таймаут ответа
		$result = curl_exec($ch); // Выполняем запрос
		curl_close($ch); // Завершаем сессию cUrl
		return json_decode($result);
	}
}
