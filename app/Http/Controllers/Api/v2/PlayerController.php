<?php

namespace App\Http\Controllers\Api\v2;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Player;
use App\Models\Leader;
use App\Models\GiftPoint;
use App\Models\GiftWeapon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PlayerWeapon;
use App\Models\GiftCharacter;
use App\Models\GiftAnimation;
use App\Models\GiftParachute;
use App\Models\GiftBoostPack;
use App\Models\PlayerAnimation;
use App\Models\PlayerCharacter;
use App\Models\PlayerParachute;
use App\Models\PlayerBoostPack;
use App\Models\DailyLoginCheck;
use App\Models\PlayerStatistic;
use App\Models\DailyLoginReward;
use App\Http\Traits\RetrieveToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestWithToken;
use App\Http\Resources\v2\Player\PlayerResource;
use App\Http\Resources\v2\Player\LeaderResource;
use App\Http\Resources\v2\Player\MyLeaderResource;

class PlayerController extends Controller
{
    use RetrieveToken;

    public function checkPlayerExist(RequestWithToken $postman)
    {
        $payload = $this->retrieveToken($postman);

        if (is_null($payload)) {
            return response()->json(['error'=>'Invalid token'], 422);
        }

        $request = new Request($payload);

        
        $request->validate([

          'mobileNo'=>'required_without:userDeviceId',
          
        ]);

        if (is_null($request->mobileNo) || empty($request->mobileNo)) {
            
            if ($userExist = User::takenDeviceId($request->userDeviceId)->first()) {

                return redirect()->route('api.v2.player_show', $userExist->player->id);
            }

            else {

                return $this->createPlayerMethod($request);
            }
        }

        else {

            // For Users who are Identified by phone
            if ($userExist = User::takenMobileNo($request->mobileNo)->first()) {

                /*
                // for old user who had device id and mobile no.
                $userExist->device_info = '';
                $request->has('profilePic') ? $userExist->profile_pic = $request->profilePic : 0 ;
                $request->has('userEmail') ? $userExist->email = $request->userEmail : 0 ;
                $userExist->save();
                */

                return redirect()->route('api.v2.player_show', $userExist->player->id);
            }

            // For Guest User who are Identified by userDeviceId
            elseif ($userExist = User::takenDeviceId($request->userDeviceId)->first()) {

                // Updating Guest User
                $userExist->device_info = '';
                $userExist->phone = $request->mobileNo;
                $userExist->login_type = 'true';
                $userExist->profile_pic = $request->profilePic;

                if ($request->facebookName || $request->gmailName) {
                    
                    $userExist->username = empty($request->facebookName) ? $request->gmailName : $request->facebookName;
                }
                else{

                    $userExist->username = $request->userName;
                }

                $request->has('userEmail') ? $userExist->email = $request->userEmail : 0;
                $request->has('facebookId') ? $userExist->facebook_id = $request->facebookId : 0;
                $request->has('facebookName') ? $userExist->facebook_name = $request->facebookName : 0;
                $request->has('gmailId') ? $userExist->gmail_id = $request->gmailId : 0;
                $request->has('gmailName') ? $userExist->gmail_name = $request->gmailName : 0;
                $userExist->save();

                return redirect()->route('api.v2.player_show', $userExist->player->id);
            }

            // For Old User who were Identified by facebookId
            elseif ($userExist = User::takenFacebookId($request->facebookId)->first()) {

                // Updating Guest User with NEW data
                $userExist->phone = $request->mobileNo;

                $request->has('userEmail') ? $userExist->email = $request->userEmail : 0;
                $request->has('facebookName') ? $userExist->facebook_name = $request->facebookName : 0;
                $userExist->save();

                return redirect()->route('api.v2.player_show', $userExist->player->id);
            } 

            else{
                return $this->createPlayerMethod($request);
            }
        }
        

        /*
        $request->validate([

          'facebookId'=>'required_without:userDeviceId',
          
        ]);

        if(is_null($request->facebookId) || empty($request->facebookId) ) {

            if ($userExist = User::where('device_info', $request->userDeviceId)->first()) {

                return redirect()->route('api.v2.player_show', $userExist->player->id);
            }
            else{
                return $this->createPlayerMethod($request);
            }
        }

        else{

            if ($userExist = User::where('facebook_id', $request->facebookId)->first()) {

                return redirect()->route('api.v2.player_show', $userExist->player->id);
            }

            else if ($userExist = User::where('device_info', $request->userDeviceId)->first()) {

                // Merging with Guest Account
                $userExist->username = $request->facebookName;
                $userExist->device_info = '';
                $userExist->email = $request->userEmail;
                $userExist->facebook_id = $request->facebookId;
                $userExist->facebook_name = $request->facebookName;
                $userExist->login_type = 'true';

                $userExist->save();

                return redirect()->route('api.v2.player_show', $userExist->player->id);
            }

            else{
                return $this->createPlayerMethod($request);
            }
        }
        */
    }

