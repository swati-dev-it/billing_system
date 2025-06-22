<script setup>
const props = defineProps({
  invoice: Object
})
const emit = defineEmits(['close'])
</script>

<template>
  <!-- Overlay -->
  <div class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

    <!-- Modal Box -->
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-6 relative animate-fade-in">
      
      <!-- Header -->
      <div class="flex justify-between items-center border-b pb-3 mb-4">
        <h2 class="text-xl font-bold text-gray-800">
          Invoice #{{ invoice.invoice_number }}
        </h2>
        <button
          @click="emit('close')"
          class="text-gray-400 hover:text-gray-800 text-xl transition"
          title="Close"
        >
          &times;
        </button>
      </div>

      <!-- Details -->
      <div class="space-y-2 text-sm text-gray-700">
        <p><span class="font-medium text-gray-600">User:</span> {{ invoice.user.name }}</p>
        <p><span class="font-medium text-gray-600">Invoice Date:</span> {{ invoice.invoice_date }}</p>
        <p><span class="font-medium text-gray-600">Due Date:</span> {{ invoice.due_date }}</p>
        <p><span class="font-medium text-gray-600">GST Number:</span> {{ invoice.gst_number }}</p>
      </div>

      <!-- Items Table -->
      <h3 class="mt-6 mb-2 text-lg font-semibold text-gray-800">Invoice Items</h3>
      <div class="overflow-x-auto">
        <table class="w-full border text-sm rounded">
          <thead class="bg-gray-100 text-gray-700">
            <tr>
              <th class="text-left px-4 py-2">Item</th>
              <th class="text-right px-4 py-2">Amount (₹)</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="item in invoice.items"
              :key="item.id"
              class="border-t hover:bg-gray-50"
            >
              <td class="px-4 py-2">{{ item.item }}</td>
              <td class="px-4 py-2 text-right">₹{{ item.amount }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-6 space-y-2 text-sm text-gray-700 ml-auto text-right">
        <p><span class="font-medium text-gray-600">Tax Rate:</span> {{ invoice.tax_rate }}%</p>
        <p><span class="font-medium text-gray-600">Total:</span> ₹{{ invoice.total_amount }}</p>
      </div>
      <!-- Footer -->
      <div class="flex justify-end mt-6">
        <button
          @click="emit('close')"
          class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md text-sm"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from {
    opacity: 0;
    transform: scale(0.96);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
.animate-fade-in {
  animation: fade-in 0.2s ease-out;
}
</style>
