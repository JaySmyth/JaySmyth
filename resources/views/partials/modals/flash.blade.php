@if(session()->has('flash'))

@push('scripts')

<script>
    swal({
        title: "{{session('flash.title')}}",
        
        @if(session('flash.html'))
            html: "{!! session('flash.message') !!}",
        @elseif(is_array(session('flash.message')))
            <?php $msg = '<li>' . implode( '</li><li>', session('flash.message')) . '</li>'; ?>
            html: '<ul class="text-left">{!! $msg !!}</ul>',
        @else
            text: "{{session('flash.message')}}",
        @endif
                        
        type: "{{session('flash.type')}}",
        
        @if(session('flash.overlay'))
            confirmButtonText: "OK",
        @else
            timer: 1200,
            showConfirmButton: false
        @endif                
    });
</script>

@endpush

@endif