    public function createPlayerMethod($request)
    {
        // Creating New User
        $newUser = $this->createUser($request);        

        // Creating New Player
        $newPlayer = $this->createPlayer($newUser, $request);

        // Creating New Players Boost Packs
        $this->createPlayerBoostPacks($newPlayer);   

        // Creating New Players Statistics
        $this->createPlayerStatistics($newPlayer);

        // Creating New Players Gift Characters
        $this->createPlayerGiftCharacters($newPlayer);

        // Creating New Players Gift Animations
        $this->createPlayerGiftAnimations($newPlayer);

        // Creating New Players Gift Parachutes
        $this->createPlayerGiftParachutes($newPlayer);

        // Creating New Players Gift Weapons
        $this->createPlayerGiftWeapons($newPlayer);
        
        // Creating New Players Consecutive Daily Login History
        $this->createDailyLoginDays($newPlayer);


        return new PlayerResource($newPlayer);
    }
    

    public function createUser($request)
    {
        $newUser = new User();
        $newUser->phone = $request->mobileNo;
        $newUser->email = $request->userEmail ?? '';
        $newUser->location = $request->userLocation ?? 'Dhaka';
        $newUser->facebook_id = $request->facebookId ?? '';
        $newUser->facebook_name = $request->facebookName ?? '';
        $newUser->gmail_id = $request->gmailId ?? '';
        $newUser->gmail_name = $request->gmailName ?? '';
        $newUser->profile_pic = $request->profilePic ?? '';
        $newUser->country = $request->country ?? 'Bangladesh';
        $newUser->connection_type = $request->connectionType;
        $newUser->type = strtolower('player');

        if ($request->mobileNo) {
            
            if ($request->facebookName || $request->gmailName) {
                
                $newUser->username = empty($request->facebookName) ? $request->gmailName : $request->facebookName;
            }
            else{
                $newUser->username = $request->userName;
            }

            $newUser->device_info = '';
            $newUser->login_type = 'true';
        }else{
            $newUser->username = $request->userName;
            $newUser->device_info = $request->userDeviceId;
            $newUser->login_type = 'false' ;
        }
        
        $newUser->save();
        

        /*
        // Creating New User
        $newUser = new User();
        $newUser->username = $request->facebookName ?? $request->userName;
        $newUser->phone = $request->mobileNo ?? '';
        $newUser->email = $request->userEmail ?? '';
        $newUser->location = $request->userLocation ?? 'Dhaka';
        $newUser->facebook_id = $request->facebookId ?? '';
        $newUser->facebook_name = $request->facebookName ?? '';
        $newUser->profile_pic = $request->profilePic ?? '';
        $newUser->country = $request->country ?? 'Bangladesh';
        $newUser->connection_type = $request->connectionType;
        $newUser->type = strtolower('player');
        empty($request->facebookId) ? $newUser->device_info = $request->userDeviceId : $newUser->device_info = '';
        empty($request->facebookId) ? $newUser->login_type = 'false' : $newUser->login_type = 'true';
        $newUser->save();
        */

        return $newUser;
    }

    public function createPlayer(User $newUser, $request)
    {
        return $newUser->player()->create([
            'selected_parachute' => $request->selectedParachute ?? 0,
            'selected_character' => $request->selectedCharacter ?? 0,
            'selected_animation' => $request->selectedAnimation ?? 0,
            'selected_weapon' => $request->selectedWeapon ?? 0,
        ]);
    }

