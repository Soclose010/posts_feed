@extends("layouts.main")

@section("title", __("main.edit_post"))

@section("content")
    <div class="container d-flex flex-column justify-content-center p-4 border border-secondary border-2 rounded mt-4">
        <div class="h2 text-center">@lang("main.edit_post")</div>
        <form class="d-flex flex-column" method="post" action="{{route("posts.update", $postDto->id)}}">
            @csrf
            @method("PUT")
            <div class="mb-3">
                <label for="title" class="form-label">@lang("main.title")</label>
                <input type="text" class="@error("title") is-invalid @enderror form-control" id="title"
                       placeholder="@lang("main.title")" name="title" value="{{$postDto->title}}" required>
                @error("title")
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">@lang("main.body")</label>
                <textarea class="@error("body") is-invalid @enderror form-control post__textarea post__textarea-tall"
                          placeholder="@lang("main.body")" id="body"
                          name="body"
                >{{$postDto->body}}</textarea>
                @error("body")
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-25 m-auto btn btn-primary mb-3">@lang("main.edit")</button>
        </form>
    </div>
@endsection
