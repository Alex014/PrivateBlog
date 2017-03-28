<?php
define('PS_DELIMITER', '|');
define('PS_UNDEF_MARKER', '!');

class session {
	
	var $Sql;
	var $Table;
	
	function session($sql_driver, $sess_table) {		
	  $this->Sql = $sql_driver;	
	  $this->Table = $sess_table;

    $this->gc();

		session_set_save_handler(	array(&$this, "open"),
															array(&$this, "close"),
															array(&$this, "read"),
															array(&$this, "write"),
															array(&$this, "destroy"),
															array(&$this, "gc"));		
		session_start();
	}

  function read_session_key($id) {
    $r = $this->read($id);
    $_SESSION = $this->session_real_decode($r);
  }

	
	function open($save_path, $session_name) {
	  return(true);
	}
	
	function close() {
	  return(true);
	}
	
	function read($id) {
		$usr_ip = $_SERVER['REMOTE_ADDR'];//echo "SELECT `data` FROM $this->Table WHERE skey = '$id'";
		$st = $this->Sql->prepare("SELECT `data` FROM $this->Table WHERE skey = :skey");
		$st->execute(array('skey' => $id));
		$row = $st->fetch();
	  $st->closeCursor();
	  return $row[0];
	}
	
	function write($id, $sess_data) {
	  //global $Sql, $TABLES;
		$user_ip = $_SERVER['REMOTE_ADDR'];
	  $sess_data = $sess_data;
		$st = $this->Sql->prepare("SELECT COUNT(skey) FROM $this->Table WHERE skey = :skey");
		$st->execute(array('skey' => $id));
		$row = $st->fetch();
	  $st->closeCursor();
	  $rc = $row[0];
	  //print "writing session(".get_magic_quotes_gpc().") ... ";
	  if($rc == 0) {
	  	$st = $this->Sql->prepare("INSERT INTO $this->Table
	  			(user_ip, skey, data, _edited)
	  			VALUES
	  			(:user_ip, :skey, :data, :_edited)"); 
      $st->execute(array('user_ip' => $user_ip, 'skey' => $id, 'data' => $sess_data, '_edited' => time()));
      $st->closeCursor();
	  }
	  else {
			$st = $this->Sql->prepare("UPDATE $this->Table SET
					user_ip = :user_ip,
					data = :data,
					_edited = :_edited WHERE skey = :skey");
      $st->execute(array('user_ip' => $user_ip, 'skey' => $id, 'data' => $sess_data, '_edited' => time()));
      $st->closeCursor();
	  }
	  //print "OK";
	}
	
	function destroy($id) {
		$usr_ip = $_SERVER['REMOTE_ADDR'];
	  return $this->Sql->exec("DELETE FROM $this->Table
															WHERE skey = '$id'");
	}
	
	function gc($maxlifetime = 7200) {
		$usr_ip = $_SERVER['REMOTE_ADDR'];
		$this->Sql->exec("DELETE FROM $this->Table	WHERE
((UNIX_TIMESTAMP()-_edited) > $maxlifetime) OR
(   ((UNIX_TIMESTAMP()-_edited) > 10)   AND  ( (`data` = '') OR ( `data` IS NULL) )   )");
	  return true;
	}


  /**
   * Декодирование данных сессии (стянуто с одного ПХПэшного сайта)
   * @param <type> $str
   * @return <type>
   */
function session_real_decode($str)
{
    $str = (string)$str;

    $endptr = strlen($str);
    $p = 0;

    $serialized = '';
    $items = 0;
    $level = 0;

    while ($p < $endptr) {
        $q = $p;
        while ($str[$q] != PS_DELIMITER)
            if (++$q >= $endptr) break 2;

        if ($str[$p] == PS_UNDEF_MARKER) {
            $p++;
            $has_value = false;
        } else {
            $has_value = true;
        }

        $name = substr($str, $p, $q - $p);
        $q++;

        $serialized .= 's:' . strlen($name) . ':"' . $name . '";';

        if ($has_value) {
            for (;;) {
                $p = $q;
                switch ($str[$q]) {
                    case 'N': /* null */
                    case 'b': /* boolean */
                    case 'i': /* integer */
                    case 'd': /* decimal */
                        do $q++;
                        while ( ($q < $endptr) && ($str[$q] != ';') );
                        $q++;
                        $serialized .= substr($str, $p, $q - $p);
                        if ($level == 0) break 2;
                        break;
                    case 'R': /* reference  */
                        $q+= 2;
                        for ($id = ''; ($q < $endptr) && ($str[$q] != ';'); $q++) $id .= $str[$q];
                        $q++;
                        $serialized .= 'R:' . ($id + 1) . ';'; /* increment pointer because of outer array */
                        if ($level == 0) break 2;
                        break;
                    case 's': /* string */
                        $q+=2;
                        for ($length=''; ($q < $endptr) && ($str[$q] != ':'); $q++) $length .= $str[$q];
                        $q+=2;
                        $q+= (int)$length + 2;
                        $serialized .= substr($str, $p, $q - $p);
                        if ($level == 0) break 2;
                        break;
                    case 'a': /* array */
                    case 'O': /* object */
                        do $q++;
                        while ( ($q < $endptr) && ($str[$q] != '{') );
                        $q++;
                        $level++;
                        $serialized .= substr($str, $p, $q - $p);
                        break;
                    case '}': /* end of array|object */
                        $q++;
                        $serialized .= substr($str, $p, $q - $p);
                        if (--$level == 0) break 2;
                        break;
                    default:
                        return false;
                }
            }
        } else {
            $serialized .= 'N;';
            $q+= 2;
        }
        $items++;
        $p = $q;
    }
    return @unserialize( 'a:' . $items . ':{' . $serialized . '}' );
}

}