<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\Vendor;
use GuzzleHttp\Client;
use App\Models\Player;
use App\Models\Earning;
use App\Models\Treasure;
use Illuminate\Support\Str;
use App\Models\GiftTreasure;
use Illuminate\Http\Request;
use App\Models\PlayerTreasure;
use App\Models\PlayerStatistic;
use App\Http\Traits\RetrieveToken;
use App\Models\TreasureRedemption;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\RequestException;
use App\Http\Resources\v1\Game\TreasureResource;
use App\Http\Resources\v1\Player\PlayerTreasureRedeemed;
use App\Http\Resources\v1\Player\PlayerTreasureResource;

class TreasureController extends Controller
{
    public function treasureIdentifier()
    {
        $updatedEarn = Earning::latest()->first();
        $currentEarn = $updatedEarn->current_gems_earning ?? 0;

        // $giftTreasure = GiftTreasure::first();
        $giftTreasure = Cache::get('giftTreasureSetting', GiftTreasure::first());
        $required_earn = $giftTreasure->required_earn ?? 0;

        if ($required_earn <= $currentEarn) {
            
            // $treasureDetails = Treasure::find($giftTreasure->treasure_id);
            $treasureDetails = Cache::get('giftTreasureDetails', Treasure::find($giftTreasure->treasure_id));

            if ($treasureDetails) {
                
                $updatedEarn->decrement('current_gems_earning', $required_earn ?? 0);

                // Countdown total treasure collected
                $giftTreasure->increment('total_treasure_gifted');

                return ['giftTreasure'=>'true', 'treasureDetails'=>new TreasureResource($treasureDetails)];
            }

            return ['giftTreasure'=>'false', 'message'=>'Gift Treasure not Found'];
        }

        return ['giftTreasure'=>'false'];
    }

    public function playerTreasureList(Request $request)
    {   
        $request->validate([
            'userId'=>'required|exists:players,id'
        ]);

        $player = Player::find($request->userId);

        return new PlayerTreasureResource($player);
    }

    public function treasureRedemption(Request $request)
    {
        $request->validate([
            'serial'=>'required',
            'userId'=>'required',
            'treasureId'=>'required',
            'playerPhone'=>'nullable|regex:/(01)[0-9]{9}/',
            'agentPhone' => 'nullable|regex:/(01)[0-9]{9}/',
            'exchangingType'=>'required|in:coins,gems,MB,TalkTime,talkTime,talktime,Burger'
        ]);

        $playerTreasureExist = PlayerTreasure::where('player_id', $request->userId)
                                            ->where('treasure_id', $request->treasureId)
                                            ->where('id', $request->serial)
                                            ->where('status', 1)
                                            ->first();

        if ($playerTreasureExist) {
            
            $treasureDetails = Treasure::find($request->treasureId);
            $playerStatistics = PlayerStatistic::where('player_id', $request->userId)->first();

            // initialisation
            $status = 1;
            $collectingPoint = '';

            if (Str::is('coins', $request->exchangingType)) {

                $playerStatistics->increment($request->exchangingType, $treasureDetails->exchanging_coins);

                $collectingPoint = 'Game Currency';
                $status = -1;
            }

            else if (Str::is('gems', $request->exchangingType)) {
                
                $playerStatistics->increment($request->exchangingType, $treasureDetails->exchanging_gems);

                $collectingPoint = 'Game Currency';
                $status = -1;
            }

            else if (Str::is('*alk*', $request->exchangingType)) {
                
                $collectingPoint = $request->playerPhone;
                $status = 0;
            }

            else if (Str::is('MB', $request->exchangingType)) {
                
                $playerPhone = Str::start($request->playerPhone, '88');

                /*if (Str::startsWith($playerPhone, '88018') || Str::startsWith($playerPhone, '88016')) {

                    // Send MB Pack to User
                    $response = $this->sendRobiOrAirtelDataPack($playerPhone, $playerTreasureExist);

                    if ($response != 202) {
            
                        $this->sendFailedSmsToUser($playerPhone);

                        return response()->json(['error' => 'MB pack couldnt sent successfully. Please try again later'], 422);
                        
                    }

                }*/

                // else {

                    $response = $this->sendEasyDataPack($playerPhone, $playerTreasureExist);

                    if ($response != 200) {
            
                        $this->sendFailedSmsToUser($playerPhone);

                        return response()->json(['error' => 'MB pack couldnt sent successfully. Please try again later'], 422);
                        
                    }
                    
                // }
                
                $sendConfirmationSmsToUser = $this->sendConfirmationSmsToUser($playerPhone, $treasureDetails, $request);
                
                $collectingPoint = 'MB, Mobile : '.$request->playerPhone;
                $status = -1;
            }


            else if (Str::is('*urger*', $request->exchangingType)) {

                $agentPhone = Str::start($request->agentPhone, '88');
                
                $vendor = Vendor::where('mobile', $agentPhone)->first();

                if (!$vendor) {
                    
                    return response()->json( ['error' => 'No such agent found'], 422);
                }

                $collectingPoint = $vendor->address.', '.$vendor->area->name.', '.$vendor->city->name.', '.$vendor->division->name;

                $status = -1;

                // Sending SMS to Vendor
                $this->sendSmsToVendor($vendor, $treasureDetails, $request);
            }

            // Updating Player Treasure
            $playerTreasureExist->status = $status;
            $playerTreasureExist->save();

            // Creating Redeem History
            $this->createTreasureRedemptionHistory($request, $treasureDetails, $playerTreasureExist, $collectingPoint);

            return ['message'=>'success', 'redemptionDetails'=> new PlayerTreasureRedeemed($playerTreasureExist)];
        }

        return response()->json(['error'=>'Treasure does not belong'], 422);
    }

