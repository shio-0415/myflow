<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        return view('settings.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'daily_work_minutes' => ['required', 'integer', 'min:30', 'max:720', 'multiple_of:30'],
        ]);

        $user = auth()->user();
        $user->daily_work_minutes = $validated['daily_work_minutes'];
        $user->save();

        return redirect()->route('settings.edit')->with('status', '設定を保存しました。');
    }
}
