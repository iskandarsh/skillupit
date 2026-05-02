<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    Dashboard
                </h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                    </span>
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-widest">
                        SkillUpIT Learning Platform
                    </p>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3">
                <span class="text-[10px] font-bold px-2 py-1 bg-indigo-50 text-indigo-600 rounded-md border border-indigo-100">
                    V2.0.4
                </span>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <div class="relative overflow-hidden bg-slate-900 rounded-[2rem] p-8 sm:p-10 text-white shadow-2xl shadow-indigo-200/50">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[80px]"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-60 h-60 bg-violet-500/20 rounded-full blur-[60px]"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-4">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-md text-xs font-medium text-indigo-200">
                        ✨ Welcome Back
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                        Selamat Belajar,<br />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 via-violet-200 to-white">
                            {{ Auth::user()->name }} 👋
                        </span>
                    </h2>
                    <p class="text-gray-400 text-sm sm:text-base max-w-md leading-relaxed">
                        Hari ini adalah waktu yang tepat untuk meningkatkan skill. Kamu punya <span class="text-white font-bold">{{ $jadwalHariIni->count() }} agenda</span> hari ini.
                    </p>
                </div>

                <div class="flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/10 backdrop-blur-sm">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] text-gray-400 uppercase tracking-tighter">Current Progress</p>
                        <p class="text-lg font-bold text-white">{{ $persentase }}% Score</p>
                    </div>
                    <div class="w-12 h-12 rounded-full border-4 border-indigo-500 border-t-transparent animate-spin-slow"></div>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-xl font-extrabold text-gray-900">
                🎟️ Voucher Kamu
            </h3>

            @forelse($referrals as $voucher)
            <div class="bg-white border rounded-2xl p-4 flex items-center justify-between">
                <div>
                    <p class="font-bold text-gray-800">{{ $voucher->kode }}</p>
                    <p class="text-xs text-gray-500">
                        Diskon {{ $voucher->disc }}%
                        @if($voucher->kelas)
                        • {{ $voucher->kelas->nama_kelas }}
                        @else
                        • Semua Kelas
                        @endif
                    </p>
                </div>

                <span class="px-3 py-1 text-xs font-bold bg-indigo-50 text-indigo-600 rounded-lg">
                    ACTIVE
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-400">Belum ada voucher tersedia.</p>
            @endforelse
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @php
            $stats = [
            ['label' => 'Total Kelas', 'value' => $totalKelas ?? 0, 'icon' => '📚', 'bg' => 'bg-blue-50', 'text' => 'text-blue-600'],
            ['label' => 'Hadir', 'value' => $hadir ?? 0, 'icon' => '✅', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600'],
            ['label' => 'Absen', 'value' => $tidakHadir ?? 0, 'icon' => '❌', 'bg' => 'bg-rose-50', 'text' => 'text-rose-600'],
            ['label' => 'Kehadiran', 'value' => ($persentase ?? 0) . '%', 'icon' => '📈', 'bg' => 'bg-violet-50', 'text' => 'text-violet-600'],
            ];
            @endphp

            @foreach($stats as $s)
            <div class="group bg-white border border-gray-100 rounded-[1.5rem] p-5 shadow-sm hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-50 transition-all duration-300">
                <div class="flex flex-col gap-3">
                    <div class="w-10 h-10 {{ $s['bg'] }} rounded-xl flex items-center justify-center text-lg group-hover:scale-110 transition-transform">
                        {{ $s['icon'] }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ $s['label'] }}</p>
                        <h3 class="text-2xl font-black mt-1 {{ $s['text'] }}">{{ $s['value'] }}</h3>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-xl font-extrabold text-gray-900 tracking-tight flex items-center gap-2">
                        📅 Jadwal Hari Ini
                        <span class="inline-flex items-center justify-center w-6 h-6 text-[10px] bg-indigo-600 text-white rounded-full">
                            {{ count($jadwalHariIni) }}
                        </span>
                    </h3>
                </div>

                <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden divide-y divide-gray-50">
                    @forelse($jadwalHariIni ?? [] as $jadwal)
                    <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-xl font-bold text-gray-400">
                                {{ substr($jadwal->kelas->nama_kelas, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg group-hover:text-indigo-600 transition-colors">
                                    {{ $jadwal->kelas->nama_kelas }}
                                </h4>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1">
                                    <span class="text-sm text-gray-500 flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $jadwal->mentor->name ?? 'Mentor' }}
                                    </span>
                                    <span class="text-sm text-indigo-500 font-medium flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="shrink-0">
                            @if($jadwal->absen_status == 'hadir')
                            <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-xs border border-emerald-100">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Terabsen
                            </div>
                            @elseif($jadwal->absen_status == 'tidak')
                            <div class="px-4 py-2 bg-rose-50 text-rose-700 rounded-xl font-bold text-xs border border-rose-100">
                                Absen Terlewat
                            </div>
                            @else
                            @if($jadwal->can_join)
                            <form method="POST" action="{{ route('attendance.store', $jadwal->id) }}">
                                @csrf
                                <button class="w-full sm:w-auto px-6 py-3 bg-indigo-600 text-white font-bold text-sm rounded-xl">
                                    Konfirmasi Kehadiran
                                </button>
                            </form>
                            @else
                            <div class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl text-xs font-bold">
                                Belum bisa join sesi sebelumnya
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-16 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-50 rounded-full mb-4">
                            <span class="text-2xl">☕</span>
                        </div>
                        <p class="text-gray-500 font-medium">Santai sejenak, tidak ada jadwal hari ini.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight px-2 flex items-center gap-2">
                    📆 Upcoming
                </h3>

                <div class="space-y-4">
                    @forelse($jadwalMendatang ?? [] as $jadwal)
                    <div class="group bg-white border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md transition-all">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="bg-indigo-50 text-indigo-600 font-bold p-2 rounded-lg text-center min-w-[50px]">
                                <p class="text-[10px] uppercase leading-none mb-1">Mei</p>
                                <p class="text-lg leading-none">05</p>
                            </div>
                            <h4 class="font-bold text-gray-800 leading-tight">{{ $jadwal->kelas->nama_kelas }}</h4>
                        </div>
                        <p class="text-xs text-gray-400 font-medium flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $jadwal->jam_mulai }} WIB
                        </p>
                    </div>
                    @empty
                    <p class="text-center py-10 text-xs text-gray-400 italic">No future plans yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex items-center justify-between px-2">
                <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">
                    📚 Koleksi Kelas Kamu
                </h3>
                <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 underline decoration-indigo-200 underline-offset-4">
                    Lihat Semua
                </a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($myKelas ?? [] as $kelas)
                <div class="group bg-white border border-gray-100 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:shadow-indigo-100 transition-all duration-500 overflow-hidden flex flex-col">

                    <div class="p-6 pb-0">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-black px-2.5 py-1 bg-slate-900 text-white rounded-lg uppercase tracking-widest">
                                {{ $kelas->periode ?? 'Active' }}
                            </span>
                            <div class="flex -space-x-2">
                                <div class="w-6 h-6 rounded-full border-2 border-white bg-indigo-500"></div>
                                <div class="w-6 h-6 rounded-full border-2 border-white bg-violet-500"></div>
                            </div>
                        </div>

                        <h4 class="text-xl font-black text-gray-900 group-hover:text-indigo-600 transition-colors">
                            {{ $kelas->nama_kelas }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-2 flex items-center gap-1.5 font-medium">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                            {{ optional($kelas->mentors->first())->name ?? 'Pengajar Ahli' }}
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="bg-slate-50 rounded-3xl p-4 space-y-3">
                            <div class="flex items-center justify-between text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">
                                <span>Materi Pembelajaran</span>
                                <span>{{ $kelas->moduls->count() }} Files</span>
                            </div>

                            <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar">
                                @forelse($kelas->moduls ?? [] as $modul)
                                <a href="{{ asset($modul->file_path) }}"
                                    class="group/item flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-2xl hover:border-indigo-300 hover:shadow-sm transition-all">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-lg group-hover/item:bg-indigo-600 group-hover/item:text-white transition-all">
                                        📂
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ $modul->original_name }}</p>
                                        <p class="text-[10px] text-gray-400 uppercase font-medium">Download PDF</p>
                                    </div>
                                </a>
                                @empty
                                <div class="text-center py-4 text-[11px] text-gray-400 italic font-medium">
                                    Belum ada modul yang diupload.
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full bg-gray-50 rounded-[3rem] py-20 border-2 border-dashed border-gray-200 text-center">
                    <span class="text-6xl mb-4 block">🎒</span>
                    <h4 class="text-xl font-bold text-gray-900">Belum ada kelas?</h4>
                    <p class="text-gray-500 mt-2 mb-6 max-w-xs mx-auto">Mulai perjalanan belajarmu sekarang dan kuasai skill baru!</p>
                    <a href="/catalog" class="px-8 py-3 bg-slate-900 text-white rounded-2xl font-bold hover:bg-indigo-600 transition-all">Jelajahi Kursus</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>


</x-app-layout>