<template>
  <div
    :class="[
      'team-performance-card bg-white border rounded-lg p-4 transition-all cursor-pointer',
      'hover:border-primary-300 hover:shadow-md'
    ]"
    @click="emit('click', member)"
  >
    <!-- Header -->
    <div class="flex items-center gap-3 mb-3">
      <!-- Avatar -->
      <div class="flex-shrink-0">
        <div v-if="member.avatar" class="h-12 w-12 rounded-full overflow-hidden">
          <img :src="member.avatar" :alt="member.name" class="h-full w-full object-cover" />
        </div>
        <div v-else class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
          <UserIcon class="h-6 w-6 text-primary-600" />
        </div>
      </div>

      <!-- Name and role -->
      <div class="flex-1 min-w-0">
        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ member.name }}</h3>
        <p class="text-xs text-gray-500">{{ member.role }}</p>
      </div>

      <!-- Overall score -->
      <div
        :class="[
          'flex-shrink-0 h-10 w-10 rounded-full flex items-center justify-center text-sm font-bold',
          getScoreColor(member.accuracy_score)
        ]"
      >
        {{ member.accuracy_score }}%
      </div>
    </div>

    <!-- Metrics -->
    <div class="space-y-2">
      <!-- Accuracy -->
      <div>
        <div class="flex items-center justify-between text-xs mb-1">
          <span class="text-gray-600">Accuracy</span>
          <span class="font-medium text-gray-900">{{ member.accuracy_score }}%</span>
        </div>
        <ProgressBar
          :value="member.accuracy_score"
          :max="100"
          :color="getAccuracyColor(member.accuracy_score)"
          size="sm"
          :show-label="false"
          :show-percentage="false"
        />
      </div>

      <!-- Speed -->
      <div>
        <div class="flex items-center justify-between text-xs mb-1">
          <span class="text-gray-600">Avg. Time per Task</span>
          <span class="font-medium text-gray-900">{{ formatTime(member.avg_task_time) }}</span>
        </div>
      </div>

      <!-- Tasks completed today -->
      <div>
        <div class="flex items-center justify-between text-xs">
          <span class="text-gray-600">Tasks Today</span>
          <span class="font-medium text-gray-900">{{ member.tasks_completed_today }}</span>
        </div>
      </div>
    </div>

    <!-- Performance indicators -->
    <div v-if="member.badges && member.badges.length > 0" class="mt-3 flex items-center gap-2">
      <StatusBadge
        v-for="badge in member.badges"
        :key="badge"
        :label="badge"
        :variant="getBadgeColor(badge)"
        size="sm"
      />
    </div>

    <!-- Arrow indicator -->
    <div class="mt-3 text-right">
      <ChevronRightIcon class="h-4 w-4 text-gray-400 inline" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { UserIcon, ChevronRightIcon } from '@heroicons/vue/24/outline'
import StatusBadge from '@/components/common/StatusBadge.vue'
import ProgressBar from '@/components/common/ProgressBar.vue'

interface TeamMember {
  id: number
  name: string
  role: string
  avatar?: string
  accuracy_score: number
  avg_task_time: number // in seconds
  tasks_completed_today: number
  badges?: string[]
}

interface Props {
  member: TeamMember
}

interface Emits {
  (e: 'click', member: TeamMember): void
}

defineProps<Props>()
const emit = defineEmits<Emits>()

// Methods
function getScoreColor(score: number): string {
  if (score >= 95) return 'bg-green-100 text-green-800'
  if (score >= 85) return 'bg-blue-100 text-blue-800'
  if (score >= 75) return 'bg-yellow-100 text-yellow-800'
  return 'bg-red-100 text-red-800'
}

function getAccuracyColor(score: number): 'success' | 'primary' | 'warning' | 'danger' {
  if (score >= 95) return 'success'
  if (score >= 85) return 'primary'
  if (score >= 75) return 'warning'
  return 'danger'
}

function formatTime(seconds: number): string {
  const minutes = Math.floor(seconds / 60)
  const secs = seconds % 60

  if (minutes === 0) {
    return `${secs}s`
  }

  return `${minutes}m ${secs}s`
}

function getBadgeColor(badge: string): 'green' | 'blue' | 'yellow' | 'purple' {
  const colors: Record<string, any> = {
    'Top Performer': 'green',
    'Fast': 'blue',
    'Accurate': 'green',
    'New': 'purple',
    'Training': 'yellow'
  }
  return colors[badge] || 'blue'
}
</script>

<style scoped>
.team-performance-card {
  transition-property: border-color, box-shadow, transform;
}

.team-performance-card:hover {
  transform: translateY(-2px);
}

/* Animation */
.team-performance-card {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
