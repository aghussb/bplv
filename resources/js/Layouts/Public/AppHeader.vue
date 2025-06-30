<template>
    <div class="layout-topbar">
        <a href="/" class="layout-topbar-logo">
            <img :src="logoUrl" alt="logo" />
            <span>SAKAI</span>
        </a>

        <!-- <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle()">
            <i class="pi pi-bars"></i>
        </button> -->

        <button class="p-link layout-topbar-menu-button layout-topbar-button" @click="onTopBarMenuButton()">
            <i class="pi pi-ellipsis-v"></i>
        </button>

        <div class="layout-topbar-menu" :class="topbarMenuClasses" v-if="!$page.props.auth.user">
            <!-- <Link :href="route('adminDashboard')">
                home
            </Link> -->

            <!-- route('masuk') -->
            <Link href="/autentikasi/masuk" class="p-link layout-topbar-button">
            <i class="pi pi-user"></i>
            <span>Profile</span>
            </Link>

        </div>

        <div class="layout-topbar-menu" :class="topbarMenuClasses" v-if="$page.props.auth.user">
            <div>
                <h6 class="mb-0 text-gray-600">John Ducky</h6>
                <p class="mb-0 text-sm text-gray-600">Administrator</p>
            </div>
            <button @click="$refs.menuAccount.toggle($event)" class="p-link layout-topbar-button">
                <img :src="winOrigin+'/demo/images/login/avatar.png'" alt="Image" height="50" />
                <!-- <i class="pi pi-user"></i> -->
            </button>
            <Menu ref="menuAccount" :popup="true" :model="menuItems"></Menu>
        </div>

        <!-- ini hanya test language-->
        <select v-model="locale">
            <option v-for="optionLocale in supportLocales" :key="`locale-${optionLocale}`" :value="optionLocale">{{ optionLocale }}
            </option>
        </select>

    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import { useLayout } from './composables/layout';
import { router } from '@inertiajs/vue3'

const { layoutConfig, onMenuToggle } = useLayout();

import { useI18n } from 'vue-i18n';
import { SUPPORT_LOCALES as supportLocales, setI18nLanguage } from '@/i18n';

const { locale } = useI18n({ useScope: 'global' });

watch(locale, (val) => {
    setI18nLanguage(val);
});

const outsideClickListener = ref(null);
const topbarMenuActive = ref(false);

const menuItems = ref([
    // {
    //     label: 'Crud',
    //     icon: 'pi pi-fw pi-pencil',
    // },
    {
        label: 'Keluar',
        icon: 'pi pi-fw pi-reply',
        command: () => {
            // router.post(route("logout"));
            router.post("/logout");
        }
    },
]);

onMounted(() => {
    bindOutsideClickListener();
});

onBeforeUnmount(() => {
    unbindOutsideClickListener();
});

const logoUrl = computed(() => {
    return `${window.origin}/layout/images/${layoutConfig.darkTheme.value ? 'logo-white' : 'logo-dark'}.svg`;
});

const onTopBarMenuButton = () => {
    topbarMenuActive.value = !topbarMenuActive.value;
};

const topbarMenuClasses = computed(() => {
    return {
        'layout-topbar-menu-mobile-active': topbarMenuActive.value
    };
});

const bindOutsideClickListener = () => {
    if (!outsideClickListener.value) {
        outsideClickListener.value = (event) => {
            if (isOutsideClicked(event)) {
                topbarMenuActive.value = false;
            }
        };
        document.addEventListener('click', outsideClickListener.value);
    }
};

const unbindOutsideClickListener = () => {
    if (outsideClickListener.value) {
        document.removeEventListener('click', outsideClickListener);
        outsideClickListener.value = null;
    }
};

const isOutsideClicked = (event) => {
    if (!topbarMenuActive.value) return;

    const sidebarEl = document.querySelector('.layout-topbar-menu');
    const topbarEl = document.querySelector('.layout-topbar-menu-button');

    return !(sidebarEl.isSameNode(event.target) || sidebarEl.contains(event.target) || topbarEl.isSameNode(event.target) || topbarEl.contains(event.target));
};

const onSettingsClick = () => {
    topbarMenuActive.value = false;
    // Add your logic for handling settings click event
    // For example, you can navigate to a settings page manually
};
const winOrigin = window.origin
</script>

<style lang="scss" scoped>
.layout-topbar {
    box-shadow: 0px 0px 0px rgba(0, 0, 0, 0.02), 0px 0px 0px rgba(0, 0, 0, 0.05), 0px 0px 3px rgba(0, 0, 0, 0.08);
}
</style>
