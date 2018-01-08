<?php
// Generic model
// $Id: BaseModel.class.php,v 1.2 2007/05/03 09:31:42 altair Exp $

class BaseModel
{
  var $userclass;
  var $link;
  var $sql_function;

  function BaseModel($uc)
  {
    global $config;

    $this->userclass = $uc;
    $this->link = mysql_connect($config['mysql_host'],
        $config['mysql_user'],
        $config['mysql_pass'])
      or die("Cannot connect to database: " . mysql_error());
    mysql_select_db($config['mysql_db']);
    // add functions to this array as needed
    $this->sql_function = array("CURDATE()","NOW()");
  }

  /**
   * Set the database if some other db than the default is wanted.
   */
  function set_db($db)
  {
    mysql_select_db($db, $this->link);
  }

  function last_insert_id()
  {
    return(mysql_insert_id());
  }

  function error_msg($mysql_error ="", $errmsg = "")
  {
    die("<h6> MySQL ERROR @ " . $_SERVER['PHP_SELF'] . " from class ".
    $this->userclass . "</h6>
    <h6>".htmlentities($errmsg)."</h6>
    <h6>".htmlentities($mysql_error)."</h6>
    ");
  }

  /**
   * Do an arbitrary database query.  $query is the SQL query to
   * be executed.  It will return the result of the query as is, or
   * the result set as an array if $fetcharray is true.
   */
  function query($query, $fetcharray = FALSE)
  {
    $result = @mysql_query($query, $this->link)
      or $this->error_msg(mysql_error(), " Error in mysql query ".
        $query . ".");
    if ($fetcharray) {
      // Build $result as an array
      $res = $result;
      $result = array();
      while ($line = mysql_fetch_array($res, MYSQL_BOTH)) {
         $result[] = $line;
      }
    }
    return($result);
  }

  function return_one($query){
    $result = @mysql_query($query, $this->link) or $this->error_msg(mysql_error(), " Error in mysql query ".  $query . ".");
	while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
		return $row[0];
	}
	return false;
  }

  function select_one($query){
    $result = @mysql_query($query, $this->link) or $this->error_msg(mysql_error(), " Error in mysql query ".  $query . ".");
	return mysql_fetch_row($result);
  }

  function select_all($query){
    $result = @mysql_query($query, $this->link) or $this->error_msg(mysql_error(), " Error in mysql query ".  $query . ".");
    $res = array();
    while ($line = mysql_fetch_assoc($result)) {
         $res[] = $line;
	}
    return($res);
  }

  /**
   * Perform a query on $table against $fieldhash.  $fieldvals is a hash
   * whose keys are the names of the fields and whose values are the values
   * one wants to obtain.  For example, if $values = ('foo' => 'bar',
   * 'baz' => 'quux') against a table 'blarg', this function will do
   * a SELECT * FROM blarg WHERE foo = 'bar' AND baz = 'quux'.
   * The optional $extra parameter is an extra constraint on the query
   * and is included as an AND at the end of the query.  The $fields
   * parameter should be a proper SQL field specifier.  The default is '*'.
   */
  function query_record($table, $fieldhash = array(), $fields="*", $extra = "")
  {
    // Construct the clause on the query
    $fieldstr = "";
    $first = true;
    foreach ($fieldhash as $key => $val) {
      if ($first) {
  $first = false;
      } else {
  // If not the first, put an AND in between
  $fieldstr .= " AND ";
      }

      $fieldstr .= "`" . $key . "` = '" . mysql_escape_string($val) . "'";
    }

    // See to it that any $extra gets properly constructed.  If there
    // is nothing, then simply put nothing.  Check if the resulting
    // $fieldstr is empty -- if it isn't add an 'AND' before the $extra.
    $extra = ($extra == "") ? "" : (($fieldstr == "") ? $extra : ' AND '
            . $extra);

    // If the clause is empty, then don't put a WHERE.
    $clause = $fieldstr . $extra;
    $clause = ($clause == "") ? "" : " WHERE " . $clause;

    $query = "SELECT ". $fields . " FROM `"
      . mysql_escape_string($table) . "`"
      . $clause;
    return($this->query($query));
  }

