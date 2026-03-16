<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\PointsRule;
use Illuminate\Http\Request;

class FrontVipController extends Controller
{
    public function index()
    {
        $customer = auth('customer')->user();
        $config = PointsRule::getMemberLevelConfig();
        $currentLevel = $customer->member_level ?? 'bronze';
        $currentLevelConfig = $config[$currentLevel] ?? $config['bronze'];
        
        $nextLevels = [];
        $levels = array_keys($config);
        $currentIndex = array_search($currentLevel, $levels);
        
        for ($i = $currentIndex + 1; $i < count($levels); $i++) {
            $nextLevels[] = [
                'key' => $levels[$i],
                'config' => $config[$levels[$i]],
            ];
        }

        $progress = 0;
        $nextLevelPoints = 0;
        if (!empty($nextLevels)) {
            $nextLevelPoints = $nextLevels[0]['config']['min_points'];
            if ($nextLevelPoints > 0 && $customer) {
                $progress = ($customer->points / $nextLevelPoints) * 100;
            }
        }

        return view('front.vip.index', compact('customer', 'config', 'currentLevel', 'currentLevelConfig', 'nextLevels', 'progress', 'nextLevelPoints'));
    }
}
