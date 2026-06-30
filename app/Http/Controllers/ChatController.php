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
        $conversations = Conversation::forUser(Auth::id())
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->latestMessage?->created_at ?? $conv->created_at;
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

        // Tandai semua pesan masuk sebagai dibaca jika ada yang belum dibaca (mencegah write query berulang)
        $hasUnread = $conversation->messages()
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->exists();

        if ($hasUnread) {
            $conversation->messages()
                ->where('sender_id', '!=', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Muat semua pesan (cukup query satu kali)
        $messages = $conversation->messages()->with('sender')->oldest()->get();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['messages' => $messages]);
        }

        // Muat daftar percakapan untuk sidebar
        $conversations = Conversation::forUser(Auth::id())
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->latestMessage?->created_at ?? $conv->created_at;
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

        broadcast(new \App\Events\MessageSent($message))->toOthers();

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
        // Hanya pencari kos (seeker) yang boleh memulai chat
        if (!Auth::user()->isSeeker()) {
            return back()->with('error', 'Hanya pencari kos yang dapat memulai obrolan.');
        }

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

        $messageBody = $request->input('message');
        if ($messageBody) {
            // Simpan pesan seeker
            $seekerMessage = \App\Models\Message::create([
                'conversation_id' => $conversation->id,
                'sender_id' => $seekerId,
                'body' => $messageBody,
                'is_read' => false,
            ]);

            // Broadcast message sent
            broadcast(new \App\Events\MessageSent($seekerMessage))->toOthers();

            // Logika Auto-Reply (Balasan Otomatis) dari Owner
            $autoReply = null;
            if ($messageBody === 'Saya butuh cepat nih. Bisa booking sekarang?') {
                $autoReply = "Balasan otomatis: Halo! Silakan ajukan sewa melalui tombol 'Ajukan Sewa' yang tersedia di atas. Kami akan segera memproses pengajuan Anda.";
            } elseif ($messageBody === 'Saya ingin survei dulu') {
                $autoReply = "Balasan otomatis: Halo! Silakan beri tahu kami waktu rencana survei Anda (tanggal dan jam). Kami akan mencoba mencocokkan jadwal.";
            } elseif ($messageBody === 'Masih ada kamar?') {
                $autoReply = "Balasan otomatis: Halo! Ketersediaan kamar ter-update sesuai dengan informasi di halaman detail kos. Jika tertulis masih tersedia, silakan langsung ajukan sewa.";
            } elseif ($messageBody === 'Alamat kos di mana?') {
                $address = $property->address ?? 'Mataram';
                $autoReply = "Balasan otomatis: Halo! Alamat lengkap kos kami adalah: {$address}. Anda juga dapat melihat lokasi persisnya pada peta interaktif di detail properti.";
            } elseif ($messageBody === 'Ada diskon untuk kos ini?') {
                $autoReply = "Balasan otomatis: Halo! Untuk saat ini belum tersedia promo diskon khusus untuk kos ini. Harga yang tertera adalah harga terbaik.";
            } elseif ($messageBody === 'Boleh tanya-tanya dulu?') {
                $autoReply = "Balasan otomatis: Halo! Tentu saja, silakan tuliskan pertanyaan Anda di sini. Kami akan menjawab secepat mungkin.";
            } elseif ($messageBody === 'Bisa pasutri?') {
                $autoReply = "Balasan otomatis: Halo! Kebijakan mengenai penghuni pasutri disesuaikan dengan tipe kos (Putra/Putri/Campur). Harap pastikan dokumen pendukung (seperti surat nikah) siap jika diperlukan.";
            } elseif ($messageBody === 'Boleh bawa hewan?') {
                $autoReply = "Balasan otomatis: Halo! Demi kenyamanan penghuni lain, kami menyarankan untuk mengkonfirmasi jenis hewan peliharaan Anda terlebih dahulu kepada kami.";
            }

            if ($autoReply) {
                // Simpan pesan auto-reply dari owner
                $ownerMessage = \App\Models\Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $ownerId,
                    'body' => $autoReply,
                    'is_read' => false,
                ]);

                // Broadcast auto-reply message sent
                broadcast(new \App\Events\MessageSent($ownerMessage))->toOthers();
            }
        }

        // Dialihkan kembali ke dasbor profile dengan tab pesan & conversation_id terbuka otomatis
        return redirect()->route('profile.edit', ['tab' => 'view-pesan', 'conversation_id' => $conversation->id]);
    }
}
