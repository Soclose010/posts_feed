@php use Illuminate\Support\Facades\Auth; @endphp
@extends("layouts.main")

@section("title", "Редактирование")

@section("content")
    <div class="container d-flex justify-content-center">
        <div class="row mt-5 w-50">
            <div class="row">
                <div class="h2 text-center">Редактирование</div>
                <form class="d-flex flex-column mb-3" action="{{route("users.update", Auth::id())}}" method="post">
                    @csrf
                    @method("PUT")
                    <div class="mb-3">
                        <label for="username" class="form-label">Новое имя пользователя</label>
                        <input type="text" class="form-control @error("username") is-invalid @enderror" id="username"
                               placeholder="Новое имя пользователя" name="username" value="{{old("username")}}"
                        >
                        @error('username')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary m-auto">Изменить</button>
                </form>
                <form class="d-flex flex-column mb-3" action="{{route("users.update", Auth::id())}}" method="post">
                    @csrf
                    @method("PUT")
                    <div class="mb-3">
                        <label for="email" class="form-label">Новый адрес электронной почты</label>
                        <input type="email" class="form-control @error("email") is-invalid @enderror" id="email"
                               placeholder="Новый адрес электронной почты" name="email" value="{{old("email")}}"
                        >
                        @error('email')
                        <div class="alert alert-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary m-auto">Изменить</button>
                </form>
                <form class="d-flex flex-column mb-3" action="{{route("users.update", Auth::id())}}" method="post">
                    @csrf
                    @method("PUT")
                    <div class="mb-3">
                        <label for="password" class="form-label">Новый пароль</label>
                        <input type="password" class="form-control @error("password") is-invalid @enderror"
                               id="password"
                               placeholder="Пароль" name="password">
                        @error('password')
                        <div class="alert alert-danger mt-2">
                            @foreach($errors->get("password") as $error)
                                <div>{{ $error }}</div>
                            @endforeach</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_conf" class="form-label">Подтвердите новый пароль</label>
                        <input type="password" class="form-control"
                               id="password_conf"
                               placeholder="Пароль" name="password_confirmation">
                    </div>
                    <button type="submit" class="btn btn-primary m-auto">Изменить</button>
                    <a class="btn btn-info text-white m-auto mt-3" href="{{route("index")}}">Назад</a>
                </form>
            </div>
        </div>
    </div>
@endsection
