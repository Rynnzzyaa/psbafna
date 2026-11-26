/**
 * RUANG CERITA NADA & AGENT HERMES - SCRIPT v3.5
 * Dual-Mode Chat Engine (Local Hermes API + Resilient Cloud/Client Sanctuary Brain)
 * Audio Ambience Synth, Polaroid Gallery & Mobile First Touch Experience
 * Best Friends Edition (Yan & Nada)
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // 1. DOM ELEMENTS
    // -------------------------------------------------------------------------
    const chatForm = document.getElementById('chat-form');
    const chatInput = document.getElementById('chat-input');
    const chatMessages = document.getElementById('chat-messages');
    const typingIndicator = document.getElementById('typing-indicator');
    const btnClearChat = document.getElementById('btn-clear-chat');
    const hermesStatusText = document.getElementById('hermes-status-text');

    const moodPills = document.querySelectorAll('.mood-pill');
    const btnAmbientSound = document.getElementById('btn-ambient-sound');
    
    // Yan Corner Elements
    const polaroidToggle = document.getElementById('polaroid-toggle');
    const yanPhoto = document.getElementById('yan-photo');
    const photoIndex = document.getElementById('photo-index');
    const polaroidQuote = document.getElementById('polaroid-quote');
    const btnVirtualHug = document.getElementById('btn-virtual-hug');

    // Modals
    const btnOpenCapsule = document.getElementById('btn-open-capsule');
    const capsuleModal = document.getElementById('capsule-modal');
    const btnCloseCapsule = document.getElementById('btn-close-capsule');

    const btnOpenSettings = document.getElementById('btn-open-settings');
    const settingsModal = document.getElementById('settings-modal');
    const btnCloseSettings = document.getElementById('btn-close-settings');
    const settingsForm = document.getElementById('settings-form');
    const btnTestConn = document.getElementById('btn-test-conn');
    const testConnectionStatus = document.getElementById('test-connection-status');
    const cfgApiUrl = document.getElementById('cfg-api-url');
    const cfgModel = document.getElementById('cfg-model');

    // -------------------------------------------------------------------------
    // 2. AUDIO AMBIENCE SYNTH (Web Audio API)
    // -------------------------------------------------------------------------
    let audioCtx = null;
    let ambientGain = null;
    let ambientSource = null;
    let isAmbientPlaying = false;

    function initAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    }

    function playTone(type = 'chime') {
        try {
            initAudio();
            const now = audioCtx.currentTime;

            if (type === 'chime' || type === 'msg') {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                osc.type = 'sine';
                osc.frequency.setValueAtTime(587.33, now); // D5
                osc.frequency.exponentialRampToValueAtTime(880, now + 0.15); // A5
                gain.gain.setValueAtTime(0.12, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.35);

                osc.start(now);
                osc.stop(now + 0.35);
            } else if (type === 'boost') {
                [523.25, 659.25, 783.99, 1046.50].forEach((freq, idx) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);

                    const startTime = now + (idx * 0.08);
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, startTime);
                    gain.gain.setValueAtTime(0.16, startTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, startTime + 0.5);

                    osc.start(startTime);
                    osc.stop(startTime + 0.5);
                });
            } else if (type === 'camera') {
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);

                osc.type = 'triangle';
                osc.frequency.setValueAtTime(800, now);
                osc.frequency.exponentialRampToValueAtTime(300, now + 0.08);
                gain.gain.setValueAtTime(0.2, now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.08);

                osc.start(now);
                osc.stop(now + 0.08);
            }
        } catch (e) {
            // Audio context silently disabled
        }
    }

    // Relaxing Rain Sound Synthesizer
    function toggleAmbientSound() {
        initAudio();
        if (isAmbientPlaying) {
            if (ambientGain) {
                ambientGain.gain.linearRampToValueAtTime(0.001, audioCtx.currentTime + 1);
                setTimeout(() => {
                    if (ambientSource) ambientSource.stop();
                    isAmbientPlaying = false;
                    if (btnAmbientSound) btnAmbientSound.classList.remove('active');
                }, 1000);
            }
        } else {
            const bufferSize = audioCtx.sampleRate * 2;
            const buffer = audioCtx.createBuffer(1, bufferSize, audioCtx.sampleRate);
            const data = buffer.getChannelData(0);
            let lastOut = 0.0;
            for (let i = 0; i < bufferSize; i++) {
                const white = Math.random() * 2 - 1;
                data[i] = (lastOut + (0.02 * white)) / 1.02;
                lastOut = data[i];
                data[i] *= 1.8;
            }

            ambientSource = audioCtx.createBufferSource();
            ambientSource.buffer = buffer;
            ambientSource.loop = true;

            const filter = audioCtx.createBiquadFilter();
            filter.type = 'lowpass';
            filter.frequency.value = 850;

            ambientGain = audioCtx.createGain();
            ambientGain.gain.setValueAtTime(0.001, audioCtx.currentTime);
            ambientGain.gain.linearRampToValueAtTime(0.07, audioCtx.currentTime + 1.5);

            ambientSource.connect(filter);
            filter.connect(ambientGain);
            ambientGain.connect(audioCtx.destination);

            ambientSource.start();
            isAmbientPlaying = true;
            if (btnAmbientSound) btnAmbientSound.classList.add('active');
        }
    }

    if (btnAmbientSound) {
        btnAmbientSound.addEventListener('click', toggleAmbientSound);
    }

    // -------------------------------------------------------------------------
    // 3. SMART HERMES AI ENGINE (Client-side Sanctuary Fallback)
    // -------------------------------------------------------------------------
    function generateHermesClientReply(userText) {
        const text = userText.toLowerCase().trim();

        // 1. Kangen ngobrol sama Yan
        if (text.includes('kangen') || text.includes('rindu') || text.includes('yan')) {
            const replies = [
                "Wajar banget Nad kalau kangen serunya ngobrol bareng Yan! Kalian berdua kan temen ngobrol yang asik banget. Kalau lo lagi pengen ngobrol atau telponan, colek aja si Yan lewat tombol WhatsApp di bawah tuh!",
                "Haha kangen ya Nad? Kadang emang seru banget ya ngebahas hal-hal random bareng Yan. Nanti pas Yan lagi senggang, langsung todong aja buat cerita-cerita lagi ☕✨",
                "Kangen vibe seru ngobrol bareng Yan ya? Santai Nad, lo berdua emang bestie yang klop. Kamu bisa langsung chat ke WA Yan lewat tombol di pesan ini ya kalau mau ngajak ngobrol!"
            ];
            return replies[Math.floor(Math.random() * replies.length)];
        }

        // 2. Capek, pusing, mumet, burnout, lelah
        if (text.includes('capek') || text.includes('lelah') || text.includes('pusing') || text.includes('mumet') || text.includes('burnout') || text.includes('pegel') || text.includes('lemes')) {
            const replies = [
                "Hari ini pasti udah ngabisin banyak energi ya, Nad? Istirahat dulu sebentar, tarik napas pelan-pelan. Lo udah ngelakuin yang terbaik hari ini, dan itu udah lebih dari cukup. Jangan dipaksain ya ✨",
                "Duh capek ya Nad? Valid banget kok rasanya pengen rebahan dan matiin notifikasi sejenak. Ambil minum air putih atau teh hangat dulu gih, jangan lupa napas santai 🌿",
                "Pelan-pelan ya Nad... Kalau dunia luar lagi terlalu bising dan bikin mumet, ruangan ini safe space buat lo narik napas. Yan juga selalu ingetin: kesehatan dan ketenangan lo nomor satu!"
            ];
            return replies[Math.floor(Math.random() * replies.length)];
        }

        // 3. Overthinking, cemas, takut, sedih
        if (text.includes('overthinking') || text.includes('cemas') || text.includes('takut') || text.includes('sedih') || text.includes('nangis') || text.includes('bingung') || text.includes('down')) {
            const replies = [
                "Hei Nad, apa pun yang lagi bikin lo overthinking, coba jeda sebentar. Seringkali hal yang kita cemasin di kepala itu jauh lebih nakutin daripada aslinya. Lo orang yang kuat dan hebat, lo pasti bisa lewatin ini ✨",
                "Gua dengerin kok Nad. Nggak apa-apa kalau hari ini rasanya berat dan lo pengen ngeluh. Tumpahin aja di sini sampai lega. Lo nggak harus selalu keliatan kuat di depan semua orang kok.",
                "Inget ya Nad, satu hari yang buruk bukan berarti hidup lo buruk. Lo punya temen-temen baik termasuk Yan yang selalu support lo. Lo hebat udah bisa sampai di titik ini!"
            ];
            return replies[Math.floor(Math.random() * replies.length)];
        }

        // 4. Cerita seru, ketawa, happy, lucu
        if (text.includes('seru') || text.includes('seneng') || text.includes('lucu') || text.includes('ketawa') || text.includes('happy') || text.includes('wkwk') || text.includes('haha')) {
            const replies = [
                "Wah seru banget tuh Nad! Ikut seneng dengernya 🎉 Cerita-cerita positif kayak gini yang bikin hari jadi jauh lebih berwarna. Ceritain lagi dong detailnya!",
                "Haha asik banget! Lo kalau lagi heboh atau seneng emang energinya nular ya Nad. Cerita ini wajib juga nih lo share ke Yan biar dia ikutan ngakak bareng!",
                "Nah gitu dong Nad! Seneng liat lo ketawa dan happy. Keep that bright smile ya! ✨"
            ];
            return replies[Math.floor(Math.random() * replies.length)];
        }

        // 5. Laper, makan, kopi, jajan
        if (text.includes('laper') || text.includes('makan') || text.includes('kopi') || text.includes('jajan') || text.includes('haus')) {
            const replies = [
                "Nah kan! Udah makan belum hari ini Nad? Jangan sampai telat makan ya, nanti maag kambuh. Cari makanan comfort food favorit lo gih biar energinya balik lagi 🍕🧋",
                "Kopi time atau cemilan time nih? Kalau lagi pusing biasanya ngemil manis-manis atau minum kopi santai emang booster terbaik sih Nad. Gaskeun pesan makanan!",
                "Penting banget nih: perut kenyang, hati tenang! Jangan lupa isi energi ya Nad, jangan cuma kerja atau tugas doang yang dipikirin."
            ];
            return replies[Math.floor(Math.random() * replies.length)];
        }

        // 6. Tugas, kerjaan, deadline, kuliah
        if (text.includes('tugas') || text.includes('kerja') || text.includes('deadline') || text.includes('kuliah') || text.includes('bos') || text.includes('dosen')) {
            const replies = [
                "Semangat ya Nad buat tugas dan kerjaannya! Kerjain satu per satu, cicil perlahan. Nggak usah buru-buru, yang penting selesai dengan tenang 💻✨",
                "Duh deadline emang suka bikin gemes ya. Jangan lupa istirahat tiap 30 menit Nad, regangin badan biar leher sama punggung nggak kaku. Lo pasti beres kok!",
                "Yan pernah bilang, lo tuh orangnya teliti dan tanggung jawab banget. Percaya sama kemampuan lo sendiri ya Nad, lo bisa lewatin ini semua!"
            ];
            return replies[Math.floor(Math.random() * replies.length)];
        }

        // 7. Salam / Sapaan
        if (text.includes('halo') || text.includes('hai') || text.includes('pagi') || text.includes('siang') || text.includes('malam') || text.includes('tes') || text.includes('hello')) {
            const replies = [
                "Halo Nada! ✨ Seneng banget kamu mampir ke sini. Gimana harimu sejauh ini? Ada cerita atau uneg-uneg apa nih yang mau lo tumpahin?",
                "Hai Nad! Hermes di sini siap dengerin apa pun yang pengen kamu ceritain. Santai aja ya, ruangan ini bebas buat kamu ngobrol apa aja 🕊️",
                "Halo Nada! Vibe hari ini gimana nih? Lagi chill, lagi sibuk, atau butuh teman ngobrol santai?"
            ];
            return replies[Math.floor(Math.random() * replies.length)];
        }

        // 8. General empathetic friend fallback
        const generalReplies = [
            `Gua dengerin lo dengan seksama kok Nad. Cerita apa pun yang lagi lo rasain, tumpahin aja di sini ya. Lo nggak sendirian, ada Yan dan Hermes yang selalu siap support lo! ✨`,
            `Apapun yang lagi ada di pikiran lo, makasih udah mau cerita ke sini ya Nad. Kadang ngeluarin kata-kata aja udah bisa bikin dada lebih plong. Mau cerita lebih lanjut?`,
            `Gua paham banget Nad. Selalu sediain waktu buat diri lo sendiri ya. Kalau lo butuh ngobrol dua arah langsung bareng Yan, tombol WhatsApp di bawah siap kapan aja kok! ☕`,
            `Semangat ya Nad! Dunia emang suka random, tapi lo selalu punya safe space di sini. Ada hal lain yang pengen lo ceritain lagi?`
        ];
        return generalReplies[Math.floor(Math.random() * generalReplies.length)];
    }

    // -------------------------------------------------------------------------
    // 4. CHAT INTERACTION & SCROLL
    // -------------------------------------------------------------------------
    function scrollToBottom() {
        if (chatMessages) {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
    }

    function escapeHtml(string) {
        const div = document.createElement('div');
        div.innerText = string;
        return div.innerHTML;
    }

    function appendMessage(sender, text, isUser = false, forwardable = false) {
        if (!chatMessages) return;

        const timeNow = new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        const msgDiv = document.createElement('div');
        msgDiv.className = `message-item ${isUser ? 'user-message' : 'bot-message'}`;

        let forwardHtml = '';
        if (isUser || forwardable) {
            const encoded = encodeURIComponent(`Hai Yan! Ini ceritaku barusan di web:\n"${text}"`);
            forwardHtml = `
                <a href="https://wa.me/6285878942325?text=${encoded}" target="_blank" class="msg-forward-btn" title="Kirim cerita ini ke WhatsApp Yan">
                    <i class="fa-brands fa-whatsapp"></i> Cerita ke Yan
                </a>
            `;
        }

        msgDiv.innerHTML = `
            <div class="msg-avatar">
                <i class="fa-solid ${isUser ? 'fa-user' : 'fa-dove'}"></i>
            </div>
            <div class="msg-body">
                <div class="msg-sender">${sender}</div>
                <div class="msg-bubble">${escapeHtml(text).replace(/\n/g, '<br>')}</div>
                ${forwardHtml}
                <div class="msg-time">${timeNow}</div>
            </div>
        `;

        chatMessages.appendChild(msgDiv);
        scrollToBottom();
    }

    async function sendMessage(text) {
        if (!text || text.trim() === '') return;
        const cleanText = text.trim();

        // 1. Render User Message
        appendMessage('Nada', cleanText, true);
        playTone('msg');

        // 2. Show Typing Indicator
        if (typingIndicator) {
            typingIndicator.classList.remove('hidden');
        }
        scrollToBottom();

        const storedApiUrl = localStorage.getItem('hermes_api_url') || '';
        const storedModel = localStorage.getItem('hermes_model') || '';
        const isLocalHost = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1';

        let botReply = '';
        let replySource = 'client_engine';

        // 3. If on localhost, try the PHP endpoint (which connects to local Hermes Agent)
        if (isLocalHost) {
            try {
                const response = await fetch('chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        message: cleanText,
                        api_url: storedApiUrl,
                        model: storedModel
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data && data.success && data.reply) {
                        botReply = data.reply;
                        replySource = data.source || 'local_hermes';
                    }
                }
            } catch (err) {
                console.log('Local PHP fetch failed, using Sanctuary Engine fallback:', err);
            }
        }

        // 4. If on Netlify or if local fetch didn't yield a reply, use Smart Client Engine
        if (!botReply) {
            // Realistic simulated typing delay (700ms - 1500ms)
            const delay = Math.min(1600, Math.max(800, cleanText.length * 25));
            await new Promise(resolve => setTimeout(resolve, delay));
            botReply = generateHermesClientReply(cleanText);
            replySource = 'hermes_sanctuary';
        }

        // 5. Hide typing & display response
        if (typingIndicator) {
            typingIndicator.classList.add('hidden');
        }

        appendMessage('Hermes', botReply, false, true);
        playTone('chime');

        // Update status text
        if (hermesStatusText) {
            if (replySource === 'local_hermes') {
                hermesStatusText.innerHTML = '<span style="color:#34d399;">● Local Hermes Active</span>';
            } else {
                hermesStatusText.innerHTML = '<span style="color:#38bdf8;">● Hermes Online • Siap Menemani</span>';
            }
        }
    }

    // Submit handler
    if (chatForm) {
        chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = chatInput.value;
            chatInput.value = '';
            sendMessage(text);
        });

        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                chatForm.dispatchEvent(new Event('submit'));
            }
        });
    }

    // Mood Trigger Pills
    moodPills.forEach(pill => {
        pill.addEventListener('click', () => {
            const msg = pill.getAttribute('data-msg');
            if (msg) {
                sendMessage(msg);
            }
        });
    });

    // Clear Chat
    if (btnClearChat) {
        btnClearChat.addEventListener('click', () => {
            if (confirm('Bersihkan percakapan ini dan mulai obrolan baru?')) {
                chatMessages.innerHTML = `
                    <div class="message-item bot-message">
                        <div class="msg-avatar"><i class="fa-solid fa-dove"></i></div>
                        <div class="msg-body">
                            <div class="msg-sender">Hermes</div>
                            <div class="msg-bubble">
                                Obrolan sudah di-reset ya Nad. Ada cerita seru atau uneg-uneg apa nih yang mau lo ceritain sekarang? ✨
                            </div>
                            <div class="msg-time">${new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}</div>
                        </div>
                    </div>
                `;
            }
        });
    }

    // -------------------------------------------------------------------------
    // 5. POLAROID YAN PHOTO GALLERY
    // -------------------------------------------------------------------------
    const yanPhotos = [
        {
            src: 'images/yan-1.jpg',
            caption: '"Tetap semangat ya Nad! Jangan lupa istirahat ✨"',
            indexText: '1 / 3 📸'
        },
        {
            src: 'images/yan-2.jpg',
            caption: '"Lagi santai di pantai nih 🌊"',
            indexText: '2 / 3 📸'
        },
        {
            src: 'images/yan-3.jpg',
            caption: '"Teman cerita terbaik: Nada & Yan 🤜🤛"',
            indexText: '3 / 3 📸'
        }
    ];

    let currentPhotoIdx = 0;

    if (polaroidToggle && yanPhoto) {
        polaroidToggle.addEventListener('click', (e) => {
            playTone('camera');
            createHeartParticle(e.clientX || (window.innerWidth / 2), e.clientY || 400);

            yanPhoto.style.opacity = '0';
            yanPhoto.style.transform = 'scale(0.96)';

            setTimeout(() => {
                currentPhotoIdx = (currentPhotoIdx + 1) % yanPhotos.length;
                const next = yanPhotos[currentPhotoIdx];

                yanPhoto.src = next.src;
                if (polaroidQuote) polaroidQuote.textContent = next.caption;
                if (photoIndex) photoIndex.textContent = next.indexText;

                yanPhoto.style.opacity = '1';
                yanPhoto.style.transform = 'scale(1)';
            }, 200);
        });
    }

    // -------------------------------------------------------------------------
    // 6. VIRTUAL ENERGY & SUPPORT BOOSTER
    // -------------------------------------------------------------------------
    if (btnVirtualHug) {
        btnVirtualHug.addEventListener('click', (e) => {
            playTone('boost');

            for (let i = 0; i < 8; i++) {
                setTimeout(() => {
                    const x = (window.innerWidth / 2) + (Math.random() * 260 - 130);
                    const y = (window.innerHeight * 0.6) + (Math.random() * 80 - 40);
                    createHeartParticle(x, y);
                }, i * 70);
            }

            if (typeof confetti === 'function') {
                confetti({
                    particleCount: 45,
                    spread: 65,
                    origin: { y: 0.7 }
                });
            }

            const origContent = btnVirtualHug.innerHTML;
            btnVirtualHug.innerHTML = `
                <i class="fa-solid fa-check"></i>
                <div>
                    <strong>Booster Terkirim! Lo Keren Banget Nad! 🔥✨</strong>
                    <small>Energi semangat langsung terisi penuh</small>
                </div>
            `;
            btnVirtualHug.style.background = 'rgba(52, 211, 153, 0.25)';

            setTimeout(() => {
                btnVirtualHug.innerHTML = origContent;
                btnVirtualHug.style.background = '';
            }, 3000);
        });
    }

    // -------------------------------------------------------------------------
    // 7. MODALS LOGIC
    // -------------------------------------------------------------------------
    // Note from Yan Modal
    if (btnOpenCapsule && capsuleModal) {
        btnOpenCapsule.addEventListener('click', () => {
            playTone('chime');
            capsuleModal.classList.remove('hidden');
        });
    }

    if (btnCloseCapsule && capsuleModal) {
        btnCloseCapsule.addEventListener('click', () => {
            capsuleModal.classList.add('hidden');
        });

        capsuleModal.addEventListener('click', (e) => {
            if (e.target === capsuleModal) capsuleModal.classList.add('hidden');
        });
    }

    // Settings Modal
    if (btnOpenSettings && settingsModal) {
        btnOpenSettings.addEventListener('click', () => {
            settingsModal.classList.remove('hidden');
            const savedUrl = localStorage.getItem('hermes_api_url');
            const savedModel = localStorage.getItem('hermes_model');
            if (savedUrl && cfgApiUrl) cfgApiUrl.value = savedUrl;
            if (savedModel && cfgModel) cfgModel.value = savedModel;

            // Context note in settings
            if (window.location.hostname.includes('netlify.app')) {
                testConnectionStatus.innerHTML = '<span style="color:#38bdf8;"><i class="fa-solid fa-cloud"></i> Mode Cloud Netlify: Hermes Safe Space aktif online untuk Nada.</span>';
            }
        });
    }

    if (btnCloseSettings && settingsModal) {
        btnCloseSettings.addEventListener('click', () => {
            settingsModal.classList.add('hidden');
        });

        settingsModal.addEventListener('click', (e) => {
            if (e.target === settingsModal) settingsModal.classList.add('hidden');
        });
    }

    // Settings Save Form
    if (settingsForm) {
        settingsForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const urlVal = cfgApiUrl ? cfgApiUrl.value.trim() : '';
            const modelVal = cfgModel ? cfgModel.value.trim() : '';

            localStorage.setItem('hermes_api_url', urlVal);
            localStorage.setItem('hermes_model', modelVal);

            testConnectionStatus.innerHTML = '<span style="color:#34d399;"><i class="fa-solid fa-circle-check"></i> Pengaturan berhasil tersimpan!</span>';
            setTimeout(() => {
                settingsModal.classList.add('hidden');
            }, 1000);
        });
    }

    // Test Connection Button in Settings
    if (btnTestConn) {
        btnTestConn.addEventListener('click', async () => {
            testConnectionStatus.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memeriksa koneksi Hermes...';

            const isNetlify = window.location.hostname.includes('netlify.app') || (!window.location.hostname.includes('localhost') && !window.location.hostname.includes('127.0.0.1'));

            if (isNetlify) {
                setTimeout(() => {
                    testConnectionStatus.innerHTML = `
                        <div style="color:#34d399; font-weight:600; margin-bottom:4px;">
                            <i class="fa-solid fa-circle-check"></i> Hermes Safe Space Online Aktif!
                        </div>
                        <div style="color:#bae6fd; font-size:0.78rem;">
                            Mode Web Netlify: Siap mendengarkan dan menemani Nada 24/7 di HP/Laptop tanpa kendala koneksi.
                        </div>
                    `;
                }, 400);
                return;
            }

            const urlVal = cfgApiUrl ? cfgApiUrl.value.trim() : '';
            const modelVal = cfgModel ? cfgModel.value.trim() : '';

            try {
                const res = await fetch('chat.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        message: 'tes koneksi',
                        api_url: urlVal,
                        model: modelVal
                    })
                });

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}`);
                }

                const data = await res.json();
                if (data.source === 'local_hermes') {
                    testConnectionStatus.innerHTML = `<span style="color:#34d399;"><i class="fa-solid fa-circle-check"></i> Berhasil terhubung ke Local Hermes AI (${data.endpoint || 'Port 8642'})!</span>`;
                } else {
                    testConnectionStatus.innerHTML = `<span style="color:#38bdf8;"><i class="fa-solid fa-circle-info"></i> Endpoint lokal belum terhubung. Sistem memakai Hermes Sanctuary Engine (tetap aktif lancar).</span>`;
                }
            } catch (err) {
                testConnectionStatus.innerHTML = `<span style="color:#38bdf8;"><i class="fa-solid fa-shield-heart"></i> Mode Web Sanctuary Engine Aktif (Chat tetap lancar untuk Nada).</span>`;
            }
        });
    }

    // -------------------------------------------------------------------------
    // 8. FLOATING PARTICLES (Stars, Sparkles, Coffee & Leaves)
    // -------------------------------------------------------------------------
    function createHeartParticle(x, y) {
        const heart = document.createElement('div');
        heart.className = 'floating-heart-particle';
        heart.innerHTML = ['✨', '☕', '🌿', '⭐', '🎈', '🕊️'][Math.floor(Math.random() * 6)];
        heart.style.fontSize = `${Math.random() * 0.7 + 1.1}rem`;
        heart.style.left = `${x}px`;
        heart.style.top = `${y}px`;
        document.body.appendChild(heart);

        setTimeout(() => {
            heart.remove();
        }, 4000);
    }

    setInterval(() => {
        const randomX = Math.random() * window.innerWidth;
        const heart = document.createElement('div');
        heart.className = 'floating-heart-particle';
        heart.innerHTML = ['✨', '☕', '🌿', '⭐', '🕊️'][Math.floor(Math.random() * 5)];
        heart.style.left = `${randomX}px`;
        heart.style.bottom = '10px';
        document.body.appendChild(heart);

        setTimeout(() => {
            heart.remove();
        }, 4500);
    }, 3200);
});
