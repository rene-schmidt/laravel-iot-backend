<!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8">

    <!-- CDC auth key for the Socket.IO connection (read by the inline script) -->
    <meta name="cdc-key" content="{{ env('CDC_KEY') }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Socket.IO client library (must be loaded before using window.io in the inline script) -->
    <script src="https://cdn.socket.io/4.7.5/socket.io.min.js"></script>

    <title>Laravel IoT Demo · rscoding.dev</title>

    <!-- Main site styles + favicon -->
    <link rel="stylesheet" href="{{ asset('rscoding/css/app.css') }}">
    <link rel="icon" href="{{ asset('rscoding/img/favicon.jpg') }}" type="image/jpeg">

    <!-- Laravel CSRF token (useful for POST/PUT/DELETE requests) -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="rs-body">
<div class="rs-bg"></div>

<header class="rs-header">
    <nav class="rs-nav">
        <!-- Brand / home link -->
        <a class="rs-brand" href="{{ route('home') }}">
            <span class="rs-dot"></span>
            rscoding<span class="rs-accent">.dev</span>
        </a>

        <!-- Top navigation -->
        <div class="rs-navlinks">
            <a class="rs-active" href="{{ route('projects') }}">Projects</a>
            <a class="rs-btn" href="mailto:rene-nicky.schmidt@mail.de">Contact</a>
        </div>
    </nav>
</header>

