import { Controller } from '@hotwired/stimulus';
import React from 'react';
import { createRoot } from 'react-dom/client';
import Dashboard from '../components/Dashboard';

export default class extends Controller {
    connect() {
        const root = createRoot(this.element);
        root.render(<Dashboard />);
    }
}
