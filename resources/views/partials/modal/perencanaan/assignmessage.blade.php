<!-- Varying modal content -->
<div class="modal fade" id="assignmessageModal" tabindex="-1" aria-labelledby="varyingcontentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assignmessageModal">New message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('penugasandokumen.store') }}" method="POST">
            @csrf
                <div class="modal-body">
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">Recipient:</label>
                            <input type="text" class="form-control" id="user_id" name="user_id[]">
                            <input type="text" class="form-control" id="txt_dokumen_kegiatan_id" name="dokumen_kegiatan_id">
                            <input type="text" class="form-control" id="txt_kegiatan_id" name="kegiatan_id">
                            <input type="text" class="form-control" id="key_msg" name="key_msg">
                            <input type="text" class="form-control" id="recipient-name">
                        </div>
                        <div class="mb-3">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control" id="message" name="message" id="message"></textarea>
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