<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
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
                        <input type="hidden" name="update_id" id="update_id" />
                        <x-form.textbox labelName="Name" name="name" required="required" col="col-md-12"
                            placeholder="Enter name" />
                        <x-form.textbox labelName="Email" name="email" required="required" col="col-md-12"
                            placeholder="Enter email" />
                        <x-form.textbox labelName="Mobile No" name="mobile_no" required="required" col="col-md-12"
                            placeholder="Enter mobile_no" />
                        <x-form.selectbox labelName="Gender" name="gender" required="required" col="col-md-12"
                            class="selectpicker">
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </x-form.selectbox>
                        <x-form.selectbox labelName="Role" name="role_id" required="required" col="col-md-12"
                            class="selectpicker">
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </x-form.selectbox>
                       
                        <label for="vehicle1">Patient Registration</label><br>
                        <input name="Patient" id="checkbox_id" type="checkbox" checked>
                        <label for="vehicle1">Station 1 Height & Weight</label><br>
                        <input name="Station1" id="checkbox_id" type="checkbox" checked="on">
                        <label for="vehicle1">Station 2 Blood Pressure</label><br>
                        <input name="Station2" id="checkbox_id" type="checkbox" checked="yes">
                        <label for="vehicle1">Station 3 Glucose & Hemoglobin</label><br>
                        <input name="Station3" id="checkbox_id" type="checkbox" checked="checked">
                        <label for="vehicle1">Station 4 Physician, Obs & Gynae Treatment Plan</label><br>
                        <input name="Station4" id="checkbox_id" type="checkbox" checked="true">
                        <label for="vehicle1">Fingerprint Verification</label><br>
                        <input name="FingerprintVerification" id="checkbox_id" type="checkbox" checked="true">
                        <label for="vehicle1">Patient Data</label><br>
                        <input name="PatientData" id="checkbox_id" type="checkbox" checked="true">
                        <label for="vehicle1">Counter [ 20 / 25 ]</label><br>
                        <input name="Counter" id="checkbox_id" type="checkbox" checked="true">

                      
                        <div class="form-group col-md-12">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password" id="password">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-warning" id="generate_password"
                                        data-toggle="tooltip" data-placement="top"
                                        data-original-title="Generate Password">
                                        <i class="fas fa-lock text-white" style="cursor: pointer;"></i>
                                    </span>
                                </div>
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary">
                                        <i class="fas fa-eye toggle-password text-white" toggle="#password"
                                            style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="password_confirmation">Confirm Password</label>
                            <div class="input-group">
                                <input type="password" class="form-control" name="password_confirmation"
                                    id="password_confirmation">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary">
                                        <i class="fas fa-eye toggle-password text-white" toggle="#password_confirmation"
                                            style="cursor: pointer;"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
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