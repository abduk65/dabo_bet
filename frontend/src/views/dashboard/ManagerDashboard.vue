<template>
  <div class="manager-dashboard min-h-screen bg-gray-50">
    <!-- Page header -->
    <div class="bg-white shadow">
      <div class="px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Manager Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">
              {{ formatDateTime(new Date()) }}
            </p>
          </div>

          <!-- Quick stats -->
          <div class="flex items-center gap-4">
            <div class="text-center">
              <p class="text-2xl font-bold text-primary-600">{{ metrics.pending_approvals }}</p>
              <p class="text-xs text-gray-500">Pending</p>
            </div>
            <div class="text-center">
              <p class="text-2xl font-bold text-green-600">{{ metrics.completed_today }}</p>
              <p class="text-xs text-gray-500">Today</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="px-4 sm:px-6 lg:px-8 py-6">
      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Left column: To-do list and approvals -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Today's To-Do List -->
          <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <h2 class="text-lg font-semibold text-gray-900">Today's To-Do</h2>
              <button
                @click="showAddTaskModal = true"
                class="text-sm font-medium text-primary-600 hover:text-primary-700"
              >
                + Add Task
              </button>
            </div>

            <div v-if="tasks.length === 0" class="text-center py-8 text-gray-500">
              <CheckCircleIcon class="h-12 w-12 mx-auto text-gray-400 mb-2" />
              <p class="text-sm">All tasks completed!</p>
            </div>

            <div v-else class="space-y-2">
              <div
                v-for="task in tasks"
                :key="task.id"
                :class="[
                  'flex items-center gap-3 p-3 rounded-lg border transition-all',
                  task.completed ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200 hover:border-primary-300'
                ]"
              >
                <input
                  type="checkbox"
                  :checked="task.completed"
                  @change="toggleTask(task.id)"
                  class="h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                />

                <div class="flex-1 min-w-0">
                  <p :class="['text-sm font-medium', task.completed && 'line-through text-gray-500']">
                    {{ task.title }}
                  </p>
                  <p v-if="task.description" class="text-xs text-gray-500 mt-1">
                    {{ task.description }}
                  </p>
                </div>

                <StatusBadge
                  v-if="task.priority"
                  :variant="getPriorityColor(task.priority)"
                  :label="task.priority"
                  size="sm"
                />

                <button
                  @click="deleteTask(task.id)"
                  class="p-1 rounded hover:bg-gray-100"
                >
                  <TrashIcon class="h-4 w-4 text-gray-400" />
                </button>
              </div>
            </div>
          </div>

          <!-- Approval Queue -->
          <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-3">
                <h2 class="text-lg font-semibold text-gray-900">Approval Queue</h2>
                <StatusBadge :label="String(approvals.length)" variant="yellow" />
              </div>

              <!-- Filters -->
              <div class="flex items-center gap-2">
                <select
                  v-model="approvalFilter"
                  class="text-sm border-gray-300 rounded-md focus:ring-primary-500 focus:border-primary-500"
                >
                  <option value="all">All Types</option>
                  <option value="adjustment">Stock Adjustments</option>
                  <option value="purchase">Purchase Orders</option>
                  <option value="expense">Expenses</option>
                  <option value="void">Void Requests</option>
                </select>

                <!-- Bulk actions -->
                <button
                  v-if="selectedApprovals.length > 0"
                  @click="showBulkApproveModal = true"
                  class="inline-flex items-center px-3 py-2 border border-green-300 shadow-sm text-sm font-medium rounded-md text-green-700 bg-white hover:bg-green-50"
                >
                  <CheckIcon class="h-4 w-4 mr-1" />
                  Approve ({{ selectedApprovals.length }})
                </button>

                <button
                  v-if="selectedApprovals.length > 0"
                  @click="showBulkRejectModal = true"
                  class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50"
                >
                  <XMarkIcon class="h-4 w-4 mr-1" />
                  Reject ({{ selectedApprovals.length }})
                </button>
              </div>
            </div>

            <!-- Approval list -->
            <div v-if="filteredApprovals.length === 0" class="text-center py-8 text-gray-500">
              <InboxIcon class="h-12 w-12 mx-auto text-gray-400 mb-2" />
              <p class="text-sm">No pending approvals</p>
            </div>

            <div v-else class="space-y-3">
              <ApprovalQueueItem
                v-for="approval in filteredApprovals"
                :key="approval.id"
                :approval="approval"
                :selected="selectedApprovals.includes(approval.id)"
                @toggle-select="toggleApprovalSelection(approval.id)"
                @approve="handleApprove(approval)"
                @reject="handleReject(approval)"
                @view="viewApprovalDetails(approval)"
              />
            </div>
          </div>
        </div>

        <!-- Right column: Team performance -->
        <div class="space-y-6">
          <!-- Team Performance -->
          <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Team Performance</h2>

            <div class="space-y-4">
              <TeamPerformanceCard
                v-for="member in teamMembers"
                :key="member.id"
                :member="member"
                @click="viewMemberDetails(member)"
              />
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h2>

            <div class="space-y-2">
              <button
                @click="navigateTo('/inventory/adjustments/new')"
                class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-colors"
              >
                <CubeIcon class="h-5 w-5 text-gray-600" />
                <span class="text-sm font-medium text-gray-900">New Adjustment</span>
              </button>

              <button
                @click="navigateTo('/inventory/purchase-orders/new')"
                class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-colors"
              >
                <ShoppingCartIcon class="h-5 w-5 text-gray-600" />
                <span class="text-sm font-medium text-gray-900">New Purchase Order</span>
              </button>

              <button
                @click="navigateTo('/reports/stock')"
                class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-colors"
              >
                <DocumentChartBarIcon class="h-5 w-5 text-gray-600" />
                <span class="text-sm font-medium text-gray-900">Stock Report</span>
              </button>

              <button
                @click="navigateTo('/reports/sales')"
                class="w-full flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:border-primary-300 hover:bg-primary-50 transition-colors"
              >
                <ChartBarIcon class="h-5 w-5 text-gray-600" />
                <span class="text-sm font-medium text-gray-900">Sales Report</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Approval Detail Modal -->
    <ApprovalModal
      v-if="selectedApproval"
      :approval="selectedApproval"
      :open="showApprovalModal"
      @close="showApprovalModal = false"
      @approve="handleModalApprove"
      @reject="handleModalReject"
    />

    <!-- Bulk Approve Confirmation -->
    <ConfirmationModal
      v-if="showBulkApproveModal"
      :open="showBulkApproveModal"
      title="Bulk Approve"
      :message="`Are you sure you want to approve ${selectedApprovals.length} items?`"
      confirm-text="Approve All"
      confirm-variant="success"
      @confirm="handleBulkApprove"
      @cancel="showBulkApproveModal = false"
    />

    <!-- Bulk Reject Confirmation -->
    <ConfirmationModal
      v-if="showBulkRejectModal"
      :open="showBulkRejectModal"
      title="Bulk Reject"
      :message="`Are you sure you want to reject ${selectedApprovals.length} items?`"
      confirm-text="Reject All"
      confirm-variant="danger"
      require-reason
      @confirm="handleBulkReject"
      @cancel="showBulkRejectModal = false"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import {
  CheckCircleIcon,
  TrashIcon,
  CheckIcon,
  XMarkIcon,
  InboxIcon,
  CubeIcon,
  ShoppingCartIcon,
  DocumentChartBarIcon,
  ChartBarIcon
} from '@heroicons/vue/24/outline'
import StatusBadge from '@/components/common/StatusBadge.vue'
import ApprovalQueueItem from '@/components/manager/ApprovalQueueItem.vue'
import ApprovalModal from '@/components/manager/ApprovalModal.vue'
import TeamPerformanceCard from '@/components/manager/TeamPerformanceCard.vue'
import ConfirmationModal from '@/components/common/ConfirmationModal.vue'
import { formatDateTime } from '@/utils/formatters'
import { useNotification } from '@/composables/useNotification'
import { dashboardService } from '@/services/dashboard.service'

