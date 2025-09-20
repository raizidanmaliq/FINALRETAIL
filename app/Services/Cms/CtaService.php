<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Helpers\ImageHelpers;
use App\Http\Requests\Cms\UpdateCtaRequest;
use App\Models\Cms\Cta;
use Yajra\DataTables\Facades\DataTables;

class CtaService
{
    protected $imageHelper;

    public function __construct()
    {
        $this->imageHelper = new ImageHelpers('back_assets/img/cms/ctas/');
    }

    public function store(UpdateCtaRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $isActive = isset($validatedData['is_active']) && $validatedData['is_active'];

            // If the new CTA is set to active, deactivate any other active CTA
            if ($isActive) {
                Cta::where('is_active', true)->update(['is_active' => false]);
            }

            // Create the new CTA
            $cta = Cta::create(array_merge($validatedData, [
                'image' => $this->imageHelper->uploadImage($request, 'image'),
            ]));

            // Ensure at least one CTA is active after creation
            if (!Cta::where('is_active', true)->exists()) {
                $cta->update(['is_active' => true]);
            }

        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(UpdateCtaRequest $request, Cta $cta)
    {
        $validatedData = $request->validated();

        try {
            $imageData = $request->hasFile('image') ?
                $this->imageHelper->updateImage($request, 'image', $cta->image) :
                $cta->image;

            // Check if this is the last active CTA and the user is trying to deactivate it
            if ($cta->is_active && isset($validatedData['is_active']) && !$validatedData['is_active']) {
                $activeCtaCount = Cta::where('is_active', true)->count();
                if ($activeCtaCount === 1) {
                    // Prevent deactivation and keep it active
                    $validatedData['is_active'] = true;
                }
            }

            // If the CTA is being updated to active, deactivate any other active CTA
            if (isset($validatedData['is_active']) && $validatedData['is_active']) {
                 Cta::where('is_active', true)
                    ->where('id', '!=', $cta->id)
                    ->update(['is_active' => false]);
            }

            // Update the CTA
            $cta->update(array_merge($validatedData, [
                'image' => $imageData,
            ]));

        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(Cta $cta)
    {
        try {
            // Check if the CTA to be deleted is the last active one
            if ($cta->is_active) {
                $activeCtaCount = Cta::where('is_active', true)->count();
                if ($activeCtaCount === 1) {
                    throw new \Exception('Tidak bisa menghapus CTA aktif terakhir. Silakan aktifkan CTA lain terlebih dahulu.');
                }
            }

            $this->imageHelper->deleteImage($cta->image);
            $cta->delete();
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data(Cta $cta)
    {
        $array = $cta->get(['id', 'title', 'image', 'is_active']);

        $data = [];
        $no = 0;

        foreach ($array as $item) {
            $no++;
            $nestedData['no'] = $no;
            $nestedData['title'] = $item->title;
            $nestedData['image'] = '<img src="' . asset($item->image) . '" width="150" height="80">';
            $nestedData['status'] = $item->is_active
                ? '<span class="badge badge-success">Active</span>'
                : '<span class="badge badge-danger">Inactive</span>';
            $nestedData['action'] = '
                <div class="btn-group">
                    <a href="' . route('admin.cms.ctas.edit', $item) . '" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                    <a href="' . route('admin.cms.ctas.destroy', $item) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
                </div>
            ';

            $data[] = $nestedData;
        }

        return DataTables::of($data)
            ->rawColumns(["action", "image", "status"])
            ->toJson();
    }
}
