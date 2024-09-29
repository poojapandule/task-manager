<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Task Details</h2>

        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success mt-3" style="display:none;"></div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title" id="taskTitle"></h5>
            </div>
            <div class="card-body">
                <h6 class="card-subtitle mb-2 text-muted">Description:</h6>
                <p id="taskDescription">No Description</p>
                <h6 class="card-subtitle mb-2 text-muted">Status:</h6>
                <p id="taskStatus"></p>
                <a href="{{ url('/tasks') }}" class="btn btn-primary">Back to Tasks</a>
                <button class="btn btn-warning" id="editButton">Edit Task</button>
                <button class="btn btn-danger" id="deleteButton">Delete Task</button>
            </div>
        </div>
    </div>

    <script>
        const taskId = new URLSearchParams(window.location.search).get('id');
        const token = localStorage.getItem('token');

        // Fetch the task details
        async function fetchTask() {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/tasks/${taskId}`, {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch task details');
                }

                const task = await response.json();
                document.getElementById('taskTitle').innerText = task.title;
                document.getElementById('taskDescription').innerText = task.description || 'No Description';
                document.getElementById('taskStatus').innerText = task.status;
            } catch (error) {
                console.error('Error fetching task:', error);
                document.getElementById('successMessage').innerText = 'Error loading task details.';
                document.getElementById('successMessage').style.display = 'block';
            }
        }

        // Delete task
        async function deleteTask() {
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    document.getElementById('successMessage').innerText = "Task deleted successfully!";
                    document.getElementById('successMessage').style.display = 'block';
                    setTimeout(() => {
                        window.location.href = '{{ url("/tasks") }}';
                    }, 2000);
                }
            } catch (error) {
                console.error('Error deleting task:', error);
                document.getElementById('successMessage').innerText = 'Error deleting task.';
                document.getElementById('successMessage').style.display = 'block';
            }
        }

        // Redirect to edit task
        document.getElementById('editButton').onclick = function() {
            window.location.href = `http://127.0.0.1:8000/tasks/edit?id=${taskId}`;
        };

        // Attach delete function to the delete button
        document.getElementById('deleteButton').onclick = deleteTask;

        // Call fetchTask when page loads
        document.addEventListener('DOMContentLoaded', fetchTask);
    </script>
</body>
</html>
