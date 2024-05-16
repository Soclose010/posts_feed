@php use Illuminate\Support\Facades\Auth; @endphp
<div class="d-flex flex-column gap-3">
    <div class="h3 text-center">Мой профиль</div>
    <div><span class="fw-bold">Имя пользователя: </span> {{Auth::user()->username}}</div>
    <div><span class="fw-bold">Адрес электронной почты: </span> {{Auth::user()->email}}</div>
    <div class="d-flex gap-2 justify-content-center">
        <a class="btn btn-info text-white" href="{{route("users.edit", Auth::id())}}">Редактировать профиль</a>
        <form class="" action="{{route("auth.logout")}}" method="post">
            @csrf
            <button class="btn btn-danger" type="submit">Выйти</button>
        </form>
    </div>
</div>
