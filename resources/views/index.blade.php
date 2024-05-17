@php use Illuminate\Support\Facades\Gate; @endphp
@extends("layouts.main")

@section("title", "Лента постов")

@section("content")
    <div class="container mt-5">
        @include("layouts.theme.change")
        <div class="h1 text-center">Лента постов</div>
        @guest
            <div class="row">
                <div class="border border-secondary border-2 rounded p-3 w-50 m-auto">
                    @include("layouts.users.login")
                </div>
            </div>
        @endguest
        @auth
            <div class="row gap-4 flex-column">
                <a class="btn btn-success text-white m-auto w-25 text-break" href="{{route("users.show", $userId)}}">Мой
                    профиль</a>
                <div class="border border-secondary border-2 rounded p-3 w-75 m-auto">
                    @include("layouts.posts.create")
                </div>
            </div>
        @endauth
        <div class="h3 text-center mt-4">Посты</div>
        <div class="row">
            @foreach($postPaginator->items() as $postDto)
                @php
                    $username = $postDto->username;
                    $canEdit = Gate::allows("edit", $postDto->user_id)
                @endphp
                @include("layouts.posts.card", compact("postDto", "username", "canEdit"))
            @endforeach
            <div class="pagination">
                {{$postPaginator->links()}}
            </div>
        </div>
    </div>
@endsection