const router = useRouter()
const { notify } = useNotification()

// State
const metrics = ref({
  pending_approvals: 0,
  completed_today: 0
})

const tasks = ref<any[]>([])
const approvals = ref<any[]>([])
const teamMembers = ref<any[]>([])
const selectedApprovals = ref<number[]>([])
const approvalFilter = ref('all')

const showAddTaskModal = ref(false)
const showApprovalModal = ref(false)
const showBulkApproveModal = ref(false)
const showBulkRejectModal = ref(false)
const selectedApproval = ref<any>(null)

// Computed
const filteredApprovals = computed(() => {
  if (approvalFilter.value === 'all') {
    return approvals.value
  }
  return approvals.value.filter(a => a.type === approvalFilter.value)
})

// Methods
async function loadDashboardData() {
  try {
    const [metricsData, tasksData, approvalsData, teamData] = await Promise.all([
      dashboardService.getManagerMetrics(),
      dashboardService.getManagerTasks(),
      dashboardService.getApprovalQueue(),
      dashboardService.getTeamPerformance()
    ])

    metrics.value = metricsData
    tasks.value = tasksData
    approvals.value = approvalsData
    teamMembers.value = teamData
  } catch (error: any) {
    notify('error', 'Failed to load dashboard', error.message)
  }
}

