        <section id="dashboard" class="tab-content space-y-12 block animate-in fade-in duration-500">
            <div class="space-y-2">
                <h2 class="font-headline text-4xl md:text-5xl text-on-surface">Selamat Datang Kembali, {{ auth()->user()->name ?? (auth()->user()->role === 'admin' ? 'Administrator' : 'Pemilik') }}!</h2>
                <p class="text-secondary max-w-2xl leading-relaxed">
                    {{ auth()->user()->role === 'admin' 
                        ? 'Berikut adalah ringkasan kinerja seluruh properti dan transaksi di platform Mataram Stay secara global.' 
                        : 'Berikut adalah ringkasan kinerja properti Anda di Mataram Stay untuk bulan ini. Semua operasional berjalan lancar.' }}
                </p>
            </div>

            <section class="grid grid-cols-1 md:grid-cols-2 mb-10 gap-8">
                <div class="bg-surface-container-lowest p-8 rounded-3xl shadow-soft border border-outline-variant/20 group hover:border-primary/30 transition-all flex flex-col justify-between h-full">
                  <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-primary/5 rounded-2xl text-primary group-hover:scale-110 transition-transform duration-700">
                      <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">apartment</span>
                    </div>
                  </div>
                  <div class="space-y-1">
                    <p class="text-secondary text-xs font-medium uppercase tracking-widest">Total Properti</p>
                    <h3 class="text-5xl font-headline font-bold text-on-surface">{{ $totalProperties }}</h3>
                  </div>
                </div>

                <div onclick="switchTab('transaksi', document.querySelector('button[onclick*=\'transaksi\']'))" class="block bg-surface-container-lowest p-8 rounded-3xl shadow-soft border border-outline-variant/20 group hover:border-primary/30 hover:bg-surface-container-low transition-all flex flex-col justify-between h-full cursor-pointer">
                  <div class="flex justify-between items-start mb-8">
                    <div class="flex items-center justify-center w-16 h-16 bg-primary/5 rounded-2xl text-primary group-hover:scale-110 transition-transform duration-700">
                      <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
                    </div>
                  </div>
                  <div class="space-y-1">
                    <p class="text-secondary text-xs font-medium uppercase tracking-widest">Transaksi Aktif</p>
                    <h3 class="text-5xl font-headline font-bold text-on-surface">{{ $activeBookings }}</h3>
                  </div>
                </div>
            </section>

            <!-- Property Management Grid -->
            <section class="space-y-6">
                <div class="flex justify-between items-end">
                    <div>
                        <h4 class="font-headline text-2xl text-on-surface">{{ auth()->user()->role === 'admin' ? 'Seluruh Properti' : 'Manajemen Properti' }}</h4>
                        <p class="text-sm text-secondary">{{ auth()->user()->role === 'admin' ? 'Daftar lengkap seluruh properti terdaftar di platform.' : 'Pantau status hunian dan kelola detail kos Anda.' }}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @forelse($properties as $prop)
                        @php
                            $totalRooms = $prop->roomTypes->sum('total_rooms');
                            $occupied = $totalRooms - $prop->roomTypes->sum('available_rooms');
                            $percentage = $totalRooms > 0 ? round(($occupied / $totalRooms) * 100) : 0;
                        @endphp
                        <div class="bg-surface-container-lowest rounded-3xl overflow-hidden shadow-soft border border-outline-variant/20 flex flex-col sm:flex-row group transition-all p-6 gap-6 h-full">
                          <div class="w-full sm:w-40 h-40 relative overflow-hidden shrink-0 rounded-2xl">
                            @if($prop->main_image)
                                <img alt="{{ $prop->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ asset('storage/' . $prop->main_image) }}">
                            @else
                                <div class="w-full h-full bg-surface-container-high flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl text-outline">apartment</span>
                                </div>
                            @endif
                          </div>
                          <div class="flex flex-col flex-1 justify-between">
                            <div>
                              <h5 class="font-headline text-2xl text-on-surface mb-2">{{ $prop->name }}</h5>
                              <div class="flex items-center gap-4 mb-3">
                                <div class="flex-1 h-1 bg-surface-container rounded-full overflow-hidden">
                                  <div class="h-full bg-primary" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="text-[10px] font-bold text-primary whitespace-nowrap">{{ $percentage == 100 ? 'FULL' : $occupied.'/'.$totalRooms }} Terisi</span>
                              </div>
                              <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-primary text-sm">location_on</span>
                                <span class="text-[11px] text-secondary uppercase tracking-wider">{{ $prop->area }}</span>
                                <span class="px-2 py-0.5 bg-primary-container/10 text-primary text-[9px] font-bold rounded uppercase">Kos {{ $prop->type }}</span>
                              </div>
                              <div class="flex flex-wrap gap-2 mb-6">
                                @foreach($prop->facilities->take(3) as $facility)
                                <span class="px-3 py-1 bg-surface-container-high/50 border border-outline-variant/20 rounded-full text-[10px] text-secondary">{{ $facility->name }}</span>
                                @endforeach
                              </div>
                            </div>
                            <div class="flex gap-3 mt-auto">
                              <a href="{{ route('property.show', $prop->slug) }}" class="flex-1 py-2.5 rounded-xl border border-outline text-secondary text-xs font-bold hover:bg-surface-container transition-colors text-center">Lihat Detail</a>
                              <a href="{{ route('property.edit', $prop) }}" class="flex-1 py-2.5 rounded-xl bg-surface-container-high text-on-surface text-xs font-bold hover:bg-secondary-container transition-colors text-center flex items-center justify-center">Edit</a>
                              <form action="{{ route('property.destroy', $prop) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus/mengarsipkan properti ini? Semua data histori sewa akan tetap tersimpan.')" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2.5 rounded-xl bg-tertiary/10 text-tertiary hover:bg-tertiary hover:text-white transition-colors flex items-center justify-center" title="Hapus Properti">
                                  <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                              </form>
                            </div>
                          </div>
                        </div>
                    @empty
                    @endforelse

                    <a href="{{ route('property.create') }}" class="border-2 border-dashed border-outline-variant/60 rounded-3xl flex flex-col items-center justify-center p-8 text-center cursor-pointer hover:bg-surface-container transition-all group h-full flex">
                        <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-secondary text-3xl">add_home</span>
                        </div>
                        <p class="font-headline text-lg text-secondary">Mulai Listing Baru</p>
                        <p class="text-[11px] text-secondary/60 mt-1">Daftarkan properti Anda hari ini.</p>
                    </a>
                </div>
            </section>
        </section>
