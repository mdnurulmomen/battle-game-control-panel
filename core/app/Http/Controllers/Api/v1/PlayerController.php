<?php

namespace App\Http\Controllers\Api\v1;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Player;
use App\Models\Leader;
use App\Models\GiftPoint;
use App\Models\GiftWeapon;
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
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\v1\Player\PlayerResource;
use App\Http\Resources\v1\Player\LeaderResource;
use App\Http\Resources\v1\Player\MyLeaderResource;

class PlayerController extends Controller
{
    /*
    protected $request;

    public function __construct(Request $postman)
    {
        $postman->validate([
            'payload'=>'required'
        ]);    

        $decryptedJWTPayload = openssl_decrypt($postman->payload, 'AES-256-CBC', env('CUSTOM_ENCRYPTION_KEY'), 0, env('CUSTOM_IV_KEY'));
        $this->request = JWTAuth::getPayload(JWTAuth::setToken($decryptedJWTPayload))->get();
        $this->request = new Request($this->request);
    }
    */

    public function checkPlayerExist(Request $request)
    {
        $request->validate([
          'facebookId'=>'required_without:userDeviceId'
        ]);

        if(is_null($request->facebookId) || empty($request->facebookId) ) {

            if ($userExist = User::where('device_info', $request->userDeviceId)->first()) {

                return redirect()->route('api.v1.player_show', $userExist->player->id);
            }
            else{
                return $this->createPlayerMethod($request);
            }
        }

        else{

            if ($userExist = User::where('facebook_id', $request->facebookId)->first()) {

                return redirect()->route('api.v1.player_show', $userExist->player->id);
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

                return redirect()->route('api.v1.player_show', $userExist->player->id);
            }

            else{
                return $this->createPlayerMethod($request);
            }
        }
    }

    public function createPlayerMethod($request)
    {
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


        // Creating New Player
        $newPlayer = Player::firstOrCreate(array('id' => $newUser->id));
        $newPlayer->selected_parachute = $request->selectedParachute ?? 0;
        $newPlayer->selected_character = $request->selectedCharacter ?? 0;
        $newPlayer->selected_animation = $request->selectedAnimation ?? 0;
        $newPlayer->selected_weapon = $request->selectedWeapon ?? 0;
        $newPlayer->user_id = $newUser->id;
        $newPlayer->save();


        // Creating New Players Boost Packs
        $giftBoostPack = GiftBoostPack::first();            
        $newPlayerBoostPack = PlayerBoostPack::firstOrCreate([
            'melee_boost' => $giftBoostPack->gift_melee_boost ?? 0,
            'light_boost' => $giftBoostPack->gift_light_boost ?? 0,
            'heavy_boost' => $giftBoostPack->gift_heavy_boost ?? 0,
            'ammo_boost' => $giftBoostPack->gift_ammo_boost ?? 0,
            'range_boost' => $giftBoostPack->gift_range_boost ?? 0,
            'speed_boost' => $giftBoostPack->gift_speed_boost ?? 0,
            'armor_boost' => $giftBoostPack->gift_armor_boost ?? 0,
            'xp_multiplier' => $giftBoostPack->gift_multiplier_boost ?? 0,
            'player_id' => $newPlayer->id,
        ]);
          

        // Creating New Players Statistics
        $giftPoints = GiftPoint::first();
        $newPlayerStatistic = PlayerStatistic::firstOrCreate([
            'coins' => $giftPoints->gift_coins ?? 0,
            'gems' => $giftPoints->gift_gems ?? 0,
            'player_id' => $newPlayer->id
        ]);


        // Creating New Players Gift Characters
        $giftCharacters = GiftCharacter::all();

        if ($giftCharacters->isNotEmpty() && !$giftCharacters->contains('gift_character_index', -1)) {
            
            foreach ($giftCharacters as $giftCharacter) {
                $newPlayerCharacter = new PlayerCharacter();
                $newPlayerCharacter->character_index = $giftCharacter->gift_character_index;
                $newPlayerCharacter->player_id = $newPlayer->id;
                $newPlayerCharacter->save();
            }
        } 


        // Creating New Players Gift Animations
        $giftAnimations = GiftAnimation::all();

        if ($giftAnimations->isNotEmpty() && !$giftAnimations->contains('gift_animation_index', -1)) {

            foreach ($giftAnimations as $giftAnimation) {
                $newPlayerAnimation = new PlayerAnimation();
                $newPlayerAnimation->animation_index = $giftAnimation->gift_animation_index;
                $newPlayerAnimation->player_id = $newPlayer->id;
                $newPlayerAnimation->save();
            }
        }


        // Creating New Players Gift Parachutes
        $giftParachutes = GiftParachute::all();

        if ($giftParachutes->isNotEmpty() && !$giftParachutes->contains('gift_parachute_index', -1)) { 

            foreach ($giftParachutes as $giftParachute) {
                $newPlayerParachute = new PlayerParachute();
                $newPlayerParachute->parachute_index = $giftParachute->gift_parachute_index;
                $newPlayerParachute->player_id = $newPlayer->id;
                $newPlayerParachute->save();
            }
        }


        // Creating New Players Gift Weapons
        $giftWeapons = GiftWeapon::all();

        if ($giftWeapons->isNotEmpty() && !$giftWeapons->contains('gift_weapon_index', -1)) {

            foreach ($giftWeapons as $giftWeapon) {
                $newPlayerWeapon = new PlayerWeapon();
                $newPlayerWeapon->weapon_index = $giftWeapon->gift_weapon_index;
                $newPlayerWeapon->player_id = $newPlayer->id;
                $newPlayerWeapon->save();
            }
        }


        // Creating New Players Login History
        $newLogin = DailyLoginCheck::firstOrCreate(array('player_id' => $newPlayer->id));
        $newLogin->player_id = $newPlayer->id;
        $newLogin->consecutive_days = 1;
        $newLogin->save();

        return new PlayerResource($newPlayer);
    }
    

