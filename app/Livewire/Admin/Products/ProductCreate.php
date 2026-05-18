<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;

    public Collection $families;

    public $product_id; // Si existe, estamos editando

    // Variables de control de jerarquía
    public $family_id = '';

    public $category_id = '';

    // Para la nueva imagen
    public $image;

    public array $product = [
        'sku' => '',
        'name' => '',
        'description' => '',
        'price' => '',
        'sub_category_id' => '',
        'image_path' => '',
    ];

    public function mount(?Product $productModel = null): void
    {
        $this->families = Family::all();

        if ($productModel && $productModel->exists) {
            $this->product_id = $productModel->id;
            $this->product = $productModel->toArray();

            // Lógica para pre-cargar los select de jerarquía
            $subCategory = SubCategory::with('category.family')->find($this->product['sub_category_id']);

            if ($subCategory) {
                $this->category_id = $subCategory->category_id;
                $this->family_id = $subCategory->category->family_id;
            }
        }
    }

    public function updatedFamilyId(): void
    {
        $this->category_id = '';
        $this->product['sub_category_id'] = '';
    }

    public function updatedCategoryId(): void
    {
        $this->product['sub_category_id'] = '';
    }

    #[Computed]
    public function categories(): Collection
    {
        return Category::where('family_id', $this->family_id)->get();
    }

    #[Computed]
    public function subcategories(): Collection
    {
        return SubCategory::where('category_id', $this->category_id)->get();
    }

    public function save()
    {
        $rules = [
            'family_id' => 'required',
            'category_id' => 'required',
            'product.sub_category_id' => 'required|exists:sub_categories,id',
            'product.sku' => 'required|unique:products,sku,'.$this->product_id,
            'product.name' => 'required|max:255',
            'product.description' => 'required',
            'product.price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ];

        $this->validate($rules);

        // Manejo de la imagen
        if ($this->image) {
            // Si estamos editando y ya había una imagen, borrar la anterior
            if ($this->product_id && $this->product['image_path']) {
                Storage::disk('public')->delete($this->product['image_path']);
            }
            $this->product['image_path'] = $this->image->store('products', 'public');
        }

        if ($this->product_id) {
            Product::findOrFail($this->product_id)->update($this->product);
            $msg = 'Producto actualizado.';
        } else {
            Product::create($this->product);
            $msg = 'Producto creado.';
        }

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => $msg,
        ]);

        return redirect()->route('admin.products.index');
    }

    public function render()
    {
        return view('livewire.admin.products.product-create');
    }
}
