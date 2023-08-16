<?php

namespace App\Http\Controllers\Web;

// use Log;
use DataTables;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Player;
use App\Models\Leader;
use App\Models\GiftPoint;
use App\Models\GiftWeapon;
use App\Models\PlayerWeapon;
use App\Models\GiftParachute;
use App\Models\GiftBoostPack;
use App\Models\GiftAnimation;
use App\Models\GiftCharacter;
use App\Models\PlayerStatistic;
use App\Models\PlayerBoostPack;
use App\Models\PlayerAnimation;
use App\Models\PlayerCharacter;
use App\Models\PlayerParachute;
use App\Models\DailyLoginCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PlayerController extends Controller
{
    public function showLeaderboard(Request $request)
    {
        if ($request->ajax()) {
            
            $model = Leader::query();

            return  DataTables::eloquent($model)

                    ->setRowId(function (Leader $leader) {
                        return $leader->id;
                    })

                    ->setRowClass(function (Leader $leader) {
                        return $leader->id % 2 == 0 ? 'alert-success' : 'alert-warning';
                    })

                    ->setRowAttr([
                        'align' => 'center',
                    ])
                    
                    ->make(true);
        }

        else {

            $topLeaders = PlayerStatistic::with('player')->orderBy(DB::raw("`opponent_killed` + `monster_killed` + `double_killed` + `triple_killed`"), 'DESC')->get();
       
            if ($topLeaders->isEmpty()) {
                return redirect()->back()->withErrors('No Player Found');
            }

            Leader::truncate();

            // create leader board
            $exception = $this->buildLeaderBoard($topLeaders);

            if ($exception) {
                
                return back()->withErrors($exception);
            }

        }


        return view('admin.other_layouts.players.view_leaderboard');

        /*
            $leaders = Leader::with('user')->paginate(50);
            return view('admin.other_layouts.players.view_leaderboard', compact('leaders'));
        */
    }

    public function buildLeaderBoard($topLeaders)
    { 
        try {
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
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function showAllPlayers(Request $request)
    {
        if($request->ajax()){

            $model = Player::with('user', 'playerStatistics')->select('players.*');

            return  DataTables::eloquent($model)


                    ->addColumn('action', function(){

                        if(auth()->user()->can('delete')){
                            
                        /*
                            $button = "<i class='fa fa-fw fa-eye' style='transform: scale(1.5);' title='View'></i>";

                            $button .= "&nbsp;&nbsp;&nbsp;";
                        */

                            $button = "<i class='fa fa-fw fa-trash text-danger' style='transform: scale(1.5);' title='Delete'></i>";

                            return $button;
                        }

                    })


                    ->setRowId(function (Player $player) {
                        return $player->id;
                    })

                    ->setRowClass(function (Player $player) {
                        return $player->id % 2 == 0 ? 'alert-success' : 'alert-warning';
                    })

                    ->setRowAttr([
                        'align' => 'center',
                    ])
                    
                    ->make(true);
        }

        return view('admin.other_layouts.players.all_players');


        /*
        $users = User::where('type', 'player')->with('player')->paginate(6);
        return view('admin.other_layouts.players.all_players', compact('users'));
        */
    }

    public function deletePlayerMethod($playerId)
    {
        $playerToDelete = Player::find($playerId);

        $playerToDelete->playerMissions()->delete();
        $playerToDelete->playerPurchases()->delete();
        $playerToDelete->playerHistories()->delete();
        $playerToDelete->playerStatistics()->delete();
        // $playerToDelete->playerAchievements()->delete();
        $playerToDelete->playerTreasures()->delete();
        $playerToDelete->playerTreasureRedemptions()->delete();
        $playerToDelete->playerWeapons()->delete();
        $playerToDelete->playerParachutes()->delete();
        $playerToDelete->playerAnimations()->delete();
        $playerToDelete->playerCharacters()->delete();
        $playerToDelete->playerBoostPacks()->delete();
        $playerToDelete->subscriptionPackage()->delete();
        $playerToDelete->checkLoginDays()->delete();
        $playerToDelete->user()->delete();
        
        $playerToDelete->delete();

        return redirect()->back()->with('success', 'Player is Deleted');
    }


    public function submitCreateBotForm(Request $request)
    {
        $request->validate([
            'type'=>'required'
        ]);


        // Creating New User
        $newUser = User::firstOrCreate([
            'username' => $request->username,
            'phone' => $request->phone,
            'device_info' => $request->id,
            'email' => $request->email,
            'location' => $request->location,
            'profile_pic' => '',
            'login_type' => 'false',
            'country' => 'Bangladesh',
            'connection_type' => 'false',
            'type' => strtolower($request->type),
        ]);

        $newUser->device_info = $newUser->id;
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


        return redirect()->back()->with('success', 'New Bot has been Created');
    }

    public function showAllBots()
    {
        $bots = User::where('type', 'bot')->with('player')->paginate(6);
        return view('admin.other_layouts.players.all_bots', compact('bots'));
    }

    public function showBotEditForm(Request$request, $botId)
    {
        $botToUpdate = User::with('player')->findOrFail($botId);
        return view('admin.other_layouts.players.edit_bot', compact('botToUpdate'));
    }

    public function submitBotEditForm(Request $request, $botId)
    {
        $profileToUpdate = User::findOrFail($botId);

        $request->validate([
            'type'=>'required'
        ]);

        $profileToUpdate->username = $request->username;
        $profileToUpdate->phone = $request->phone;
        // $profileToUpdate->device_info = $newUser->id;
        $profileToUpdate->email = $request->email;
        $profileToUpdate->location = $request->location;
        // $profileToUpdate->facebook_id = $request->facebookID;
        // $profileToUpdate->facebook_name = $request->facebookName;
        // $profileToUpdate->profile_pic = '';
        // $profileToUpdate->login_type = false;
        // $profileToUpdate->country = 'Bangladesh';
        // $profileToUpdate->connection_type = false;
        // $profileToUpdate->type = strtolower($request->type);
        $profileToUpdate->save();

        return redirect()->back()->with('success', 'Bot Profile is Updated');
    }

    public function deleteBotMethod($botId)
    {
        $botToDelete = User::find($botId);

        $botToDelete->player->playerStatistics->delete();
        $botToDelete->player->playerCharacters()->delete();
        $botToDelete->player->playerAnimations()->delete();
        $botToDelete->player->playerParachutes()->delete();
        $botToDelete->player->playerWeapons()->delete();
        $botToDelete->player->playerTreasures()->delete();
        $botToDelete->player->playerHistories()->delete();
        $botToDelete->player->playerBoostPacks->delete();
        $botToDelete->player->checkLoginDays->delete();
        $botToDelete->player->delete();
        
        $botToDelete->delete();


        /*
        $playerToDelete = Player::find($playerId);
        $playerToDelete->playerMissions()->delete();
        $playerToDelete->playerHistories()->delete();
        $playerToDelete->playerStatistics()->delete();
        // $playerToDelete->playerAchievements()->delete();
        $playerToDelete->playerTreasures()->delete();
        $playerToDelete->playerWeapons()->delete();
        $playerToDelete->playerParachutes()->delete();
        $playerToDelete->playerAnimations()->delete();
        $playerToDelete->playerCharacters()->delete();
        $playerToDelete->playerBoostPacks()->delete();
        $playerToDelete->subscriptionPackage()->delete();
        $playerToDelete->checkLoginDays()->delete();
        $playerToDelete->user()->delete();
        
        $playerToDelete->delete();
        */

        return redirect()->back()->with('success', 'Bot is Deleted');
    }
}