    public function createPlayerBoostPacks(Player $newPlayer)
    {
        $giftBoostPack = GiftBoostPack::first();

        $newPlayer->playerBoostPacks()->create([
            'melee_boost' => $giftBoostPack->gift_melee_boost ?? 0,
            'light_boost' => $giftBoostPack->gift_light_boost ?? 0,
            'heavy_boost' => $giftBoostPack->gift_heavy_boost ?? 0,
            'ammo_boost' => $giftBoostPack->gift_ammo_boost ?? 0,
            'range_boost' => $giftBoostPack->gift_range_boost ?? 0,
            'speed_boost' => $giftBoostPack->gift_speed_boost ?? 0,
            'armor_boost' => $giftBoostPack->gift_armor_boost ?? 0,
            'xp_multiplier' => $giftBoostPack->gift_multiplier_boost ?? 0,
        ]);
    }

    public function createPlayerStatistics(Player $newPlayer)
    {
        $giftPoints = GiftPoint::first();

        $newPlayer->playerStatistics()->create([
            'coins' => $giftPoints->gift_coins ?? 0,
            'gems' => $giftPoints->gift_gems ?? 0,
        ]);
    }

    public function createPlayerGiftCharacters(Player $newPlayer)
    {
        $giftCharacters = GiftCharacter::all();

        if ($giftCharacters->isNotEmpty() && !$giftCharacters->contains('gift_character_index', -1)) {
            
            foreach ($giftCharacters as $giftCharacter) {

                $newPlayerCharacter = $newPlayer->playerCharacters()->create([

                    'character_index' => $giftCharacter->gift_character_index,
                ]);
            }
        }
    }

    public function createPlayerGiftAnimations(Player $newPlayer)
    {
        $giftAnimations = GiftAnimation::all();

        if ($giftAnimations->isNotEmpty() && !$giftAnimations->contains('gift_animation_index', -1)) {

            foreach ($giftAnimations as $giftAnimation) {

                $newPlayerAnimation = $newPlayer->playerAnimations()->create([

                    'animation_index' => $giftAnimation->gift_animation_index,
                ]);
            }
        }
    }

    public function createPlayerGiftWeapons(Player $newPlayer)
    {
        $giftWeapons = GiftWeapon::all();

        if ($giftWeapons->isNotEmpty() && !$giftWeapons->contains('gift_weapon_index', -1)) {

            foreach ($giftWeapons as $giftWeapon) {

                $newPlayerWeapon = $newPlayer->playerWeapons()->create([

                    'weapon_index' => $giftWeapon->gift_weapon_index,
                ]);
            }
        }
    }

    public function createPlayerGiftParachutes(Player $newPlayer)
    {
        $giftParachutes = GiftParachute::all();

        if ($giftParachutes->isNotEmpty() && !$giftParachutes->contains('gift_parachute_index', -1)) { 
            foreach ($giftParachutes as $giftParachute) {

                $newPlayerParachute = $newPlayer->playerParachutes()->create([

                    'parachute_index' => $giftParachute->gift_parachute_index,
                ]);
            }
        }
    }

    public function createDailyLoginDays(Player $newPlayer)
    {
        $newPlayer->checkLoginDays()->create([

            'consecutive_days' => 1,
            'reward_status' => 1,
            'created_at' => now(), 
            'updated_at' => now()
        ]);
    }

    // Updating Players Daily Login Data
    public function consecutiveLoginDays($playerId)
    {
        $playerLogin = DailyLoginCheck::where('player_id', $playerId)->first();

        if (is_null($playerLogin) || empty($playerLogin)) {
            
            $newPlayer = Player::find($playerId);
            $this->createDailyLoginDays($newPlayer);
        }

        else {

            $date = Carbon::parse(optional($playerLogin->updated_at)->format('d-m-Y') ?? "01-01-2019");
            $now = now()->format('d-m-Y');
            $difference = $date->diffInDays($now);

            if($difference == 0){

                $playerLogin->update([

                    'created_at' => null
                ]);
            }

            elseif($difference > 0 && $difference < 2){

                $playerLogin->update([
                    'consecutive_days' => $playerLogin->consecutive_days + 1,             
                    'reward_status' => 1,
                    'created_at' => $playerLogin->updated_at, 
                    'updated_at' => now()
                ]);
            }

            elseif($difference > 1){
                
                $playerLogin->update([
                    'consecutive_days' => 1,
                    'reward_status' => 1,
                    'created_at' => $playerLogin->updated_at, 
                    'updated_at' => now()
                ]);

            }  
        }
    }

