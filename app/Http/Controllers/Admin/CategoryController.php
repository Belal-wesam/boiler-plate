<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('admin.pages.categories.index');
    }
    public function store(CategoryRequest $request)
    {
        Category::create($request->validated());
        return http_response_code(200);
    }
    public function getCategoriesList()
    {
        $data = Category::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->addColumn('actions', function ($row) {
                $edit_text = trans('general.edit');
                $delete_text = trans('general.delete');
                $btns = <<<HTML
                    <div class="dropdown d-flex justify-content-center">
                        <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item edit-btn"
                             data-id="{$row->id}"
                             data-name = "{$row->name}"
                              data-bs-toggle="modal"
                              data-bs-target = "#editCategoryModal"
                              href="javascript:void(0);"><i class="bx bx-edit me-0 me-2 text-primary"></i>{$edit_text}</a></li>
                             <li>
                              <a class="dropdown-item delete-btn"
                                data-id = "{$row->id}"
                              href="javascript:void(0);"><i class="bx bx-trash me-0 me-2 text-danger"></i>{$delete_text}</a></li>
                          </ul>
                        </div>
                HTML;

                if (auth('admin')->user()->hasAbilityTo('edit categories')) {
                    return $btns;
                }
                return;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    public function update(CategoryRequest $request)
    {
        $category = Category::findOrFail($request->id);
        $category->update($request->validated());
        return http_response_code(200);
    }
    public function destroy(Request $request)
    {
        Category::findOrFail($request->id)->delete();
        return http_response_code(200);
    }
}
