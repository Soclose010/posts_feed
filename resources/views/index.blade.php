@php use Illuminate\Support\Facades\Gate; @endphp
@extends("layouts.main")

@section("title", "Лента постов")

@section("content")
    <div class="container position-relative mt-5">
        @include("layouts.theme.change")
        @include("layouts.localization.change")
        <div class="h1 text-center">@lang("main.posts_feed")</div>
        @guest
            <div class="row">
                <div class="border border-secondary border-2 rounded p-3 w-50 m-auto">
                    @include("layouts.users.login")
                </div>
            </div>
        @endguest
        @auth
            <div class="row gap-4 flex-column">
                <a class="btn btn-success text-white m-auto w-25 text-break"
                   href="{{route("users.show", $userId)}}">@lang("main.my_profile")</a>
                <div class="border border-secondary border-2 rounded p-3 w-75 m-auto">
                    @include("layouts.posts.create")
                </div>
            </div>
        @endauth
        <div class="h3 text-center mt-4">@lang("main.posts")</div>
        <div class="row">
            <div class="p-3">
                <form class="filter d-flex gap-4 justify-content-center" action="{{route("index")}}" method="get">
                    <input class="form-control w-75" type="text" name="title" placeholder="@lang("main.title")"
                           value="{{request()->get("title")}}">
                    <input class="form-control w-75" type="text" name="username" placeholder="@lang("main.username")"
                           value="{{request()->get("username")}}">
                    <input class="form-control w-75" type="date" name="date" value="{{request()->get("date")}}">
                    <button class="btn btn-primary" type="submit">@lang("main.search")</button>
                    <a class="btn btn-danger" href="{{route("index")}}">@lang("main.reset")</a>
                </form>
                @error("date")
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            @foreach($postPaginator->items() as $postDto)
                @php
                    $username = $postDto->username;
                    $canEdit = Gate::allows("edit", $postDto->user_id)
                @endphp
                @include("layouts.posts.card", compact("postDto", "username", "canEdit"))
            @endforeach
            <div class="pagination">
                {{$postPaginator->onEachSide(2)->withQueryString()->links()}}
            </div>
        </div>
    </div>
@endsection
