WHMCS Hook Manager
==================

Purpose
-------
This class and associated code was created due to the fact that WHMCS does not allow mutiple hooks calls on the same prioity.
The issue comes with encrypted code you can not change. What if two functions were put in the same hook prioity? It would not run as expected.

This was intally created as I found having 2 hooks calls to "inject" code into a client area page were canceling each other out. Somehow the array argument getting past was getting cleared/reset even when no result was returned from the hook function callback.
This was causing only 1 hook to function properly at a time. It also apparently occured for that specific hook even on different priorities.


How to Use With WHMCS Hooks
---------------------------

The class uses static methods and is fully compatible with WHMCS arguments. The only catch is due to the use of func_get_args(), you may need to go a level deeper in the argument/param array to get arguments.

This class unlike WHMCS add_hook() supports class callbacks.

### Example
```php
  whmcsHookManager::addHook("AdminLogin",1,"myHookFuntion");
  function myHookFunction($vars)
  {
    var_dump($vars);
    die();
  }
```
### Class Example
  ```php
  $someClass = new myHookCallbackClass;
  whmcsHookManager::addHook("AdminLogin",1,array($someClass,"myHookCallbackMethod"));
  class myHookCallbackClass()
  {
    function myHookCallbackMethod($vars)
    {
      var_dump($vars);
      die();
    }
  }
```

How To Install
--------------
I recommend you put this class in [WHMCS ROOT]/includes/hooks/. If you put it with you module, use a require_once and/or class_exists() check to prevent duplicate declarations.

Contributing
------------
You ae welcome to add to or improve my code at any time. Just fork, edit, and send a pull request.
I am not the best at code beauty so I use http://beta.phpformatter.com/ on PEAR style to keep code in check. 

Please do so, so I don't have to :)

Thanks and I hope you find this useful.

License
-------
I m putting this code unde GPLv3 license, so please be nice and follow the rules :).
