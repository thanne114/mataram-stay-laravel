<x-layout>
    <main class="flex-grow max-w-7xl mx-auto w-full px-4 md:px-8 py-8 flex flex-col">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-secondary mb-6">
            <a href="/" class="hover:text-primary transition-colors">Beranda</a>
            <span class="material-symbols-outlined text-xs">chevron_right</span>
            <span class="text-on-surface font-bold">Pesan Saya</span>
        </nav>

        <!-- Chat Container Card -->
        <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/30 overflow-hidden shadow-sm flex flex-1 min-h-[550px] md:min-h-[600px] h-[calc(100vh-16rem)]">
            
            <!-- Left Pane: Conversation List -->
            <div class="w-full md:w-80 lg:w-96 border-r border-outline-variant/30 flex flex-col bg-surface-container-lowest">
                <!-- Search / Header area -->
                <div class="p-4 border-b border-outline-variant/30 flex items-center justify-between">
                    <h2 class="font-headline text-2xl font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">forum</span>
                        <span>Pesan Saya</span>
                    </h2>
                </div>

                <!-- Active Conversations -->
                <div class="flex-grow overflow-y-auto divide-y divide-outline-variant/20">
                    @forelse($conversations as $conv)
                        @php
                            $partner = $conv->partner;
                            $lastMsg = $conv->messages->first();
                            $isOwner = auth()->id() === $conv->owner_id;
                        @endphp
                        <a href="{{ route('chat.show', $conv) }}" class="flex items-center gap-4 p-4 hover:bg-surface-container transition-colors duration-200">
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
                    @empty
                        <div class="p-8 text-center text-secondary">
                            <span class="material-symbols-outlined text-4xl mb-2 text-outline-variant">mail_outline</span>
                            <p class="text-sm font-medium">Belum ada obrolan</p>
                            <p class="text-xs text-secondary/70 mt-1">Chat akan muncul saat Anda menghubungi pemilik kos atau sebaliknya.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Right Pane: Active Chat Placeholder -->
            <div class="hidden md:flex flex-col flex-grow items-center justify-center bg-surface-container-low p-8 text-center">
                <div class="w-20 h-20 rounded-full bg-surface-container flex items-center justify-center mb-4 border border-outline-variant/30">
                    <span class="material-symbols-outlined text-4xl text-primary" style="font-variation-settings: 'FILL' 1;">chat_bubble_outline</span>
                </div>
                <h3 class="font-headline text-2xl font-bold text-on-surface mb-2">Mulai Percakapan</h3>
                <p class="text-sm text-secondary max-w-sm leading-relaxed">
                    Pilih salah satu percakapan di sebelah kiri untuk melihat pesan dan mulai berkirim pesan dengan mitra Anda.
                </p>
            </div>
            
        </div>
    </main>
</x-layout>
