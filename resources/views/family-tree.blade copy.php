<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Family Tree Hasan Basri</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            overflow-x: hidden;
        }

        /* Loading Overlay */
        #loader {
            position: fixed;
            inset: 0;
            z-index: 999;
            background: rgba(15, 23, 42, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease;
        }

        #loader.fade-out {
            opacity: 0;
            pointer-events: none;
        }

        /* Container Tree */
        #tree {
            width: 100%;
            height: 85vh;
            margin-top: 20px;
        }

        /* Styling Garis (Connectors) */
        .Treant>svg path {
            stroke: #94a3b8 !important;
            stroke-width: 4px !important;
        }

        /* Base Node */
        .node {
            background: #fff;
            border-radius: 15px;
            padding: 12px;
            width: 190px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .node:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .node.selected {
            border-color: #6366f1;
        }

        /* Node Elements Fix (Pointer Event None agar Klik Tembus ke Switch Treant) */
        .node img,
        .node div,
        .node span {
            pointer-events: none;
        }

        /* Image Styling */
        .node img {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 0 auto 8px;
        }

        .collage {
            display: flex;
            justify-content: center;
            margin-bottom: 8px;
        }

        .collage img:nth-child(2) {
            margin-left: -15px;
        }

        /* Border Types */
        .male {
            border-top: 5px solid #3b82f6;
        }

        .female {
            border-top: 5px solid #ec4899;
        }

        .couple {
            border-top: 5px solid #10b981;
        }

        .multiple-branch {
            border-top: 5px dashed #64748b;
            background: #f8fafc;
        }

        /* Treant Collapse Switch (Invisible but Clickable) */
        .Treant .collapse-switch {
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            cursor: pointer;
            opacity: 0;
            z-index: 10;
        }

        /* Collapse Indicator */
        .node::after {
            content: "●";
            font-size: 10px;
            color: #cbd5e1;
            display: block;
            margin-top: 5px;
        }

        .node.collapsed::after {
            content: "⊕ Lihat Keturunan";
            font-size: 11px;
            color: #6366f1;
            font-weight: bold;
            background: #f5f3ff;
            border-radius: 5px;
            padding: 2px 0;
        }

        /* Menangani nama panjang agar turun ke bawah (maks 2 baris) */
        .name-wrapper {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* Maksimal 2 baris */
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.25;
            min-height: 2.5em;
            /* Menjaga tinggi node tetap konsisten */
            word-break: break-word;
        }

        /* Memastikan area role/pekerjaan tidak meluber */
        .role-wrapper {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>

<body class="p-6">

    <div id="loader">
        <div class="w-16 h-16 border-4 border-slate-700 border-t-indigo-500 rounded-full animate-spin"></div>
        <h2 class="text-white font-bold mt-4 tracking-widest">MEMUAT SILSILAH...</h2>
    </div>

    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
            <h1 class="text-3xl font-extrabold text-slate-800">🌳 Silsilah Keluarga <span class="text-indigo-600">Hasan Basri</span></h1>
            <div class="flex gap-2 text-xs">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full border border-blue-200">♂ Laki-laki</span>
                <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full border border-pink-200">♀ Perempuan</span>
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full border border-green-200">💍 Pasangan</span>
            </div>
        </div>

        <div id="tree" class="bg-white/50 backdrop-blur rounded-[2rem] border-2 border-white shadow-xl"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.3.0/raphael.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/treant-js/1.0/Treant.min.js"></script>

    <script>
        function formatNode(data) {
            if (!data) return null;

            let htmlClass = 'node';
            let innerHTML = '';

            if (data.type === 'multiple') {
                htmlClass += ' multiple-branch';
                innerHTML = `<div class="font-bold text-slate-500 py-2 name-wrapper">🌳 ${data.name}</div>`;
            } else if (data.type === 'couple') {
                htmlClass += ' couple';
                const statusStr = data.status === 'divorced' ? '<span class="text-red-500">💔 Cerai</span>' : '<span class="text-green-600">💍 Menikah</span>';

                let deathInfo = '';
                if (data.death_date1) deathInfo += `<div class="text-[10px] text-gray-400">🕯️ ${data.death_date1}</div>`;
                if (data.death_date2) deathInfo += `<div class="text-[10px] text-gray-400">🕯️ ${data.death_date2}</div>`;

                innerHTML = `
            <div class="collage">
                <img src="${data.img1}" title="${data.name} (${data.role || ''})">
                <img src="${data.img2}" title="${data.partnerName} (${data.partnerRole || ''})">
            </div>
            <div class="text-sm font-bold name-wrapper" title="${data.name} & ${data.partnerName}">
                ${data.name} & ${data.partnerName}
            </div>
            
            <div class="text-[10px] text-indigo-500 font-medium italic role-wrapper">
                ${data.role || '-'} & ${data.partnerRole || '-'}
            </div>
            
            <div class="text-[10px] mb-1">${statusStr}</div>
            ${deathInfo}
        `;
            } else {
                htmlClass += ` single ${data.gender}`;
                const deathStr = data.death_date ? `<div class="text-[10px] text-gray-500">🕯️ ${data.death_date}</div>` : '';

                innerHTML = `
            <img src="${data.img}">
            <div class="text-sm font-bold name-wrapper" title="${data.name}">${data.name}</div>
            <div class="text-[10px] text-slate-400 role-wrapper">${data.role || ''}</div>
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
        const familyRawData = @json($familyData);

        const config = {
            chart: {
                container: "#tree",
                levelSeparation: 70,
                siblingSeparation: 40,
                connectors: {
                    type: "step",
                    style: {
                        "stroke": "#cbd5e1",
                        "stroke-width": 4
                    }
                },
                node: {
                    collapsable: true
                },
                callback: {
                    onTreeLoaded: function() {
                        const loader = document.getElementById('loader');
                        setTimeout(() => loader.classList.add('fade-out'), 600);
                    }
                }
            },
            nodeStructure: formatNode(familyRawData)
        };

        window.onload = () => new Treant(config);

        // Click selection effect
        document.addEventListener('click', (e) => {
            const node = e.target.closest('.node');
            if (node) {
                document.querySelectorAll('.node').forEach(n => n.classList.remove('selected'));
                node.classList.add('selected');
            }
        });
    </script>
</body>

</html>