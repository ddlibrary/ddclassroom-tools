<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subject\CreateSubjectRequest;
use App\Http\Requests\Subject\UpdateSubjectRequest;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->name) {
            $name = $request->name;
            $query->where(function ($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            });
        }

        $subjects = $query->paginate();

        return inertia('Subject/Index', ['subjects' => $subjects]);
    }

    public function create()
    {
        return inertia('Subject/Create');
    }

    public function store(CreateSubjectRequest $request)
    {

        $subject = Subject::create([
            'name' => $request->name,
            'is_active' => $request->is_active == 1 ? true : false,
        ]);

        return redirect('subjects');
    }

    public function edit(Subject $subject)
    {
        return inertia('Subject/Edit', ['subject' => $subject]);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {

        $subject->update([
            'name' => $request->name,
            'is_active' => $request->is_active == 1 ? true : false,
        ]);

        return redirect('subjects');
    }

    public function destroy(Subject $subject)
    {

        $subject->delete();

        return redirect('subjects');
    }
}
