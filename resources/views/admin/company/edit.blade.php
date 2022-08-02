@extends('admin.layouts.app')
@section('title',$title)
@section('user_name',$user->name)

@section('content')
<div class="content-wrapper">
   <div class="row">
   <div class="col-md-8 offset-md-2 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                @if (\Session::has('error'))
                  <div class="alert alert-danger">
                     {!! \Session::get('error') !!}
                  </div>
                @endif
                  <h4 class="card-title">{{$title}}</h4>

                  <form class="forms-sample" method="post" enctype="multipart/form-data" id="create_company" action="{{route("companies.update", [$company->id])}}">
                  @csrf
                  <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                      <label for="name"> {{ trans('sentence.name') }}</label>
                      <input type="text" name="name" class="form-control" id="name" placeholder="{{trans('sentence.company'). trans('sentence.name') }}"
                       value="{{$company->name}}" required />
                    @if ($errors->has('name'))
                    <div class="error">{{ $errors->first('name') }}</div>
                    @endif
                    </div>
                    <div class="form-group">
                      <label for="emil">{{ trans('sentence.email') }}</label>
                      <input type="email" name="email" class="form-control" id="email" placeholder="{{trans('sentence.company'). trans('sentence.email') }}"
                       value="{{$company->email}}" required />
                    @if ($errors->has('email'))
                    <div class="error">{{ $errors->first('email') }}</div>
                    @endif
                    </div>
                    <div class="form-group">
                      <label for="emil">{{ trans('sentence.website') }}</label>
                      <input type="text" name="website" class="form-control" id="website" placeholder="{{trans('sentence.company'). trans('sentence.website') }}"
                       value="{{$company->website}}" required />
                    @if ($errors->has('website'))
                    <div class="error">{{ $errors->first('website') }}</div>
                    @endif
                    </div>

                  <div class="form-group">
                      <label>{{ trans('sentence.logo') }} </label>
                      <input type="file" name="logo" class="form-control" accept="image/*"   >
                      @if ($errors->has('logo'))
                        <div class="error">{{ $errors->first('logo') }}</div>
                    @endif
                    </div>
                    <button type="submit" class="btn own_btn_background mr-2">{{ trans('sentence.update') }}</button>
                  </form>
                </div>
              </div>
            </div>
   </div>
</div>

@endsection

@section('footerScript')
@parent
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
@endsection
