@extends("layouts.main")

@section("title", "Регистрация")

@section("content")
    <div class="container d-flex justify-content-center">
        <div class="row mt-5 w-50 ">
            <div class="h2 text-center">Регистрация</div>
            <form class="d-flex flex-column border border-secondary border-2 rounded mb-4 p-4"
                  action="{{route("users.store")}}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="username" class="form-label">Имя пользователя</label>
                    <input type="text" class="form-control @error("username") is-invalid @enderror" id="username"
                           placeholder="Имя пользователя" name="username" value="{{old("username")}}" required>
                    @error('username')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Адрес электронной почты</label>
                    <input type="email" class="form-control @error("email") is-invalid @enderror" id="email"
                           placeholder="Адрес электронной почты" name="email" value="{{old("email")}}" required>
                    @error('email')
                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control @error("password") is-invalid @enderror" id="password"
                           placeholder="Пароль" name="password" required>
                    @error('password')
                    <div class="alert alert-danger mt-2">
                        @foreach($errors->get("password") as $error)
                            <div>{{ $error }}</div>
                        @endforeach</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password_conf" class="form-label">Подтвердите пароль</label>
                    <input type="password" class="form-control @error("password_confirmation") is-invalid @enderror"
                           id="password_conf" placeholder="Пароль" name="password_confirmation" required>
                </div>
                <button type="submit" class="btn btn-primary m-auto">Регистрация</button>
                <a class="btn btn-info text-white m-auto mt-3" href="{{route("index")}}">На главную</a>
            </form>
        </div>
    </div>
@endsection
