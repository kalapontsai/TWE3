<?php
/* -----------------------------------------------------------------------------------------
   $Id: query_factory.php, 2010/09/15 22:38:22 oldpa   Exp $
   TWE-Commerce - community made shopping
   http://www.oldpa.com.tw
   Copyright (c) 2003 TWE-Commerce
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------*/
   /**
 * MySQL query_factory Class.
 * Class used for database abstraction to MySQL
 *
 * @package classes
 * @copyright Copyright 2003-2010 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: query_factory.php 17549 2010-09-12 17:35:32Z drbyte $
 */
 /**
 * cache Class.
 *
 * @package classes
 * @copyright Copyright 2003-2009 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: cache.php 14864 2009-11-18 16:22:05Z wilt $
 */
class queryFactory {
  var $link, $count_queries, $total_query_time;

  function queryFactory() {
    $this->count_queries = 0;
    $this->total_query_time = 0;
  }

  function connect($zf_host, $zf_user, $zf_password, $zf_database, $zf_pconnect = 'false', $zp_real = false) {
    $this->database = $zf_database;
    $this->user = $zf_user;
    $this->host = $zf_host;
    $this->password = $zf_password;
    $this->pConnect = $zf_pconnect;
    $this->real = $zp_real;
    if (!function_exists('mysql_connect')) die ('Call to undefined function: mysql_connect().  Please install the MySQL Connector for PHP');
    $connectionRetry = 10;
    while (!isset($this->link) || ($this->link == FALSE && $connectionRetry !=0) )
    {
      $this->link = @mysql_connect($zf_host, $zf_user, $zf_password, true);
      $connectionRetry--;
    }
    if ($this->link) {
      if (@mysql_select_db($zf_database, $this->link)) {
        if (defined('DB_CHARSET') && version_compare(@mysql_get_server_info(), '4.1.0', '>=')) {
          @mysql_query("SET NAMES '" . DB_CHARSET . "'", $this->link);
          if (function_exists('mysql_set_charset')) {
            @mysql_set_charset(DB_CHARSET, $this->link);
          } else {
            @mysql_query("SET CHARACTER SET '" . DB_CHARSET . "'", $this->link);
          }
        }
        $this->db_connected = true;
        return true;
      } else {
        $this->set_error(mysql_errno(),mysql_error(), $zp_real);
        return false;
      }
    } else {
      $this->set_error(mysql_errno(),mysql_error(), $zp_real);
      return false;
    }
  }

  function selectdb($zf_database) {
    @mysql_select_db($zf_database, $this->link);
  }

  function prepare_input($zp_string) {
    if (function_exists('mysql_real_escape_string')) {
      return mysql_real_escape_string($zp_string, $this->link);
    } elseif (function_exists('mysql_escape_string')) {
      return mysql_escape_string($zp_string, $this->link);
    } else {
      return addslashes($zp_string);
    }
  }

  function close() {
    @mysql_close($this->link);
  }

  function set_error($zp_err_num, $zp_err_text, $zp_fatal = true) {
    $this->error_number = $zp_err_num;
    $this->error_text = $zp_err_text;
    if ($zp_fatal && $zp_err_num != 1141) { // error 1141 is okay ... should not die on 1141, but just continue on instead
      $this->show_error();
      die();
    }
  }

  function show_error() {
    if ($this->error_number == 0 && $this->error_text == DB_ERROR_NOT_CONNECTED && !headers_sent() && file_exists('nddbc.html') ) include('nddbc.html');
    echo '<div class="systemError">';
    echo $this->error_number . ' ' . $this->error_text;
    echo '<br />in:<br />[' . (strstr($this->zf_sql, 'db_cache') ? 'db_cache table' : $this->zf_sql) . ']<br />';
    //if (defined('IS_ADMIN_FLAG') && IS_ADMIN_FLAG==true) echo 'If you were entering information, press the BACK button in your browser and re-check the information you had entered to be sure you left no blank fields.<br />';
    echo '</div>';
  }

