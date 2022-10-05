<div class="col-sm-6 col-md-4 mb-3">
    <div class="card h-100"> <!--  bg-black bg-opacity-25 -->
        <div class="card-body">
            <i class="bi bi-person"></i>
            <span class="card-title">Cliente {{ $customer->id }}</span>
            <p class="card-text">{{$customer->name}}</p>
            <div class="d-flex justify-content-center">
                <a >
                    <button class="btn btn-outline-primary me-2" onclick="modify({{ $customer }})">
                        <i class="bi bi-pencil-square me-1"></i>
                        Modifica
                    </button>
                </a>
                <form method="POST" action="/clienti/{{$customer->id}}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger"
                            onclick="return confirm('Sicuro di voler Eliminare?')">
                        <i class="bi bi-trash me-1"></i>
                        Elimina
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>