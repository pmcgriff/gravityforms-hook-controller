## GravityForms Hook Controller

GravityForms is a WordPress plugin that allows for creating and managing forms via a simple User Interface. It also includes a Developer API that follows WordPress' hook/filter implementation. This plugin provides an object oriented implementation of the GravityForms API.

### Getting Started

To get started, clone the repository into your `./wp-content/plugins` folder. Any hooks you implement yourself may be placed in the main plugin file, `gravityforms.php`.

### Creating Form Controllers

A simple form controller container would look like this:

```
<?php

namespace GravityFormsHooks\Forms
{
  class Form extends \GravityFormsHooks\HookController
  {
    // Implement your methods here.
  }
}
```

I recommend grouping methods for each form in their own class for code organization, but how you create your controllers is up to you.

### Methods

Implementation should follow the GravityForms Documentation's instructions for using either hooks or filters. The exception to this rule is use of the `field_value` hook, which should implement the `GravityFormsHooks\Loader::value()` method.

For instance, the [gform_form_tag](http://www.gravityhelp.com/documentation/page/Gform_form_tag) hook's usage example shows that it should be implemented using the `add_filte()r` function. Therefore, using the GravityForms Hook Controller, we would implement it via the `GravityFormsHooks\Loader::filter()` method.

This plugin provides three methods for implementing GravityForms Hooks:

* `GravityFormsHooks\Loader::action()`

* `GravityFormsHooks\Loader::filter()`

* `GravityFormsHooks\Loader::value()`

#### `action()` and `filter()`

The usage for Action and Filter hooks are similar, and will therefore be covered together.

```
GravityFormsHooks\Loader::action(1, 'Form', 'pre_submission', 'submit');
GravityFormsHooks\Loader::filter(1, 'Form', 'confirmation', 'confirmation', 10, 4);
```

###### Parameters

* **$form_id** REQUIRED
	
	The Gravity Forms Form ID. If a hook should be implemented globally, or the hook does not support specific form targeting, pass `bool(FALSE)`.

* **$class** REQUIRED

	The name of the class where the hook's logic is implemented. The class must be a method of the `GravityFormsHooks\Forms` namespace.

* **$hook** REQUIRED

	The GravityForms Hook name. Omit the `gform_` and form ID portion of the hook name. i.e. for 'gform_pre_submission_5`, pass `pre_submission`. If a hook should be applied to all forms, pass bool(FALSE) as $form_id

* **$function** REQUIRED

	The method of param `$class` object to call when the hook is fired.

* **$priority** OPTIONAL
	
	Defaults to 10. See [WordPress Codex](http://codex.wordpress.org/Function_Reference/add_action#Parameters) for more information.
	
* **$accepted_args** OPTIONAL

	Defaults to 1. See [WordPress Codex](http://codex.wordpress.org/Function_Reference/add_action#Parameters) for more information.


#### `value()`

The Value hook allows for pre-populating values of fields.

```
GravityFormsHooks\Loader::value('Form', 'myFormFieldParam', 'myValueFunction');
GravityFormsHooks\Loader::value('Form', 'myOtherFormFieldParam', 'myOtherValueFunction', 'specific-page');
```

###### Parameters

* **$class** REQUIRED

	The name of the class where the hook's logic is implemented. The class must be a method of the `GravityFormsHooks\Forms` namespace.

* **$param** REQUIRED

	The GravityForms Field Parameter to populate. See [GravityForms Documentation](http://www.gravityhelp.com/documentation/page/Gform_field_value_$parameter_name). More information on setting parameter values on your field can be found below.

* **$function** REQUIRED

	The method of param `$class` object to call when the hook is fired.

* **$page** OPTIONAL

	If $page is set, the value will only be set when the page slug matches the given value.

###### Setting Parameter Values

In the form editor, enter a value in the `Parameter Name` field. Make sure that `Allow field to be populated dynamically` is checked. This value should match the `$param` argument in your `value()` function invokation.

![Setting Parameter Values](https://raw.github.com/jdpedrie/gravityforms-hook-controller/assets/param.png)

### Authors

GravityForms Hook Controller is written by me, [John Dennis Pedrie](http://johnpedrie.com).

## License

GravityForms Hook Controller is licensed under the MIT license. A copy of the MIT license may be found in `LICENSE.md`.
