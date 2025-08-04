@props(['columns' => []])


<table>
    <thead>
        <tr class="text-nowrap">
            @foreach ($columns as $column => $value)
            <th scope="col">{{ Str::upper($value) }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($datas as $data)
        <tr>
            @foreach ($columns as $column => $value)
            <td>{{ $data->$value }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
