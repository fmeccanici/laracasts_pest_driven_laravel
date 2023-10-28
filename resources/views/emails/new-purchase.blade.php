@component('mail::message')
    # Thanks for purchasing {{ $course->title }}

    If this is your first purchase on {{ config('app.name') }}, then a new account was created fr your, and you just need to reset your password.
    Have fun with this new course.

    @component('mail::button', ['url' => route('login')])
        Login
    @endcomponent

@endcomponent
