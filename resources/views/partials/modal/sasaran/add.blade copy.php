<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-info-subtle">
                <h5 class="modal-title" id="exampleModalLabel">Tambah {{ $tittle }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form class="tablelist-form" autocomplete="off">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div id="modal-id" style="display: none;">
                                <label for="orderId" class="form-label">ID</label>
                                <input type="text" id="orderId" class="form-control" placeholder="ID" value="#VLZ462" readonly="">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div>
                                <label for="tasksTitle-field" class="form-label">Title</label>
                                <input type="text" id="tasksTitle-field" class="form-control" placeholder="Title" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="client_nameName-field" class="form-label">Client Name</label>
                                <input type="text" id="client_nameName-field" class="form-control" placeholder="Client Name" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div>
                                <label for="assignedtoName-field" class="form-label">Assigned To</label>
                                <input type="text" id="assignedtoName-field" class="form-control" placeholder="Assigned to" required="">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label for="date-field" class="form-label">Create Date</label>
                            <input type="text" id="date-field" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="d M, Y" placeholder="Create Date" required="" readonly="readonly">
                        </div>
                        <div class="col-lg-6">
                            <label for="duedate-field" class="form-label">Due Date</label>
                            <input type="text" id="duedate-field" class="form-control flatpickr-input" data-provider="flatpickr" data-date-format="d M, Y" placeholder="Due Date" required="" readonly="readonly">
                        </div>
                        <div class="col-lg-6">
                            <label for="ticket-status" class="form-label">Status</label>
                            <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-control choices__input" data-plugin="choices" name="ticket-status" id="ticket-status" hidden="" tabindex="-1" data-choice="active"><option value="" data-custom-properties="[object Object]">Status</option></select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">Status</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Status" placeholder=""><div class="choices__list" role="listbox"><div id="choices--ticket-status-item-choice-5" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="5" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Status</div><div id="choices--ticket-status-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="Closed" data-select-text="Press to select" data-choice-selectable="">Closed</div><div id="choices--ticket-status-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Inprogress" data-select-text="Press to select" data-choice-selectable="">Inprogress</div><div id="choices--ticket-status-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="New" data-select-text="Press to select" data-choice-selectable="">New</div><div id="choices--ticket-status-item-choice-4" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="4" data-value="Open" data-select-text="Press to select" data-choice-selectable="">Open</div></div></div></div>
                        </div>
                        <div class="col-lg-6">
                            <label for="priority-field" class="form-label">Priority</label>
                            <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false"><div class="choices__inner"><select class="form-control choices__input" data-plugin="choices" name="priority-field" id="priority-field" hidden="" tabindex="-1" data-choice="active"><option value="" data-custom-properties="[object Object]">Priority</option></select><div class="choices__list choices__list--single"><div class="choices__item choices__placeholder choices__item--selectable" data-item="" data-id="1" data-value="" data-custom-properties="[object Object]" aria-selected="true">Priority</div></div></div><div class="choices__list choices__list--dropdown" aria-expanded="false"><input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Priority" placeholder=""><div class="choices__list" role="listbox"><div id="choices--priority-field-item-choice-4" class="choices__item choices__item--choice is-selected choices__placeholder choices__item--selectable is-highlighted" role="option" data-choice="" data-id="4" data-value="" data-select-text="Press to select" data-choice-selectable="" aria-selected="true">Priority</div><div id="choices--priority-field-item-choice-1" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="1" data-value="High" data-select-text="Press to select" data-choice-selectable="">High</div><div id="choices--priority-field-item-choice-2" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="2" data-value="Low" data-select-text="Press to select" data-choice-selectable="">Low</div><div id="choices--priority-field-item-choice-3" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="3" data-value="Medium" data-select-text="Press to select" data-choice-selectable="">Medium</div></div></div></div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer" style="display: block;">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success" id="add-btn">Add Ticket</button>
                        <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
