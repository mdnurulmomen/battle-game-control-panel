<?php

namespace App\Http\Resources\Player;

use App\Http\Resources\Game\NewsResource;
use App\Http\Resources\Game\MessageResource;
use App\Http\Resources\Game\MessageCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {

        return [
            'playerBasicInfo'=>new PlayerUserDetails($this->user),
            
            'playerAdvanceInfo'=>new PlayerSelectionDetails($this),

            'statistics'=>new PlayerStatistic($this->playerStatistics),

            'unlockedItems'=>new PlayerUnlockingDetails($this),
            
            'totalBoostItems'=>new PlayerBoostDetails($this),

            'newsFeeds'=>NewsResource::collection($this->allNews()),

            'messages'=>new MessageCollection($this->allMessages()),

            // 'consequentLoginDays'=>$this->checkLoginDays->consecutive_days
        ];
    }
}
