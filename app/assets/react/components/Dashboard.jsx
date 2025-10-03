import React, { useState, useEffect } from 'react';

export default function Dashboard() {
    const [dashboardData, setDashboardData] = useState(null);
    const [isLoading, setIsLoading] = useState(true);
    const [error, setError] = useState('');
    const [updatingTasks, setUpdatingTasks] = useState(new Set());
    const [showResults, setShowResults] = useState(false);
    const [scoringsData, setScoringsData] = useState(null);
    const [loadingResults, setLoadingResults] = useState(false);

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

    const fetchScorings = async () => {
        setLoadingResults(true);
        try {
            const token = localStorage.getItem('authToken');
            if (!token) {
                setError('Not authenticated');
                return;
            }

            const response = await fetch('/api/session/scorings', {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
            });

            if (response.ok) {
                const data = await response.json();
                setScoringsData(data);
                setShowResults(true);
                setError('');
            } else {
                setError('Failed to load results');
            }
        } catch (err) {
            console.error('Error fetching scorings:', err);
            setError('Network error while loading results');
        } finally {
            setLoadingResults(false);
        }
    };

    const getActionForStatus = (status) => {
        switch (status) {
            case 'pending':
                return 'start';
            case 'in_progress':
                return 'finish';
            default:
                return null;
        }
    };

    const getNewStatusForAction = (action) => {
        switch (action) {
            case 'start':
                return 'in_progress';
            case 'finish':
                return 'completed';
            case 'cancel':
                return 'pending';
            default:
                return null;
        }
    };

    const getButtonTextForStatus = (status) => {
        switch (status) {
            case 'pending':
                return 'Start';
            case 'in_progress':
                return 'Complete';
            default:
                return '';
        }
    };

    const handleTaskAction = async (taskId, currentStatus) => {
        const action = getActionForStatus(currentStatus);
        if (!action) {
            console.error('No valid action for status:', currentStatus);
            return;
        }

        // Add task to updating state
        setUpdatingTasks(prev => new Set([...prev, taskId]));

        try {
            const token = localStorage.getItem('authToken');
            if (!token) {
                setError('Not authenticated');
                return;
            }

            const response = await fetch(`/api/task/${taskId}`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ action })
            });

            if (response.ok) {
                const result = await response.json();
                console.log('Task updated successfully:', result.message);

                // Refresh entire dashboard data to get updated balances
                await fetchDashboardData();

                setError('');
            } else {
                const errorData = await response.json();
                setError(errorData.error || 'Failed to update task status');
            }
        } catch (err) {
            console.error('Error updating task:', err);
            setError('Network error while updating task');
        } finally {
            // Remove task from updating state
            setUpdatingTasks(prev => {
                const newSet = new Set(prev);
                newSet.delete(taskId);
                return newSet;
            });
        }
    };

    const closeResults = () => {
        setShowResults(false);
        setScoringsData(null);
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
                                    <span className={`task-type type-${task.type}`}>
                                        Type: {task.type.charAt(0).toUpperCase() + task.type.slice(1)}
                                    </span>
                                    <span className={`task-status status-${task.status}`}>
                                        Status: {task.status.replace('_', ' ')}
                                    </span>
                                </div>
                                {task.status !== 'completed' && (
                                    <button
                                        className={`task-button ${task.status}`}
                                        onClick={() => handleTaskAction(task.id, task.status)}
                                        disabled={updatingTasks.has(task.id)}
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
                                    <span className={`task-type type-${task.type}`}>
                                        Type: {task.type.charAt(0).toUpperCase() + task.type.slice(1)}
                                    </span>
                                    <span className={`task-status status-${task.status}`}>
                                        Status: {task.status.replace('_', ' ')}
                                    </span>
                                </div>
                                <button
                                    className={`task-button ${task.status}`}
                                    onClick={() => handleTaskAction(task.id, task.status)}
                                    disabled={updatingTasks.has(task.id)}
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
                                    <span className={`task-type type-${task.type}`}>
                                        Type: {task.type.charAt(0).toUpperCase() + task.type.slice(1)}
                                    </span>
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

            <div className="results-section">
                <button
                    className="see-results-button"
                    onClick={fetchScorings}
                    disabled={loadingResults}
                >
                    {loadingResults ? 'Loading results...' : 'See results'}
                </button>
            </div>

            {showResults && scoringsData && (
                <div className="modal-overlay" onClick={closeResults}>
                    <div className="modal-content" onClick={(e) => e.stopPropagation()}>
                        <div className="modal-header">
                            <h3>Game Results</h3>
                            <button className="modal-close" onClick={closeResults}>×</button>
                        </div>
                        <div className="modal-body">
                            {scoringsData.count === 0 ? (
                                <p>No game results yet.</p>
                            ) : (
                                <>
                                    <p className="results-summary">
                                        Total completed weeks: {scoringsData.count}
                                    </p>
                                    <div className="scorings-list">
                                        {scoringsData.sessionScorings.map((scoring, index) => (
                                            <div key={scoring.id} className="scoring-item">
                                                <div className="scoring-header">
                                                    <h4>Week {index + 1}</h4>
                                                    <span className="scoring-date">
                                                        {new Date(scoring.dateStart).toLocaleDateString()} - {new Date(scoring.dateEnd).toLocaleDateString()}
                                                    </span>
                                                </div>
                                                <div className="scoring-details">
                                                    <div className="winner-section">
                                                        <span className="winner-label">Winner:</span>
                                                        <span className="winner-name">{scoring.winner.name}</span>
                                                        <span className="winner-score">{scoring.winnerScore} points</span>
                                                    </div>
                                                    <div className="loser-section">
                                                        <span className="loser-label">Runner-up:</span>
                                                        <span className="loser-name">{scoring.looser.name}</span>
                                                        <span className="loser-score">{scoring.looserScore} points</span>
                                                    </div>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </>
                            )}
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
