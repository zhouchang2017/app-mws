<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Resources\Channel as ChannelResource;

class ChannelController extends Controller
{
    public static $resource = ChannelResource::class;
}