  function Execute($zf_sql, $zf_limit = false, $zf_cache = 'false', $zf_cachetime=0) {
    global $zc_cache;
    if ($zf_limit) {
      $zf_sql = $zf_sql . ' LIMIT ' . $zf_limit;
    }
    $this->zf_sql = $zf_sql;
    if ( $zf_cache== 'true' AND $zc_cache->sql_cache_exists($zf_sql) AND !$zc_cache->sql_cache_is_expired($zf_sql, $zf_cachetime) ) {
      $obj = new queryFactoryResult;
      $obj->cursor = 0;
      $obj->is_cached = true;
      $obj->sql_query = $zf_sql;
      $zp_result_array = $zc_cache->sql_cache_read($zf_sql);
      $obj->result = $zp_result_array;
      if (sizeof($zp_result_array) > 0 ) {
        $obj->EOF = false;
        while (list($key, $value) = each($zp_result_array[0])) {
          $obj->fields[$key] = $value;
        }
        return $obj;
      } else {
        $obj->EOF = true;
      }
    } elseif ($zf_cache == 'true') {
      $zc_cache->sql_cache_expire_now($zf_sql);
      $time_start = explode(' ', microtime());
      $obj = new queryFactoryResult;
      $obj->sql_query = $zf_sql;
      if (!$this->db_connected)
      {
        if (!$this->connect($this->host, $this->user, $this->password, $this->database, $this->pConnect, $this->real))
        $this->set_error('0', DB_ERROR_NOT_CONNECTED);
      }
      $zp_db_resource = @mysql_query($zf_sql, $this->link);
      if (!$zp_db_resource) $this->set_error(@mysql_errno(),@mysql_error());
      if(!is_resource($zp_db_resource)){
        $obj = null;
        return true;
      }
      $obj->resource = $zp_db_resource;
      $obj->cursor = 0;
      $obj->is_cached = true;
      if ($obj->RecordCount() > 0) {
        $obj->EOF = false;
        $zp_ii = 0;
        while (!$obj->EOF) {
          $zp_result_array = @mysql_fetch_array($zp_db_resource);
          if ($zp_result_array) {
            while (list($key, $value) = each($zp_result_array)) {
              if (!preg_match('/^[0-9]/', $key)) {
                $obj->result[$zp_ii][$key] = $value;
              }
            }
          } else {
            $obj->Limit = $zp_ii;
            $obj->EOF = true;
          }
          $zp_ii++;
        }
        while (list($key, $value) = each($obj->result[$obj->cursor])) {
          if (!preg_match('/^[0-9]/', $key)) {
            $obj->fields[$key] = $value;
          }
        }
        $obj->EOF = false;
      } else {
        $obj->EOF = true;
      }
      $zc_cache->sql_cache_store($zf_sql, $obj->result);
      $time_end = explode (' ', microtime());
      $query_time = $time_end[1]+$time_end[0]-$time_start[1]-$time_start[0];
      $this->total_query_time += $query_time;
      $this->count_queries++;
	/* print_r('<table  width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="90%" scope="col">'.$this->zf_sql.'</td>
    <td scope="col">'.number_format($query_time,4).'</td>
  </tr>
</table>');*/
      return($obj);
    } else {
      $time_start = explode(' ', microtime());
      $obj = new queryFactoryResult;
      if (!$this->db_connected)
      {
        if (!$this->connect($this->host, $this->user, $this->password, $this->database, $this->pConnect, $this->real))
        $this->set_error('0', DB_ERROR_NOT_CONNECTED);
      }
      $zp_db_resource = @mysql_query($zf_sql, $this->link);
      if (!$zp_db_resource) {
        if (@mysql_errno($this->link) == 2006) {
          $this->link = FALSE;
          $this->connect($this->host, $this->user, $this->password, $this->database, $this->pConnect, $this->real);
          $zp_db_resource = @mysql_query($zf_sql, $this->link);
        }
        if (!$zp_db_resource) {
          $this->set_error(@mysql_errno($this->link),@mysql_error($this->link));
        }
      }
      if(!is_resource($zp_db_resource)){
        $obj = null;
        return true;
      }
      $obj->resource = $zp_db_resource;
      $obj->cursor = 0;
      if ($obj->RecordCount() > 0) {
        $obj->EOF = false;
        $zp_result_array = @mysql_fetch_array($zp_db_resource);
        if ($zp_result_array) {
          while (list($key, $value) = each($zp_result_array)) {
            if (!preg_match('/^[0-9]/', $key)) {
              $obj->fields[$key] = $value;
            }
          }
          $obj->EOF = false;
        } else {
          $obj->EOF = true;
        }
      } else {
        $obj->EOF = true;
      }

      $time_end = explode (' ', microtime());
      $query_time = $time_end[1]+$time_end[0]-$time_start[1]-$time_start[0];
      $this->total_query_time += $query_time;
      $this->count_queries++;
	/*print_r('<table  width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="90%" scope="col">'.$this->zf_sql.'</td>
    <td scope="col">'.number_format($query_time,4).'</td>
  </tr>
</table>');*/
      return($obj);
    }
  }

