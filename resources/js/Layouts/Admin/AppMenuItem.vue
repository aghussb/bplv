<template>
    <!-- item.activeSide || -->
    <li :class="[{ 'layout-root-menuitem': root, 'active-menuitem': isActiveMenu }]" class="list-menu-item">
        <!-- {{item.activeSide}} -->
        <a v-if="(item.items)" @click="itemClick($event, item, index)" :class="item.class" class="link-menu-item">
            <i :class="item.icon" class="layout-menuitem-icon"></i>
            <span class="layout-menuitem-text">{{ item.label }}</span>
            <i class="pi pi-fw pi-angle-down layout-submenu-toggler" v-if="item.items"></i>
        </a>

        <Link v-if="!root && !item.items && item.function === null" :href="item.route_name != null ? item.url : ''" @click="itemClick($event, item, index)" :class="[item.class, { 'active-route': item.activeRoute }]">
            <!-- {{route(item.route_name)}} -->
            <!-- {{ item.url }} -->
        <i :class="item.icon" class="layout-menuitem-icon"></i>
        <span class="layout-menuitem-text">{{ item.label }}</span>
        </Link>

        <a v-if="!root && !item.items && item.function != null" @click="itemClick($event, item, index)" :class="[item.class, { 'active-route': item.activeRoute }]">
            <i :class="item.icon" class="layout-menuitem-icon"></i>
            <span class="layout-menuitem-text">{{ item.label }}</span>
        </a>

        <transition v-if="item.items" name="layout-submenu">
            <ul v-show="root ? true : isActiveMenu" class="layout-submenu">
                <!-- <ul v-show="root ? true : item.activeSide || isActiveMenu" class="layout-submenu"> -->
                <app-menu-item v-for="(child, i) in item.items" :key="child" :index="i" :item="child" :parentItemKey="itemKey" :root="false"></app-menu-item>
            </ul>
        </transition>
    </li>
</template>

<script>
import { ref, onBeforeMount, watch, toRaw } from 'vue';
import { useLayout } from './composables/layout';
import { usePage } from "@inertiajs/vue3";

export default {
    props: {
        item: {
            type: Object,
            default: () => ({})
        },
        index: {
            type: Number,
            default: 0
        },
        root: {
            type: Boolean,
            default: true
        },
        parentItemKey: {
            type: String,
            default: null
        }
    },
    watch: {
        '$page.url': function (newUrl, oldUrl) {
            if (this.item.id && this.item.items) {
                this.item.items.forEach(value => {
                    value.activeRoute = (value.url === document.URL || value.id == usePage().props.menu_active.id) && newUrl == usePage().props.route.path;
                    // value.activeRoute = (value.route_name == route().current() || value.id == usePage().props.menu_active.id) && newUrl == usePage().props.route.path;

                });
            }
            else {
                this.item.activeRoute = (this.item.url === document.URL || this.item.id == usePage().props.menu_active.id) && newUrl == usePage().props.route.path;
                // this.item.activeRoute = (this.item.route_name == route().current() || this.item.id == usePage().props.menu_active.id) && newUrl == usePage().props.route.path;
            }
        }
    },
    setup(props) {
        // console.log(toRaw(usePage().props));
        const { layoutConfig, layoutState, setActiveMenuItem, onMenuToggle } = useLayout();
        const isActiveMenu = ref(false);
        const itemKey = ref(null);
        // console.log(location.href);
        onBeforeMount(() => {
            itemKey.value = props.parentItemKey ? props.parentItemKey + '-' + props.index : String(props.index);

            const activeItem = layoutState.activeMenuItem;
            isActiveMenu.value = activeItem === itemKey.value || (activeItem ? activeItem.startsWith(itemKey.value + '-') : false);
            // console.log(toRaw(activeItem));
            if (props.item.items) {
                if (props.item.id) {
                    isActiveMenu.value = props.item.items.some(function (obj) {
                        return obj.url === document.URL || obj.id == usePage().props.menu_active.id;
                        // return obj.route_name === route().current() || obj.id == usePage().props.menu_active.id;
                    });
                }

                props.item.items.forEach(value => {
                    value.activeRoute = value.url === document.URL || value.id == usePage().props.menu_active.id;
                    // value.activeRoute = value.route_name == route().current() || value.id == usePage().props.menu_active.id;
                });
            }

            isActiveMenu.value = props.item.activeSide ?? isActiveMenu.value;

        });

        watch(
            () => layoutConfig.activeMenuItem.value,
            (newVal) => {
                isActiveMenu.value = newVal === itemKey.value || newVal.startsWith(itemKey.value + '-');
            }
        );

        const itemClick = (event, item) => {
            // if (item.disabled) {
            //     event.preventDefault();
            //     return;
            // }

            const { overlayMenuActive, staticMenuMobileActive } = layoutState;

            // if ((item.url || item.items) && (staticMenuMobileActive.value || overlayMenuActive.value)) {
            //     onMenuToggle();
            // }

            if (item.command) {
                item.command({ originalEvent: event, item: item });
                event.preventDefault();
                return;
            }

            const foundItemKey = item.items ? (isActiveMenu.value ? props.parentItemKey : itemKey) : itemKey.value;
            setActiveMenuItem(foundItemKey);


        };

        return {
            isActiveMenu,
            itemClick,
            itemKey
        };
    }
};
</script>


<style lang="scss" scoped></style>
