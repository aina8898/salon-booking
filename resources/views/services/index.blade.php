@extends('layouts.app')

@section('title', 'メニュー一覧')

@section('content')

    @if($services->isEmpty())

        <p class="empty-text">
            登録されているメニューはありません。
        </p>

    @else

        <div class="service-list-wrap">

            <table class="service-table">

                <thead>
                    <tr>
                        <th>メニュー</th>
                        <th>所要時間</th>
                        <th>料金</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($services as $service)

                        <tr>
                            <td class="service-name">
                                {{ $service->service_name }}
                            </td>

                            <td>
                                {{ $service->duration_minutes }} 分
                            </td>

                            <td class="service-price">
                                ¥ {{ number_format($service->price) }}
                            </td>
                        </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    @endif

@endsection