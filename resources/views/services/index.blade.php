@extends('layouts.app')

@section('title', 'メニュー一覧')

@section('content')
<h2 class="text-xl font-bold mb-4">メニュー一覧</h2>

<div class="bg-white shadow rounded p-4">
    @if($services->isEmpty())
        <p class="text-gray-500 text-sm">登録されているメニューはありません。</p>
    @else
        <table class="min-w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-2 py-1 text-left">ID</th>
                    <th class="border px-2 py-1 text-left">メニュー名</th>
                    <th class="border px-2 py-1 text-left">所要時間</th>
                    <th class="border px-2 py-1 text-left">料金</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                    <tr>
                        <td class="border px-2 py-1">{{ $service->id }}</td>
                        <td class="border px-2 py-1">{{ $service->service_name }}</td>
                        <td class="border px-2 py-1">{{ $service->duration_minutes }} 分</td>
                        <td class="border px-2 py-1">{{ $service->price }} 円</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

