<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'customer' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $customer = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'name_kana' => ['required', 'string', 'max:100'],
            'phone_number' => ['required', 'string', 'max:15', 'regex:/^[0-9-]+$/'],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('customers', 'email')->ignore($customer->id),
            ],
        ], [
            'phone_number.regex' => '携帯番号は半角数字とハイフンで入力してください。',
        ]);

        $customer->update($validated);

        return redirect()
            ->route('appointments.mypage')
            ->with('success', 'ユーザー情報を更新しました。');
    }
}
