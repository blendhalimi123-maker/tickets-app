import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// If bootstrap already initialized Echo (preferred), skip creating another instance.
if (!window.Echo) {
    const hostRaw = import.meta.env.VITE_REVERB_HOST ?? '127.0.0.1';
    const host = String(hostRaw).replace(/"/g, '');
    const port = Number(import.meta.env.VITE_REVERB_PORT ?? 8080);
    const scheme = (import.meta.env.VITE_REVERB_SCHEME ?? 'http');

    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: host,
        wsPort: port,
        wssPort: port,
        forceTLS: scheme === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            },
            withCredentials: true
        }
    });
}
