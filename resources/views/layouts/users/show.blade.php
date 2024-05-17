@extends("layouts.main")

@section("title", "Профиль $userDto->username")

@section("content")
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <div class="row mt-5 w-50">
            @include("layouts.users.card", ["id" => $userDto->id])
        </div>
        <div class="row mt-5 w-75">
            <div class="h3 text-center mt-4">Посты</div>
            @foreach($posts as $postDto)
                @php($username = $userDto->username)
                @include("layouts.posts.card", compact("postDto", "username"))
            @endforeach
        </div>
    </div>
@endsection
