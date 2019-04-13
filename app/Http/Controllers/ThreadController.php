<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilter;
use App\Http\Requests\ThreadStoreRequest;
use App\Thread;
use Illuminate\Http\Request;

class ThreadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(ThreadFilter $filters, Channel $channel)
    {
        $threads = $this->getThreads($filters, $channel);

        return view('threads.index')->withThreads($threads);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThreadStoreRequest $request)
    {
        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => $request->channel_id,
            'title'      => $request->title,
            'body'       => $request->body
        ]);

        return redirect($thread->path());
    }

    /**
     * Display the specified resource.
     *
     * @param $channelId
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function show($channelId, Thread $thread)
    {
        return view('threads.show')->with([
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(20)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function edit(Thread $thread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Thread $thread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Thread $thread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Thread $thread)
    {
        //
    }

    /**
     * @param ThreadFilter $filters
     * @param Channel $channel
     * @return mixed
     */
    protected function getThreads(ThreadFilter $filters, Channel $channel)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->whereChannelId($channel->id);
        }

        $threads = $threads->get();
        return $threads;
    }
}
