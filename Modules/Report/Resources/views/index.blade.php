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

.userImg {
  margin-top: 20px;
  width: 200px;
  height: 200px;
  object-fit: cover;
  border-radius: 20px;
  border: 10px solid rgba(122,122,122,.15);
}

.dataItem p{
  font-weight: 400;
  font-size: 15px;
}
.dataItem span{
  font-weight: 600;
  font-size: 15px;
}

@media (max-width: 767px){
    #prescription, .logoText, address p, .header p{
        font-size: 12px !important;
    }
    .header h4{
        font-size: 18px !important;
    }
    .patientageLeftSide {
    width: 100% !important;
    min-height: auto !important;
    border: 0 !important;
  }
  .itemMerge{
    flex-direction: column;
  }
  .patientageLeftSide h5{
    font-size: 18px !important;
  }
  .userImg {
    width: 140px !important;
    height: 140px !important;
    border-width: 5px;
  }
  .patientageRightSide .dataItem p,
  .patientageRightSide .dataItem span,
  .patientageLeftSide p{
    margin-bottom: 0;
    font-size: 14px;
  }
  .patientageRightSide .dataItem h5{
    font-size: 16px !important;
    margin-bottom: 5px !important;
  }
  .patientageRightSide{
    padding: 10px 10px !important;
  }
  .patientageRightSide .dataItem{
    margin-top: 15px !important;
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
                @if (permission('patientage-add'))
                <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New patientage','Save')">
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
                                <label for="name">Registration Id</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter patientage Registration Id">
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
                                @if (permission('patientage-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
                                <th>Registration Id</th>
                                <th>patientage Name</th>
                                <th>NID Number</th>
                                <th>Mobile Number</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
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
@endsection

@push('script')
<script>
var table;
$(document).ready(function(){

    

    

    


});
</script>
@endpush