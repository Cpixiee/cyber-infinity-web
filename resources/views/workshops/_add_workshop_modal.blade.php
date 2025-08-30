<!-- Add Workshop Modal -->
<div id="workshopModal" class="fixed inset-0 z-50 overflow-y-auto hidden transition-all duration-300">
    
    <!-- Background overlay -->
    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 transition-opacity duration-300"></div>

    <!-- Modal panel -->
    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div class="relative bg-gray-800 rounded-lg max-w-lg w-full"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            <div class="px-6 py-4 border-b border-gray-700">
                <h3 class="text-xl font-medium text-white">Create New Workshop</h3>
            </div>

            <form id="workshopForm">
                @csrf
                <div class="p-6 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-300">Title</label>
                        <input type="text" name="title" id="title" 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            required>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                        <textarea name="description" id="description" rows="3" 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            required></textarea>
                    </div>

                    <div>
                        <label for="activity_type" class="block text-sm font-medium text-gray-300">Activity Type</label>
                        <select name="activity_type" id="activity_type" 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="workshop">Workshop</option>
                            <option value="bootcamp">Bootcamp</option>
                            <option value="training">Training</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-300">Start Date</label>
                            <input type="date" name="start_date" id="start_date" 
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                min="{{ date('Y-m-d') }}"
                                onchange="document.getElementById('end_date').min=this.value"
                                required>
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-300">End Date</label>
                            <input type="date" name="end_date" id="end_date" 
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                min="{{ date('Y-m-d') }}"
                                required>
                        </div>
                    </div>

                    <div>
                        <label for="target_participants" class="block text-sm font-medium text-gray-300">Target Participants</label>
                        <input type="number" name="target_participants" id="target_participants" min="1" 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            required>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-300">Location</label>
                        <input type="text" name="location" id="location" 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                            required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_time" class="block text-sm font-medium text-gray-300">Start Time</label>
                            <input type="time" name="start_time" id="start_time" 
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                required>
                        </div>
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-300">Duration (hours)</label>
                            <input type="number" name="duration" id="duration" min="1" step="0.5"
                                class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                required>
                        </div>
                    </div>

                    <div>
                        <label for="requirements" class="block text-sm font-medium text-gray-300">Requirements</label>
                        <textarea name="requirements" id="requirements" rows="2" 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>

                    @if(auth()->check() && auth()->user()->role === 'admin')
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-300">Status</label>
                        <select name="status" id="status" 
                            class="mt-1 block w-full rounded-md border-gray-600 bg-gray-700 text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-gray-700 flex justify-end space-x-3">
                    <div id="loadingIndicator" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <button type="button" id="closeModalBtn"
                        class="px-4 py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none transition duration-150">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none transition duration-150">
                        Create Workshop
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
