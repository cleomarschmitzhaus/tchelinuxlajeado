<?php 

require('db_conf.php');

function connect() {
    $db = $GLOBALS['vendor'];
    if ($db == 'postgres') {
        $str = "host=${GLOBALS['dbhost']} dbname=${GLOBALS['dbname']} user=${GLOBALS['dbuser']}";
        return pg_connect($str);
    } else if ($db == "mysql") {
        $conn = mysql_connect($GLOBALS['dbhost'],$GLOBALS['dbuser'],$GLOBALS['dbpassword']);
        if (!$conn)
            die(mysql_error()."\n");
        mysql_select_db($GLOBALS['dbname'], $conn);
        mysql_query("SET NAMES 'utf8'");
        mysql_query('SET character_set_connection=utf8');
        mysql_query('SET character_set_client=utf8');
        mysql_query('SET character_set_results=utf8');
        return $conn;
    } else
        return false;
}

function list_table($table_candidate, $fields, $index)
{
    if ($GLOBALS['vendor'] == 'postgres') {
        $do_query = 'pg_query';
        $do_fetch = 'pg_fetch_assoc';
        $do_close = 'pg_close';
    } else if ($GLOBALS['vendor'] == 'mysql') {
        $do_query = 'mysql_query';
        $do_fetch = 'mysql_fetch_assoc';
        $do_close = 'mysql_close';
    } else
        return null;

    $table = split(" ",$table_candidate);
    $table = $table[0];
    $query = "SELECT ".join(",",$fields)." FROM ".$table.' ORDER BY '.$index;

    $conn = connect() or die("Could not connect to database.");
    $rs = $do_query($query);
    while ( $row = $do_fetch($rs) ) {
        foreach ($fields as $_ => $f)
            echo '<div class="dbentry">'.utf8_decode($row[$f])."</div>";
    }

    $do_close($conn);
}

?>
