<!-- Details Modal -->
<div
    id="detailsModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 p-4"
    onclick="if(event.target === this) closeDetailsModal()"
>
    <div class="w-full max-w-2xl rounded-xl bg-white shadow-2xl" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
            <h2 class="text-xl font-bold text-gray-900" id="modalTitle">Details</h2>
            <button
                onclick="closeDetailsModal()"
                class="rounded-lg p-1 text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
            >
                <x-heroicon-o-x-mark class="h-6 w-6" />
            </button>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-4">
            <div id="modalContent" class="space-y-4">
                <!-- Loading State -->
                <div id="loadingState" class="flex items-center justify-center py-8">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-butter-500 border-t-transparent"></div>
                </div>

                <!-- Content will be inserted here -->
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="border-t border-gray-200 px-6 py-4">
            <button
                onclick="closeDetailsModal()"
                class="w-full rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200 sm:w-auto"
            >
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openDetailsModal(url, title = 'Details') {
        const modal = document.getElementById('detailsModal');
        const modalTitle = document.getElementById('modalTitle');
        const modalContent = document.getElementById('modalContent');
        const loadingState = document.getElementById('loadingState');

        // Show modal and loading state
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        modalTitle.textContent = title;
        loadingState.classList.remove('hidden');
        
        // Clear previous content
        const existingContent = modalContent.querySelector('#detailsContent');
        if (existingContent) {
            existingContent.remove();
        }

        // Fetch data
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingState.classList.add('hidden');
            
            // Create content HTML
            const contentHtml = `
                <div id="detailsContent" class="grid gap-4 sm:grid-cols-2">
                    ${Object.entries(data).map(([key, value]) => {
                        const label = key.split('_').map(word => 
                            word.charAt(0).toUpperCase() + word.slice(1)
                        ).join(' ');
                        
                        let displayValue = value;
                        if (key === 'status') {
                            const statusColors = {
                                'approved': 'bg-green-100 text-green-800',
                                'rejected': 'bg-red-100 text-red-800',
                                'pending': 'bg-yellow-100 text-yellow-800'
                            };
                            displayValue = `<span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ${statusColors[value] || 'bg-gray-100 text-gray-800'}">${value.charAt(0).toUpperCase() + value.slice(1)}</span>`;
                        }
                        
                        return `
                            <div>
                                <dt class="text-sm font-medium text-gray-500">${label}</dt>
                                <dd class="mt-1 text-sm text-gray-900">${displayValue}</dd>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
            
            modalContent.insertAdjacentHTML('beforeend', contentHtml);
        })
        .catch(error => {
            loadingState.classList.add('hidden');
            modalContent.innerHTML += `
                <div id="detailsContent" class="rounded-lg bg-red-50 p-4 text-center">
                    <p class="text-sm text-red-800">Failed to load details. Please try again.</p>
                </div>
            `;
            console.error('Error fetching details:', error);
        });
    }

    function closeDetailsModal() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDetailsModal();
        }
    });
</script>
