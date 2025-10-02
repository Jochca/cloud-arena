import React from 'react';

export default function HelloWorld() {
    return (
        <div style={{
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            height: '100vh',
            fontFamily: 'Arial, sans-serif',
            fontSize: '2rem',
            background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            color: 'white',
            margin: 0
        }}>
            <div style={{
                textAlign: 'center',
                padding: '2rem',
                borderRadius: '10px',
                backgroundColor: 'rgba(255, 255, 255, 0.1)',
                backdropFilter: 'blur(10px)',
                boxShadow: '0 8px 32px 0 rgba(31, 38, 135, 0.37)'
            }}>
                <h1>🚀 Hello World from React!</h1>
                <p style={{ fontSize: '1.2rem', marginTop: '1rem' }}>
                    Welcome to Cloud Arena
                </p>
            </div>
        </div>
    );
}
