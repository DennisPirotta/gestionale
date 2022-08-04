@if(session()->has('message'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bi bi-tools me-2"></i>
                <strong class="me-auto">Impostazioni</strong>
                <small>Adesso</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{session('message')}}
            </div>
        </div>
    </div>
    <script>
        setTimeout(function () {
            document.getElementById("liveToast").classList.remove("show")
        },3000)
    </script>
@endif

@if(session()->has('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header text-danger">
                <i class="bi bi-bug me-2"></i>
                <strong class="me-auto">Impostazioni</strong>
                <small>Adesso</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                {{session('error')}}
            </div>
        </div>
    </div>
    <script>
        setTimeout(function () {
            document.getElementById("liveToast").classList.remove("show")
        },5000)
    </script>
@endif
