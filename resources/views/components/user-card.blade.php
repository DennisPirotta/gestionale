<div class="col-sm-6 col-md-4 mb-3">
    <div class="card h-100 @if($user->id === auth()->id()) bg-secondary bg-opacity-25 @endif"> <!--  bg-primary bg-opacity-25 -->
        <div class="card-body space">
            <i class="bi bi-person"></i>
            <span class="card-title">{{$user->surname}} {{$user->name}}</span>
            @if($user->company->id === 1)
                <span class="badge bg-primary ms-1">3D</span>
            @else
                <span class="badge bg-success ms-1">S+H</span>
            @endif
            <div class="my-2">
                <div><b>{{ $user->getLeftHolidays() }}</b> ore di ferie totali</div>
                <div><b>{{ $user->holidays - $user->getLeftHolidays() }}</b> ore di ferie utilizzate</div>
                <div><b>{{ $user->getLeftHolidays() }}</b> ore di ferie rimanenti</div>
            </div>
            <div class="d-flex justify-content-center">
                <a href="{{ route('users.show',$user->id) }}">
                    <button class="btn btn-outline-primary me-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Dettagli
                    </button>
                </a>
                @if($hired)
                    <form method="POST" action="{{ route('users.resigned',$user->id) }}">
                        @csrf
                        @method('PUT')
                        <button class="btn btn-outline-danger" onclick="return confirm('Contrassegnare il dipendente come dimesso?')">
                            <i class="bi bi-box-arrow-right me-1"></i>
                            Dimesso
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('users.destroy',$user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-danger" onclick="return confirm('Sicuro di voler Eliminare?')">
                            <i class="bi bi-trash me-1"></i>
                            Elimina
                        </button>
                    </form>
                @endif

            </div>
        </div>
    </div>
</div>
