@if($parentModel->documents->count() > 0)

<h4 class="mb-2">Documents <span class="badge badge-pill badge-secondary badge-sm ml-sm-1">{{$parentModel->documents->count()}}</span></h4>

<table class="table table-striped table-bordered mb-5">
    <thead>
        <tr>
            <th>#</th>
            <th>Filename</th>
            <th>Description</th>
            <th>Size</th>
            <th>Uploaded By</th>
            <th>Date / Time</th>
            <th class="text-center">Delete</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($parentModel->documents as $document)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td><a href="{{$document->public_url}}" target="_blank">{{$document->filename}}</a></td>
            <td>{{$document->description}}</td>
            <td>{{formatBytes($document->size)}}</td>
            <td>{{$document->user_name}}</td>
            <td>{{$document->created_at->timezone(Auth::user()->time_zone)->format(Auth::user()->date_format . ' H:i')}}</td>
            <td class="text-center">
                @if(Auth::user()->id == $document->user_id || Auth::user()->hasIfsRole())
                <a href="{{ url('/documents/' . $document->id) }}" title="Delete {{$document->filename}}" class="delete" data-record-name="document" data-parent="{{$modelName}}" data-parent-id="{{$parentModel->id}}" data-progress-indicator="true"><i class="fas fa-times"></i></a>
                @else
                <span class="fas fa-times faded" aria-hidden="true"></span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endif