  function ExecuteRandomMulti($zf_sql, $zf_limit = 0, $zf_cache = false, $zf_cachetime=0) {
    $this->zf_sql = $zf_sql;
    $time_start = explode(' ', microtime());
    $obj = new queryFactoryResult;
    $obj->result = array();
    if (!$this->db_connected)
    {
      if (!$this->connect($this->host, $this->user, $this->password, $this->database, $this->pConnect, $this->real))
      $this->set_error('0', DB_ERROR_NOT_CONNECTED);
    }
    $zp_db_resource = @mysql_query($zf_sql, $this->link);
    if (!$zp_db_resource) $this->set_error(mysql_errno(),mysql_error());
    if(!is_resource($zp_db_resource)){
      $obj = null;
      return true;
    }
    $obj->resource = $zp_db_resource;
    $obj->cursor = 0;
    $obj->Limit = $zf_limit;
    if ($obj->RecordCount() > 0 && $zf_limit > 0) {
      $obj->EOF = false;
      $zp_Start_row = 0;
      if ($zf_limit) {
		  if($obj->RecordCount() > $zf_limit){
      $zp_start_row = twe_rand(0, ($obj->RecordCount() - $zf_limit));
		  }else{
	  $zp_start_row = twe_rand(0, ($obj->RecordCount()-1));		  
		  }
      }
      $obj->Move($zp_start_row);
      $obj->Limit = $zf_limit;
      $zp_ii = 0;
      while (!$obj->EOF) {
        $zp_result_array = @mysql_fetch_array($zp_db_resource);
        if ($zp_ii == $zf_limit) $obj->EOF = true;
        if ($zp_result_array) {
          while (list($key, $value) = each($zp_result_array)) {
            $obj->result[$zp_ii][$key] = $value;
          }
        } else {
          $obj->Limit = $zp_ii;
          $obj->EOF = true;
        }
        $zp_ii++;
      }
      $obj->result_random = array_rand($obj->result, sizeof($obj->result));
      if (is_array($obj->result_random)) {
        $zp_ptr = $obj->result_random[$obj->cursor];
      } else {
        $zp_ptr = $obj->result_random;
      }
      while (list($key, $value) = each($obj->result[$zp_ptr])) {
        if (!preg_match('/^[0-9]/', $key)) {
          $obj->fields[$key] = $value;
        }
      }
      $obj->EOF = false;
    } else {
      $obj->EOF = true;
    }


    $time_end = explode (' ', microtime());
    $query_time = $time_end[1]+$time_end[0]-$time_start[1]-$time_start[0];
    $this->total_query_time += $query_time;
    $this->count_queries++;
	/*print_r('<table  width="100%" border="1" cellspacing="0" cellpadding="0">
  <tr>
    <td width="90%" scope="col">'.$this->zf_sql.'</td>
    <td scope="col">'.number_format($query_time,4).'</td>
  </tr>
</table>');*/
    return($obj);
  }

