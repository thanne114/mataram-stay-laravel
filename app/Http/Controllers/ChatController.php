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
     * Tampilkan isi percakapan tertentu (Dukung AJAX & Non-AJAX)
     */
    public function show(Request $request, Conversation $conversation)
    {
        // Pastikan user adalah bagian dari percakapan ini
        if (Auth::id() !== $conversation->seeker_id && Auth::id() !== $conversation->owner_id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        // Tandai semua pesan masuk sebagai dibaca
        $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Muat semua pesan
        $messages = $conversation->messages()->with('sender')->oldest()->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['messages' => $conversation->messages()->with(['sender'])->get()]);
        }

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
     * Kirim pesan baru dalam percakapan (Dukung AJAX & Non-AJAX)
     */
    public function store(Request $request, Conversation $conversation)
    {
        // Pastikan user adalah bagian dari percakapan ini
        if (Auth::id() !== $conversation->seeker_id && Auth::id() !== $conversation->owner_id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'body' => $request->body,
            'is_read' => false,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'body' => $message->body,
                    'sender_id' => $message->sender_id,
                    'is_self' => true,
                    'time' => $message->created_at->format('H:i'),
                    'is_read' => false,
                ]
            ]);
        }

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

        // Dialihkan kembali ke dasbor profile dengan tab pesan & conversation_id terbuka otomatis
        return redirect()->route('profile.edit', ['tab' => 'view-pesan', 'conversation_id' => $conversation->id]);
    }
}
