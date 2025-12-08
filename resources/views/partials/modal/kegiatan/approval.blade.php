<!-- Varying modal content -->
<div class="modal fade" id="assignmessageModal" tabindex="-1" aria-labelledby="varyingcontentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tittleassignmessage">Approval message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('approvalpenugasan.store') }}" method="POST">
            @csrf
                <div class="modal-body">
                        <label for="message-text" class="col-form-label" id="label_message" name="label_message">Message: </label>
                        <div class="mb-3" style="display: none">
                            <label for="recipient-name" class="col-form-label">Kegiatan ID:</label>
                            <input type="text" class="form-control" id="txt_kegiatan_id" name="txt_kegiatan_id">
                        </div>
                        <div class="mb-3" style="display: none">
                            <label for="recipient-name" class="col-form-label">Status ID:</label>
                            <input type="text" class="form-control" id="txt_approval_id" name="txt_approval_id">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" id="message" name="message"></textarea>
                        </div>               
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Send message</button>
                </div>
            </form>
        </div>
    </div>
  </div>