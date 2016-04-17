<?php
namespace Auth\Formatter;

use Account\Entity\Account;
use Profile\Entity\Profile;

class SignInFormatter
{
    public function format(Account $account)
    {
        $profiles = array_map(function(Profile $profile) {
            return $profile->toJSON();
        }, $account->getProfiles()->toArray());

        return [
            "api_key" => $account->getAPIKey(),
            "account" => $account->toJSON(),
            "profiles" => $profiles,
        ];
    }
}