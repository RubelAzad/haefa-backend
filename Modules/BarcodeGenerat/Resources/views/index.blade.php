@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">{{ $sub_title }}</li>
              </ol>
        </div>
        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}</h2>
                </div>
                <!-- /entry heading -->
                @if (permission('bgenerate-add'))
                <button class="btn btn-primary btn-sm" onclick="showStoreFormModal('Add New Barcode Generate','Save')">
                    <i class="fas fa-plus-square"></i> Add New
                 </button>
                @endif


            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="name">bgenerate Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Barcode Generate name">
                            </div>
                            <div class="form-group col-md-12">
                               <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset"
                               data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                   <i class="fas fa-redo-alt"></i>
                                </button>
                               <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter"
                               data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                   <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <table id="dataTable" class="table table-striped table-bordered table-hover">
                        <thead class="bg-primary">
                            <tr>
                                @if (permission('bgenerate-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
				                <th>Barcode Id</th>
				                <th>Barcode Status</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@include('barcodegenerat::modal')
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
var table;
$(document).ready(function(){
      $('.summernote').summernote({
        tabsize: 2,
        height: 120,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ]
      });
    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "lengthMenu": [
            [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
            [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
        ],
        "pageLength": 10, //number of data show per page
        "language": {
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('bgenerate.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name        = $("#form-filter #name").val();
                data._token      = _token;
            }
        },
        "columnDefs": [{
                @if (permission('bgenerate-bulk-delete'))
                "targets": [0,3],
                @else
                "targets": [3],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('bgenerate-bulk-delete'))
                "targets": [1,2,4],
                @else
                "targets": [0,1,3],
                @endif
                "className": "text-center"
            },
            {
                @if (permission('bgenerate-bulk-delete'))
                "targets": [2,3],
                @else
                "targets": [2,3],
                @endif
                "className": "text-right"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('bgenerate-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "bgenerate List",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                },
                customize: function (win) {
                    $(win.document.body).addClass('bg-white');
                },
            },
            {
                "extend": 'csv',
                'text':'CSV',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "bgenerate List",
                "filename": "bgenerate-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "bgenerate List",
                "filename": "bgenerate-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
            {
                "extend": 'pdf',
                'text':'PDF',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "bgenerate List",
                "filename": "bgenerate-list",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3,4,5,6,7,8]
                },
            },
            @endif
            @if (permission('bgenerate-bulk-delete'))
            {
                'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                'text':'Delete',
                action:function(e,dt,node,config){
                    multi_delete();
                }
            }
            @endif
        ],
    });

    $('#btn-filter').click(function () {
        table.ajax.reload();
    });

    $('#btn-reset').click(function () {
        $('#form-filter')[0].reset();
        $('#form-filter .selectpicker').selectpicker('refresh');
        table.ajax.reload();
    });

        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            let url = "{{route('bgenerate.store.or.update')}}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
                $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                complete: function(){
                    $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                success: function (data) {
                    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                    $('#store_or_update_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#store_or_update_form input#' + key).addClass('is-invalid');
                            $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                            $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                            if(key == 'code'){
                                $('#store_or_update_form #' + key).parents('.form-group').append(
                                '<small class="error text-danger">' + value + '</small>');
                            }else{
                                $('#store_or_update_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                            }

                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            if (method == 'update') {
                                table.ajax.reload(null, false);
                            } else {
                                table.ajax.reload();
                            }
                            $('#store_or_update_modal').modal('hide');
                        }
                    }

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });

    


    $(document).on('click', '.delete_data', function () {
        let id    = $(this).data('id');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('bgenerate.delete') }}";
        delete_data(id, url, table, row, name);
    });

    function multi_delete(){
        let ids = [];
        let rows;
        $('.select_data:checked').each(function(){
            ids.push($(this).val());
            rows = table.rows($('.select_data:checked').parents('tr'));
        });
        if(ids.length == 0){
            Swal.fire({
                type:'error',
                title:'Error',
                text:'Please checked at least one row of table!',
                icon: 'warning',
            });
        }else{
            let url = "{{route('bgenerate.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let id    = $(this).data('id');
        let status = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('bgenerate.change.status') }}";
        change_status(id,status,name,table,url);

    });

    $('#image').spartanMultiImagePicker({
        fieldName: 'image',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
        }
    });

    $('input[name="image"]').prop('required',true);

    $('#lifestyle_image').spartanMultiImagePicker({
        fieldName: 'lifestyle_image',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
        }
    });

    $('input[name="lifestyle_image"]').prop('required',true);


    /* $('#bgenerate_video_path').spartanMultiImagePicker({
        fieldName: 'file',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only video/mp4 file format allowed!'});
        }
    }); */

    $('input[name="bgenerate_video_path"]').prop('nullable',true);
    $('input[name="bgenerate_brochure"]').prop('nullable',true);

    $('.remove-files').on('click', function(){
        $(this).parents('.col-md-12').remove();
    });

    $('#generate_barcode').click(function(){
        $.get('generate-code',function(data){
            $('#store_or_update_form #code').val(data);
        });
    });



});

function getVariantOptionList(mdata_barcode_prefix,mdata_barcode_number=''){
    $.ajax({
        url: "{{url('/latest-range')}}/"+mdata_barcode_prefix,
        type: "GET",
        dataType: "json",
        success: function(data) {
            console.log(data);
            if (data) {
                $('input[name="range"]').val(data.numbers ? data.numbers : data.range);
            } else {
                $('input[name="range"]').val('');
            }

            if (!$.trim(data)){
                    $('#store_or_update_form .mdata_barcode_number').addClass('d-none');
            }
            else{
                $('#store_or_update_form .mdata_barcode_number').removeClass('d-none');
                $('#store_or_update_form #mdata_barcode_number').val(data.barcode_number ? data.barcode_number : data.mdata_barcode_number);
            }
        },

    });
}



function showStoreFormModal(modal_title, btn_text)
{
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();

    $('#store_or_update_form #image img.spartan_image_placeholder').css('display','block');
    $('#store_or_update_form #image .spartan_remove_row').css('display','none');
    $('#store_or_update_form #image .img_').css('display','none');
    $('#store_or_update_form #image .img_').attr('src','');
    $('#store_or_update_form #lifestyle_image img.spartan_image_placeholder').css('display','block');
    $('#store_or_update_form #lifestyle_image .spartan_remove_row').css('display','none');
    $('#store_or_update_form #lifestyle_image .img_').css('display','none');
    $('#store_or_update_form #lifestyle_image .img_').attr('src','');
    $('.selectpicker').selectpicker('refresh');
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square"></i> '+modal_title);
    $('#store_or_update_modal #save-btn').text(btn_text);
}
</script>
@endpush
