@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

  

@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!--begin::Notice-->
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <a href="{{ route('barcodeprint') }}" class="btn btn-warning btn-sm font-weight-bolder">
                            <i class="fas fa-arrow-left"></i> Back</a>
                        <!--end::Button-->
                    </div>
                </div>
            </div>
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom" style="padding-bottom: 100px !important;">
                <div class="card-body">
                    
                    <form id="generate_barcode_form" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="row pb-3 mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="barcode_type" id="barcode_type1" value="old" onclick="getGeneratedBarcode(this.value)">
                                    <label class="form-check-label" for="inlineRadio1">Generated Barcode</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="barcode_type" id="barcode_type2" value="new" onclick="getGeneratedBarcode(this.value)">
                                    <label class="form-check-label" for="inlineRadio1">New Barcode</label>
                                </div>
                            </div>
                            <x-form.selectbox labelName="Barcode Start" name="mdata_barcode_prefix_number_start" col="col-md-4" class="mdata_barcode_prefix_number_start selectpicker"/>
                            <x-form.selectbox labelName="Barcode End" name="mdata_barcode_prefix_number_end" col="col-md-4 " class="mdata_barcode_prefix_number_end selectpicker"/>

                            <div class="form-group col-md-3" style="padding-top: 22px;">
                                <button type="button" class="btn btn-primary btn-sm" id="save-btn">Save</button>
                            </div>
                        </div>
                    </form>
                    <div class="row" id="barcode-section">

                    </div>

                   
                       
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>

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

          

            $(document).on('click','#print-barcode',function()
            {
                var mode = 'popup'; //popup
                var close = mode == 'popup';
                var options = {
                    mode:mode,
                    popClose:close
                };
                $('#printableArea').printArea(options);
            })




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


         

            $('input[name="bgenerate_video_path"]').prop('nullable',true);
            $('input[name="bgenerate_brochure"]').prop('nullable',true);

            $('.remove-files').on('click', function(){
                $(this).parents('.col-md-12').remove();
            });

      



        });

        
            $('#save-btn').on('click', function(event) {
                event.preventDefault();
                
                var filterValueStart = $('#mdata_barcode_prefix_number_start').val();
                var filterValueEnd = $('#mdata_barcode_prefix_number_end').val();
                var barcode_type1 = $('#barcode_type1').val();
                var barcode_type2 = $('#barcode_type2').val();
                
                $.ajax({
                    url: '{{ route('barcodeprint.store.or.update') }}',
                    method: 'GET',
                    data: { filterValueStart: filterValueStart, filterValueEnd: filterValueEnd , barcode_type1: barcode_type1, barcode_type2: barcode_type2},
                    success:function(data){
                    console.log(data);
                    $.each(data, function(key, value) {
                        if (!$.trim(data)){
                        }
                        else{
                            
                        }
                    });


                },
                    
                });
            });

       

       
            
        




        $('#generate_barcode_form #generate_barcode').click(function(e) {
            e.preventDefault(); // Prevent form submission


        });
        //function to generate new barcode or view old one's


        //get Generated Barcode from radio button
        function getGeneratedBarcode(barcode_type){

            $.ajax({
                url: "{{url('/get-barcodes')}}/"+barcode_type,
                type: "GET",
                dataType: "json",
                success:function(data){
                    console.log(data);
                    $('#generate_barcode_form #mdata_barcode_prefix_number_start').empty();
                    $('#generate_barcode_form #mdata_barcode_prefix_number_end').empty();
                    $.each(data, function(key, value) {
                        if (!$.trim(data)){
                            $('#generate_barcode_form .mdata_barcode_prefix_number_end').addClass('d-none');
                        }
                        else{
                            $('#generate_barcode_form .mdata_barcode_prefix_number_end').removeClass('d-none');
                            $('#generate_barcode_form .mdata_barcode_prefix_number_start').removeClass('d-none');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_start').append('<option value="'+ value.mdata_barcode_prefix_number +'">'+ value.mdata_barcode_prefix_number +'</option>');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_end').append('<option value="'+ value.mdata_barcode_prefix_number +'">'+ value.mdata_barcode_prefix_number +'</option>');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_start.selectpicker').selectpicker('refresh');
                            $('#generate_barcode_form #mdata_barcode_prefix_number_end.selectpicker').selectpicker('refresh');
                        }
                    });
                    $('#generate_barcode_form .selectpicker').selectpicker('refresh');


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
