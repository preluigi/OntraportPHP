<?php
  namespace Ontraport;

  /**
   * The following include load the needed credentials configuration.
   * See config.sample.php for instructions
   */
  include ( __DIR__ . '/config.php' );


  class OntraportTest extends \PHPUnit_Framework_TestCase {
    /**
     * [testSetGet description]
     * @return [type] [description]
     */
    public function testSetGet () {
      $ontraport = new Ontraport ( APP_ID, APP_KEY );
      $ontraport->set_version ( 'test' );
      $this->assertEquals ( 'test', $ontraport->get_version () );
      $this->assertEquals ( APP_ID, $ontraport->get_app_id () );
      $this->assertEquals ( APP_KEY, $ontraport->get_app_key () );

      $endpoints = $ontraport->get_endpoint ();
      $this->assertEquals ( 'array', gettype ( $endpoints ) );
      $test_endpoints = array ( 'a' => 'b', 'c' => 'd' );
      $ontraport->set_endpoint ( $test_endpoints, true );
      $this->assertEquals ( $test_endpoints, $ontraport->get_endpoint () );
      $ontraport->set_endpoint ( $endpoints, true );
      $test_endpoints = array ( 'objects' => 'http://test_objects', 'object' => 'http://test_object' );
      $ontraport->set_endpoint ( $test_endpoints );
      foreach ( $test_endpoints as $k => $v ) { $endpoints [ $k ] = $v; }
      $this->assertEquals ( $endpoints, $ontraport->get_endpoint () );
    }

    /**
     * [testConnection description]
     * @return [type] [description]
     * @depends testSetGet
     */
    public function testConnection () {
      $ontraport = new Ontraport ( APP_ID, APP_KEY );
      $result = json_decode ( $ontraport->send_request ( 'objects_meta', 'get', array () ) );
      $this->assertObjectHasAttribute ( 'data', $result );
    }

    public function testContactCreate () {
      $ontraport = new Ontraport ( APP_ID, APP_KEY );

      $contact = new \stdClass;
      $contact->email = 'test@test.me';
      $contact->firstname = 'Test';
      $contact->lastname = 'User';

      $result = $ontraport->Contacts->create ( $contact );

      $this->assertObjectHasAttribute ( 'id', $result->data );

      return $result->data->id;
    }

    /**
     * [testContactGet description]
     * @return [type] [description]
     * @depends testContactCreate
     */
    public function testContactGet ( $id ) {
      $ontraport = new Ontraport ( APP_ID, APP_KEY );
      $contact = $ontraport->Contacts->read ( $id );
      $this->assertEquals ( 'Test', $contact->data->firstname );
      return $id;
    }

    /**
     * [testContactDelete description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     * @depends testContactGet
     */
    public function testContactDelete ( $id ) {
      $ontraport = new Ontraport ( APP_ID, APP_KEY );
      $result = $ontraport->Contacts->delete ( $id );
    }

    public function testProductCreate () {
      $ontraport = new Ontraport ( APP_ID, APP_KEY );
      $result = $ontraport->Products->create (
        array (
          'name' => 'An awesome product!',
          'price' => 119.95
        )
      );
      $this->assertObjectHasAttribute ( 'id', $result->data );
    }
  }
?>
