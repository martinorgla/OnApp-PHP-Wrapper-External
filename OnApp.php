<?php
/**
 * API Wrapper for OnApp
 *
 * This API provides an interface to onapp.net allowing common virtual machine
 * and account management tasks
 *
 * @category    API wrapper
 * @package     OnApp
 * @author      Andrew Yatskovets
 * @author      Yakubskiy Yuriy
 * @author      Lev Bartashevsky
 * @copyright   (c) 2011-2012 OnApp
 * @link        http://www.onapp.com/
 *
 * @todo        Pack using the lib (http://pecl.php.net/)
 */

/**
 * Current OnApp PHP API wrapper version
 */
define( 'ONAPP_VERSION', '3.0.dev' );

/**
 * The OnApp class uses this variable to define the Proxy server used by cURL
 */
define( 'ONAPP_OPTION_CURL_PROXY', 'proxy' );

/**
 * The OnApp class uses this variable to define the URL to the API used by cURL
 */
define( 'ONAPP_OPTION_CURL_URL', 'url' );

/**
 * The OnApp class uses this variable to define the data type which would help transfer data between the client and the API server
 *
 * Possible values:
 *   - xml  (default)
 *   - json (will be available after the parcer is created)
 */
define( 'ONAPP_OPTION_API_TYPE', 'data_type' );

/**
 * The OnApp class uses this variable to define the charsets used to transfer data between the client and the API server
 *
 * Possible values:
 *   - charset=utf-8 (default)
 */
define( 'ONAPP_OPTION_API_CHARSET', 'charset' );

/**
 * The OnApp class uses this value to define the content type used to transfer data between the client and the API server
 *
 * Possible values:
 *   - application/xml (default)
 *   - application/json (will be available after the Json parcer is created)
 */
define( 'ONAPP_OPTION_API_CONTENT', 'content' );

/**
 * TODO add description
 */
define( 'ONAPP_OPTION_DEBUG_MODE', 'debug_mode' );

/**
 * The OnApp class uses this field name to map this field in the API response and variable in the class
 * The field name is used to unserialize the API server response to the necessary class.
 */
define( 'ONAPP_FIELD_MAP', 'map' );

/**
 * The field name that stands for the mapped field type in the API response
 *
 * The field is used to unserialize the object into API request.
 * Possible values:
 *   - integer
 *   - ...
 */
define( 'ONAPP_FIELD_TYPE', 'type' );

/**
 * The field name that stands for the field access in the API response
 *
 * Used to unserialize the object into API request.
 * Possible values:
 *   - true
 *   - false
 */
define( 'ONAPP_FIELD_READ_ONLY', 'read_only' );

/**
 * The flag to exclude this field from sending request
 *
 * Possible values:
 *   - true
 *   - false
 */
define( 'ONAPP_FIELD_SKIP_FROM_REQUEST', 'skip' );

/**
 * The field name that specifies if it is necessary to be used in the API request when new objects are created or existing edited
 *
 * Possible values:
 *   - true
 *   - false
 */
define( 'ONAPP_FIELD_REQUIRED', 'required' );

/**
 * The field name that stands for the default field value
 *
 * The field name is used to unserialize if the field was changed or not loaded.
 */
define( 'ONAPP_FIELD_DEFAULT_VALUE', 'default' );

/**
 * Specify field type to serialize and unserialize obgect using their name
 */
define( 'ONAPP_FIELD_CLASS', 'class' );

define( 'ONAPP_GETRESOURCE_DEFAULT', 'default' );

define( 'ONAPP_GETRESOURCE_LOAD', 'load' );

define( 'ONAPP_GETRESOURCE_LIST', 'list' );

define( 'ONAPP_GETRESOURCE_ADD', 'add' );

define( 'ONAPP_GETRESOURCE_EDIT', 'edit' );

define( 'ONAPP_GETRESOURCE_DELETE', 'delete' );

define( 'ONAPP_GETRESOURCE_VERSION', 'version' );

define( 'ONAPP_ACTIVATE_GETLIST', 'getList' );

define( 'ONAPP_ACTIVATE_LOAD', 'load' );

define( 'ONAPP_ACTIVATE_SAVE', 'save' );

define( 'ONAPP_ACTIVATE_DELETE', 'delete' );

/**
 * Specify the GET request
 *
 */
define( 'ONAPP_REQUEST_METHOD_GET', 'GET' );

/**
 * Specify the POST request
 *
 */
define( 'ONAPP_REQUEST_METHOD_POST', 'POST' );

/**
 * Specify the PUT request
 *
 */
define( 'ONAPP_REQUEST_METHOD_PUT', 'PUT' );

/**
 * Specify the DELETE request
 *
 */
define( 'ONAPP_REQUEST_METHOD_DELETE', 'DELETE' );

