<?php
/**
 * Description of ApiAuthenticate
 *
 * @author DenGalePirat
 * @copyright Casper Paulsen 2012 - <dengalepirat@gmail.dk>
 */

App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class ApiAuthenticate extends BaseAuthenticate {
	
	/**
 * Settings for this object.
 *
 * - `fields` The fields to use to identify a user by.
 * - `userModel` The model name of the User, defaults to User.
 * - `scope` Additional conditions to use when looking up and authenticating users,
 *    i.e. `array('User.is_active' => 1).`
 * - `recursive` The value of the recursive key passed to find(). Defaults to 0.
 * - `contain` Extra models to contain and store in session.
 * - `realm` The realm authentication is for.  Defaults the server name.
 *
 * @var array
 */
	public $settings = array(
		'fields' => array(
			'username' => 'id',
		),
		'userModel' => 'AccessToken',
		'scope' => array(
			'AccessToken.active' => 1,
		),
		'recursive' => -1,
		'contain' => null,
		'realm' => '',
	);

/**
 * Constructor, completes configuration for basic authentication.
 *
 * @param ComponentCollection $collection The Component collection used on this request.
 * @param array $settings An array of settings.
 */
	public function __construct(ComponentCollection $collection, $settings) {
		parent::__construct($collection, $settings);
		if (empty($this->settings['realm'])) {
			$this->settings['realm'] = env('SERVER_NAME');
		}
	}

/**
 * Authenticate a user using basic HTTP auth.  Will use the configured User model and attempt a
 * login using basic HTTP auth.
 *
 * @param CakeRequest $request The request to authenticate with.
 * @param CakeResponse $response The response to add headers to.
 * @return mixed Either false on failure, or an array of user data on success.
 */
	public function authenticate(CakeRequest $request, CakeResponse $response) {
		$result = $this->getUser($request);

		if (empty($result)) {
			$response->header($this->loginHeaders());
			$response->statusCode(401);
			$response->send();
			return false;
		}
		return $result;
	}

/**
 * Get a user based on information in the request.  Used by cookie-less auth for stateless clients.
 *
 * @param CakeRequest $request Request object.
 * @return mixed Either false or an array of user information
 */
	public function getUser($request) {
		$username = env('PHP_AUTH_USER');
		$pass = env('PHP_AUTH_PW');

		if (empty($username) || empty($pass)) {
			return false;
		}
		return $this->_findUser($username, $pass);
	}

/**
 * Generate the login headers
 *
 * @return string Headers for logging in.
 */
	public function loginHeaders() {
		return sprintf('WWW-Authenticate: Basic realm="%s"', $this->settings['realm']);
	}
	
	protected function _findUser($username, $password) {
		$userModel = $this->settings['userModel'];
		list($plugin, $model) = pluginSplit($userModel);
		$fields = $this->settings['fields'];

		$conditions = array(
			$model . '.' . $fields['username'] => $username,
		);
		if (!empty($this->settings['scope'])) {
			// A little workaround since it is not possible to call the date() in $settings
			$this->settings['scope'] = array_merge($this->settings['scope'], array('AccessToken.expiration_date >=' => date('Y-m-d H:i:s')));
			$conditions = array_merge($conditions, $this->settings['scope']);
		}
		$result = ClassRegistry::init($userModel)->find('first', array(
			'conditions' => $conditions,
			'recursive' => (int)$this->settings['recursive'],
			'contain' => $this->settings['contain'],
		));
		if (empty($result) || empty($result[$model])) {
			return false;
		}
		$user = $result[$model];
		unset($user[$fields['password']]);
		unset($result[$model]);
		return array_merge($user, $result);
	}
	
	public function currentDate() {
		return date('Y-m-d H:i:s', time());
		
	}
}

?>
