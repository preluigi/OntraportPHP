# OntraportPHP
PHP Library for Ontraport REST API

## Installation - Using Composer
in order to use the library you need to update your require config in composer.json like this

```php
"require": {
    "preluigi/ontraport": "dev-master"
},
```

## Simple overview and usage
First you need to instantiate a new Ontraport object instance with your AppId and Key credentials

```php
$ontraport = new Ontraport\Ontraport ( 'my_app_id', 'my_app_key' );
```

Then you can access OP objects and their CRUD functions like this

```php
$my_contact = $ontraport->Contacts->read ( 55 ); //Where 55 is the ID of the contact

print_r ( $my_contact );

/**
 * Ouputs something like:
 *    stdClass Object
 *    (
 *        [code] => 0
 *        [data] => stdClass Object
 *            (
 *                [name] => Customer Name
 *                [price] => 119.95
 *                [id] => 340
 *                [owner] => 2
 *                [firstname] =>
 *                [lastname] =>
 *                [email] =>
 *                [address] =>
 *                [city] =>
 *                [state] =>
 *                [zip] =>
 *                [birthday] =>
 *                [date] => 1448207931
 *                [notes] =>
 *                [status] =>
 *                [category] =>
 *                [lead_source] =>
 *            )

 *        [updates] => Array
 *            (
 *            )

 *        [notifications] => Array
 *            (
 *            )

 *        [account_id] => 27801
 *    )
 **/

$new_product = array (
    'name' => 'An awesome product!',
    'price' => 119.95
);
$result = $ontraport->Products->create ( $new_product );

print_r ( $result );

/**
 * Outputs something like:
 * stdClass Object
 *	(
 *	    [code] => 0
 *	    [data] => stdClass Object
 *	        (
 *	            [name] => An awesome product!
 *	            [price] => 119.95
 *	            [id] => 19
 *	        )
 *
 *	    [updates] => Array
 *	        (
 *	        )
 *
 *	    [notifications] => Array
 *	        (
 *	        )
 *
 *	    [account_id] => 27801
 *	)
 **/

```

## Supported Ontraport Object Types
Currently the following Ontraport Object Types are supported:
* Contacts
* Notes
* Objects
* Products
* Purchases
* Shippings
* Staff
* Tags
* Taxes

You can access them using the already seen syntax

```php
$ontraport->{Object type classname, eg: Contacts}->{method to call}
````

## Object Types methods
Every Object Type has the following default methods

* ``` find ( string $field, mixed $value, string $operand = '=', string $type = 'auto' ) ```
* ``` create ( array $attrs ) ```
* ``` read ( int $id ) ```
* ``` get ( array $ids ) ```
* ``` update ( object $object ) ```
* ``` delete ( int $id ) ```

## Extend the library
Section coming soon