/**
 * API Wrapper for OnApp
 *
 *
 * @package OnApp
 *
 *
 * @todo    Pack using the lib (http://pecl.php.net/)
 *
 * The wrapper is used to describe the following basic methods: {@link load},
 * {@link save}, {@link delete} and {@link getList}.
 *
 * To create a new class inheriting this one, re-define the
 * following variables:
 * <code>
 *
 *    // root tag used in the API request
 *    protected $rootElement  = '<root>';
 *
 *    // alias processing the object data
 *    protected $URLPath = '<alias>';
 *
 *    // the fields array used in the response and request to the API server
 *    var $fields   = array(
 *     ...
 *    )
 * </code>
 *
 * To create a read-only class, close the save and delete methods.
 * To re-define the traditional API aliases to the non-traditional,
 * re-define the  {@link getURL},  {@link getURLADD}, {@link getURLEDIT},
 * {@link getURLLOAD},  {@link getURLDELETE} and  {@link getURLLIST}
 * methods in the class that will be inheriting the OnApp class.
 *
 *
 * This API provides an interface to onapp.net allowing common virtual machine
 * and account management tasks
 *
 * <b> Usage OnApp_VirtualMachine class example ( Could be applied almost to any of the Wrapper classes ): </b> <br /><br />
 * <b> Important ( OnApp CP Permissions Set Up): </b>
 * <code>
 *     Go to OnApp CP.
 *     Users and Groups -> Roles
 *     Push pencil edit icon to edit role of the user which you are going to use.
 *     Check checkbox { View OnApp version (settings.version) }
 *     Check other permissions in order to perform particular actions.
 *</code>
 *
 * <b>Code example:</b> <br />
 *
 * Require Wrapper AutoLoad Class:
 *
 * <code>
 *    require_once '{Path to the Wrapper}/OnAppInit.php';
 * </code>
 *
 *
 * Get OnApp Instance:
 *
 * <code>
 *     $onapp = new OnApp_Factory('{IP Address / Hostname}', '{Username}', '{Password}');
 * </code>
 *
 *
 * Get OnApp_VirtualMachine Instance:
 *
 * <code>
 *     $vm_obj = $onapp->factory('VirtualMachine', {debug mode boolean, not required} );
 * </code>
 *
 *
 * Get the list of VMs:
 *
 * <code>
 *     $vms    = $vm_obj->getList();
 * </code>
 *
 *
 * Get particular VM:
 *
 * <code>
 *     $vm     = $vm_obj->load({VM ID});
 * </code>
 *
 *
 * Create a VM:
 *
 * <code>
 *     $vm_obj->_label               = '{VM Label}';
 *     $vm_obj->_memory              = {VM RAM };
 *     $vm_obj->_cpu_shares          = {VM CPU priority};
 *     $vm_obj->_hostname            = '{Hostname}';
 *     $vm_obj->_cpus                = {number of VM CPUs};
 *     $vm_obj->_primary_disk_size   = {VM Disk Space};
 *     $vm_obj->_swap_disk_size      = {VM Swap Size};
 *     $vm_obj->_template_id         = {VM Template ID};
 *     $vm_obj->_allowed_hot_migrate = {VM Hot Migrate Boolean Value};
 *
 *     $vm_obj->save();
 * </code>
 *
 *
 * Edit VM:
 *
 * <code>
 *     $vm_obj->_id = {VM ID};
 *     $vm_obj->_{Field You Want To Edit} = {New Value};
 *
 *     $vm_obj->save();
 * </code>
 *
 *
 * Getting debug log ( depends on debug mode ):
 *
 * <code>
 *     print_r( $vm_obj );
 * </code>
 *
 * For full fields reference and curl request details visit: ( http://help.onapp.com/manual.php?m=2 )
 */
abstract class OnApp {
	/**
	 * The list of all available options used in the class to create API requests and receive responses,
	 * as well as to serialize and unserialize.
	 *
	 * @access private
	 * @var    array
	 */
	var $_knownOptions = array(
		ONAPP_OPTION_CURL_PROXY,
		ONAPP_OPTION_CURL_URL,
		ONAPP_OPTION_API_TYPE,
		ONAPP_OPTION_API_CHARSET,
		ONAPP_OPTION_API_CONTENT,
		ONAPP_OPTION_DEBUG_MODE
	);

	/**
	 * Default options used in the class to create API requests and receive responses,
	 * as well as serialize and unserialize objects.
	 *
	 * @access private
	 * @var    array
	 */
	private $defaultOptions = array(
		// cURL proxy
		ONAPP_OPTION_CURL_PROXY  => '',
		// cURL url
		ONAPP_OPTION_CURL_URL    => '',
		// API request and response charset
		ONAPP_OPTION_API_CHARSET => 'charset=utf-8',
		// API request and response type
		ONAPP_OPTION_API_TYPE    => 'json',
		// API request and response content
		ONAPP_OPTION_API_CONTENT => 'application/json',
		// Debug mode
		ONAPP_OPTION_DEBUG_MODE  => false,
	);

	/**
	 * The array of the options used to create API requests and receive responses,
	 * as well as serialize and unserialize objects in the class
	 *
	 * By default equals to $_defaultOptions
	 *
	 * <code>
	 *    var $options = array(
	 *
	 *        // cURL proxy
	 *        ONAPP_OPTION_CURL_PROXY     => '',
	 *
	 *        // cURL url
	 *        ONAPP_OPTION_CURL_URL       => '',
	 *
	 *        // API request and response type
	 *        ONAPP_OPTION_API_TYPE       => 'xml',
	 *
	 *        // API request and response charset
	 *        ONAPP_OPTION_API_CHARSET   => 'charset=utf-8',
	 *
	 *        // API request and response content
	 *        ONAPP_OPTION_API_CONTENT   => 'application/xml',
	 *
	 *          // Debug mode
	 *          ONAPP_OPTION_DEBUG_MODE => false
	 *    );
	 * </code>
	 *
	 * @access public
	 * @var    array
	 */
	var $options = array();

