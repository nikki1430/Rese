@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index_user.css') }}">
@endsection

@section('content')
<div class="table__wrap">
    <table class="user__table">
        <thead>
            <tr>
                <th class="table__header header-no">Id</th>
                <th class="table__header header-name">name</th>
                <th class="table__header header-email">email</th>
                <th class="table__header header-roles">
                    <select name="roles" id="roles" class="select-box__item">
                        <option value="all" class="select-box__option" selected>roles</option>
                        <option value="user" class="select-box__option">user</option>
                        @foreach ($roles as $role)
                        <option value="{{ $role->id }}" class="select-box__option">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="table__data">{{ $user->id }}</td>
                <td class="table__data">{{ $user->name }}</td>
                <td class="table__data data-email">{{ $user->email }}</td>
                @forelse ($user->roles as $role)
                <td class="table__data">{{ $role->name }}</td>
                @empty
                <td class="table__data">user</td>
                @endforelse
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div id="pagination-controls"></div>

{{ $users->links('vendor/pagination/paginate') }}
<script src="{{ asset('js/search_user.js') }}"></script>
@endsection