    public function sendConfirmationSmsToUser($playerPhone, Treasure $treasureDetails, Request $request)
    {
        $client = new Client();

        $username = 'treasure_hunt';
        $password = 'Treasure@12';
        $from = 'T hunt';
        $to = $playerPhone;

        if (Str::startsWith($playerPhone, '88017')  || Str::startsWith($playerPhone, '88013')) {
            $check = "*121*1*4%23";
        }
        if (Str::startsWith($playerPhone, '88019')  || Str::startsWith($playerPhone, '88014')) {
            $check = "*121*1%23 or *5000*500%23";
        }
        if (Str::startsWith($playerPhone, '88015')) {
            $check = "*152%23";
        }

        if (Str::startsWith($playerPhone, '88016') || Str::startsWith($playerPhone, '88018')) {
            $check = "*222*2%23";
        }

        // $check = htmlentities($check);

        $playerName = Player::find($request->userId)->user->username;

        $message = "$playerName, Your request to convert $treasureDetails->name to Megabyte pack $treasureDetails->exchanging_megabyte MB at $request->playerPhone is successfully added. To check balance dial $check";

        $api = "https://api.mobireach.com.bd/SendTextMessage?Username=$username&Password=$password&From=$from&To=$to&Message=$message";

        $response = $client->request('GET', "$api");

    }

    public function sendFailedSmsToUser($playerPhone)
    {
        $client = new Client();

        $username = 'treasure_hunt';
        $password = 'Treasure@12';
        $from = 'T hunt';
        $to = $playerPhone;

        $message = "MB pack couldnt sent successfully. Please try again later";

        $api = "https://api.mobireach.com.bd/SendTextMessage?Username=$username&Password=$password&From=$from&To=$to&Message=$message";

        $response = $client->request('GET', "$api");
    }

    /*public function sendRobiOrAirtelDataPack($playerPhone, $playerTreasureExist)
    {
        $grantType = "password";
        $userName = "MIFE_DV_TREASURE";
        $password = "T-#u|\|T@2O!9O2!3";
        $scope  = "PRODUCTION";

        $consumerKey = "Nm2tROFJV3QtcxkrovlPco_ObHAa";
        $consumerSecret = "id8n7HgLUZL0tJ2PsAeB5fxkZI0a";
        $basicAuthorization = base64_encode($consumerKey.':'.$consumerSecret);

        $headers = [
            'Authorization' => "Basic $basicAuthorization",
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        
        $client = new Client([
            'headers' => $headers
        ]);

        $response = $client->request('POST', 'https://api.robi.com.bd/token', [

            'form_params' => [
                'grant_type' => $grantType,
                'username' => $userName,
                'password' => $password,
                'scope' => $scope,
            ]
        ]);

        $request = json_decode($response->getBody());

        $accessToken = $request->access_token;

        $headers2 = [
            'Authorization' => "Bearer $accessToken",
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        
        $clientWithToken = new Client([
            'headers' => $headers2
        ]);

        $treasureDetails = Treasure::find($playerTreasureExist->treasure_id);

        if ($treasureDetails->equivalent_price > 600) {
            
            $packageName = '649TK_USSD';
        }

        else if ($treasureDetails->exchanging_megabyte > 99) {
            
            $packageName = '100MB_SC2';
        }

        else
            $packageName = '10Tk_USSD_60MB';


        try {
            
            $responseWithResult = $clientWithToken->request('POST', 'https://api.robi.com.bd/adcs/adcspackProvisioningNormal/v1/packProvisioningNormal', [

                'form_params' => [
                    'MSISDN' => $playerPhone,
                    'name' => $packageName
                ]

            ]);
        }

        catch (RequestException $e) {

            if ($e->hasResponse()) {
                
                $response = $e->getResponse();
                return $responseBodyAsString = $response->getBody()->getContents();
            }
        }

        return $responseWithResult->getStatusCode();

    }*/

