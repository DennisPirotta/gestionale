<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="success_toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-tools me-2"></i>
            <strong class="me-auto">Impostazioni</strong>
            <small>Adesso</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="success_text">
            {{session('message')}}
        </div>
    </div>
</div>
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="error_toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header text-danger">
            <i class="bi bi-tools me-2"></i>
            <strong class="me-auto">Impostazioni</strong>
            <small>Adesso</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="error_text">
            {{session('error')}}
        </div>
    </div>
</div>
@if(session()->has('message'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let t_el = document.getElementById("success_toast")
            let t = new bootstrap.Toast(t_el)
            t.show()
        })
    </script>
@endif
@if(session()->has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let t_el = document.getElementById("error_toast")
            let t = new bootstrap.Toast(t_el)
            t.show()
        })
    </script>
@endif


