@extends(layouts.app)

@section('content')
<h1>All Tickets</h1>

<a href="{{route ('tickets.create')}}">Create New Ticket </a>

@if(session ('success'))
<div style="color:green">{{session('succes')}}</div>

@endif


<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Game Date</th>
        <th>Stadium</th>
        <th>Seat Info</th>
        <th>Price</th>
        <th>Aviailable</th>
        <th>Actions</th>
    </tr>
    @foreach($tickets as $ticket)

    <tr>
        <td>{{ $ticket->id}}</td>
        <td>{{ $ticket->title }}</td>
        <td>{{ $ticket->game_date }}</td>
        <td>{{ $ticket->stadium }}</td>
        <td>{{ $ticket->seat_info }}</td>
        <td>${{ $ticket->price }}</td>
        <td>{{ $ticket->is_available ? 'Yes' : 'No' }}</td>
        <td>
            <a href="{{route('tickets.show',$ticket->)}}">View</a>|
            <a href="{{route('tickets.destroy',$ticket->id)}}">Edit</a>

            <form action="{{route('tickets.destroy',$ticket->id)}}" method="POST" style="display:inline;"></form>
            @csrf
            @method ('DELETE')
            <button type="sumbit" onclick="return confrim('Delete TIcket?')">Delete</button>
            </form>
        </td>
    </tr>
    @endforeach
</table>
@endsection