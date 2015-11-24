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
```

## Object Types methods
Every Object Type has the following default methods

* ``` find ( string $field, mixed $value, string $operand = '=', string $type = 'auto' ) ```
* ``` create ( array $attrs ) ```
* ``` read ( int $id ) ```
* ``` get ( array $ids ) ```
* ``` update ( object $object ) ```
* ``` delete ( int $id ) ```

## How the library works
Before you start diving into more advanced OntraportPHP usages I bet you want to see in detail how the library works.
So here's the answer:
The library itself is composed by a set of classes organized by that pattern:

class **Ontraport** --calls--> class **Contacts** which extends class **Objects**

at the moment the library supports only operations on Ontraport Object Types; base CRUD methods are defined in the Ontraport\Objects class which is extended by object specific classes like Contacts or Products.
So when you call ->Contacts->read() on an Ontraport\Ontraport object you're simply receiving the results from the Ontraport\Contacts->read() method which is inherited from the Ontraport\Objects class.
Behind the scenes is going on a dependency injection from the class Ontraport\Ontraport object to the called class (eg: Ontraport\Contacts), in fact here there are two working examples:

```php
$ontraport = new Ontraport\Ontraport ( 'my_app_id', 'my_app_key' );

/*
 * Outputs the object representing contact with id=55
 */
print_r ( $ontraport->Contacts->read ( 55 ) );

$contacts = new Ontraport\Contacts ( $ontraport );

/*
 * Outputs the same object as above
 */
print_r ( $contacts->read ( 55 ) );
```

## Extend the library
I suggest you to read the preceding section: **How the library works** to better understand the following instructions.

There are basically two ways to extend the library with you own classes:

**1) By using a custom namespace (Recommended)**

The Ontraport\Ontraport class exposes a method called **set_namespace** which you can use to modify the namespace of the called Object Type classes, I think is better have an example:

```php
  namespace MyNamespace;  // Using a custom namespace
  include ( __DIR__ . '/vendor/autoload.php' ); // Including the composer autoload

  /*
   * Defining a MyNamespace\Contacts class which extends Ontraport\Contacts
   */
  class Contacts extends Ontraport\Contacts {
    // Overriding the parent method
    public function read ( $id ) {
      $result = parent::read ( $id );
      // Adding the "example_field" to parent called method object result
      $result->data->example_field = 'Test';

      return $result;
    }
  }

  $ontraport = new Ontraport\Ontraport ( 'my_app_id', 'my_app_key' );

  $ontraport->Contacts->read ( 55 );  // Will return the Contact object with ID=55

  /*
   * Changing the base namespace where Ontraport\Ontraport looks for class inclusion
   */
  $ontraport->set_namespace ( 'MyNamespace' );

  $ontraport->Contacts->read ( 55 );  // Will return the Contact object with ID=55 and added field "example_field"
```
Using this approach you can override any Object Type class and define your own while mantain the $ontraport->ClassName clear and compact syntax.

**2) By using dependency injection**

This method works the same as the first mentioned but instead of changing the base namespace used for inclusion you've got to extend Object Type classes and use dependency injection to pass the Ontraport\Ontraport object to the class constructor.
Example:

```php
  namespace MyNamespace;  // Using a custom namespace
  include ( __DIR__ . '/vendor/autoload.php' ); // Including the composer autoload

  /*
   * Defining a MyNamespace\Contacts class which extends Ontraport\Contacts
   */
  class Contacts extends Ontraport\Contacts {
    // Overriding the parent method
    public function read ( $id ) {
      $result = parent::read ( $id );
      // Adding the "example_field" to parent called method object result
      $result->data->example_field = 'Test';

      return $result;
    }
  }

  $ontraport = new Ontraport\Ontraport ( 'my_app_id', 'my_app_key' );

  $contacts_object = new Contacts ( $ontraport );

  $contacts_object->read ( 55 );  // Will return the Contact object with ID=55 and added field "example_field"
```
