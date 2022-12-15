<div id="delete-request-modal" class="modal fade" tabindex="-1" aria-labelledby="title-modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-modal">Remove this Request from the Request Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Are you sure you want to remove this request from the logs?
                </p>
            </div>
            <div class="modal-footer">
                <form action="{{ route($routeName, ['requestID' => $requestInfo->request_id]) }}" method="post">
                    @csrf
                    <button class="btn btn-sm btn-danger">Proceed</button>
                </form>
                <button class="btn btn-sm btn-success" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
