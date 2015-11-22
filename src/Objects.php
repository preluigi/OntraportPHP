<?php
	namespace Ontraport;

	class Objects {
		protected static $endpoint = 'object';
		protected static $multiple_endpoint = 'objects';
		protected $ontraport;
		protected static $object_id = 0;

		public function __construct ( &$ontraport ) {
			$this->ontraport = $ontraport;
		}

		public function search ( $field, $value, $operand = '=', $type = 'auto' ) {
			switch ( $type ) {
				case 'auto':
					if ( is_numeric ( $value ) )
						$condition = "{$field}{$operand}{$value}";
					else
						$condition = "{$field}{$operand}'{$value}'";
					break;
				case 'string':
					$condition = "{$field}{$operand}'{$value}'";
					break;
				case 'noquote':
				case 'numeric':
					$condition = "{$field}{$operand}{$value}";
					break;
			}

			$params = array (
				'objectID' => self::$object_id,
				'condition' => $condition
			);

			return json_decode ( $this->ontraport->send_request ( self::$multiple_endpoint, 'get', $params ) );
		}

		public function create ( $object ) {
			$params = array (
				'objectID' => self::$object_id
			);

			$object = json_decode ( json_encode ( $object ), true );

			$params = array_merge ( $params, $object );

			return json_decode ( $this->ontraport->send_request ( self::$multiple_endpoint, 'post', $params ) );
		}

		public function read ( $id ) {
			$params = array (
				'objectID' => self::$object_id,
				'id' => $id
			);

			return json_decode ( $this->ontraport->send_request ( self::$endpoint, 'get', $params ) );
		}

		public function get ( $ids = array () ) {
			$params = array (
				'objectID' => self::$object_id
			);

			if ( count ( $ids ) > 1 ) {
				$params [ 'ids' ] = implode ( ',', $ids );
			}

			return json_decode ( $this->ontraport->send_request ( self::$multiple_endpoint, 'get', $params ) );
		}

		public function update ( $object ) {
			$params = array (
				'objectID' => self::$object_id
			);

			$object = json_decode ( json_encode ( $object ), true );

			if ( isset ( $object [ 'id' ] ) ) {
				$params = array_merge ( $params, $object );
				return json_decode (  $this->ontraport->send_request ( self::$multiple_endpoint, 'put', $params ) );
			} else
				return false;
		}

		public function delete ( $id ) {

		}

	}
?>