	/**
	 * Object cURL
	 *
	 * @var OnApp_Helper_Handler_CURL
	 */
	protected $ch;

	/**
	 * Variable storing the data loaded by the API request. The data is static and cannot be changed by the class setters
	 *
	 * @access private
	 * @var    object
	 */
	protected $inheritedObject;

	/**
	 * cURL Object alias used as the basic alias to the load, save, delete and getList methods
	 *
	 * @access private
	 * @var    string
	 */
	protected $URLPath = null;

	/**
	 * @access private
	 * @var    string
	 */
	protected $rootElement = null;

	/**
	 * @access private
	 * @var    array
	 */
	var $_tagRequired = null;

	protected $isAuthenticated = false;

	/**
	 * @access private
	 * @var    boolean
	 * @todo   move in to getter an setter
	 */
	var $_is_changed = false;

	/**
	 * @access private
	 * @var    boolean
	 * @todo   move in to getter an setter
	 */
	var $_is_deleted = false;

	/**
	 * Return OnApp version
	 *
	 * @access private
	 * @var    sting
	 */
	protected $version;

	/**
	 * Return OnApp release
	 *
	 * @access private
	 * @var       sting
	 */
	var $_release;

	/**
	 * Holder for storing properties that were setted via magic setter
	 *
	 * @var  array
	 * @access  protected
	 */
	protected $dynamicFields;

	/**
	 * The Object Logger used to log the processes in the basic and inherited classes
	 * It is possible to use the debug add error log methods
	 */
	public $logger = null;

	protected $response;

	/**
	 * Variable for error handling
	 *
	 * @var    string
	 */
	protected $errors;

	/**
	 * @var string current class' name
	 */
	protected $className;

	/**
	 * @var array fields which should be excluded from API request
	 */
	protected $skipFromRequest = array();

	protected $URLID;

	/**
	 * Returns API version
	 *
	 * @access private
	 * @return string  $version API version
	 */
	function _apiVersion() {
		return $this->version;
	}

	/**
	 * Returns Curl Response
	 *
	 * @access public
	 * @return array response
	 */
	function getResponse() {
		return $this->response;
	}

	/**
	 * Resets all options to default options
	 *
	 * @return void
	 * @access public
	 */
	function resetOptions() {
		$this->options = $this->defaultOptions;
	}

	/**
	 * Sets an option
	 *
	 * Use this method if you do not want
	 * to set all options in the constructor
	 *
	 * @param string $name  option name
	 * @param mixed  $value option value
	 *
	 * @return void
	 * @access public
	 */
	function setOption( $name, $value ) {
		$this->logger->logDebugMessage( 'setOption: Set option ' . $name . ' => ' . $value );
		$this->options[ $name ] = $value;
	}

	/**
	 * Sets several options at once
	 *
	 * Use this method if you do not want
	 * to set all options in the constructor
	 *
	 * @param array $options options array
	 *
	 * @return void
	 * @access public
	 */
	function setOptions( array $options ) {
		$this->options = array_merge( $this->options, $options );
	}

	/**
	 * Returns the URL Alias of the API Class that inherits the OnApp class
	 *
	 * Can be redefined if the API does not use the default alias (the alias
	 * consisting of few fields).
	 * The following example illustrates:
	 *
	 * <code>
	 *    function getURL() {
	 *        return "alias/" . $this->_field_name . "/" . $this->URLPath;
	 *    }
	 * </code>
	 *
	 * @param string $action
	 *
	 * @return string API resource
	 */
	protected function getURL( $action = ONAPP_GETRESOURCE_DEFAULT ) {
		switch( $action ) {
			case ONAPP_GETRESOURCE_LOAD:
			case ONAPP_GETRESOURCE_EDIT:
			case ONAPP_GETRESOURCE_DELETE:
				$URL = $this->getURL() . '/' . $this->id;
				break;

			case ONAPP_GETRESOURCE_LIST:
			case ONAPP_GETRESOURCE_ADD:
				$URL = $this->getURL();
				break;

			case ONAPP_GETRESOURCE_DEFAULT:
			default:
				$URL = $this->URLPath;
		}
		$this->logger->logDebugMessage( 'getURL( ' . $action . ' ): return ' . $URL );

		return $URL;
	}

	/**
	 * Returns true if the API instance has authentication information set and authentication was succesful
	 *
	 * @return boolean true if authenticated
	 * @access public
	 *
	 * @todo   move to the defaut getter
	 */
	public function isAuthenticate() {
		return $this->isAuthenticated;
	}

	/**
	 * Returns true if the Object was changed and API response was succesfull
	 *
	 * @return boolean true if the Object was changed
	 * @access public
	 *
	 * @todo   move to the defaut getter
	 */
	function isChanged() {
		return $this->_is_changed;
	}

