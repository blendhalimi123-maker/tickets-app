<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;  

class TicketController extends Controller
{
    
     
    public function index()
    {
        $maxTickets = config('tickets.max_tickets_per_user');


        $tickets = Ticket::all();
        return view("tickets.index", compact('tickets'));
    }

    public function create()
    {
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' =>'required|string|max:255',
            'game_date' => 'required|date',
            'stadium'  => 'required|string|max:255',
            'seat_info' => 'required|string|max:255',
            'price' =>  'required|numeric',
        ]);

        Ticket::create([
            'user_id' => 1,
            'title' => $request->title,
            'game_date'=> $request->game_date,
            'stadium' => $request->stadium,
            'seat_info' => $request->seat_info,
            'price' => $request->price,
        ]);
        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');


    }

    
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

   
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact ('ticket'));
    }

    
    public function update(Request $request, Ticket $ticket)
    {
         $request->validate([
            'title' =>'required|string|max:255',
            'game_date' => 'required|date',
            'stadium'  => 'required|string|max:255',
            'seat_info' => 'required|string|max:255',
            'price' =>  'required|numeric',
            'is_available' => 'required|boolean'
        ]);

        $ticket->update($request->all());
        return redirect()->route('tickets.index')->with('success','Ticket updated successfully!');
    
    }


    
     
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redirect()->route('tickets.index')->with('success',' Ticket delted succefully');
        
    }
}
