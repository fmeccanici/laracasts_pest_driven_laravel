@guest()
    <a href="{{ route('login') }}">Login</a>
@else
    <form method="post" action="{{ route('logout') }}">Logout</form>
@endguest
@foreach($courses as $course)
    <h2>{{ $course->title }}</h2>
    <p>{{ $course->description }}</p>
@endforeach
