<script setup>
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InvoiceTable from '@/Components/InvoiceTable.vue'
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue'

const props = defineProps({
    invoices: Object,
    filters: Object,
    sort: String,
    direction: String
})

const search = ref(props.filters.search || '')
const fromDate = ref(props.filters.from_date || '')
const toDate = ref(props.filters.to_date || '')
const sort = ref(props.sort || 'invoice_date')
const direction = ref(props.direction || 'desc')

function applyFilters() {
    router.get(route('billing.index'), {
        search: search.value,
        from_date: fromDate.value,
        to_date: toDate.value,
        sort: sort.value,
        direction: direction.value,
    }, {
        preserveState: true,
        replace: true
    })
}

function sortBy(column) {
    if (sort.value === column) {
        direction.value = direction.value === 'asc' ? 'desc' : 'asc'
    } else {
        sort.value = column
        direction.value = 'asc'
    }
    applyFilters()
}

function resetFilters() {
  search.value = ''
  sort.value = 'invoice_date'
  direction.value = 'desc'

  router.get(route('billing.index'), {}, {
    preserveState: false,
    replace: true
  })
}
</script>


<template>
    <Head title="Billing" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Billing
            </h2>
        </template>

        <div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <!-- Filter Form -->
            <div class="mb-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end">

                <!-- Search -->
                <div class="flex flex-col space-y-1">
                    <label for="search" class="text-sm font-medium text-gray-700">Search</label>
                    <input
                    id="search"
                    v-model="search"
                    type="text"
                    placeholder="Invoice number or GST Number"
                    class="border rounded px-3 py-2 text-sm w-full"
                    />
                </div>

                <!-- From Date -->
                <div class="flex flex-col space-y-1">
                    <label for="fromDate" class="text-sm font-medium text-gray-700">From Date</label>
                    <input
                    id="fromDate"
                    v-model="fromDate"
                    type="date"
                    class="border rounded px-3 py-2 text-sm w-full"
                    />
                </div>

                <!-- To Date -->
                <div class="flex flex-col space-y-1">
                    <label for="toDate" class="text-sm font-medium text-gray-700">To Date</label>
                    <input
                    id="toDate"
                    v-model="toDate"
                    type="date"
                    class="border rounded px-3 py-2 text-sm w-full"
                    />
                </div>

                <!-- Apply Button -->
                <div class="flex flex-col space-y-1">
                    <label class="text-sm font-medium text-transparent">Apply</label>
                    <button
                    @click="applyFilters"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm w-full"
                    >
                    Apply
                    </button>
                </div>

                <!-- Reset Button -->
                <div class="flex flex-col space-y-1">
                    <label class="text-sm font-medium text-transparent">Reset</label>
                    <button
                    @click="resetFilters"
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm w-full"
                    >
                    Reset Filters
                    </button>
                </div>

            </div>


            <!-- Table -->
            <div class="bg-white rounded shadow-sm p-4">
                <InvoiceTable
                    :invoices="invoices.data"
                    :sort="sort"
                    :direction="direction"
                    @sort="sortBy"
                />

                <!-- Pagination -->
                <div class="mt-4 flex justify-end gap-2">
                    <Pagination :links="invoices.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
