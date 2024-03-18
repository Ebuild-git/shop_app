<div>
    @if (session('success'))
    <div class="bs-toast toast fade show position-absolute top-15 end-0" role="alert" aria-live="assertive"
        aria-atomic="true" id="myToast">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2 text-success"></i>
            <div class="me-auto fw-medium text-success">Notification</div>
            <small class="text-muted">A l'intant</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
@endif
@if (session('success'))
    <div class="bs-toast toast fade show position-absolute top-15 end-0" role="alert" aria-live="assertive"
        aria-atomic="true" id="myToast">
        <div class="toast-header">
            <i class="ti ti-bell ti-xs me-2 text-success"></i>
            <div class="me-auto fw-medium text-success">Notification</div>
            <small class="text-muted">A l'intant</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session('erreur'))
    <div class="bs-toast toast fade show position-absolute top-15 end-0" role="alert" aria-live="assertive"
    aria-atomic="true" id="myToast">
    <div class="toast-header">
        <i class="ti ti-bell ti-xs me-2 text-danger"></i>
        <div class="me-auto fw-medium text-danger">Notification</div>
        <small class="text-muted">A l'intant</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ session('erreur') }}
    </div>
</div>
@endif


<script>
    var myToast = new bootstrap.Toast(document.getElementById('myToast'));
    myToast.show();
    setTimeout(function() {
        myToast.hide();
    }, 5000);
</script>

</div>