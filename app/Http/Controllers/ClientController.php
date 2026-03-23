<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

// use App\Http\Requests\ClientRequest;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::query();

        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->q.'%')
                    ->orWhere('type', 'like', '%'.$request->q.'%');
            });
        }

        if ($request->filled('sort')) {
            $query->orderBy($request->sort);
        } else {
            // المفضلة أولاً ثم الباقي
            $query->orderByDesc('is_favorite')->orderBy('name');
        }

        $clients = $query->paginate(12);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:500',
                'join_date' => 'nullable|date',
            ]);

            Client::create($data);

            return redirect()->route('clients.index')->with('success', 'Client created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function show(Client $client)
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'phone' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
                'type' => 'nullable|string|max:50',
                'notes' => 'nullable|string|max:500',
                'join_date' => 'nullable|date',
            ]);

            $client->update($data);

            return redirect()->route('clients.index')->with('success', 'Client updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: '.$e->getMessage());
        }
    }

    public function destroy(Client $client)
    {
        try {
            $client->delete();

            return redirect()->route('clients.index')->with('success', 'Successfully Deleted Client');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong: '.$e->getMessage());
        }
    }

    public function toggleFavorite(Client $client)
    {
        $client->is_favorite = ! $client->is_favorite;
        $client->save();

        return response()->json([
            'success' => true,
            'is_favorite' => $client->is_favorite,
        ]);
    }
}
