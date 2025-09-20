<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Helpers\ImageHelpers;
use App\Http\Requests\Cms\UpdateBannerRequest;
use App\Models\Cms\Banner;
use Yajra\DataTables\Facades\DataTables;

class BannerService {
    protected $imageHelper;

    public function __construct() {
        $this->imageHelper = new ImageHelpers('back_assets/img/cms/banners/');
    }

    public function store(UpdateBannerRequest $request) {
        $validatedData = $request->validated();
        try {
            // Check if the new banner is set to be active
            if (isset($validatedData['is_active']) && $validatedData['is_active']) {
                $activeBannersCount = Banner::where('is_active', true)->count();

                // If the count is already 3 or more, deactivate the oldest one
                if ($activeBannersCount >= 3) {
                    $oldestActiveBanner = Banner::where('is_active', true)
                        ->orderBy('created_at', 'asc')
                        ->first();

                    if ($oldestActiveBanner) {
                        $oldestActiveBanner->update(['is_active' => false]);
                    }
                }
            }

            // Create the new banner
            Banner::create(array_merge($validatedData, [
                'image' => $this->imageHelper->uploadImage($request, 'image'),
            ]));

        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(UpdateBannerRequest $request, Banner $banner) {
        $validatedData = $request->validated();

        try {
            $imageData = $request->hasFile('image') ?
                $this->imageHelper->updateImage($request, 'image', $banner->image) :
                $banner->image;

            // Check if the banner is being updated to be inactive and if it's the last active one
            if (isset($validatedData['is_active']) && !$validatedData['is_active']) {
                $activeBannersCount = Banner::where('is_active', true)->count();
                // If there's only one active banner (the current one) and it's being deactivated, prevent the action
                if ($activeBannersCount === 1 && $banner->is_active) {
                    // Revert the 'is_active' status to true to keep it active
                    $validatedData['is_active'] = true;
                }
            }

            // If the banner is being updated to be active and is currently inactive
            if (isset($validatedData['is_active']) && $validatedData['is_active'] && !$banner->is_active) {
                // Count active banners, excluding the current one
                $activeBannersCount = Banner::where('is_active', true)
                    ->where('id', '!=', $banner->id)
                    ->count();

                // If the count is already 3, deactivate the oldest one
                if ($activeBannersCount >= 3) {
                    $oldestActiveBanner = Banner::where('is_active', true)
                        ->orderBy('updated_at', 'asc')
                        ->first();

                    if ($oldestActiveBanner) {
                        $oldestActiveBanner->update(['is_active' => false]);
                    }
                }
            }

            // Update the banner
            $banner->update(array_merge($validatedData, [
                'image' => $imageData,
            ]));
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(Banner $banner) {
        try {
            // Get the count of active banners before deletion
            $activeBannersCount = Banner::where('is_active', true)->count();

            // If the banner to be deleted is active and it's the last active one
            if ($banner->is_active && $activeBannersCount === 1) {
                // Return an error or throw an exception since you can't delete the last active banner
                throw new \Exception('Tidak bisa menghapus banner aktif terakhir.');
            }

            $this->imageHelper->deleteImage($banner->image);
            $banner->delete();
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data(object $banner) {
        // This method remains unchanged as it only formats the output
        $array = $banner->get(['id', 'title', 'image', 'is_active']);

        $data = [];
        $no = 0;

        foreach ($array as $item) {
            $no++;
            $nestedData['no'] = $no;
            $nestedData['title'] = $item->title;
            $nestedData['image'] = '<img src="' . asset($item->image) . '" width="150" height="80">';
            $nestedData['status'] = $item->is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Tidak Aktif</span>';
            $nestedData['actions'] = '
                <div class="btn-group">
                    <a href="' . route('admin.cms.banners.edit', $item) . '" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                    <a href="' . route('admin.cms.banners.destroy', $item) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
                </div>
            ';

            $data[] = $nestedData;
        }

        return DataTables::of($data)
            ->rawColumns(["actions", "image", "status"])
            ->toJson();
    }
}
