<div class="modal" id="feedback_modal" tabindex="-1" role="dialog" aria-labelledby="feedback_modal_label"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-body text-center">
                <h3 class="mt-3 mb-4">How did we do today?</h3>

                <div class="row justify-content-center mb-4">
                    <div class="col-auto"><i class="far fa-frown fa-3x text-muted smiley" data-smiley="frown"></i></div>
                    <div class="col-auto"><i class="far fa-meh fa-3x text-muted smiley" data-smiley="meh"></i></div>
                    <div class="col-auto"><i class="far fa-smile fa-3x text-muted smiley" data-smiley="smile"></i></div>
                </div>

                <form>
                    <input type="hidden" id="smiley">
                    <textarea id="comments" rows="4" class="form-control mb-3" placeholder="Comments" maxlength="200"></textarea>
                    <button type="button" id="send-feedback" class="btn btn-lg btn-primary btn-block mb-3">Send Feedback</button>
                    <div id="feedback-error" class="text-danger"></div>
                </form>

            </div>

        </div>
    </div>
</div>