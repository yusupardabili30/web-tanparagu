<!-- Static Backdrop -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Static Backdrop Modal
</button>
<!-- staticBackdrop Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <lord-icon
                    src="https://cdn.lordicon.com/lupuorrc.json"
                    trigger="loop"
                    colors="primary:#121331,secondary:#08a88a"
                    style="width:120px;height:120px">
                </lord-icon>
                
                <div class="mt-4">
                    <h4 class="mb-3">You've made it!</h4>
                    <p class="text-muted mb-4"> The transfer was not successfully received by us. the email of the recipient wasn't correct.</p>
                    <div class="hstack gap-2 justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</a>
                        <a href="javascript:void(0);" class="btn btn-success">Completed</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>