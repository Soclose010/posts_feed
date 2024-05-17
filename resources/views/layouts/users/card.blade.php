<div class="user__card d-flex flex-column gap-3 border border-secondary border-2 rounded p-3 text-break">
    <div class="h3 text-center">Профиль</div>
    <div><span class="fw-bold">Имя пользователя: </span> {{$userDto->username}}</div>
    @if($canEdit)
        <div><span class="fw-bold">Адрес электронной почты: </span> {{$userDto->email}}</div>
    @endif

    <div class="user__card-buttons d-flex gap-2 justify-content-center align-items-center">
        <a class="btn btn-info text-white" href="{{route("index")}}">На главную</a>
        @if($canEdit)
            <a class="btn btn-success text-white" href="{{route("users.edit", $id)}}">Редактировать профиль</a>
            <form class="" action="{{route("auth.logout")}}" method="post">
                @csrf
                <button class="btn btn-danger" type="submit">Выйти</button>
            </form>
        @endif
    </div>

</div>
