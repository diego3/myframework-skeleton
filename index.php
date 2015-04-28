<?php
session_start();
/**
 * Página que recebe todas as requisições dinâmicas e encaminha a solicitação para o respectivo page
 * Ordem/Padrão da URL:
 *      /[page]
 * 
 *      Se o segundo parametro é uma string:
 *          /[page]/[action]
 *          /[page]/[action]?responseType=[format]
 * 
 *      Se o segundo parâmetro é um inteiro:
 *          /[page]/[id]
 *          /[page]/[id]?responseType=[format]
 * 
 *      /[page]/[id]/[action]
 *      /[page]/[id]/[action]?responseType=[format]
 * 
 *      /[page]/[id]/[action]/[format]
 * 
 *      //TODO arquivos publicados dos contatos
 */

/**
  * Display all errors when APPLICATION_ENV is development.
  */
 if ($_SERVER['APPLICATION_ENV'] == 'development') {
     error_reporting(E_ALL);
     ini_set("display_errors", 1);
 }
 
require_once 'bootstrap.php';
$pageclass = filter_input(INPUT_GET, '_page', FILTER_SANITIZE_STRING);
if (empty($pageclass)) {
    $pageclass = 'main';
}

$action = filter_input(INPUT_GET, '_action', FILTER_SANITIZE_STRING);
if (empty($action)) {
    $action = '';
}

$type = filter_input(INPUT_GET, 'responseType', FILTER_CALLBACK, array("options" => "ResponseType::getDefaultType"));

$page = Factory::page($pageclass);

if (filter_has_var(INPUT_GET, '_id')) {
    $page->setId(filter_input(INPUT_GET, '_id', FILTER_SANITIZE_STRING));
}

$page->service(ProcessRequest::getMethod(), $action, $type);
