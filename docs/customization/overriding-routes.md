# Overriding Routes

You can override any Hymer routes by writing the routes you want to overwrite below `Hymer::routes()`. For example if you want to override your post LoginController:

```php
Route::group(['prefix' => 'admin'], function () {
   Hymer::routes();

   // Your overwrites here
   Route::post('login', ['uses' => 'MyAuthController@postLogin', 'as' => 'postlogin']);
});
```

