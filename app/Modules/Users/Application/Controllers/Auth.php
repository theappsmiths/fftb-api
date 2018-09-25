<?php

namespace App\Modules\Users\Application\Controllers;

use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Zend\Diactoros\Response as Psr7Response;
use Psr\Http\Message\ServerRequestInterface;
use Dusterio\LumenPassport\LumenPassport;
use Laravel\Passport\Http\Controllers\AccessTokenController;

use App\Transformers\ResponseTransformer;

use App\Modules\Users\Domain\Manager;

use Illuminate\Http\Request;

use App\Validations\Users\Login;
use App\Validations\Users\Device;
use App\Validations\Users\UserName;

class Auth extends AccessTokenController {

    /**
     * Method to Login user
     * 
     * @api users/login
     * @method  POST
     * 
     * @table:  tbl_users
     * 
     * @success-format: {"status":"success","title":"Authorization","message":"User successfully login.","data":{"token_type":"Bearer","expires_in":31535999,"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjI3ZmVlOTQ4NmE5YzkwZjk0MWNiYmE3ODI4MTJlMzkzOTc1NWI1MzU0OTVlODAyNmY0MTBlMGIxNzA2MzY5M2RjYWMxZjM2NDFmMWRhZWJkIn0.eyJhdWQiOiIyIiwianRpIjoiMjdmZWU5NDg2YTljOTBmOTQxY2JiYTc4MjgxMmUzOTM5NzU1YjUzNTQ5NWU4MDI2ZjQxMGUwYjE3MDYzNjkzZGNhYzFmMzY0MWYxZGFlYmQiLCJpYXQiOjE1Mzc0MzU3ODQsIm5iZiI6MTUzNzQzNTc4NCwiZXhwIjoxNTY4OTcxNzgzLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.LMfaZ5yui80W5cPZBhoo8MB3LonpMNWz6wIeNPw9jWoyFp_MrAuTp8E7xVr3mGIxtXbAq2DZR7jFl1z3ojlFUlb4gqEV--JsgTz1lQ2TXDaOzqcZeRks7WS5hwCvxiGiaMqpN3uOppUWbEhbTNOoF01TjtI3qLdtOIL4Mtpn7Ng2fiyVWRIITwxpyVR_i9gRjicGfKENm8Ui7CLDJeynzFbRJWV6Brzo2L13004mPyiZUN7lcO9mJdHFRuMrlLgBjB72ZWnHsDOiA4WJDvwQMMlhWitSqJN5DNN-CuwLKwy0s8HwapA9nLL8z6HQMInAZwxzdGfK8OlJsAkqUYj_yRSPDjFrUZag2puf9qRMoXZcKyP4dSAeWFIatW0X20Rt5YiSB-s2C9cPHZTDB9PcDpPcvnd8ixn_9MlK5GbIrmJ9DYjuOS_ZRpFaDXrRn1QhdJvgbhFKGPr6MvMLWrtZ6wQJniM1m7gEyB1I8zZSY24ubutb5G3euCEXt1KK0mT-2I0iUqe5mAbrz4vjNoOlc1B6G-7QVnXimktTv3WIS3bSv51gsxItISHi4Iz0glBq-OCJSqzhvXv8L2A6eoR5mjSy1HWhsGpUKboqfquKbVjjbRqfYUUtzA0ojKyzIgCMIsBDL3s0PfIvBCUY6YIyqJOD1kljBoufYx7P24u0CI4","refresh_token":"def502006ef8362e676b421d5a15381931a1783bda2d393e37767bb37dd89ceee4fe1d22fa5ff74d5106391b06898a3c9aec62dd9c4c1e89fcf36f6ea29de7e8ef4a1e5ae6108ca6e509ff6b0a078c3b9a44e51f58f1514f251e30bda75b75366c98fc42295fdb2cd1c80d19d97e1e52b7a6404bdeaf76f783992038cd348dc09ace8dcbcf28ef9b07a226a6e49173335b542308d1821b6c4cff1910b34d3a204915d012bc737c84e0368c50a6dc73205d7a8e2a90fa55480b9bbc7e6bebd27d0e416612daada703da1845d91eeb19c61334fe00b1a566ea849a45c65b929e4fd5759c665e22e6237b527626efe3f7e7c694986f7d5fcc3d99d993927a7e6ebf9199ab6b2b3ef76aebf43891ca6446153e65f44b047ea3fc8befc1634f3d8672b9dc6aa203011e416ff2a709aba622d06cfa18a5e1668e12e1a15de671487e015976a06bc44851bffc49b22217f27c8451b796d7a6c68026d7a784bb7f46794e84"}}
     * 
     * @access any
     * 
     * @return ResponseTransformer
     */
    public function login (
        Login $validator, 
        Device $validator1, 
        UserName $validator2,
        ServerRequestInterface $request
    ) {
        // check for any error if occur
        if ($validation = $this->validateLoginParams ($validator, $validator1, $validator2, $request)) {
            return ResponseTransformer::response (false, 'Authorization', 'parameter errors', $validation->messages()->toArray(), 422);
        }

        $response = $this->validateOAuthFields ($request);

        if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
            return ResponseTransformer::response (
                false, 
                'Authorization',
                json_decode ($response->getContent())->error, 
                array_filter ([
                    json_decode ($response->getContent())->message,
                    json_decode ($response->getContent())->hint ?? null,
                ]), 
                $response->getStatusCode()
            );
        }

