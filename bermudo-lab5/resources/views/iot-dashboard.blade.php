<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="Ma.Regine Bermudo">
    <meta name="description" content="Smart Greenhouse - House Slytherin">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Greenhouse | House Slytherin</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Cinzel+Decorative:wght@700&display=swap');

        :root {
            --slytherin-green: #1a5c38;
            --slytherin-bright: #2ecc71;
            --slytherin-dark: #0a1f14;
            --slytherin-silver: #c0c0c0;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: 
                radial-gradient(ellipse at 20% 10%, rgba(26, 92, 56, 0.35) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 90%, rgba(10, 60, 35, 0.3) 0%, transparent 45%),
                radial-gradient(ellipse at 50% 50%, rgba(5, 20, 12, 0.4) 0%, transparent 70%),
                linear-gradient(180deg, #020805 0%, #0a1510 35%, #050a07 70%, #030504 100%);
            background-attachment: fixed;
            font-family: 'Cinzel', serif;
            color: #d4e8d4;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 30px 20px 60px;
            position: relative;
            overflow-x: hidden;
        }

        /* Dungeon stone texture */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background-image: 
                radial-gradient(rgba(80, 160, 100, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.15) 1px, transparent 1px),
                linear-gradient(rgba(0,0,0,0.12) 1px, transparent 1px);
            background-size: 24px 24px, 80px 80px, 80px 80px;
            pointer-events: none;
            z-index: 0;
            opacity: 0.7;
        }

        /* Ambient fog layers */
        .fog {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .fog span {
            position: absolute;
            bottom: -20%;
            width: 200%;
            height: 60%;
            background: radial-gradient(ellipse, rgba(20, 80, 50, 0.12) 0%, transparent 70%);
            animation: drift 28s infinite linear;
            opacity: 0.6;
        }

        .fog span:nth-child(2) {
            animation-duration: 36s;
            animation-delay: -8s;
            opacity: 0.4;
            transform: scaleX(-1);
        }

        @keyframes drift {
            0% { transform: translateX(-30%) translateY(0); }
            100% { transform: translateX(10%) translateY(-8%); }
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: var(--slytherin-bright);
            border-radius: 50%;
            box-shadow: 0 0 6px 2px rgba(46, 204, 113, 0.6);
            animation: float-up linear infinite;
            opacity: 0;
        }

        @keyframes float-up {
            0% {
                transform: translateY(100vh) scale(0.4);
                opacity: 0;
            }
            10% { opacity: 0.8; }
            90% { opacity: 0.5; }
            100% {
                transform: translateY(-10vh) scale(1.2);
                opacity: 0;
            }
        }

        /* Header */
        header {
            position: relative;
            z-index: 5;
            text-align: center;
            margin-bottom: 8px;
        }

        h1 {
            font-family: 'Cinzel Decorative', serif;
            font-size: clamp(2.2rem, 6vw, 2.9rem);
            letter-spacing: 6px;
            color: #2ecc71;
            text-shadow: 
                0 0 12px rgba(46, 204, 113, 0.7),
                0 0 30px rgba(46, 204, 113, 0.35),
                3px 3px 0 #000,
                -1px -1px 0 rgba(0,0,0,0.8);
            margin-bottom: 6px;
        }

        h1::after {
            content: "";
            display: block;
            width: 160px;
            height: 2px;
            margin: 10px auto 0;
            background: linear-gradient(90deg, transparent, var(--slytherin-bright), transparent);
            box-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
        }

        .subtitle {
            font-size: 0.85rem;
            letter-spacing: 6px;
            color: #8fb89a;
            text-transform: uppercase;
            margin-top: 10px;
            text-shadow: 0 0 8px rgba(0,0,0,0.8);
        }

        /* ========== CAULDRON + SMOKE ========== */
        .cauldron-stage {
            position: relative;
            width: 140px;
            height: 130px;
            margin: 18px auto 28px;
            z-index: 4;
        }

        /* Cauldron body */
        .cauldron {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 110px;
            height: 70px;
            background: 
                radial-gradient(ellipse at 50% 30%, #2a3530 0%, #121816 40%, #0a0e0c 100%);
            border-radius: 50% 50% 45% 45% / 60% 60% 40% 40%;
            box-shadow: 
                inset 0 8px 12px rgba(0,0,0,0.6),
                inset 0 -4px 8px rgba(46, 204, 113, 0.15),
                0 6px 16px rgba(0,0,0,0.7),
                0 0 20px rgba(46, 204, 113, 0.12);
            z-index: 3;
        }

        /* Cauldron rim */
        .cauldron::before {
            content: "";
            position: absolute;
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 118px;
            height: 18px;
            background: linear-gradient(180deg, #3a4a40, #1a2520 60%, #0e1410);
            border-radius: 50%;
            box-shadow: 
                0 2px 4px rgba(0,0,0,0.5),
                inset 0 1px 0 rgba(255,255,255,0.08);
        }

        /* Glowing potion liquid */
        .cauldron::after {
            content: "";
            position: absolute;
            top: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 78px;
            height: 22px;
            background: radial-gradient(ellipse, rgba(46, 204, 113, 0.55) 0%, rgba(20, 100, 50, 0.3) 70%, transparent);
            border-radius: 50%;
            box-shadow: 0 0 18px 6px rgba(46, 204, 113, 0.35);
            animation: liquid-pulse 2.8s ease-in-out infinite alternate;
        }

        @keyframes liquid-pulse {
            0% { opacity: 0.7; box-shadow: 0 0 14px 4px rgba(46, 204, 113, 0.3); }
            100% { opacity: 1; box-shadow: 0 0 24px 8px rgba(46, 204, 113, 0.5); }
        }

        /* Legs */
        .leg {
            position: absolute;
            bottom: -6px;
            width: 10px;
            height: 18px;
            background: linear-gradient(180deg, #1a2220, #0a0e0c);
            border-radius: 0 0 4px 4px;
            z-index: 2;
        }

        .leg.left { left: 18px; transform: rotate(-12deg); }
        .leg.right { right: 18px; transform: rotate(12deg); }
        .leg.center { left: 50%; transform: translateX(-50%); width: 8px; height: 14px; }

        /* Smoke plumes */
        .smoke {
            position: absolute;
            bottom: 68px;
            left: 50%;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: rgba(160, 220, 180, 0.35);
            filter: blur(8px);
            pointer-events: none;
            animation: smoke-rise 4.5s ease-out infinite;
            opacity: 0;
        }

        .smoke:nth-child(1) { animation-delay: 0s; margin-left: -18px; }
        .smoke:nth-child(2) { animation-delay: 0.7s; margin-left: 2px; width: 26px; height: 26px; }
        .smoke:nth-child(3) { animation-delay: 1.4s; margin-left: -8px; }
        .smoke:nth-child(4) { animation-delay: 2.1s; margin-left: 12px; width: 18px; height: 18px; }
        .smoke:nth-child(5) { animation-delay: 2.8s; margin-left: -22px; width: 24px; height: 24px; }
        .smoke:nth-child(6) { animation-delay: 3.5s; margin-left: 6px; }

        @keyframes smoke-rise {
            0% {
                transform: translateY(0) scale(0.6) translateX(0);
                opacity: 0;
            }
            15% {
                opacity: 0.55;
            }
            50% {
                opacity: 0.35;
                transform: translateY(-55px) scale(1.4) translateX(8px);
            }
            100% {
                transform: translateY(-110px) scale(2.2) translateX(-12px);
                opacity: 0;
            }
        }

        /* Extra soft green haze above cauldron */
        .haze {
            position: absolute;
            bottom: 55px;
            left: 50%;
            transform: translateX(-50%);
            width: 90px;
            height: 40px;
            background: radial-gradient(ellipse, rgba(46, 204, 113, 0.18) 0%, transparent 70%);
            filter: blur(6px);
            animation: haze-pulse 3.2s ease-in-out infinite alternate;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes haze-pulse {
            0% { opacity: 0.5; transform: translateX(-50%) scale(0.9); }
            100% { opacity: 0.85; transform: translateX(-50%) scale(1.15); }
        }

        /* Table container */
        .table-container {
            width: 100%;
            max-width: 920px;
            background: 
                linear-gradient(160deg, rgba(10, 22, 16, 0.97) 0%, rgba(6, 16, 12, 0.98) 100%);
            border: 3px solid #2ecc71;
            border-radius: 6px;
            padding: 32px 26px 26px;
            box-shadow: 
                0 0 0 1px #1a4a30,
                8px 8px 0 #000,
                0 0 55px rgba(46, 204, 113, 0.18),
                inset 0 0 40px rgba(0, 0, 0, 0.4);
            position: relative;
            z-index: 5;
            overflow-x: auto;
        }

        .table-container::before {
            content: "";
            position: absolute;
            inset: 6px;
            border: 1px solid rgba(46, 204, 113, 0.22);
            border-radius: 3px;
            pointer-events: none;
        }

        /* Serpent accents */
        .serpent {
            position: absolute;
            font-size: 1.45rem;
            opacity: 0.8;
            filter: drop-shadow(0 0 6px rgba(46, 204, 113, 0.5));
            z-index: 2;
        }

        .serpent.top-left {
            top: 14px;
            left: 16px;
        }

        .serpent.bottom-right {
            bottom: 14px;
            right: 16px;
            transform: scaleX(-1);
        }

        .badge {
            position: absolute;
            top: -15px;
            right: 24px;
            background: #081210;
            color: #2ecc71;
            font-size: 0.68rem;
            letter-spacing: 2.5px;
            padding: 5px 16px;
            border: 1px solid #2ecc71;
            border-radius: 2px;
            text-transform: uppercase;
            z-index: 6;
            box-shadow: 0 0 12px rgba(46, 204, 113, 0.35);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            position: relative;
            z-index: 2;
        }

        th {
            font-family: 'Cinzel', serif;
            font-size: 0.9rem;
            letter-spacing: 2.5px;
            color: #2ecc71;
            text-transform: uppercase;
            padding: 14px 12px;
            border-bottom: 2px solid #2ecc71;
            text-align: left;
            background: rgba(46, 204, 113, 0.08);
            text-shadow: 0 0 8px rgba(46, 204, 113, 0.3);
        }

        td {
            padding: 13px 12px;
            border-bottom: 1px solid #1a4a30;
            color: #d4e8d4;
            font-size: 0.95rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: rgba(46, 204, 113, 0.07);
        }

        a.edit-link {
            color: #16ff77;
            text-decoration: none;
            letter-spacing: 1px;
            transition: all 0.2s ease;
            text-shadow: 0 0 6px rgba(22, 255, 119, 0.4);
        }

        a.edit-link:hover {
            color: #a8ffc0;
            text-shadow: 0 0 12px rgba(22, 255, 119, 0.7);
        }

        /* Empty state */
        .empty {
            text-align: center;
            padding: 36px 20px;
            color: #6a9a7a;
            font-size: 1rem;
            letter-spacing: 1.5px;
        }

        /* Subtle corner runes */
        .rune {
            position: absolute;
            font-size: 0.65rem;
            color: rgba(46, 204, 113, 0.3);
            letter-spacing: 1px;
            pointer-events: none;
            z-index: 1;
        }

        .rune.tl { top: 28px; left: 28px; }
        .rune.tr { top: 28px; right: 28px; }
        .rune.bl { bottom: 28px; left: 28px; }
        .rune.br { bottom: 28px; right: 28px; }

        /* ========== DASHBOARD EXTRAS ========== */
        .topnav {
            position: relative;
            z-index: 6;
            width: 100%;
            max-width: 920px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 12px 22px;
            margin-bottom: 22px;
            background: rgba(6, 16, 12, 0.92);
            border: 1px solid rgba(46, 204, 113, 0.45);
            border-radius: 4px;
            box-shadow: 0 0 22px rgba(46, 204, 113, 0.14), 4px 4px 0 #000;
        }

        .topnav .links,
        .topnav .right {
            display: flex;
            align-items: center;
            gap: 24px;
            flex-wrap: wrap;
        }

        .topnav a,
        .topnav button {
            font-family: 'Cinzel', serif;
            font-size: 0.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #8fb89a;
            text-decoration: none;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 4px 0;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .topnav a:hover,
        .topnav button:hover {
            color: #2ecc71;
            text-shadow: 0 0 10px rgba(46, 204, 113, 0.6);
        }

        .topnav a.active {
            color: #2ecc71;
            border-bottom-color: #2ecc71;
            text-shadow: 0 0 10px rgba(46, 204, 113, 0.5);
        }

        .topnav .user {
            font-size: 0.8rem;
            letter-spacing: 2px;
            color: var(--slytherin-silver);
            text-transform: uppercase;
        }

        .topnav .logout {
            color: #ff5c5c;
        }

        .topnav .logout:hover {
            color: #ffa8a8;
            text-shadow: 0 0 10px rgba(255, 92, 92, 0.6);
        }

        .welcome {
            position: relative;
            z-index: 2;
            text-align: center;
            margin: 6px 0 28px;
        }

        .welcome h2 {
            font-family: 'Cinzel Decorative', serif;
            font-size: 1.5rem;
            letter-spacing: 3px;
            color: #2ecc71;
            text-shadow: 0 0 12px rgba(46, 204, 113, 0.6);
        }

        .welcome p {
            margin-top: 10px;
            color: #8fb89a;
            letter-spacing: 1.5px;
            font-size: 0.9rem;
        }

        .stats {
            position: relative;
            z-index: 2;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 32px;
        }

        .stat {
            text-align: center;
            padding: 24px 14px;
            background: rgba(46, 204, 113, 0.06);
            border: 1px solid #1a5c38;
            border-radius: 4px;
            box-shadow: inset 0 0 22px rgba(0, 0, 0, 0.45);
            transition: all 0.25s ease;
        }

        .stat:hover {
            border-color: #2ecc71;
            box-shadow: inset 0 0 22px rgba(0, 0, 0, 0.45), 0 0 18px rgba(46, 204, 113, 0.25);
        }

        .stat .num {
            font-family: 'Cinzel Decorative', serif;
            font-size: 1.9rem;
            color: #2ecc71;
            text-shadow: 0 0 12px rgba(46, 204, 113, 0.55);
        }

        .stat .label {
            margin-top: 10px;
            font-size: 0.72rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #8fb89a;
        }

        .section-title {
            position: relative;
            z-index: 2;
            font-size: 0.95rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #2ecc71;
            text-shadow: 0 0 8px rgba(46, 204, 113, 0.4);
        }

        .btn-row {
            position: relative;
            z-index: 2;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 14px;
            margin-top: 28px;
        }

        .btn {
            font-family: 'Cinzel', serif;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
            padding: 10px 24px;
            color: #2ecc71;
            border: 1px solid #2ecc71;
            border-radius: 3px;
            background: transparent;
            transition: all 0.2s ease;
        }

        .btn:hover {
            background: #2ecc71;
            color: #020805;
            box-shadow: 0 0 18px rgba(46, 204, 113, 0.5);
        }

        @media (max-width: 640px) {
            .table-container {
                padding: 28px 16px 20px;
            }
            th, td {
                padding: 10px 8px;
                font-size: 0.85rem;
            }
            .cauldron-stage {
                transform: scale(0.9);
            }
            .topnav {
                justify-content: center;
            }
        }

        /* ========== GREENHOUSE EXTRAS ========== */
        .stat .num.small { font-size: 1.15rem; line-height: 1.5; }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.8rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            padding: 6px 16px;
            border-radius: 20px;
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        .status::before {
            content: "";
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #2ecc71;
            box-shadow: 0 0 8px #2ecc71;
        }

        .status.offline {
            border-color: #ff5c5c;
            color: #ff5c5c;
        }

        .status.offline::before {
            background: #ff5c5c;
            box-shadow: 0 0 8px #ff5c5c;
        }

        .status-row {
            position: relative;
            z-index: 2;
            text-align: center;
            margin-bottom: 26px;
        }
    </style>
</head>
<body>
    <!-- Ambient fog -->
    <div class="fog">
        <span></span>
        <span></span>
    </div>

    <!-- Floating particles -->
    <div class="particles" id="particles"></div>

    <header>
        <h1>GREENHOUSE</h1>
        <div class="subtitle">ESP32 • Laravel • MySQL</div>
    </header>

    @php
        // Device counts as online if Laravel received a reading in the last 2 minutes
        $isOnline = $latest && $latest->created_at->gt(now()->subMinutes(2));
    @endphp

    <div class="table-container" style="margin-top: 26px;">
        <div class="badge">Smart Greenhouse</div>

        <span class="serpent top-left">🐍</span>
        <span class="serpent bottom-right">🐍</span>

        <span class="rune tl">ᚦ</span>
        <span class="rune tr">ᚨ</span>
        <span class="rune bl">ᚱ</span>
        <span class="rune br">ᚹ</span>

        <div class="welcome">
            <h2>Live Readings</h2>
            <p>Latest data sent by the ESP32 to the Laravel API.</p>
        </div>

        <div class="status-row">
            @if($isOnline)
                <span class="status">Device online</span>
            @else
                <span class="status offline">Device offline</span>
            @endif
        </div>

        <div class="stats">
            <div class="stat">
                <div class="num">{{ $latest ? number_format($latest->temperature, 2) . ' °C' : '--' }}</div>
                <div class="label">Temperature</div>
            </div>
            <div class="stat">
                <div class="num">{{ $latest ? number_format($latest->humidity, 2) . ' %' : '--' }}</div>
                <div class="label">Humidity</div>
            </div>
            <div class="stat">
                <div class="num small">{{ $latest?->device_id ?? 'No device' }}</div>
                <div class="label">Device ID</div>
            </div>
            <div class="stat">
                <div class="num small">{{ $latest ? $latest->created_at->format('M d, Y h:i:s A') : 'No readings yet' }}</div>
                <div class="label">Last Received</div>
            </div>
        </div>

        <div class="section-title">Sensor Reading History</div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Device</th>
                    <th>Temp</th>
                    <th>Humidity</th>
                    <th>Recorded At</th>
                    <th>Received At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($readings as $reading)
                <tr>
                    <td>{{ $reading->id }}</td>
                    <td>{{ $reading->device_id }}</td>
                    <td>{{ number_format($reading->temperature, 2) }} °C</td>
                    <td>{{ number_format($reading->humidity, 2) }} %</td>
                    <td>{{ $reading->recorded_at ? $reading->recorded_at->format('M d, Y h:i:s A') : 'Not provided' }}</td>
                    <td>{{ $reading->created_at->format('M d, Y h:i:s A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty">No sensor readings available yet...</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="btn-row">
            <button type="button" class="btn" onclick="location.reload()">Refresh Readings</button>
        </div>
    </div>

    <script>
        // Floating ambient particles
        const particlesContainer = document.getElementById('particles');
        for (let i = 0; i < 26; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + '%';
            p.style.animationDuration = (9 + Math.random() * 13) + 's';
            p.style.animationDelay = (Math.random() * 11) + 's';
            p.style.width = p.style.height = (2 + Math.random() * 3) + 'px';
            particlesContainer.appendChild(p);
        }
    </script>
</body>
</html>