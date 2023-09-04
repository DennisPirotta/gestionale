<div class="col-sm-6 col-md-4 mb-3">
    <div class="card h-100 bg-opacity-25 bg-{{$commessa->status->color}}">
        <!--  bg-black bg-opacity-25 -->
        <div class="card-body">
            <i class="bi bi-building"></i>
            <div class="card-title">
                Commessa Interna {{ $commessa->innerCode }}
                @if($commessa->outerCode ?? false)
                    <br>Commessa Esterna {{ $commessa->outerCode }}
                @endif
            </div>
            <a href="{{ route('orders.index',['company' => $commessa->company->name]) }}">
                @if($commessa->company->id === 1)
                    <span class="badge text-bg-primary bg-opacity-100">3D</span>
                @else
                    <span class="badge text-bg-success bg-opacity-100">S+H</span>
                @endif
            </a>
            <a href="{{ route('orders.index',['customer' => $commessa->customer->name]) }}">
                <h6 class="card-subtitle my-2 text-muted">{{$commessa->customer->name}}</h6>
            </a>
            <p class="card-text">{{$commessa->description}}</p>
            <div class="d-flex justify-content-center">
                <a href="{{ route('orders.show',$commessa->id) }}">
                    <button class="btn btn-outline-primary me-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Dettagli
                    </button>
                </a>

                <form method="POST" action="{{ route('orders.destroy',$commessa->id) }}">
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
