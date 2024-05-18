@php use Illuminate\Support\Facades\Gate; @endphp
@extends("layouts.main")

@section("title", __("main.profile") . " $userDto->username")

@section("content")
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <div class="row mt-5 w-50">
            @include("layouts.users.card", ["id" => $userDto->id])
        </div>
        <div class="row mt-5 w-75">
            <div class="h3 text-center mt-4">@lang("main.posts")</div>
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