        $payload = json_decode($response->getBody()->__toString(), true);

        if (isset($payload['access_token'])) {
            $tokenId = $this->jwt->parse($payload['access_token'])->getClaim('jti');
            $token = $this->tokens->find($tokenId);

            // check for device detail in header
            if (count ($this->fetchDeviceParamFromHeader ($request))) {
                // save user device detail
                (new Manager)->saveUserDeviceDetail ($token->user()->first(), $this->fetchDeviceParamFromHeader ($request));
            }
            
            // logout user other than current login
            $this->revokeOrDeleteAccessTokens($token, $tokenId);
        }

        return ResponseTransformer::response (($response->getStatusCode() === 200) ? true : false, 'Authorization', 'User successfully login.', $payload);
    }

    /**
     * Method to validate Login request
     */
    private function validateLoginParams (
        Login $validator, 
        Device $validator1, 
        UserName $validator2, 
        ServerRequestInterface $request
    ) {
        // fetch Header values
        $data = array_merge ($request->getParsedBody(), $this->fetchDeviceParamFromHeader ($request));

        // merge Validateors
        $validator->rules = array_merge ($validator->rules, $validator1->rules, $validator1->rules);

        return $validator->validate ($data);
    }

    /**
     * Method to find header device attribute
     */
    private function fetchDeviceParamFromHeader (ServerRequestInterface $request) {
        if (current ($request->getHeader('deviceToken')) && current ($request->getHeader('deviceType')) && current ($request->getHeader('fcmToken'))) {
            // fetch device required field from header
            return [
                'deviceToken' => current ($request->getHeader('deviceToken')),
                'deviceType' => current ($request->getHeader('deviceType')),
                'fcmToken' => current ($request->getHeader('fcmToken')),
            ];
        }

        return [];
    }

    /**
     * Authorize a client to access the user's account.
     *
     * @param  ServerRequestInterface  $request
     * @return Response
     */
    protected function validateOAuthFields (ServerRequestInterface $request) {
        return $this->withErrorHandling(function () use ($request) {
            $input = (array) $request->getParsedBody();
            $clientId = isset($input['client_id']) ? $input['client_id'] : null;

            // Overwrite password grant at the last minute to add support for customized TTLs
            $this->server->enableGrantType(
                $this->makePasswordGrant(), LumenPassport::tokensExpireIn(null, $clientId)
            );

            return $this->server->respondToAccessTokenRequest($request, new Psr7Response);
        });
    }

    /**
     * Create and configure a Password grant instance.
     *
     * @return \League\OAuth2\Server\Grant\PasswordGrant
     */
    private function makePasswordGrant()
    {
        $grant = new \League\OAuth2\Server\Grant\PasswordGrant(
            app()->make(\Laravel\Passport\Bridge\UserRepository::class),
            app()->make(\Laravel\Passport\Bridge\RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }

    /**
     * Revoke the user's other access tokens for the client.
     *
     * @param  Token $token
     * @param  string $tokenId
     * @return void
     */
    protected function revokeOrDeleteAccessTokens(Token $token, $tokenId)
    {
        $query = Token::where('user_id', $token->user_id)->where('client_id', $token->client_id);

        if ($tokenId) {
            $query->where('id', '<>', $tokenId);
        }

        if (Passport::$pruneRevokedTokens) {
            return $query->delete();
        } else {
            return $query->update(['revoked' => true]);
        }
    }

    /**
     * Method to logout User
     * 
     * @api users/logout    [POST]
     * 
     * @access any
     * 
     * @param ServerRequestInterface request
     * 
     * @return Response HTTP
     */
    public function logout (Request $request) {
        $tokenId = $this->jwt->parse($request->bearerToken())->getClaim('jti');
        $token = $this->tokens->find($tokenId);

        $token->revoke();
        return ResponseTransformer::response (true, 'Authorization', 'User successfully logout.');
    }
}