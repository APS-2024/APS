@extends('layouts.admin')

@section('breadcrumb')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Clients</li>
        </ol>
        <h5 class="font-weight-bolder mb-0">Client Management</h5>
    </nav>
@stop

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-10 d-flex align-items-center">
                            <h6>All Client</h6>
                        </div>
                        <div class="col-2 text-end">

<a class="btn bg-gradient-dark mb-0 redirect-mark" href="{{ route('admin.users.create') }}"><i class="fas fa-plus"></i>&nbsp;&nbsp;Add Client</a>
</div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Client</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Phone</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Since</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div>
                                            <!-- If User as Image it show up if not then a random picture will be displayed -->
                                            <img src="{{ $user->avatar ?? $user->defAvatar($user->id) }}" class="avatar avatar-sm me-3" alt="avatar">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                            <p class="text-xs text-secondary mb-0">{{ $user->username ? '@' . $user->username : '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->email }}</span>
                                </td>
                                <td>
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->phone ? '+'.$user->phone : '---' }}</span>
                                </td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="text-secondary text-xs font-weight-bold">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($user->is_active == 0)
                                        <span class="badge badge-sm bg-gradient-secondary">InActive</span>
                                    @else
                                        <span class="badge badge-sm bg-gradient-success">ACTIVE</span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-xs font-weight-bold">{{ $user->created_at->format('d-m-Y') }}</span>
                                </td>
                                <td class="text-center">
                                    @foreach($user->roles as $role)
                                        @if(auth()->user()->id != $user->id)
                                        <form id="userDelete" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                           
                                            <a href="{{ route('admin.users.editUser', $user->id) }}" @if(auth()->user()->hasRole($role->name)) hidden @endif class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="Edit client">
                                                <i class="fas fa-user-edit text-secondary-new"></i>
                                            </a>
                                     
                                            <a href="{{ route('admin.users.view', $user->id) }}" @if(auth()->user()->hasRole($role->name)) hidden @endif class="mx-3" data-bs-toggle="tooltip" data-bs-original-title="view">
                                                <i class="fas fa-eye text-secondary-new"></i>
                                            </a>
                                                <button class="cursor-pointer fas fa-trash text-danger" style="border: none; background: no-repeat;" data-bs-toggle="tooltip" @if(auth()->user()->hasRole($role->name)) hidden @endif data-bs-original-title="Delete User"></button>
                                                                        </form>
                                        @endif
                                    @endforeach
                                </td>
                               


                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <style>
/* Toggle Switch Styles */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #36c2ad;
  transition: .4s;
  border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  border-radius: 50%;
  left: 4px;
  bottom: 4px;
  background-color: white;
  transition: .4s;
}

input:checked + .slider {
  background-color: #ea0606;
}

input:checked + .slider:before {
  transform: translateX(26px);
}

/* Rounded slider */
.round {
  border-radius: 34px;
}



.toggler.off {
    background: red;
    border-right-width: 2px;
    border-left-width: 15px;
}

    </style>
@stop

@section('scripts')


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$(document).ready(function(){
        $('.toggler').click(function(){
            $(this).toggleClass('off');
            var userallow;

            // Check if the 'off' class is present
            if ($(this).hasClass('off')) {
                userallow = 0;
                $('#toggleValue').val(userallow);
                console.log('Toggled off, value is 0');
            } else {
                userallow = 1;
                $('#toggleValue').val(userallow);
                console.log('Toggled on, value is 1');
            }

            var userid = $(this).attr('data-user');
            console.log(userid);

            var url = "{{ route('allowDisallow', ['id' => '__userid__']) }}";
            url = url.replace('__userid__', userid);

            // Send the AJAX request
            $.ajax({
                url: url,
                type: 'GET',
                data: {
                    user_allow: userallow,  // Sending the userallow value
                    _token: '{{ csrf_token() }}' // CSRF token for security
                },
                success: function(response) {
                   if(response.user_allow == 0){
                    $('body').append('<div class="flash-message" style="position: fixed; top: 20px; right: 20px; background-color: #28a745; color: white; padding: 10px; border-radius: 5px;">User disallow status updated successfully.</div>');
                   }else{

                    $('body').append('<div class="flash-message" style="position: fixed; top: 20px; right: 20px; background-color: #28a745; color: white; padding: 10px; border-radius: 5px;">User allow status updated successfully.</div>');

                   }
// Remove the success message after 1 second
setTimeout(function() {
    $('.flash-message').fadeOut('slow', function() {
        $(this).remove();
        location.reload(); // Reload the page after message fades out
    });
}, 1000);



                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    });

  

</script>

@stop