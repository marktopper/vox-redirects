@extends('vox::index')
@section('page_header')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                <i class="material-icons">directions</i>
                {{ __('Add New Redirect') }}
            </h1>
            <div class="add_buttons">
                <a href="{{ route('voyager.redirects.add') }}" class="btn btn-success">
                    <i class="material-icons">add</i> <span>{{ __('Add New') }}</span>
                </a>
            </div>
        </div>
    </div>
@stop
@section('content')
    <div id="voyager-notifications"></div>
    <div class="row edit-add-type">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <form role="form" class="form-edit-add" action="@if(isset($redirect->id)){{ route('vox.redirects.edit.post') }}@else{{ route('vox.redirects.add.post') }}@endif" method="POST">
                    @if(isset($redirect->id))
                        <input type="hidden" name="id" value="{{ $redirect->id }}">
                    @endif
                    {{ csrf_field() }}
                    <div class="panel-body">
                        <div class="form-group col-md-5">
                            <label for="from">{{__('Redirect from (don\'t include the full URL, only the URI, ex. \'/awesome/page\')')}}</label>
                            <input required type="text" class="form-control" name="from" placeholder="{{__('Redirect from')}}" value="@if(isset($redirect->from)){{ $redirect->from }}@endif">
                        </div>
                        <div class="form-group  col-md-5">
                            <label for="to">{{__('Redirect To')}}</label>
                            <input required type="text" class="form-control" name="to" placeholder="{{__('Redirect To')}}" value="@if(isset($redirect->to)){{ $redirect->to }}@endif">
                        </div>
                        <div class="form-group  col-md-2">
                            <label for="type" style="display:block;">{{__('Type')}}</label>
                            <select name="type" id="type">
                                <option value="301" @if(isset($redirect->type) && $redirect->type == '301'){{ 'selected' }}@endif>301</option>
                                <option value="302" @if(isset($redirect->type) && $redirect->type == '302'){{ 'selected' }}@endif>302</option>
                                <option value="303" @if(isset($redirect->type) && $redirect->type == '303'){{ 'selected' }}@endif>303</option>
                                <option value="307" @if(isset($redirect->type) && $redirect->type == '307'){{ 'selected' }}@endif>307</option>
                                <option value="308" @if(isset($redirect->type) && $redirect->type == '308'){{ 'selected' }}@endif>308</option>
                            </select>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <button type="submit" class="btn btn-primary save">{{__('Save')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop