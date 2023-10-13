<?php

namespace App\Http\Controllers;

use App\Models\Course;

class PageCourseDetailsController extends Controller
{
    public function __invoke(Course $course)
    {
        if (! $course->released_at) {
            abort(404);
        }

        $course->loadCount('videos');

        return view('pages.course-details', compact('course'));
    }
}
