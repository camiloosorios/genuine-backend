<?php

namespace App\Http\Controllers;

use Google\Cloud\Dialogflow\V2\Client\SessionsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Illuminate\Http\Request;

class DialogflowController extends Controller
{
    public function detectIntent(Request $request)
    {
        $projectId = env('DIALOGFLOW_PROJECT_ID');
        $sessionId = uniqid();
        $languageCode = 'es';

        $text = $request->input('text');

        $credentials = [
            "type" => "service_account",
            "project_id" => env('DIALOGFLOW_PROJECT_ID'),
            "private_key_id" => env('DIALOGFLOW_PRIVATE_KEY_ID'),
            "private_key" => env('DIALOGFLOW_PRIVATE_KEY'),
            "client_email" => env('DIALOGFLOW_CLIENT_EMAIL'),
            "client_id" => env('DIALOGFLOW_CLIENT_ID'),
            "auth_uri" => env('DIALOGFLOW_AUTH_URI'),
            "token_uri" => env('DIALOGFLOW_TOKEN_URI'),
            "auth_provider_x509_cert_url" => env('DIALOGFLOW_AUTH_PROVIDER_CERT_URL'),
            "client_x509_cert_url" => env('DIALOGFLOW_CLIENT_CERT_URL'),
            "universe_domain" => env('DIALOGFLOW_UNIVERSE_DOMAIN'),
        ];

        $sessionsClient = new SessionsClient([
            'credentials' => $credentials,
        ]);

        $session = $sessionsClient->sessionName($projectId, $sessionId);

        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode($languageCode);

        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        $detectIntentRequest = new \Google\Cloud\Dialogflow\V2\DetectIntentRequest();
        $detectIntentRequest->setSession($session);
        $detectIntentRequest->setQueryInput($queryInput);

        $response = $sessionsClient->detectIntent($detectIntentRequest);
        $queryResult = $response->getQueryResult();

        $sessionsClient->close();

        return response()->json([
            'response' => $queryResult->getFulfillmentText(),
        ]);
    }
}
