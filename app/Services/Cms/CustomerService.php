<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Http\Requests\Cms\CustomerRequest;
use App\Models\Customer;
use Yajra\DataTables\Facades\DataTables;

class CustomerService
{
    public function store(CustomerRequest $request)
    {
        $request->validated();
        try {
            Customer::create($request->all());
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $request->validated();
        try {
            $data = $request->except('password');
            if ($request->filled('password')) {
                $data['password'] = $request->password;
            }
            $customer->update($data);
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data()
    {
        $customers = Customer::select(['id', 'name', 'phone', 'email', 'address']);

        return DataTables::of($customers)
            ->addColumn('actions', function ($customer) {
                return '
                    <div class="btn-group">
                        <a href="' . route('admin.cms.customers.edit', $customer) . '" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                        <a href="' . route('admin.cms.customers.destroy', $customer) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
                    </div>
                ';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
