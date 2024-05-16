@extends("layouts.main")

@section("title", "Лента постов")

@section("content")
    <div class="container mt-5">
        <div class="h1 text-center">Лента постов</div>
        @guest
            <div class="row">
                <div class="border border-secondary border-2 rounded p-3 w-50 m-auto">
                    @include("layouts.users.login")
                </div>
            </div>
        @endguest
        @auth
            <div class="row d-flex flex-column gap-4">
                <div class="border border-secondary border-2 rounded p-3 w-50 m-auto">
                    @include("layouts.users.card")
                </div>
                <a class="btn btn-success text-white m-auto w-25" href="{{route("posts.create")}}">Создать пост</a>
            </div>
        @endauth

    </div>
@endsection
