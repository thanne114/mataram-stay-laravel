        <section id="pesan" class="tab-content hidden animate-in fade-in duration-500">
            <div class="bg-surface-container-lowest rounded-3xl border border-outline-variant/30 overflow-hidden shadow-soft flex min-h-[550px] h-[600px]">
                
                <!-- Left Pane: Conversation List -->
                <div class="w-full md:w-80 lg:w-96 border-r border-outline-variant/30 flex flex-col bg-surface-container-lowest shrink-0">
                    <div class="p-4 border-b border-outline-variant/30">
                        <h3 class="font-headline text-xl font-bold text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">forum</span>
                            <span>Pesan Saya</span>
                        </h3>
                    </div>
                    <div class="flex-grow overflow-y-auto divide-y divide-outline-variant/20" id="owner-chat-sidebar-list">
                        @forelse($conversations as $conv)
                            @php
                                $partner = $conv->partner;
                                $lastMsg = $conv->messages->first();
                                $isOwner = auth()->id() === $conv->owner_id;
                            @endphp
                            <button onclick="loadConversation({{ $conv->id }}, this)" 
                                    class="conversation-item w-full text-left flex items-center gap-3 p-4 hover:bg-surface-container transition-colors duration-200" 
                                    data-id="{{ $conv->id }}"
                                    data-partner-name="{{ $partner->name }}"
                                    data-partner-initial="{{ strtoupper(substr($partner->name, 0, 1)) }}"
                                    data-partner-role="{{ $isOwner ? 'Pemilik Kos' : 'Pencari Kos' }}"
                                    data-property-name="{{ $conv->property ? $conv->property->name : '' }}"
                                    data-property-area="{{ $conv->property ? $conv->property->area : '' }}"
                                    data-property-price="{{ $conv->property ? number_format($conv->property->lowest_price, 0, ',', '.') : '' }}"
                                    data-property-slug="{{ $conv->property ? $conv->property->slug : '' }}"
                                    data-property-image="{{ $conv->property && $conv->property->main_image ? asset('storage/' . $conv->property->main_image) : '' }}"
                                    data-property-room-type-id="{{ $conv->property && $conv->property->roomTypes->count() > 0 ? $conv->property->roomTypes->first()->id : '' }}">
                                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center font-headline font-bold text-primary text-base shrink-0">
                                    {{ strtoupper(substr($partner->name, 0, 1)) }}
                                </div>
                                <div class="flex-grow min-w-0">
                                    <div class="flex justify-between items-baseline mb-1">
                                        <h4 class="font-bold text-xs text-on-surface truncate pr-2">{{ $partner->name }}</h4>
                                        <span class="text-[9px] text-secondary shrink-0 last-msg-time">
                                            {{ $lastMsg ? $lastMsg->created_at->diffForHumans(null, true) : $conv->created_at->diffForHumans(null, true) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1 mb-0.5">
                                        @if($isOwner)
                                            <span class="px-1.5 py-0.5 bg-tertiary-fixed text-on-tertiary-fixed-variant text-[8px] font-bold rounded-full uppercase tracking-wider scale-90 origin-left">Pencari</span>
                                        @else
                                            <span class="px-1.5 py-0.5 bg-primary-fixed text-on-primary-fixed text-[8px] font-bold rounded-full uppercase tracking-wider scale-90 origin-left">Pemilik</span>
                                        @endif
                                        @if($conv->property)
                                            <span class="text-[9px] text-primary truncate max-w-[100px] font-medium">{{ $conv->property->name }}</span>
                                        @endif
                                    </div>
                                    <p class="text-[11px] text-secondary truncate last-msg-body">
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
                                @if($conv->unread_messages_count > 0)
                                    <div class="w-4 h-4 rounded-full bg-primary text-on-primary flex items-center justify-center text-[9px] font-bold shrink-0 unread-badge">
                                        {{ $conv->unread_messages_count }}
                                    </div>
                                @endif
                            </button>
                        @empty
                            <div class="p-8 text-center text-secondary">
                                <span class="material-symbols-outlined text-3xl mb-1 text-outline-variant">mail_outline</span>
                                <p class="text-xs font-medium">Belum ada obrolan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Right Pane: Active Chat Room -->
                @php
                    $activeConversationId = request('conversation_id');
                    $activeConversation = null;
                    if ($activeConversationId) {
                        $activeConversation = $conversations->firstWhere('id', $activeConversationId);
                    }
                    $activeMessages = $activeConversation ? $activeConversation->messages()->with('sender')->oldest()->get() : collect();
                    $hasProperty = $activeConversation && $activeConversation->property;
                @endphp
                <div class="flex flex-col flex-grow bg-surface-container-low" id="chat-room-container">
                    <!-- Initial Empty State -->
                    <div class="flex flex-col flex-grow items-center justify-center p-8 text-center {{ $activeConversation ? 'hidden' : '' }}" id="chat-empty-state">
                        <div class="w-16 h-16 rounded-full bg-surface-container flex items-center justify-center mb-4 border border-outline-variant/30">
                            <span class="material-symbols-outlined text-3xl text-primary" style="font-variation-settings: 'FILL' 1;">chat_bubble_outline</span>
                        </div>
                        <h3 class="font-headline text-xl font-bold text-on-surface mb-1">Mulai Obrolan</h3>
                        <p class="text-xs text-secondary max-w-xs leading-relaxed">
                            Pilih obrolan di sebelah kiri untuk berkirim pesan dengan pemilik atau pencari kos.
                        </p>
                    </div>

                    <!-- Dynamic Chat Area -->
                    <div class="{{ $activeConversation ? '' : 'hidden' }} flex flex-col h-full" id="chat-active-area">
                        <!-- Header -->
                        <div class="p-4 border-b border-outline-variant/30 bg-surface-container-lowest flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full bg-primary-fixed flex items-center justify-center font-headline font-bold text-primary text-sm shrink-0" id="chat-header-avatar">
                                    {{ $activeConversation ? strtoupper(substr($activeConversation->partner->name, 0, 1)) : 'A' }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-sm text-on-surface truncate" id="chat-header-name">{{ $activeConversation ? $activeConversation->partner->name : 'Andi Owner' }}</h4>
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <span class="px-1.5 py-0.5 bg-primary-fixed text-on-primary-fixed text-[8px] font-bold rounded-full uppercase tracking-wider" id="chat-header-role">{{ $activeConversation ? (auth()->id() === $activeConversation->owner_id ? 'Pemilik Kos' : 'Pencari Kos') : 'Pemilik Kos' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Banner -->
                        <div class="px-4 py-2 bg-surface-container border-b border-outline-variant/30 flex items-center justify-between gap-4 {{ $hasProperty ? '' : 'hidden' }}" id="chat-property-banner">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-lg overflow-hidden shrink-0 bg-surface-container-high border border-outline-variant/20">
                                    @if($hasProperty && $activeConversation->property->main_image)
                                        <img src="{{ asset('storage/' . $activeConversation->property->main_image) }}" class="w-full h-full object-cover" id="chat-property-image">
                                        <div class="w-full h-full flex items-center justify-center bg-surface-container hidden" id="chat-property-placeholder">
                                            <span class="material-symbols-outlined text-base text-outline">apartment</span>
                                        </div>
                                    @else
                                        <img src="" class="w-full h-full object-cover hidden" id="chat-property-image">
                                        <div class="w-full h-full flex items-center justify-center bg-surface-container" id="chat-property-placeholder">
                                            <span class="material-symbols-outlined text-base text-outline">apartment</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 text-left">
                                    <p class="text-xs font-bold truncate text-on-surface" id="chat-property-name">{{ $hasProperty ? $activeConversation->property->name : 'Nama Kos' }}</p>
                                    <p class="text-[9px] text-secondary" id="chat-property-details">{{ $hasProperty ? $activeConversation->property->area . ' • Rp ' . number_format($activeConversation->property->lowest_price, 0, ',', '.') . '/bln' : 'Area • Rp 0/bln' }}</p>
                                </div>
                            </div>
                            <a href="{{ $hasProperty ? route('property.show', $activeConversation->property->slug) : '' }}" class="text-xs font-bold text-primary hover:text-primary-container shrink-0 flex items-center gap-0.5 transition-colors" id="chat-property-link">
                                <span>Detail Kos</span>
                                <span class="material-symbols-outlined text-xs">arrow_forward</span>
                            </a>
                        </div>

                        <!-- Messages Stream -->
                        <div id="tab-message-container" class="flex-grow overflow-y-auto p-4 space-y-4">
                            @if($activeConversation)
                                @forelse($activeMessages as $msg)
                                    @php
                                        $isSelf = $msg->sender_id === auth()->id();
                                        $isAutoReply = str_starts_with($msg->body, 'Balasan otomatis:');
                                        $displayBody = $isAutoReply ? trim(substr($msg->body, strlen('Balasan otomatis:'))) : $msg->body;
                                    @endphp
                                    <div class="flex {{ $isSelf ? 'justify-end' : 'justify-start' }}">
                                        <div class="flex flex-col max-w-[75%] md:max-w-[65%] gap-1">
                                            <!-- Bubble -->
                                            <div class="p-3.5 rounded-2xl shadow-sm leading-relaxed text-sm {{ $isSelf ? 'bg-primary text-on-primary rounded-tr-none' : 'bg-surface-container-lowest text-on-surface rounded-tl-none border border-outline-variant/20' }}">
                                                @if($isAutoReply)
                                                    <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[9px] font-bold uppercase tracking-wider mb-1.5 border border-amber-500/20 text-left">
                                                        <span class="material-symbols-outlined text-[10px]" style="font-size: 10px;">smart_toy</span>
                                                        <span>Balasan Otomatis</span>
                                                    </div>
                                                @endif
                                                <p class="whitespace-pre-wrap break-words text-left">{{ $displayBody }}</p>
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
                                        <span class="material-symbols-outlined text-3xl mb-1 text-outline-variant">forum</span>
                                        <p class="text-xs font-bold">Mulai obrolan Anda</p>
                                    </div>
                                @endforelse
                            @endif
                        </div>

                        <!-- Form -->
                        <div class="p-4 border-t border-outline-variant/30 bg-surface-container-lowest">
                            <form id="chat-msg-form" onsubmit="sendMessage(event)" class="flex gap-3 items-end">
                                @csrf
                                <input type="hidden" id="active-conversation-id" value="{{ $activeConversation ? $activeConversation->id : '' }}">
                                <div class="flex-grow">
                                    <textarea 
                                        id="chat-msg-input"
                                        name="body" 
                                        rows="1" 
                                        placeholder="Tulis pesan..." 
                                        required
                                        class="w-full bg-surface-container border border-outline-variant/40 focus:border-primary focus:ring-1 focus:ring-primary rounded-2xl px-4 py-2 text-xs resize-none max-h-24 min-h-[38px] outline-none text-on-surface font-body"
                                        oninput="this.style.height = 'auto'; this.style.height = (this.scrollHeight) + 'px';"
                                    ></textarea>
                                </div>
                                <button type="submit" class="bg-primary text-on-primary hover:bg-primary-container p-2.5 rounded-2xl font-bold flex items-center justify-center shrink-0 shadow-md transition-all active:scale-95">
                                    <span class="material-symbols-outlined text-sm">send</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
