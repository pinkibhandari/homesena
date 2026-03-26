<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPage;
use Illuminate\Support\Str;

class CmsPagesController extends Controller
{
    public function index(Request $request)
    {
        $pages = CmsPage::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('slug', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.cms_pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms_pages.form', [
            'page' => new CmsPage()
        ]);
    }

    public function edit(CmsPage $cms_page)
    {
        return view('admin.cms_pages.form', [
            'page' => $cms_page
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['slug'] = $this->generateUniqueSlug($data['title']);
        CmsPage::create($data);

        return redirect()->route('admin.cms_pages.index')
                         ->with('success', 'Page created successfully');
    }

    public function update(Request $request, CmsPage $cms_page)
    {
        $data = $this->validateData($request);
        if ($cms_page->title !== $data['title']) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $cms_page->id);
        }
        $cms_page->update($data);

        return redirect()->route('admin.cms_pages.index')
                         ->with('success', 'Page updated successfully');
    }

    public function destroy(CmsPage $cms_page)
    {
        $cms_page->delete();
        return redirect()->route('admin.cms_pages.index')
                         ->with('success', 'Page deleted successfully');
    }

    private function validateData(Request $request)
    {
        return $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'status'  => 'required|in:0,1',
        ]);
    }

    private function generateUniqueSlug($title, $ignoreId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (CmsPage::when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                      ->where('slug', $slug)
                      ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}