	/**
	 * Returns true if the Object was deleted in the API instance
	 * and API response was succesfull
	 *
	 * @return boolean true if the Object was deleted
	 * @access public
	 *
	 * @todo   move to the defaut getter
	 */
	function isDelete() {
		return $this->_is_deleted;
	}

	/**
	 * Returns Text written to the full class logs
	 *
	 * When the log level is set to debug, the debug messages will be also
	 * included
	 *
	 * @return string All formatted logs
	 * @access public
	 */
	function logs() {
		if( isset( $this->logger ) ) {
			return $this->logger->getLog();
		}
	}

	/**
	 * Authorizes users in the system by the specified URL by means of cURL
	 *
	 * To authorize, set the user name and password. Specify the Proxy, if
	 * needed. When authorized, {@link load}, {@link save}, {@link delete} and
	 * {@link getList} methods can be used.
	 *
	 * @param string $url   API URL
	 * @param string $user  user name
	 * @param string $pass  password
	 * @param string $proxy (optional) proxy server
	 *
	 * @return void
	 * @access public
	 */
	function auth( $url, $user, $pass, $proxy = '' ) {
		$this->logger->setDebugMode( $this->options[ ONAPP_OPTION_DEBUG_MODE ] );

		$this->logger->setTimezone();

		$this->logger->logDebugMessage( 'auth: Authorization( url => ' . $url . ', user => ' . $user . ', pass => ******** ).' );

		$this->setOption( ONAPP_OPTION_CURL_URL, $url );
		$this->setOption( ONAPP_OPTION_CURL_PROXY, $proxy );

		$this->_init_curl( $user, $pass );

		$this->setAPIResource( ONAPP_GETRESOURCE_VERSION );

		$this->sendRequest( ONAPP_REQUEST_METHOD_GET );

		// todo check this code
		if( $this->ch->getResponseStatusCode() == '200' ) {
			$this->setAPIVersion( $this->ch->getResponseBody() );

			$this->setErrors();
			$this->isAuthenticated = true;
		}
		else {
			$this->setErrors( $this->ch->getResponseBody() );
			$this->isAuthenticated = false;
		}
	}

	protected function setAPIVersion( $data ) {
		switch( $this->options[ ONAPP_OPTION_API_TYPE ] ) {
			case 'xml':
			case 'json':
				$tag           = 'version';
				$this->version = null;

				$objCast       = new OnApp_Helper_Caster( $this );
				$this->version = $objCast->unserialize( $this->getClassName(), $data, $tag );
				break;

			default:
				//todo add CLI check
				$msg = 'FATAL ERROR: Caster for "' . $this->options[ ONAPP_OPTION_API_TYPE ] . '" is not defined'
					. ' in FILE ' . __FILE__ . ' LINE ' . __LINE__ . PHP_EOL . PHP_EOL;
				try {
					throw new Exception( $msg );
				}
				catch( Exception $e ) {
					echo $e->getMessage();
					exit( $this->logger->getLog() );
				}
		}
		$this->version = (float)$this->version;
	}

	// todo delete
	/*
	public $fields;
	public function initFields( $version = NULL, $className = '' ) {
		return array();

		if( ! is_null( $version ) ) {
			$this->version = $version;
		}

		if( is_null( $this->fields ) && ( $this->getClassName() != 'OnApp' ) ) {
			$this->logger->debug( 'No fields defined for current API version [ ' . $version . ' ]' );
			//			if( IS_CLI ) {
			//				throw new Exception( 'No fields defined for current API version [ ' . $version . ' ]' );
			//			}
			//			else {
			//				$this->logger->error( 'No fields defined for current API version [ ' . $version . ' ]' );
			//			}
		}
		elseif( ! is_null( $version ) ) {
			if( $version == $this->version ) {
				if( $this->defaultOptions[ ONAPP_OPTION_DEBUG_MODE ] ) {
					$this->logger->debug( $className . '::initFields, version ' . $version . PHP_EOL . print_r( $this->fields, TRUE ) );
				}
				else {
					$this->logger->add( $className . '::initFields, version ' . $version );
				}
			}
		}
	}
	*/

	protected function _init_curl( $user, $pass, $cookiesFile = null ) {
		$this->logger->logDebugMessage( '_init_curl: Init Curl ( cookies file => "' . $cookiesFile . '" ).' );

		$this->ch = new OnApp_Helper_Handler_CURL( $this );

		if( ! empty( $this->options[ ONAPP_OPTION_CURL_PROXY ] ) ) {
			$this->ch->setOption( CURLOPT_PROXY, $this->options[ ONAPP_OPTION_CURL_PROXY ] );
		}

		if( ! is_null( $cookiesFile ) ) {
			$this->ch->useCookies();
		}

		$this->ch->setOption( CURLOPT_USERPWD, $user . ':' . $pass );
		$this->ch->setOption( CURLOPT_HEADER, true );
	}

