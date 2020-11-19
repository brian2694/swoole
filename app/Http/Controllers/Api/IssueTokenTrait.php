<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use phpDocumentor\Reflection\Types\String_;

trait IssueTokenTrait
{
    /**
     * Se encarga de expedir un auth token si las credenciales son válidas
     * @param Request $request 
     * @param String $device_uuid \Modules\Auth\Models\Device 
     * @param String_ $grantType Tipo de concesión [password, social]
     * #method #trait #auth #IssueTokenTrait #issueToken
     */
    public function issueToken(Request $request, $grantType, $scope = "")
    {
        $params = [
            'grant_type' => $grantType,
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'scope' => $scope
        ];
        if ($grantType !== 'social') {
            // $params['username'] = $request->username ?: $request->email;
            $params['username'] = $request->email;
        }
        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');
        $tokenResponse = Route::dispatch($proxy);
        $content = $tokenResponse->getContent();
        $data = json_decode($content, true);

        if (isset($data["error"])) {
            throw new OAuthServerException(__("auth::errors.invalid_credentials"), 6, 'invalid_credentials', 401);
        }

        return $data;
    }
}
