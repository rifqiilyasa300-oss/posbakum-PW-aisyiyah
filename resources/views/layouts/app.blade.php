<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Posbakum PW Aisyiyah DKI Jakarta</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        overflow-x: hidden !important;
        box-sizing: border-box !important;
    }
    *, *::before, *::after {
        box-sizing: inherit;
    }

    main.flex-grow,
    .container.mx-auto.px-4.relative.z-10.pt-20,
    .hero-section,
    section {
        padding-top: 0 !important;
        margin-top: 0 !important;
    }

    @media only screen and (max-width: 767px) {
        nav {
            height: 82px !important;
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 999;
        }
        main {
            margin-top: 100px !important;
            padding: 0 1rem !important;
        }
        .hero-section {
            padding-top: 110px !important;
        }

        .hero-section .button-group,
        .hero-section .btn-group,
        .hero-section .btn-ajukan,
        .hero-section .btn-wa {
            margin-top: -40px !important;
            margin-bottom: 35px !important;
            display: inline-block !important;
        }

        .hero-section .flex.flex-wrap {
            gap: 12px !important;
            margin-bottom: 40px !important;
        }

        table.riwayat-perkara {
            width: 100% !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            margin: 0 !important;
        }

        table.riwayat-perkara thead {
            display: none !important;
        }

        table.riwayat-perkara tr {
            display: block !important;
            width: 100% !important;
            margin-bottom: 1.25rem !important;
            background: #ffffff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 0.75rem !important;
            overflow: hidden !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04) !important;
        }

        table.riwayat-perkara td {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 14px 18px !important;
            border: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
            font-size: 14px !important;
            line-height: 1.5 !important;
            min-height: 50px !important;
        }

        table.riwayat-perkara td::before {
            content: attr(data-label);
            font-weight: 600 !important;
            color: #475569 !important;
            text-align: left !important;
            margin-right: 1rem !important;
            flex-shrink: 0 !important;
            width: 115px !important;
        }

        table.riwayat-perkara td > div,
        table.riwayat-perkara td > span,
        table.riwayat-perkara td > a {
            text-align: right !important;
            margin-left: auto !important;
            max-width: calc(100% - 130px) !important;
        }

        table.riwayat-perkara td.aksi,
        table.riwayat-perkara td:last-child {
            border-bottom: none !important;
            background-color: #fafbfc !important;
            justify-content: center !important;
            padding: 16px !important;
            min-height: auto !important;
        }

        table.riwayat-perkara td.aksi a {
            padding: 8px 16px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 0.5rem !important;
            text-decoration: none !important;
            background-color: #ecfdf5 !important;
            color: #065f46 !important;
            transition: all 0.2s ease;
        }

        table.riwayat-perkara td.aksi a:hover {
            background-color: #d1fae5 !important;
        }
    }

    @media only screen and (min-width: 768px) {
        nav {
            height: 80px !important;
        }
        main {
            margin-top: 90px !important;
        }
        .hero-section {
            padding-top: 100px !important;
        }
        .hero-section .button-group {
            margin-top: -25px !important;
            margin-bottom: 30px !important;
            gap: 12px !important;
        }

        table.riwayat-perkara {
            width: 100% !important;
            table-layout: fixed !important;
            border-collapse: collapse !important;
        }

        table.riwayat-perkara th:nth-child(1),
        table.riwayat-perkara td:nth-child(1) {
            width: 25% !important;
            text-align: left !important;
            vertical-align: middle !important;
        }

        table.riwayat-perkara th:nth-child(2),
        table.riwayat-perkara td:nth-child(2) {
            width: 50% !important;
            text-align: center !important;
            vertical-align: middle !important;
        }

        table.riwayat-perkara th:nth-child(3),
        table.riwayat-perkara td:nth-child(3) {
            width: 25% !important;
            text-align: right !important;
            vertical-align: middle !important;
        }

        table.riwayat-perkara th,
        table.riwayat-perkara td {
            padding: 16px 20px !important;
            border-bottom: 1px solid #e5e7eb !important;
        }

        table.riwayat-perkara th {
            background: #f9fafb !important;
            font-weight: 600 !important;
            color: #475569 !important;
        }

        table.riwayat-perkara td:last-child > div {
            display: flex !important;
            flex-direction: column !important;
            align-items: flex-end !important;
            justify-content: center !important;
            gap: 8px !important;
            min-height: 60px !important;
        }
    }

    .bg-white\/60,
    .card-kosong,
    .empty-state {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        text-align: center !important;
        min-height: 180px !important;
        width: 100% !important;
        padding: 2rem !important;
    }
