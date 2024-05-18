<div class="h2 text-center">@lang("main.create_post")</div>
<form class="d-flex flex-column m-auto" method="post" action="{{route("posts.store")}}">
    @csrf
    <div class="mb-3">
        <label for="title" class="form-label">@lang("main.title")</label>
        <input type="text" class="@error("title") is-invalid @enderror form-control" id="title"
               placeholder="@lang("main.title")" name="title" value="{{old("title")}}" required>
        @error("title")
        <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="body" class="form-label">@lang("main.body")</label>
        <textarea class="@error("body") is-invalid @enderror form-control post__textarea"
                  placeholder="@lang("main.body")"
                  id="body"
                  name="body"
        >{{old("body")}}</textarea>
        @error("body")
        <div class="alert alert-danger mt-2">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="w-25 m-auto btn btn-primary mb-3 text-break">@lang("main.create")</button>
</form>




