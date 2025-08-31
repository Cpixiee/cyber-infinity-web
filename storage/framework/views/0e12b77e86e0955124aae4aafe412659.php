<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Kelola Tasks - <?php echo e($challenge->title); ?></title>
    
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite Assets -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Simple Header with Back Button Only -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="<?php echo e(route('admin.challenges.index')); ?>" 
                           class="flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full hover:bg-gray-200 transition-colors mr-4">
                            <i class="fas fa-arrow-left text-gray-600"></i>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Kelola Tasks</h1>
                            <p class="text-sm text-gray-600">Challenge: <?php echo e($challenge->title); ?></p>
                        </div>
                    </div>
                    <button onclick="openAddTaskModal()" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                        <i class="fas fa-plus mr-2"></i>Tambah Task
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Tasks Content -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <!-- Challenge Info -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-lg">
                                        <i class="fas fa-tasks text-blue-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Tasks</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e($tasks->count()); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-green-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <i class="fas fa-check-circle text-green-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Active Tasks</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e($tasks->where('is_active', true)->count()); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-yellow-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-yellow-100 rounded-lg">
                                        <i class="fas fa-star text-yellow-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Total Poin</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e($tasks->sum('points')); ?></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-purple-50 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="p-2 bg-purple-100 rounded-lg">
                                        <i class="fas fa-eye text-purple-600"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-600">Challenge Status</p>
                                        <p class="text-lg font-semibold text-gray-900"><?php echo e(ucfirst($challenge->status)); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks List -->
                    <div class="p-6">
                        <?php if($tasks->count() > 0): ?>
                            <div class="space-y-4">
                                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="border border-gray-200 rounded-lg p-4 <?php echo e(!$task->is_active ? 'opacity-50' : ''); ?>"
                                         data-task-id="<?php echo e($task->id); ?>"
                                         data-title="<?php echo e($task->title); ?>"
                                         data-description="<?php echo e($task->description); ?>"
                                         data-flag="<?php echo e($task->flag); ?>"
                                         data-points="<?php echo e($task->points); ?>"
                                         data-order="<?php echo e($task->order); ?>"
                                         data-is-active="<?php echo e($task->is_active ? '1' : '0'); ?>">
                                        <div class="flex items-start justify-between">
                                            <div class="flex items-start">
                                                <div class="w-8 h-8 <?php echo e($task->is_active ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400'); ?> rounded-full flex items-center justify-center mr-3 mt-1">
                                                    <span class="text-sm font-medium"><?php echo e($task->order); ?></span>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900 mb-2"><?php echo e($task->title); ?></h3>
                                                    <p class="text-gray-700 mb-3"><?php echo e($task->description); ?></p>
                                                    
                                                    <div class="flex items-center gap-4 text-sm text-gray-600">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-flag mr-1"></i>
                                                            <code class="px-2 py-1 bg-gray-100 rounded text-xs"><?php echo e($task->flag); ?></code>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <i class="fas fa-star mr-1 text-yellow-500"></i>
                                                            <span><?php echo e($task->points); ?> poin</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <i class="fas fa-<?php echo e($task->is_active ? 'check-circle text-green-500' : 'times-circle text-red-500'); ?> mr-1"></i>
                                                            <span><?php echo e($task->is_active ? 'Active' : 'Inactive'); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <button data-task-id="<?php echo e($task->id); ?>" onclick="editTask(this.dataset.taskId)" 
                                                        class="px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                                    <i class="fas fa-edit mr-1"></i>Edit
                                                </button>
                                                <form action="<?php echo e(route('admin.challenges.tasks.destroy', $task)); ?>" method="POST" class="inline" 
                                                      onsubmit="return confirm('Yakin ingin menghapus task ini?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" 
                                                            class="px-3 py-1 bg-red-600 text-white text-xs rounded-lg hover:bg-red-700 transition-colors duration-200">
                                                        <i class="fas fa-trash mr-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <!-- Empty State -->
                            <div class="text-center py-12">
                                <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-tasks text-4xl text-gray-400"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada task</h3>
                                <p class="text-gray-600 mb-4">Tambahkan task pertama untuk challenge ini</p>
                                <button onclick="openAddTaskModal()" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                    <i class="fas fa-plus mr-2"></i>Tambah Task
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl shadow-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Tambah Task Baru</h2>
                    <button onclick="closeAddTaskModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="<?php echo e(route('admin.challenges.tasks.store', $challenge)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <!-- Title -->
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Task <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Task 1: Login tanpa password" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Jelaskan apa yang harus dilakukan user..." required></textarea>
                    </div>

                    <!-- Flag and Points -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="flag" class="block text-sm font-medium text-gray-700 mb-2">
                                Flag <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="flag" name="flag" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="CYBER{flag_here}" required>
                        </div>

                        <div>
                            <label for="points" class="block text-sm font-medium text-gray-700 mb-2">
                                Poin <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="points" name="points" min="0" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="50" required>
                        </div>
                    </div>

                    <!-- Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Urutan <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="order" name="order" min="1" value="<?php echo e($tasks->count() + 1); ?>"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               required>
                        <p class="mt-1 text-xs text-gray-500">Task akan dikerjakan berdasarkan urutan ini</p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center justify-end space-x-4">
                        <button type="button" onclick="closeAddTaskModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                            <i class="fas fa-save mr-2"></i>Simpan Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto border-t-4 border-purple-500">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Edit Task</h2>
                            <p class="text-gray-600 text-sm">Update task information</p>
                        </div>
                    </div>
                    <button onclick="closeEditTaskModal()" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-all">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <form action="<?php echo e(route('admin.challenges.tasks.update', 'TASK_ID')); ?>" method="POST" id="editTaskForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" id="edit_task_id" name="task_id" value="">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="edit_title" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading text-blue-500 mr-2"></i>Task Title
                            </label>
                            <input type="text" name="title" id="edit_title" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="edit_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-align-left text-green-500 mr-2"></i>Description
                            </label>
                            <textarea name="description" id="edit_description" rows="4" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"></textarea>
                        </div>

                        <!-- Flag -->
                        <div class="md:col-span-2">
                            <label for="edit_flag" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-flag text-red-500 mr-2"></i>Flag
                            </label>
                            <input type="text" name="flag" id="edit_flag" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all font-mono">
                        </div>

                        <!-- Points -->
                        <div>
                            <label for="edit_points" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-star text-yellow-500 mr-2"></i>Points
                            </label>
                            <input type="number" name="points" id="edit_points" min="0" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        </div>

                        <!-- Order -->
                        <div>
                            <label for="edit_order" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sort-numeric-up text-indigo-500 mr-2"></i>Order
                            </label>
                            <input type="number" name="order" id="edit_order" min="1" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        </div>

                        <!-- Active Status -->
                        <div class="md:col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="is_active" id="edit_is_active" value="1"
                                       class="w-5 h-5 text-purple-600 bg-gray-100 border-gray-300 rounded focus:ring-purple-500 focus:ring-2">
                                <label for="edit_is_active" class="ml-3 text-sm font-semibold text-gray-700">
                                    <i class="fas fa-toggle-on text-green-500 mr-2"></i>Active
                                </label>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 ml-8">Task akan ditampilkan jika dicentang</p>
                        </div>
                    </div>

                    <!-- Hints Management -->
                    <div class="mt-8 pt-6 border-t border-gray-200" x-data="{ 
                        hints: [], 
                        taskId: null, 
                        showHints: false,
                        loadTaskHints() {
                            if (!window.currentEditTaskId) {
                                Swal.fire('Error', 'Task ID tidak ditemukan', 'error');
                                return;
                            }

                            // Show hints section
                            this.showHints = true;

                            // Load existing hints via AJAX
                            fetch(`/admin/challenges/tasks/${window.currentEditTaskId}/hints`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        displayExistingHints(data.hints);
                                    } else {
                                        console.error('Failed to load hints:', data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error loading hints:', error);
                                    // Show empty state if no hints or error
                                    displayExistingHints([]);
                                });
                        }
                    }">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Hints Management</h3>
                                <p class="text-sm text-gray-600">Kelola hints untuk task ini</p>
                            </div>
                            <button type="button" @click="loadTaskHints()" x-show="!showHints"
                                    class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors">
                                <i class="fas fa-lightbulb mr-2"></i>Kelola Hints
                            </button>
                        </div>

                        <div x-show="showHints" x-transition class="space-y-4">
                            <!-- Existing Hints -->
                            <div id="existing-hints" class="space-y-3">
                                <!-- Hints will be loaded here via JavaScript -->
                            </div>

                            <!-- Add New Hint -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-yellow-800 mb-3">Tambah Hint Baru</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Judul Hint</label>
                                        <input type="text" id="new_hint_title" 
                                               class="w-full px-3 py-2 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                               placeholder="Contoh: Cek input sanitization...">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Cost (Points)</label>
                                        <input type="number" id="new_hint_cost" min="1" value="10"
                                               class="w-full px-3 py-2 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Konten Hint</label>
                                        <textarea id="new_hint_content" rows="3"
                                                  class="w-full px-3 py-2 border border-yellow-300 rounded-lg focus:ring-2 focus:ring-yellow-500 text-sm"
                                                  placeholder="Isi hint yang akan membantu user..."></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <button type="button" onclick="addNewHint()" 
                                                class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors text-sm">
                                            <i class="fas fa-plus mr-2"></i>Tambah Hint
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditTaskModal()" 
                                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg font-medium">
                            <i class="fas fa-save mr-2"></i>Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddTaskModal() {
            document.getElementById('addTaskModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAddTaskModal() {
            document.getElementById('addTaskModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function editTask(taskId) {
            // Find task data from the page
            const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
            if (!taskElement) {
                Swal.fire('Error', 'Task tidak ditemukan', 'error');
                return;
            }

            // Get task data from data attributes
            const taskData = {
                id: taskId,
                title: taskElement.dataset.title,
                description: taskElement.dataset.description,
                flag: taskElement.dataset.flag,
                points: taskElement.dataset.points,
                order: taskElement.dataset.order,
                isActive: taskElement.dataset.isActive === '1'
            };

            // Fill edit modal with data
            document.getElementById('edit_task_id').value = taskData.id;
            document.getElementById('edit_title').value = taskData.title;
            document.getElementById('edit_description').value = taskData.description;
            document.getElementById('edit_flag').value = taskData.flag;
            document.getElementById('edit_points').value = taskData.points;
            document.getElementById('edit_order').value = taskData.order;
            document.getElementById('edit_is_active').checked = taskData.isActive;

            // Store task ID globally for hints management
            window.currentEditTaskId = taskData.id;

            // Update form action URL
            const form = document.getElementById('editTaskForm');
            const baseUrl = '<?php echo e(route("admin.challenges.tasks.update", "TASK_ID")); ?>';
            form.action = baseUrl.replace('TASK_ID', taskData.id);

            // Show edit modal
            document.getElementById('editTaskModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        // loadTaskHints now moved to Alpine.js component

        function displayExistingHints(hints) {
            const container = document.getElementById('existing-hints');
            if (!container) return;

            if (hints.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4 text-gray-500 text-sm">
                        <i class="fas fa-lightbulb text-2xl mb-2 block"></i>
                        Belum ada hints untuk task ini
                    </div>
                `;
                return;
            }

            container.innerHTML = hints.map((hint, index) => `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3" data-hint-id="${hint.id}">
                    <div class="flex items-center justify-between mb-2">
                        <h5 class="font-medium text-yellow-800">${hint.title}</h5>
                        <div class="flex items-center space-x-2">
                            <span class="text-xs bg-yellow-200 text-yellow-800 px-2 py-1 rounded">${hint.cost} pts</span>
                            <button type="button" onclick="deleteHint(${hint.id})" 
                                    class="text-red-500 hover:text-red-700 text-xs">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700">${hint.content || 'Tidak ada konten'}</p>
                    ${hint.is_active ? 
                        '<span class="inline-block mt-2 text-xs bg-green-200 text-green-800 px-2 py-1 rounded">Active</span>' : 
                        '<span class="inline-block mt-2 text-xs bg-red-200 text-red-800 px-2 py-1 rounded">Inactive</span>'
                    }
                </div>
            `).join('');
        }

        function addNewHint() {
            const title = document.getElementById('new_hint_title').value.trim();
            const cost = document.getElementById('new_hint_cost').value;
            const content = document.getElementById('new_hint_content').value.trim();

            if (!title || !cost || !content) {
                Swal.fire('Error', 'Semua field hint harus diisi', 'error');
                return;
            }

            if (!window.currentEditTaskId) {
                Swal.fire('Error', 'Task ID tidak ditemukan', 'error');
                return;
            }

            // Show loading
            Swal.fire({
                title: 'Menambah hint...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Send AJAX request
            fetch(`/admin/challenges/tasks/${window.currentEditTaskId}/hints`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    title: title,
                    cost: parseInt(cost),
                    content: content,
                    content_type: 'text',
                    order: 1,
                    is_active: true
                })
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();
                if (data.success) {
                    Swal.fire('Berhasil', 'Hint berhasil ditambahkan', 'success');
                    // Clear form
                    document.getElementById('new_hint_title').value = '';
                    document.getElementById('new_hint_cost').value = '10';
                    document.getElementById('new_hint_content').value = '';
                    // Reload hints
                    const hintsSection = document.querySelector('[x-data*="hints"]');
                    if (hintsSection && hintsSection.__x) {
                        hintsSection.__x.$data.loadTaskHints();
                    }
                } else {
                    Swal.fire('Error', data.message || 'Gagal menambah hint', 'error');
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error adding hint:', error);
                Swal.fire('Error', 'Terjadi kesalahan saat menambah hint', 'error');
            });
        }

        function deleteHint(hintId) {
            Swal.fire({
                title: 'Hapus Hint?',
                text: 'Hint yang dihapus tidak dapat dikembalikan',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Send delete request
                    fetch(`/admin/challenges/hints/${hintId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Berhasil', 'Hint berhasil dihapus', 'success');
                            // Reload hints
                            const hintsSection = document.querySelector('[x-data*="hints"]');
                            if (hintsSection && hintsSection.__x) {
                                hintsSection.__x.$data.loadTaskHints();
                            }
                        } else {
                            Swal.fire('Error', data.message || 'Gagal menghapus hint', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting hint:', error);
                        Swal.fire('Error', 'Terjadi kesalahan saat menghapus hint', 'error');
                    });
                }
            });
        }

        function closeEditTaskModal() {
            document.getElementById('editTaskModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function confirmLogout() {
            Swal.fire({
                title: 'Logout dari Akun?',
                text: 'Anda akan keluar dari dashboard',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Flash messages are handled by separate script tags above

        // Responsive sidebar functions
        function initializeResponsiveSidebar() {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileCloseBtn = document.getElementById('mobile-close-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-overlay');
            
            function isMobile() {
                return window.innerWidth < 1024;
            }
            
            function setupSidebarState() {
                if (isMobile()) {
                    sidebar?.classList.remove('mobile-open');
                    overlay?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                } else {
                    sidebar?.classList.remove('mobile-open');
                    overlay?.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
            
            function openMobileMenu() {
                if (!isMobile()) return;
                sidebar?.classList.add('mobile-open');
                overlay?.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
            
            function closeMobileMenu() {
                if (!isMobile()) return;
                sidebar?.classList.remove('mobile-open');
                overlay?.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
            
            mobileMenuBtn?.addEventListener('click', function(e) {
                if (!isMobile()) return;
                e.preventDefault();
                openMobileMenu();
            });
            
            mobileCloseBtn?.addEventListener('click', function(e) {
                if (!isMobile()) return;
                e.preventDefault();
                closeMobileMenu();
            });
            
            overlay?.addEventListener('click', function() {
                if (!isMobile()) return;
                closeMobileMenu();
            });
            
            document.addEventListener('keydown', function(e) {
                if (!isMobile()) return;
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });
            
            window.addEventListener('resize', setupSidebarState);
            setupSidebarState();
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeResponsiveSidebar);
        } else {
            initializeResponsiveSidebar();
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAddTaskModal();
                closeEditTaskModal();
            }
        });

        // Close modal on outside click
        document.getElementById('addTaskModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddTaskModal();
            }
        });

        document.getElementById('editTaskModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditTaskModal();
            }
        });
    </script>
        </div> <!-- End tasks content div -->
        </main>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <?php if(session('success')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo e(session("success")); ?>',
                showConfirmButton: false,
                timer: 3000
            });
        });
    </script>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo e(session("error")); ?>',
                confirmButtonText: 'OK'
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
<?php /**PATH C:\laragon\www\cyber-infinity-web\resources\views/admin/challenges/tasks.blade.php ENDPATH**/ ?>