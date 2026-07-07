<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use DatabaseTransactions;

    public function test_guest_is_redirected_from_profile_edit_page(): void
    {
        $this->get(route('profile.edit'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_customer_can_view_profile_edit_page(): void
    {
        $customer = new Customer([
            'name' => '山田 花子',
            'name_kana' => 'ヤマダ ハナコ',
            'phone_number' => '090-1234-5678',
            'email' => 'hanako@example.com',
        ]);
        $customer->id = 1;

        $this->actingAs($customer)
            ->get(route('profile.edit'))
            ->assertOk()
            ->assertSee('ユーザー情報編集')
            ->assertSee('ヤマダ ハナコ')
            ->assertSee('090-1234-5678');
    }

    public function test_authenticated_customer_can_update_profile(): void
    {
        $customer = Customer::create([
            'name' => '山田 花子',
            'name_kana' => 'ヤマダ ハナコ',
            'phone_number' => '090-1234-5678',
            'email' => 'before@example.com',
            'password' => 'password',
        ]);

        $this->actingAs($customer)
            ->put(route('profile.update'), [
                'name' => '佐藤 花子',
                'name_kana' => 'サトウ ハナコ',
                'phone_number' => '080-9876-5432',
                'email' => 'after@example.com',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('appointments.mypage'));

        $customer->refresh();

        $this->assertSame('佐藤 花子', $customer->name);
        $this->assertSame('サトウ ハナコ', $customer->name_kana);
        $this->assertSame('080-9876-5432', $customer->phone_number);
        $this->assertSame('after@example.com', $customer->email);
    }
}
