@extends('layouts.mail')

@section('content')

<h1 class="mb-3">{{$subject}}</h1>

<table border="0" cellspacing="0" width="100%" class="table">

    <h2>{{$depotName}}</h2>
    <thead>
        <tr>
            <th>Status</th>
            @foreach($carriers as $key => $carrier)
            <th>{{$carrier}}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
      <tr>
        <td>Shipped</td>
          @foreach($carriers as $key => $carrier)
          <?php
            $total = 0;
            foreach ($data as $status => $row) {
                if (isset($row[$key])) {
                    $total += $row[$key];
                }
            }
           ?>
            <td>{{$total ?? '0'}}</td>
          @endforeach
      </tr>
      <tr>
        <td>Received/ InTransit</td>
          @foreach($carriers as $key => $carrier)
            <td>{{($data['3'][$key] ?? '0') + ($data['4'][$key] ?? '0') + ($data['10'][$key] ?? '0')}}</td>
          @endforeach
      </tr>
      <tr>
        <td>Out For Delivery</td>
          @foreach($carriers as $key => $carrier)
            <td>{{$data['5'][$key] ?? '0'}}</td>
          @endforeach
      </tr>
      <tr>
        <td>Delivered</td>
          @foreach($carriers as $key => $carrier)
            <td>{{$data['6'][$key] ?? '0'}}</td>
          @endforeach
      </tr>
      <tr>
        <td>Partial Delivery</td>
          @foreach($carriers as $key => $carrier)
            <td>{{$data['20'][$key] ?? '0'}}</td>
          @endforeach
      </tr>
      <tr>
        <td>RTS</td>
          @foreach($carriers as $key => $carrier)
            <td>{{$data['9'][$key] ?? '0'}}</td>
          @endforeach
      </tr>
      <tr>
        <td>Claim</td>
          @foreach($carriers as $key => $carrier)
            <td>{{$data['21'][$key] ?? '0'}}</td>
          @endforeach
      </tr>
      <tr>
        <td>Unknown</td>
          @foreach($carriers as $key => $carrier)
            <td>{{$data['11'][$key] ?? '0'}}</td>
          @endforeach
      </tr>

  </tbody>

</table>

@endsection
