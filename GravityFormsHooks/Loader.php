<?php

namespace GravityFormsHooks
{
  use \ReflectionClass;

  class Loader
  {

    /**
     * Function call log
     * @var array
     * @static
     */
    public static $called = array();

    const HOOK_ACTION = 'add_action';
    const HOOK_FILTER = 'add_filter';

    /**
     * Create an ACTION hook
     * @param  int|bool  $form_id        The Gravity Forms Form ID. Pass bool(FALSE) for a global hook.
     * @param  string    $class          The name of the Class to instantiate. Must be a member of namespace `GravityFormsHooks\Forms`.
     * @param  string    $hook           The GravityForms Hook name. Omit the `gform_` and form ID portion of the hook name.
     *                                   i.e. for 'gform_pre_submission_5`, pass `pre_submission`.
     *                                   If a hook should be applied to all forms, pass bool(FALSE) as $form_id
     * @param  string    $function       The method of param `$class` object to call when the hook is fired.
     * @param  int       $priority       Defaults to 10. See WordPress Codex for more information.
     * @param  int       $accepted_args  Defaults to 1. See WordPress Codex for more information.
     * @see    http://codex.wordpress.org/Function_Reference/add_action#Parameters
     * @return null
     */
    public static function action($form_id, $class, $hook, $function, $priority = 10, $accepted_args = 1)
    {
      self::hook(self::HOOK_ACTION, $form_id, $class, $hook, $function, $priority, $accepted_args);
    }

    /**
     * Create a FILTER hook
     * @param  int|bool  $form_id        The Gravity Forms Form ID. Pass bool(FALSE) for a global hook.
     * @param  string    $class          The name of the Class to instantiate. Must be a member of namespace `GravityFormsHooks\Forms`.
     * @param  string    $hook           The GravityForms Hook name. Omit the `gform_` and form ID portion of the hook name.
     *                                   i.e. for 'gform_pre_submission_5`, pass `pre_submission`.
     *                                   If a hook should be applied to all forms, pass bool(FALSE) as $form_id
     * @param  string    $function       The method of param `$class` object to call when the hook is fired.
     * @param  int       $priority       Defaults to 10. See WordPress Codex for more information.
     * @param  int       $accepted_args  Defaults to 1. See WordPress Codex for more information.
     * @see    http://codex.wordpress.org/Function_Reference/add_action#Parameters
     * @return null
     */
    public static function filter($form_id, $class, $hook, $function, $priority = 10, $accepted_args = 1)
    {
      self::hook(self::HOOK_FILTER, $form_id, $class, $hook, $function, $priority, $accepted_args);
    }

    /**
     * Set a DEFAULT VALUE
     * @param  string    $class          The name of the Class to instantiate. Must be a member of namespace `GravityFormsHooks\Forms`.
     * @param  string    $param          The GravityForms Field Parameter to populate. See GravityForms Documentation.
     * @param  string    $function       The method of param `$class` object to call when the hook is fired.
     * @param  string    $page           The Page Slug to limit hook to. If not set, hook will be fired everywhere this form is displayed.
     * @see    http://www.gravityhelp.com/documentation/page/Gform_field_value_$parameter_name
     * @return null
     */
    public static function value($class, $param, $function, $page = '')
    {
      if ($page) {
        $slug = explode('/', $_SERVER['REQUEST_URI']);

        if (!in_array($page, $slug)) return;
      }

      self::hook(self::HOOK_FILTER, $param, $class, 'field_value', $function, $priority, $accepted_args);
    }

    private static function hook($hook_type, $form_id, $class, $hook, $function, $priority = 10, $accepted_args = 1)
    {
      $namespacedClassName = '\GravityFormsHooks\\Forms\\'. $class;
      $class = new $namespacedClassName;

      if($form_id) {
        $hook_name = 'gform_'. $hook .'_'. $form_id;
      }
      else {
        $hook_name = 'gform_'. $hook;
      }

      $hook_type($hook_name, array($class, $function), $priority, $accepted_args);

      self::logFunctionCall(func_get_args());
    }

    private static function logFunctionCall($arguments)
    {
      $reflector = new ReflectionClass(__CLASS__);
      $parameters = $reflector->getMethod('hook')->getParameters();

      $callTrace = array();
      foreach ($parameters as $key => $parameter) {
        $callTrace[$parameter->name] = $arguments[$key];
      }

      self::$called[] = $callTrace;
    }
  }
}