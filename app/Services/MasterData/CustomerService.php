<?php

namespace App\Services\MasterData;

use App\Helpers\ErrorHandling;
use App\Http\Requests\MasterData\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class CustomerService
{
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'phone'    => 'required',
            'email'    => 'required|email|unique:customers,email',
            'address'  => 'required',
            'password' => 'required|min:8',
        ]);

        try {
            Customer::create([
                'name'     => $request->name,
                'phone'    => $request->phone,
                'email'    => $request->email,
                'address'  => $request->address,
                'password' => $request->password,
            ]);
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $request->validated();

        try {
            $customer->name    = $request->name;
            $customer->phone   = $request->phone;
            $customer->email   = $request->email;
            $customer->address = $request->address;

            if ($request->filled('password')) {
                $customer->password = $request->password;
            }

            $customer->save();
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

    public function data(object $customer)
    {
        $array = $customer->get(['id', 'name', 'phone', 'email', 'address']);

        $data = [];
        $no   = 0;

        foreach ($array as $item) {
            $no++;
            $nestedData['no']      = $no;
            $nestedData['name']    = $item->name;
            $nestedData['phone']   = $item->phone;
            $nestedData['email']   = $item->email;
            $nestedData['address'] = $item->address;
            $nestedData['actions'] = '
                <div class="btn-group">
                    <a href="' . route('admin.master_data.customers.edit', $item) . '" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                    <a href="' . route('admin.master_data.customers.destroy', $item) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
                </div>
            ';

            $data[] = $nestedData;
        }

        return DataTables::of($data)->rawColumns(["actions"])->toJson();
    }
}
