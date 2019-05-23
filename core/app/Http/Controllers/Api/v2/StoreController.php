<?php

namespace App\Http\Controllers\Api\v2;

use App\Models\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\v2\Store\StoreCollection;

class StoreController extends Controller
{
    public function showAllStore()
    {       
        return new StoreCollection(Store::all());
    }
}
