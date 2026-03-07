@extends('layouts.app')

@section('title', 'スタッフ一覧')

@section('content')
<h2 class="text-xl font-bold mb-4">スタッフ一覧</h2>

<div class="bg-white shadow rounded p-4">
    @if($staffs->isEmpty())
        <p class="text-gray-500 text-sm">登録されているスタッフはいません。</p>
    @else
        <table class="min-w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-2 py-1 text-left">ID</th>
                    <th class="border px-2 py-1 text-left">写真</th>
                    <th class="border px-2 py-1 text-left">名前</th>
                    <th class="border px-2 py-1 text-left">専門</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $staff)
                    <tr>
                        <td class="border px-2 py-1 align-middle">{{ $staff->id }}</td>
                        <td class="border px-2 py-1 align-middle">
                           <img
                           src="{{ asset("images/staff/staff-{$staff->id}.jpg") }}"
                           alt="{{ $staff->name }}"
                           class="w-16 h-16 object-cover rounded-full mx-auto"
>                           >
                        </td>
                        <td class="border px-2 py-1 align-middle">{{ $staff->name }}</td>
                        <td class="border px-2 py-1 align-middle">{{ $staff->specialization }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection


