@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')
    <style>
#prescription .container {
  background-color: #f2f2f2 !important;
}

.header p {
  font-size: 14px;
}
.aside {
  width: 400px;
  border-right: 1px solid #ddd;
  min-height: 600px;
  padding-bottom: 20px;
}

.signatureImage {
  display: inline-block;
  width: 100px;
  object-fit: contain;
  margin-bottom: 5px;
}
.signatureBox {
  position: absolute;
  right: 50px;
  bottom: 30px;
}
.footer {
  padding-top: 20px;
  padding-bottom: 20px;
  border-top: 1px solid #ddd;
}

.footer p {
  font-size: 14px;
}
.apiLogo {
  max-width: 40px;
  transform: translateY(-4px);
  margin-left: 5px;
}
.logoText {
  font-size: 14px;
}
.nextinfo {
  margin-top: 150px;
}

@media (max-width: 767px){
    #prescription, .logoText, address p, .header p{
        font-size: 12px !important;
    }
    .header h4{
        font-size: 18px !important;
    }

}
    </style>
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

                @if (permission('refdesignation-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add Refdesignation','Save');optionData();removeId()">
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
                            <div class="form-group col-md-4">
                                <label for="name">Designation Title</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter Designation Title">
                            </div>
                            <div class="form-group col-md-8 pt-24">
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
                                @if (permission('refdesignation-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
                                <th>DesignationTitle</th>
                                <th>Description</th>
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
@include('refdesignation::view-modal')
@include('refdesignation::add-edit-modal')
@endsection

@push('script')
<script>
var table;
$(document).ready(function(){

    table = $('#dataTable').DataTable({
        "processing": true, //Feature control the processing indicator
        "serverSide": true, //Feature control DataTable server side processing mode
        "order": [], //Initial no order
        "responsive": true, //Make table responsive in mobile device
        "bInfo": true, //TO show the total number of data
        "bFilter": false, //For datatable default search box show/hide
        "pageLength": 10, //number of data show per page
        "language": { 
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('refdesignation.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name = $("#form-filter #name").val();
                data._token    = _token;
            }
        },
        "columnDefs": [{
                @if (permission('refdesignation-bulk-delete'))
                "targets": [0,4],
                @else 
                "targets": [3],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('refdesignation-bulk-delete'))
                "targets": [1,3],
                @else 
                "targets": [0,2],
                @endif
                "className": "text-center"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('refdesignation-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'excel',
                'text':'Excel',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Menu List",
                "filename": "refdesignation",
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
                "title": "Menu List",
                "filename": "refdesignation",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3]
                },
            },
            @endif 
            @if (permission('refdesignation-bulk-delete'))
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
        table.ajax.reload();
    });

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('refdesignation.store.or.update')}}";
        let id = $('#RefDesignationId').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
        }
        store_or_update_data(table, method, url, formData);
    });

    

    $(document).on('click', '.view_data', function () {
        let id = $(this).data('id');
       // let date = $(this).data('date');
        if (id) {
            $.ajax({
                url: "{{route('refdesignation.show')}}",
                type: "POST",
                data: { id: id,_token: _token},
                success: function (data) {

                    $('#view_modal .details').html();
                    $('#view_modal .details').html(data);

                    $('#view_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#view_modal .modal-title').html(
                        '<i class="fas fa-eye"></i> <span>Refdesignation</span>');
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    });

    

    $(document).on('click', '.delete_data', function () {
        let refdesignationId    = $(this).data('id');
        // let name  = $(this).data('name');
        let name  = "refdesignation";
        console.log(name);
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('refdesignation.delete') }}";
        let response = delete_data(refdesignationId, url, table, row, name);
        
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
            let url = "{{route('refdesignation.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let RefDesignationId    = $(this).data('id');
        let Status    = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('refdesignation.change.status') }}";
        Swal.fire({
            title: 'Are you sure to change ' + name + ' status?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: url,
                    type: "POST",
                    data: { RefDesignationId: RefDesignationId,Status:Status, _token: _token},
                    dataType: "JSON",
                }).done(function (response) {
                    if (response.status == "success") {
                        Swal.fire("Status Changed", response.message, "success").then(function () {
                            table.ajax.reload(null, false);
                        });
                    }
                    if (response.status == "error") {
                        Swal.fire('Oops...', response.message, "error");
                    }
                }).fail(function () {
                    Swal.fire('Oops...', "Somthing went wrong with ajax!", "error");
                });
            }
        });

    });

});

