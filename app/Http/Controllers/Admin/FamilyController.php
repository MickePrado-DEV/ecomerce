<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $families = Family::orderBy('id', 'desc')->paginate();
        return view('admin.families.index', compact('families'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.families.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Family::create($request->all());

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Familia creada exitosamente.'
        ]);

        return redirect()->route('admin.families.index')
            ;

    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        //
        return view('admin.families.edit', compact('family'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $family->update($request->all());
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Familia editada exitosamente.'
        ]);
        return redirect()->route('admin.families.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {

        if($family->categories()->count() > 0) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => '¡Error!',
                'text' => 'No se puede eliminar la familia porque tiene productos asociados.'
            ]);
            return redirect()->route('admin.families.index');
        }
        //
        $family->delete($family);
        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => 'Familia eliminada exitosamente.'
        ]);
        return redirect()->route('admin.families.index');
    }
}