	/**
	 * Sets full API path to the variable cURL
	 *
	 * @param string  $resource    API alias
	 * @param boolean $append_api_version
	 * @param string  $queryString API request
	 *
	 * @return void
	 * @access public
	 */
	protected function setAPIResource( $resource, $append_api_version = true, $queryString = '' ) {
		$url = sprintf(
			'%1$s/%2$s.%3$s', $this->options[ ONAPP_OPTION_CURL_URL ], $resource, $this->options[ ONAPP_OPTION_API_TYPE ] );
		$this->ch->setOption( CURLOPT_URL, $url );
		$this->logger->logMessage(
			'setAPIResource: Set an option for a cURL transfer (' .
				' url => "' . $url . '", ' .
				' resource => "' . $resource . '", ' .
				' data_type => "' . $this->options[ ONAPP_OPTION_API_TYPE ] . '"' .
				' append_api_version => ' . $this->getAPIVersion() . ',' .
				' queryString => "' . $queryString . '" ).'
		);
	}

	/**
	 * Sends API request to the API server and gets response from it
	 *
	 * @param string     $method
	 * @param array|null $data
	 *
	 * @return array|bool cURL response
	 */
	protected function sendRequest( $method, $data = null ) {
		$alowed_methods = array(
			ONAPP_REQUEST_METHOD_GET,
			ONAPP_REQUEST_METHOD_POST,
			ONAPP_REQUEST_METHOD_PUT,
			ONAPP_REQUEST_METHOD_DELETE,
		);
		if( ! in_array( $method, $alowed_methods ) ) {
			$this->logger->logErrorMessage( 'Wrong request method: ' . $method );
		}

		$debug_msg = 'Send ' . $method . ' request.';
		if( $data ) {
			$debug_msg .= ' Request:' . PHP_EOL . print_r( $data, true );
		}
		$this->logger->logDebugMessage( $debug_msg );

		$this->ch->setHeader( 'Content-Length', strlen( $data ) );
		$this->ch->setHeader( 'Accept', $this->options[ ONAPP_OPTION_API_CONTENT ] );
		$this->ch->setHeader( 'Content-Type', $this->options[ ONAPP_OPTION_API_CONTENT ] );

		if( ! is_null( $data ) ) {
			$this->ch->setOption( CURLOPT_POSTFIELDS, $data );
		}

		$method = strtolower( $method );
		$body   = $this->ch->$method()->getResponseBody();
		if( $this->ch->getRequestError() ) {
			$this->logger->logErrorMessage( 'Error during sending request' );
		}
		elseif( empty( $body ) && ( $this->ch->getResponseStatusCode() != 204 ) ) {
			$this->logger->logErrorMessage( 'Response body couldn\'t be empty for status code: ' . $this->ch->getResponseStatusCode() );
		}

		$this->logger->logDebugMessage( 'Receive Response ' . print_r( $this->ch->getResponseBody(), true ) );

		if( $this->ch->getRequestInfo( 'content_type' ) == $this->options[ ONAPP_OPTION_API_CONTENT ] . "; " . $this->options[ ONAPP_OPTION_API_CHARSET ] ) {
			switch( $this->ch->getResponseStatusCode() ) {
				case 200:
				case 201:
				case 204:
					break;

				case 422:
				case 404:
					switch( $this->options[ ONAPP_OPTION_API_TYPE ] ) {
						case 'xml':
						case 'json':
							$this->logger->logMessage( 'Response (code => ' . $this->ch->getResponseStatusCode() . ', cast:' . PHP_EOL . "\t" . $this->ch->getResponseBody() );
							break;
					}
					break;

				default:
					$this->logger->logWarningMessage( 'Response (code => ' . $this->ch->getResponseStatusCode() . ', body: ' . PHP_EOL . $this->ch->getResponseBody() );
			}
		}
		else {
			$this->logger->logMessage( 'sendRequest: Response:' . PHP_EOL . $this->ch->getResponseBody() );
		}
	}

	/**
	 * Casts an API response to the Array of Objects or an Object
	 *
	 * @return mixed (Array of Object or Object)
	 */
	protected function castResponseToClass() {
		if( $this->ch->getRequestError() ) {
			$this->logger->logErrorMessage(
				'castResponseToClass: Can\'t parse ' . $this->ch->getResponseBody(),
				__FILE__,
				__LINE__
			);
		}
		else {
			switch( $this->ch->getResponseStatusCode() ) {
				case 200:
				case 201:
				case 404:
				case 422:
					return $this->doCastResponseToClass();
					break;

				case 204:
					break;

				case 500:
					$this->setErrors( 'We\'re sorry, but something went wrong.' );
					return $this;

				default:
					$this->setErrors( 'Bad response ( code => ' . $this->ch->getResponseStatusCode() . ', response => ' . $this->ch->getResponseBody() . ' )' );
			}
		}
	}

