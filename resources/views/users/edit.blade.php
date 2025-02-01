@extends('layouts.app')

@section('title', ___('Edit User'))
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Edit User') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            {{-- @dd(getImageUrl($user->user_img)); --}}
            <form id="userForm" data-action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group row align-items-center">
                    <div class="col-md-12">
                        <div class="profile-img-edit">
                            <img class="profile-pic"
                                src="{{ $user->profile_image ?? asset('assets/images/user/default_user.png') }}"
                                alt="profile-pic">
                            <div class="p-image">
                                <i class="ri-pencil-line upload-button"></i>
                                <input class="file-upload" name="profile_image" type="file" accept="image/*" />
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Full Name') }}*</label>
                        <input name="name" type="text" class="form-control" id="name" value="{{ $user->name }}"
                            placeholder="{{ ___('Full Name') }}">
                    </div>
                    <div class="col">
                        <label for="phone">{{ ___('Phone') }}*</label>
                        <input name="phone" type="text" class="form-control" id="phone" value="{{ $user->phone }}"
                            placeholder="201000000000">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="level">{{ ___('Status') }}*</label>
                        <select name="status" id="level" class="form-control">
                            <option @if($user->status) selected @endif value="1">{{ ___('Active') }}</option>
                            <option @if(!$user->status) selected @endif value="0">{{ ___('Inactive') }}</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="password">{{ ___('Password') }}*</label>
                        <input name="password" type="password" class="form-control" id="password"
                            placeholder="{{ ___('Leave Plank To Keep Current') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="level">{{ ___('User Type') }}*</label>
                            <select name="user_type" id="level" class="form-control">
                                <option @if($user->user_type == 'user') selected @endif value="user">{{ ___('User') }}</option>
                                <option @if($user->user_type=='delivery') selected @endif value="delivery">{{ ___('Delivery') }}</option>
                            </select>
                    </div>
                    <div class="col">
                        <label for="level">{{ ___('Phone Verified') }}*</label>
                            <select name="phone_verified" id="level" class="form-control">
                                <option @if($user->phone_verified_at) selected @endif value="1">{{ ___('Verified') }}</option>
                                <option @if(!$user->phone_verified_at) selected @endif value="0">{{ ___('Not Verified') }}</option>
                            </select>
                    </div>
                </div>
                <br>
                <div class="form-group">
                    <button form="userForm" type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
            <div id="validationErrors"></div>
        </div>
    </div>


    <script>
        var autoForm = $("#userForm");

        $(autoForm).on('submit', function(event) {
            event.preventDefault();

            var url = $(this).attr('data-action');

            var formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(response) {
                    console.log(response);
                    window.location.href = '{{ route('users.edit', $user->id) }}';
                    toastr.success('{{ __('messages.updated') }}');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                    var errorsReturned = xhr.responseJSON.errors;
                    if (errorsReturned) {
                        Object.keys(errorsReturned).forEach(function(key) {
                            errorsReturned[key].forEach(function(error) {
                                toastr.error(error);
                            });
                        });
                    }
                }
            });
        });
    </script>

@endsection