  function insert_ID() {
    return @mysql_insert_id($this->link);
  }

  function metaColumns($zp_table) {
    $res = @mysql_query("select * from " . $zp_table . " limit 1", $this->link);
    $num_fields = @mysql_num_fields($res);
    for ($i = 0; $i < $num_fields; $i++) {
     $obj[strtoupper(@mysql_field_name($res, $i))] = new queryFactoryMeta($i, $res);
    }
    return $obj;
  }

  function get_server_info() {
    if ($this->link) {
      return mysql_get_server_info($this->link);
    } else {
      return UNKNOWN;
    }
  }

  function queryCount() {
    return $this->count_queries;
  }

  function queryTime() {
    return $this->total_query_time;
  }

  function perform ($tableName, $tableData, $performType='INSERT', $performFilter='', $debug=false) {
    switch (strtolower($performType)) {
      case 'insert':
      $insertString = "";
      $insertString = "INSERT INTO " . $tableName . " (";
      foreach ($tableData as $key => $value) {
        if ($debug === true) {
          echo $value['fieldName'] . '#';
        }
        $insertString .= $value['fieldName'] . ", ";
      }
      $insertString = substr($insertString, 0, strlen($insertString)-2) . ') VALUES (';
      reset($tableData);
      foreach ($tableData as $key => $value) {
        $bindVarValue = $this->getBindVarValue($value['value'], $value['type']);
        $insertString .= $bindVarValue . ", ";
      }
      $insertString = substr($insertString, 0, strlen($insertString)-2) . ')';
      if ($debug === true) {
        echo $insertString;
        die();
      } else {
        $this->execute($insertString);
      }
      break;
      case 'update':
      $updateString ="";
      $updateString = 'UPDATE ' . $tableName . ' SET ';
      foreach ($tableData as $key => $value) {
        $bindVarValue = $this->getBindVarValue($value['value'], $value['type']);
        $updateString .= $value['fieldName'] . '=' . $bindVarValue . ', ';
      }
      $updateString = substr($updateString, 0, strlen($updateString)-2);
      if ($performFilter != '') {
        $updateString .= ' WHERE ' . $performFilter;
      }
      if ($debug === true) {
        echo $updateString;
        die();
      } else {
        $this->execute($updateString);
      }
      break;
    }
  }
  function getBindVarValue($value, $type) {
    $typeArray = explode(':',$type);
    $type = $typeArray[0];
    switch ($type) {
      case 'csv':
        return $value;
      break;
      case 'passthru':
        return $value;
      break;
      case 'float':
        return (!twe_not_null($value) || $value=='' || $value == 0) ? 0 : $value;
      break;
      case 'integer':
        return (int)$value;
      break;
      case 'string':
        if (isset($typeArray[1])) {
          $regexp = $typeArray[1];
        }
        return '\'' . $this->prepare_input($value) . '\'';
      break;
      case 'noquotestring':
        return $this->prepare_input($value);
      break;
      case 'currency':
        return '\'' . $this->prepare_input($value) . '\'';
      break;
      case 'date':
        return '\'' . $this->prepare_input($value) . '\'';
      break;
      case 'enum':
        if (isset($typeArray[1])) {
          $enumArray = explode('|', $typeArray[1]);
        }
        return '\'' . $this->prepare_input($value) . '\'';
      case 'regexp':
        $searchArray = array('[', ']', '(', ')', '{', '}', '|', '*', '?', '.', '$', '^');
        foreach ($searchArray as $searchTerm) {
          $value = str_replace($searchTerm, '\\' . $searchTerm, $value);
        }
        return $this->prepare_input($value);
      default:
      die('var-type undefined: ' . $type . '('.$value.')');
    }
  }
/**
 * method to do bind variables to a query
**/
  function bindVars($sql, $bindVarString, $bindVarValue, $bindVarType, $debug = false) {
    $bindVarTypeArray = explode(':', $bindVarType);
    $sqlNew = $this->getBindVarValue($bindVarValue, $bindVarType);
    $sqlNew = str_replace($bindVarString, $sqlNew, $sql);
    return $sqlNew;
  }

