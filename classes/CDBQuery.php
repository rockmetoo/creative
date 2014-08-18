<?php

	include_once 'CSettings.php';
	include_once 'CLog.php';
	include_once 'CMemCacheD.php';
	
	$db		= new CDBQuery();
	
	class CDBQuery
	{
		private $connection			= NULL;
		private $defaultConnection	= 'creative';
		private $cacheDeactivate	= false;
		private $debug				= false;
		private $debugBenchmark		= false;
		private $debugExplain		= false;
		private $debugTurnOff		= false;
		private $isMemCacheCompress	= false;
		var		$registeredSql		= array();
		
		public function __construct($connection = '')
		{
			include_once('includes'. DIRECTORY_SEPARATOR . 'memcacheSqlValues.php');
			
			if($connection) $this->connection = $connection;
			else $this->connection = $this->defaultConnection;
			if(!extension_loaded('Memcache'))
			{
				trigger_error('No memcache servers to connect', E_USER_WARNING);
				$this->deactivateCache();
			}
		}
		
		public function checkTableExists($tableName)
		{
			return $this->checkTableExistsOther($this->connection, $tableName);
		}

		public function checkTableExistsOther($connection, $tableName)
		{
			$connection	= (string) $connection;
			$tableName	= (string) $tableName;
			
			// Get connection and run simple query to check if it exists or not
			$conn	= $this->connect($connection);
			$query	= "SELECT 1 FROM `$tableName` LIMIT 0";

			if(@$conn->query($query))
			{
				return true;
			}
			
			return false;
		}

		public function clearCache($query)
		{
			global $cache;
			
			if(!$this->cacheDeactivate)
			{
				CMemCacheD::delete($this->connection . md5($query));
			}
		}

		public function clearCacheKey($key)
		{
			global $cache;
			
			if(!$this->cacheDeactivate) CMemCacheD::delete($key);
		}
		
		/**
		 * Connect to the DataBase and return the connection
		 *
		 * @param string $conn Connection name
		 * @return mysqli
		 */
		public function connect($conn)
		{
			if(CSettings::$MYSQL_CONNECTION_POOL[$conn][5])
			{
				return CSettings::$MYSQL_CONNECTION_POOL[$conn][5];
			}
				
			// Initialize and return the mysqli object
			$mysqli = new mysqli(
				CSettings::$MYSQL_CONNECTION_POOL[$conn][0], CSettings::$MYSQL_CONNECTION_POOL[$conn][1],
				CSettings::$MYSQL_CONNECTION_POOL[$conn][2], CSettings::$MYSQL_CONNECTION_POOL[$conn][3],
				CSettings::$MYSQL_CONNECTION_POOL[$conn][4]
			);
			
			if($this->debug && mysqli_connect_errno())
			{
				echo nl2br("[ CONNECTION ERROR ]" . mysqli_connect_errno() . ": " . mysqli_connect_error());
			}

			CSettings::$MYSQL_CONNECTION_POOL[$conn][5] = $mysqli;
			
			return $mysqli;
		}
		
		public function getConnection($conn)
		{
			return CSettings::$MYSQL_CONNECTION_POOL[$conn][5];
		}
		
		public function deactivateCache()
		{
			$this->cacheDeactivate = true;
		}

		private function debug($connection, $query, &$conn, $return, $attempt_repair)
		{
			global $log;
			
			if(!isset($_SERVER['HTTP_HOST']))		$_SERVER['HTTP_HOST']	= 'devnew.simpleso.jp';
			if(!isset($_SERVER['REQUEST_URI']))		$_SERVER['REQUEST_URI']	= '';
			
			$message = '';
			@$message .= "[ ERROR ]\n" . $conn->errno . ": " . $conn->error . "\n";
			if(isset($_SERVER['HTTP_HOST'])) $message .= $_SERVER['HTTP_HOST'] . "/" . $_SERVER['REQUEST_URI'] . "\n";
			$message .= "--------------------------------------\nQuery : " . str_replace("\t", "", $query) . "\n";
			$message .= "--------------------------------------\nScript : " . $_SERVER['PHP_SELF'] . "\n";
			if(isset($_SERVER['REQUEST_URI'])) $message .= "Request URI : " . $_SERVER['REQUEST_URI'] . "\n";
			if(isset($_SERVER['REMOTE_ADDR'])) $message .= "Remote Addr : " . $_SERVER['REMOTE_ADDR'] . "\n";

			@$trace = debug_backtrace();
			
			$backtrace = "File : " . $trace[2]['file']."\n";
			$backtrace .= "Line : " . $trace[2]['line']."\n";
			$backtrace .= "Class : " . $trace[2]['class']."\n";
			$backtrace .= "Function : " . $trace[2]['function']."\n";
			$message .= "--------------------------------------\nBacktrace : \n" . $backtrace . "\n";
			
			$log->setLogFileName('mysql');
			$log->info($message);
		}

		public function insert($table, $param, $html=false)
		{
			return $this->insertOther($this->connection, $table, $param, $html);
		}

		public function insertOther($connection, $table, $param, $html=false)
		{
			foreach($param as $k=>$v)
				$param[$k] = $this->quoteInto('%s', sanitize($v, $html));

			$query = "
				INSERT INTO $table (".implode(', ', array_keys($param)).")
				VALUES (".implode(', ', $param).")
			";

			$conn = $this->queryOther($connection, $query, false, 'con');
			return $conn->insert_id;
		}
		
		public function insertAndReturnConn($connection, $table, $param, $html=false)
		{
			foreach($param as $k=>$v)
				$param[$k] = $this->quoteInto('%s', sanitize($v, $html));
		
			$query = "
			INSERT INTO $table (".implode(', ', array_keys($param)).")
				VALUES (".implode(', ', $param).")
			";
		
			$conn = $this->queryOther($connection, $query, false, 'con');
			return $conn;
		}

		public function duplicate($table, $insert, $update, $html=false)
		{
			return $this->duplicateOther($this->connection, $table, $insert, $update, $html);
		}

		public function duplicateOther($connection, $table, $insert, $update, $html=false)
		{
			$set_array = array();
			foreach($update as $k=>$v) $set_array[] = $this->quoteInto($k.' = %s', sanitize($v, $html));
			$set_str = implode(', ', $set_array);

			$keys_str = implode(", ", array_keys($insert));
			$values_array = array();
			foreach(array_values($insert) as $value)
			{
				$values_array[] = $this->quoteInto('%s', sanitize($value, $html));
			}
			$values_str = implode(', ', $values_array);
			$query = "
				INSERT INTO $table ($keys_str)
				VALUES ($values_str)
				ON DUPLICATE KEY UPDATE $set_str
			";

			$conn = $this->queryOther($connection, $query, false, 'con');
			return $conn->insert_id;
		}

		public function duplicateRemovePrimary($table, $insert, $primary, $html=false)
		{
			$update = $insert;
			if(is_array($primary))
			{
				foreach($primary as $foo)
				{
					unset($update[$foo]);
				}
			}
			else unset($update[$primary]);
			return $this->duplicateOther($this->connection, $table, $insert, $update, $html);
		}

		public function duplicateRemovePrimaryOther($connection, $table, $insert, $primary, $html=false)
		{
			$update = $insert;
			if(is_array($primary))
			{
				foreach($primary as $foo)
				{
					unset($update[$foo]);
				}
			}
			else unset($update[$primary]);
			return $this->duplicateOther($connection, $table, $insert, $update, $html);
		}

		public function update($table, $cond, $condval=0, $param=0, $html=false)
		{
			return $this->updateOther($this->connection, $table, $cond, $condval, $param, $html);
		}

		public function updateOther($connection, $table, $cond, $condval=0, $param=0, $html=false)
		{
			$set_array = array();
			foreach($param as $k=>$v) $set_array[] = $this->quoteInto($k.' = %s', sanitize($v, $html));
			$set_str = implode(', ', $set_array);
			$where_array = array();
			if(!is_array($cond)) $cond = array($cond=>$condval);
			foreach($cond as $k=>$v) $where_array[] = $this->quoteInto($k.' = %s', $v);
			$where_str = implode(' && ', $where_array);
			$query = "UPDATE $table SET $set_str WHERE $where_str";
			$con = $this->queryOther($connection, $query, false, 'con');
			
			$matches = array();
			preg_match('/.*?: (\d+).*/', $con->info, $matches);
			return intval($matches[1]);
		}

		public function microtime_float()
		{
			list($usec, $sec) = explode(" ", microtime());
			return ((float)$usec + (float)$sec);
		}

		public function query($query, $allow_cache = -1, $return = 'rs', $attempt_repair = 1, $force_explain = 0)
		{
			return $this->queryOther($this->connection, $query, $allow_cache, $return, $attempt_repair, $force_explain);
		}

		public function queryOther($connection, $query, $allow_cache = -1, $return = 'rs', $attempt_repair = 1, $force_explain = 0)
		{
			global $cache;
			
			if($allow_cache >= 0 && !$this->debugExplain && !$this->cacheDeactivate)
			{
				$cache_rs = CMemCacheD::get($connection . md5($query));
				
				if(isset($cache_rs) && $cache_rs && !empty($cache_rs))
				{
					return $cache_rs;
				}
			}
			$conn = $this->connect($connection);
			
			if($this->debug) echo nl2br(htmlspecialchars("[ DEBUG QUERY ]" . $query));
			
			if($this->debugBenchmark || $this->debugExplain) $time_start = $this->microtime_float();
			
			if(strpos($query, '%'))
			{
				$query = str_replace("'|", '', $query);
				$query = str_replace("|'", '', $query);
			}
			
			$rs = @$conn->query($query);
			
			if(!$rs)
			{
				$this->debug($connection, $query, $conn, $return, $attempt_repair);
				return -1;
			}
			
			if($this->debugBenchmark)
			{
				$time_end = $this->microtime_float();
				echo "\n<br>[ --- QUERY TOOK " . number_format(($time_end-$time_start), 5)." SECONDS --- ]" . nl2br(htmlspecialchars($query))."[ ---------------------------------------------- ]<br>\n";
			}
			
			if(($this->debugExplain || $force_explain) && stristr($query, 'SELECT'))
			{
				$time_end = $this->microtime_float();
				$rs2 = @$conn->query('EXPLAIN '.$query);
				echo '<div class="sql_explain">';
				    echo '<table>';
				        echo '<tr><td>Query</td><td colspan="8">'.nl2br(htmlspecialchars($query)).'</td></tr>';
				        echo '<tr><td>Seconds</td><td colspan="8">'.number_format(($time_end-$time_start), 5).'</td></tr>';
				        echo '<tr><td>id</td><td>select type</td><td>table</td><td>type</td><td>possible keys</td><td>key</td><td>ref</td><td>rows</td><td>extra</td></tr>';
        				while($foo = $rs2->fetch_assoc()){
        					echo '<tr><td>'.$foo['id'].'</td><td>'.$foo['select_type'].'</td><td>'.$foo['table'].'</td><td>'.$foo['type'].'</td><td>'.$foo['possible_keys'].'</td><td>'.$foo['key'].'</td><td>'.$foo['ref'].'</td><td>'.$foo['rows'].'</td><td>'.$foo['Extra'].'</td></tr>';
        				}
				    echo '</table>';
				echo '</div>';
			}
			
			if($this->debugTurnOff)
			{
				$this->debugTurnOff = false;
				$this->debug = false;
			}
			
			if($return == 'con') return $conn;
			else
			{
				if($allow_cache >= 0 && !$this->cacheDeactivate && stristr($query, 'SELECT') && !stristr($query, 'INSERT INTO') && !stristr($query, 'DELETE') && !stristr($query, 'UPDATE'))
				{
					try
					{
						CMemCacheD::set($connection . md5($query), new cacheMySQL($rs), $this->isMemCacheCompress, $allow_cache);
					}
					catch(Exception $e)
					{
						//error occured, do not cache
					}
					
					$rs->data_seek(0);
				}
				return $rs;
			}
		}
		/**
		 * Quote variable if needed
		 *
		 * @param mixed $var
		 * @return string
		 */
		public function quote($var)
		{
			switch(gettype($var))
			{
				case 'string':
				case 'double':
					return addslashes($var);
				case 'integer':
					return $var;
				case 'boolean':
					return ($var) ? 1 : 0;
				default:
					return '';
			}
		}
		/**
		 * Quote into query string
		 *
		 * @param string $format
		 * @param mixed $vars
		 * @return string
		 */
		public function quoteInto($format, $vars)
		{
			if(!is_array($vars)) $vars = array($vars);
			
			foreach($vars as $k=>$var)
			{
				if(is_int($var))
					$vars[$k] = $var;
				else
					$vars[$k] = '\''.$this->quote($var).'\'';
			}
			return vsprintf($format, $vars);
		}
		/**
		 * Submit a query, quoting given variables
		 *
		 * @param string $connection
		 * @param string $query
		 * @param mixed $vars
		 * @param int $allow_cache
		 * @param string $return
		 * @param boolean $attempt_repair
		 * @param boolean $force_explain
		 */
		public function quotedQueryOther($connection, $query, $vars, $allow_cache=-1, $return='rs', $attempt_repair=1, $force_explain=0)
		{
			return $this->queryOther($connection, $this->quoteInto($query, $vars), $allow_cache, $return, $attempt_repair, $force_explain);
		}
		
		/**
		 * Submit a query, quoting given variables
		 *
		 * @param string $query
		 * @param mixed $vars
		 * @param int $allow_cache
		 * @param string $return
		 * @param boolean $attempt_repair
		 * @param boolean $force_explain
		 */
		public function quotedQuery($query, $vars, $allow_cache=-1, $return='rs', $attempt_repair=1, $force_explain=0)
		{
			return $this->query($this->quoteInto($query, $vars), $allow_cache, $return, $attempt_repair, $force_explain);
		}
		
		/**
		 * Enable/Disable debugging
		 *
		 * @param boolean $bool
		 */
		public function setDebug($bool)
		{
			$this->debug = $bool;
		}

		/**
		 * Enable/Disable once-only debugging
		 *
		 * @param boolean $bool
		 */
		public function setDebugOnce($bool)
		{
			$this->setDebug($bool);
			$this->debugTurnOff = true;
		}
		
		/**
		 * Enable/Disable benchmarking
		 *
		 * @param boolean $bool
		 */
		public function setDebugBenchmark($bool)
		{
			$this->debugBenchmark = $bool;
		}
		
		/**
		 * Enable/Disable full debug information
		 *
		 * @param boolean $bool
		 */
		public function setDebugExplain($bool)
		{
			$this->debugExplain	= $bool;
		}
		/**
		 * Add ordering to query.
		 *
		 * @param string $default
		 * @param string|array $order_by
		 * @param int|array $order 0 for ASC, 1 for DESC
		 * @param string $query
		 * @return string
		 */
		public function setOrder($default, $order_by, $order, $query)
		{
			if(is_array($order_by))
			{
				$out = "$query ORDER BY";
				$count_order_by = count($order_by);
				if(is_array($order))
				{
					for($i=0; $i<$count_order_by; $i++)
					{
						if($order[$i]) $desc = 'DESC';
						else $desc = 'ASC';
						if($i>0) $out .= ',';
						$out .= ' '.$order_by[$i].' '.$desc;
					}
				}
				else
				{
					if($order) $desc = 'DESC';
					else $desc = 'ASC';
					for($i=0; $i<$count_order_by; $i++)
					{
						if($i>0) $out .= ',';
						$out .= ' '.$order_by[$i].' '.$desc;
					}
				}
			}
			else
			{
				if(!$order_by) $order_by = $default;
				elseif(!preg_match('/^[a-zA-Z0-9_.]+$/', $order_by)) $order_by = $default;
				if($order) $desc = 'DESC';
				else $desc = 'ASC';
				$out = "$query ORDER BY $order_by $desc";
			}
			return $out;
		}

		public function queryGet($index, $value = false, $quoteInto = false)
		{
			if(!$value && isset($this->registeredSql[$index])) return $this->registeredSql[$index];
			else if(is_array($value) && isset($this->registeredSql[$index]))
			{
				if($quoteInto)
				{
					return $this->quoteInto($this->registeredSql[$index], $value);
				}
				return vsprintf($this->registeredSql[$index], $value);
			}
			else if($value && isset($this->registeredSql[$index]))
			{
				if($quoteInto)
				{
					return $this->quoteInto($this->registeredSql[$index], $value);
				}
				return sprintf($this->registeredSql[$index], $value);
			}
			else
			{
				$message = "[ MISSING CACHE QUERY ] \n";
				$message .= $_SERVER['HTTP_HOST'] . "/" . $_SERVER['REQUEST_URI'] . "\n";
				$message .= "--------------------------------------\nIndex : ".$index."\n";
				$trace = debug_backtrace();
				$message .= "File : ".$trace[0]['file']."\n";
				$message .= "Line : ".$trace[0]['line']."\n";
				return $index;
			}
		}
	}

	function decode($foo, $to='CP932', $from='utf8')
	{
		if(is_array($foo))
		{
			reset($foo);
			while (list($k, $v) = each($foo)) $foo[$k] = mb_convert_encoding(stripslashes($v), $to, $from);
		}
		else $foo = mb_convert_encoding(stripslashes($foo), $to, $from);
		return $foo;
	}

	function encode($foo, $to='utf8', $from='CP932')
	{
		if(is_array($foo))
		{
			reset($foo);
			while (list($k, $v) = each($foo)) $foo[$k] = addslashes(mb_convert_encoding(mb_convert_kana($v, "KV"), $to, $from));
		}
		else $foo = addslashes(mb_convert_encoding(mb_convert_kana($foo, "KV"), $to, $from));

		return $foo;
	}
	
	//Stripslashes for multibyte characters
	function stripslashes2($foo)
	{
		if(is_array($foo)) reset($foo);
		if(is_array($foo))
		{
			while (list($k, $v) = each($foo))
			{
				$foo[$k] = str_replace("\\\"", "\"", $foo[$k]);
	 			$foo[$k] = str_replace("\\'", "'", $foo[$k]);
				$foo[$k] = str_replace("\\\\", "\\", $foo[$k]);
			}
		}
		else
		{
			$foo = str_replace("\\\"", "\"", $foo);
	   		$foo = str_replace("\\'", "'", $foo);
	   		$foo = str_replace("\\\\", "\\", $foo);
		}
	   	return $foo;
	}

	function sanitize($foo, $html=false)
	{
		if($html)
		{
			return sanitize_html($foo);
			exit;
		}

		if(is_array($foo))
		{
			reset($foo);
			while (list($k, $v) = each($foo))
			{
				// just to be sure we dont strip out large chunks of text
				$foo[$k] = preg_replace('/\<([^\>;"]{1,6})\>/u', '*$1*', $foo[$k]);
				$foo[$k] = preg_replace('/&lt;([^&;"]{1,6})&gt;/u', '*$1*', $foo[$k]);
			}
		}
		else
		{
			// just to be sure we dont strip out large chunks of text
			$foo = preg_replace('/\<([^\>;"]{1,6})\>/u', '*$1*', $foo);
			$foo = preg_replace('/&lt;([^&;"]{1,6})&gt;/u', '*$1*', $foo);
		}

		return $foo;
	}

	function sanitize_html($foo)
	{
		return $foo;
	}

	function unsanitize($foo)
	{
		return stripslashes2(strtr($foo, array('&amp;'=>'&', '&rsquo;'=>"'")));
	}

	class cacheMySQL
	{
		private $rs = array();
		public function __construct(&$resultset)
		{
			if($foo = $resultset->fetch_assoc())
			{
				$limit = 8 * 1024 * 1024;
				if(array_sum($resultset->lengths) * $resultset->num_rows > $limit)
				{
					throw new Exception('Will exceed memory limit. Will not fit in cache.');
				}
				$this->rs[] = $foo;
				while ($foo = $resultset->fetch_assoc())
				{
					if(memory_get_usage() > $limit) throw new Exception('Memory limit exceeded. Will not fit in cache.');
					$this->rs[] = $foo;
				}
			}
			reset($this->rs);
		}

		public function __get($name)
		{
			switch($name)
			{
				case 'num_rows':
					return count($this->rs);
				default:
					return;
			}
		}

		public function fetch_assoc()
		{
			list($void, $rs) = each($this->rs);
			if(is_array($rs)) return $rs;
			else return false;
		}

		public function fetch_row()
		{
			list($void, $rs) = each($this->rs);
			if(is_array($rs)) return array_values($rs);
			else return false;
		}

		public function data_seek($row)
		{
			reset($this->rs);
			if($row) for ($i=0; $i<$row; $i++) next($this->rs);
		}
	}