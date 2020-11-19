<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserMongo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use League\OAuth2\Server\Exception\OAuthServerException;

class BasicController extends Controller
{
    // use IssueTokenTrait;
    // private $client;

    public function __construct()
    {
        // $this->client = Client::whereName("Laravel Password Grant Client")->first();
    }

    public function index()
    {
        $status = "ok";
        return response()->json(compact('status'), 200);
    }
    
    public function counter_mongo(){
        $data = UserMongo::first();
        $data->counter = ($data->counter??0) + 1;
        $data->save();
        return response()->json(compact('data'), 200);
    }

    public function counter_mysql(){
        $data = User::first();
        $data->counter = ($data->counter??0) + 1;
        $data->save();
        return response()->json(compact('data'), 200);
    }

    public function user(Request $request)
    {
        $queries = [];
        $queries_count = 0;

        DB::enableQueryLog();

        $data = $request->user();
        $data->counter = ($data->counter??0) + 1;
        $data->save();
        // \Log::info($data->name);
        
        $queries = DB::getQueryLog();
        $queries_count = count($queries);

        DB::disableQueryLog();
        DB::flushQueryLog();
        return response()->json(compact('data', 'queries_count', 'queries'), 200);
    }

    public function login(Request $request)
    {

        // $request_all = $request->all();
        // echo(response()->json(compact('request_all'), 200));    
        // // $this->validate($request, [
        // //     "email" => "required",
        // //     "password"  => "required"
        // // ]);
        // return $this->issueToken($request, 'password');
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }

    public function issueToken(Request $request, $grantType, $scope = "")
    {

        $client = Client::whereName("Laravel Password Grant Client")->first();
        $params = [
            'grant_type' => $grantType,
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => $scope
        ];
        if ($grantType !== 'social') {
            // $params['username'] = $request->username ?: $request->email;
            $params['username'] = $request->email;
        }
        // echo(response()->json(compact('params'), 200));    
        $request->request->add($params);
        $proxy = Request::create('oauth/token', 'POST');
        $tokenResponse = Route::dispatch($proxy);
        $content = $tokenResponse->getContent();
        $data = json_decode($content, true);

        if (isset($data["error"])) {
            return $data["error"];
            throw new OAuthServerException(__("auth::errors.invalid_credentials"), 6, 'invalid_credentials', 401);
        }

        return $data;
    }
}
