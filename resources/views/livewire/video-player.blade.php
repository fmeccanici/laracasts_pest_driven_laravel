<div>
    <h3>{{ $video->title }}</h3>
    <h3>{{ $video->description }}</h3>
    <h3>{{ $video->duration }}min</h3>

    <iframe src="https://player.vimeo.com/video/{{ $video->vimeo_id }}" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>