<main class="rs-container">
    {{-- Page Head --}}
    <section class="rs-pagehead">
        <h1 class="rs-h2">Laravel IoT Demo</h1>
        <p class="rs-sub">
            Live sensor data via Raspberry Pi gateway. Tabs for I2C, CAN (0x101/0x120), and USB CDC.
        </p>

        <div class="rs-actions">
            {{-- Navigation links --}}
            <a class="rs-btn-ghost" href="{{ route('projects.embedded') }}">Back to Embedded</a>
            <a class="rs-btn-ghost" href="{{ route('projects.laravel') }}">Back to Laravel</a>
            <a class="rs-btn-ghost" href="{{ route('projects') }}">Back to projects</a>
        </div>
    </section>

    {{-- Tabs (buttons toggle corresponding panels) --}}
    <section class="rs-card rs-card-tight">
        <div class="rs-tabs" role="tablist" aria-label="Laravel IoT Demo Tabs">
            <button class="rs-tab is-active" type="button" data-tab="i2c" role="tab" aria-selected="true">I2C</button>
            <button class="rs-tab" type="button" data-tab="can101" role="tab" aria-selected="false">CAN 0x101</button>
            <button class="rs-tab" type="button" data-tab="can120" role="tab" aria-selected="false">CAN 0x120</button>
            <button class="rs-tab" type="button" data-tab="usbcdc" role="tab" aria-selected="false">USB CDC</button>
            <button class="rs-tab" type="button" data-tab="info" role="tab" aria-selected="false">Info</button>
        </div>
    </section>

    {{-- Panels (only one is visible/active at a time) --}}
    <section class="rs-panel is-active" data-panel="i2c" role="tabpanel" aria-label="I2C Panel">
        <section class="rs-card">
            <h2 class="rs-h3">I2C – ESP32 Slave (0x28)</h2>
            <p class="rs-sub">Temperatur (analog sensor → ESP32 → I2C → STM32 → UDP → Raspi → Laravel)</p>

            <!-- Live I2C widgets (temperature + metadata + raw payload) -->
            <div class="rs-grid" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-top:12px;">
                <div class="rs-card">
                    <h2 style="margin:0;">Temperature</h2>
                    <p class="rs-mono" id="i2c-temp" style="font-size:28px;margin-top:8px;">— °C</p>
                    <p class="rs-sub" id="i2c-temp-meta" style="margin-top:6px;">last update: —</p>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">Slave Address</h2>
                    <p class="rs-mono" style="font-size:28px;margin-top:8px;">0x28</p>
                    <p class="rs-sub" style="margin-top:6px;">I2C slave on ESP32</p>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">Raw Payload</h2>
                    <pre class="rs-mono" id="i2c-raw" style="white-space:pre-wrap;margin-top:8px;">—</pre>
                </div>
            </div>
        </section>
    </section>

    <section class="rs-panel" data-panel="can101" role="tabpanel" aria-label="CAN 0x101 Panel">
        <section class="rs-card">
            <h2 class="rs-h3">CAN – 0x101 (Health / Heartbeat)</h2>
            <p class="rs-sub">Slave alive check (sequence counter)</p>

            <!-- Live CAN 0x101 widgets (sequence + metadata + raw payload) -->
            <div class="rs-grid" style="display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin-top:12px;">
                <div class="rs-card">
                    <h2 style="margin:0;">Sequence</h2>
                    <p class="rs-mono" id="can101-seq" style="font-size:28px;margin-top:8px;">—</p>
                    <p class="rs-sub" id="can101-meta" style="margin-top:6px;">last update: —</p>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">CAN ID</h2>
                    <p class="rs-mono" style="font-size:28px;margin-top:8px;">0x101</p>
                    <p class="rs-sub" style="margin-top:6px;">heartbeat frame</p>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">Raw Payload</h2>
                    <pre class="rs-mono" id="can101-raw" style="white-space:pre-wrap;margin-top:8px;">—</pre>
                </div>
            </div>
        </section>
    </section>

    <section class="rs-panel" data-panel="can120" role="tabpanel" aria-label="CAN 0x120 Panel">
        <section class="rs-card">
            <h2 class="rs-h3">CAN – 0x120 (Light Sensor)</h2>
            <p class="rs-sub">TSL2591 via ESP32 → CAN → STM32 → Gateway</p>

            <!-- Live CAN 0x120 widgets (lux/full/ir + raw payload) -->
            <div class="rs-grid" style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-top:12px;">
                <div class="rs-card">
                    <h2 style="margin:0;">Lux</h2>
                    <p class="rs-mono" id="can120-lux" style="font-size:28px;margin-top:8px;">—</p>
                    <p class="rs-sub" id="can120-meta" style="margin-top:6px;">last update: —</p>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">Full</h2>
                    <p class="rs-mono" id="can120-full" style="font-size:28px;margin-top:8px;">—</p>
                    <p class="rs-sub" style="margin-top:6px;">raw channel</p>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">IR</h2>
                    <p class="rs-mono" id="can120-ir" style="font-size:28px;margin-top:8px;">—</p>
                    <p class="rs-sub" style="margin-top:6px;">raw channel</p>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">Raw Payload</h2>
                    <pre class="rs-mono" id="can120-raw" style="white-space:pre-wrap;margin-top:8px;">—</pre>
                </div>
            </div>
        </section>
    </section>

    <section class="rs-panel" data-panel="usbcdc" role="tabpanel" aria-label="USB CDC Panel">
        <section class="rs-card">
            <h2 class="rs-h3">USB CDC</h2>
            <p class="rs-sub">
                The Raspberry Pi can read the STM32 via /dev/ttyACM0 (CLI/logs) and display the output in Laravel.
            </p>

            <div class="rs-grid" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:12px;">
                <div class="rs-card">
                    <h2 style="margin:0;">Output</h2>

                    <!-- Latest CDC line(s) -->
                    <pre class="rs-mono" id="cdc-last" style="white-space:pre-wrap;margin-top:8px;">—</pre>
                    <p class="rs-sub" id="cdc-meta" style="margin-top:6px;">last update: —</p>

                    <!-- Socket connection status details -->
                    <div class="rs-card" style="margin-top:12px;">
                        <h2 style="margin:0;">CDC Socket Status</h2>
                        <ul class="rs-repolist" style="margin-top:10px;">
                            <li><span class="rs-repo-bullet"></span><span class="rs-mono" id="cdc-status">status: —</span></li>
                            <li><span class="rs-repo-bullet"></span><span class="rs-mono" id="cdc-room">room: —</span></li>
                            <li><span class="rs-repo-bullet"></span><span class="rs-mono" id="cdc-socketid">socket id: —</span></li>
                        </ul>
                    </div>
                </div>

                <div class="rs-card">
                    <h2 style="margin:0;">Send Command</h2>
                    <p class="rs-sub">Sent to the CDC server via Socket.IO (no Laravel API required).</p>

                    <!-- Command input + send button -->
                    <div style="margin-top:10px;display:flex;gap:8px;">
                        <input
                            id="cdc-cmd"
                            type="text"
                            placeholder="e.g. help"
                            style="flex:1;padding:12px;border-radius:14px;border:1px solid rgba(255,255,255,.12);background:rgba(0,0,0,.2);color:inherit;"
                        >
                        <button class="rs-btn-primary" type="button" id="cdc-send">Send</button>
                    </div>

                    <!-- Documented event names for the Socket.IO channel -->
                    <p class="rs-sub" style="margin-top:8px;">
                        Events: <span class="rs-mono">cdc:command</span> (out) · <span class="rs-mono">cdc:line</span> (in)
                    </p>
                </div>
            </div>
        </section>
    </section>

    <section class="rs-panel" data-panel="info" role="tabpanel" aria-label="Info Panel">
        <section class="rs-card">
            <h2 class="rs-h3">Info</h2>
            <p>
                Data flow: <span class="rs-mono">ESP32 → (CAN/I2C) → STM32 → UDP → Raspberry Pi → Laravel</span>.
                This page polls <span class="rs-mono">/api/telemetry/latest</span> and displays the values live.
            </p>

            <!-- Generic status information filled by JS -->
            <div class="rs-card" style="margin-top:12px;">
                <h2 style="margin:0;">Status</h2>
                <ul class="rs-repolist" style="margin-top:10px;">
                    <li><span class="rs-repo-bullet"></span><span class="rs-mono" id="status-last">last update: —</span></li>
                    <li><span class="rs-repo-bullet"></span><span class="rs-mono" id="status-id">event id: —</span></li>
                    <li><span class="rs-repo-bullet"></span><span class="rs-mono" id="status-source">source: —</span></li>
                    <li><span class="rs-repo-bullet"></span><span class="rs-mono" id="status-device">device: —</span></li>
                </ul>
            </div>
        </section>
    </section>