	/**
	 * Casts string (API response body content) to the Object
	 *
	 * @return mixed array of Objects or Object
	 */
	protected function doCastResponseToClass() {
		$className = $this->getClassName();

		$this->logger->logMessage( 'doCastResponseToClass: cast data into the ' . $className . ' object.' );

		//todo add errors
		switch( $this->options[ ONAPP_OPTION_API_TYPE ] ) {
			case 'xml':
			case 'json':
				$root = $this->rootElement;
				$data = $this->ch->getResponseBody();

				$objCast = new OnApp_Helper_Caster( $this );

				if( $this->ch->getResponseStatusCode() > 201 ) {
					$root   = 'errors';
					$errors = $objCast->unserialize( $className, $data, $root );
					$this->setErrors( $errors );

					return $this;
				}
				else {
					return $objCast->unserialize( $className, $data, $root );
				}

			default:
				//todo check
				$msg = 'FATAL ERROR: Caster for "' . $this->options[ ONAPP_OPTION_API_TYPE ] . '" is not defined'
					. ' in FILE ' . __FILE__ . ' LINE ' . __LINE__ . PHP_EOL . PHP_EOL;
				try {
					throw new Exception( $msg );
				}
				catch( Exception $e ) {
					echo $e->getMessage();
					//todo ???
					exit( $this->logger->getLog() );
				}
		}
	}

	/**
	 * Activates action performed with object
	 *
	 * @param string $action_name the name of action
	 *
	 * @access public
	 */
	function activate( $action_name ) {
	}

	/**
	 * Sends an API request to get the Objects. After requesting,
	 * unserializes the received response into the array of Objects
	 *
	 * @param mixed $params
	 * @param mixed $url_args
	 *
	 * @return array|bool|null
	 */
	public function getList( $params = null, $url_args = null ) {
		$this->activate( ONAPP_ACTIVATE_GETLIST );

		$this->logger->logMessage( 'Run ' . __METHOD__ );

		$result = $this->sendGet( ONAPP_GETRESOURCE_LIST, $params, $url_args );

		if( ! is_null( $this->getErrorsAsArray() ) ) {
			return false;
		}
		else {
			if( ! is_array( $result ) && ! is_null( $result ) ) {
				$result = array( $result );
			}
			return $result;
		}
	}

	/**
	 * Sends an API request to get the Object after sending,
	 * unserializes the response into an object
	 *
	 * The key field Parameter ID is used to load the Object. You can re-set
	 * this parameter in the class inheriting OnApp class.
	 *
	 * @param integer $id Object id
	 *
	 * @return mixed serialized Object instance from API
	 * @access public
	 */
	function load( $id = null ) {
		$this->activate( ONAPP_ACTIVATE_LOAD );

		if( is_null( $id ) && ! is_null( $this->_id ) ) {
			$id = $this->_id;
		}

		if( is_null( $id ) &&
			isset( $this->inheritedObject ) &&
			! is_null( $this->inheritedObject->_id )
		) {
			$id = $this->inheritedObject->_id;
		}

		if( is_null( $id ) ) {
			$this->logger->logErrorMessage( 'load: Can\'t load ID = ' . $id, __FILE__, __LINE__ );
		}

		$this->logger->logMessage( 'load: Load class ( id => ' . $id . ' ).' );

		if( strlen( $id ) > 0 ) {
			$this->_id = $id;

			$this->setAPIResource( $this->getURL( ONAPP_GETRESOURCE_LOAD ) );
			$response = $this->sendRequest( ONAPP_REQUEST_METHOD_GET );
			$result   = $this->doCastResponseToClass( $response );

			$this->inheritedObject = $result;
			$this->_id             = $this->inheritedObject->_id;

			return $result;
		}
		else {
			$this->logger->logErrorMessage( 'load: property id not set.', __FILE__, __LINE__ );
		}
	}

	/**
	 * The method saves an Object to your account
	 *
	 * After sending an API request to create an object or change the data in
	 * the existing object, the method checks the response and loads the
	 * exisitng object with the new data.
	 *
	 * This method can be closed for read only objects of the inherited class
	 * <code>
	 *    function save() {
	 *        $this->logger->error(
	 *            "Call to undefined method ".__CLASS__."::save()",
	 *            __FILE__,
	 *            __LINE__
	 *        );
	 *    }
	 * </code>
	 *
	 * @return void
	 * @access public
	 */
	function save() {
		$this->activate( ONAPP_ACTIVATE_SAVE );

		if( is_null( $this->_id ) ) {
			$obj = $this->_create();
		}
		else {
			$obj = $this->_edit();
		}

		//todo handle errors
		if( isset( $obj ) && ! isset( $obj->errors ) ) {
			$this->load();
		}
	}

	/**
	 * The method creates a new Object
	 *
	 * @return object Serialized API Response
	 * @access private
	 */
	protected function _create() {
		$this->logger->logMessage( 'Create new Object.' );

		switch( $this->options[ ONAPP_OPTION_API_TYPE ] ) {
			case 'xml':
			case 'json':
				$objCast = new OnApp_Helper_Caster( $this );
				$data    = $objCast->serialize( $this->rootElement, $this->getFieldsToSend() );
				$this->logger->logDebugMessage( 'serialize: serialized data:' . PHP_EOL . $data );
				$this->setAPIResource( $this->getURL( ONAPP_GETRESOURCE_ADD ) );
				$response = $this->sendRequest( ONAPP_REQUEST_METHOD_POST, $data );

				$result                = $this->castResponseToClass( $response );
				$this->inheritedObject = $result;
				break;

			default:
				$msg = 'FATAL ERROR: Caster for "' . $this->options[ ONAPP_OPTION_API_TYPE ] . '" is not defined'
					. ' in FILE ' . __FILE__ . ' LINE ' . __LINE__ . PHP_EOL . PHP_EOL;
				try {
					throw new Exception( $msg );
				}
				catch( Exception $e ) {
					echo $e->getMessage();
					//todo ???
					exit( $this->logger->getLog() );
				}
		}
		return $result;
	}

