<div class="col-sm-6 col-md-4 mb-3">
    <div class="card h-100 @if($user->id === auth()->id()) bg-secondary bg-opacity-25 @endif"> <!--  bg-primary bg-opacity-25 -->
        <div class="card-body">
            <i class="bi bi-person"></i>
            <span class="card-title">Dipendente {{$user->id}}</span>
            @if($user->company->id === 1)
                <span class="badge bg-primary ms-1">3D</span>
            @else
                <span class="badge bg-success ms-1">S+H</span>
            @endif
            <p class="card-text">{{$user->surname}} {{$user->name}}</p>
            <div class="d-flex justify-content-center">
                <a>
                    <button class="btn btn-outline-primary me-2" onclick="modify({{$user->id}},'{{$user->name}}')">
                        <i class="bi bi-pencil-square me-1"></i>
                        Modifica
                    </button>
                </a>
                <form method="POST" action="{{ route('users.destroy',$user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger" onclick="return confirm('Sicuro di voler Eliminare?')">
                        <i class="bi bi-trash me-1"></i>
                        Elimina
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>