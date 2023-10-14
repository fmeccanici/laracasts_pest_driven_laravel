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
                @if($this->isCurrentVideo($courseVideo))
                    {{ $courseVideo->title }}
                @else
                    <a href="{{ route('page.course-videos', $courseVideo) }}">
                        {{ $courseVideo->title }}
                    </a>
                @endif
            </li>
        @endforeach
    </ul>

</div>
