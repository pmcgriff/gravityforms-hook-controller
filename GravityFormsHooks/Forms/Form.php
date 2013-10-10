<?php

namespace GravityFormsHooks\Forms
{
  class Form extends \GravityFormsHooks\HookController
  {
    public function submit($form)
    {
      $_POST['input_12'] = 'My Form Input';
    }

    public function confirmation($confirmation, $form, $lead, $ajax)
    {
      if($lead[5] === "BAD NEWS BUDDY") {
        $confirmation = array("redirect" =>"/EPIC-FAIL");
        return $confirmation;
      }
    }

    public function myFormFieldParam()
    {
      return 'My Default Value';
    }

    public function myOtherValueFunction()
    {
      return 'My Other Field Value';
    }
  }
}