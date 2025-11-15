
<script setup>
import { mdiForwardburger, mdiBackburger, mdiMenu } from '@mdi/js'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import menuAside from '@/menuAside.js'
import menuNavBar from '@/menuNavBar.js'
import { useDarkModeStore } from '@/stores/darkMode.js'
import BaseIcon from '@/components/base/BaseIcon.vue'
import FormControl from '@/components/form/FormControl.vue'
import NavBar from '@/components/navbar/NavBar.vue'
import NavBarItemPlain from '@/components/navbar/NavBarItemPlain.vue'
import AsideMenu from '@/components/aside/AsideMenu.vue'
import FooterBar from '@/components/FooterBar.vue'
import { useAuthStore } from '@/stores/authentication'
import { useI18n } from 'vue-i18n'

const layoutAsidePadding = 'xl:pl-60'

const darkModeStore = useDarkModeStore()

const router = useRouter()

const isAsideMobileExpanded = ref(false)
const isAsideLgActive = ref(false)

router.beforeEach(() => {
  isAsideMobileExpanded.value = false
  isAsideLgActive.value = false
})

const store = useAuthStore()

const {t, locale} = useI18n()

const menuClick = (event, item) => {
  if (item.isToggleLightDark) {
    darkModeStore.set()
  }

  if(item.isLocaleSwitcher) {
    console.log(item, '--3ks', t, ' HELLO/;.SAD', locale.value)
    locale.value = locale.value === 'en' ? 'am' : 'en';
  }

  if (item.isLogout) {
    store.logout()
  }
}
</script>

<template>
  <div :class="{ 'overflow-hidden lg:overflow-visible': isAsideMobileExpanded }">
    <div :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
      class="pt-14 min-h-screen w-screen transition-position lg:w-auto bg-gray-50 dark:bg-slate-800 dark:text-slate-100">
      <NavBar :menu="menuNavBar" :class="[layoutAsidePadding, { 'ml-60 lg:ml-0': isAsideMobileExpanded }]"
        @menu-click="menuClick">
        <NavBarItemPlain display="flex lg:hidden" @click.prevent="isAsideMobileExpanded = !isAsideMobileExpanded">
          <BaseIcon :path="isAsideMobileExpanded ? mdiBackburger : mdiForwardburger" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain display="hidden lg:flex xl:hidden" @click.prevent="isAsideLgActive = true">
          <BaseIcon :path="mdiMenu" size="24" />
        </NavBarItemPlain>
        <NavBarItemPlain use-margin>
          <FormControl placeholder="Search (ctrl+k)" ctrl-k-focus transparent borderless />
        </NavBarItemPlain>
      </NavBar>
      <AsideMenu :is-aside-mobile-expanded="isAsideMobileExpanded" :is-aside-lg-active="isAsideLgActive"
        :menu="menuAside" @menu-click="menuClick" @aside-lg-close-click="isAsideLgActive = false" />
      <slot />
      <FooterBar>
        Get more with
        <a href="https://tailwind-vue.justboil.me/" target="_blank" class="text-blue-600">Premium version</a>
      </FooterBar>
    </div>
  </div>
</template>