	/**
	 * The method edits an existing Object
	 *
	 * @return object Serialized API Response
	 */
	protected function _edit() {
		switch( $this->options[ ONAPP_OPTION_API_TYPE ] ) {
			case 'xml':
			case 'json':
				$objCast = new OnApp_Helper_Caster( $this );
				$data    = $objCast->serialize( $this->rootElement, $this->getFieldsToSend() );
				$this->logger->logDebugMessage( 'serialize: serialized data:' . PHP_EOL . $data );
				$this->setAPIResource( $this->getURL( ONAPP_GETRESOURCE_EDIT ) );
				$response = $this->sendRequest( ONAPP_REQUEST_METHOD_PUT, $data );

				if( $response[ 'info' ][ 'http_code' ] > 201 ) {
					$this->castResponseToClass( $response );
				}
				else {
					$this->load( $this->_id );
				}
				break;

			default:
				$msg = 'FATAL ERROR: Caster for "' . $this->options[ ONAPP_OPTION_API_TYPE ] . '" is not defined'
					. ' in FILE ' . __FILE__ . ' LINE ' . __LINE__ . PHP_EOL . PHP_EOL;
				try {
					throw new Exception( $msg );
				}
				catch( Exception $e ) {
					echo $e->getMessage();
					//todo ???
					exit( $this->logger->getLog() );
				}
		}
	}

	/**
	 * Creates data fro API response to save or change the object data
	 *
	 * Returns the Hash of Object fields with values
	 *
	 * @return hash of string
	 * @access private
	 */
	protected function getFieldsToSend() {
		$this->logger->logDebugMessage( 'getFieldsToSend: Prepare data array:' );
		if( empty( $this->inheritedObject->dynamicFields ) ) {
			$result = $this->dynamicFields;
		}
		else {
			$result = array_merge( $this->inheritedObject->dynamicFields, $this->dynamicFields );
		}

		if( ! empty( $this->skipFromRequest ) ) {
			for( $i = 0, $size = count( $this->skipFromRequest ); $i < $size; ++$i ) {
				$this->skipFromRequest[ $this->skipFromRequest[ $i ] ] = '';
				unset( $this->skipFromRequest[ $i ] );
			}
			$result = array_diff_key( $result, $this->skipFromRequest );
		}

		return $result;
	}

	/**
	 * Sends an API request to delete an Object from your account
	 *
	 * This method can be closed for read only objects of the inherited class
	 * <code>
	 *    function delete() {
	 *        $this->logger->error(
	 *            "Call to undefined method ".__CLASS__."::delete()",
	 *            __FILE__,
	 *            __LINE__
	 *        );
	 *    }
	 * </code>
	 *
	 * @return boolean the Object deleted
	 * @access public
	 */
	function delete() {
		$this->activate( ONAPP_ACTIVATE_DELETE );

		$this->logger->logMessage( 'Delete existing Object ( id => ' . $this->_id . ' ).' );

		$this->sendDelete( ONAPP_GETRESOURCE_DELETE );

		if( count( $this->getErrorsAsArray() ) < 1 ) {
			$this->_is_deleted = true;
		}
	}

	function sendPost( $resource, $data = null ) {
		return $this->_action( ONAPP_REQUEST_METHOD_POST, $resource, $data );
	}

	protected function sendGet( $resource, $data = null, $url_args = null ) {
		return $this->_action( ONAPP_REQUEST_METHOD_GET, $resource, $data, $url_args );
	}

	function sendPut( $resource, $data = null ) {
		return $this->_action( ONAPP_REQUEST_METHOD_PUT, $resource, $data );
	}

	function sendDelete( $resource, $data = null ) {
		return $this->_action( ONAPP_REQUEST_METHOD_DELETE, $resource, $data );
	}

	/**
	 * Sends API Requests to realize not base actions
	 *
	 * @param string     $method
	 * @param string     $resource
	 * @param array|null $data
	 *
	 * @return bool|mixed (Array of Object or Object)
	 */
	protected function _action( $method, $resource, $data = null, $url_args = null ) {
		switch( $this->options[ ONAPP_OPTION_API_TYPE ] ) {
			case 'xml':
			case 'json':
				if( ! is_null( $data ) ) {
					$objCast = new OnApp_Helper_Caster( $this );
					$data    = $objCast->serialize( $data[ 'root' ], $data[ 'data' ] );
					$this->logger->logDebugMessage( 'Additional parameters: ' . $data );
				}

				$url_args = ( $url_args ) ? preg_replace( '/%5B(0-9){1,4}%5D/', '%5B%5D', http_build_query( $url_args ) ) : '';

				$this->setAPIResource( $this->getURL( $resource ), true, $url_args );
				$this->sendRequest( $method, $data );

				$this->sendRequest( $method, $data );
				$result = $this->castResponseToClass( $this->ch->getResponseBody() );

				if( $this->ch->getResponseStatusCode() > 400 ) {
					if( is_null( $result ) ) {
						$this->inheritedObject = clone $this;
					}
					else {
						$this->inheritedObject->errors = $result->getErrorsAsArray();
					}
					return false;
				}
				else {
					$this->inheritedObject = $result;
				}
				break;

			default:
				$this->logger->logErrorMessage( '_action: Can\'t find serialize and unserialize functions for type (apiVersion => \'' . $this->getAPIVersion() . "').", __FILE__, __LINE__ );
		}

		return $result;
	}

