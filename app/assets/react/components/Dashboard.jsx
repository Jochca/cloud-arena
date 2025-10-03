import React, { useState, useEffect } from 'react';

export default function Dashboard() {
    const [dashboardData, setDashboardData] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState('');

    useEffect(() => {
        fetchDashboardData();
    }, []);

    const fetchDashboardData = async () => {
        try {
            const token = localStorage.getItem('authToken');
            if (!token) {
                setError('Not authenticated');
                setIsLoading(false);
                return;
            }

            const response = await fetch('/api/dashboard', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            });

            if (response.ok) {
                const data = await response.json();
                setDashboardData(data);
                setError('');
            } else {
                setError('Failed to load dashboard data');
            }
        } catch (err) {
            console.error(err);
            setError('Network error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleTaskAction = async (taskId, status) => {
        // TODO: Implement task status update API call
        console.log(`Task ${taskId} action for status ${status}`);
    };

    if (isLoading) {
        return <div>Loading dashboard...</div>;
    }

    if (error) {
        return <div className="error">Error: {error}</div>;
    }

    if (!dashboardData) {
        return <div>No data available</div>;
    }

    const { balances, tasks, round_count } = dashboardData;

    return (
        <div className="dashboard">
            <h1>It's {round_count} week of the game!</h1>

            <div className="balances">
                <div className="balance-item">
                    <h3>Your balance is: {balances.current_player}</h3>
                </div>
                <div className="balance-item">
                    <h3>The {balances.other_player_name} balance is: {balances.other_player}</h3>
                </div>
            </div>

            <div className="tasks-section">
                <h2>Tasks</h2>

                <div className="task-category">
                    <h3>Your tasks</h3>
                    <div className="task-list">
                        {tasks.your_tasks.map(task => (
                            <div key={task.id} className="task-item">
                                <div className="task-info">
                                    <h4>{task.name}</h4>
                                    <p>{task.description}</p>
                                    <span className="task-value">Value: {task.value}</span>
                                    <span className={`task-status status-${task.status}`}>
                                        Status: {task.status.replace('_', ' ')}
                                    </span>
                                </div>
                                {task.status !== 'completed' && (
                                    <button
                                        className={`task-button ${task.status}`}
                                        onClick={() => handleTaskAction(task.id, task.status)}
                                    >
                                        {task.button_text}
                                    </button>
                                )}
                            </div>
                        ))}
                        {tasks.your_tasks.length === 0 && <p>No tasks assigned to you.</p>}
                    </div>
                </div>

                <div className="task-category">
                    <h3>Free tasks</h3>
                    <div className="task-list">
                        {tasks.free_tasks.map(task => (
                            <div key={task.id} className="task-item">
                                <div className="task-info">
                                    <h4>{task.name}</h4>
                                    <p>{task.description}</p>
                                    <span className="task-value">Value: {task.value}</span>
                                    <span className={`task-status status-${task.status}`}>
                                        Status: {task.status.replace('_', ' ')}
                                    </span>
                                </div>
                                <button
                                    className={`task-button ${task.status}`}
                                    onClick={() => handleTaskAction(task.id, task.status)}
                                >
                                    {task.button_text}
                                </button>
                            </div>
                        ))}
                        {tasks.free_tasks.length === 0 && <p>No free tasks available.</p>}
                    </div>
                </div>

                <div className="task-category">
                    <h3>{balances.other_player_name}'s tasks</h3>
                    <div className="task-list">
                        {tasks.other_player_tasks.map(task => (
                            <div key={task.id} className="task-item">
                                <div className="task-info">
                                    <h4>{task.name}</h4>
                                    <p>{task.description}</p>
                                    <span className="task-value">Value: {task.value}</span>
                                    <span className={`task-status status-${task.status}`}>
                                        Status: {task.status.replace('_', ' ')}
                                    </span>
                                </div>
                                <span className="task-assigned">Assigned to {balances.other_player_name}</span>
                            </div>
                        ))}
                        {tasks.other_player_tasks.length === 0 && <p>No tasks assigned to {balances.other_player_name}.</p>}
                    </div>
                </div>
            </div>
        </div>
    );
}
