<x-app-layout>

    {{-- OPTIONAL CUSTOM SCROLLBAR --}}
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
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

        <div class="space-y-5 mt-10">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-extrabold text-gray-900 flex items-center gap-2">
                    🏆 Sertifikat Kamu
                </h3>

                <span class="text-xs font-bold px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full">
                    {{ $certificates->count() }} Earned
                </span>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($certificates as $cert)
                <button
                    onclick="openCertModal(
                        '{{ addslashes(Auth::user()->name) }}', 
                        '{{ addslashes($cert->kelas->nama_kelas ?? 'SkillUpIT Program') }}', 
                        '{{ $cert->no }}', 
                        '{{ \Carbon\Carbon::parse($cert->order->created_at)->translatedFormat('d F Y') }}'
                    )"
                    class="group relative bg-white border border-gray-100 rounded-2xl p-5 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 text-left overflow-hidden">

                    <!-- glow effect -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-100 rounded-full blur-2xl opacity-40 group-hover:opacity-70 transition"></div>

                    <div class="relative">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 text-lg">
                                🏅
                            </div>

                            <span class="text-[10px] px-2 py-1 bg-emerald-50 text-emerald-600 font-bold rounded-full">
                                CERTIFIED
                            </span>
                        </div>

                        <h4 class="mt-4 font-bold text-gray-900 group-hover:text-indigo-600 transition">
                            {{ $cert->kelas->nama_kelas ?? 'SkillUpIT Program' }}
                        </h4>

                        <p class="text-xs text-gray-500 mt-1">
                            Certificate Code: <span class="font-semibold">{{ $cert->no }}</span>
                        </p>

                        <div class="mt-4 flex items-center justify-between">
                            <span class="text-[10px] text-gray-400">
                                Click to view
                            </span>

                            <span class="text-indigo-500 text-xs font-bold group-hover:translate-x-1 transition">
                                Open →
                            </span>
                        </div>
                    </div>
                </button>
                @empty
                <div class="col-span-full bg-gray-50 border border-dashed rounded-2xl p-10 text-center">
                    <div class="text-4xl">🏆</div>
                    <p class="text-sm text-gray-500 mt-2">Belum ada sertifikat yang tersedia</p>
                </div>
                @endforelse
            </div>
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
                                <p class="text-[10px] uppercase leading-none mb-1">
                                    {{ \Carbon\Carbon::parse($jadwal->date)->translatedFormat('M') }}
                                </p>
                                <p class="text-lg leading-none">
                                    {{ \Carbon\Carbon::parse($jadwal->date)->format('d') }}
                                </p>
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

        <div class="space-y-8">

            {{-- HEADER --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                        📚 Koleksi Kelas Kamu
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Semua materi pembelajaran dan modul kelas tersedia di sini.
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    {{-- SEARCH --}}
                    <div class="relative w-full sm:w-72">

                        <input
                            type="text"
                            id="searchKelas"
                            placeholder="Cari kelas..."
                            class="w-full rounded-2xl border border-gray-200 bg-white pl-11 pr-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                            🔍
                        </div>

                    </div>

                    {{-- TOTAL --}}
                    <div class="hidden sm:flex items-center px-4 py-3 rounded-2xl bg-indigo-50 border border-indigo-100">

                        <span class="text-xs font-black uppercase tracking-widest text-indigo-600">
                            {{ count($myKelas) }} Classes
                        </span>

                    </div>

                </div>

            </div>

            {{-- GRID --}}
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-7">

                @forelse($myKelas ?? [] as $kelas)

                <div
                    class="kelas-item group relative bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl hover:shadow-indigo-100 hover:-translate-y-1 transition-all duration-500 flex flex-col">

                    {{-- GLOW --}}
                    <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-100 rounded-full blur-3xl opacity-0 group-hover:opacity-60 transition-all duration-500"></div>

                    {{-- HEADER --}}
                    <div class="relative p-7 pb-5">

                        <div class="flex items-start justify-between gap-4">

                            <div>

                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em]">
                                    {{ $kelas->periode ?? 'Active' }}
                                </span>

                                <h4 class="text-2xl font-black text-gray-900 mt-4 leading-tight group-hover:text-indigo-600 transition-colors">
                                    {{ $kelas->nama_kelas }}
                                </h4>

                                <div class="flex items-center gap-2 mt-3">

                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ strtoupper(substr(optional($kelas->mentors->first())->name ?? 'M', 0, 1)) }}
                                    </div>

                                    <div>

                                        <p class="text-xs uppercase tracking-widest text-gray-400 font-bold">
                                            Mentor
                                        </p>

                                        <p class="text-sm font-semibold text-gray-700">
                                            {{ optional($kelas->mentors->first())->name ?? 'Pengajar Ahli' }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                            {{-- FILE COUNT --}}
                            <div class="shrink-0 text-right">

                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center text-2xl">
                                    📚
                                </div>

                                <p class="text-[11px] text-gray-400 font-bold mt-2 uppercase tracking-widest">
                                    {{ $kelas->moduls->count() }} Files
                                </p>

                            </div>

                        </div>

                    </div>

                    {{-- MODUL --}}
                    <div class="relative px-7 pb-7 flex-1">

                        <div class="bg-slate-50 border border-slate-100 rounded-[2rem] p-4">

                            <div class="flex items-center justify-between mb-4">

                                <div>
                                    <h5 class="text-sm font-black text-gray-900">
                                        Materi Pembelajaran
                                    </h5>

                                    <p class="text-[11px] text-gray-400 mt-1">
                                        PDF hanya bisa dibuka di website
                                    </p>
                                </div>

                                <div class="w-10 h-10 rounded-2xl bg-white border border-gray-100 flex items-center justify-center">
                                    📄
                                </div>

                            </div>

                            {{-- LIST MODUL --}}
                            <div class="space-y-3 max-h-72 overflow-y-auto custom-scrollbar pr-1">

                                @forelse($kelas->moduls ?? [] as $modul)

                                <button
                                    type="button"
                                    onclick="openPdfViewer('{{ asset($modul->file_path) }}')"
                                    class="w-full group/item text-left bg-white border border-gray-100 hover:border-indigo-200 rounded-2xl p-4 transition-all hover:shadow-md">

                                    <div class="flex items-start gap-4">

                                        {{-- ICON --}}
                                        <div class="w-12 h-12 shrink-0 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl group-hover/item:bg-indigo-600 group-hover/item:text-white transition-all">
                                            📘
                                        </div>

                                        {{-- TEXT --}}
                                        <div class="flex-1 min-w-0">

                                            <h6 class="text-sm font-bold text-gray-800 truncate group-hover/item:text-indigo-600 transition-colors">
                                                {{ $modul->original_name }}
                                            </h6>

                                            <div class="flex items-center justify-between mt-2">

                                                <p class="text-[10px] uppercase tracking-widest font-bold text-gray-400">
                                                    View PDF
                                                </p>

                                                <span class="text-xs font-bold text-indigo-500 group-hover/item:translate-x-1 transition-transform">
                                                    Open →
                                                </span>

                                            </div>

                                        </div>

                                    </div>

                                </button>

                                @empty

                                {{-- EMPTY MODUL --}}
                                <div class="py-10 text-center">

                                    <div class="w-16 h-16 mx-auto rounded-full bg-white border border-dashed border-gray-200 flex items-center justify-center text-3xl mb-4">
                                        📂
                                    </div>

                                    <p class="text-sm font-semibold text-gray-500">
                                        Belum ada modul
                                    </p>

                                    <p class="text-xs text-gray-400 mt-1">
                                        Mentor belum mengupload materi.
                                    </p>

                                </div>

                                @endforelse

                            </div>

                        </div>

                    </div>

                </div>

                @empty

                {{-- EMPTY CLASS --}}
                <div class="col-span-full">

                    <div class="bg-gradient-to-br from-slate-50 to-white border-2 border-dashed border-gray-200 rounded-[3rem] py-24 px-6 text-center">

                        <div class="w-28 h-28 mx-auto rounded-full bg-white shadow-sm border border-gray-100 flex items-center justify-center text-6xl mb-8">
                            🎒
                        </div>

                        <h4 class="text-3xl font-black text-gray-900">
                            Belum ada kelas?
                        </h4>

                        <p class="text-gray-500 mt-4 mb-8 max-w-md mx-auto leading-relaxed">
                            Mulai perjalanan belajarmu sekarang dan kuasai skill baru bersama mentor terbaik.
                        </p>

                        <a href="/catalog"
                            class="inline-flex items-center gap-3 px-8 py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-indigo-600 transition-all duration-300 shadow-lg shadow-slate-200">

                            <span>🚀</span>
                            <span>Jelajahi Kursus</span>

                        </a>

                    </div>

                </div>

                @endforelse

            </div>

        </div>



        <div class="space-y-6">
            <div class=" space-y-6 mt-10">

                {{-- HEADER --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                    <div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
                            🎥 Rekaman Pembelajaran
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Putar ulang materi kelas kapan saja langsung dari dashboard.
                        </p>
                    </div>

                    <div class="flex items-center gap-2">

                        <div class="px-4 py-2 rounded-2xl bg-red-50 border border-red-100">
                            <span class="text-xs font-black tracking-wider text-red-600 uppercase">
                                {{ count($historyVideos) }} Videos
                            </span>
                        </div>

                    </div>
                </div>

                {{-- CONTAINER --}}
                <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">

                    {{-- SEARCH BAR --}}
                    <div class="p-5 border-b border-gray-100 bg-slate-50/70">

                        <div class="relative">

                            <input
                                type="text"
                                id="searchVideo"
                                placeholder="Cari rekaman pembelajaran..."
                                class="w-full rounded-2xl border border-gray-200 bg-white pl-12 pr-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">

                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                🔍
                            </div>

                        </div>

                    </div>

                    {{-- LIST --}}
                    <div class="max-h-[650px] overflow-y-auto custom-scrollbar">

                        @forelse($historyVideos as $video)

                        <button
                            data-title="{{ strtolower($video->title) }}"
                            data-kelas="{{ strtolower($video->kelas) }}"
                            onclick="openVideoModal(
                    '{{ $video->video }}',
                    '{{ addslashes($video->kelas) }}',
                    '{{ addslashes($video->title) }}',
                    '{{ $video->session }}'
                )"
                            class="video-item group w-full flex items-center gap-4 p-5 hover:bg-slate-50 transition-all duration-300 border-b border-gray-100 last:border-0 text-left">

                            {{-- THUMBNAIL --}}
                            <div class="relative shrink-0">

                                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-br from-red-500 via-rose-500 to-pink-500 flex items-center justify-center shadow-lg shadow-red-100">

                                    <span class="text-3xl">
                                        🎬
                                    </span>

                                </div>

                                {{-- PLAY BADGE --}}
                                <div class="absolute -bottom-2 -right-2 w-9 h-9 rounded-2xl bg-white shadow-lg border border-gray-100 flex items-center justify-center text-sm">
                                    ▶
                                </div>

                            </div>

                            {{-- CONTENT --}}
                            <div class="flex-1 min-w-0">

                                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">

                                    {{-- LEFT --}}
                                    <div class="min-w-0">

                                        <div class="flex flex-wrap items-center gap-2 mb-2">

                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest">
                                                Session {{ $video->session }}
                                            </span>

                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-bold uppercase tracking-widest">
                                                Recording
                                            </span>

                                        </div>

                                        <h4 class="text-base sm:text-lg font-black text-gray-900 truncate group-hover:text-indigo-600 transition-colors">
                                            {{ $video->kelas }}
                                        </h4>

                                        <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                            {{ $video->title }}
                                        </p>

                                    </div>

                                    {{-- RIGHT --}}
                                    <div class="flex lg:flex-col items-start lg:items-end justify-between gap-3 shrink-0">

                                        <div class="text-left lg:text-right">

                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">
                                                Uploaded
                                            </p>

                                            <p class="text-sm font-semibold text-gray-700 mt-1">
                                                {{ \Carbon\Carbon::parse($video->date)->format('d M Y') }}
                                            </p>

                                        </div>

                                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-gray-50 border border-gray-100 text-sm font-bold text-gray-700 group-hover:bg-indigo-600 group-hover:text-white transition-all">

                                            <span>Watch</span>

                                            <span class="group-hover:translate-x-1 transition-transform">
                                                →
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </button>

                        @empty

                        {{-- EMPTY --}}
                        <div class="p-16 text-center">

                            <div class="w-24 h-24 mx-auto rounded-full bg-gray-100 flex items-center justify-center text-5xl mb-6">
                                🎬
                            </div>

                            <h4 class="text-xl font-black text-gray-800">
                                Belum ada recording
                            </h4>

                            <p class="text-sm text-gray-400 mt-3 max-w-md mx-auto leading-relaxed">
                                Video pembelajaran akan otomatis muncul setelah mentor mengupload rekaman kelas.
                            </p>

                        </div>

                        @endforelse

                    </div>

                </div>

            </div>
        </div>
    </div>


    {{-- VIDEO MODAL --}}
    <div id="videoModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">

        <div class="bg-white w-full max-w-5xl rounded-[2rem] overflow-hidden shadow-2xl">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">

                <div>
                    <h3 id="videoModalKelas"
                        class="text-xl font-black text-gray-900">
                    </h3>

                    <p id="videoModalTitle"
                        class="text-sm text-gray-500 mt-1">
                    </p>
                </div>

                <button
                    onclick="closeVideoModal()"
                    class="w-11 h-11 rounded-2xl bg-red-50 hover:bg-red-100 text-red-600 font-bold transition-all">
                    ✕
                </button>
            </div>

            {{-- VIDEO --}}
            <div class="bg-black">

                <video
                    id="videoPlayer"
                    controls
                    controlsList="nodownload"
                    disablePictureInPicture
                    class="w-full h-[70vh] bg-black"
                    oncontextmenu="return false;">

                    <source id="videoSource" src="" type="video/mp4">

                </video>

            </div>
        </div>
    </div>


    {{-- PDF MODAL VIEWER --}}
    <div id="pdfModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 backdrop-blur-sm p-4">

        <div class="bg-white w-full max-w-7xl h-[92vh] rounded-[2rem] overflow-hidden shadow-2xl flex flex-col">

            {{-- HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">

                <div>
                    <h2 class="text-lg font-black text-gray-900">
                        📚 View Materi
                    </h2>

                    <p class="text-xs text-gray-400 mt-1">
                        PDF hanya dapat dilihat di website
                    </p>
                </div>

                <button
                    onclick="closePdfViewer()"
                    class="w-11 h-11 rounded-2xl bg-red-50 hover:bg-red-100 text-red-600 font-bold transition-all">
                    ✕
                </button>
            </div>

            {{-- PDF CONTENT --}}
            <div class="flex-1 bg-gray-100">

                <iframe
                    id="pdfFrame"
                    src=""
                    class="w-full h-full"
                    frameborder="0">
                </iframe>

            </div>
        </div>
    </div>

    <div id="certModal"
        class="fixed inset-0 hidden items-center justify-center bg-black/70 backdrop-blur-sm z-50 p-4">

        <div class="relative w-full max-w-4xl animate-[fadeIn_0.25s_ease]">

            <!-- CLOSE -->
            <button onclick="closeCertModal()"
                class="absolute -top-12 right-0 text-white text-2xl font-bold hover:scale-110 transition">
                ✕
            </button>

            <!-- CERTIFICATE FRAME -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/10 mx-auto" style="max-width: 900px;">

                <img src="{{ asset('uploads/sertif/sertif.png') }}" class="w-full h-auto block">

                <div class="absolute inset-0 flex flex-col items-center">

                    <div class="absolute" style="top: 30%;">
                        <p id="certCode" class="text-[#e11d48] text-[1.1vw] font-medium tracking-widest"></p>
                    </div>

                    <div class="absolute" style="top: 45%; width: 80%;">
                        <h2 id="certName" class="text-[#1a1a1a] text-[2.8vw] font-bold text-center uppercase tracking-tight"></h2>
                    </div>

                    <div class="absolute" style="top: 55%; width: 85%;">
                        <p id="certDate" class="text-[#333] text-[1.3vw] text-center font-medium leading-relaxed">
                        </p>
                    </div>

                </div>
            </div>
            <!-- ACTION -->
            <div class="flex justify-end mt-4 gap-3">

                <button onclick="closeCertModal()"
                    class="px-4 py-2 rounded-xl bg-white/10 text-white text-xs font-bold hover:bg-white/20 transition">
                    Close
                </button>

                <a href="{{ asset('uploads/sertif/sertif.png') }}"
                    download
                    class="px-5 py-2 rounded-xl bg-indigo-600 text-white text-xs font-bold hover:bg-indigo-700 transition">
                    Download
                </a>
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

    <script>
        function openCertModal(name, kelas, code, tanggal) {
            const elName = document.getElementById('certName');
            const elCode = document.getElementById('certCode');
            const elDate = document.getElementById('certDate');

            if (elName) elName.innerText = name;
            if (elCode) elCode.innerText = "NO: " + code;

            // Menggabungkan kalimat dengan Nama Kelas dan Tanggal
            if (elDate) {
                elDate.innerText = "Telah menyelesaikan pelatihan " + kelas + " pada tanggal " + tanggal;
            }

            document.getElementById('certModal').classList.remove('hidden');
            document.getElementById('certModal').classList.add('flex');
        }

        function closeCertModal() {
            const modal = document.getElementById('certModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>

    {{-- SCRIPT PDF VIEWER --}}
    <script>
        function openPdfViewer(pdfUrl) {

            const iframe = document.getElementById('pdfFrame');

            // hide toolbar download print
            iframe.src = pdfUrl + '#toolbar=0&navpanes=0&scrollbar=1';

            const modal = document.getElementById('pdfModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }

        function closePdfViewer() {

            const modal = document.getElementById('pdfModal');

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.getElementById('pdfFrame').src = '';

            document.body.classList.remove('overflow-hidden');
        }

        // close modal when click outside
        document.getElementById('pdfModal').addEventListener('click', function(e) {

            if (e.target === this) {
                closePdfViewer();
            }
        });
    </script>

    <script>
        function openVideoModal(videoUrl, kelas, title, session) {

            document.getElementById('videoSource').src = videoUrl;

            const player = document.getElementById('videoPlayer');

            player.load();

            document.getElementById('videoModalKelas').innerText =
                kelas + ' • Session ' + session;

            document.getElementById('videoModalTitle').innerText = title;

            const modal = document.getElementById('videoModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }

        function closeVideoModal() {

            const modal = document.getElementById('videoModal');

            const player = document.getElementById('videoPlayer');

            player.pause();

            modal.classList.add('hidden');
            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }

        // close when click outside
        document.getElementById('videoModal').addEventListener('click', function(e) {

            if (e.target === this) {
                closeVideoModal();
            }
        });
    </script>
    <script>
        // SEARCH VIDEO
        document.getElementById('searchVideo')?.addEventListener('keyup', function() {

            let value = this.value.toLowerCase();

            document.querySelectorAll('.video-item').forEach(item => {

                const title = item.dataset.title;
                const kelas = item.dataset.kelas;

                if (title.includes(value) || kelas.includes(value)) {

                    item.style.display = 'flex';

                } else {

                    item.style.display = 'none';
                }
            });
        });
    </script>

    <script>
        // SEARCH KELAS
        document.getElementById('searchKelas')?.addEventListener('keyup', function() {

            let value = this.value.toLowerCase();

            document.querySelectorAll('.kelas-item').forEach(item => {

                const text = item.innerText.toLowerCase();

                if (text.includes(value)) {

                    item.style.display = 'flex';

                } else {

                    item.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>