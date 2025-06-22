<script setup>

import { ref } from 'vue'
import axios from 'axios'
import InvoiceDetailModal from '@/Components/InvoiceDetailModal.vue'

const selectedInvoice = ref(null)
const showModal = ref(false)
const loading = ref(false)

const openInvoiceDetails = async (id) => {
  loading.value = true
  try {
    const res = await axios.get(route('invoices.show', id))
    selectedInvoice.value = res.data
    showModal.value = true
  } catch (error) {
    console.error('Failed to load invoice', error)
  } finally {
    loading.value = false
  }
}

defineProps({
    invoices: Array,
    sort: String,
    direction: String
})
defineEmits(['sort'])
</script>

<template>
    <div v-if="loading" class="fixed inset-0 bg-black/30 flex items-center justify-center z-50">
        <div class="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin"></div>
    </div>
    <table class="min-w-full divide-y divide-gray-200 border text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th @click="$emit('sort', 'invoice_number')" class="px-4 py-2 cursor-pointer text-left">
                Invoice #
                    <span class="ml-1 text-xs">{{sort === 'invoice_number' ? direction === 'asc' ? '↑' : '↓' : '⇅'}}
                    </span>
                </th>

                <th @click="$emit('sort', 'invoice_date')" class="px-4 py-2 cursor-pointer text-left">
                Invoice Date
                    <span class="ml-1 text-xs"> {{ sort === 'invoice_date' ? direction === 'asc' ? '↑' : '↓' : '⇅'}}
                    </span>
                </th>

                <th @click="$emit('sort', 'due_date')" class="px-4 py-2 cursor-pointer text-left">
                Due Date
                    <span class="ml-1 text-xs">{{ sort === 'due_date' ? direction === 'asc' ? '↑' : '↓' : '⇅'}}
                    </span>
                </th>

                <th @click="$emit('sort', 'total_amount')" class="px-4 py-2 cursor-pointer text-right">
                Total
                    <span class="ml-1 text-xs"> {{sort === 'total_amount' ? direction === 'asc' ? '↑' : '↓': '⇅'}}
                    </span>
                </th>

                <th class="px-4 py-2 text-left">GST</th>
                <th class="px-4 py-2 text-left">Action</th>
            </tr>
            </thead>

        <tbody class="divide-y divide-gray-200">
            <tr v-for="invoice in invoices" :key="invoice.id" class="hover:bg-gray-50 cursor-pointer" @click="openInvoiceDetails(invoice.id)">
                <td class="px-4 py-2">{{ invoice.invoice_number }}</td>
                <td class="px-4 py-2">{{ invoice.invoice_date }}</td>
                <td class="px-4 py-2">{{ invoice.due_date }}</td>
                <td class="px-4 py-2 text-right">₹{{ invoice.total_amount }}</td>
                <td class="px-4 py-2">{{ invoice.gst_number }}</td>
                <td>
                    <a :href="route('invoices.download', invoice.id)" class="px-2 py-1 bg-green-600 text-white rounded text-sm" target="_blank" @click.stop>
                        Download
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

    <InvoiceDetailModal
        v-if="showModal"
        :invoice="selectedInvoice"
        @close="showModal = false"
    />
</template>
