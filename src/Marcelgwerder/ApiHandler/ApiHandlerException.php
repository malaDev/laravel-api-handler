<?php namespace Marcelgwerder\ApiHandler;

class ApiHandlerException extends \Exception
{
	/**
	 * Http status code
	 * 
	 * @var int
	 */
	protected $httpCode;

	/**
	 * Create a new instance of ApiHandlerException
	 * 
	 * @param string $code   
	 * @param array  $replace
	 * @param string $message
	 */
	public function __construct($code, $replace = array(), $message = null) 
	{
		$config = app()->make('config');

		//Set the package
		$config->package('marcelgwerder/laravel-api-handler', 'laravel-api-handler');

		$errors = $config->get('laravel-api-handler::errors');
		$internalErrors = $config->get('laravel-api-handler::internal_errors');

		//Check if error is internal or not
		if(isset($internalErrors[$code]))
		{
			$code = $internalErrors[$code];
		}

		$error = $errors[$code];

		if($message == null) 
		{
			$message = $error['message'];
		}

		$this->httpCode = $error['http_code'];  
		$this->code = $code;

		//Replace replacement values
		foreach($replace as $key => $value)
		{
			$message = str_replace(':'.$key, $value, $message);
		}

		parent::__construct($message);
	}

	/**
	 * Get the http code of the exception
	 * 
	 * @return int|string
	 */
	public function getHttpCode()
	{
		return $this->httpCode;
	}
}