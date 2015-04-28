<?php

/* 
 * Arquivo de bootstrap do sistema
 */
mb_http_output('UTF-8');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

// Configurações do sistema
define("PATH_LOCAL", dirname(__FILE__));
set_include_path(ini_get("include_path") . PATH_SEPARATOR  . PATH_LOCAL . PATH_SEPARATOR);
define("PATH_APP", PATH_LOCAL . '/app');
define("PATH_MYFRAME", PATH_LOCAL . '/vendor/myframework');
define("PATH_DEFAULT", PATH_LOCAL . '/app_default');
define("PATH_TEMP", PATH_LOCAL . '/tmp');
define("UPSALT", 'S&@c%(*mA');  //Não mexer vai quebrar o login

require_once PATH_MYFRAME . '/mycore.php';

$appconfig = parse_ini_file('conf/application.local.ini', true);

//Constants
define('DEVELOPMENT', 'DEVELOPMENT');
define('PRODUCTION', 'PRODUCTION');
define("SERVER_MODE", $appconfig['geral']['mode']);
define("DOMAIN", $appconfig['geral']['url']);
define("DOMAIN_EMAIL", $appconfig['geral']['domain']);
define("PAGE_TITLE_PREFIX", $appconfig['geral']['prefix']);

$env = 'local';
if(SERVER_MODE == PRODUCTION) {
    $env = 'global';
}

$databaseconfig = parse_ini_file("app/conf/database.{$env}.ini", true);

define("DATABASE_DRIVER", getValueFromArray($databaseconfig['database'], 'driver'), '');
define("DATABASE_NAME", getValueFromArray($databaseconfig['database'], 'dbname', ''));
define("DATABASE_HOST", getValueFromArray($databaseconfig['database'], 'host', ''));
define("DATABASE_PORT", getValueFromArray($databaseconfig['database'], 'port', ''));
define("DATABASE_USER", getValueFromArray($databaseconfig['database'], 'user', ''));
define("DATABASE_PASSWORD", getValueFromArray($databaseconfig['database'], 'password'), '');

//Default CSS and JS
foreach ($appconfig['html']['js'] as $jsfile) {
    MemoryPage::addJs($jsfile);
}

foreach ($appconfig['html']['css'] as $cssfile) {
    MemoryPage::addCss($cssfile);
}

//Set Memory values
foreach ($appconfig['memory'] as $k => $v) {
    Memory::set($k, $v);
}

//Error lib - http://logging.apache.org/log4php/quickstart.html
require_once PATH_MYFRAME . '/LoggerApp.php';
Logger::configure(PATH_MYFRAME . '/app_default/conf/php4log.xml');

// pra rodar o instalador precisa comentar essas 3 linhas
if (file_exists(PATH_APP . '/appbootstrap.php')) {
    require_once (PATH_APP . '/appbootstrap.php');
}
