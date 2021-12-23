<?php

use JoeMugen\EloquentyModel\MassAssignmentException;
use JoeMugen\EloquentyModel\Tests\Stubs\ModelStub;
use function Pest\Faker\faker;

it('can create a model instance from a valid array', function () {
    $model = new ModelStub([
        'name' => $name = faker()->firstName,
    ]);
    expect($model)->toBeInstanceOf(ModelStub::class);
    expect($model->name)->toBeString()->toBe($name);
    expect($model['name'])->toBeString()->toBe($name);
});

it('can create a model instance without array', function () {
    $model = new ModelStub;
    expect($model)->toBeInstanceOf(ModelStub::class);
});

it('can create a new model instance with attributes', function () {
    $model = new ModelStub;
    $instance = $model->newInstance([
        'name' => $name = faker()->firstName,
    ]);
    expect($model)->toBeInstanceOf(ModelStub::class);
    expect($model->name)->toBeNull();
    expect($instance)->toBeInstanceOf(ModelStub::class);
    expect($instance->name)->toBeString()->toBe($name);
});

it('can manipulate model properties', function () {
    $model = new ModelStub;
    $model->name = $word = faker()->word;
    expect($model->name)->toBeString()->toBe($word);
    unset($model->name);
    expect($model->name)->toBeNull();
});

it('can manipulate model attributes', function () {
    $model = new ModelStub;
    $model['name'] = $word = faker()->word;
    expect($model['name'])->toBeString()->toBe($word);
    unset($model['name']);
    expect($model['name'])->toBeNull();
});

it('should hide hidden attributes', function () {
    $model = new ModelStub;
    $model->password = faker()->password;
    $attributes = $model->attributesToArray();
    expect(isset($attributes['password']))->toBeFalse();
    expect($model->getHidden())->toBeArray()->toBe(['password']);
});

it('should show attributes when set to visible', function () {
    $model = new ModelStub;
    $model->setVisible(['name']);
    $model->name = $firstname = faker()->firstName;
    $model->city = faker()->city;
    $attributes = $model->attributesToArray();
    expect($attributes)->toBeArray()->toBe(['name' => $firstname]);
});

it('should returns the model as array', function () {
    $model = new ModelStub;
    $model->name = $firstname = faker()->firstName;
    $model->foobar = null;
    $model->password = faker()->password;
    $model->setHidden(['password']);
    $modelToArray = $model->toArray();

    expect($modelToArray)->toBeArray()->toMatchArray([
        'name' => $firstname,
    ]);
    expect(isset($attributes['password']))->toBeFalse();
    expect($modelToArray['foobar'])->toBeFalsy();
});

it('should returns the model as JSON', function () {
    $model = new ModelStub;
    $model->name = $firstname = faker()->firstName;
    $model->foobar = $foobar = faker()->randomNumber();
    $model->password = faker()->password;
    $model->setHidden(['password']);

    $object = new stdClass;
    $object->name = $firstname;
    $object->foobar = $foobar;
    $json = json_encode($object);

    expect($model->toJson())->toBeJson()->toMatch($json);
    expect((string)$model)->toBeJson()->toMatch($json);
});

it('should mutate attributes', function () {
    $model = new ModelStub;
    $model->list_items = ['name' => $name = faker()->firstName];
    expect($model->list_items)->toBeArray()->toBe(['name' => $name]);
    $attributes = $model->getAttributes();
    expect($attributes['list_items'])->toBeJson()->toBe(json_encode(['name' => $name]));

    $birthday = strtotime('245 months ago');
    $model = new ModelStub;
    $model->birthday = '245 months ago';
    expect($model->birthday)->toBe(date('Y-m-d', $birthday));
    expect($model->age)->toBeInt()->toBe(20);
});

it('should use mutators when using to array', function () {
    $model = new ModelStub;
    $model->list_items = $items = [1, 2, 3];
    $array = $model->toArray();
    expect($array['list_items'])->toBeArray()->toBe($items);
});

it('should replicate', function () {
    $model = new ModelStub;
    $model->name = $name = faker()->firstName;
    $model->city = $city = faker()->city;
    $clone = $model->replicate();
    expect($clone)->toBeInstanceOf(ModelStub::class);
    expect($clone)->toEqual($model);
    expect($clone->name)->toBeString()->toBe($name);
    expect($clone->city)->toBeString()->toBe($city);
});

