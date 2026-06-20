<x-layout>
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 md:px-8 py-8 flex flex-col">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-secondary mb-6">
            <a href="/" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <a href="{{ route('chat.index') }}" class="hover:text-primary transition-colors">Pesan Saya</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">{{ $conversation->partner->name }}</span>
        </nav>

        <!-- Chat Container Card -->
        <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm flex flex-1 min-h-[550px] md:min-h-[600px] h-[calc(100vh-16rem)]">
            
            <!-- Left Pane: Conversation List (Hidden on mobile when chat is active) -->
            <div class="hidden md:flex w-full md:w-80 lg:w-96 border-r border-outline-variant/30 flex-col bg-surface-container-lowest shrink-0">
                <!-- Search / Header area -->
                <div class="p-4 border-b border-outline-variant/30 flex items-center justify-between">
                    <h2 class="font-headline text-2xl font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">forum</span>
                        <span>Pesan Saya</span>
                    </h2>
                </div>

                <!-- Active Conversations -->
                <div class="flex-grow overflow-y-auto divide-y divide-outline-variant/20">
                    @foreach($conversations as $conv)
                        @php
                            $partner = $conv->partner;
                            $lastMsg = $conv->messages->first();
                            $isOwner = auth()->id() === $conv->owner_id;
                            $isActive = $conv->id === $conversation->id;
                        @endphp
                        <a href="{{ route('chat.show', $conv) }}" class="flex items-center gap-4 p-4 hover:bg-surface-container transition-colors duration-200 {{ $isActive ? 'bg-surface-container-high/60 border-l-4 border-primary' : '' }}">
                            <!-- Avatar with dynamic colors -->
                            <div class="w-12 h-12 rounded-full bg-primary-fixed flex items-center justify-center font-headline font-bold text-primary text-lg shrink-0">
                                {{ strtoupper(substr($partner->name, 0, 1)) }}
                            </div>
                            
                            <!-- Content -->
                            <div class="flex-grow min-w-0">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h3 class="font-bold text-sm text-on-surface truncate pr-2">{{ $partner->name }}</h3>
                                    <span class="text-[10px] text-secondary shrink-0">
                                        {{ $lastMsg ? $lastMsg->created_at->diffForHumans(null, true) : $conv->created_at->diffForHumans(null, true) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5 mb-1">
                                    @if($isOwner)
                                        <span class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed-variant text-[9px] font-bold rounded-full uppercase tracking-wider scale-90 origin-left">Pencari</span>
                                    @else
                                        <span class="px-2 py-0.5 bg-primary-fixed text-on-primary-fixed text-[9px] font-bold rounded-full uppercase tracking-wider scale-90 origin-left">Pemilik</span>
                                    @endif
                                    @if($conv->property)
                                        <span class="text-[10px] text-primary truncate max-w-[120px] font-medium">{{ $conv->property->name }}</span>
                                    @endif
                                </div>
                                <p class="text-xs text-secondary truncate">
                                    @if($lastMsg)
                                        @if($lastMsg->sender_id === auth()->id())
                                            <span class="text-primary font-medium">Anda: </span>
                                        @endif
                                        {{ $lastMsg->body }}
                                    @else
                                        <span class="italic text-secondary/70">Mulai percakapan...</span>
                                    @endif
                                </p>
                            </div>

                            <!-- Badge -->
                            @if($conv->unread_messages_count > 0)
                                <div class="w-5 h-5 rounded-full bg-primary text-on-primary flex items-center justify-center text-[10px] font-bold shrink-0">
                                    {{ $conv->unread_messages_count }}
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Right Pane: Active Chat Room -->
            <div class="flex flex-col flex-grow bg-surface-container-low w-full">
                
                <!-- Chat Header -->
                <div class="p-4 border-b border-outline-variant/30 bg-surface-container-lowest flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <!-- Back Button (Mobile only) -->
                        <a href="{{ route('chat.index') }}" class="md:hidden p-1 hover:bg-surface-container rounded-full text-secondary flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </a>

                        <!-- Avatar -->
                        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-headline font-bold text-primary text-base shrink-0">
                            {{ strtoupper(substr($conversation->partner->name, 0, 1)) }}
                        </div>

                        <!-- Name & Role -->
                        <div class="min-w-0">
                            <h3 class="font-bold text-sm text-on-surface truncate">{{ $conversation->partner->name }}</h3>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                @if(auth()->id() === $conversation->owner_id)
                                    <span class="px-2 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed-variant text-[9px] font-bold rounded-full uppercase tracking-wider">Pencari Kos</span>
                                @else
                                    <span class="px-2 py-0.5 bg-primary-fixed text-on-primary-fixed text-[9px] font-bold rounded-full uppercase tracking-wider">Pemilik Kos</span>
                                @endif
                                @if($conversation->partner->no_whatsapp)
                                    <span class="text-[10px] text-green-600 flex items-center gap-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                        Tersedia WA
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Direct WA shortcut -->
                    @if($conversation->partner->no_whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $conversation->partner->no_whatsapp) }}" target="_blank" 
                           class="flex items-center gap-1.5 px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-xl text-xs font-label font-bold transition-all shadow-sm">
                            <span class="material-symbols-outlined text-sm">chat</span>
                            <span class="hidden sm:inline">WhatsApp</span>
                        </a>
                    @endif
                </div>

                <!-- Contextual Property Banner -->
                @if($conversation->property)
                    <div class="px-4 py-2.5 bg-surface-container border-b border-outline-variant/30 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0 bg-surface-container-high border border-outline-variant/20">
                                @if($conversation->property->main_image)
                                    <img src="{{ asset('storage/' . $conversation->property->main_image) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-surface-container">
                                        <span class="material-symbols-outlined text-lg text-outline">apartment</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold truncate text-on-surface">{{ $conversation->property->name }}</p>
                                <p class="text-[10px] text-secondary">
                                    {{ $conversation->property->area }} • Rp {{ number_format($conversation->property->lowest_price, 0, ',', '.') }}/bln
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('property.show', $conversation->property->slug) }}" class="text-xs font-bold text-primary hover:text-primary-container shrink-0 flex items-center gap-0.5 transition-colors">
                            <span>Detail Kos</span>
                            <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </a>
                    </div>
                @endif

                <!-- Message stream area -->
                <div id="message-container" class="flex-grow overflow-y-auto p-4 space-y-4">
                    @forelse($messages as $msg)
                        @php
                            $isSelf = $msg->sender_id === auth()->id();
                        @endphp
                        <div class="flex {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                            <div class="flex flex-col max-w-[75%] md:max-w-[65%] gap-1">
                                <!-- Bubble -->
                                <div class="p-3.5 rounded-2xl shadow-sm leading-relaxed text-sm {{ $isSelf ? 'bg-primary text-on-primary rounded-tr-none' : 'bg-surface-container-lowest text-on-surface rounded-tl-none border border-outline-variant/20' }}">
                                    <p class="whitespace-pre-wrap break-words">{{ $msg->body }}</p>
                                </div>
                                <!-- Timestamp and status -->
                                <div class="flex items-center gap-1 text-[9px] text-secondary mt-0.5 {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                                    <span>{{ $msg->created_at->format('H:i') }}</span>
                                    @if($isSelf)
                                        @if($msg->is_read)
                                            <span class="material-symbols-outlined text-xs text-primary font-bold">done_all</span>
                                        @else
                                            <span class="material-symbols-outlined text-xs text-secondary/60">done</span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full py-12 text-secondary flex-col">
                            <span class="material-symbols-outlined text-4xl mb-2 text-outline-variant">forum</span>
                            <p class="text-sm font-bold">Mulai obrolan Anda</p>
                            <p class="text-xs text-secondary/70 mt-1">Kirim pesan pertama Anda di bawah untuk memulai percakapan.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input form Area -->
                <div class="p-4 border-t border-outline-variant/30 bg-surface-container-lowest">
                    <form action="{{ route('chat.send', $conversation) }}" method="POST" class="flex gap-3 items-end">
                        @csrf
                        <div class="flex-grow">
                            <textarea 
                                name="body" 
                                rows="1" 
                                placeholder="Tulis pesan..." 
                                required
                                class="w-full bg-surface-container border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary rounded-2xl px-4 py-2.5 text-sm resize-none max-h-32 min-h-[42px] outline-none text-on-surface font-body"
                                oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"
                            ></textarea>
                        </div>
                        <button type="submit" class="bg-primary text-on-primary hover:bg-primary-container p-2.5 rounded-2xl font-bold flex items-center justify-center shrink-0 shadow-md transition-all active:scale-95">
                            <span class="material-symbols-outlined">send</span>
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </main>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var container = document.getElementById("message-container");
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
</x-layout>
