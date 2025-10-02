import { Controller } from '@hotwired/stimulus';
import React from 'react';
import { createRoot } from 'react-dom/client';

export default class extends Controller {
    connect() {
        console.log('Hello from React Stimulus controller!');

        // Create React component directly
        const HelloWorld = () => {
            return React.createElement('div', {
                style: {
                    display: 'flex',
                    justifyContent: 'center',
                    alignItems: 'center',
                    height: '100vh',
                    fontFamily: 'Arial, sans-serif',
                    fontSize: '2rem',
                    background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                    color: 'white',
                    margin: 0
                }
            }, React.createElement('div', {
                style: {
                    textAlign: 'center',
                    padding: '2rem',
                    borderRadius: '10px',
                    backgroundColor: 'rgba(255, 255, 255, 0.1)',
                    backdropFilter: 'blur(10px)',
                    boxShadow: '0 8px 32px 0 rgba(31, 38, 135, 0.37)'
                }
            }, [
                React.createElement('h1', { key: 'title' }, '🚀 Hello World from React!'),
                React.createElement('p', {
                    key: 'subtitle',
                    style: { fontSize: '1.2rem', marginTop: '1rem' }
                }, 'Welcome to Cloud Arena')
            ]));
        };

        // Render React component
        const root = createRoot(this.element);
        root.render(React.createElement(HelloWorld));
    }
}