    // For View Player API
    public function showPlayerDetails(Request $request, $playerId = null)
    {   
        if ($request->token) {
            
            $payload = $this->retrieveToken($request);

            if (is_null($payload)) {
                return response()->json(['error'=>'Invalid token'], 422);
            }

            $request = new Request($payload);
        }


        $playerToShow = Player::find($request->userId ?? $playerId);

        if(is_null($playerToShow) || empty($playerToShow)){

            return response()->json(['error'=>'Invalid player'], 422);
        }

        $this->consecutiveLoginDays($playerToShow->id);
        
        $this->rewardDailyLoginPrize($playerToShow->checkLoginDays);

        return new PlayerResource($playerToShow);
    }

    public function rewardDailyLoginPrize(DailyLoginCheck $dailyLoginCheck)
    {
        if ($dailyLoginCheck->reward_status) {
            
            $prizeToReward = DailyLoginReward::with('rewardType')->get()->get($dailyLoginCheck->consecutive_days-1);

            
            if ($prizeToReward) {

                $playerStatisticToUpdate = Player::find($dailyLoginCheck->player_id)->playerStatistics;
                
                $playerBoostPackToUpdate = Player::find($dailyLoginCheck->player_id)->playerBoostPacks; 
                
                if (Str::is('*oin', $prizeToReward->rewardType->reward_type_name)) {
                    
                    $playerStatisticToUpdate->increment('coins', $prizeToReward->amount);
                }

                elseif (Str::is('*em', $prizeToReward->rewardType->reward_type_name)) {
                    
                    $playerStatisticToUpdate->increment('gems', $prizeToReward->amount);
                }

                elseif (Str::is('*peed*', $prizeToReward->rewardType->reward_type_name)) {
                    
                    $playerBoostPackToUpdate->increment('speed_boost', $prizeToReward->amount);
                }

                elseif (Str::is('*rmor*', $prizeToReward->rewardType->reward_type_name)) {
                    
                    $playerBoostPackToUpdate->increment('armor_boost', $prizeToReward->amount);
                }

                elseif (Str::is('*mmo*', $prizeToReward->rewardType->reward_type_name)) {
                    
                    $playerBoostPackToUpdate->increment('ammo_boost', $prizeToReward->amount);
                }

                elseif (Str::is('*PBoost', $prizeToReward->rewardType->reward_type_name)) {
                    
                    $playerBoostPackToUpdate->increment('xp_multiplier', $prizeToReward->amount);
                }

                $dailyLoginCheck->update(['reward_status' => 0]);
            }
        }
    }

    public function editUserInfo(RequestWithToken $postman)
    {
        $payload = $this->retrieveToken($postman);

        if (is_null($payload)) {
            return response()->json(['error'=>'Invalid token'], 422);
        }

        $request = new Request($payload);

        $request->validate([
            'userId'=>'required|exists:users,id'
        ]);

        $request = $this->sanitize($request);

        $request_1 = $request->only(['username', 'email', 'location', 'profile_pic', 'connection_type', 'country']);

        $request_2 = $request->only(['player_batch','selected_parachute', 'selected_character', 'selected_animation', 'selected_weapon']);

        // Filtering null values to prevent null updation
        $request_1 = array_filter($request_1, function($value) {
            return ($value !== null); 
        });

        // Filtering null values to prevent null updation
        $request_2 = array_filter($request_2, function($value) {
            return ($value !== null && $value !== false); 
        });

        $userToUpdate = User::find($request->userId);

        if(!is_null($userToUpdate)){
            
            $playerToUpdate = Player::find($userToUpdate->player->id);

            $userToUpdate->update($request_1);
            $playerToUpdate->update($request_2);
   
            return new PlayerResource($playerToUpdate);
        }

        return response()->json(['error'=>'Invalid user'], 422);
    }

