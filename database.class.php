<?php
//PHP Flat File Simple Key/Val Database Cache v0.1 - Simple PHP Database in 150 lines of code.
//Copyright (c) Benjamin Dahrooge 2015
//MIT License

class database {
	public $path;
	public $database;
	protected $d_settings;
	protected $dev_mode;

	public function __construct($database_name = 'default', $path = 'database/', $dev_mode = true)
	{
		$this->path = $path;
		$this->database = $path . md5($database_name) . '.json';
		$this->d_settings = $path . 'd_settings.json';
		$this->dev_mode = true;

		if(!file_exists($path)) {mkdir($path);}

		file_put_contents($this->d_settings, '{"dev_mode": ' . $this->dev_mode . ', "recent_update": ' . time() . '}');
	}

	private function handleError($text, $override = false)
	{
		if($this->dev_mode == true || $override == true)
		{
			echo '<br>Error: ' . $text;
		}
	}

	public function getDatabaseContent()
	{
		if(file_exists($this->database))
		{
		$data_file = file_get_contents($this->database);
		return (array) json_decode($data_file);
		}
		else
		{
			return array();
		}
	}

	public function getPointer()
	{
		return count($this->getDatabaseContent());
	}

	private function newDatabase()
	{
		file_put_contents($this->database, '{"d_info_created" : ' . time() . '}'); 
		return true;
	}

	public function set($key, $value, $expire = 0)
	{
		if(!is_string($key))
		{
			$this->handleError('Key must be a string, not "' . gettype($key) . '"');
		}
		else
		{
			$var_type = gettype($value);
			$data = array();
			$data[$key]['a'] = time();
			$data[$key]['t'] = substr($var_type, 0, 1);
			$data[$key]['d'] = $value;

			if(isset($expire) && is_numeric($expire) && $expire >= 1)
			{
				//the expire time is calucated based on the creation time
				$data['e'] = $expire;
			}

			if(file_exists($this->database))
			{
				$data_array = $this->getDatabaseContent();
				$new_array = array_merge($data_array, $data);
				file_put_contents($this->database, json_encode($new_array));
				return true;
			}
			else
			{
				if($this->newDatabase() == true)
				{
					$data_array = $this->getDatabaseContent();
					$new_array = array_merge($data_array, $data);
					file_put_contents($this->database, json_encode($new_array));
					return true;
				}
				else
				{
					$this->handleError('Failed to create new database, read and write permissions are required', true);
					return false;
				}
			}
		}
	}

	public function fileOps($key, $request)
	{
		$data_file = $this->getDatabaseContent();
		if(isset($data_file[$key]))
		{
			switch ($request)
			{
				case 'delete':
					unset($data_file[$key]);
					return is_numeric(file_put_contents($this->database, json_encode($data_file)));
				break;
				case 'search':
					return true;
				break;
				case "return":
					return $data_file[$key]->d;
			}
		}
		else
		{
			return false;
		}
	}

	public function del($key)
	{ return $this->fileOps($key, 'delete'); }

	public function get($key)
	{
		return $this->fileOps($key, 'return');
	}

	public function search($key)
	{
		return $this->fileOps($key, 'search');
	}

	public function getAll($include_meta_data = false)
	{
		if($include_meta_data == true)
		{
			return array_shift(json_decode(file_get_contents($this->datatbase)));
		}
		else
		{
			return json_decode(file_get_contents($this->database));
		}
	}
}
?>