$(document).on('click', '.edit_data', function () {
    let id = $(this).data('id');
    $('#store_or_update_form')[0].reset();
    // $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    // $('#store_or_update_form').find('.error').remove();
    
    if (id) {
        $.ajax({
            url: "{{route('refdesignation.edit')}}",
            type: "POST",
            data: { id: id,_token: _token},
            dataType: "JSON",
            success: function (data) {
                // console.log(data);
                // console.log(data[1][0]);
                $('#DepartmentCode').val(data.designation[0].DepartmentCode);
                $('#DesignationTitle').val(data.designation[0].DesignationTitle);
                $('#Description').val(data.designation[0].Description);
                $('#RefDesignationId').val(data.designation[0].RefDesignationId);

                //workplace code start
                var op_select = '<option value="">' + 'Select' + '</option>';
                var options = [];
                options.push(op_select);
                var selectedValue = data.designation[0].WorkPlaceId;
                data.workplaces.map((dt, ind) => {
                    var op = '<option value="' + dt.WorkPlaceId + '">' + dt.WorkPlaceName + '</option>';
                    // Check if the option matches the selected value
                    if (dt.WorkPlaceId === selectedValue) {
                        op = '<option value="' + dt.WorkPlaceId + '" selected>' + dt.WorkPlaceName + '</option>';
                    }
                    options.push(op);
                });

                $('#WorkPlaceId').html(options.join(''));
                //workplace code end

                //department code start
                var opt_select = '<option value="">' + 'Select' + '</option>';
                var all_options = [];
                all_options.push(opt_select);
                var selectedValue2 = data.designation[0].RefDepartmentId;
                data.departments.map((dpt, ind) => {
                    var op = '<option value="' + dpt.RefDepartmentId + '">' + dpt.DepartmentCode + '</option>';
                    // Check if the option matches the selected value
                    if (dpt.RefDepartmentId === selectedValue2) {
                        op = '<option value="' + dpt.RefDepartmentId + '" selected>' + dpt.DepartmentCode + '</option>';
                    }
                    all_options.push(op);
                });

                $('#RefDepartmentId').html(all_options.join(''));
                //department code end

                $('#store_or_update_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#store_or_update_modal .modal-title').html(
                    '<i class="fas fa-edit"></i> <span>Edit Refdesignation</span>');
                $('#store_or_update_modal #save-btn').text('Update');
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    }
});
function optionData(){
    $.ajax({
        url: "{{route('refdesignation.workplaces_departments')}}",
        type: "get",
        dataType: "JSON",
        success: function (data) {
            console.log(data);

            $('#refdesignationId').val('');

            //workplaces loop start
            var op_select = '<option value="">' + 'Select' + '</option>';
            var options = [];
            options.push(op_select);

            data.workplaces.map((dt, ind) => {
                var op = '<option value="' + dt.WorkPlaceId + '">' + dt.WorkPlaceName + '</option>';
                options.push(op);
            });
            $('#WorkPlaceId').html(options.join(''));
            //workplaces loop end
            
            //dpeartment loop start
            var op_select2 = '<option value="">' + 'Select' + '</option>';
            var options_dpeartments = [];
            options_dpeartments.push(op_select2);

            data.departments.map((dpt, ind) => {
                var op2 = '<option value="' + dpt.RefDepartmentId + '">' + dpt.DepartmentCode + '</option>';
                options_dpeartments.push(op2);
            });
            $('#RefDepartmentId').html(options_dpeartments.join(''));
            //dpeartment loop end

        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
}

function removeId(){
    $('#RefDesignationId').val('');
}
</script>
@endpush