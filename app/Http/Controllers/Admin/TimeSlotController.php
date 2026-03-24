<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeSlotController extends Controller
{
    // LIST + SEARCH
    public function index(Request $request)
    {
        $slots = TimeSlot::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where('start_time', 'like', "%{$search}%");
            })
            ->orderBy('start_time', 'asc') // morning → evening
            ->paginate(10)
            ->withQueryString();

        return view('admin.time_slots.index', compact('slots'));
    }

    // CREATE PAGE
    public function create()
    {
        return view('admin.time_slots.form', [
            'slot' => new TimeSlot()
        ]);
    }

    // EDIT PAGE
    public function edit(TimeSlot $time_slot)
    {
        return view('admin.time_slots.form', [
            'slot' => $time_slot
        ]);
    }

    // STORE
    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $data['start_time'] = $this->formatTime($request->start_time);

        // Duplicate check
        if ($this->isDuplicate($data['start_time'])) {
            return back()->withErrors([
                'start_time' => 'This time slot already exists'
            ])->withInput();
        }

        TimeSlot::create($data);

        return redirect()->route('admin.time_slots.index')
            ->with('success', 'Time Slot created successfully.');
    }

    // UPDATE
    public function update(Request $request, TimeSlot $time_slot)
    {
        $data = $this->validateData($request);

        $data['start_time'] = $this->formatTime($request->start_time);

        // Duplicate check (except current)
        if ($this->isDuplicate($data['start_time'], $time_slot->id)) {
            return back()->withErrors([
                'start_time' => 'This time slot already exists'
            ])->withInput();
        }

        $time_slot->update($data);

        return redirect()->route('admin.time_slots.index')
            ->with('success', 'Time Slot updated successfully.');
    }

    // DELETE
    public function destroy(TimeSlot $time_slot)
    {
        $time_slot->delete();

        return redirect()->route('admin.time_slots.index')
            ->with('success', 'Time Slot deleted successfully.');
    }

    

    // VALIDATION
    private function validateData(Request $request)
    {
        return $request->validate([
            'start_time' => 'required',
        ]);
    }

    // FORMAT TIME (AM/PM → DB)
    private function formatTime($time)
    {
        return Carbon::parse($time)->format('H:i:s');
    }

    // DUPLICATE CHECK
    private function isDuplicate($time, $ignoreId = null)
    {
        return TimeSlot::where('start_time', $time)
            ->when($ignoreId, function ($q) use ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            })
            ->exists();
    }
}