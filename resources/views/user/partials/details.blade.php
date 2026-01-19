<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="role_id">@lang('Role')</label>
            <select name="role_id" id="role_id" class="form-control input-solid" {{ $profile ? 'disabled' : '' }}>
                @foreach($roles as $roleId => $roleName)
                    <option value="{{ $roleId }}" {{ ($edit && $user->role->id == $roleId) ? 'selected' : '' }}>
                        {{ $roleName }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="status">@lang('Status')</label>
            <select name="status" id="status" class="form-control input-solid" {{ $profile ? 'disabled' : '' }}>
                @foreach($statuses as $statusId => $statusName)
                    <option value="{{ $statusId }}" {{ ($edit && $user->status->value == $statusId) ? 'selected' : '' }}>
                        {{ $statusName }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="first_name">@lang('First Name')</label>
            <input type="text" class="form-control input-solid" id="first_name"
                   name="first_name" placeholder="@lang('First Name')" value="{{ $edit ? $user->first_name : '' }}">
        </div>
        <div class="form-group">
            <label for="last_name">@lang('Last Name')</label>
            <input type="text" class="form-control input-solid" id="last_name"
                   name="last_name" placeholder="@lang('Last Name')" value="{{ $edit ? $user->last_name : '' }}">
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="phone">@lang('Phone')</label>
            <input type="text" class="form-control input-solid" id="phone"
                   name="phone" placeholder="@lang('Phone')" value="{{ $edit ? $user->phone : '' }}">
        </div>
        <div class="form-group">
            <label for="county">@lang('County')</label>
            <select class="form-control input-solid" id="county" name="county_id">
                @foreach($counties as $countyId => $countyName)
                    <option value="{{ $countyId }}" {{ ($edit && $user->county == $countyName) ? 'selected' : '' }}>
                        {{ $countyName }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="subcounty">@lang('Sub County')</label>
            <select class="form-control input-solid" id="subcounty" name="subcounty_id">
                <option value="">@lang('Select a Sub County')</option>
                @foreach($subcounties as $subcountyId => $subcountyName)
                    <option value="{{ $subcountyId }}" {{ ($edit && $user->sub_county == $subcountyName) ? 'selected' : '' }}>
                        {{ $subcountyName }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="ward">@lang('Ward')</label>
            <select class="form-control input-solid" id="ward" name="ward_id">
                <option value="">@lang('Select a Ward')</option>
                @foreach($wards as $wardId => $wardName)
                    <option value="{{ $wardId }}" {{ ($edit && $user->ward == $wardName) ? 'selected' : '' }}>
                        {{ $wardName }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <!-- JavaScript to handle AJAX requests for dynamic dropdowns -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#county').change(function() {
                    let countyId = $(this).val();
                    if (countyId) {
                        $.ajax({
                            url: '{{ route("get.subcounties") }}',
                            type: 'GET',
                            data: { county_id: countyId },
                            success: function(data) {
                                $('#subcounty').empty();
                                $('#ward').empty();
                                $.each(data, function(key, value) {
                                    $('#subcounty').append('<option value="'+ key +'">'+ value +'</option>');
                                });
                            }
                        });
                    } else {
                        $('#subcounty').empty();
                        $('#ward').empty();
                    }
                });
        
                $('#subcounty').change(function() {
                    let subcountyId = $(this).val();
                    if (subcountyId) {
                        $.ajax({
                            url: '{{ route("get.wards") }}',
                            type: 'GET',
                            data: { subcounty_id: subcountyId },
                            success: function(data) {
                                $('#ward').empty();
                                $.each(data, function(key, value) {
                                    $('#ward').append('<option value="'+ key +'">'+ value +'</option>');
                                });
                            }
                        });
                    } else {
                        $('#ward').empty();
                    }
                });
            });
        </script>        