</style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">

        @include('layouts.navigation')

        <main class="flex-grow w-full">
            {{ $slot }}
        </main>

        <footer class="py-6 bg-slate-950 text-white">
            <div class="container mx-auto px-4 text-center">
                <div class="mb-4">
                    <a href="https://www.instagram.com/posbakumaisyiyah.dki/" target="_blank" class="inline-flex items-center justify-center w-10 h-10 bg-white/5 rounded-full text-white hover:bg-emerald-600 transition-all duration-300">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] md:text-xs uppercase tracking-[0.2em] font-bold text-slate-500">
                        &copy; 2026 POSBAKUM PW AISYIYAH DKI JAKARTA
                    </p>
                    <p class="text-[9px] md:text-xs uppercase tracking-widest text-slate-600 opacity-50">
                        Seluruh Hak Cipta Dilindungi
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @if(!auth()->check() || auth()->user()->role == 'klien')
        <div x-data="{
            open: false,
            messages: [{ role: 'bot', text: 'Halo! Saya Asisten AI Posbakum. Apa yang bisa saya bantu?' }],
            userInput: '',
            showMenu: true,
            showFAQ: false,

            selectOption(type) {
                this.showMenu = false;
                if(type === 'faq') {
                    this.showFAQ = true;
                    this.messages.push({ role: 'bot', text: 'Silakan pilih topik bantuan atau ketik pertanyaan Anda:' });
                } else {
                    window.open('https://wa.me/628161159564?text=Halo Admin, saya ingin mendaftar layanan.', '_blank');
                    this.showMenu = true;
                }
            },

            selectFAQTopic(topic) {
                let reply = '';
                if(topic === 'syarat') {
                    reply = 'Syarat Prodeo: KTP DKI & SKTM. Syarat Umum: KTP & Bukti bayar pendaftaran.';
                } else if(topic === 'biaya') {
                    reply = 'Layanan Prodeo adalah Gratis (dengan SKTM). Untuk layanan Umum, biaya pendaftaran sebesar Rp300.000.';
                } else if(topic === 'lokasi') {
                    reply = 'Kantor pusat kami berada di Jl. Tebet Timur Dalam No. 4, Jakarta Selatan.';
                }
                this.messages.push({ role: 'user', text: topic.toUpperCase() });
                this.messages.push({ role: 'bot', text: reply });
            },

            askAI() {
                if (!this.userInput.trim()) return;
                this.messages.push({ role: 'user', text: this.userInput });
                let input = this.userInput.toLowerCase();
                let reply = 'Maaf, silakan gunakan tombol FAQ atau hubungi Admin kami via WA untuk info lebih lanjut.';
                if(input.includes('syarat')) reply = 'Syarat Prodeo: KTP & SKTM. Syarat Umum: KTP & Bukti bayar Rp300.000.';
                else if(input.includes('biaya') || input.includes('harga')) reply = 'Prodeo Gratis dengan SKTM. Layanan Umum biaya pendaftarannya Rp300.000.';
                else if(input.includes('lokasi')) reply = 'Kami berlokasi di Jl. Tebet Timur Dalam No. 4, Jakarta Selatan.';
                this.messages.push({ role: 'bot', text: reply });
                this.userInput = '';
            }
        }" class="fixed bottom-6 right-6 z-[99999]">

            <button @click="open = !open"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white w-16 h-16 rounded-full shadow-2xl flex items-center justify-center transition-all transform hover:scale-110 active:scale-95">
                <i x-show="!open" class="fas fa-comment-dots text-2xl"></i>
                <i x-show="open" class="fas fa-times text-2xl"></i>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-300"
                 class="absolute bottom-20 right-0 w-[350px] max-w-[calc(100vw-2rem)] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col border border-slate-100">
                <div class="bg-emerald-800 p-5 text-white font-bold text-sm">AI Assistant Posbakum</div>
                <div class="h-80 overflow-y-auto p-4 bg-slate-50">
                    <template x-for="msg in messages">
                        <div :class="msg.role === 'user' ? 'text-right mb-2' : 'text-left mb-2'">
                            <span :class="msg.role === 'user' ? 'bg-emerald-600 text-white px-4 py-2 rounded-2xl inline-block text-sm' : 'bg-white border px-4 py-2 rounded-2xl inline-block text-sm'">
                                <span x-text="msg.text"></span>
                            </span>
                        </div>
                    </template>
                    <div x-show="showMenu" class="grid grid-cols-1 gap-2 mt-2">
                        <button @click="selectOption('faq')" class="bg-white border border-emerald-600 text-emerald-700 hover:bg-emerald-50 py-2 rounded-xl text-xs font-bold transition">Tanya FAQ</button>
                        <button @click="selectOption('wa')" class="bg-emerald-600 text-white hover:bg-emerald-700 py-2 rounded-xl text-xs font-bold transition">Lanjut ke WA</button>
                    </div>
                    <div x-show="showFAQ" class="grid grid-cols-2 gap-2 mt-2">
                        <button @click="selectFAQTopic('syarat')" class="bg-white border border-slate-300 hover:bg-emerald-50 px-3 py-2 rounded-full text-[10px] text-slate-700 transition">Syarat Berkas</button>
                        <button @click="selectFAQTopic('biaya')" class="bg-white border border-slate-300 hover:bg-emerald-50 px-3 py-2 rounded-full text-[10px] text-slate-700 transition">Info Biaya</button>
                        <button @click="selectFAQTopic('lokasi')" class="bg-white border border-slate-300 hover:bg-emerald-50 px-3 py-2 rounded-full text-[10px] text-slate-700 transition">Lokasi Kantor</button>
                        <button @click="showMenu=true; showFAQ=false" class="bg-slate-200 border border-slate-300 hover:bg-slate-300 px-3 py-2 rounded-full text-[10px] text-slate-700 font-bold">Kembali</button>
                    </div>
                </div>
                <form @submit.prevent="askAI" class="p-3 border-t bg-white">
                    <input type="text" x-model="userInput" placeholder="Atau ketik pertanyaan Anda..." class="w-full px-4 py-2 rounded-full border text-xs focus:outline-emerald-500">
                </form>
            </div>
        </div>
    @endif
</body>
</html>
