@extends('layouts.app')

@section('content')

<div class="clearfix text-nowrap">

    <h2 class="float-left text-capitalize ml-3">Error Log</h2>
    

    <div class="float-right mt-3">

        <div class="float-left mr-sm-3">

            @if($current_file)
            
            <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}" title="Download file" class="mr-3 text-dark"><span class="fa fa-download fa-lg"></span></a>    
                <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}" title="Clean file" class="mr-3 text-dark"><span class="fa fa-sync fa-lg"></span></a>            
                <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}" title="Delete file" class="text-dark"><span class="fa fa-trash fa-lg"></span></a>

                @if(count($files) > 1)            
                <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}" title="Delete all files"><span class="fa fa-trash-alt fa-lg"></span></a>
                @endif
            
            @endif

        </div>

        @if(isset($results) && $results->total() > 0)
        <div class="float-right">
            <strong>{{$results->firstItem()}}</strong> - <strong>{{$results->lastItem()}}</strong> of <strong>{{number_format($results->total())}}</strong>
        </div>
        @endif

    </div>

</div>



<div class="table-container mt-2">
    
@if ($logs === null)

<div class="h3 text-danger text-center">Log file >50M, please download it.</div>

@else

    <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
        <thead>
            <tr>
                @if ($standardFormat)
                <th>Level</th>
                <th>Context</th>
                <th>Date</th>
                @else
                <th>Line number</th>
                @endif
                <th>Content</th>
            </tr>
        </thead>
        <tbody>

            @foreach($logs as $key => $log)
            <tr data-display="stack{{{$key}}}">
                @if ($standardFormat)
                <td class="text-nowrap text-{{{$log['level_class']}}}">
                    <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                </td>
                <td class="text">{{$log['context']}}</td>
                @endif
                <td class="text-nowrap">{{{$log['date']}}}</td>
                <td class="log-text">
                    @if ($log['stack'])
                    <button type="button" class="float-right expand btn btn-outline-dark btn-sm mb-2 ml-2" data-display="stack{{{$key}}}"><span class="fa fa-search"></span></button>
                    @endif
                    {{{$log['text']}}}
                    @if (isset($log['in_file']))
                    <br/>{{{$log['in_file']}}}
                    @endif
                    @if ($log['stack'])
                    <div class="stack" id="stack{{{$key}}}" style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}</div>
                    @endif
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>

@endif
</div>

@endsection  


@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
@endpush


@push('scripts')
    <!-- Datatables -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script>
      $(document).ready(function () {
        $('.table-container tr').on('click', function () {
          $('#' + $(this).data('display')).toggle();
        });
        $('#table-log').DataTable({
          "order": [$('#table-log').data('orderingIndex'), 'desc'],
          "stateSave": true,
          "stateSaveCallback": function (settings, data) {
            window.localStorage.setItem("datatable", JSON.stringify(data));
          },
          "stateLoadCallback": function (settings) {
            var data = JSON.parse(window.localStorage.getItem("datatable"));
            if (data) data.start = 0;
            return data;
          }
        });
        $('#delete-log, #clean-log, #delete-all-log').click(function () {
          return confirm('Are you sure?');
        });
      });
    </script>
@endpush
