@extends('layouts.app')

@section('content')
<div class="container">
    <h1>予約一覧</h1>

    <a href="{{ route('appointments.create') }}" class="btn btn-primary mb-3">新規予約</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>顧客名</th>
                <th>スタッフ</th>
                <th>日時</th>
                <th>金額</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($appointments as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->customer->name ?? '-' }}</td>
                    <td>{{ $a->staff->name ?? '-' }}</td>
                    <td>{{ $a->appointment_start }}</td>
                    <td>{{ $a->total_price }}円</td>
                    <td>
                        <a href="{{ route('appointments.edit', $a->id) }}" class="btn btn-sm btn-warning">編集</a>
                        <form action="{{ route('appointments.destroy', $a->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
