<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Helpers\ImageHelpers;
use App\Http\Requests\Cms\UpdateTestimonialRequest;
use App\Models\Cms\Testimonial;
use Yajra\DataTables\Facades\DataTables;

class TestimonialService {
    protected $imageHelper;

    public function __construct() {
        $this->imageHelper = new ImageHelpers('back_assets/img/cms/testimonials/');
    }

    public function store(UpdateTestimonialRequest $request) {
        $request->validated();
        try {
            Testimonial::create(array_merge($request->all(), [
                'customer_photo' => $request->hasFile('customer_photo') ? $this->imageHelper->uploadImage($request, 'customer_photo') : null,
            ]));
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(UpdateTestimonialRequest $request, Testimonial $testimonial)
    {
        $request->validated();
        try {
            $testimonial->update(array_merge($request->all(), [
                'customer_photo' => $request->hasFile('customer_photo') ?
                    $this->imageHelper->updateImage($request, 'customer_photo', $testimonial->customer_photo) :
                    $testimonial->customer_photo,
            ]));
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(Testimonial $testimonial) {
        try {
            $this->imageHelper->deleteImage($testimonial->customer_photo);
            $testimonial->delete();
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data()
    {
        $query = Testimonial::query();

        return DataTables::of($query)
            ->addColumn('actions', function ($item) {
                return '
                    <div class="btn-group">
                        <a href="' . route('admin.cms.testimonials.edit', $item) . '" class="btn btn-outline-warning">
                            <i class="fa fa-edit"></i>
                        </a>
                        <a href="' . route('admin.cms.testimonials.destroy', $item) . '" class="btn btn-outline-danger btn-delete">
                            <i class="fa fa-trash"></i>
                        </a>
                    </div>
                ';
            })
            ->editColumn('customer_photo', function ($item) {
                return $item->customer_photo
                    ? '<img src="' . asset($item->customer_photo) . '" width="100" height="100">'
                    : '-';
            })
            ->editColumn('review', function ($item) {
                return $item->review;
            })
            ->addColumn('order_date', function ($item) {
                return $item->order_date ? \Carbon\Carbon::parse($item->order_date)->format('d-m-Y') : '-';
            })
            ->addColumn('product_name', function ($item) {
                return $item->product_name ?? '-';
            })
            ->rawColumns(['actions', 'customer_photo', 'review'])
            ->make(true);
    }
}
