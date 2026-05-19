<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Tree Hasan Basri - Gen Z Edition</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #6366f1;
            --secondary: #ec4899;
            --accent: #10b981;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background Mesh */
        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.1) 0px, transparent 50%);
            z-index: -1;
        }

        /* Loading Overlay Modern */
        #loader {
            position: fixed;
            inset: 0;
            z-index: 999;
            background: #0f172a;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #loader.fade-out {
            opacity: 0;
            visibility: hidden;
            transform: scale(1.1);
        }

        /* Tree Container Customization */
        #tree {
            width: 100%;
            min-height: 80vh;
            margin-top: 20px;
            padding: 40px;
        }

        /* Connectors (Garis) Modern */
        .Treant>svg path {
            stroke: #cbd5e1 !important;
            stroke-width: 3px !important;
            stroke-dasharray: 8;
            animation: dash 30s linear infinite;
        }

        @keyframes dash {
            to {
                stroke-dashoffset: -1000;
            }
        }

        /* Node Styling - Glassmorphism */
        .node {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 16px;
            width: 210px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .node:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 1);
            z-index: 50 !important;
        }

        .node.selected {
            border: 2px solid var(--primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
        }

        /* Element Logic Fix */
        .node img,
        .node div,
        .node span {
            pointer-events: none;
        }

        /* Avatars */
        .node img {
            width: 65px;
            height: 65px;
            border-radius: 20px;
            /* Squircle style */
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            margin: 0 auto 12px;
            transition: transform 0.3s ease;
        }

        .collage {
            display: flex;
            justify-content: center;
            margin-bottom: 12px;
        }

        .collage img:nth-child(2) {
            margin-left: -20px;
            transform: rotate(5deg);
        }

        .collage img:nth-child(1) {
            transform: rotate(-5deg);
            z-index: 1;
        }

        /* Border Indicator Types */
        .male {
            border-bottom: 6px solid #3b82f6;
        }

        .female {
            border-bottom: 6px solid #ec4899;
        }

        .couple {
            border-bottom: 6px solid #10b981;
        }

        .multiple-branch {
            background: rgba(241, 245, 249, 0.7);
            border: 2px dashed #94a3b8;
        }

        /* Treant Collapse Switch */
        .Treant .collapse-switch {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
            opacity: 0;
            z-index: 10;
        }

        /* Custom Collapse Button */
        .node::after {
            content: "↓";
            display: inline-block;
            margin-top: 8px;
            width: 24px;
            height: 24px;
            line-height: 24px;
            background: #f1f5f9;
            border-radius: 50%;
            font-size: 12px;
            color: #64748b;
            transition: all 0.3s;
        }

        .node.collapsed::after {
            content: "+";
            background: var(--primary);
            color: white;
            transform: rotate(0deg);
            box-shadow: 0 4px 10px rgba(99, 102, 241, 0.4);
        }

        /* Typography Wrappers */
        .name-wrapper {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .role-wrapper {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="p-4 md:p-8">

    <div id="loader">
        <div class="relative">
            <div class="w-20 h-20 border-4 border-indigo-500/20 border-t-indigo-500 rounded-full animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-indigo-500 text-xs font-bold">HB</span>
            </div>
        </div>
        <h2 class="text-white font-black mt-6 tracking-[0.3em] text-sm">LOADING ARCHIVE</h2>
    </div>

    <div class="max-w-[1600px] mx-auto">
        <header class="flex flex-col lg:flex-row justify-between items-center mb-12 gap-6 bg-white/40 p-6 rounded-[2.5rem] border border-white/60 backdrop-blur-md shadow-sm">
            <div>
                <span class="text-indigo-600 font-bold tracking-widest text-xs uppercase mb-2 block">The Legacy of</span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight">
                    Hasan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-pink-500">Basri</span>
                </h1>
            </div>

            <div class="flex flex-wrap justify-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                    <span class="text-sm font-semibold text-slate-600">Male</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-3 h-3 rounded-full bg-pink-500"></div>
                    <span class="text-sm font-semibold text-slate-600">Female</span>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-2xl shadow-sm border border-slate-100">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <span class="text-sm font-semibold text-slate-600">Couple</span>
                </div>
            </div>
        </header>


        <main id="tree" class="bg-white/30 backdrop-blur-sm rounded-[3rem] border border-white shadow-2xl relative overflow-hidden">
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 pointer-events-none">
                <div class="bg-slate-900/80 text-white text-[10px] px-4 py-2 rounded-full backdrop-blur-md uppercase tracking-widest font-bold opacity-50">
                    Scroll & Drag to Explore
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.min.js"></script>

    <script>
        // Data Formatter Logic (Tetap Sesuai Original)
        function formatNode(data) {
            if (!data) return null;

            let htmlClass = 'node';
            let innerHTML = '';

            if (data.type === 'multiple') {
                htmlClass += ' multiple-branch';
                innerHTML = `<div class="font-extrabold text-slate-400 py-2 name-wrapper">✧ ${data.name}</div>`;
            } else if (data.type === 'couple') {
                htmlClass += ' couple';
                const statusStr = data.status === 'divorced' ?
                    '<span class="bg-red-50 text-red-500 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-tighter">Separated</span>' :
                    '<span class="bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-tighter">United</span>';

                let deathInfo = '';
                if (data.death_date1) deathInfo += `<div class="text-[9px] text-slate-400 mt-1">🕯️ ${data.death_date1}</div>`;
                if (data.death_date2) deathInfo += `<div class="text-[9px] text-slate-400">🕯️ ${data.death_date2}</div>`;

                innerHTML = `
                    <div class="collage">
                        <img src="${data.img1}" loading="lazy">
                        <img src="${data.img2}" loading="lazy">
                    </div>
                    <div class="name-wrapper text-sm">${data.name} & ${data.partnerName}</div>
                    <div class="role-wrapper mb-2">${data.role || 'Family'} & ${data.partnerRole || 'Family'}</div>
                    <div class="flex flex-col items-center gap-1">
                        ${statusStr}
                        ${deathInfo}
                    </div>
                `;
            } else {
                htmlClass += ` single ${data.gender}`;
                const deathStr = data.death_date ? `<div class="text-[9px] text-slate-400 mt-1 font-medium">🕯️ Passed: ${data.death_date}</div>` : '';

                innerHTML = `
                    <img src="${data.img}" class="hover:scale-110 transition-transform" loading="lazy">
                    <div class="name-wrapper text-sm">${data.name}</div>
                    <div class="role-wrapper">${data.role || 'Member'}</div>
                    ${deathStr}
                `;
            }

            return {
                HTMLclass: htmlClass,
                innerHTML: innerHTML,
                collapsed: data.collapsed || false,
                children: (data.children) ? data.children.map(c => formatNode(c)).filter(n => n !== null) : []
            };
        }

        // Variable data dari Backend
        const familyRawData = @json($familyData);

        const config = {
            chart: {
                container: "#tree",
                levelSeparation: 100,
                siblingSeparation: 50,
                subTeeSeparation: 60,
                connectors: {
                    type: "step",
                    style: {
                        "stroke": "#cbd5e1",
                        "stroke-width": 3,
                        "stroke-dasharray": "8, 5"
                    }
                },
                node: {
                    collapsable: true
                },
                callback: {
                    onTreeLoaded: function() {
                        const loader = document.getElementById('loader');
                        setTimeout(() => loader.classList.add('fade-out'), 800);
                    }
                }
            },
            nodeStructure: formatNode(familyRawData)
        };

        window.onload = () => new Treant(config);

        // Interactive Click Effect
        document.addEventListener('click', (e) => {
            const node = e.target.closest('.node');
            if (node) {
                document.querySelectorAll('.node').forEach(n => n.classList.remove('selected'));
                node.classList.add('selected');

                // Haptic feedback simulation for mobile
                if (window.navigator.vibrate) window.navigator.vibrate(5);
            }
        });
    </script>
</body>

</html>