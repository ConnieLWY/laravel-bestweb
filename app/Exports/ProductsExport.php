<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Product::with('category')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Category Name',
            'Description',
            'Price',
            'Stock',
            'Enabled',
            'Created At',
            'Updated At',
        ];
    }

    public function map($product): array
    {
        return [
            $product->id,
            $product->name,
            $product->category->name ?? 'N/A',
            $product->description,
            $product->price,
            $product->stock,
            $product->enabled ? 'Yes' : 'No',
            $product->created_at,
            $product->updated_at,
        ];
    }
}
