@extends("layouts.main")

@section("title", $postDto->title)

@section("content")
    <div class="container d-flex justify-content-center">
        <div class="row mt-5 w-75 border border-secondary border-2 rounded mb-4 p-2">
            <div class="d-flex flex-column gap-2">
                <div class="h2 text-center">{{$postDto->title}}</div>
                <div class="text-left">
                    <span class="fw-bold">@lang("main.author"): <a
                            class="text-left text-decoration-none link-secondary fw-normal"
                            href="{{route("users.show", $postDto->user_id)}}">
                        {{$postDto->username}}
                    </a>
                    </span>
                </div>
                <div class="text-center">{{$postDto->body}}</div>
                <div class="text-left"><span
                        class="fw-bold">@lang("main.created_at"): </span>{{$postDto->created_at->toDateString()}} {{$postDto->created_at->toTimeString()}}
                </div>
                <div class="text-left"><span
                        class="fw-bold">@lang("main.updated_at"): </span>{{$postDto->updated_at->toDateString()}} {{$postDto->updated_at->toTimeString()}}
                </div>
                <a class="btn btn-info text-white m-auto mt-3" href="{{route("index")}}">@lang("main.home")</a>
            </div>
        </div>
    </div>
@endsection
