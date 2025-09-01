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
        $request->validated();

        try {
            Banner::create(array_merge($request->all(), [
                'image' => $this->imageHelper->uploadImage($request, 'image'),
            ]));
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $request->validated();

        try {
            $banner->update(array_merge($request->all(), [
                'image' => $request->hasFile('image') ?
                    $this->imageHelper->updateImage($request, 'image', $banner->image) :
                    $banner->image,
            ]));
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function destroy(Banner $banner) {
        try {
            $this->imageHelper->deleteImage($banner->image);
            $banner->delete();
        } catch (\Error $e) {
            ErrorHandling::environmentErrorHandling($e->getMessage());
        }
    }

    public function data(object $banner)
    {
        $array = $banner->get(['id', 'title', 'link', 'image', 'is_active']);

        $data = [];
        $no = 0;

        foreach ($array as $item) {
            $no++;
            $nestedData['no'] = $no;
            $nestedData['title'] = $item->title;
            $nestedData['link'] = $item->link;
            $nestedData['image'] = '<img src="' . asset($item->image) . '" width="150" height="80">';
            $nestedData['status'] = $item->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>';
            $nestedData['actions'] = '
                <div class="btn-group">
                    <a href="' . route('admin.cms.banners.edit', $item) . '" class="btn btn-outline-warning "><i class="fa fa-edit"></i></a>
                    <a href="' . route('admin.cms.banners.destroy', $item) . '" class="btn btn-outline-danger btn-delete"><i class="fa fa-trash"></i></a>
                </div>
            ';

            $data[] = $nestedData;
        }

        return DataTables::of($data)->rawColumns(["actions", "image", "status"])->toJson();
    }
}
