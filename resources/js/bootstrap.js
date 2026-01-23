import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';


const csrfMeta = document.head.querySelector('meta[name="csrf-token"]');
if (csrfMeta) {
	window.axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.content;
}

try {
	const reverbKeyMeta = document.head.querySelector('meta[name="reverb-key"]');
	if (reverbKeyMeta && reverbKeyMeta.content) {
		const reverbHost = document.head.querySelector('meta[name="reverb-host"]')?.content || '127.0.0.1';
		const reverbPort = document.head.querySelector('meta[name="reverb-port"]')?.content || '8080';
		const reverbScheme = document.head.querySelector('meta[name="reverb-scheme"]')?.content || 'http';

		import('pusher-js').then(({ default: Pusher }) => {
			return import('laravel-echo').then(({ default: Echo }) => {
				window.Pusher = Pusher;
				window.Echo = new Echo({
					broadcaster: 'pusher',
					key: reverbKeyMeta.content,
					cluster: undefined,
					wsHost: reverbHost,
					wsPort: Number(reverbPort),
					wssPort: Number(reverbPort),
					forceTLS: reverbScheme === 'https',
					enabledTransports: ['ws', 'wss'],
					auth: {
						headers: {
							'X-CSRF-TOKEN': csrfMeta?.content || ''
						}
					}
				});
			});
		}).catch(e => console.warn('Echo/Reverb not initialized:', e));
	} else {
		const pusherKeyMeta = document.head.querySelector('meta[name="pusher-key"]');
		if (pusherKeyMeta && pusherKeyMeta.content) {
			import('pusher-js').then(({ default: Pusher }) => {
				return import('laravel-echo').then(({ default: Echo }) => {
					window.Pusher = Pusher;
					window.Echo = new Echo({
						broadcaster: 'pusher',
						key: pusherKeyMeta.content,
						cluster: document.head.querySelector('meta[name="pusher-cluster"]')?.content || undefined,
						forceTLS: true,
						enabledTransports: ['ws', 'wss'],
						auth: {
							headers: {
								'X-CSRF-TOKEN': csrfMeta?.content || ''
							}
						}
					});
				});
			}).catch(e => console.warn('Echo/Pusher not initialized:', e));
		}
	}
} catch (e) {
	console.warn('Echo init skipped:', e);
}



import './echo';
