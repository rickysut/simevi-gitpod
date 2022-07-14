@extends('layouts.admin')
@section('content')
@include('partials.subheader')
<div class="row">
	<div class="col-12">
		<div id="panel-1" class="panel show" data-panel-sortable data-panel-close data-panel-collapsed>
			<div class="panel-hdr">
				<h2>
					Data | <span class="fw-300"><i>{{ trans('cruds.provinsi.title') }}</i></span>
				</h2>
				@can('provinsi_create')
				<div class="panel-toolbar">
					<a class="btn btn-success btn-xs mr-2" href="{{ route('admin.provinsis.create') }}" data-toggle="tooltip" title="tambah data" data-original-title="tambah data">
						{{ trans('global.add') }} {{ trans('cruds.provinsi.title_singular') }}
					</a>
				</div>
                <button class="btn btn-warning btn-xs mr-2" data-toggle="modal" data-target="#csvImportModal">
                    {{ trans('global.app_csvImport') }}
                </button>
                @include('csvImport.modal', ['model' => 'Provinsi', 'route' => 'admin.provinsis.parseCsvImport'])
				@endcan
			</div>
			<div class="panel-container show">
				<div class="panel-content">
					<div class="row">
						<div class="col-12">
							<div class="table dataTables_wrapper dt-bootstrap4">
								<table class="dtr-inline table table-bordered table-striped table-hover ajaxTable datatable datatable-Provinsi w-100">
									<thead  class="bg-primary-50">

                                        <tr>
                                            <th width="10">

                                            </th>
                                            <th>
                                                {{ trans('cruds.provinsi.fields.kd_prop') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.provinsi.fields.kd_dt1') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.provinsi.fields.nm_prop') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.provinsi.fields.kd_bast') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.provinsi.fields.lat') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.provinsi.fields.lng') }}
                                            </th>
                                            <!--
                                            <th>
                                                {{ trans('cruds.provinsi.fields.kd_satker') }}
                                            </th>
                                            <th>
                                                {{ trans('cruds.satker.fields.id') }}
                                            </th>
                                            -->
                                            <th>
                                                {{ trans('cruds.provinsi.fields.kd_kemenkeu') }}
                                            </th>
                                            <th style="width:15%">
                                                {{ trans('global.actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
  $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('provinsi_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.provinsis.massDestroy') }}",
            className: 'btn-danger btn-sm mr-1',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                    return entry.id
                });

                if (ids.length === 0) {
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                    headers: {'x-csrf-token': _token},
                    method: 'POST',
                    url: config.url,
                    data: { ids: ids, _method: 'DELETE' }})
                    .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

  let dtOverrideGlobals = {
    buttons: dtButtons,
    processing: true,
    serverSide: true,
    retrieve: true,
    aaSorting: [],
    ajax: "{{ route('admin.provinsis.index') }}",
    columns: [
        { data: 'placeholder', name: 'placeholder' },
        { data: 'kd_prop', name: 'kd_prop' },
        { data: 'kd_dt1', name: 'kd_dt1' },
        { data: 'nm_prop', name: 'nm_prop' },
        { data: 'kd_bast', name: 'kd_bast' },
        { data: 'lat', name: 'lat' , class: 'text-right'},
        { data: 'lng', name: 'lng' , class: 'text-right'},
        
        { data: 'kd_kemenkeu', name: 'kd_kemenkeu' },
        { data: 'actions', name: '{{ trans('global.actions') }}' }
    ],
    orderCellsTop: true,
    order: [[ 1, 'asc' ]],
    pageLength: 25,
  };
  let table = $('.datatable-Provinsi').DataTable(dtOverrideGlobals);
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
});

</script>
@endsection