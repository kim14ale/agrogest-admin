<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedRequest;
use App\Models\Feed;

class FeedController extends Controller
{
    public function index()
    {
        return view('admin.feeds.index', ['items' => Feed::orderBy('name')->paginate(15)]);
    }

    public function create()
    {
        return view('admin.feeds.form', ['item' => new Feed()]);
    }

    public function store(FeedRequest $request)
    {
        Feed::create($request->validated());

        return redirect()->route('feeds.index')->with('ok', 'Registro creado correctamente.');
    }

    public function edit(Feed $feed)
    {
        return view('admin.feeds.form', ['item' => $feed]);
    }

    public function update(FeedRequest $request, Feed $feed)
    {
        $feed->update($request->validated());

        return redirect()->route('feeds.index')->with('ok', 'Registro actualizado correctamente.');
    }

    public function destroy(Feed $feed)
    {
        try {
            $feed->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'No se puede eliminar: el registro está siendo usado por otros datos.');
        }

        return redirect()->route('feeds.index')->with('ok', 'Registro eliminado.');
    }
}
