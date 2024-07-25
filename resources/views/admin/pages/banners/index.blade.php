@extends('admin.layout.app')

@section('title')
    @lang('nav.banners')
@endsection

@section('css-vendor')
    <link rel="stylesheet" href="{{ asset('/dashboard/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('/dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('/dashboard/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('/dashboard/assets/vendor/libs/select2/select2.css') }}" />
@endsection

{{-- main content --}}
@section('content')
    <div class="card">
        <div class="card-header border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">{{ __('banners') }}</h5>
            {{-- check if auth user has ability to create  --}}
            @if (auth('admin')->user()->hasAbilityTo('create banners'))
                <div>
                    <button class="btn btn-primary" data-bs-target="#addBannerModal" data-bs-toggle="modal"><i
                            class="bx bx-plus me-0 me-lg-2"></i><span
                            class="d-none d-lg-inline-block">{{ __('add_banner') }}</span></button>
                </div>
            @endif
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-categories table border-top">
                <thead>
                    <tr>
                        <th></th>
                        <th>{{ __('status') }}</th>
                        <th>@lang('categories.created_at')</th>
                        <th class="d-flex justify-content-center" data-searchable="false" data-orderable="false">
                            @lang('general.actions')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <!-- Add Modal -->
    <div class="modal fade" id="addBannerModal" tabindex="-1" aria-labelledby="addBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBannerModalLabel">{{ __('add_banner') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="addBannerForm">
                        <div class="form-group mb-3">
                            <label for="image" class="form-label">{{ __('image') }}</label>
                            <input type="file" name="image" id="image" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-create-btn" class="btn btn-primary">@lang('general.create')</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('general.cancel')</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal fade" id="editBannerModal" tabindex="-1" aria-labelledby="editBannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBannerModalLabel">@lang('general.edit')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" id="editCategoryForm">
                        <img src="" alt="previewImage" id="preview-image" class="img-fluid">
                        <div class="form-group mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="edit_status">
                                <label class="form-check-label" for="edit_status">{{ __('status') }}</label>
                            </div>

                        </div>
                        <input type="hidden" id="edit_id">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="submit-edit-btn" class="btn btn-primary">@lang('general.edit')</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('general.cancel')</button>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script-vendor')
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables-buttons/datatables-buttons.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/jszip/jszip.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/pdfmake/pdfmake.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables-buttons/buttons.html5.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/datatables-buttons/buttons.print.js') }}"></script>
    <script src="{{ asset('/dashboard/assets/vendor/libs/select2/select2.js') }}"></script>
@endsection
@section('script')
    <script>
        $('document').ready(function() {
            //initialise datatbles
            let datatable = $('.datatables-categories').DataTable({
                language: {
                    sLengthMenu: '_MENU_',
                    search: '',
                    searchPlaceholder: '@lang('general.search')..',
                    paginate: {
                        previous: '@lang('general.previous')',
                        next: '@lang('general.next')'
                    }
                },
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: "{!! route('admin.banners.banners_list') !!}",
                columns: [{
                        data: 'image',
                        name: 'image'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        name: 'actions',
                        data: 'actions',
                        searchable: false,
                        orderable: false
                    },
                ],
            })

            // to make the datatables inputs appear larger
            setTimeout(() => {
                $('.dataTables_filter .form-control').removeClass('form-control-sm');
                $('.dataTables_length .form-select').removeClass('form-select-sm');
            })
            // ----- crud operations

            //create new ajax request
            $('body').on('click', '#submit-create-btn', function() {
                let fd = new FormData();
                fd.append('_token', "{!! csrf_token() !!}")
                if (document.getElementById('image').files[0]) {
                    fd.append('image', document.getElementById('image').files[0])

                }
                let formBtn = $(this) // the button that sends the reuquest (to minipulate ui)

                $.ajax({
                    method: 'POST',
                    url: "{!! route('admin.banners.store') !!}",
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        formBtn.html(
                            '<span class="spinner-border" role="status" aria-hidden="true"></span>'
                        )
                        formBtn.prop('disabled', true)
                    },
                    success: function(response) {
                        successMessage("@lang('general.create_success')")
                        $('#addBannerModal').modal('toggle')
                        document.getElementById("addBannerForm").reset();
                        datatable.ajax.reload()
                    },
                    error: function(response) {
                        errorMessage("@lang('general.error')")
                        displayErrors(response, false)
                    },
                }).done(function() {
                    formBtn.html("@lang('general.create')")
                    formBtn.prop('disabled', false)
                    $('#addBannerModal').modal('toggle')
                }).fail(function() {
                    formBtn.html("@lang('general.create')")
                    formBtn.prop('disabled', false)
                })
            })

            //populate table when pressing edit admin (from table)
            $('body').on('click', '.edit-btn', function() {
                $('#edit_status').prop("checked", false)
                $('#preview-image').attr('src', $(this).data('src'))
                let status = $(this).data('status')
                if (status == 'active') {
                    $('#edit_status').prop("checked", true)
                }
                $('#edit_id').val($(this).data('id'))
            })
            //edit ajax request
            $('body').on('click', '#submit-edit-btn', function() {
                let fd = new FormData();
                if ($('#edit_status').is(':checked')) {
                    fd.append('status', 'active')
                } else {
                    fd.append('status', 'inactive')
                }
                fd.append('_token', "{!! csrf_token() !!}")
                fd.append('_method', "PATCH")
                fd.append('id', $('#edit_id').val())
                let formBtn = $(this) // the button that sends the reuquest (to minipulate ui)

                $.ajax({
                    method: 'POST',
                    url: "{!! route('admin.banners.update') !!}",
                    data: fd,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        formBtn.html(
                            '<span class="spinner-border" role="status" aria-hidden="true"></span>'
                        )
                        formBtn.prop('disabled', true)
                    },
                    success: function(response) {
                        successMessage("@lang('general.edit_success')")
                        $('#editBannerModal').modal('toggle')
                        datatable.ajax.reload()
                    },
                    error: function(response) {
                        errorMessage("@lang('general.error')")
                        displayErrors(response, true)
                    },
                }).done(function() {
                    formBtn.html("@lang('general.edit')")
                    formBtn.prop('disabled', false)
                }).fail(function() {
                    formBtn.html("@lang('general.edit')")
                    formBtn.prop('disabled', false)
                })
            })

            //delete btn (from table)
            $('body').on('click', '.delete-btn', function() {
                let id = $(this).data('id')
                Swal.fire({
                    title: "@lang('general.confirmation')",
                    text: " @lang('general.cant_revert')",
                    icon: 'warning',
                    showCancelButton: true,
                    cancelButtonText: "@lang('general.cancel')",
                    confirmButtonText: "@lang('general.delete')",
                    customClass: {
                        confirmButton: 'btn btn-danger me-3',
                        cancelButton: 'btn btn-label-secondary'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        //ajax delete call
                        let data = {
                            _token: "{!! csrf_token() !!}",
                            id: id,
                        }
                        $.ajax({
                            method: 'DELETE',
                            url: "{!! route('admin.banners.delete') !!}",
                            data: data,
                            success: function(response) {
                                successMessage("@lang('general.delete_success')")
                                datatable.ajax.reload()
                            },
                            error: function(response) {
                                errorMessage("@lang('general.error')")
                            },
                        })
                    }
                });
            })
        })
    </script>
@endsection