	public function getClassName() {
		if( is_null( $this->className ) ) {
			return __CLASS__;
		}
		else {
			return $this->className;
		}
	}

	//todo check this code
	public function getAPIVersion() {
		return $this->version;
	}

	/**
	 * Store errors
	 *
	 * @param mixed $errors
	 *
	 * @return void
	 */
	public function setErrors( $errors = null ) {
		if( is_null( $errors ) ) {
			$this->errors = null;
		}
		else {
			$this->errors = (array)$errors;
		}
	}

	/**
	 * Return errors as string
	 *
	 * @param string $glue
	 *
	 * @return string
	 */
	public function getErrorsAsString( $glue = PHP_EOL ) {
		if( is_null( $this->getErrorsAsArray() ) ) {
			return '';
		}

		$errors = '';
		foreach( $this->getErrorsAsArray() as $Key => $Value ) {
			if( is_array( $Value ) ) {
				foreach( $Value as $key => $value ) {
					if( ! is_array( $value ) ) {
						$value = (array)$value;
					}
					$errors .= $glue . $Key . ': ' . implode( $glue . $Key . ': ', $value );
				}
			}
			else {
				$errors .= $Value . $glue;
			}
		}

		if( strpos( $errors, $glue ) == 0 ) {
			$errors = substr( $errors, strlen( $glue ) );
		}

		return $errors;
	}

	/**
	 * Return errors as array
	 *
	 * @return array
	 */
	public function getErrorsAsArray() {
		return $this->errors;
	}

	//todo check this code
	/**
	 * Unset unnecessary fields
	 *
	 * @param array $fields fields name
	 *
	 * @return void
	 */
	/*
	protected function unsetFields( $fields ) {
		foreach( $fields as $field ) {
			unset( $this->fields[ $field ] );
		}
	}
	*/

	// getters, setters & other magic stuff //
	public function __construct() {
		$this->className = get_class( $this );
		$this->options   = $this->defaultOptions;
		$this->logger    = new OnApp_Helper_Handler_Log;
	}

	/**
	 * Standard method to handle getting non-existent class' property
	 *
	 * @param string $name property name
	 *
	 * @return mixed
	 */
	public function __get( $name ) {
		// legacy getting errors
		// remove after rewriting the code to use getErrors() method
		// instead of direct access
		switch( $name ) {
			case 'error':
				return $this->getErrorsAsArray();

			case '_version':
				return $this->getAPIVersion();

			case '_obj':
				var_dump( '_obj [ ' . __METHOD__ . ' ]' );
				return $this->inheritedObject;
		}

		if( ! isset( $this->dynamicFields[ $name ] ) ) {
			if( strpos( $name, '_' ) === 0 ) {
				$name = substr( $name, 1 );
				if( ! isset( $this->dynamicFields[ $name ] ) ) {
					return null;
				}
			}
			else {
				return null;
			}
		}

		if( is_object( $this->dynamicFields[ $name ] ) ) {
			if( $this->dynamicFields[ $name ] instanceof OnAppNestedDataHolder ) {
				$this->dynamicFields[ $name ] = OnApp_Helper_Caster::unserializeNested( $this->dynamicFields[ $name ] );
			}
		}
		return $this->dynamicFields[ $name ];
	}

	/**
	 * Standard method to handle writing into non-existent class' property
	 *
	 * @param string $name  property name
	 * @param string $value property value
	 *
	 * @return void
	 */
	public function __set( $name, $value ) {
		if( strpos( $name, '_' ) === 0 ) {
			$name = substr( $name, 1 );
		}

		$this->dynamicFields[ $name ] = $value;
	}

	/**
	 * Standard method to check if property was set via setter
	 *
	 * @param string $name  property name
	 *
	 * @return bool         result of checking property
	 */
	public function __isset( $name ) {
		// legacy getting errors
		// remove after rewriting the code to use getErrors() method
		// instead of direct access
		switch( $name ) {
			case 'error':
				return isset( $this->errors );
				break;

			case '_version':
				return isset( $this->version );
				break;
		}

		return isset( $this->dynamicFields[ $name ] );
	}

	/**
	 * Standard method to unset property which was set via setter
	 *
	 * @param string $name property name
	 *
	 * @return void
	 */
	public function __unset( $name ) {
		if( ! isset( $this->dynamicFields[ $name ] ) ) {
			if( strpos( $name, '_' ) === 0 ) {
				$name = substr( $name, 1 );
				if( ! isset( $this->dynamicFields[ $name ] ) ) {
					return null;
				}
			}
			else {
				return null;
			}
		}

		unset( $this->dynamicFields[ $name ] );
	}
}