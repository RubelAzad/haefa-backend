<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

      <!-- Modal Content -->
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <!-- /modal header -->
        <form id="store_or_update_form" method="post">
          @csrf
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <x-form.textbox labelName="Barcode District Name" name="barcode_district" required="required" col="col-md-12" placeholder="Enter District Name"/>
                    <x-form.textbox labelName="Barcode Upazila Name" name="barcode_upazila" required="required" col="col-md-12" placeholder="Enter Upazila Name"/>
                    <x-form.textbox labelName="Barcode Union Name" name="barcode_union" required="required" col="col-md-12" placeholder="Enter Union Name"/>
                    <x-form.textbox labelName="Barcode Community Clinic Name" name="barcode_community_clinic" required="required" col="col-md-12" placeholder="Enter Community Clinic Name"/>
                    <x-form.textbox labelName="Barcode Prefix name" name="barcode_prefix" required="required" col="col-md-12" placeholder="Enter Barcode Prefix name"/>
                    <x-form.textbox labelName="Barcode Number name" name="barcode_number" required="required" col="col-md-12" placeholder="Enter Barcode Number name"/>
                </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>