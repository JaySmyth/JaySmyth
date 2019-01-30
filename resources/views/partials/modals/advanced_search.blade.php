@section('advanced_search')

<div class="modal" id="advanced_search" tabindex="-1" role="dialog" aria-labelledby="advanced_search_label" aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="advanced_search_label">
                    <span class="fas fa-search fa-lg mr-sm-1" aria-hidden="true"></span> Advanced Search
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            {!! Form::Open(['url' => Request::path(), 'method' => 'get', 'class' => 'form-compact', 'autocomplete' => 'off']) !!}

            <div class="modal-body">
                
                @yield('advanced_search_form')

            </div>

            <div class="modal-footer">                
                <button type="submit" class="btn btn-primary btn-shadow">Search <span class="fas fa-arrow-right" aria-hidden="true"></span></button>
            </div>

            {!! Form::Close() !!}

        </div>
    </div>
</div>

@endsection