<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subCategories = SubCategory::orderBy('id', 'desc')
            ->with('category.family')
            ->paginate(10);

        return view('admin.subCategories.index', compact('subCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.subCategories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * Nota: la creación se realiza desde el componente Livewire
     * `App\Livewire\Admin\Subcategories\SubcategoryCreate`. Este método
     * se mantiene por compatibilidad con la ruta resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        SubCategory::create($validated);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Sub Categoría creada exitosamente.',
        ]);

        return redirect()->route('admin.subCategories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SubCategory $subCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SubCategory $subCategory)
    {
        return view('admin.subCategories.edit', compact('subCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SubCategory $subCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SubCategory $subCategory)
    {
        if ($subCategory->products()->count() > 0) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se puede eliminar la familia porque tiene productos asociados.'
            ]);
            return redirect()->route('admin.families.index');
        }
        //
        $subCategory->delete($subCategory);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Sub Categoría eliminada exitosamente.'
        ]);
        return redirect()->route('admin.subCategories.index');
    }
}
