<?php

require_once __DIR__ . '/../../vendor/autoload.php';
// require_once __DIR__ . '/../../user/function_user.php';

use PHPUnit\Framework\TestCase;
use Casdoor\Auth\Jwt;
use Casdoor\Auth\Token;
use Casdoor\Auth\User;

User::initConfig(
    $_config["sso"]["endpoint"],
    $_config["sso"]["client_id"],
    $_config["sso"]["secret"],
    $_config["sso"]["certificate"],
    $_config["sso"]["org_name"],
    $_config["sso"]["app_name"]
);

$code = $_GET["code"];
$state = $_GET["state"];

$token = new Token();
$accessToken = $token->getOAuthToken($code, $state);
$jwt = new Jwt();
$result = $jwt->parseJwtToken($accessToken, User::$authConfig);

// print_r($result);
$uid = C::t('user')->fetch_uid_by_username($result["name"]);
$user = C::t('user')->get_user_by_uid($uid);
// print_r($user);
if (!empty($user)) {
    // Login
    if ($_G['member']['lastip'] && $_G['member']['lastvisit']) {
		dsetcookie('lip', $_G['member']['lastip'] . ',' . $_G['member']['lastvisit']);
	}
    C::t('user_status') -> update($_G['uid'], array('lastip' => $_G['clientip'], 'lastvisit' => TIMESTAMP, 'lastactivity' => TIMESTAMP));
    setloginstatus($user, 259200);
    // $loginmessage = $_G['groupid'] == 8 ? 'login_succeed_inactive_member' : 'login_succeed';
	// $location = $_G['groupid'] == 8 ? 'index.php?open=password' : dreferer();
    showmessage("登录成功", "/", $user, $extra);
} else {
    // Auto registry

}