<?php
// Append associative array elements
function array_push_associative(&$arr) {
    $args = func_get_args();
    foreach ($args as $arg) {
        if (is_array($arg)) {
            foreach ($arg as $key => $value) {
                $arr[$key] = $value;
                $ret++;
            }
        } else {
            $arr[$arg] = "";
        }
    }
    return $ret;
}
/////////
function escape_and_clear ($input_var) {
    $input_var = array_map('trim', $input_var);
    if (get_magic_quotes_gpc()) {
        $input_var = array_map('stripslashes', $input_var);
    }
    //$input_var = array_map('mysqli_real_escape_string', $input_var);
    return $input_var;
}

/////////
function datetime_to_de ($date_str = '', $return_type = "full") {
    $de_datetime = array('', '');
    if ($date_str != ''){
        $splitted = explode (' ',$date_str);
        $splitted_date = explode('-', $splitted[0]);
        $splitted_time = explode(':', $splitted[1]);
        $de_datetime[0] = $splitted_date[2] . '.' . $splitted_date[1] . '.' . $splitted_date[0];
        $de_datetime[1] = $splitted_time[0] . ':' . $splitted_time[1];
        switch ($return_type) {
            case "full" : return $de_datetime[0] . ' ' . $de_datetime[1];
            case "date" : return $de_datetime[0];
            case "time" : return $de_datetime[1];
default     : return $de_datetime[0] . ' ' . $de_datetime[1];
        }
    } else {
        return "";
    }
}

/////////
function get_username_by_id ($conn, $user_dbid = 0, $format = "short") {
    $query = "SELECT username, familienname, vorname FROM `user` WHERE `ID`='".$user_dbid."'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
        switch ($format) {
            case "short" : return $row['username'];
            case "first" : return $row['vorname'];
            case "last"  : return $row['familienname'];
            default      : return $row['username'];
        }
    } else {
        return "";
    }
}

////////
function get_function_by_userid ($conn, $user_dbid = 0) {
    $query = "SELECT geschlecht, function FROM `user` WHERE `ID`='".$user_dbid."'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) == 1) {
        $userdata = mysqli_fetch_array($result);
        mysqli_free_result($result);
        if ($userdata['function'] > 0) {
            $query = "SELECT * FROM `userfunction` WHERE `ID`='".$userdata['function']."'";
            $result = mysqli_query($conn, $query);
            $functiondata = mysqli_fetch_array($result);
            mysqli_free_result($result);
            if ($userdata['geschlecht'] < 2) {
                return $functiondata['male'];
            } else {
                return $functiondata['female'];
            }
        } else {
            return "";
        }
    } else {
        return "";
    }
}

////////
function message_die($msg_code, $msg_text = '', $msg_title = '', $err_line = '', $err_file = '', $sql = '') {
    global $smarty;
    switch($msg_code) {
        case GENERAL_MESSAGE:
            $smarty -> assign('msg_type', 'normal');
            $smarty -> assign('msg_title', $msg_title);
            $smarty -> assign('msg_text', $msg_text);
            $smarty -> display('message_die.tpl');
        break;
        case GENERAL_ERROR:
            $smarty -> assign('msg_type', 'error');
            $smarty -> assign('msg_title', $msg_title);
            $smarty -> assign('msg_text', $msg_text);
            $smarty -> display('message_die.tpl');
        break;
    }
    exit;
}

/////////
function create_select($conn, $boxname, $elements_to_skip = array()) {
    global $smarty;
    $query = "SELECT * FROM f_".$boxname." WHERE `active`=1 ORDER BY `view_order` ASC";
    $result = mysqli_query($conn, $query);
    $num = mysqli_num_rows($result);
    $dummyarray_i = array("-1");
    $dummyarray_k = array("&nbsp;");
    for ($i = 0; $i < $num; $i++) {
        $row = mysqli_fetch_array($result);
        if ( !in_array($row['ID'], $elements_to_skip) ){
            $dummyarray_i[]=$row['ID'];
            $dummyarray_k[]=$row['option'];
        }
    }
    mysqli_free_result($result);
    $smarty -> assign($boxname.'_values', $dummyarray_i);
    $smarty -> assign($boxname.'_options', $dummyarray_k);
}

/////////
function create_view($boxname) {
    global $smarty;
    $query = "SELECT * FROM f_".$boxname." WHERE `active`=1 ORDER BY ID ASC";
    $result = mysqli_query($conn, $query);
    $num = mysqli_num_rows($result);
    $dummyarray_i=array();
    $dummyarray_k=array();
    for ($i = 0; $i < $num; $i++) {
        $row = mysqli_fetch_array($result);
        $dummyarray_i[]=$row['ID'];
        $dummyarray_k[]=$row['option'];
    }
    mysqli_free_result($result);
    $smarty -> assign($boxname.'_id', $dummyarray_i);
    $smarty -> assign($boxname.'_option', $dummyarray_k);
}

