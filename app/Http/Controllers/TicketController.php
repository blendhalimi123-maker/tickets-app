<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;  

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::all();
        return view("tickets.index", compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
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
            'user_id' => auth()->id(),
            'title' => $request->title,
            'game_date'=> $request->game_date,
            'stadium' => $request->stadium,
            'seat_info' => $request->seat_info,
            'price' => $request->price,
            

        ]);
        return redirect()->route('ticket.index')->with('success', 'Ticket created successfully!');


    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        return view('tickets.edit', compact ('ticket'));
    }

    /**
     * Update the specified resource in storage.
     */
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
        return redirect()->route('ticket.index')->with('success','Ticket updated successfully!');

       
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        return redricet()->route('ticekt.index')->with('success',' Ticket delted succefully');
        
    }
}
