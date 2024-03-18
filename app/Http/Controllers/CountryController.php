<?php

namespace App\Http\Controllers;

use App\Http\Requests\Country\CountryRequest;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {

        $query = Country::query();

        if ($request->name) {
            $query->where('name', 'like', "%$request->name%");
        }

        $countries = $query->paginate();

        return inertia('Country/Index', ['countries' => $countries]);
    }

    public function toggleIsActive(Request $request)
    {
        Country::where('id', $request->id)->update(['is_active' => $request->is_active]);

        return redirect('/countries');
    }

    public function store(CountryRequest $request)
    {
        if ($request->id) {
            Country::where('id', $request->id)->update([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);
        } else {
            Country::insert([
                'name' => $request->name,
                'is_active' => $request->is_active,
            ]);
        }

        return redirect('/countries');
    }

    public function destroy(Country $country)
    {

        $country->delete();

        return redirect('countries');
    }
}
