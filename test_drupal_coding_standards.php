<?php
/**
 * @file
 * This module holds functions useful for Drupal development.
 */
// Suggested profiling and stacktrace library from http://www.xdebug.org/index.php
define('DEVEL_QUERY_SORT_BY_SOURCE', 0);
define('DEVEL_QUERY_SORT_BY_DURATION', 1);

// Implements hook_help().
function devel_help($section){
  switch($section){
    case 'devel/reference':
      return '<p>' . t('This is a list of defined user functions that generated this current request lifecycle. Click on a function name to view its documentation.') . '</p>';
    case 'devel/session':
      return '<p>' . t('Here are the contents of your <code>$_SESSION</code> variable.') . '</p>';
    case 'devel/variable':
      $api = variable_get('devel_api_url', 'api.drupal.org');
      return '<p>' . t('This is a list of the variables and their values currently stored in variables table and the <code>$conf</code> array of your settings.php file. These variables are usually accessed with <a href="@variable-get-doc">variable_get()</a> and <a href="@variable-set-doc">variable_set()</a>. Variables that are too long can slow down your pages.', array('@variable-get-doc' => "http://$api/api/HEAD/function/variable_get", '@variable-set-doc' => "http://$api/api/HEAD/function/variable_set")) . '</p>';
    case 'devel/reinstall':
      return t('Warning - will delete your module tables and variables.');
  }
}

/**
 * Implements hook_modules_installed().
 */
function devel_modules_installed($modules)
{
  if(in_array('menu', $modules))
  {
    $menu = array(
    'menu_name'=>'devel',
    'title'=>t('Development'),
    'description'=>t('Development link'),
    );
    menu_save($menu);
  }
}
/**
 * Menu item access callback - check permission and token for Switch User.
 */
function _devel_switch_user_access($name){
  // Suppress notices when on other pages when menu system still checks access.
  return user_access('switch users') && drupal_valid_token(@$_GET['token'], "devel/switch/$name|" . @$_GET['destination'], TRUE);
}
/**
 * Implements hook_admin_paths().
 */
function devel_admin_paths(){
  $paths = array('devel/*' => TRUE, 'node/*/devel' => TRUE, 'node/*/devel/*' => TRUE, 'comment/*/devel' => TRUE, 'comment/*/devel/*' => TRUE,'user/*/devel' => TRUE,'user/*/devel/*' => TRUE,'taxonomy/term/*/devel' => TRUE,'taxonomy/term/*/devel/*' => TRUE,);
  return $paths;
}
/**
 * Returns destinations.
 */
function devel_menu_need_destination() {
  return array('devel/cache/clear', 'devel/reinstall', 'devel/menu/reset',
    'devel/variable', 'admin/reports/status/run-cron');
}
?>