it('should appends', function () {
    $model = new ModelStub;
    $array = $model->toArray();
    expect(isset($array['test']))->toBeFalse();

    $model = new ModelStub;
    $model->setAppends(['test']);
    $array = $model->toArray();
    expect(isset($array['test']))->toBeTrue();
    expect($array['test'])->toBeString()->toBe('test');
});

it('should access array properties', function () {
    $model = new ModelStub;
    $model->name = faker()->firstName;
    $model['city'] = faker()->city;
    expect($model->name)->toBeString()->toBe($model['name']);
    expect($model->city)->toBeString()->toBe($model['city']);
});

it('should serialize the model', function () {
    $model = new ModelStub;
    $model->name = $name = faker()->firstName;
    $model->foobar = 10;
    $serialized = serialize($model);
    expect($model)->toEqual(unserialize($serialized));
});

it('should cast attributes as defined', function () {
    $model = new ModelStub;
    $model->score = '0.34';
    $model->data = ['foo' => 'bar'];
    $model->count = 1;
    $model->object_data = $objectData = ['foo' => 'bar'];
    $model->active = 'true';
    $model->default = $default = faker()->word;
    $model->collection_data = [['foo' => 'bar', 'baz' => 'bat']];

    expect($model->score)->toBeFloat();
    expect($model->data)->toBeArray();
    expect($model->active)->toBeBool();
    expect($model->count)->toBeInt();
    expect($model->default)->tobeString()->toBe($default);
    expect($model->object_data)->toBeInstanceOf(stdClass::class);
    expect($model->collection_data)->toBeInstanceOf(\Illuminate\Support\Collection::class);

    $attributes = $model->getAttributes();
    expect($attributes['score'])->tobeString();
    expect($attributes['data'])->tobeString();
    expect($attributes['active'])->tobeString();
    expect($attributes['count'])->toBeInt();
    expect($attributes['default'])->tobeString();
    expect($attributes['object_data'])->tobeString();
    expect($attributes['collection_data'])->tobeString();

    $array = $model->toArray();
    expect($array['score'])->toBeFloat();
    expect($array['data'])->toBeArray();
    expect($array['active'])->toBeBool();
    expect($array['count'])->toBeInt();
    expect($array['default'])->toBe($default);
    expect($array['object_data'])->toBeInstanceOf(stdClass::class);
    expect($array['collection_data'])->toBeInstanceOf(\Illuminate\Support\Collection::class);
});

it('should guard attributes', function () {
    $model = new ModelStub([
        'secret' => faker()->word,
    ]);
    expect($model->isGuarded('secret'))->toBeTrue();
    expect($model->secret)->toBeNull();
    expect($model->getGuarded()[0])->toBe('secret');

    $model->secret = $secret = faker()->word;
    expect($model->secret)->toBe($secret);

    ModelStub::unguard();
    expect(ModelStub::isUnguarded())->toBeTrue();

    $model = new ModelStub([
        'secret' => $secret = faker()->word,
    ]);
    expect($model->secret)->toBe($secret);

    ModelStub::reguard();
});

it('can use guard callback', function () {
    ModelStub::unguard();
    $mock = $this->getMockBuilder(stdClass::class)
        ->addMethods(['callback'])
        ->getMock();

    $mock->expects($this->once())
        ->method('callback')
        ->willReturn('foo');
    $string = ModelStub::unguarded([$mock, 'callback']);
    $this->assertEquals('foo', $string);
    ModelStub::reguard();
});

it('can guard all attributes using wildcard', function () {
    $model = new ModelStub();
    $model->guard(['*']);
    $model->fillable([]);
    $model->fill(['name' => faker()->firstName]);
})->throws(MassAssignmentException::class);

it('should be fillable', function () {
    $model = new ModelStub(['foo' => 'bar']);
    expect($model->isFillable('foo'))->toBeFalse();
    expect($model->foo)->toBeNull();
    expect($model->getFillable())->not()->toContain('foo');

    $model->foo = 'bar';
    expect($model->foo)->toBe('bar');

    $model = new ModelStub;
    $model->forceFill(['foo' => 'bar']);
    expect($model->foo)->toBe('bar');
});

it('should hydrate the model', function () {
    $models = ModelStub::hydrate([
        ['name' => $name = faker()->firstName]
    ]);
    expect($models[0]->name)->toBe($name);
});