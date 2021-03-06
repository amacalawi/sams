<?php
// Generic model
// $Id: BaseModel.class.php,v 1.2 2007/05/03 09:31:42 altair Exp $

class BaseModel
{
  var $userclass;
  var $link;
  var $sql_function;
  protected $config2;

  function BaseModel($uc)
  {
	  global $config;
    $this->config2 = $config;

    $this->userclass = $uc;
    $this->link = mysqli_connect($config['mysql_host'],
        $config['mysql_user'],
        $config['mysql_pass'], $config['mysql_db'])
      or die("Cannot connect to database: " . mysql_error());
    // mysqli_select_db($config['mysql_db']);
    // add functions to this array as needed
    $this->sql_function = array("CURDATE()","NOW()");
  }

  /**
   * Set the database if some other db than the default is wanted.
   */
  function set_db($db)
  {
    mysqli_select_db($db, $this->link);
  }

  function last_insert_id()
  {
    return(mysql_insert_id());
  }

  function error_msg($mysql_error ="", $errmsg = "")
  {
	die($errmsg);
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
    $result = @mysqli_query($this->link, $query)
      or $this->error_msg(mysqli_error($this->link), " Error in mysql query ".
        $query . ".");
    if ($fetcharray) {
      // Build $result as an array
      $res = $result;
      $result = array();
      while ($line = mysqli_fetch_array($res, MYSQLI_BOTH)) {
         $result[] = $line;
      }
    }
    return($result);
  }

  function return_one($query){
    $result = @mysqli_query($this->link, $query) or $this->error_msg(mysql_error(), " Error in mysql query ".  $query . ".");
	while ($row = mysqli_fetch_array($result, MYSQLI_NUM)) {
		return $row[0];
	}
	return false;
  }

  function select_one($query){
    $result = @mysqli_query($this->link, $query) or $this->error_msg(mysql_error(), " Error in mysql query ".  $query . ".");
	return mysqli_fetch_row($result);
  }

  function select_all($query){
    $result = @mysqli_query($this->link, $query) or $this->error_msg(mysql_error(), " Error in mysql query ".  $query . ".");
    $res = array();
    while ($line = mysqli_fetch_assoc($result)) {
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

      $fieldstr .= "`" . $key . "` = '" . mysqli_real_escape_string($this->link, $val) . "'";
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
      . mysqli_real_escape_string($this->link, $table) . "`"
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

      $fieldstr .= "`" . $key . "` = '" . mysqli_real_escape_string($this->link, $val) . "'";
    }

    // See to it that any $extra gets properly constructed.  If there
    // is nothing, then simply put nothing.  Check if the resulting
    // $fieldstr is empty -- if it isn't add an 'AND' before the $extra.
    $extra = ($extra == "") ? "" : (($fieldstr == "") ? $extra : ' AND '
            . $extra);

    // If the clause is empty, then don't put a WHERE.
    $clause = $fieldstr . $extra;
    $clause = ($clause == "") ? "" : " WHERE " . $clause;

    $query = "SELECT COUNT(*) FROM `" . mysqli_real_escape_string($this->link, $table) . "`"
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
      $ftxt .= "`" . $key . "`";
      // SQL functions are not quoted or escaped...
     // $ftxt .= "" . mysqli_real_escape_string($this->link, $key) . "";
      // SQL functions are not quoted or escaped...
      if (array_search($val, $this->sql_function)) {
  $vtxt .= $val;
      } else {
  $vtxt .= "'" . mysqli_real_escape_string($this->link, $val) . "'";
      }
    }
    $ftxt .= ")";
    $vtxt .= ")";

    $query = "INSERT INTO " . $table . " " . $ftxt . " VALUES " . $vtxt;
   // $query = "INSERT INTO " . mysqli_real_escape_string($this->link, $table) . " " . $ftxt
   // . " VALUES " . $vtxt;

    // var_dump($query);
    // var_dump($this->query("SELECT * FROM inbox")); exit();

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

      $fieldstr .= "`" . $key . "` = '" . mysqli_real_escape_string($this->link, $val) . "'";
    }

    // See to it that any $extra gets properly constructed.  If there
    // is nothing, then simply put nothing.  Check if the resulting
    // $fieldstr is empty -- if it isn't add an 'AND' before the $extra.
    $extra = ($extra == "") ? "" : (($fieldstr == "") ? $extra : ' AND '
            . $extra);

    // If the clause is empty, then don't put a WHERE.
    $clause = $fieldstr . $extra;
    $clause = ($clause == "") ? "" : " WHERE " . $clause;

    $query = "DELETE FROM `" . mysqli_real_escape_string($this->link, $table) . "`"
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
        $vtxt = "'" . mysqli_real_escape_string($this->link, $val) . "'";
      } else {
        $vtxt = $val;
      }
      $sets[] = "`" . mysqli_real_escape_string($this->link, $key) . "` = " . $vtxt;
    }

    $where = ($where) ? (" WHERE " . $where) : "";
    $query = "UPDATE `" . mysqli_real_escape_string($this->link, $table) . "` SET "
      . implode(", ", $sets) . $where;
    return($this->query($query));
  }

}

?>