function toggleTask(taskId: number) {
  const task = tasks.value.find(t => t.id === taskId)
  if (task) {
    task.completed = !task.completed
    // TODO: Save to backend
  }
}

function deleteTask(taskId: number) {
  tasks.value = tasks.value.filter(t => t.id !== taskId)
  // TODO: Delete from backend
}

function getPriorityColor(priority: string): 'red' | 'yellow' | 'blue' {
  const colors = {
    high: 'red',
    medium: 'yellow',
    low: 'blue'
  }
  return colors[priority as keyof typeof colors] || 'blue'
}

function toggleApprovalSelection(approvalId: number) {
  const index = selectedApprovals.value.indexOf(approvalId)
  if (index > -1) {
    selectedApprovals.value.splice(index, 1)
  } else {
    selectedApprovals.value.push(approvalId)
  }
}

function handleApprove(approval: any) {
  selectedApproval.value = approval
  showApprovalModal.value = true
}

function handleReject(approval: any) {
  selectedApproval.value = approval
  showApprovalModal.value = true
}

function viewApprovalDetails(approval: any) {
  selectedApproval.value = approval
  showApprovalModal.value = true
}

async function handleModalApprove(approval: any, notes?: string) {
  try {
    await dashboardService.bulkApprove([approval.id], notes)
    notify('success', 'Approval completed')
    showApprovalModal.value = false
    await loadDashboardData()
  } catch (error: any) {
    notify('error', 'Failed to approve', error.message)
  }
}

async function handleModalReject(approval: any, reason: string) {
  try {
    await dashboardService.bulkReject([approval.id], reason)
    notify('success', 'Rejection completed')
    showApprovalModal.value = false
    await loadDashboardData()
  } catch (error: any) {
    notify('error', 'Failed to reject', error.message)
  }
}

async function handleBulkApprove() {
  try {
    await dashboardService.bulkApprove(selectedApprovals.value)
    notify('success', `Approved ${selectedApprovals.value.length} items`)
    selectedApprovals.value = []
    showBulkApproveModal.value = false
    await loadDashboardData()
  } catch (error: any) {
    notify('error', 'Failed to bulk approve', error.message)
  }
}

async function handleBulkReject(reason: string) {
  try {
    await dashboardService.bulkReject(selectedApprovals.value, reason)
    notify('success', `Rejected ${selectedApprovals.value.length} items`)
    selectedApprovals.value = []
    showBulkRejectModal.value = false
    await loadDashboardData()
  } catch (error: any) {
    notify('error', 'Failed to bulk reject', error.message)
  }
}

function viewMemberDetails(member: any) {
  router.push(`/team/members/${member.id}`)
}

function navigateTo(path: string) {
  router.push(path)
}

// Lifecycle
onMounted(() => {
  loadDashboardData()
})
</script>

<style scoped>
.manager-dashboard {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}
</style>
