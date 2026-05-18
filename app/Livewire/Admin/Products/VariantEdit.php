<?php

namespace App\Livewire\Admin\Products;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class VariantEdit extends Component
{
    use WithFileUploads;

    public Product $product;

    public Variant $variant;

    public string $sku = '';

    public int $stock = 0;

    public $image = null;

    public function mount(Product $product, Variant $variant): void
    {
        abort_unless($variant->product_id === $product->id, 404);

        $this->product = $product;
        $this->variant = $variant->load('features.option');
        $this->sku = (string) ($variant->sku ?? '');
        $this->stock = (int) ($variant->stock ?? 0);
    }

    public function save(): void
    {
        $this->validate([
            'sku' => 'required|string|max:255|unique:variants,sku,'.$this->variant->id,
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'sku' => $this->sku,
            'stock' => $this->stock,
        ];

        if ($this->image) {
            if ($this->variant->image_path) {
                Storage::disk('public')->delete($this->variant->image_path);
            }

            $data['image_path'] = $this->image->store('variants', 'public');
        }

        $this->variant->update($data);
        $this->variant->refresh();
        $this->image = null;

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Variante actualizada correctamente.',
        ]);

        $this->redirect(route('admin.products.edit', $this->product), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.products.variant-edit');
    }
}
