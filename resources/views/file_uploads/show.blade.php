@extends('layouts.app')

@section('content')

<h2>{{strtoupper($fileUpload->type)}} Upload <div class="float-right">{{$fileUpload->company->company_name}}</div></h2>

<div class="row mb-4">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">Upload Details</div>
            <div class="card-body text-large">
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-refresh mr-sm-2" aria-hidden="true"></span> <strong>Frequency</strong></div>
                    <div class="col-sm-7"> {{ucwords($fileUpload->frequency)}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-clock-o mr-sm-2" aria-hidden="true"></span> <strong>Time</strong></div>
                    <div class="col-sm-7">
                        @if($fileUpload->frequency == 'hourly')
                        On the hour, every hour
                        @else
                        {{$fileUpload->time}}
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-calendar mr-sm-2" aria-hidden="true"></span> <strong>Last Upload</strong></div>
                    <div class="col-sm-7">
                        @if($fileUpload->last_upload)
                        {{$fileUpload->last_upload->timezone(Auth::user()->time_zone)->format('jS M - H:i')}}
                        @else
                        <i>Never</i>
                        @endif
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-check-square-o mr-sm-2" aria-hidden="true"></span> <strong>Last Status</strong></div>
                    <div class="col-sm-7">@if($fileUpload->last_status)<span class="text-success">Success</span>@else <span class="text-danger">Upload Failed</span> @endif</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-calendar mr-sm-2" aria-hidden="true"></span> <strong>Next Upload</strong></div>
                    <div class="col-sm-7">
                        @if($fileUpload->next_upload)
                        {{$fileUpload->next_upload->timezone(Auth::user()->time_zone)->format('jS M - H:i')}}
                        @else
                        <i>Unknown</i>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">

        <div class="card">
            <div class="card-header">Host Settings
                <div class="float-right">
                    <strong>
                        @if($fileUpload->fileUploadHost->sftp)
                        SFTP
                        @else
                        FTP
                        @endif
                    </strong>
                </div>
            </div>
            <div class="card-body text-large">
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-cloud mr-sm-2" aria-hidden="true"></span> <strong>Host</strong></div>
                    <div class="col-sm-7"> {{$fileUpload->fileUploadHost->host}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-chevron-right mr-sm-2" aria-hidden="true"></span> <strong>Port</strong></div>
                    <div class="col-sm-7"> {{$fileUpload->fileUploadHost->port}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-user mr-sm-2" aria-hidden="true"></span> <strong>Username</strong></div>
                    <div class="col-sm-7"> {{$fileUpload->fileUploadHost->username}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-key mr-sm-2" aria-hidden="true"></span> <strong>Password</strong></div>
                    <div class="col-sm-7"> {{$fileUpload->fileUploadHost->password}}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-5"><span class="fas fa-fw fa-folder-open mr-sm-2" aria-hidden="true"></span> <strong>Directory</strong></div>
                    <div class="col-sm-7"> {{$fileUpload->fileUploadHost->directory}}/{{$fileUpload->upload_directory}}</div>
                </div>
            </div>
        </div>

    </div>
</div>

<h4 class="mb-2">Latest Log Entries <span class="badge badge-pill badge-secondary">{{$fileUpload->getLatestLogs()->count()}}</span></h4>

<table class="table table-striped table-bordered table-sm">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Time</th>
            <th>Output</th>
            <th class="text-center">Upload Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fileUpload->getLatestLogs() as $log)
        <tr>
            <td class="align-middle">{{$loop->iteration}}</td>
            <td class="align-middle">{{$log->created_at->timezone(Auth::user()->time_zone)->format('jS M - Y')}}</td>
            <td class="align-middle">{{$log->created_at->timezone(Auth::user()->time_zone)->format('g:ia')}}</td>
            <td class="align-middle">{!!$log->output!!}</td>
            <td class="text-center align-middle">@if($log->uploaded)<span class="text-success">Success</span>@else <span class="text-danger">Upload Failed</span> @endif</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection