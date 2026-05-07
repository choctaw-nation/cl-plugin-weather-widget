import { createRoot } from '@wordpress/element';
import domReady from '@wordpress/dom-ready';
import App from './App';

function initApp() {
	const el = document.getElementById( 'cno-weather-widget-api-settings' );
	if ( ! el ) {
		throw new Error( 'App root element not found' );
	}

	const root = createRoot( el );
	root.render( <App nonce={ el.dataset.nonce! } /> );
}
domReady( initApp );
