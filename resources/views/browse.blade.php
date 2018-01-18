@extends('vox::index')
@section('page_header')
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">
				<i class="material-icons">directions</i>
				{{ __('Redirects') }}
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
	<div class="row" id="app">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-body panel-database">
					<table id="dataTable" class="table table-hover dataTable no-footer" role="grid" aria-describedby="dataTable_info">
						<thead>
						<tr role="row">
							<th class="@if($filter == 'from' && $sorting == 'desc'){{ 'sorting_desc' }}@elseif($filter == 'from'){{ 'sorting_asc' }}@else{{ 'sorting' }}@endif" onClick="filter_click('{{ $filter }}', '{{ $sorting }}', 'from')">{{__('Redirect from')}}</th>
							<th class="@if($filter == 'to' && $sorting == 'desc'){{ 'sorting_desc' }}@elseif($filter == 'to'){{ 'sorting_asc' }}@else{{ 'sorting' }}@endif" onClick="filter_click('{{ $filter }}', '{{ $sorting }}', 'to')">{{__('To')}}</th>
							<th class="@if($filter == 'type' && $sorting == 'desc'){{ 'sorting_desc' }}@elseif($filter == 'type'){{ 'sorting_asc' }}@else{{ 'sorting' }}@endif" onClick="filter_click('{{ $filter }}', '{{ $sorting }}', 'type')">{{__('Type')}}</th>
							<th class="@if($filter == 'created_at' && $sorting == 'desc'){{ 'sorting_desc' }}@elseif($filter == 'created_at'){{ 'sorting_asc' }}@else{{ 'sorting' }}@endif" onClick="filter_click('{{ $filter }}', '{{ $sorting }}', 'created_at')">{{__('Created')}}</th>
							<th class="@if($filter == 'updated_at' && $sorting == 'desc'){{ 'sorting_desc' }}@elseif($filter == 'updated_at'){{ 'sorting_asc' }}@else{{ 'sorting' }}@endif" onClick="filter_click('{{ $filter }}', '{{ $sorting }}', 'updated_at')">{{__('Updated')}}</th>
							<th class="actions">{{__('Actions')}}</th></tr>
						</thead>
						<tbody>
						@foreach($redirects as $redirect)
							<tr role="row" class="odd">
								<td><a href="/{{ $redirect->from }}" target="_blank">{{ $redirect->from }}</a></td>
								<td><a href="/{{ $redirect->to }}" target="_blank">{{ $redirect->to }}</a></td>
								<td>{{ $redirect->type }}</td>
								<td>{{ Carbon\Carbon::parse($redirect->created_at)->toDayDateTimeString() }}</td>
								<td>{{ Carbon\Carbon::parse($redirect->updated_at)->toDayDateTimeString() }}</td>
								<td>
									<div class="btn btn-sm btn-simple btn-danger pull-right delete" data-id="{{ $redirect->id }}" id="delete-1">
										<i class="material-icons">close</i>
									</div>
									<a href="{{ route('voyager.redirects.edit', $redirect->id) }}" class="btn btn-sm btn-simple btn-primary pull-right edit">
										<i class="material-icons">edit</i>
									</a>
								</td>
							</tr>
						@endforeach
						</tbody>
					</table>
					@if(count($redirects) < 1)
						<p style="padding: 10px 5px;">{{__('No Redirects to display.')}}</p>
					@endif
				</div>
				<div class="panel-footer">
					<div class="pull-right">
						{{ $redirects->appends(['filter' => Request::get('filter'), 'sorting' => Request::get('sorting')])->links() }}
					</div>
					<div style="clear:both"></div>
				</div>
			</div>
			<div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">{{__('Are you sure you want to delete this Redirect?')}}</h4>
						</div>
						<div class="modal-footer">
							<form action="{{ route('vox.redirects.delete') }}" id="delete_form" method="POST">
								{{ method_field("DELETE") }}
								{{ csrf_field() }}
								<input type="hidden" value="" id="delete_id" name="id">
								<input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{__('Yes, delete this redirect')}}">
							</form>
							<button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{__('Cancel')}}</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('js')
<script>
	$('document').ready(function(){
		$('td').on('click', '.delete', function (e) {
            let form = $('#delete_form')[0];
            $('#delete_id').val( $(this).data('id') );
            $('#delete_modal').modal('show');
        });
	});
	function filter_click(filter, sorting, filter_by){
		if(filter === filter_by){
			if(sorting.toLowerCase() === 'desc'){
				window.location = window.location.pathname + '?filter=' + filter_by + '&sorting=asc';
			} else {
				window.location = window.location.pathname + '?filter=' + filter_by + '&sorting=desc';
			}
		} else {
			window.location = window.location.pathname + '?filter=' + filter_by;
		}
	}
</script>
@stop