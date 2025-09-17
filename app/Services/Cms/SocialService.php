<?php

namespace App\Services\Cms;

use App\Helpers\ErrorHandling;
use App\Helpers\ImageHelpers;
use App\Http\Requests\Cms\UpdateSocialRequest;
use App\Models\Cms\Social;
use Yajra\DataTables\Facades\DataTables;

class SocialService {
    protected $imageHelper;

    public function __construct() {
        $this->imageHelper = new ImageHelpers('back_assets/img/cms/socials/');
    }

    public function store(UpdateSocialRequest $request) {
        $validatedData = $request->validated();

        try {
            $isActive = isset($validatedData['is_active']) && $validatedData['is_active'];

            // Jika entri baru akan aktif, nonaktifkan semua entri yang sudah ada.
            if ($isActive) {
                Social::where('is_active', true)->update(['is_active' => false]);
            }

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePaths[] = $this->imageHelper->uploadFile($image);
                }
            }

            Social::create(array_merge($validatedData, [
                'images' => $imagePaths,
                'is_active' => $isActive,
            ]));
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(UpdateSocialRequest $request, Social $social) {
        $validatedData = $request->validated();

        try {
            $imagePaths = $social->images;
            if ($request->hasFile('images')) {
                // Hapus gambar lama sebelum mengunggah yang baru
                foreach ($social->images as $oldImage) {
                    $this->imageHelper->deleteImage($oldImage);
                }

                $newImagePaths = [];
                foreach ($request->file('images') as $image) {
                    $newImagePaths[] = $this->imageHelper->uploadFile($image);
                }
                $imagePaths = $newImagePaths;
            }

            // Jika entri ini akan aktif, nonaktifkan entri lainnya.
            if (isset($validatedData['is_active']) && $validatedData['is_active']) {
                Social::where('is_active', true)
                    ->where('id', '!=', $social->id)
                    ->update(['is_active' => false]);
            }

            $social->update(array_merge($validatedData, [
                'images' => $imagePaths,
            ]));
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(Social $social) {
        try {
            if ($social->images) {
                foreach ($social->images as $image) {
                    $this->imageHelper->deleteImage($image);
                }
            }
            $social->delete();
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data(object $social) {
    // Mengambil data yang diperlukan, tanpa 'button_text' dan 'button_link'
    $array = $social->get(['id', 'images', 'shopee_link', 'tokopedia_link', 'lazada_link', 'is_active']);

    $data = [];
    $no = 0;

    foreach ($array as $item) {
        $no++;
        $nestedData['no'] = $no;

        $imagesHtml = '';
        if (!empty($item->images)) {
            foreach ($item->images as $imagePath) {
                $imagesHtml .= '<img src="' . asset($imagePath) . '" width="80" height="50" style="margin-right: 5px;">';
            }
        }
        $nestedData['images'] = $imagesHtml;

        // 🔹 Tampilkan hanya tautan e-commerce dalam satu kolom
        $linksHtml = '';
        $linksHtml .= $item->shopee_link ? '<a href="' . $item->shopee_link . '" target="_blank" class="btn btn-sm btn-info mb-1">Shopee</a><br>' : '';
        $linksHtml .= $item->tokopedia_link ? '<a href="' . $item->tokopedia_link . '" target="_blank" class="btn btn-sm btn-info mb-1">Tokopedia</a><br>' : '';
        $linksHtml .= $item->lazada_link ? '<a href="' . $item->lazada_link . '" target="_blank" class="btn btn-sm btn-info">Lazada</a>' : '';

        $nestedData['links'] = $linksHtml ?: '-';

        $nestedData['status'] = $item->is_active
            ? '<span class="badge badge-success">Aktif</span>'
            : '<span class="badge badge-danger">Tidak Aktif</span>';
        $nestedData['actions'] = '
            <div class="btn-group">
                <a href="' . route('admin.cms.socials.edit', $item) . '" class="btn btn-outline-warning"><i class="fa fa-edit"></i></a>
                <a href="' . route('admin.cms.socials.destroy', $item) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
            </div>
        ';

        $data[] = $nestedData;
    }

    return DataTables::of($data)
        ->rawColumns(["actions", "images", "links", "status"])
        ->toJson();
}
}
