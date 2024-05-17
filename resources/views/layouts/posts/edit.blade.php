@extends("layouts.main")

@section("title", "Редактирование поста")

@section("content")
    <div class="container d-flex flex-column justify-content-center p-4 border border-secondary border-2 rounded mt-4">
        <div class="h2 text-center">Редактирование поста</div>
        <form class="d-flex flex-column" method="post" action="{{route("posts.update", $postDto->id)}}">
            @csrf
            @method("PUT")
            <div class="mb-3">
                <label for="title" class="form-label">Заголовок</label>
                <input type="text" class="@error("title") is-invalid @enderror form-control" id="title"
                       placeholder="Заголовок" name="title" value="{{$postDto->title}}" required>
                @error("title")
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="body" class="form-label">Текст поста</label>
                <textarea class="@error("body") is-invalid @enderror form-control post__textarea post__textarea-tall"
                          placeholder="Текст поста" id="body"
                          name="body"
                >{{$postDto->body}}</textarea>
                @error("body")
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="w-25 m-auto btn btn-primary mb-3">Изменить</button>
        </form>
    </div>
@endsection