    // Creating Players Login Data
    public function consecutiveLoginDays($playerId)
    {
        $playerLogin = DailyLoginCheck::where('player_id', $playerId)->first();

        if (is_null($playerLogin) || empty($playerLogin)) {
            
            DailyLoginCheck::create(['player_id' => $playerId, 'consecutive_days' => 1]);
        }

        else{

            $previousLoginDate = new Carbon($playerLogin->updated_at);
            $currentDate = Carbon::now();

            $difference = $previousLoginDate->diffInDays($currentDate);

            if($difference>0 && $difference<2){
                $playerLogin->increment('consecutive_days');
            }else{
               $playerLogin->consecutive_days = 1; 
            }
            
            $playerLogin->save();               // To Update updated_at
        }
    }

    public function showPlayerDetails($playerId)
    {   
        $playerToShow = Player::find($playerId);

        if(is_null($playerToShow) || empty($playerToShow)){

            return response()->json(['error'=>'Invalid player'], 422);
        }

        $this->consecutiveLoginDays($playerId);
        
        return new PlayerResource($playerToShow);
    }

    public function editUserInfo(UserRequest $request)
    {
        $request_1 = $request->only(['username','phone', 'device_info', 'email', 'location', 'facebook_id', 'facebook_name', 'profile_pic', 'login_type', 'connection_type']);

        $request_2 = $request->only(['player_batch','selected_parachute', 'selected_character', 'selected_animation', 'selected_weapon']);

        $request_1 = array_filter($request_1);

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

    public function definePlayerLever($currentXpPoint)
    {
        $x = 0;
        $m = 0;

        while ($m <= $currentXpPoint) {
            $x++;
            $m += $x * ($x + 1) * 25;
        }

        return $x;
    }

    public function showLeaderboard(Request $request)
    {
        $request->validate([
          'userId'=>'required'
        ]);


        $topLeaders = PlayerStatistic::orderBy(DB::raw("`opponent_killed` + `monster_killed` + `double_killed` + `triple_killed`"), 'DESC')->get();

        if ($topLeaders->isEmpty()) {
            return response()->json(['message'=>'No player found'], 422);
        }

        Leader::truncate();

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

        $leaders = Leader::take(20)->get();
        $myPossition = Player::find($request->userId)->playerLeadershipPosition ?? null;


        return ['topLeaders' => LeaderResource::collection($leaders), 'myPossition'=> new MyLeaderResource($myPossition)];
    }


    public function updateMultipleAssets(Request $request)
    {
        $request->validate([
            'userId'=>'required'
        ]);

        $playerToUpdate = Player::find($request->userId);

        $playerStatisticsToUpdate = $playerToUpdate->playerStatistics;
        $playerBoostPacksToUpdate = $playerToUpdate->playerBoostPacks;

        $coins = $request->coinsEarned ?? 0;
        $gems = $request->gemsEarned ?? 0;
        $xp_multiplier = $request->xpMultiplierEarned ?? 0;

        $playerStatisticsToUpdate->increment('coins', $coins);
        $playerStatisticsToUpdate->increment('gems', $gems);
        $playerBoostPacksToUpdate->increment('xp_multiplier', $xp_multiplier);

        return response()->json(['message'=>'success'], 200);
    }
}