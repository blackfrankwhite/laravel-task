<?php 

namespace App\Custom;

use App\Models\UserToken;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Str;

class AccessTokenProvider implements UserProvider
{
	private $userToken;
	private $user;

	public function __construct (User $user, UserToken $userToken) {
		$this->user = $user;
		$this->userToken = $userToken;
	}

	public function retrieveById ($identifier) {
		return $this->user->find($identifier);
	}

	public function retrieveByToken ($identifier, $userToken) {
		$userToken = $this->userToken->with('user')->where($identifier, $userToken)->first();

		return $userToken && $userToken->user ? $userToken->user : null;
	}

	public function updateRememberToken (Authenticatable $user, $userToken) {
		// update via remember token not necessary
	}

	public function retrieveByCredentials (array $credentials) {
		$user = $this->user;
		foreach ($credentials as $credentialKey => $credentialValue) {
			if (!Str::contains($credentialKey, 'password')) {
				$user->where($credentialKey, $credentialValue);
			}
		}

		return $user->first();
	}

	public function validateCredentials (Authenticatable $user, array $credentials) {
		$plain = $credentials['password'];

		return app('hash')->check($plain, $user->getAuthPassword());
	}
}