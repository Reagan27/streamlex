<?php

namespace Vanguard\Http\Controllers\Web;

use Illuminate\Http\Request;
use Vanguard\Http\Controllers\Controller;
use Vanguard\Region;
use Vanguard\County;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::with('counties')->get();
        $counties = County::all();
        return view('regions.index', compact('regions', 'counties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:regions,name',
            'counties' => 'array',
            'counties.*' => 'exists:counties,id',
        ]);
        $region = Region::create(['name' => $request->name]);
        $region->counties()->sync($request->counties ?? []);
        return redirect()->route('regions.index')->with('success', 'Region created!');
    }

    public function edit($id)
    {
        $region = Region::with('counties')->findOrFail($id);
        $counties = County::all();
        return view('regions.edit', compact('region', 'counties'));
    }

    public function update(Request $request, $id)
    {
        $region = Region::findOrFail($id);
        $request->validate([
            'name' => 'required|string|unique:regions,name,' . $region->id,
            'counties' => 'array',
            'counties.*' => 'exists:counties,id',
        ]);
        $region->update(['name' => $request->name]);
        $region->counties()->sync($request->counties ?? []);
        return redirect()->route('regions.index')->with('success', 'Region updated!');
    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        $region->counties()->detach();
        $region->delete();
        return redirect()->route('regions.index')->with('success', 'Region deleted!');
    }
}