  /**
   * Count the number of results for a query on $table against $fieldhash.
   * It returns the size of the result set were query_record to be used
   * on it.
   */
  function count_record($table, $fieldhash = array(), $extra = "")
  {
    // Construct the clause on the query
    $fieldstr = "";
    $first = true;
    foreach ($fieldhash as $key => $val) {
      if ($first) {
  $first = false;
      } else {
  // If not the first, put an AND in between
  $fieldstr .= " AND ";
      }

      $fieldstr .= "`" . $key . "` = '" . mysql_escape_string($val) . "'";
    }

    // See to it that any $extra gets properly constructed.  If there
    // is nothing, then simply put nothing.  Check if the resulting
    // $fieldstr is empty -- if it isn't add an 'AND' before the $extra.
    $extra = ($extra == "") ? "" : (($fieldstr == "") ? $extra : ' AND '
            . $extra);

    // If the clause is empty, then don't put a WHERE.
    $clause = $fieldstr . $extra;
    $clause = ($clause == "") ? "" : " WHERE " . $clause;

    $query = "SELECT COUNT(*) FROM `" . mysql_escape_string($table) . "`"
      . $clause;
    $res = $this->query($query, true);
    return($res[0][0]);
  }

  /**
   * Perform an insertion on $table, using the hash $values, whose keys
   * are the fields in the table and whose values are the values to
   * be inserted.
   */
  function insert($table, $values)
  {
    // initialize with open parens
    $ftxt = $vtxt = "(";
    // Iterate over the values hash
    $first = true;
    foreach ($values as $key => $val) {
      if ($first) {
  $first = false;
      } else {
  $ftxt .= ", ";
  $vtxt .= ", ";
      }
      $ftxt .= "`" . mysql_escape_string($key) . "`";
      // SQL functions are not quoted or escaped...
      if (array_search($val, $this->sql_function)) {
  $vtxt .= $val;
      } else {
  $vtxt .= "'" . mysql_escape_string($val) . "'";
      }
    }
    $ftxt .= ")";
    $vtxt .= ")";

    $query = "INSERT INTO `" . mysql_escape_string($table) . "` " . $ftxt
      . " VALUES " . $vtxt;

    $this->query($query);
	$id = $this->query("SELECT LAST_INSERT_ID();", true);
	return $id[0][0];
  }

  /**
   * Delete from $table against $fieldhash.  $fieldhash is a hash
   * whose keys are the names of the fields and whose values are the values
   * one wants to delete.  For example, if $values = ('foo' => 'bar',
   * 'baz' => 'quux') against a table 'blarg', this function will do
   * a DELETE FROM blarg WHERE foo = 'bar' AND baz = 'quux'.
   * The optional $extra parameter is an extra constraint on the query
   * and is included as an AND at the end of the query.
   *
   * Be warned: If $fieldhash and $extra are both empty, it will delete
   * everything from the table specified!  To protect the innocent, unlike
   * query_record, $fieldhash must always be specified explicitly.
   */
  function delete_record($table, $fieldhash, $extra = "")
  {
    // Construct the clause on the query
    $fieldstr = "";
    $first = true;
    foreach ($fieldhash as $key => $val) {
      if ($first) {
  $first = false;
      } else {
  // If not the first, put an AND in between
  $fieldstr .= " AND ";
      }

      $fieldstr .= "`" . $key . "` = '" . mysql_escape_string($val) . "'";
    }

    // See to it that any $extra gets properly constructed.  If there
    // is nothing, then simply put nothing.  Check if the resulting
    // $fieldstr is empty -- if it isn't add an 'AND' before the $extra.
    $extra = ($extra == "") ? "" : (($fieldstr == "") ? $extra : ' AND '
            . $extra);

    // If the clause is empty, then don't put a WHERE.
    $clause = $fieldstr . $extra;
    $clause = ($clause == "") ? "" : " WHERE " . $clause;

    $query = "DELETE FROM `" . mysql_escape_string($table) . "`"
      . $clause;
    return($this->query($query));
  }

  /**
   * Update a record in table $table to $value using the $where
   * constraint.  To prevent this function from causing undue damage,
   * $where must always be explicitly specified.
   */
  function update($table, $value, $where)
  {
    $sets = array();
    foreach ($value as $key => $val) {
      // SQL functions are not quoted or escaped...
      if (!array_search($val, $this->sql_function)) {
        $vtxt = "'" . mysql_escape_string($val) . "'";
      } else {
        $vtxt = $val;
      }
      $sets[] = "`" . mysql_escape_string($key) . "` = " . $vtxt;
    }

    $where = ($where) ? (" WHERE " . $where) : "";
    $query = "UPDATE `" . mysql_escape_string($table) . "` SET "
      . implode(", ", $sets) . $where;
    return($this->query($query));
  }

}

?>