  function prepareInput($string) {
    return $this->prepare_input($string);
  }
  
  //phpbb
 function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
	{

		$this->persistency = $persistency;
		$this->user = $sqluser;
		$this->password = $sqlpassword;
		$this->server = $sqlserver;
		$this->dbname = $database;

		if($this->persistency)
		{
			$this->link = @mysql_pconnect($this->server, $this->user, $this->password);
		}
		else
		{
			$this->link = @mysql_connect($this->server, $this->user, $this->password);
		}
		if($this->link)
		{
			if($database != "")
			{
				$this->dbname = $database;
				$dbselect = @mysql_select_db($this->dbname);
				if(!$dbselect)
				{

					@mysql_close($this->link);
					$this->link = $dbselect;
					
				}
			}
			return $this->link;
		}
		else
		{
			return false;
		}
	}
	//
	// Other base methods
	//
	function sql_close()
	{
		if($this->link)
		{
			if($this->query_result)
			{
				@mysql_free_result($this->query_result);
			}
			$result = @mysql_close($this->link);
			return $result;
		}
		else
		{
			return false;
		}
	}

	//
	// Base query method
	//
	function sql_query($query = "", $transaction = FALSE)
	{
		// Remove any pre-existing queries
		unset($this->query_result);
		if($query != "")
		{
 
			$this->num_queries++;

			$this->query_result = @mysql_query($query, $this->link);
		}
		if($this->query_result)
		{
			unset($this->row[$this->query_result]);
			unset($this->rowset[$this->query_result]);
			return $this->query_result;
		}
		else
		{
			return ( $transaction == END_TRANSACTION ) ? true : false;
		}
	}