    public function sendEasyDataPack($playerPhone, $playerTreasureExist)
    {
        $operator = 0;

        if (Str::startsWith($playerPhone, '88017')  || Str::startsWith($playerPhone, '88013')) {
            $operator = "1";
        }

        else if (Str::startsWith($playerPhone, '88019')  || Str::startsWith($playerPhone, '88014')) {
            $operator = "2";
        }

        else if (Str::startsWith($playerPhone, '88018')) {
            $operator = "3";
        }

        else if (Str::startsWith($playerPhone, '88015')) {
            $operator = "5";
        }

        else if (Str::startsWith($playerPhone, '88016')) {
            $operator = "6";
        }

        $treasureDetails = Treasure::find($playerTreasureExist->treasure_id);

        $client_recharge = new Client();

        $response_recharge = $client_recharge->request('POST', 'https://abcdgamepanel.com/test/abcd/ssl/recharge.php', [

            'form_params' => [
                'mobile' => $playerPhone,
                'operator' => $operator,
                'amount' => $treasureDetails->exchanging_megabyte
            ]
        ]);

        $recharge_request = json_decode($response_recharge->getBody());

        if (empty($recharge_request->vr_guid)) {

            return response()->json(['error' => 'vr_guid is missing'], 422);
        }

        else if ($recharge_request->vr_guid && $recharge_request->guid) {

            $client_init = new Client();

            $response = $client_init->request('POST', 'https://abcdgamepanel.com/test/abcd/ssl/recharge_init.php', [

                'form_params' => [
                    'guid' => $recharge_request->guid,
                    'vr_guid' => $recharge_request->vr_guid
                ]

            ]);

            return $response->getStatusCode();

        }
    }

    public function sendSmsToVendor(Vendor $vendor, Treasure $treasureDetails, Request $request)
    {
        $client = new Client();

        $username = 'treasure_hunt';
        $password = 'Treasure@12';
        $from = 'T hunt';
        $to = $vendor->mobile;

        $playerName = Player::find($request->userId)->user->username;

        $message = "User Name : $playerName, Mobile : $request->playerPhone has requested for a $treasureDetails->name";

        $api = "https://api.mobireach.com.bd/SendTextMessage?Username=$username&Password=$password&From=$from&To=$to&Message=$message";

        $response = $client->request('GET', "$api");

        /*
        if ($response->getStatusCode() === 200) {
            
            return response()->json(['error'=>'Message couldnt sent'], 422);
        }
       */ 
    }

    public function createTreasureRedemptionHistory(Request $request, Treasure $treasureDetails, PlayerTreasure $playerTreasureExist, $collectingPoint)
    {
        $newTreasureRedemption = new TreasureRedemption();
        $newTreasureRedemption->player_id = $request->userId;
        $newTreasureRedemption->treasure_id = $request->treasureId;
        $newTreasureRedemption->exchanging_type = $request->exchangingType;
        $newTreasureRedemption->player_phone = $request->playerPhone;
        $newTreasureRedemption->agent_phone = $request->agentPhone;

        $newTreasureRedemption->collecting_point = $collectingPoint;
        $newTreasureRedemption->status = $playerTreasureExist->status;

        $newTreasureRedemption->equivalent_price = $treasureDetails->equivalent_price;
        $newTreasureRedemption->player_treasure_serial = $playerTreasureExist->id;
        $newTreasureRedemption->save(); 
    }
}
