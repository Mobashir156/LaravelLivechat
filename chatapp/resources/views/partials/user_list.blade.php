@foreach($users as $user)
<a href="{{ route('user.chat',$user->id) }}">
    <div class="user">{{ $user->name }}</div></a>
@endforeach
