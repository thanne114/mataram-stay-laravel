<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Tampilkan daftar percakapan pengguna
     */
    public function index()
    {
        $conversations = Conversation::where('seeker_id', Auth::id())
            ->orWhere('owner_id', Auth::id())
            ->with(['seeker', 'owner', 'property', 'messages' => function ($q) {
                $q->latest();
            }])
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->messages->first()?->created_at ?? $conv->created_at;
            });

        return view('chat.index', compact('conversations'));
    }

    /**
     * Tampilkan isi percakapan tertentu
     */
    public function show(Conversation $conversation)
    {
        // Pastikan user adalah bagian dari percakapan ini
        if (Auth::id() !== $conversation->seeker_id && Auth::id() !== $conversation->owner_id) {
            abort(403, 'Unauthorized action.');
        }

        // Tandai semua pesan masuk sebagai dibaca
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Muat semua pesan
        $messages = $conversation->messages()->with('sender')->oldest()->get();

        // Muat daftar percakapan untuk sidebar
        $conversations = Conversation::where('seeker_id', Auth::id())
            ->orWhere('owner_id', Auth::id())
            ->with(['seeker', 'owner', 'property', 'messages' => function ($q) {
                $q->latest();
            }])
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->messages->first()?->created_at ?? $conv->created_at;
            });

        return view('chat.show', compact('conversation', 'messages', 'conversations'));
    }

    /**
     * Kirim pesan baru dalam percakapan
     */
    public function store(Request $request, Conversation $conversation)
    {
        // Pastikan user adalah bagian dari percakapan ini
        if (Auth::id() !== $conversation->seeker_id && Auth::id() !== $conversation->owner_id) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->body,
            'is_read' => false,
        ]);

        return redirect()->route('chat.show', $conversation);
    }

    /**
     * Memulai percakapan baru atau membuka yang sudah ada dari halaman detail kos
     */
    public function start(Request $request, Property $property)
    {
        $seekerId = Auth::id();
        $ownerId = $property->user_id;

        // Cegah chat dengan diri sendiri
        if ($seekerId === $ownerId) {
            return back()->with('error', 'Anda tidak dapat memulai obrolan dengan diri sendiri.');
        }

        // Cari atau buat percakapan baru untuk konteks kos ini
        $conversation = Conversation::firstOrCreate([
            'seeker_id' => $seekerId,
            'owner_id' => $ownerId,
            'property_id' => $property->id,
        ]);

        return redirect()->route('chat.show', $conversation);
    }
}
