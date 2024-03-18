<?php

namespace App\Http\Controllers;

use App\Http\Requests\Year\YearRequest;
use App\Models\Year;
use Illuminate\Http\Request;

class YearController extends Controller
{
    public function index(Request $request)
    {

        $query = Year::query();

        if ($request->name) {
            $query->where('name', 'like', "%$request->name%");
        }

        $years = $query->paginate();

        return inertia('Year/Index', ['years' => $years]);
    }

    public function store(YearRequest $request)
    {
        if ($request->id) {
            Year::where('id', $request->id)->update([
                'name' => $request->name,
            ]);
        } else {
            Year::insert([
                'name' => $request->name,
            ]);
        }

        return redirect('/years');
    }

    public function destroy(Year $year)
    {
        $year->delete();

        return redirect('years');
    }
}
