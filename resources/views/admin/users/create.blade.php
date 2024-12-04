@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.users.index') }}">Clients</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Create Client</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">Client Management</h5>
    </nav>
@stop
@php 
use App\Models\AdunitReport;
@endphp
@section('content')

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h5 class="mb-0">{{ __('Add Client') }}</h5>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($errors->any())
                        <div class="alert alert-primary alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $message)
                            <ul style="list-style-type: none;">
                                <li>
                                     <span class="alert-text text-white">{{$message}}</span>
                                </li>
                            </ul>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                <i class="fa fa-close" aria-hidden="true"></i>
                            </button>
                        </div>
                    @endif
                  
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group has-validation">
                                <label for="user-name" class="form-control-label">{{ __('Full Name') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">

                                @if($selectedOptions)
@php
                                 $unit_value=implode(',',$selectedOptions);
                                    @endphp
                                @endif
                                <input class="form-control" type="hidden"  name="ad_unit_id" value="">

                                    <input class="form-control" type="text" placeholder="Name" id="user-name" name="name">
                                    @error('name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">{{ __('Email') }}</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="email" placeholder="@example.com" id="email" name="email">
                                    @error('email')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="avatar" class="form-control-label">{{ __('Profile Avatar') }}</label>
                                <div class="@error('avatar')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="file" placeholder="Choose File" id="avatar" name="avatar">
                                    @error('avatar')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">{{ __('Password') }}</label>
                                <div class="@error('password')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="password" placeholder="Password" id="password" name="password">
                                    @error('password')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                <i class="input-icon fa fa-eye" id="togglePassword"></i>
                               <span id="generatePasswordBtn" class="justify-content-end mt-2"> <i class="fa fa-refresh"></i>Generate Password</span>
                                <!-- <button class="btn btn-outline-secondary" type="button" id="generatePasswordBtn">Generate Random Password</button> -->

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-control-label">{{ __('Re-Enter Password') }}</label>
                                <div class="@error('password_confirmation')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Re-Enter Password" id="password_confirmation" name="password_confirmation">
                                    @error('password_confirmation')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role" class="form-control-label">{{ __('Role') }}</label>
                                <div class="@error('role')border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="role" name="role">
                                        <option value="0" hidden selected>Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="form-text text-xs text-bold ps-2">Default is Client</span>
                                    @error('role')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_active" class="form-control-label">{{ __('Active') }}</label>
                                <div class="@error('is_active')border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="is_active" name="is_active">
                                        <option value="0" selected>Not Active</option>
                                        <option value="1">Active</option>
                                    </select>
                                    @error('is_active')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Create Client' }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop
<style>
select.form-control {
    max-width:100% ! important;
}

span#generatePasswordBtn {
    float: right;
    cursor: pointer;
    color: #36c2ad;
}

i#togglePassword {
    cursor: pointer;
    margin-top: -29px;
    float: right;
    margin-right: 24px;
}

</style>

<style>.text-right {
    text-align: right !important;
    margin-top: 12px;
}

.g-items-header {
    font-weight: bold;
    border: solid 1px #c4cdd5;
    padding: 10px;
    text-align: center;
}
.item {
    border: solid 1px #c4cdd5;
    border-top: 0px;
    padding: 0px 15px;
}
.form-group-item .g-items .item > .row > div {
    padding: 10px;
    border-right: 1px solid #c4cdd5;
}


</style>
@section('scripts')
<script>
        document.getElementById('generatePasswordBtn').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const password_confirmation = document.getElementById('password_confirmation');
            const randomPassword = generateRandomPassword(12); // You can change the length
            passwordField.value = randomPassword;
            password_confirmation.value = randomPassword;
        });

        function generateRandomPassword(length) {
            const charset = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+<>?';
            let password = '';
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }
            return password;
        }

        const togglePassword = document.querySelector('#togglePassword');
  const password = document.querySelector('#password');

  togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});


    </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded -->

   <script>

/*import BookingCoreAdaterPlugin from "../../../../resources/admin/js/ckeditor/uploadAdapter";*/




$(".form-group-item").each(function () {
   let container = $(this);
   $(this).on('click', '.btn-remove-item', function () {
       $(this).closest(".item").remove();
   });
   $(this).on('press', 'input,select', function () {
       let value = $(this).val();
       $(this).attr("value", value);
   });
});
$(".form-group-item .btn-add-item").click(function () {
   let number = $(this).closest(".form-group-item").find(".g-items .item:last-child").data("number");
   if (number === undefined) number = 0;
   else number++;
   let extra_html = $(this).closest(".form-group-item").find(".g-more").html();
   extra_html = extra_html.replace(/__name__=/gi, "name=");
   extra_html = extra_html.replace(/__number__/gi, number);
   $(this).closest(".form-group-item").find(".g-items").append(extra_html);
});



</script>
    @stop