<?php

namespace App\Http\Controllers;

use App\Http\Requests\Result\ResultRequest;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    // Result list
    public function index(Request $request)
    {
        $query = Result::query();

        if ($request->name) {
            $name = $request->name;
            $query->where(function ($query) use ($name) {
                $query->whereAny(['name', 'result', 'from', 'to'], 'like', "%$name%");
            });
        }

        $results = $query->paginate();

        return inertia('Result/Index', ['results' => $results]);
    }

    // Toggle result state
    public function toggleIsActive(Request $request)
    {
        Result::where('id', $request->id)->update(['is_active' => $request->is_active]);

        return redirect('/results');
    }

    // Store or update result
    public function store(ResultRequest $request)
    {
        if ($request->id) {
            Result::where('id', $request->id)->update([
                'name' => $request->name,
                'from' => $request->from,
                'to' => $request->to,
                'result' => $request->result,
                'is_active' => $request->is_active,
            ]);
        } else {
            Result::insert([
                'name' => $request->name,
                'from' => $request->from,
                'to' => $request->to,
                'result' => $request->result,
                'is_active' => $request->is_active,
            ]);
        }

        return redirect('/results');
    }

    // Delete result
    public function destroy(Result $result)
    {
        $result->delete();

        return redirect('results');
    }
}
