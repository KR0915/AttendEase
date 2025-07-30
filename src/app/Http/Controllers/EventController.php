<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $events = Event::with('creator')
            ->where('is_active', true)
            ->orderBy('start_time', 'asc')
            ->paginate(10);

        return view('events.index', compact('events'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        $validated['created_by'] = auth()->id();

        Event::create($validated);

        return redirect()->route('events.index')
            ->with('success', 'イベントが正常に作成されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event): View
    {
        $event->load('creator');
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event): View
    {
        if ($event->created_by !== auth()->id()){
            abort(403, 'このイベントを編集する権限がありません。');
        }

        return view('events.edit', compact('event'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        if ($event->created_by !== auth()->id()){
            abort(403, 'このイベントを更新する権限がありません。');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'max_participants' => 'nullable|integer|min:1',
        ]);

        $event->update($validated);

        return redirect()->route('events.show', $event)
            ->with('success', 'イベントが正常に更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event): RedirectResponse
    {
        if($event->created_by !== auth()->id()){
            abort(403, 'このイベントを削除する権限がありません。');
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'イベントが正常に削除されました。');
    }
}
