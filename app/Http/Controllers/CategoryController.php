<?php

namespace App\Http\Controllers;

use App\Http\Filters\CategoryFilter;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, CategoryFilter $filters)
    {
        $query = Category::query()->orderBy('name');

        $filtered = $filters->apply($query, ['name', 'slug']);

        $perPage = (int) $request->input('per_page', 15);

        $categories = $perPage > 0
            ? $filtered->paginate($perPage)->appends($request->query())
            : $filtered->get();

        return response()->json(['data' => CategoryResource::collection($categories)], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?? $validated['name']);

        $category = Category::create($validated);

        return response()->json([
            'data' => CategoryResource::make($category),
            'message' => 'Category created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json(['data' => CategoryResource::make($category)], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

        $validated['slug'] = $this->makeUniqueSlug($validated['slug'] ?? $validated['name'], $category);

        $category->update($validated);

        return response()->json(['data' => CategoryResource::make($category), 'message' => 'Category updated successfully'],201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 204);
    }

    private function makeUniqueSlug(string $value, ?Category $ignore = null): string
    {
        $baseSlug = Str::slug($value);
        $slug = $baseSlug;
        $counter = 1;

        while ($this->slugExists($slug, $ignore)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    private function slugExists(string $slug, ?Category $ignore = null): bool
    {
        return Category::query()
            ->where('slug', $slug)
            ->when($ignore, fn ($query) => $query->where('id', '!=', $ignore->id))
            ->exists();
    }
}
