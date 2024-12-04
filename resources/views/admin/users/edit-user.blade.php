@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.users.index') }}">Clients</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Edit User</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">Client Management</h5>
    </nav>
@stop
@php 
use App\Models\Admin\Adunitpercen;
@endphp
@section('content')

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h5 class="mb-0">{{ __('Edit Client') }}</h5>
            </div>
            <div class="card-body pt-4 p-3">
            <form action="{{ route('admin.users.updateUser', $user->id) }}" method="POST" role="form text-left" enctype="multipart/form-data">
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

                                    <input class="form-control" type="text" placeholder="Name" id="user-name" name="name" value="{{$user->name}}">
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
                                    <input class="form-control" type="email" placeholder="@example.com" id="email" name="email" value="{{$user->email}}">
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
                                <label for="role" class="form-control-label">{{ __('Role') }}</label>
                                <div class="@error('role')border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="role" name="role">
                                        <option value="0" hidden>Select Role</option>
                                        @foreach($roles as $role)
                                            <option @if(count($user->roles->where('id',$role->id)))
                                                    value="{{ $role->name }}"
                                                    selected
                                                @endif>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
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
                                            <option value="0" @if($user->is_active == 0) selected @endif>Not Active</option>
                                            <option value="1"  @if($user->is_active != 0) selected @endif>Active</option>
                                    </select>
                                    @error('is_active')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>


                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ 'Edit Client' }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <style>
    
    select.form-control {
    max-width:100% ! important;
}
    .text-right {
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

$(document).on('click', '.removeitem', function() {
    var unitId = $(this).attr('data-unitit'); // Note the correction: 'data-unitit' to 'data-unitid'

    // Confirm before deletion
    if (confirm("Are you sure you want to delete this item?")) {
        $.ajax({
            url: "{{ url('admin/users') }}/" + unitId + "/deleteUnit",
            type: 'GET',
            data: {
                _token: '{{ csrf_token() }}' // Include CSRF token for Laravel
            },
            success: function(response) {
                console.log('Response:', response);

                if (response.status == true) {
                    console.log('Appending flash message');
                    // Show success message
                    $('body').append('<div class="flash-message" style="position: fixed; top: 20px; right: 20px; background-color: #28a745; color: white; padding: 10px; border-radius: 5px;">Item deleted successfully</div>');

                    // Remove the success message after 1 second
                    setTimeout(function() {
                        $('.flash-message').fadeOut('slow', function() {
                            $(this).remove();
                            location.reload(); // Reload the page after message fades out
                        });
                    }, 1000);
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
});


</script>
@stop
