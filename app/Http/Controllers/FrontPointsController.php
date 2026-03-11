<?php

namespace App\Http\Controllers;

use App\Models\PointsRecord;
use Illuminate\Http\Request;

class FrontPointsController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        $records = $customer->pointsRecords()
            ->with('order')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $levelConfig = $customer->getMemberLevelConfig();
        $currentLevel = $levelConfig[$customer->member_level] ?? null;
        $nextLevel = $customer->next_level;

        return view('front.points.index', compact('records', 'customer', 'currentLevel', 'nextLevel'));
    }
}
