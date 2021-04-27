<?php

$level_access = 2;
$currentUser = null;

if(isset($_COOKIE["authsession"])){
    if( $query = dbQueryOne("SELECT account.* FROM account, account_session WHERE account_session.session_key = '{$_COOKIE['authsession']}' AND account.id = account_session.account_id") ){
        $level_access = ($query["type"] === "moderator") ? 1 : 0;
        $currentUser = $query;
    } else {
        setcookie("authsession", null, time()-3600, "/");
    }
}