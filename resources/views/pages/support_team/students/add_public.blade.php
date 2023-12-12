@extends('layouts.master_public')
@section('page_title', 'ENROLL')
@section('content')
        <div class="card">
            <div class="card-header bg-white header-elements-inline">
                <h6 class="card-title">STUDENT ENROLLMENT</h6>

                {{-- {!! Qs::getPanelOptions() !!} --}}
            </div>

            <form id="ajax-reg" method="post" enctype="multipart/form-data" class="wizard-form steps-validation" action="{{ route('students.store_public') }}" data-fouc>
                @if(session('error_verification'))    
                <p class="alert alert-danger">{{ session('error_verification') }}</p>
                @endif
                @csrf
                <h6>Personal Data</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>First Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('first_name') }}" required type="text" name="first_name" placeholder="Full Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Middle Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('middle_name') }}" required type="text" name="middle_name" placeholder="Middle Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Last Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('last_name') }}" required type="text" name="last_name" placeholder="Last Name" class="form-control">
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Address: <span class="text-danger">*</span></label>
                                <input value="{{ old('address') }}" class="form-control" placeholder="Address" name="address" type="text" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email address: <span class="text-danger">*</span></label>
                                <input type="email" value="{{ old('email') }}" name="email" class="form-control" placeholder="Email Address" required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="gender">Gender: <span class="text-danger">*</span></label>
                                <select class="select form-control" id="gender" name="gender" required data-fouc data-placeholder="Choose.." >
                                    <option value=""></option>
                                    <option {{ (old('gender') == 'Male') ? 'selected' : '' }} value="Male">Male</option>
                                    <option {{ (old('gender') == 'Female') ? 'selected' : '' }} value="Female">Female</option>
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Phone: <span class="text-danger">*</span></label>
                                <input value="{{ old('phone') }}" type="text" name="phone" class="form-control" placeholder="" required>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Phone: <span class="text-danger">*</span></label>
                                <input id="personal_phone_number" value="{{ old('phone') }}" type="text" name="phone" class="form-control" 
                                       placeholder="Enter 11-digit phone number" required
                                       minlength="11" maxlength="11" title="Phone number must be exactly 11 digits">
                            </div>
                        </div>
                        

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Telephone:</label>
                                <input value="{{ old('phone2') }}" type="text" name="phone2" class="form-control" placeholder="" >
                            </div>
                        </div> --}}

                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Date of Birth: <span class="text-danger">*</span></label>
                                <input name="dob" value="{{ old('dob') }}" type="text" class="form-control date-pick" placeholder="Select Date..." required>

                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label for="nal_id">Nationality: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="nal_id" id="nal_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($nationals as $nal)
                                        <option {{ (old('nal_id') == $nal->id ? 'selected' : '') }} value="{{ $nal->id }}">{{ $nal->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="bg_id">Blood Group: </label>
                                <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    @foreach(App\Models\BloodGroup::all() as $bg)
                                        <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Birth Certificate Number:</label>
                                <input value="{{ old('birth_certificate') }}" type="text" name="birth_certificate" placeholder="Birth Certificate Number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="d-block">Report Card: <span class="text-danger">*</span></label>
                                <input value="{{ old('report_card') }}" accept="image/*" type="file" name="report_card" class="form-input-styled" data-fouc required>
                                <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label for="bg_id">Blood Group: </label>
                                <select class="select form-control" id="bg_id" name="bg_id" data-fouc data-placeholder="Choose..">
                                    <option value=""></option>
                                    @foreach(App\Models\BloodGroup::all() as $bg)
                                        <option {{ (old('bg_id') == $bg->id ? 'selected' : '') }} value="{{ $bg->id }}">{{ $bg->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <label class="d-block">Upload Passport Photo:</label>
                                <input value="{{ old('photo') }}" accept="image/*" type="file" name="photo" class="form-input-styled" data-fouc>
                                <span class="form-text text-muted">Accepted Images: jpeg, png. Max file size 2Mb</span>
                            </div>
                        </div> --}}
                        
                    </div>

                </fieldset>

                <h6>Parents/Guardians Information</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Father's Name</h3>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>First Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('father_first_name') }}" required type="text" name="father_first_name" placeholder="Full Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Middle Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('father_middle_name') }}" required type="text" name="father_middle_name" placeholder="Middle Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Last Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('father_last_name') }}" required type="text" name="father_last_name" placeholder="Last Name" class="form-control">
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact Number: <span class="text-danger">*</span></label>
                                <input value="{{ old('father_contact_number') }}" class="form-control" placeholder="Contact Number" name="father_contact_number" type="text" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Mother's Maiden Name</h3>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>First Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('mother_first_name') }}" required type="text" name="mother_first_name" placeholder="Full Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Middle Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('mother_middle_name') }}" required type="text" name="mother_middle_name" placeholder="Middle Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Last Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('mother_last_name') }}" required type="text" name="mother_last_name" placeholder="Last Name" class="form-control">
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact Number: <span class="text-danger">*</span></label>
                                <input value="{{ old('mother_contact_number') }}" class="form-control" placeholder="Contact Number" name="mother_contact_number" type="text" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h3>Legal Guardian's Name</h3>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>First Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('legal_guardian_first_name') }}" required type="text" name="legal_guardian_first_name" placeholder="Full Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Middle Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('legal_guardian_middle_name') }}" required type="text" name="legal_guardian_middle_name" placeholder="Middle Name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Last Name: <span class="text-danger">*</span></label>
                                <input value="{{ old('legal_guardian_last_name') }}" required type="text" name="legal_guardian_last_name" placeholder="Last Name" class="form-control">
                                </div>
                            </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Contact Number: <span class="text-danger">*</span></label>
                                <input value="{{ old('legal_guardian_contact_number') }}" class="form-control" placeholder="Contact Number" name="legal_guardian_contact_number" type="text" required>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <h6>Student Data</h6>
                <fieldset>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="my_class_id">Class: <span class="text-danger">*</span></label>
                                <select onchange="getClassSections(this.value)" data-placeholder="Choose..." required name="my_class_id" id="my_class_id" class="select-search form-control">
                                    <option value=""></option>
                                    @foreach($my_classes as $c)
                                        <option {{ (old('my_class_id') == $c->id ? 'selected' : '') }} value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                </select>
                        </div>
                            </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="year_admitted">Year Admitted: <span class="text-danger">*</span></label>
                                <select data-placeholder="Choose..." required name="year_admitted" id="year_admitted" class="select-search form-control">
                                    <option value=""></option>
                                    {{-- @for($y=date('Y', strtotime('- 1 years')); $y<=date('Y'); $y++)
                                        <option {{ (old('year_admitted') == $y) ? 'selected' : '' }} value="{{ $y }}">{{ $y }}</option>
                                    @endfor --}}
                                    @for($y=date('Y'); $y<=date('Y'); $y++)
                                    <option {{ (old('year_admitted') == $y) ? 'selected' : '' }} value="{{ $y }}" selected>{{ $y }}</option>
                                @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>LRN Number: <span class="text-danger">*</span></label>
                                <input type="text" name="adm_no" placeholder="LRN Number" class="form-control" value="{{ old('adm_no') }}" required>
                            </div>
                        </div>

                        <select data-placeholder="Choose..."  name="my_parent_id" id="my_parent_id" class="form-control" hidden>
                            <option  value=""></option>
                        </select>
                    </div>

                    <div class="row">
                        {{-- <div class="col-md-3">
                            <label for="dorm_id">Dormitory: </label>
                            <select data-placeholder="Choose..."  name="dorm_id" id="dorm_id" class="select-search form-control">
                                <option value=""></option>
                                @foreach($dorms as $d)
                                    <option {{ (old('dorm_id') == $d->id) ? 'selected' : '' }} value="{{ $d->id }}">{{ $d->name }}</option>
                                    @endforeach
                            </select>

                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Dormitory Room No:</label>
                                <input type="text" name="dorm_room_no" placeholder="Dormitory Room No" class="form-control" value="{{ old('dorm_room_no') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Sport House:</label>
                                <input type="text" name="house" placeholder="Sport House" class="form-control" value="{{ old('house') }}">
                            </div>
                        </div> --}}

                        {{-- <div class="col-md-3">
                            <div class="form-group">
                                <label>Admission Number:</label>
                                <input type="text" name="adm_no" placeholder="Admission Number" class="form-control" value="{{ old('adm_no') }}" required>
                            </div>
                        </div> --}}
                    </div>
                </fieldset>
                <input id="verification_code" type="hidden" name="verification_code" value="" />

            </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="verificationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">SMS Verification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Verification Code: <span class="text-danger">*</span></label>
                        <input id="modal_verification_code" type="text" placeholder="Verification Code" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button id="verification_submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    @endsection