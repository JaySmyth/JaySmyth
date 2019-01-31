<div class="modal" id="tracking_modal" tabindex="-1" role="dialog" aria-labelledby="tracking_modal_label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="tracking_modal_label"><span class="fas fa-truck fa-lg mr-sm-1" aria-hidden="true"></span> Track a Shipment</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-large mb-4">Need the status of your shipment or a proof of delivery? Enter your tracking number below.</p>
                
                <form class="form-inline" role="form" method="POST" action="{{ url('/track') }}">
                    {!! csrf_field() !!}
                    <input type="text"  name="tracking_number" id="tracking_number" value="{{ old('tracking_number') }}" class="form-control form-control-lg mr-sm-1"  placeholder="Tracking number" required>
                    <button class="btn btn-lg btn-primary" type="submit">Track <span class="fas fa-arrow-right" aria-hidden="true"></span></button>
                </form>
                
            </div>

        </div>
    </div>
</div>