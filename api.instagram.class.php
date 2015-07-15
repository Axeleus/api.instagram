<?php

/**
 * Instagram
 * @author Maxim Doronin <maksimdoronin93@gmail.com>
 */
class Instagram
{
	public $userId = '';
	public $clientId = '';

	/**
	 * @param $argument
	 */
	function __construct($userName, $clientId)
	{
		if (isset($clientId) && !empty($clientId)) {
			$this->clientId = $clientId;
		}
		if (isset($userName) && !empty($userName)) {
			$this->setUserIdByName($userName);
		}
	}

	private function setUserIdByName($userName)
	{
		$url = "https://api.instagram.com/v1/users/search";
		$url .= "?q=" . $userName;
		$url .= "&client_id=" . $this->clientId;
		$data = json_decode($this->cUrl($url));
		return $this->userId = $data->data[0]->id;
	}

	/**
	 * @return Array
	 */
	public function getMedia()
	{
		$url = "https://api.instagram.com/v1/users/{$this->userId}/media/recent/?client_id=" . $this->clientId;
		$data = json_decode($this->cUrl($url));
		return $data->data;
	}

	/**
	 * @param $url
	 * @return JSON
	 */
	private function cUrl($url)
	{
		$ch = curl_init($url); // Инициализируем сессию cURL
		// Устанавливаем параметры cURL
		//curl_setopt($ch, CURLOPT_URL, $url); // Url страницы
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Возвращает веб-страницу
		curl_setopt($ch, CURLOPT_TIMEOUT, 20); // Таймаут ответа
		$result = curl_exec($ch); // Выполняем запрос
		curl_close($ch); // Завершаем сессию cUrl
		return $result;
	}
}
