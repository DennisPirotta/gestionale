@extends('layouts.app')
@section('content')
    <div class="container my-5 p-4 shadow-sm">
        <h1>Chiavi di Accesso</h1>
        <p>Le chiavi di accesso sono utilizzate per permettere la registrazione di nuovi utenti sul portale</p>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Aggiungi Chiave</button>
        <hr class="mb-5">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Nome</th>
                <th scope="col">Chiave<small class="ms-1">(Clicca per copiare)</small></th>
                <th scope="col">Creazione</th>
                <th scope="col">Azioni</th>
            </tr>
            </thead>
            <tbody>
            @foreach($keys as $key)
                @php($date = Carbon\Carbon::parse($key->created_at))
                <tr class="align-middle">
                    <th scope="row">{{ $key->name }}</th>
                    <td class="keyBox">
                        <span><i class="bi bi-eye fs-5 me-3 align-middle toggle-visibility"></i></span>
                        <label for="key"></label>
                        <span id="hide" class="hide"></span><input type="password" id="key" class="pe-2 key" value="{{ Crypt::decryptString($key->key) }}" readonly>
                    </td>
                    <td>{{ $date->translatedFormat('D d M Y') }}</td>
                    <td class="d-flex">
                        <form action="{{ route('access.destroy',$key->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger" onclick="return confirm('Sicuro di voler eliminare la chiave?')"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Aggiungi Chiave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('access.store') }}">
                    <div class="modal-body">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="bi bi-key fs-5"></i></span>
                            <input type="text" class="form-control" placeholder="Chiave" aria-label="Chiave" aria-describedby="basic-addon1">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2"><i class="bi bi-braces fs-5"></i></span>
                            <input type="text" class="form-control" placeholder="Nome" aria-label="Nome" aria-describedby="basic-addon2">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancella</button>
                        <button type="submit" class="btn btn-primary">Salva</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>

        $(() => {

            $('.toggle-visibility').click( e => {
                if ($(e.target).hasClass('bi-eye')){
                    $(e.target).parent().parent().find(':input').attr('type','text')
                    $(e.target).removeClass('bi-eye')
                    $(e.target).addClass('bi-eye-slash')
                }
                else {
                    $(e.target).parent().parent().find(':input').attr('type','password')
                    $(e.target).addClass('bi-eye')
                    $(e.target).removeClass('bi-eye-slash')
                }
            })

            $('.keyBox').each( index => {
                let hide = $(this .hide)[index]
                let key = $(this .key)[index]

                $(hide).text($(key).val())
                $(key).width($(hide).width())
            })
        })

        $('.key').click(e => {
            navigator.clipboard.writeText($(e.target).val());
            const popover = new bootstrap.Popover(e.target,{
                placement: 'right',
                content: 'La chiave è stata copiata',
                container: 'body',
                html: true,
                sanitize: false,
                role: 'button',
                customClass: 'custom-popover'
            })
            popover.show()
            setTimeout(()=>{
                popover.hide()
            },1500)
        })
    </script>
@endsection
