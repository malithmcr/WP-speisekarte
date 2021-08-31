<?php
require_once dirname(__FILE__) . '/de.php';
require_once dirname(__FILE__) . '/en.php';

function SKDE_useTranslations($language, $string){
  switch($language){
    case 'de':
      return  $GLOBALS['de'][$string];
      break;
    case 'de':
      return  $GLOBALS['en'][$string];
      break;
    default:
      return  $GLOBALS['en'][$string];
  }
}