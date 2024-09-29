<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2 class="text-center my-5">Your Tasks</h2>

        <!-- Success Message -->
        <div id="successMessage" class="alert alert-success mt-3" style="display:none;">
            Task added successfully!
        </div>

        <h3 class="mt-5">Add a New Task</h3>

        <form id="taskForm">
            <input type="hidden" name="_token" value="">
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
            <button type="submit" class="btn btn-primary">Add Task</button>
        </form>

        <!-- Task List -->
        <h3 class="mt-5">Task List</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tasksList"></tbody>
        </table>
    </div>

    <script>
        // Fetch and display tasks on page load
        async function fetchTasks() {
            const token = localStorage.getItem('token');
            try {
                const response = await fetch('http://127.0.0.1:8000/api/taskslist', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (!response.ok) {
                    throw new Error('Failed to fetch tasks');
                }

                const tasks = await response.json();
                let tasksHTML = '';
                tasks.forEach(task => {
                    tasksHTML += `<tr>
                        <td>${task.title}</td>
                        <td>${task.description || 'No Description'}</td>
                        <td>${task.status}</td>
                        <td>
                            <button class="btn btn-info btn-sm" onclick="viewTask(${task.id})">View</button>
                            <button class="btn btn-warning btn-sm" onclick="window.location.href='http://127.0.0.1:8000/tasks/edit?id=${task.id}'">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deleteTask(${task.id})">Delete</button>
                        </td>
                    </tr>`;
                });
                document.getElementById('tasksList').innerHTML = tasksHTML;

                // Check if no tasks found
                if (tasks.length === 0) {
                    document.getElementById('tasksList').innerHTML = '<tr><td colspan="4" class="text-center">No tasks found.</td></tr>';
                }
            } catch (error) {
                console.error('Error fetching tasks:', error);
                document.getElementById('tasksList').innerHTML = '<tr><td colspan="4" class="text-center">Error loading tasks.</td></tr>';
            }
        }

        // View task function
        function viewTask(id) {
            window.location.href = `http://127.0.0.1:8000/tasks/view?id=${id}`;
        }

        // Call fetchTasks when page loads
        document.addEventListener('DOMContentLoaded', fetchTasks);

        // Handle task form submission
        document.getElementById('taskForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const token = localStorage.getItem('token');
            const title = document.getElementById('title').value;
            const description = document.getElementById('description').value;
            const status = document.getElementById('status').value;

            try {
                const response = await fetch('http://127.0.0.1:8000/api/tasks', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({ title, description, status })
                });

                if (!response.ok) {
                    throw new Error('Failed to add task');
                }

                // Clear form
                document.getElementById('taskForm').reset();

                // Show success message
                const successMessage = document.getElementById('successMessage');
                successMessage.style.display = 'block';
                setTimeout(() => {
                    successMessage.style.display = 'none';
                }, 3000);

                // Refresh the task list
                fetchTasks();
            } catch (error) {
                console.error('Error adding task:', error);
            }
        });

        // Delete task
        async function deleteTask(id) {
            const token = localStorage.getItem('token');
            try {
                const response = await fetch(`http://127.0.0.1:8000/api/tasks/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    // Show success message
                    const successMessage = document.getElementById('successMessage');
                    successMessage.innerText = "Task deleted successfully!";
                    successMessage.classList.remove("alert-success");
                    successMessage.classList.add("alert-danger");
                    successMessage.style.display = 'block';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 3000);

                    fetchTasks(); // Refresh the task list
                }
            } catch (error) {
                console.error('Error deleting task:', error);
            }
        }
    </script>
</body>
</html>
