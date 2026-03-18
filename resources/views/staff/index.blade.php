@extends('layouts.app')

@section('title', 'スタッフ一覧')

@section('content')

    <div class="staff-page">

        <div class="staff-table-wrapper">

            @if($staffs->isEmpty())

                <p class="staff-empty">
                    登録されているスタッフはいません。
                </p>

            @else

                <table class="staff-table">

                    <thead>
                        <tr>
                            <th>写真</th>
                            <th>名前</th>
                            <th>専門</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($staffs as $staff)

                            <tr>

                                <td>
                                    <img src="{{ asset("images/staff/staff-{$staff->id}.jpg") }}" alt="{{ $staff->name }}"
                                        class="staff-photo">
                                </td>

                                <td class="staff-name">
                                    {{ $staff->name }}
                                </td>

                                <td>
                                    {{ $staff->specialization }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @endif

        </div>
    </div>

@endsection