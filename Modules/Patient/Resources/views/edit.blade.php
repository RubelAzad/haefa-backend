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
                    <a href="{{ route('patient') }}" class="btn btn-warning btn-sm font-weight-bolder">
                        <i class="fas fa-arrow-left"></i> Back</a>
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom" style="padding-bottom: 100px !important;">
            <div class="card-body">
                <form id="store_or_update_form" method="post" enctype="multipart/form-data">
                @csrf
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <h3>Registration</h3><br>
                        <input type="hidden" name="address_update_id" id="address_update_id" value="{{ $address->AddressId }}"/>
                        <input type="hidden" name="update_id" id="update_id" value="{{ $patient->PatientId }}"/>
                        <input type="hidden" name="WorkPlaceId" id="WorkPlaceId" value="{{ $patient->WorkPlaceId }}"/>
                        <input type="hidden" name="WorkPlaceBranchId" id="WorkPlaceBranchId" value="{{ $patient->WorkPlaceBranchId }}"/>
                        <input type="hidden" name="PatientCode" id="PatientCode" value="{{ $patient->PatientCode }}"/>
                        <input type="hidden" name="RegistrationId" id="RegistrationId" value="{{ $patient->RegistrationId }}"/>
                        <div class="col-md-12">
                            <div class="row">
                                <x-form.textbox type="text" labelName="First Name" name="GivenName" col="col-md-6" value="{{ $patient->GivenName }}" placeholder="Enter First Name" readonly/>
                                <x-form.textbox type="text" labelName="Last Name" name="FamilyName" col="col-md-6" value="{{ $patient->FamilyName }}" placeholder="Enter Last Name" />
                                <x-form.textbox type="date" labelName="Date Of Birth" name="BirthDate" required="required" col="col-md-6" value="{{ $patient->BirthDate }}" placeholder="Enter Date Of Birth" />
                                <x-form.textbox type="number" labelName="Patient Age" name="Age" col="col-md-6" value="{{ $patient->Age }}" placeholder="Enter name" />
                                <x-form.textbox type="text" labelName="Contact Number" name="CellNumber" col="col-md-6" value="{{ $patient->CellNumber }}" placeholder="Enter name" />
                                <x-form.selectbox labelName="Gender" name="GenderId" required="required" col="col-md-6" class="selectpicker">
                                    @if (!$genders->isEmpty())
                                        @foreach ($genders as $gender)
                                        <option value="{{ $gender->GenderId }}"  {{ $patient->GenderId == $gender->GenderId ? 'selected' : '' }}>{{ $gender->GenderCode }}</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>
                                <div class="form-group col-md-8">
                                    <label for="">Currency Position</label><br>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="NID" name="IdType" value="NID" class="custom-control-input"
                                                {{ $patient->IdType == 'NID' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="NID">NID</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="BIRTH" name="IdType" value="BIRTH" class="custom-control-input"
                                            {{ $patient->IdType == 'BIRTH' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="BIRTH">BIRTH</label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="ID" name="IdType" value="ID" class="custom-control-input"
                                            {{ $patient->IdType == 'ID' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="ID">ID</label>
                                        </div>
                                </div>
                                <x-form.textbox type="text" labelName="Contact Number" name="IdNumber" col="col-md-6" value="{{ $patient->IdNumber }}" placeholder="Id Number" />
                                <x-form.selectbox labelName="Gender" name="IdOwner" required="required" col="col-md-6" class="selectpicker">
                                    @if (!$selfTypes->isEmpty())
                                        @foreach ($selfTypes as $selfType)
                                        <option value="{{ $selfType->HeadOfFamilyId }}"  {{ $patient->IdOwner == $selfType->HeadOfFamilyId ? 'selected' : '' }}>{{ $selfType->HeadOfFamilyCode }}</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.selectbox labelName="Gender" name="MaritalStatusId" required="required" col="col-md-6" class="selectpicker">
                                    @if (!$maritals->isEmpty())
                                        @foreach ($maritals as $marital)
                                        <option value="{{ $marital->MaritalStatusId }}"  {{ $patient->MaritalStatusId == $marital->MaritalStatusId ? 'selected' : '' }}>{{ $marital->MaritalStatusCode }}</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>
                           
                            
                            </div>
                        </div>
                        <h3>Present Address</h3><br>
                        <div class="col-md-12">
                            <div class="row">
                                <x-form.textbox type="text" labelName="Address" name="AddressLine1" col="col-md-6" value="{{ $address->AddressLine1 }}" placeholder="Enter Address" readonly/>
                                <x-form.textbox type="text" labelName="Village" name="Village" col="col-md-6" value="{{ $address->Village }}" placeholder="Enter Village" />
                                <x-form.textbox type="text" labelName="Union" name="Thana" col="col-md-6" value="{{ $address->Thana }}" placeholder="Enter Union" />
                                <x-form.textbox type="text" labelName="Post Code" name="PostCode" col="col-md-6" value="{{ $address->PostCode }}" placeholder="Enter Post Code" />
                                
                                <x-form.selectbox labelName="District" name="District" required="required" col="col-md-6" class="selectpicker">
                                    @if (!$districts->isEmpty())
                                        @foreach ($districts as $district)
                                        <option value="{{ $district->districtName }}"  {{ $address->District == $district->districtName ? 'selected' : '' }}>{{ $district->districtName }}</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.textbox type="text" labelName="Country" name="Country" col="col-md-6" value="{{ $address->Country }}" placeholder="Enter Country" />
                           
                            
                            </div>
                        </div>
                        <h3>Permanent Address</h3><br>
                        <div class="col-md-12">
                            <div class="row">
                                <x-form.textbox type="text" labelName="Address" name="AddressLine1Parmanent" col="col-md-6" value="{{ $address->AddressLine1Parmanent }}" placeholder="Enter Address" readonly/>
                                <x-form.textbox type="text" labelName="Village" name="VillageParmanent" col="col-md-6" value="{{ $address->VillageParmanent }}" placeholder="Enter Village" />
                                <x-form.textbox type="text" labelName="Union" name="ThanaParmanent" col="col-md-6" value="{{ $address->ThanaParmanent }}" placeholder="Enter Union" />
                                <x-form.textbox type="text" labelName="Post Code" name="PostCodeParmanent" col="col-md-6" value="{{ $address->PostCodeParmanent }}" placeholder="Enter Post Code" />
                                
                                <x-form.selectbox labelName="District" name="DistrictParmanent" required="required" col="col-md-6" class="selectpicker">
                                    @if (!$districts->isEmpty())
                                        @foreach ($districts as $district)
                                        <option value="{{ $district->districtName }}"  {{ $address->DistrictParmanent == $district->districtName ? 'selected' : '' }}>{{ $district->districtName }}</option>
                                        @endforeach
                                    @endif
                                </x-form.selectbox>
                                <x-form.textbox type="text" labelName="Country" name="CountryParmanent" col="col-md-6" value="{{ $address->CountryParmanent }}" placeholder="Enter Country" />
                           
                            
                            </div>
                            
                        </div>
                        <h3>FDMN Camp</h3><br>
                        <div class="col-md-12">
                            <div class="row">
                                <x-form.textbox type="text" labelName="Camp" name="Camp" col="col-md-6" value="{{ $address->Camp }}" placeholder="Enter Address" readonly/>
                                <x-form.textbox type="text" labelName="Block Number" name="BlockNumber" col="col-md-6" value="{{ $address->BlockNumber }}" placeholder="Enter Village" />
                                <x-form.textbox type="text" labelName="Majhi" name="Majhi" col="col-md-6" value="{{ $address->Majhi }}" placeholder="Enter Union" />
                                <x-form.textbox type="text" labelName="Tent Number" name="TentNumber" col="col-md-6" value="{{ $address->TentNumber }}" placeholder="Enter Post Code" />
                                <x-form.textbox type="text" labelName="FCN Number" name="FCN" col="col-md-6" value="{{ $address->FCN }}" placeholder="Enter Country" />
                           
                            
                            </div>
                            
                        </div>
                        

                    </div>
                </div>
                <!-- /modal body -->

                <!-- Modal Footer -->
                <div class="form-group col-md-12 pt-5">
                    <button type="button" class="btn btn-primary btn-sm" id="update-btn">Update</button>
                </div>
                <!-- /modal footer -->
                </form>
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
$(document).ready(function () {
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
    /** Start :: patient Image **/
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

    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });

    @if(!empty($patient->image))
    $('#image img').css('display','none');
    $('#image .spartan_remove_row').css('display','block');
    $('#image .img_').css('display','block');
    $('#image .img_').attr('src',"{{ asset('storage/'.patient_IMAGE_PATH.$patient->image)}}");
    @else
    $('#image img').css('display','block');
    $('#image .spartan_remove_row').css('display','none');
    $('#image .img_').css('display','none');
    $('#image .img_').attr('src','');
    @endif
    /** End :: patient Image **/


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

    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });

    @if(!empty($patient->lifestyle_image))
    $('#lifestyle_image img').css('display','none');
    $('#lifestyle_image .spartan_remove_row').css('display','block');
    $('#lifestyle_image .img_').css('display','block');
    $('#lifestyle_image .img_').attr('src',"{{ asset('storage/'.patient_IMAGE_PATH.$patient->lifestyle_image)}}");
    @else
    $('#lifestyle_image img').css('display','block');
    $('#lifestyle_image .spartan_remove_row').css('display','none');
    $('#lifestyle_image .img_').css('display','none');
    $('#lifestyle_image .img_').attr('src','');
    @endif
$('input[name="patient_video_path"]').prop(true);
$('input[name="patient_brochure"]').prop(true);

    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });




    /****************************/
    $(document).on('click','#update-btn',function(){

        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);

        $.ajax({
            url: "{{route('patient.store.or.update1')}}",
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            cache: false,
            beforeSend: function(){
                $('#update-btn').addClass('spinner spinner-white spinner-right');
            },
            complete: function(){
                $('#update-btn').removeClass('spinner spinner-white spinner-right');
            },
            success: function (data) {
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (data.status == false) {
                    $.each(data.errors, function (key, value){
                        var key = key.split('.').join('_');
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
                            window.location.replace("{{ route('patient') }}");
                    }
                }
            },
            error: function (xhr, ajaxOption, thrownError) {
                console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
            }
        });
    });
});


</script>
@endpush
