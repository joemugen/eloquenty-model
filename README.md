Model
=====

This model provides a Laravel eloquent-like base class that can be used to build custom models in Laravel or other frameworks.

Features
--------

 - Accessors and mutators
 - Model to Array and JSON conversion
 - Hidden attributes in Array/JSON conversion
 - Guarded and fillable attributes
 - Appending accessors and mutators to Array/JSON conversion
 - Attribute casting

You can read more about these features and the original Eloquent model on http://laravel.com/docs/eloquent

Installation
------------

Install using composer:

```bash
composer require joemugen/eloquenty-model
```

Example
-------

```php

use JoeMugen\EloquentyModel\Model;

class User extends Model {
    protected $hidden = ['password'];
    protected $guarded = ['password'];
    protected $casts = ['age' => 'integer'];

    public function save()
    {
        return API::post('/items', $this->attributes);
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = strtotime($value);
    }

    public function getBirthdayAttribute($value)
    {
        return new DateTime("@$value");
    }

    public function getAgeAttribute($value)
    {
        return $this->birthday->diff(new DateTime('now'))->y;
    }
}

$item = new User(['name' => 'John']);
$item->password = 'secret';

echo $item; // {"name":"John"}
```
