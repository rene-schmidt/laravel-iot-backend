<!doctype html>
<html lang="de">
<!-- 
    Root HTML document.
    Language is set to German ("de") for accessibility and SEO.
-->
<head>
    <!-- Character encoding set to UTF-8 to support special characters -->
    <meta charset="utf-8">

    <!-- Responsive viewport configuration for mobile and desktop devices -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Page title shown in the browser tab -->
    <title>NestJS · Projects · rscoding.dev</title>

    <!-- Main compiled CSS asset loaded via Laravel's asset helper -->
    <link rel="stylesheet" href="{{ asset('rscoding/css/app.css') }}">

    <!-- Favicon for the website -->
    <link rel="icon" href="{{ asset('rscoding/img/favicon.jpg') }}" type="image/jpeg">
</head>

<body class="rs-body">
<!-- 
    Background element used for global styling effects 
    (e.g. gradients, patterns, animations)
-->
<div class="rs-bg"></div>

<header class="rs-header">
    <!-- Main navigation bar -->
    <nav class="rs-nav">
        <!-- Brand / logo linking back to the home route -->
        <a class="rs-brand" href="{{ route('home') }}">
            <!-- Decorative dot element as part of the brand -->
            <span class="rs-dot"></span>
            <!-- Brand name with accent styling -->
            rscoding<span class="rs-accent">.dev</span>
        </a>

        <!-- Navigation links section -->
        <div class="rs-navlinks">
            <!-- Active link to the projects overview -->
            <a class="rs-active" href="{{ route('projects') }}">Projects</a>

            <!-- Contact button using a mailto link -->
            <a class="rs-btn" href="mailto:rene-nicky.schmidt@mail.de">Contact</a>
        </div>
    </nav>
</header>

<main class="rs-container">
    <!-- Page header section with title and description -->
    <section class="rs-pagehead">
        <!-- Project title -->
        <h1 class="rs-h2">Laravel</h1>

        <!-- Short project description -->
        <p class="rs-sub">
            A Laravel-based IoT demo showcasing real-time sensor data, hardware integration,
            and live communication via REST APIs and Socket.IO.
        </p>

        <!-- Action buttons for navigation -->
        <div class="rs-actions">
            <!-- Link to open the Laravel IoT demo -->
            <a class="rs-btn-primary" href="{{ route('projects.laravel.demo') }}">Open demo</a>

            <!-- Link back to the projects overview -->
            <a class="rs-btn-ghost" href="{{ route('projects') }}">Back to projects</a>
        </div>
    </section>

    <!-- Card section containing repository links -->
    <section class="rs-card">
        <!-- Section heading -->
        <h2 class="rs-h3">Repositories</h2>

        <!-- List of related GitHub repositories -->
        <ul class="rs-repolist">
            <li>
                <!-- Visual bullet indicator -->
                <span class="rs-repo-bullet"></span>
                <!-- Monospaced repository name with link -->
                <span class="rs-mono">
                    <a href="https://github.com/rene-schmidt/laravel-iot-backend" class="rs-link-underline">
                        laravel-iot-backend
                    </a>
                </span>
            </li>
            <li>
                <span class="rs-repo-bullet"></span>
                <span class="rs-mono">
                    <a href="https://github.com/rene-schmidt/embedded-iot-controller" class="rs-link-underline">
                        embedded-iot-controller
                    </a>
                </span>
            </li>
        </ul>
    </section>

    <!-- Split layout for feature explanations -->
    <section class="rs-split">
        <!-- REST API explanation card -->
        <div class="rs-card">
            <h2>REST API</h2>
            <p>
                Laravel provides a REST API that exposes live telemetry data,
                which is periodically polled by the frontend.
            </p>
        </div>

        <!-- Real-time communication explanation card -->
        <div class="rs-card">
            <h2>Realtime Communication</h2>
            <p>
                Socket.IO is used for real-time communication, enabling live CDC output
                and bidirectional command handling without REST overhead.
            </p>
        </div>

        <!-- IoT gateway explanation card -->
        <div class="rs-card">
            <h2>IoT Gateway</h2>
            <p>
                A Raspberry Pi acts as a gateway between embedded devices and Laravel,
                forwarding data via UDP and USB CDC.
            </p>
        </div>

        <!-- Hardware integration explanation card -->
        <div class="rs-card">
            <h2>Hardware Integration</h2>
            <p>
                Sensor data is collected from ESP32 devices via I2C and CAN,
                processed by an STM32, and forwarded to the backend.
            </p>
        </div>

        <!-- Deployment strategy explanation card -->
        <div class="rs-card">
            <h2>Deployment</h2>
            <p>
                The project is designed for containerized deployment,
                including services for Laravel, Socket.IO, and gateway components.
            </p>
        </div>
    </section>

    <!-- Bottom navigation link back to projects -->
    <div class="rs-backline">
        <a class="rs-link" href="{{ route('projects') }}">← Back to projects</a>
    </div>
</main>

<!-- Main JavaScript bundle loaded with defer to avoid blocking rendering -->
<script src="{{ asset('rscoding/js/app.js') }}" defer></script>
</body>
</html>
