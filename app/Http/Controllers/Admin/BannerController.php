<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Traits\CustomeFileHandler;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\Admin\BannerRequest;

class BannerController extends Controller
{
    use CustomeFileHandler;
    public function index()
    {
        return view('admin.pages.banners.index');
    }

    public function store(BannerRequest $request)
    {
        $path = $this->uploadFile('banners', $request->image);
        $banner = Banner::create(["image" => $path]);

        return http_response_code(200);
    }

    public function getBannersList()
    {
        $data = Banner::orderBy('created_at', 'desc')->get();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->editColumn('image', function ($row) {
                $path = Storage::url($row->image);
                return <<<HTML
                        <img src="{$path}" width="100" height="50" alt="Avatar" class="rounded">
                HTML;
            })
            ->editColumn('status', function ($row) {
                $status = $row->status;
                $classes_array = ["inactive" => "danger", "active" => "success"];
                $status_text = __($row->status);
                return <<<HTML
                        <span class="badge pill-rounded bg-{$classes_array[$status]}">{$status_text}</span>
                HTML;
            })
            ->addColumn('actions', function ($row) {
                $edit_text = trans('general.edit');
                $delete_text = trans('general.delete');
                $src = Storage::url($row->image);
                $btns = <<<HTML
                    <div class="dropdown d-flex justify-content-center">
                        <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item edit-btn"
                             data-id="{$row->id}"
                             data-src = {$src}
                             data-status = "{$row->status}"
                              data-bs-toggle="modal"
                              data-bs-target = "#editBannerModal"
                              href="javascript:void(0);"><i class="bx bx-edit me-0 me-2 text-primary"></i>{$edit_text}</a></li>
                             <li>
                              <a class="dropdown-item delete-btn"
                                data-id = "{$row->id}"
                              href="javascript:void(0);"><i class="bx bx-trash me-0 me-2 text-danger"></i>{$delete_text}</a></li>
                          </ul>
                        </div>
                HTML;

                if (auth('admin')->user()->hasAbilityTo('edit banners')) {
                    return $btns;
                }
                return;
            })
            ->rawColumns(['actions', 'image', 'status'])
            ->make(true);
    }


    public function destroy(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $this->deleteFile($banner->image);
        $banner->delete();
        return http_response_code(200);
    }

    public function update(Request $request)
    {
        $banner = Banner::findOrFail($request->id);
        $banner->update(["status" => $request->status]);
        return http_response_code(200);
    }
}
