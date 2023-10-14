<div>
    <div>
        <h3>{{ $video->title }}</h3>
        <h3>{{ $video->description }}</h3>
        <h3>({{ $video->getReadableDuration() }})</h3>

        <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen
                allowfullscreen></iframe>
    </div>

    <ul>
        @foreach($courseVideos as $courseVideo)
            <li>
                <a href="{{ route('page.course-videos', $courseVideo) }}">
                    {{ $courseVideo->title }}
                </a>
            </li>
        @endforeach
    </ul>

</div>
