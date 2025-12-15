/**
 * Load Laravel + Axios bootstrap (no JSX here!)
 */
import './bootstrap.js';

/**
 * Import React and ReactDOM
 */
import React from 'react';
import ReactDOM from 'react-dom/client';

/**
 * Import the main React component
 */
import LiquidacionEditView from './components/LiquidacionEditView.jsx';

/**
 * Mount it to the DOM if the target element exists
 */
const element = document.getElementById('liquidacion-view');

if (element) {
    const props = JSON.parse(element.dataset.props);
    const volverUrl = element.dataset.volverUrl; // 🔥
    const csrfToken = element.dataset.csrfToken;

    const root = ReactDOM.createRoot(element);
    root.render(<LiquidacionEditView {...props} volverUrl={volverUrl} csrfToken={csrfToken} />);
}
