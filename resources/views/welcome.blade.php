<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Skill Up IT — Level Up Your Career</title>

    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .text-gradient {
            background: linear-gradient(to right, #2563eb, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-900 overflow-x-hidden">

    <nav class="glass fixed w-full z-[100] px-8 py-4 flex justify-between items-center shadow-sm">
        <h1 class="text-2xl font-black tracking-tighter text-blue-600">SKILLUP<span class="text-slate-400">.IT</span></h1>

        <div class="hidden md:flex space-x-8 font-medium text-sm">
            <a href="#kelas" class="hover:text-blue-600 transition">Materi</a>
            <a href="#harga" class="hover:text-blue-600 transition">Pricing</a>
            <a href="#testimoni" class="hover:text-blue-600 transition">Testimoni</a>
        </div>

        <div class="space-x-3">
            @auth
            <a href="/dashboard" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl text-sm font-semibold shadow-lg shadow-blue-200">Dashboard</a>
            @else
            <a href="{{ route('login') }}" class="text-sm font-semibold px-4 py-2 hover:text-blue-600">Login</a>
            <a href="{{ route('register') }}" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-sm font-semibold hover:bg-blue-600 transition shadow-xl shadow-slate-200">Gabung Sekarang</a>
            @endauth
        </div>
    </nav>

    <section class="relative pt-32 pb-20 px-6 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] bg-blue-50 rounded-full blur-[120px] -z-10 opacity-60"></div>

        <div class="max-w-5xl mx-auto text-center">
            <div data-aos="fade-up" class="inline-block px-4 py-1.5 mb-6 text-xs font-bold tracking-widest text-blue-700 uppercase bg-blue-100 rounded-full">
                🚀 Platform Belajar IT No. 1
            </div>
            <h2 data-aos="fade-up" data-aos-delay="100" class="text-5xl md:text-7xl font-black mb-6 leading-[1.1] tracking-tight">
                Kuasai Skill Digital <br> <span class="text-gradient font-extrabold">Tanpa Batas.</span>
            </h2>
            <p data-aos="fade-up" data-aos-delay="200" class="text-lg text-slate-500 max-w-2xl mx-auto mb-10 leading-relaxed">
                Akses kurikulum berstandar industri dari praktisi ahli. Siapkan dirimu untuk transformasi karier yang lebih cepat dan terukur.
            </p>

            <div data-aos="fade-up" data-aos-delay="300" class="flex flex-col md:flex-row justify-center gap-4">
                <a href="#kelas" class="px-8 py-4 bg-blue-600 text-white rounded-2xl shadow-2xl shadow-blue-300 hover:scale-105 transition-all font-bold">
                    Mulai Belajar Sekarang
                </a>
                <a href="#harga" class="px-8 py-4 glass rounded-2xl border border-slate-200 hover:bg-white transition-all font-bold">
                    Lihat Paket Harga
                </a>
            </div>
        </div>
    </section>

    <section class="py-10 px-6">
        <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8 text-center">
            <div data-aos="zoom-in" data-aos-delay="100">
                <h4 class="text-3xl font-bold text-slate-800">10k+</h4>
                <p class="text-sm text-slate-500">Alumni Sukses</p>
            </div>
            <div data-aos="zoom-in" data-aos-delay="200">
                <h4 class="text-3xl font-bold text-slate-800">50+</h4>
                <p class="text-sm text-slate-500">Video Kursus</p>
            </div>
            <div data-aos="zoom-in" data-aos-delay="300">
                <h4 class="text-3xl font-bold text-slate-800">24/7</h4>
                <p class="text-sm text-slate-500">Mentor Support</p>
            </div>
            <div data-aos="zoom-in" data-aos-delay="400">
                <h4 class="text-3xl font-bold text-slate-800">4.9</h4>
                <p class="text-sm text-slate-500">Rating Kepuasan</p>
            </div>
        </div>
    </section>

    <section id="kelas" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none overflow-hidden">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] bg-blue-200/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] bg-indigo-200/20 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
                    Kelas <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Populer</span>
                </h2>
                <p class="text-slate-500 text-lg leading-relaxed">
                    Investasi terbaik adalah investasi pada dirimu sendiri. Mulai belajar dengan kurikulum berstandar industri.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($kelas as $item)
                <div class="group bg-white rounded-3xl p-4 border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-200 hover:-translate-y-1 transition-all duration-300 flex flex-col relative">

                    <div class="relative overflow-hidden rounded-2xl aspect-video mb-5 bg-slate-100">
                        @if($item->thumbnail)
                        <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->nama_kelas }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        @endif

                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur-md text-slate-900 text-xs font-bold uppercase tracking-wide rounded-full shadow-sm flex items-center gap-1">
                                <span class="text-orange-500">★</span> Populer
                            </span>
                        </div>
                    </div>

                    <div class="px-2 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-blue-50/50 text-blue-600 rounded-lg text-xs font-semibold border border-blue-100">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ $item->periode ? \Carbon\Carbon::parse($item->periode)->translatedFormat('d M Y') : 'Segera Hadir' }}</span>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold text-slate-900 mb-2 leading-snug group-hover:text-blue-600 transition-colors line-clamp-2">
                            {{ $item->nama_kelas }}
                        </h3>
                        <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-2">
                            {{ $item->deskripsi }}
                        </p>

                        @if($item->mentors && $item->mentors->count())
                        <div class="flex items-center gap-3 mb-6 mt-auto">
                            <div class="flex -space-x-2">
                                @foreach($item->mentors->take(3) as $mentor)
                                @if($mentor->photo)
                                <img src="{{ asset('storage/' . $mentor->photo) }}" alt="{{ $mentor->name }}" class="w-8 h-8 rounded-full border-2 border-white object-cover">
                                @else
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-indigo-600 text-white flex items-center justify-center text-xs font-bold">
                                    {{ strtoupper(substr($mentor->name, 0, 1)) }}
                                </div>
                                @endif
                                @endforeach
                            </div>
                            <span class="text-xs text-slate-500 font-medium">
                                {{ $item->mentors->count() }} Mentor Industri
                            </span>
                        </div>
                        @endif

                        <div class="pt-5 border-t border-slate-100 flex items-end justify-between mb-4">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Investasi</p>
                                <div class="flex flex-col">
                                    @if($item->harga_coret && $item->harga_coret > $item->harga)
                                    <span class="text-xs text-slate-400 line-through font-medium mb-0.5">
                                        Rp {{ number_format($item->harga_coret, 0, ',', '.') }}
                                    </span>
                                    @endif
                                    <span class="text-xl font-extrabold text-slate-900">
                                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick='openCheckoutModal(@json($item->id), @json($item->nama_kelas), @json($item->harga))' class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl font-semibold transition-all duration-300 shadow-md shadow-slate-200 hover:shadow-blue-200">
                            <span>Daftar Sekarang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full flex flex-col items-center justify-center py-24 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Belum Ada Kelas</h3>
                    <p class="text-slate-500 max-w-sm">Nantikan update kelas menarik dengan kurikulum terbaik dari kami segera.</p>
                </div>
                @endforelse
            </div>

            @if($kelas->hasPages())
            <div class="mt-16 flex justify-center">
                <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-200">
                    {{ $kelas->links() }}
                </div>
            </div>
            @endif
        </div>
    </section>

    <section class="py-24 bg-white overflow-hidden border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
                    Mentor <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Pilihan</span>
                </h2>
                <p class="text-slate-500 text-lg leading-relaxed">
                    Belajar langsung dari praktisi yang aktif di industri dan siap memandu perjalanan karirmu.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($mentors as $mentor)
                <div class="group bg-slate-50 rounded-3xl p-6 border border-slate-100 hover:bg-white hover:border-blue-100 hover:shadow-xl transition-all duration-300 flex flex-col items-center text-center relative overflow-hidden">

                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-b from-blue-50 to-transparent"></div>

                    <div class="relative w-24 h-24 rounded-full p-1 bg-white shadow-sm mb-5 z-10">
                        @if($mentor->photo)
                        <img src="{{ asset('storage/' . $mentor->photo) }}" alt="{{ $mentor->name }}" class="w-full h-full rounded-full object-cover">
                        @else
                        <div class="w-full h-full rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center text-2xl font-bold">
                            {{ strtoupper(substr($mentor->name, 0, 1)) }}
                        </div>
                        @endif
                    </div>

                    <div class="z-10 w-full">
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors truncate mb-1">
                            {{ $mentor->name }}
                        </h3>
                        <p class="text-sm font-medium text-blue-600 mb-4 truncate">
                            {{ $mentor->job_title ?? 'Expert Mentor' }}
                        </p>

                        <p class="text-sm text-slate-500 leading-relaxed mb-6 line-clamp-3">
                            {{ $mentor->bio ?? 'Praktisi industri yang siap membagikan pengalaman dan membimbing kamu meraih karir impian.' }}
                        </p>

                        <div class="pt-5 border-t border-slate-200/60 flex flex-col items-center gap-2">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wide">Mengajar</span>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-md">
                                    {{ $mentor->kelas->count() }} Kelas
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center text-slate-500 bg-slate-50 rounded-3xl border border-slate-100">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <p class="text-lg font-medium">Belum ada mentor yang ditampilkan.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="harga" class="py-24 bg-slate-900 text-white overflow-hidden">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-black mb-4">Investasi yang Masuk Akal</h2>
                <p class="text-slate-400">Bandingkan paket dan tentukan masa depanmu hari ini.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                <div data-aos="fade-right" data-aos-delay="100" class="p-10 rounded-[2.5rem] bg-slate-800/50 border border-slate-700 hover:bg-slate-800 transition-all">
                    <h3 class="text-xl font-bold mb-6">Personal Course</h3>
                    <div class="flex items-baseline gap-2 mb-8">
                        <span class="text-sm font-medium text-slate-400 font-normal">Mulai dari</span>
                        <span class="text-4xl font-black text-white">Rp 199k</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-slate-300">
                            <div class="p-1 bg-emerald-500/20 text-emerald-500 rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            Sertifikat Kelulusan
                        </li>
                        <li class="flex items-center gap-3 text-slate-300">
                            <div class="p-1 bg-emerald-500/20 text-emerald-500 rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            Akses Kelas Selamanya
                        </li>
                    </ul>
                    <button
                        type="button"
                        onclick="openCheckoutModal(null, 'Personal Course', 199000)"
                        class="w-full py-4 bg-white text-slate-900 font-black rounded-2xl hover:bg-blue-500 hover:text-white transition-all">
                        Beli Eceran
                    </button>
                </div>

                <div data-aos="fade-left" data-aos-delay="200" class="p-10 rounded-[2.5rem] bg-gradient-to-br from-blue-600 to-indigo-700 shadow-2xl shadow-blue-500/20 relative scale-105">
                    <div class="absolute top-6 right-6 px-4 py-1 bg-yellow-400 text-slate-900 text-[10px] font-black uppercase rounded-full">Best Value</div>
                    <h3 class="text-xl font-bold mb-6">Pro Subscription</h3>
                    <div class="flex items-baseline gap-2 mb-8">
                        <span class="text-4xl font-black text-white">Rp 150k</span>
                        <span class="text-sm text-blue-100 font-normal">/bulan</span>
                    </div>
                    <ul class="space-y-4 mb-10">
                        <li class="flex items-center gap-3 text-white">
                            <div class="p-1 bg-white/20 text-white rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            Akses SEMUA Kelas
                        </li>
                        <li class="flex items-center gap-3 text-white">
                            <div class="p-1 bg-white/20 text-white rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            Review Project oleh Mentor
                        </li>
                    </ul>
                    <button
                        type="button"
                        onclick="openCheckoutModal(null, 'Pro Subscription', 150000)"
                        class="w-full py-4 bg-white text-blue-600 font-black rounded-2xl hover:scale-[1.02] transition-all">
                        Berlangganan Sekarang
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-24 bg-slate-50 border-t border-slate-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->
            <div class="text-center max-w-2xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
                    Apa Kata <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Mereka?</span>
                </h2>
                <p class="text-slate-500 text-lg">
                    Testimoni dari para alumni yang sudah merasakan dampaknya 🚀
                </p>
            </div>

            <!-- Cards -->
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach([
                [
                'nama' => 'Andi Pratama',
                'job' => 'Frontend Developer',
                'pesan' => 'Materinya daging banget! Dari nol sampai bisa bikin project real. Mentor juga responsif 🔥',
                ],
                [
                'nama' => 'Siti Rahma',
                'job' => 'UI/UX Designer',
                'pesan' => 'Belajarnya enak, tidak kaku. Banyak studi kasus nyata yang bikin cepat paham.',
                ],
                [
                'nama' => 'Budi Santoso',
                'job' => 'Backend Engineer',
                'pesan' => 'Worth it banget. Setelah ikut kelas ini langsung dapat kerja freelance pertama!',
                ],
                [
                'nama' => 'Rizky Hidayat',
                'job' => 'Fullstack Developer',
                'pesan' => 'Dari bingung coding jadi ngerti flow aplikasi. Recommended untuk pemula.',
                ],
                [
                'nama' => 'Dewi Lestari',
                'job' => 'Data Analyst',
                'pesan' => 'Mentornya bukan kaleng-kaleng. Insight industri dapet banget!',
                ],
                [
                'nama' => 'Fajar Nugroho',
                'job' => 'Mobile Developer',
                'pesan' => 'UI platformnya juga nyaman. Belajar jadi lebih fokus dan fun.',
                ],
                ] as $testi)

                <div data-aos="fade-up" class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between">

                    <!-- Stars -->
                    <div class="flex items-center gap-1 mb-4 text-yellow-400">
                        ★★★★★
                    </div>

                    <!-- Pesan -->
                    <p class="text-slate-600 text-sm leading-relaxed mb-6">
                        "{{ $testi['pesan'] }}"
                    </p>

                    <!-- User -->
                    <div class="flex items-center gap-3 mt-auto">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr($testi['nama'], 0, 1)) }}
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">
                                {{ $testi['nama'] }}
                            </h4>
                            <p class="text-xs text-slate-500">
                                {{ $testi['job'] }}
                            </p>
                        </div>
                    </div>

                </div>
                @endforeach

            </div>
        </div>
    </section>

    <footer class="bg-white py-16 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-4 gap-12">
            <div class="col-span-2">
                <h2 class="text-2xl font-black tracking-tighter text-blue-600 mb-6">SKILLUP.IT</h2>
                <p class="text-slate-500 max-w-xs leading-relaxed">Platform edukasi berbasis project yang fokus pada kesiapan kerja di industri teknologi global.</p>
            </div>
            <div>
                <h4 class="font-bold mb-6">Navigasi</h4>
                <ul class="space-y-4 text-slate-500 text-sm font-medium">
                    <li><a href="#" class="hover:text-blue-600 transition">Karir</a></li>
                    <li><a href="#" class="hover:text-blue-600 transition">Blog</a></li>
                    <li><a href="#" class="hover:text-blue-600 transition">Bantuan</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold mb-6">Social Media</h4>
                <div class="flex gap-4">
                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition cursor-pointer">IG</div>
                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition cursor-pointer">YT</div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-8 mt-16 pt-8 border-t border-slate-50 text-center text-xs text-slate-400 font-medium">
            © {{ date('Y') }} Skill Up IT. Empowering Future Talent.
        </div>
    </footer>

    <!-- MODAL CHECKOUT -->
    <div id="checkoutModal" class="fixed inset-0 hidden items-center justify-center z-[999] bg-black/60 px-4">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl p-6 md:p-8 relative">
            <button type="button" onclick="closeCheckoutModal()" class="absolute top-4 right-4 text-slate-400 hover:text-red-500 text-2xl leading-none">
                ×
            </button>

            <div class="mb-6">
                <p class="text-sm font-bold text-blue-600 uppercase tracking-widest">Checkout</p>
                <h2 class="text-2xl font-black text-slate-900 mt-2">Isi Data Pembelian</h2>
                <p class="text-sm text-slate-500 mt-2">Lengkapi data untuk lanjut ke payment Xendit.</p>
            </div>

            <div class="mb-5 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <p class="text-xs uppercase tracking-widest text-slate-400 mb-1">Produk dipilih</p>
                <p id="selectedProductName" class="font-bold text-slate-900">-</p>
                <p id="selectedProductPrice" class="text-sm text-slate-500 mt-1">-</p>
                <p id="discountInfo" class="text-sm text-emerald-600 mt-2 hidden"></p>
            </div>

            <form id="checkoutForm">
                @csrf

                <input type="hidden" name="kelas_id" id="kelas_id">
                <input type="hidden" name="nama_kelas" id="nama_kelas">
                <input type="hidden" name="amount" id="amount">
                <input type="hidden" name="base_amount" id="base_amount">
                <input type="hidden" name="disc" id="disc">
                <input type="hidden" name="final_amount" id="final_amount">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" placeholder="Masukkan email"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nomor HP</label>
                        <input type="text" name="no_hp" id="no_hp" placeholder="Masukkan nomor HP"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Referral</label>
                        <input type="text" name="referral_kode" id="referral_kode"
                            placeholder="Masukkan kode referral (opsional)"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" id="btnCheckout"
                    class="mt-6 w-full rounded-2xl bg-blue-600 py-3.5 text-white font-bold hover:bg-blue-700 transition">
                    Lanjut ke Pembayaran
                </button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic',
        });

        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: "toast-top-right",
            timeOut: 2500
        };

        let basePrice = 0;
        let selectedDisc = 0;
        let selectedFinalPrice = 0;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number || 0);
        }

        function renderPrice() {
            if (!basePrice) {
                $('#selectedProductPrice').text('-');
                $('#discountInfo').addClass('hidden').text('');
                return;
            }

            if (selectedDisc > 0 && selectedFinalPrice > 0) {
                $('#selectedProductPrice').html(`
                    <span class="line-through text-rose-400 text-sm">
                        Rp ${formatRupiah(basePrice)}
                    </span><br>
                    <span class="font-bold text-slate-900">
                        Rp ${formatRupiah(selectedFinalPrice)}
                    </span>
                `);

                $('#discountInfo')
                    .removeClass('hidden')
                    .text(`Diskon ${selectedDisc}% aktif 🎉`);
            } else {
                $('#selectedProductPrice').text('Rp ' + formatRupiah(basePrice));
                $('#discountInfo').addClass('hidden').text('');
            }
        }

        function resetReferralState() {
            selectedDisc = 0;
            selectedFinalPrice = basePrice;
            $('#disc').val(0);
            $('#final_amount').val(basePrice);
            $('#amount').val(basePrice);
            renderPrice();
        }

        function openCheckoutModal(kelasId = null, namaKelas = '', amount = 0) {
            basePrice = parseInt(amount || 0);
            selectedDisc = 0;
            selectedFinalPrice = basePrice;

            $('#kelas_id').val(kelasId ?? '');
            $('#nama_kelas').val(namaKelas ?? '');
            $('#amount').val(basePrice);
            $('#base_amount').val(basePrice);
            $('#final_amount').val(basePrice);
            $('#disc').val(0);
            $('#referral_kode').val('');

            $('#selectedProductName').text(namaKelas || '-');
            renderPrice();

            $('#checkoutModal').removeClass('hidden').addClass('flex');
        }

        function closeCheckoutModal() {
            $('#checkoutModal').removeClass('flex').addClass('hidden');
            $('#checkoutForm')[0].reset();

            basePrice = 0;
            selectedDisc = 0;
            selectedFinalPrice = 0;

            $('#selectedProductName').text('-');
            $('#selectedProductPrice').text('-');
            $('#discountInfo').addClass('hidden').text('');
        }

        $('#checkoutModal').on('click', function(e) {
            if (e.target === this) {
                closeCheckoutModal();
            }
        });

        let referralTimer = null;

        $('#referral_kode').on('input', function() {
            const kode = $(this).val().trim();

            clearTimeout(referralTimer);

            if (!kode) {
                resetReferralState();
                return;
            }

            referralTimer = setTimeout(function() {
                $.get('/check-referral?kode=' + encodeURIComponent(kode))
                    .done(function(res) {
                        if (res.valid) {
                            const disc = parseInt(res.disc || 0);

                            selectedDisc = disc;
                            selectedFinalPrice = Math.max(0, basePrice - Math.round((basePrice * disc) / 100));

                            $('#disc').val(disc);
                            $('#final_amount').val(selectedFinalPrice);
                            $('#amount').val(selectedFinalPrice);

                            renderPrice();
                            toastr.success(`Diskon ${disc}% berhasil diterapkan`);
                        } else {
                            selectedDisc = 0;
                            selectedFinalPrice = basePrice;
                            $('#disc').val(0);
                            $('#final_amount').val(basePrice);
                            $('#amount').val(basePrice);

                            renderPrice();
                            toastr.error('Kode referral tidak valid');
                        }
                    })
                    .fail(function() {
                        selectedDisc = 0;
                        selectedFinalPrice = basePrice;
                        $('#disc').val(0);
                        $('#final_amount').val(basePrice);
                        $('#amount').val(basePrice);

                        renderPrice();
                        toastr.error('Gagal mengecek referral');
                    });
            }, 400);
        });

        $('#checkoutForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "/checkout",
                method: "POST",
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#btnCheckout').prop('disabled', true).text('Processing...');
                },
                success: function(res) {
                    if (res.status && res.url) {
                        toastr.success('Redirect ke pembayaran...');
                        setTimeout(() => {
                            window.location.href = res.url;
                        }, 700);
                        return;
                    }

                    toastr.error(res.message ?? 'Gagal checkout');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    toastr.error(xhr.responseJSON?.message ?? 'Terjadi kesalahan server');
                },
                complete: function() {
                    $('#btnCheckout').prop('disabled', false).text('Lanjut ke Pembayaran');
                }
            });
        });
    </script>
</body>

</html>