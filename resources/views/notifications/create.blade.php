@extends('layouts.app')

@section('title', ___('Send Notifications'))
@section('content')
    <div class="iq-card">
        <div class="iq-card-header d-flex justify-content-between">
            <div class="iq-header-title">
                <h4 class="card-title">{{ ___('Send Notifications') }}</h4>
            </div>
        </div>
        <div class="iq-card-body">
            <form id="notificationForm" data-action="{{ route('notifications.send') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col">
                        <label for="name">{{ ___('Notification Title') }}*</label>
                        <input name="title" type="text" class="form-control" id="name"
                            placeholder="{{ ___('Full Name') }}">
                    </div>
                    <div class="col">
                        <label for="body">{{ ___('Notification Body') }}*</label>
                        <input name="body" type="text" class="form-control" id="body"
                            placeholder="{{ ___('Notification Body') }}">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col">
                        <label for="level">{{ ___('Send To') }}*</label>
                            <select name="topic" id="level" class="form-control">
                                <option value="all-users">{{ ___('All Users') }}</option>
                                <option value="users">{{ ___('Users') }}</option>
                                <option value="deliveries">{{ ___('Deliveries') }}</option>
                            </select>
                    </div>
                </div>
                <br>
                <br>
                <div class="form-group">
                    <button form="notificationForm" type="submit" class="btn btn-primary">
                        {{ ___('Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>


    <script>
        var autoForm = $("#notificationForm");

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
                    window.location.href = '{{ route('notifications.create') }}';
                    toastr.success('{{ __('messages.added') }}');
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseJSON);
                    var errorsReturned = xhr.responseJSON.errors;
                    var errorsMessage = xhr.responseJSON.error.message;
                    if (errorsMessage != '') {
                        toastr.error(errorsMessage);
                    }
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
