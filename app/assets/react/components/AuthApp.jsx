import React, { useState, useEffect } from 'react';

export default function AuthApp() {
    const [isAuthenticated, setIsAuthenticated] = useState(false);
    const [key, setKey] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const [error, setError] = useState('');
    const [token, setToken] = useState(null);

    useEffect(() => {
        // Check if user is already authenticated
        const savedToken = localStorage.getItem('authToken');
        if (savedToken) {
            setToken(savedToken);
            setIsAuthenticated(true);
        }
    }, []);

    const handleLogin = async (e) => {
        e.preventDefault();
        setIsLoading(true);
        setError('');

        try {
            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ key: parseInt(key) }),
            });

            const data = await response.json();

            if (response.ok) {
                // Save token to localStorage
                localStorage.setItem('authToken', data.token);
                setToken(data.token);
                setIsAuthenticated(true);
                setError('');
            } else {
                setError(data.error || 'Authentication failed');
            }
        } catch (err) {
            setError('Network error. Please try again.');
        } finally {
            setIsLoading(false);
        }
    };

    const handleLogout = () => {
        localStorage.removeItem('authToken');
        setToken(null);
        setIsAuthenticated(false);
        setKey('');
    };

    if (isAuthenticated) {
        return (
            <div style={{
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                height: '100vh',
                fontFamily: 'Arial, sans-serif',
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
                    <h1>🚀 Hello World!</h1>
                    <p style={{ fontSize: '1.2rem', marginTop: '1rem' }}>
                        Welcome to Cloud Arena
                    </p>
                    <p style={{ fontSize: '0.9rem', marginTop: '1rem', opacity: 0.8 }}>
                        You are successfully authenticated!
                    </p>
                    <button
                        onClick={handleLogout}
                        style={{
                            marginTop: '1rem',
                            padding: '0.5rem 1rem',
                            backgroundColor: 'rgba(255, 255, 255, 0.2)',
                            color: 'white',
                            border: 'none',
                            borderRadius: '5px',
                            cursor: 'pointer',
                            fontSize: '1rem'
                        }}
                    >
                        Logout
                    </button>
                </div>
            </div>
        );
    }

    return (
        <div style={{
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center',
            height: '100vh',
            fontFamily: 'Arial, sans-serif',
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
                boxShadow: '0 8px 32px 0 rgba(31, 38, 135, 0.37)',
                minWidth: '300px'
            }}>
                <h1>🔐 Login</h1>
                <p style={{ fontSize: '1rem', marginBottom: '2rem' }}>
                    Enter your session key to access Cloud Arena
                </p>

                <form onSubmit={handleLogin}>
                    <div style={{ marginBottom: '1rem' }}>
                        <input
                            type="number"
                            value={key}
                            onChange={(e) => setKey(e.target.value)}
                            placeholder="Enter your key"
                            required
                            disabled={isLoading}
                            style={{
                                width: '100%',
                                padding: '0.75rem',
                                fontSize: '1rem',
                                border: 'none',
                                borderRadius: '5px',
                                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                color: '#333',
                                boxSizing: 'border-box'
                            }}
                        />
                    </div>

                    {error && (
                        <div style={{
                            color: '#ff6b6b',
                            marginBottom: '1rem',
                            fontSize: '0.9rem',
                            backgroundColor: 'rgba(255, 107, 107, 0.1)',
                            padding: '0.5rem',
                            borderRadius: '3px'
                        }}>
                            {error}
                        </div>
                    )}

                    <button
                        type="submit"
                        disabled={isLoading || !key}
                        style={{
                            width: '100%',
                            padding: '0.75rem',
                            fontSize: '1rem',
                            backgroundColor: isLoading || !key ? 'rgba(255, 255, 255, 0.3)' : 'rgba(255, 255, 255, 0.2)',
                            color: 'white',
                            border: 'none',
                            borderRadius: '5px',
                            cursor: isLoading || !key ? 'not-allowed' : 'pointer',
                            transition: 'background-color 0.3s'
                        }}
                    >
                        {isLoading ? 'Logging in...' : 'Login'}
                    </button>
                </form>
            </div>
        </div>
    );
}
