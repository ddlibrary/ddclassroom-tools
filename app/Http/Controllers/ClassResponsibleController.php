<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassResponsible\ClassResponsibleRequest;
use App\Models\ClassResponsible;
use App\Models\SubGrade;
use App\Models\User;
use App\Models\Year;
use Illuminate\Http\Request;

class ClassResponsibleController extends Controller
{
    // Result list
    public function index(Request $request)
    {
        $query = ClassResponsible::query()->with(['subGrade:id,full_name', 'teacher:id,name']);

        if ($request->sub_grade_id) {
            $query->where('sub_grade_id', $request->sub_grade_id);
        }

        if ($request->year) {
            $query->where('year', $request->year);
        }

        if ($request->teacher_id) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $classResponsibles = $query->orderBy('year', 'desc')->paginate();
        $subGrades = SubGrade::whereIsActive(true)->get();
        $years = Year::all(['id', 'name']);
        $teachers = User::where('user_type_id', 2)->get(['id', 'name']);

        return inertia('ClassResponsible/Index', ['classResponsibles' => $classResponsibles, 'subGrades' => $subGrades, 'years' => $years, 'teachers' => $teachers]);
    }

    // Toggle result state
    public function toggleIsActive(Request $request)
    {
        SubGrade::where('id', $request->id)->update(['is_active' => $request->is_active]);

        return redirect('class-responsible');
    }

    // Store or update result
    public function store(ClassResponsibleRequest $request)
    {

        $data = [
            'teacher_id' => $request->teacher_id,
            'year' => $request->year,
            'sub_grade_id' => $request->sub_grade_id,
        ];

        $request->id ? ClassResponsible::where('id', $request->id)->update($data) : ClassResponsible::insert($data);

        return redirect('class-responsible');
    }

    // Delete result
    public function destroy(ClassResponsible $classResponsible)
    {
        $classResponsible->delete();

        return redirect('class-responsible');
    }
}
