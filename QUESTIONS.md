* Even if I didn't do some test I made sure that the code is decoupled enough so it could be implemented with a little more time.
I would have used phpunit to test some of my services or even to test the change of currency (functional test in this case).
* Regarding the latest features in php, I would say the arrays as constants.

```php
<?php

class MyClass
{
    ...
    const MY_CONST = [
        'key1' => 'value1',
        ...
    ];
}
```
Still using the constants you can now have expression in it.
```php
<?php

class MyClass
{
    const MY_CONST = 'value';
}

class OtherClass
{
    const OTHER_CONST = MyClass::My_CONST . ' add some text';
}
```
* Yes I did have to check about a performance issue on production.
  - I check the process running on the server to see if there is some kind of a script running for days.
  - If we just deployed on production I rollback imiedatly to run some test. Maybe it is a never ending loop or a recursive function.
  - I ask an admin sys to check for DDOS maybe.
  - Check the BDD configuration (query size limit, indexes)
* Regarding the APIs... Well as you may already know: you now need to pay for it. So it's hard to say what I would improve without ever used it. However the return response seems a little weird:
```json
"quotes": {
        "USDAED": 3.672982,
        "USDAFN": 57.8936,
        "USDALL": 126.1652,
        "USDAMD": 475.306,
        "USDANG": 1.78952,
        ...
}
```
    The fact that the "source" currency code is concat with all the currencies you've been asking is weird. 
* I use JSON for API because it is lighter. Both to implement and to send. When I use JSON I try to keep it very organized on several nested arrays. That way if the JSON has to evolve it woul be easier to retrieve the information you seek by groups.
