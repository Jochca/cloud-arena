import { Controller } from '@hotwired/stimulus';
import React from 'react';
import { createRoot } from 'react-dom/client';
import AuthApp from '../react/components/AuthApp';

export default class extends Controller {
    connect() {
        const root = createRoot(this.element);
        root.render(React.createElement(AuthApp));
    }
}
