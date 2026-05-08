<?php

namespace App\Livewire\Admin\Subcategories;

use App\Models\Category;
use App\Models\Family;
use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SubcategoryCreate extends Component
{
    public Collection $families;

    public ?int $subCategoryId = null;

    /**
     * @var array{family_id: int|string|null, category_id: int|string|null, name: string}
     */
    public array $subCategory = [
        'family_id' => '',
        'category_id' => '',
        'name' => '',
    ];

    public function mount(?SubCategory $subCategoryModel = null): void
    {
        $this->families = Family::orderBy('name','desc')->get();

        if ($subCategoryModel && $subCategoryModel->exists) {
            $this->subCategoryId = $subCategoryModel->id;
            $this->subCategory = [
                'family_id' => $subCategoryModel->category?->family_id ?? '',
                'category_id' => $subCategoryModel->category_id ?? '',
                'name' => $subCategoryModel->name ?? '',
            ];
        }
    }

    public function updatedSubCategoryFamilyId(): void
    {
        $this->subCategory['category_id'] = '';
    }

    #[Computed]
    public function categories(): Collection
    {
        if (empty($this->subCategory['family_id'])) {
            return collect();
        }

        return Category::where('family_id', $this->subCategory['family_id'])
            ->orderBy('name','desc')
            ->get();
    }

    public function isEditing(): bool
    {
        return $this->subCategoryId !== null;
    }

    public function save()
    {
        $validated = $this->validate([
            'subCategory.family_id' => 'required|exists:families,id',
            'subCategory.category_id' => 'required|exists:categories,id',
            'subCategory.name' => 'required|string|min:3|max:255',
        ]);

        $payload = [
            'name' => $validated['subCategory']['name'],
            'category_id' => $validated['subCategory']['category_id'],
        ];

        if ($this->isEditing()) {
            SubCategory::findOrFail($this->subCategoryId)->update($payload);
            $text = 'Sub Categoría actualizada exitosamente.';
        } else {
            SubCategory::create($payload);
            $text = 'Sub Categoría creada exitosamente.';
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Éxito!',
            'text' => $text,
        ]);

        return redirect()->route('admin.subCategories.index');
    }

    public function render()
    {
        return view('livewire.admin.subcategories.subcategory-create');
    }
}
