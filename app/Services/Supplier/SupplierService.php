<?php

namespace App\Services\Supplier;

use App\Models\Supplier;
use App\Http\Requests\Supplier\SupplierRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class SupplierService
{
    /**
     * Store a new supplier.
     * @param SupplierRequest $request
     * @return Supplier
     */
    public function store(SupplierRequest $request)
    {
        $request->validated();

        DB::beginTransaction();
        try {
            $supplier = Supplier::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();
            return $supplier;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to create supplier. ' . $e->getMessage());
        }
    }

    /**
     * Update an existing supplier.
     * @param Supplier $supplier
     * @param SupplierRequest $request
     * @return Supplier
     */
    public function update(Supplier $supplier, SupplierRequest $request)
    {
        $request->validated();

        DB::beginTransaction();
        try {
            $supplier->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            DB::commit();
            return $supplier;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to update supplier. ' . $e->getMessage());
        }
    }

    /**
     * Delete a supplier.
     * @param Supplier $supplier
     * @return bool|null
     */
    public function destroy(Supplier $supplier)
    {
        DB::beginTransaction();
        try {
            $result = $supplier->delete();
            DB::commit();
            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Failed to delete supplier. ' . $e->getMessage());
        }
    }

    /**
     * Generate DataTables for suppliers.
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateDataTables()
{
    $query = Supplier::query();

    return DataTables::of($query)
        ->addIndexColumn() // <<< tambahin ini biar ada kolom "no"
        ->addColumn('actions', function ($supplier) {
            return '
                <div class="btn-group" role="group">
                    <a href="' . route('admin.suppliers.edit', $supplier) . '" class="btn btn-outline-warning">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <a href="' . route('admin.suppliers.destroy', $supplier) . '" class="btn btn-outline-danger btn-delete">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            ';
        })
        ->rawColumns(['actions'])
        ->make(true);
}


}
