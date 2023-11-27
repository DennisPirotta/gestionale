@props([
    'invoices'
])

<table {{ $attributes->class(['table table-striped']) }}>
    <thead>
    <tr>
        <th scope="col">Dipendente</th>
        <th scope="col">Cliente</th>
        <th scope="col">Ore</th>
    </tr>
    </thead>
    <tbody>
        @foreach($invoices as $invoice)
            @if(count($invoice['customers']) > 0)
                <tr>
                    <td rowspan="{{ count($invoice['customers']) + 1 }}">{{ $invoice['name'] }}</td>
                    <td>Totale</td>
                    <td>{{ $invoice['total'] }}</td>
                </tr>
                @foreach($invoice['customers'] as $name => $count)
                    <tr>
                        <td>{{$name}}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" style="background-color: rgb(229,217,217)"></td>
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