/////////
function create_menu($userlevel = 0) {
    global $smarty;
    // Menu zum Userlevel
    switch ($userlevel) {
        case UG_ADMIN:
            // Admin
            $smarty->assign("Menu", array(
                array("itemtitel" => "Fall Liste", "itemlink" => "bes_badolist.php"),
                array("itemtitel" => "Benutzerverwaltung", "itemlink" => "admin_userlist.php"),
                array("itemtitel" => "Abmelden", "itemlink" => "logout.php")
            ));
        break;
        case UG_STATUSER:
            // Statistikuser
            $smarty->assign("Menu", array(
                array("itemtitel" => "Fall Liste", "itemlink" => "bes_badolist.php"),
                array("itemtitel" => "Patientenregister", "itemlink" => "bes_patregister.php"),
                array("itemtitel" => "Abmelden", "itemlink" => "logout.php")
            ));
        break;
        case UG_BEHANDLER:
            // Arzt
            $smarty->assign("Menu", array(
                array("itemtitel" => "Fall Liste", "itemlink" => "bes_badolist.php"),
                array("itemtitel" => "Abmelden", "itemlink" => "logout.php")
            ));
        break;
        case UG_STATION:
            $smarty->assign("Menu", array(
                array("itemtitel" => "Fall Liste", "itemlink" => "bes_badolist.php"),
                array("itemtitel" => "Abmelden", "itemlink" => "logout.php")
            ));
        break;
        default:
            $smarty->assign("Menu", array(
                array("itemtitel" => "Anmelden", "itemlink" => "index.php")
            ));
        break;
    }
}

/////////
function idtostr($dbconn="", $id=-1, $table="", $row="option") {
    if ($id == -1) {
        return false;
    }
    $query = "SELECT * FROM `".$table."` WHERE `ID`='".$id."'";
    $result = mysqli_query($dbconn, $query);
    $num = mysqli_num_rows($result);
    if ($num == 1) {
        $rowfetch = mysqli_fetch_array($result);
        mysqli_free_result($result);
        return $rowfetch[$row];
    } else {
        return false;
    }
}

/////////
function user_activity($conn, $session_id = '', $fall_id = '') {
    if (func_num_args() != 3) {
        $output = false;
    } else {
        $query = "UPDATE `user` SET ".
            "`lastactivity`='".$fall_id."'".
            "WHERE `sessionid`='".$session_id."'";
        if (!($result = mysqli_query($conn, $query))) {
            $output = false;
        } else {
            $output = true;
        }
    }
    return $output;
}

// Auth
function auth($userlevel = 0 ,$page_id = 0) {
    //PAGE_INDEX, 1
    //PAGE_PATLIST, 2
    //PAGE_BADOCREATE, 3
    //PAGE_BADOEDIT, 4
    //PAGE_BADOCHECK, 5
    //PAGE_INFO, 6
    //PAGE_BADOMANAGE, 7
    //PAGE_ADMIN_USER, 8
    //PAGE_PDFOUT, 9
    //PAGE_DB_EXPORT, 10
    //PAGE_PATREGISTER, 11
    //PAGE_DBUPDATECMDS, 12
    //PAGE_VERLAUFEDIT, 13
    //PAGE_VERLAUF_EXPORT, 14
    $userlevel_pageauth=array(
        //      1 2 3 4 5 6 7 8 9 0 1 2 3 4
        array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),       //LEVEL 0
        array(0,1,1,0,0,0,1,0,1,0,0,0,0,0,0),       //LEVEL 1 - Admin
        array(0,1,1,0,0,1,1,1,0,1,1,1,1,0,1),       //LEVEL 2 - Statistik User
        array(0,1,1,1,1,0,1,0,0,0,0,0,1,1,1),       //LEVEL 3 - Arzt
        array(0,1,1,1,0,0,1,0,0,0,0,0,0,1,1)        //LEVEL 4 - Station
    );
    return $userlevel_pageauth[$userlevel][$page_id];
}

// Auth User
function user_permission($conn, $userID = '', $rightRow = '') {
    $query = "SELECT * FROM `user` WHERE `ID`='".$userID."'";
    if ($result = mysqli_query($conn, $query)) {
        $num = mysqli_num_rows($result);
        if ($num == 1) {
            $rowfetch = mysqli_fetch_array($result);
            mysqli_free_result($result);
            return ($rowfetch[$rightRow] == 1 ? TRUE : FALSE);
        }
    }
    return FALSE;
}

?>
