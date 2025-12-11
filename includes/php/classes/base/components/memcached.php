<?php
class Memcached {
	
	private $memcache;
	private $time_life;
	private $compress;
 
	/**
	*
	* @param string $host – хост сервера memcached
	* @param int $port – порт сервера memcached
	* @param int $compress – [0,1], сжимать или нет данные перед
	* помещением в память
	*/
	
	public function __construct($host, $port = 11211, $compress = 0)
	{
		$this->memcache = new Memcache;
		$this->memcache->connect($host, $port);
		$this->compress = ($compress) ? MEMCACHE_COMPRESSED : 0;
	}
	
	/**
	*
	* @param string or array $key – ключ или массив ключей, которые необходимо получить
	*/
	 
	public function get($key)
	{
		if(!$key)
			system_die('Required argument $valueID not specified','Memcached -> get');
			
		if(!is_string($key) && !is_array($key))
			system_die('Invalid argument $valueID not specified','Memcached -> get');
			
		return $this->memcache->get($key);
	}
	 
	/**
	*
	* @param string $key – ключ, значение которого необходимо установить
	* @param $value – значение
	* @param $time_life – время жизни ключа (в секундах или UNIX DATE)
	*/
	
	public function set($key, $value, $time_life = 0)
	{
		if(!$key)
			system_die('Required argument $valueID not specified','Memcached -> set');
			
		if(!is_string($key))
			system_die('Invalid argument $valueID not specified','Memcached -> set');
		
		if(!is_numeric($time_life))
			system_die('Invalid input argument $time_life','Memcached -> set');
			
		return $this->memcache->set($key, $value, $this->compress, $time_life);
	}
	
	/**
	*
	* @param string $key – ключ, значение которого необходимо заменить
	* @param $value – значение
	* @param $time_life – время жизни ключа (в секундах или UNIX DATE)
	*/
	
	public function replace($key, $value, $time_life = 0)
	{
		if(!$key)
			system_die('Required argument $valueID not specified','Memcached -> replace');
			
		if(!is_string($key))
			system_die('Invalid argument $valueID not specified','Memcached -> replace');
		
		if(!is_numeric($time_life))
			system_die('Invalid input argument $time_life','Memcached -> replace');
			
		return $this->memcache->replace($key, $value, $this->compress, $time_life);
	}
	
	/**
	*
	* @param string $key – ключ
	* @param int $inc_val – число на которое необходимо увеличить значение ключа
	*/
	
	public function increment($key, $inc_val)
	{
		if(!$key)
			system_die('Required argument $valueID not specified','Memcached -> increment');
		
		if(!is_numeric($inc_val))
			system_die('Invalid input argument $time_life','Memcached -> increment');
			
		return $this->memcache->increment($key, $inc_val);
	}
	
	/**
	*
	* @param string $key – ключ
	* @param int $dec_val – число на которое необходимо уменьшить значение ключа
	*/
	
	public function decrement($key, $dec_val)
	{
		if(!$key)
			system_die('Required argument $valueID not specified','Memcached -> increment');
		
		if(!is_numeric($inc_val))
			system_die('Invalid input argument $time_life','Memcached -> increment');
			
		return $this->memcache->decrement($key, $dec_val);
	}
	 
	public function delete($key)
	{
		if(!$key)
			system_die('Required argument $valueID not specified','Memcached -> delete');
			
		$this->memcache->delete($key);
	}
	
	public function flush()
	{
		$this->memcache->flush();
	}
	 
	public function __destruct()
	{
		$this->memcache->close();
	}
}
?>