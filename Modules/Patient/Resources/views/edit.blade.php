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
                    <a href="{{ route('product') }}" class="btn btn-warning btn-sm font-weight-bolder">
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

                        <input type="hidden" name="update_id" id="update_id" value="{{ $product->id }}"/>
                        <div class="col-md-9">
                            <div class="row">
                            <x-form.textbox type="hidden" labelName="Name" name="name" required="required" col="col-md-6" value="{{ $product->name }}" placeholder="Enter name" />
                              <x-form.textbox labelName="Name" name="name" required="required" col="col-md-6" value="{{ $product->name }}" placeholder="Enter name" />
                            <x-form.selectbox labelName="Category" name="category_id" required="required" col="col-md-6" class="selectpicker">
                                @if (!$categories->isEmpty())
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"  {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                @endif
                            </x-form.selectbox>
                            
                            <x-form.textbox labelName="Product Link" name="product_link" col="col-md-6" placeholder="Enter Product Link" value="{{ $product->product_link }}"/>
                            <x-form.textbox labelName="Product BNCN" name="product_bncn" col="col-md-6" placeholder="Enter Product BNCN" value="{{ $product->product_bncn }}"/>


                                <x-form.textarea labelName="Product Short Description" name="product_short_desc" col="col-md-6" value="{{ $product->product_short_desc }}"/>

                                <div class="form-group col-md-12">
                                    <label for="description">Description</label>
                                    <textarea class="summernote form-control" name="product_long_desc" id="product_long_desc">{{ $product->product_long_desc }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="form-group col-md-12 mb-0 text-center">
                                    <label for="logo" class="form-control-label">Product Image</label>
                                    <div class="col=md-12 px-0  text-center">
                                        <div id="image">

                                        </div>
                                    </div>
                                    <input type="hidden" name="old_image" id="old_image" value="{{ $product->image }}">
                                </div>
                                <div class="form-group col-md-12 mb-0 text-center">
                                    <label for="lifestyle_image">Life Style Banner</label>
                                    <div class="col-md-12 px-0 text-center">
                                        <div id="lifestyle_image">

                                        </div>
                                    </div>
                                    <input type="hidden" name="old_lifestyle_image" id="old_lifestyle_image" value="{{ $product->lifestyle_image }}">
                                </div>
				 <div class="form-group col-md-12 mb-5 text-left">
                                    <label for="Brochure">Product Video</label>
                                    <div class="col-md-12 px-0 text-center">
                                        <input type="file" name="product_video_path" id="product_video_path">{{ $product->product_video_path }}
                                        <input type="hidden" name="old_product_video_path" id="old_product_video_path" value="{{ $product->product_video_path }}">
                                    </div>

                                </div>
				                <div class="form-group col-md-12 mb-5 text-left">
                                    <label for="Brochure">Product Brochure</label>
                                    <div class="col-md-12 px-0 text-center">
                                        <input type="file" name="product_brochure" id="product_brochure"/>{{ $product->product_brochure }}

                                        <input type="hidden" name="old_product_brochure" id="old_product_brochure" value="{{ $product->product_brochure }}"/>
                                    </div>

                                </div>


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
    /** Start :: Product Image **/
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

    @if(!empty($product->image))
    $('#image img').css('display','none');
    $('#image .spartan_remove_row').css('display','block');
    $('#image .img_').css('display','block');
    $('#image .img_').attr('src',"{{ asset('storage/'.PRODUCT_IMAGE_PATH.$product->image)}}");
    @else
    $('#image img').css('display','block');
    $('#image .spartan_remove_row').css('display','none');
    $('#image .img_').css('display','none');
    $('#image .img_').attr('src','');
    @endif
    /** End :: Product Image **/


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

    @if(!empty($product->lifestyle_image))
    $('#lifestyle_image img').css('display','none');
    $('#lifestyle_image .spartan_remove_row').css('display','block');
    $('#lifestyle_image .img_').css('display','block');
    $('#lifestyle_image .img_').attr('src',"{{ asset('storage/'.PRODUCT_IMAGE_PATH.$product->lifestyle_image)}}");
    @else
    $('#lifestyle_image img').css('display','block');
    $('#lifestyle_image .spartan_remove_row').css('display','none');
    $('#lifestyle_image .img_').css('display','none');
    $('#lifestyle_image .img_').attr('src','');
    @endif
$('input[name="product_video_path"]').prop(true);
$('input[name="product_brochure"]').prop(true);

    $('.remove-files').on('click', function(){
        $(this).parents(".col-md-12").remove();
    });




    /****************************/
    $(document).on('click','#update-btn',function(){

        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);

        $.ajax({
            url: "{{route('product.store.or.update')}}",
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
                            window.location.replace("{{ route('product') }}");
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
