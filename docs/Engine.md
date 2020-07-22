* [Registry](#registry)
* [Loader](#loader)
* [Action](#action)

## Registry

A basic container class with only set and get methods to store library objects.

There is no need to use the registry class directly with controllers as is available via the controller and models.

###### Example

```
$this->load->library('cache');

$this->registry->get('cache')->get('products');

// is the same as
$this->cache->get('products');
```

### Methods

#### `object get(string $key)`

Returns the library object.

##### Parameters

| Tables | Type   | Description |
|:------ |:-------|:------------|
| $key   | string | gets the library object by key |

##### Returns

returns the library object by key

#### `void set(string $key, object $value)`

Sets a new library

##### Parameters

| Tables | Type | Description |
| :--- | :--- | :--- |
| $key | string | gets the library object by key |
| $value | object | gets the library object by key |

#### `boolean has(string $key)`

Checks if a library is using a key

##### Parameters

| Tables        | Type | Description |
|:------------- |:-----|:------------|
| $key | string | gets the library object by key |

##### returns 

Returns if a library key is being used.

***

## Loader

* `__construct(string $registry)`

##### Parameters

| Tables        | Type | Description |
|:------------- |:-----|:------------|
| $registry | object | object registry |

* `controller(string $key, object $value)` - loads the requested controller into the registry class.

##### Parameters

| Tables        | Type | Description |
|:------------- |:-----|:------------|
| $registry | object | object registry |


* `model(string $key)` - loads the requested controller into the registry class.
* `view(string $key, array $value)` - loads the requested view and returns the output.
* `library(string $key)` - Deletes the cache by key
* `helper(string $key)` - Deletes the cache by key
* `config(string $key)` - Deletes the cache by key
* `language(string $key)` - Deletes the cache by key

The loader class is for loading the different components of the OpenCart framework.

***

## Action

Action classes are used to load controllers.

###### Example

```
$action = new Action('common/home');
$action->execute($this->registry);
```

#### __construct

Constructor.

```
__construct(string $registry)
```

#### execute

Calls the requested controller. Requires the registry so it can be passed into the controller. 

```
execute(object $registry) : mixed
```


## Event

