@extends('layouts.app')

@section('page-title', __('Edit Appraisal'))
@section('page-heading', __('Edit Appraisal for ' . $appraisal->user->first_name . ' ' . $appraisal->user->last_name))

@section('content')

<div class="card">
    <div class="card-body">
        <form action="{{ route('appraisals.update', $appraisal) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Appraisal Date and Week Side by Side -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="mt-4 mb-2 medium h5">@lang('Appraisal Date')</label>
                        <p class="form-control-static">{{ $appraisalDate }}</p>
                        <input type="hidden" name="appraisal_date" value="{{ $appraisalDate }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="mt-4 mb-2 medium h5">@lang('Week')</label>
                        <p class="form-control-static">{{ $period }}</p>
                        <input type="hidden" name="period" value="{{ $period }}">
                    </div>
                </div>
            </div>

            <!-- Ratings Title Styling -->
            <label class="mt-4 mb-2 medium h5">@lang('Ratings')</label>

            @foreach(['motivation', 'resourcefulness', 'leadership', 'discipline', 'teamwork'] as $category)
                <div class="form-group row align-items-center">
                    <label class="col-sm-3 col-form-label">@lang(ucfirst($category))</label>
                    <div class="col-sm-9">
                        <div class="d-flex align-items-center">
                            @foreach(range(1, 5) as $score)
                                <div class="form-check mr-3 mb-2">
                                    <input class="form-check-input @error($category.'_score') is-invalid @enderror"
                                           type="radio"
                                           name="{{ $category }}_score"
                                           id="{{ $category }}_score_{{ $score }}"
                                           value="{{ $score }}"
                                           {{ old($category.'_score', $appraisal->{$category.'_score'}) == $score ? 'checked' : '' }}
                                           required>
                                    <label class="form-check-label" for="{{ $category }}_score_{{ $score }}">{{ $score }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error($category.'_score')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            @endforeach

            <div class="form-group">
                <label for="comments" class="mt-4 mb-2 medium h5">@lang('Comments')</label>
                <textarea class="form-control @error('comments') is-invalid @enderror" id="comments" name="comments" rows="5" required>{{ old('comments', $appraisal->comments) }}</textarea>
                @error('comments')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary" disabled>@lang('Update Appraisal')</button>
        </form>
    </div>
</div>

@stop