</main>

<script>
(function () {
	// Run a function when the DOM is ready (covers both already-loaded and not-yet-loaded cases)
	function onReady(fn) {
		if (document.readyState === 'complete' || document.readyState === 'interactive') {
			setTimeout(fn, 0);
		} else {
			document.addEventListener('DOMContentLoaded', fn, { once: true });
		}
	}

	onReady(function () {

		// State for CDC output handling
		let cdcBuffer = [];
		const CDC_MAX_LINES = 12;
		let cdcHelpMode = false;

		// Tab buttons + panel containers
		const tabs = Array.from(document.querySelectorAll('.rs-tab'));
		const panels = Array.from(document.querySelectorAll('.rs-panel'));

		// Activate a tab by name (updates button state + shows the matching panel)
		function activate(tabName) {
			tabs.forEach(t => {
				const active = t.dataset.tab === tabName;
				t.classList.toggle('is-active', active);
				t.setAttribute('aria-selected', active ? 'true' : 'false');
			});

			panels.forEach(p => {
				const active = p.dataset.panel === tabName;
				p.classList.toggle('is-active', active);
				p.style.display = active ? '' : 'none';
			});
		}

		// Determine initial tab (use pre-marked active tab or fall back to the first)
		const defaultTab =
			(tabs.find(t => t.classList.contains('is-active')) || tabs[0])?.dataset?.tab || 'i2c';

		// Ensure only the default panel is visible on load
		panels.forEach(p => p.style.display = (p.dataset.panel === defaultTab) ? '' : 'none');
		tabs.forEach(btn => btn.addEventListener('click', () => activate(btn.dataset.tab)));

		// Minimal DOM helper: safely write text content (fallback to em dash)
		function setText(id, text) {
			const el = document.getElementById(id);
			if (!el) return;
			el.textContent = (text === null || text === undefined || text === '') ? '—' : String(text);
		}

		// Timestamp label for UI metadata
		function nowLabel() {
			return new Date().toLocaleString();
		}

		// Safe JSON pretty-print for "raw payload" boxes
		function safeJsonPretty(obj) {
			try { return JSON.stringify(obj, null, 2); } catch (_) { return '—'; }
		}

		// Extract "seq=<number>" from a CAN 0x101 text payload
		function parseHeartbeatSeq(str) {
			if (!str || typeof str !== 'string') return null;
			const m = str.match(/seq\s*=\s*(\d+)/i);
			return m ? parseInt(m[1], 10) : null;
		}

		// Extract lux/full/ir values from a CAN 0x120 text payload
		function parseLight(str) {
			if (!str || typeof str !== 'string') return null;
			const lux = str.match(/lux\s*=\s*(-?\d+(\.\d+)?)/i);
			const full = str.match(/full\s*=\s*(-?\d+)/i);
			const ir = str.match(/ir\s*=\s*(-?\d+)/i);
			return {
				lux: lux ? Number(lux[1]) : null,
				full: full ? parseInt(full[1], 10) : null,
				ir: ir ? parseInt(ir[1], 10) : null,
			};
		}

		// Apply REST telemetry data to all UI fields (I2C, CAN, and status)
		function applyTelemetry(data) {
			const payload = data?.data ?? data ?? {};

			setText('status-last', `last update: ${nowLabel()}`);
			if (payload?.id !== undefined) setText('status-id', `event id: ${payload.id}`);
			if (payload?.source !== undefined) setText('status-source', `source: ${payload.source}`);
			if (payload?.device !== undefined) setText('status-device', `device: ${payload.device}`);

			if (payload?.i2c !== undefined) {
				setText('i2c-temp', `${payload.i2c} °C`);
				setText('i2c-temp-meta', `last update: ${nowLabel()}`);
				setText('i2c-raw', safeJsonPretty({ i2c: payload.i2c }));
			}

			const seq = parseHeartbeatSeq(payload?.can101);
			setText('can101-seq', seq ?? '—');
			setText('can101-meta', `last update: ${nowLabel()}`);
			setText('can101-raw', safeJsonPretty({ can101: payload?.can101 }));

			const light = parseLight(payload?.can120);
			if (light) {
				setText('can120-lux', light.lux);
				setText('can120-full', light.full);
				setText('can120-ir', light.ir);
			}
			setText('can120-meta', `last update: ${nowLabel()}`);
			setText('can120-raw', safeJsonPretty({ can120: payload?.can120 }));
		}

		// REST polling for latest telemetry (guarded to avoid overlapping requests)
		let isPolling = false;

		async function pollLatest() {
			if (isPolling) return;
			isPolling = true;

			try {
				const res = await fetch("{{ route('api.telemetry.latest') }}", {
					headers: { 'Accept': 'application/json' },
					cache: 'no-store',
				});

				if (res.ok) {
					const json = await res.json();
					applyTelemetry(json);
				}
			} catch (_) {}
			finally {
				isPolling = false;
			}
		}

		// Initial fetch + periodic updates
		pollLatest();
		setInterval(pollLatest, 1200);

		// Socket.IO configuration for CDC streaming
		const CDC_SOCKET_URL = "https://cdc.rscoding.dev";
		const CDC_ROOM = "default";

		// Small helpers to write socket status fields
		function cdcSetStatus(s) { setText('cdc-status', `status: ${s}`); }
		function cdcSetRoom(s)   { setText('cdc-room', `room: ${s}`); }
		function cdcSetSid(s)    { setText('cdc-socketid', `socket id: ${s}`); }

		// Read CDC key from meta tag; also ensure Socket.IO is available
		const CDC_KEY = document.querySelector('meta[name="cdc-key"]')?.getAttribute('content')?.trim();
		if (!CDC_KEY || typeof window.io !== 'function') {
			cdcSetStatus('ERROR');
			return;
		}

		// Connect via WebSocket only (no long-polling upgrade)
		const cdcSocket = window.io(CDC_SOCKET_URL, {
			path: "/socket.io",
			transports: ["websocket"],
			upgrade: false,
			auth: { key: CDC_KEY, room: CDC_ROOM, role: "web" }
		});

		// On connect: show status, room, and socket id
		cdcSocket.on('connect', () => {
			cdcSetStatus('connected');
			cdcSetRoom(CDC_ROOM);
			cdcSetSid(cdcSocket.id || '—');
		});

		// Incoming CDC output lines
		cdcSocket.on('cdc:line', (msg) => {
			let line = typeof msg === 'string' ? msg : (msg?.line || '');
			if (!line) return;

			// If "help" was requested: keep a rolling buffer of lines, otherwise show only the latest line
			if (cdcHelpMode) {
				cdcBuffer.push(line);
				if (cdcBuffer.length > CDC_MAX_LINES) {
					cdcBuffer = cdcBuffer.slice(-CDC_MAX_LINES);
				}
				setText('cdc-last', cdcBuffer.join('\n'));
			} else {
				cdcBuffer = [];
				setText('cdc-last', line);
			}

			setText('cdc-meta', `last update: ${nowLabel()}`);
		});

		// Send a command from the browser to the CDC server (forwarded to the Raspi/STM32 side)
		const btn = document.getElementById('cdc-send');
		if (btn) {
			btn.addEventListener('click', () => {
				const cmdEl = document.getElementById('cdc-cmd');
				const cmd = cmdEl?.value?.trim();
				if (!cmd) return;

				// Enable buffered output for "help" to display multiple lines
				cdcHelpMode = /^help\b/i.test(cmd);
				cdcBuffer = [];

				cdcSocket.emit('cdc:command', { command: cmd, room: CDC_ROOM });
				cmdEl.value = '';
			});
		}
	});
})();
</script>

</body>
</html>
