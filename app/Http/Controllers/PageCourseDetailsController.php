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

        return view('course-details', compact('course'));
    }
}
