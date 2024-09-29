<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center my-5">Edit Task</h2>
<!-- Success Message -->
<div id="successMessage" class="alert alert-success mt-3" style="display:none;">
            Task updated successfully!
        </div>

        <form id="taskForm">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" id="taskId">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description"></textarea>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status">
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Task</button>
        </form>

        
    </div>

    <script>
        // Fetch task data for editing
        async function fetchTask(taskId) {
            const token = localStorage.getItem('token');
            const response = await fetch(`http://127.0.0.1:8000/api/tasks/${taskId}`, {
                method: 'GET',
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.ok) {
                const task = await response.json();
                document.getElementById('taskId').value = task.id;
                document.getElementById('title').value = task.title;
                document.getElementById('description').value = task.description;
                document.getElementById('status').value = task.status;
            } else {
                console.error('Error fetching task:', response.statusText);
            }
        }

        // Handle task form submission for updating
        document.getElementById('taskForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const token = localStorage.getItem('token');
            const id = document.getElementById('taskId').value;
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const status = document.getElementById('status').value;

            const response = await fetch(`http://127.0.0.1:8000/api/tasks/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify({ title, description, status })
            });

            if (response.ok) {
                const successMessage = document.getElementById('successMessage');
                successMessage.style.display = 'block';
                setTimeout(() => {
                    window.location.href = 'http://127.0.0.1:8000/tasks'; // Redirect to tasks view
                }, 3000);
            } else {
                console.error('Error updating task:', response.statusText);
            }
        });

        // Call fetchTask with the task ID passed via URL
        const urlParams = new URLSearchParams(window.location.search);
        const taskId = urlParams.get('id'); // e.g., ?id=1
        fetchTask(taskId);
    </script>
</body>
</html>