    public function sanitize(Request $request)
    {
        if (!empty($request->facebookName)) {
            $request['username'] = $request->facebookName;
        }
        else{
            $request['username'] = $request->userName;
        }
        
        unset($request['userName']);

        $request['email'] = $request->userEmail;
        unset($request['userEmail']);

        $request['location'] = $request->userLocation;
        unset($request['userLocation']);

        $request['facebook_id'] = $request->facebookId;
        unset($request['facebookId']);

        $request['facebook_name'] = $request->facebookName;
        unset($request['facebookName']);

        $request['profile_pic'] = $request->profilePic;
        unset($request['profilePic']);

        $request['connection_type'] = $request->connectionType;
        unset($request['connectionType']);

        /*
        if (empty($request->facebook_id)) {
            $request['device_info'] = $request->userDeviceId;
            $request['login_type'] = 'false';
        }
        else{
            $request['device_info'] = '';
            $request['login_type'] = 'true';
        }
        */

        $request['player_batch'] = $request->playerBatch;
        unset($request['playerBatch']);

        $request['selected_parachute'] = $request->selectedParachute;
        unset($request['selectedParachute']);

        $request['selected_character'] = $request->selectedCharacter;
        unset($request['selectedCharacter']);

        $request['selected_animation'] = $request->selectedAnimation;
        unset($request['selectedAnimation']);

        $request['selected_weapon'] = $request->selectedWeapon;
        unset($request['selectedWeapon']);

        return $request;
    }

    public function showLeaderboard(Request $request)
    {
        $request->validate([
          'userId'=>'required|exists:players,id'
        ]);


        $topLeaders = PlayerStatistic::orderBy(DB::raw("`opponent_killed` + `monster_killed` + `double_killed` + `triple_killed`"), 'DESC')->get();

        if ($topLeaders->isEmpty()) {
            return response()->json(['message'=>'No player found'], 422);
        }

        // Deleting all data from leader board
        Leader::truncate();

        // Creating leader board for current data
        $this->createLeadershipBoard($topLeaders);

        $leaders = Leader::take(20)->get();
        $myPossition = Player::find($request->userId)->playerLeadershipPosition ?? null;

        return ['topLeaders' => LeaderResource::collection($leaders), 'myPossition'=> new MyLeaderResource($myPossition)];
    }

    public function createLeadershipBoard($topLeaders)
    {
        foreach($topLeaders as $leader){
            $newLeader = new Leader();
            $newLeader->username = $leader->player->user->username;
            $newLeader->total_kill =  $leader->opponent_killed + $leader->monster_killed + $leader->double_killed + $leader->triple_killed;
            $newLeader->treasure_won = $leader->treasure_won;
            $newLeader->level = $leader->player_level;
            $newLeader->location = $leader->player->user->location;
            $newLeader->profile_pic = $leader->player->user->profile_pic;
            $newLeader->player_id = $leader->player_id;
            $newLeader->save();
        }
    }

    public function updateMultipleAssets(RequestWithToken $postman)
    {
        $payload = $this->retrieveToken($postman);

        if (is_null($payload)) {
            return response()->json(['error'=>'Invalid token'], 422);
        }

        $request = new Request($payload);

        $request->validate([
            'userId'=>'required|exists:players,id'
        ]);

        $playerToUpdate = Player::find($request->userId);

        $playerStatisticsToUpdate = $playerToUpdate->playerStatistics;
        $playerBoostPacksToUpdate = $playerToUpdate->playerBoostPacks;

        $coins = empty($request->coinsEarned) ? 0 : $request->coinsEarned;
        $gems = empty($request->gemsEarned) ? 0 : $request->gemsEarned;
        $xp_multiplier = empty($request->xpMultiplierEarned) ? 0 : $request->xpMultiplierEarned;

        $playerStatisticsToUpdate->increment('coins', $coins);
        $playerStatisticsToUpdate->increment('gems', $gems);
        $playerBoostPacksToUpdate->increment('xp_multiplier', $xp_multiplier);

        return response()->json(['message'=>'success'], 200);
    }
}
