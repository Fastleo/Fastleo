<?php

namespace Fastleo\Fastleo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function setting(Request $request)
    {
        if ($request->isMethod('post')) {
            foreach ($request->except(['_token']) as $key => $value) {
                FastleoSetting::updateOrInsert(
                    ['key' => $key], ['value' => $value]
                );
            }
        }

        $setting = FastleoSetting::get()->keyBy('key')->map(function ($item) {
            return $item->value;
        });

        return view('fastleo::setting', [
            'setting' => $setting
        ]);
    }
}