	//
	// Other query methods
	//
	function sql_numrows($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_rows($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_affectedrows()
	{
		if($this->link)
		{
			$result = @mysql_affected_rows($this->link);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_numfields($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_num_fields($query_id);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldname($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_name($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fieldtype($offset, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_field_type($query_id, $offset);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrow($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$this->row[$query_id] = @mysql_fetch_array($query_id);
			return $this->row[$query_id];
		}
		else
		{
			return false;
		}
	}
	function sql_fetchrowset($query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			unset($this->rowset[$query_id]);
			unset($this->row[$query_id]);
			while($this->rowset[$query_id] = @mysql_fetch_array($query_id))
			{
				$result[] = $this->rowset[$query_id];
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_fetchfield($field, $rownum = -1, $query_id = 0)
	{
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			if($rownum > -1)
			{
				$result = @mysql_result($query_id, $rownum, $field);
			}
			else
			{
				if(empty($this->row[$query_id]) && empty($this->rowset[$query_id]))
				{
					if($this->sql_fetchrow())
					{
						$result = $this->row[$query_id][$field];
					}
				}
				else
				{
					if($this->rowset[$query_id])
					{
						$result = $this->rowset[$query_id][$field];
					}
					else if($this->row[$query_id])
					{
						$result = $this->row[$query_id][$field];
					}
				}
			}
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_rowseek($rownum, $query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}
		if($query_id)
		{
			$result = @mysql_data_seek($query_id, $rownum);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_nextid(){
		if($this->link)
		{
			$result = @mysql_insert_id($this->link);
			return $result;
		}
		else
		{
			return false;
		}
	}
	function sql_freeresult($query_id = 0){
		if(!$query_id)
		{
			$query_id = $this->query_result;
		}

		if ( $query_id )
		{
			unset($this->row[$query_id]);
			unset($this->rowset[$query_id]);

			@mysql_free_result($query_id);

			return true;
		}
		else
		{
			return false;
		}
	}
	function sql_error($query_id = 0)
	{
		$result["message"] = @mysql_error($this->link);
		$result["code"] = @mysql_errno($this->link);

		return $result;
	}
}

class queryFactoryResult {

  function queryFactoryResult() {
    $this->is_cached = false;
  }

  function MoveNext() {
    global $zc_cache;
    $this->cursor++;
    if ($this->is_cached) {
      if ($this->cursor >= sizeof($this->result)) {
        $this->EOF = true;
      } else {
        while(list($key, $value) = each($this->result[$this->cursor])) {
          $this->fields[$key] = $value;
        }
      }
    } else {
      $zp_result_array = @mysql_fetch_array($this->resource);
      if (!$zp_result_array) {
        $this->EOF = true;
      } else {
        while (list($key, $value) = each($zp_result_array)) {
          if (!preg_match('/^[0-9]/', $key)) {
            $this->fields[$key] = $value;
          }
        }
      }
    }
  }

  function MoveNextRandom() {
    $this->cursor++;
    if ($this->cursor < $this->Limit) {
      $zp_result_array = $this->result[$this->result_random[$this->cursor]];
      while (list($key, $value) = each($zp_result_array)) {
        if (!preg_match('/^[0-9]/', $key)) {
          $this->fields[$key] = $value;
        }
      }
    } else {
      $this->EOF = true;
    }
  }

  function RecordCount() {
      global $zc_cache;
    return @mysql_num_rows($this->resource);	
  }

  function Move($zp_row) {
    global $db;
    if (@mysql_data_seek($this->resource, $zp_row)) {
      $zp_result_array = @mysql_fetch_array($this->resource);
        while (list($key, $value) = each($zp_result_array)) {
          $this->fields[$key] = $value;
        }
      @mysql_data_seek($this->resource, $zp_row);
      $this->EOF = false;
      return;
    } else {
      $this->EOF = true;
      $db->set_error(mysql_errno(),mysql_error());
    }
  }
}

class queryFactoryMeta {

  function queryFactoryMeta($zp_field, $zp_res) {
    $this->type = @mysql_field_type($zp_res, $zp_field);
    $this->max_length = @mysql_field_len($zp_res, $zp_field);
  }
}



class cache{

  function sql_cache_exists($zf_query) {
    global $db;
    $zp_cache_name = $this->cache_generate_cache_name($zf_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
      // where using a single directory at the moment. Need to look at splitting into subdirectories
      // like adodb
      if (file_exists(DIR_FS_SQL_CACHE . '/db_cache/' . $zp_cache_name . '.sql')) {
        return true;
      } else {
        return false;
      }
      break;
      case 'database':
      $sql = "select * from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $zp_cache_name . "'";
      $zp_cache_exists = $db->Execute($sql);
      if ($zp_cache_exists->RecordCount() > 0) {
        return true;
      } else {
        return false;
      }
      break;
      case 'memory':
      return false;
      break;
      case 'none':
      default:
      return false;
      break;
    }
  }

  function sql_cache_is_expired($zf_query, $zf_cachetime) {
    global $db;
    $zp_cache_name = $this->cache_generate_cache_name($zf_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
      if (filemtime(DIR_FS_SQL_CACHE . '/db_cache/' . $zp_cache_name . '.sql') > (time() - $zf_cachetime)) {
        return false;
      } else {
        return true;
      }
      break;
      case 'database':
      $sql = "select * from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $zp_cache_name ."'";
      $cache_result = $db->Execute($sql);
      if ($cache_result->RecordCount() > 0) {
        $start_time = $cache_result->fields['cache_entry_created'];
        if (time() - $start_time > $zf_cachetime) return true;
        return false;
      } else {
        return true;
      }
      break;
      case 'memory':
      return true;
      break;
      case 'none':
      default:
      return true;
      break;
    }
  }

  function sql_cache_expire_now($zf_query) {
    global $db;
    $zp_cache_name = $this->cache_generate_cache_name($zf_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
      @unlink(DIR_FS_SQL_CACHE . '/db_cache/' . $zp_cache_name . '.sql');
      return true;
      break;
      case 'database':
      $sql = "delete from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $zp_cache_name . "'";
      $db->Execute($sql);
      return true;
      break;
      case 'memory':
      unset($this->cache_array[$zp_cache_name]);
      return true;
      break;
      case 'none':
      default:
      return true;
      break;
    }
  }

  function sql_cache_store($zf_query, $zf_result_array) {
    global $db;
    $zp_cache_name = $this->cache_generate_cache_name($zf_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
      $OUTPUT = serialize($zf_result_array);
      $fp = fopen(DIR_FS_SQL_CACHE . '/db_cache/' . $zp_cache_name . '.sql',"w");
      fputs($fp, $OUTPUT);
      fclose($fp);
      return true;
      break;
      case 'database':
      $sql = "select * from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $zp_cache_name . "'";
      $zp_cache_exists = $db->Execute($sql);
      if ($zp_cache_exists->RecordCount() > 0) {
        return true;
      }
      $result_serialize = $db->prepare_input(base64_encode(serialize($zf_result_array)));
      $sql = "insert into " . TABLE_DB_CACHE . " set cache_entry_name = '" . $zp_cache_name . "',
	                                               cache_data = '" . $result_serialize . "',
						       cache_entry_created = '" . time() . "'";
      $db->Execute($sql);
      return true;
      break;
      case 'memory':
      return true;
      break;
      case 'none':
      default:
      return true;
      break;
    }
  }

  function sql_cache_read($zf_query) {
    global $db;
    $zp_cache_name = $this->cache_generate_cache_name($zf_query);
    switch (SQL_CACHE_METHOD) {
      case 'file':
      $zp_fa = file(DIR_FS_SQL_CACHE . '/db_cache/' . $zp_cache_name . '.sql');
      $zp_result_array = unserialize(implode('', $zp_fa));
      return $zp_result_array;
      break;
      case 'database':
      $sql = "select * from " . TABLE_DB_CACHE . " where cache_entry_name = '" . $zp_cache_name . "'";
      $zp_cache_result = $db->Execute($sql);
      $zp_result_array = unserialize(base64_decode($zp_cache_result->fields['cache_data']));
      return $zp_result_array;
      break;
      case 'memory':
      return true;
      break;
      case 'none':
      default:
      return true;
      break;
    }
  }

  function sql_cache_flush_cache() {
    global $db;
    switch (SQL_CACHE_METHOD) {
      case 'file':
      if ($za_dir = @dir(DIR_FS_SQL_CACHE)) {
        while ($zv_file = $za_dir->read()) {
          if (strstr($zv_file, '.sql') && strstr($zv_file, 'twec_')) {
            @unlink(DIR_FS_SQL_CACHE . '/db_cache/' .  $zv_file);
          }
        }
        $za_dir->close();
      }
      return true;
      break;
      case 'database':
      $sql = "delete from " . TABLE_DB_CACHE;
      $db->Execute($sql);
      return true;
      break;
      case 'memory':
      return true;
      break;
      case 'none':
      default:
      return true;
      break;
    }
  }

  function cache_generate_cache_name($zf_query) {
    switch (SQL_CACHE_METHOD) {
      case 'file':
      return 'twec_' . md5($zf_query);
      break;
      case 'database':
      return 'twec_' . md5($zf_query);
      break;
      case 'memory':
      return 'twec_' . md5($zf_query);
      break;
      case 'none':
      default:
      return true;
      break;
    }
  }
}
