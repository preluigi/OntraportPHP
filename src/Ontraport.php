<?php
	namespace Ontraport;
	/**
	 * class Ontraport
	 */
	class Ontraport {
		/**
		 * [$app_id description]
		 * @var [type]
		 */
		protected $app_id;
		/**
		 * [$app_key description]
		 * @var [type]
		 */
		protected $app_key;
		/**
		 * [$endpoint description]
		 * @var [type]
		 */
		protected $endpoint;
		/**
		 * [$version description]
		 * @var [type]
		 */
		protected $version;
		/**
		 * [__construct description]
		 * @param [type] $app_id  [description]
		 * @param [type] $app_key [description]
		 */
		public function __construct ( $app_id, $app_key ) {
			$this->set_app_id ( $app_id );
			$this->set_app_key ( $app_key );
			$this->set_version ( 1 );
			$this->default_endpoints ();
		}

		/**
		 * [set_version description]
		 * @param [type] $v [description]
		 */
		public function set_version ( $v ) {
			$this->version = $v;
		}

		/**
		 * [set_app_id description]
		 * @param [type] $id [description]
		 */
		public function set_app_id ( $id ) {
			$this->app_id = $id;
		}

		/**
		 * [set_app_key description]
		 * @param [type] $key [description]
		 */
		public function set_app_key ( $key ) {
			$this->app_key = $key;
		}

		/**
		 * [set_endpoint description]
		 * @param [type] $endpoint [description]
		 * @param [type] $subst    [description]
		 */
		public function set_endpoint ( $endpoint, $subst = false ) {
			if ( $subst == true ) {
				$this->endpoint = $endpoint;
			} else {
					foreach ( $endpoint as $k => $endp ) {
						$this->endpoint [ $k ] = $endp;
					}
			}
		}

		/**
		 * [get_version description]
		 * @return [type] [description]
		 */
		public function get_version () {
			return $this->version;
		}

		/**
		 * [get_app_id description]
		 * @return [type] [description]
		 */
		public function get_app_id () {
			return $this->app_id;
		}

		/**
		 * [get_app_key description]
		 * @return [type] [description]
		 */
		public function get_app_key () {
			return $this->app_key;
		}

		/**
		 * [get_endpoint description]
		 * @param  string $_id [description]
		 * @return [type]      [description]
		 */
		public function get_endpoint ( $_id = '' ) {
			if ( $_id != '' && isset ( $this->endpoint [ $_id ] ) )
				return $this->endpoint [ $_id ];
			return $this->endpoint;
		}

		/**
		 * [default_endpoints description]
		 * @return [type] [description]
		 */
		protected function default_endpoints () {
			$this->set_endpoint ( array (
				'object' => "https://api.ontraport.com/{$this->version}/object",
				'objects' => "https://api.ontraport.com/{$this->version}/objects",
				'objects_meta' => "https://api.ontraport.com/{$this->version}/objects/meta",
				'objects_tag' => "https://api.ontraport.com/{$this->version}/objects/tag",
				'form' => "https://api.ontraport.com/{$this->version}/form",
				'message' => "https://api.ontraport.com/{$this->version}/message",
				'task_cancel' => "https://api.ontraport.com/{$this->version}/task/cancel",
				'task_complete' => "https://api.ontraport.com/{$this->version}/task/complete",
				'transaction_processmanual' => "https://api.ontraport.com/{$this->version}/transaction/processManual",
				'transaction_refund' => "https://api.ontraport.com/{$this->version}/transaction/refund",
				'transaction_converttodecline' => "https://api.ontraport.com/{$this->version}/transaction/convertToDecline",
				'transaction_converttocollections' => "https://api.ontraport.com/{$this->version}/transaction/convertToCollections",
				'transaction_void' => "https://api.ontraport.com/{$this->version}/transaction/void",
				'transaction_voidpurchase' => "https://api.ontraport.com/{$this->version}/transaction/voidPurchase",
				'transaction_reruncommission' => "https://api.ontraport.com/{$this->version}/transaction/rerunCommission",
				'transaction_markpaid' => "https://api.ontraport.com/{$this->version}/transaction/markPaid",
				'transaction_rerun' => "https://api.ontraport.com/{$this->version}/transaction/rerun",
				'transaction_writeoff' => "https://api.ontraport.com/{$this->version}/transaction/writeOff",
				'transaction_order' => "https://api.ontraport.com/{$this->version}/transaction/order",
				'transaction_resendinvoice' => "https://api.ontraport.com/{$this->version}/transaction/resendInvoice"
			) );
		}

		/**
		 * [send_request description]
		 * @param  string $endpoint   HTTP endpoint for the REST call
		 * @param  string $method     HTTP verb to use
		 * @param  array $parameters  HTTP request-body content
		 * @return string             HTTP response-body content
		 */
		public function send_request ( $endpoint, $method, $parameters ) {
			/* instantiate HTTP headers with authentication data from Ontraport */
			$headers = array ();
			array_push ( $headers, "Api-Appid: {$this->app_id}" );
			array_push ( $headers, "Api-Key: {$this->app_key}" );

			/* istantiate querystring and postargs variables that will be used respectively in GET and POST/PUT requests */
			$querystring = '';
			$postargs = '';

			/* which method will we be using? */
			$method = strtoupper ( $method );
			if ( $method == 'GET' ) {
				/* we will use GET so let build the query string that we will append to the endpoint */
				$querystring = '?' . http_build_query ( $parameters );
			} else {
				/* we will use POST or PUT so we set up the request-body postargs */
				$postargs = http_build_query ( $parameters );
			}

			/* Setting up the cURL object */
			$session = curl_init ();
			curl_setopt ( $session, CURLOPT_URL, $this->endpoint [ $endpoint ] . $querystring );
			curl_setopt ( $session, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ( $session, CURLOPT_CUSTOMREQUEST, $method );
			curl_setopt ( $session, CURLOPT_HTTPHEADER, $headers );
			curl_setopt ( $session, CURLOPT_USERAGENT, 'LSPOAW/LSP Ontraport API Wrapper' );

			if ( $method != 'GET' ) {
				curl_setopt ( $session, CURLOPT_POSTFIELDS, $postargs );
			}

			/* Executing cURL call and return result */
			$response = curl_exec ( $session );
			curl_close ( $session );

			return $response;
		}

		/**
		 * [__get description]
		 * @param  [type] $name [description]
		 * @return [type]       [description]
		 */
		public function __get ( $name ) {
			try {
				$name = 'Ontraport\\' . $name;
				return new $name( $this );
			} catch ( Exception $e ) {
				throw $e;
			}
		}
?>
