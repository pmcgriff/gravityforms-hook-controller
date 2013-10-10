<?php

/*
Plugin Name: Gravity Forms Hook Framework
Author: John Dennis Pedrie
Plugin URI: https://github.com/jdpedrie/gravityforms-hook-controller
Description: Modify your Gravity Forms with a nice OO container.
Version: 1.0
Author URI: http://johnpedrie.com
License: MIT
*/

spl_autoload_register(function($class) {
  if(strpos($class, 'GravityFormsHooks') === FALSE) return false;

  $class = explode('\\', $class);

  $class = implode('/', $class);

  include plugin_dir_path(__FILE__) . $class .'.php';
});

GravityFormsHooks\Loader::action(1, 'Form', 'pre_submission', 'submit');
GravityFormsHooks\Loader::filter(1, 'Form', 'confirmation', 'confirmation', 10, 4);

GravityFormsHooks\Loader::value('Form', 'myFormFieldParam', 'myValueFunction');
GravityFormsHooks\Loader::value('Form', 'myOtherFormFieldParam', 'myOtherValueFunction', 'specific-page');