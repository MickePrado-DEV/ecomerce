<?php

namespace App\Livewire\Admin\Products;

use App\Models\Category;
use App\Models\Family;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;

    public Collection $families;

    public ?int $product_id = null;

    public string $family_id = '';

    public string $category_id = '';

    public $image = null;

    public bool $hideProductStock = false;

    public array $product = [
        'sku' => '',
        'name' => '',
        'description' => '',
        'price' => '',
        'sub_category_id' => '',
        'image_path' => '',
        'stock' => 0,
    ];

    public function mount(?Product $productModel = null): void
    {
        $this->families = Family::all();

        if ($productModel && $productModel->exists) {
            $this->product_id = (int) $productModel->id;

            $this->product = [
                'sku' => (string) $productModel->sku,
                'name' => (string) $productModel->name,
                'description' => (string) $productModel->description,
                'price' => $productModel->price,
                'sub_category_id' => (string) $productModel->sub_category_id,
                'image_path' => (string) ($productModel->image_path ?? ''),
                'stock' => (int) ($productModel->stock ?? 0),
            ];

            $subCategory = SubCategory::with('category.family')->find($productModel->sub_category_id);

            if ($subCategory) {
                $this->category_id = (string) $subCategory->category_id;
                $this->family_id = (string) $subCategory->category->family_id;
            }

            $this->refreshProductStockVisibility();
        } else {
            $this->product['stock'] = 0;
        }
    }

    #[On('product-stock-visibility-changed')]
    public function refreshProductStockVisibility(): void
    {
        if (! $this->product_id) {
            return;
        }

        $product = Product::withCount(['variants', 'options'])->find($this->product_id);

        $this->hideProductStock = $product
            && ($product->variants_count > 0 || $product->options_count > 0);
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
        if (empty($this->family_id)) {
            return collect();
        }

        return Category::where('family_id', $this->family_id)->get();
    }

    #[Computed]
    public function subcategories(): Collection
    {
        if (empty($this->category_id)) {
            return collect();
        }

        return SubCategory::where('category_id', $this->category_id)->get();
    }

    #[Computed]
    public function showStockField(): bool
    {
        return $this->product_id !== null && ! $this->hideProductStock;
    }

    private function rulesForSave(): array
    {
        $rules = [
            'family_id' => 'required',
            'category_id' => 'required',
            'product.sub_category_id' => 'required|exists:sub_categories,id',
            'product.sku' => 'required|unique:products,sku,'.($this->product_id ?? 'NULL').',id',
            'product.name' => 'required|max:255',
            'product.description' => 'required',
            'product.price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:2048',
        ];

        if ($this->product_id && ! $this->hideProductStock) {
            $rules['product.stock'] = 'required|integer|min:0';
        }

        return $rules;
    }

    public function save()
    {
        $this->validate($this->rulesForSave());

        $payload = $this->product;

        if ($this->product_id) {
            if (! $this->hideProductStock) {
                $payload['stock'] = (int) ($this->product['stock'] ?? 0);
            } else {
                unset($payload['stock']);
            }
        } else {
            unset($payload['stock']);
        }

        if ($this->image) {
            if ($this->product_id && ! empty($this->product['image_path'])) {
                Storage::disk('public')->delete($this->product['image_path']);
            }

            $payload['image_path'] = $this->image->store('products', 'public');
        }

        if ($this->product_id) {
            Product::findOrFail($this->product_id)->update($payload);
            $msg = 'Producto actualizado.';
        } else {
            $created = Product::create(array_merge($payload, ['stock' => 0]));
            $this->product_id = (int) $created->id;
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
