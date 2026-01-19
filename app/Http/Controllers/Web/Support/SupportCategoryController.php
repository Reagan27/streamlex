<?php

namespace Vanguard\Http\Controllers\Web\Support;

use Vanguard\Http\Controllers\Controller;
use Vanguard\IssuesCategory;
use Illuminate\Http\Request;

class SupportCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $categories = IssuesCategory::all();
        return view('support.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('support.categories.create');
    }

    /**
     * Store a newly created category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:issues_categories,name',
        ]);

        IssuesCategory::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     *
     * @param  \Vanguard\IssuesCategory  $category
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(IssuesCategory $category)
    {
        return view('support.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Vanguard\IssuesCategory  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, IssuesCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:issues_categories,name,' . $category->id,
        ]);
    
        $category->update(['name' => $request->input('name')]);
    
        return redirect()->route('support.manage')->with('success', 'Category updated successfully.');
    }
    

    /**
     * Remove the specified category from the database.
     *
     * @param  \Vanguard\IssuesCategory  $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(IssuesCategory $category)
    {
        $category->delete();
        return redirect()->route('support.manage')->with('success', 'Category deleted successfully.');
    }
    
}

