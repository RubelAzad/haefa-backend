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
            <form id="store_or_update_form" enctype="multipart/form-data" method="post">
                @csrf
                <!-- Modal Body -->
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="EmployeeId" value="" id="EmployeeId" />
                            <input type="hidden" name="SortOrder" value="8" />
                            <x-form.textbox labelName="Employee Code" name="EmployeeCode" id="EmployeeCode"
                                required="required" col="col-md-12" placeholder="Enter Employee Code" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.textbox labelName="Registration Number" name="RegistrationNumber"
                                id="RegistrationNumber" required="required" col="col-md-12"
                                placeholder="Enter Registration Number" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.textbox labelName="First Name" name="FirstName" id="FirstName" 
                                col="col-md-12" placeholder="Enter First Name" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.textbox labelName="Last Name" name="LastName" id="LastName" 
                                col="col-md-12" placeholder="Enter Last Name" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-md-12 ">
                                <label for="BirthDate">Birth Date</label>
                                <input type="date" name="BirthDate" id="BirthDate" class="form-control " value=""
                                    placeholder="Enter Birth Date">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-md-12">
                                <label for="JoiningDate">Joining Date</label>
                                <input type="date" name="JoiningDate" id="JoiningDate" class="form-control " value=""
                                    placeholder="Enter Joining Date">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.selectbox labelName="Gender" name="GenderId" id="WorkPlaceId" col="col-md-12"
                                class="selectpicker">
                                @foreach($genders as $gender)
                                <option value="{{$gender->GenderId??''}}">{{$gender->GenderCode??""}}</option>
                                @endforeach
                            </x-form.selectbox>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.selectbox labelName="MaritalStatus" name="MaritalStatusId" id="MaritalStatusId"
                                col="col-md-12" class="selectpicker">
                                @foreach($maritalStatus as $maritalStat)
                                <option value="{{$maritalStat->MaritalStatusId??''}}">{{$maritalStat->MaritalStatusCode??""}}
                                </option>
                                @endforeach
                            </x-form.selectbox>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.selectbox labelName="Education" name="EducationId" id="EducationId" col="col-md-12"
                                class="selectpicker">
                                @foreach($educations as $education)
                                <option value="{{$education->EducationId??''}}">{{$education->EducationCode??""}}
                                </option>
                                @endforeach
                            </x-form.selectbox>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.textbox labelName="Designation" name="Designation" id="Designation" required="required"
                                col="col-md-12" placeholder="Enter Designation" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 required">
                            <x-form.selectbox labelName="Religion" name="ReligionId" id="ReligionId" col="col-md-12"
                                class="selectpicker">
                                @foreach($religions as $religion)
                                <option value="{{$religion->ReligionId??''}}">{{$religion->ReligionCode??""}}
                                </option>
                                @endforeach
                            </x-form.selectbox>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.selectbox labelName="Role" name="RoleId" id="RoleId" col="col-md-12"
                                class="selectpicker">
                                @foreach($roles as $role)
                                <option value="{{$role->RoleId??''}}">{{$role->RoleCode??""}}
                                </option>
                                @endforeach
                            </x-form.selectbox>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <x-form.textbox labelName="Email" name="Email" id="Email" col="col-md-12"
                                placeholder="Enter Email" />
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <x-form.textbox labelName="Phone" name="Phone" id="Phone" col="col-md-12"
                                placeholder="Enter Phone" />
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <x-form.textbox labelName="National Id Number" name="NationalIdNumber" id="NationalIdNumber" col="col-md-12"
                                placeholder="Enter National Id Number" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-md-12 required">
                                <label for="EmployeeImage">Employee Image</label>
                                <div class="col-md-12 px-0 text-center">
                                <input type="file" name="EmployeeImage" id="EmployeeImage">
                                
                                </div>
                                <!-- <input type="date" name="EmployeeImage" id="EmployeeImage" class="form-control " value=""
                                    placeholder="Enter Employee Image"> -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group col-md-12 required">
                                <label for="EmployeeSignature">Employee Signature</label>
                            <div class="col-md-12 px-0 text-center">
                                <input type="file" name="EmployeeSignature" data-allowed-formats="portrait square" id="EmployeeSignature">
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