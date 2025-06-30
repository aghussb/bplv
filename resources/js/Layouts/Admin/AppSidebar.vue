<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in menus" :key="item">
            <app-menu-item :item="item" :index="i"></app-menu-item>
        </template>
    </ul>
</template>

<script>
import { toRaw, watch, ref, reactive, computed } from 'vue'
import { usePage } from "@inertiajs/vue3";
import AppMenuItem from './AppMenuItem.vue';

export default {
    components: {
        AppMenuItem
    },
    // watch: {
    //     '$page.url': function (newUrl, oldUrl) {
    //         this.menus = [{
    //             items: usePage().props.menus.admin
    //         }];
    //     }
    // },
    setup(props) {
        const dataMenusLoginLayout = JSON.parse(JSON.stringify(usePage().props.sidebar_menus.admin));

        // const processArrayWithCommand = function (arr) {
        //     arr.map((item, key) => {
        //         if (item.function !== null) {
        //             arr[key].command = () => {
        //                 eval(item.function);
        //             }
        //         }
        //         if (item.items != undefined) {
        //             processArrayWithCommand(item.items);
        //         }
        //     });
        // }

        // processArrayWithCommand(dataMenusLoginLayout);

        const addActiveSide = function(data, id) {
            return data.map(item => {
                if (item.function !== null) {
                    item.command = () => {
                        eval(item.function);
                    }
                }
                if (item.id === id || (item.items && addActiveSide(item.items, id).some(child => child.activeSide))) {
                    item.activeSide = true;
                }
                if (item.items) {
                    item.items = addActiveSide(item.items, id);
                }
                return item;
            });
        }

        // console.log(toRaw(usePage().props.menu_active.id));

        const menus = ref([{
            items: addActiveSide(dataMenusLoginLayout,usePage().props.menu_active.id)
        }]);

        watch(
            () => usePage().props.sidebar_menus.admin,
            (newVal) => {
                const dataMenusLoginLayout = JSON.parse(JSON.stringify(newVal));            
                menus.value = [{
                    items: addActiveSide(dataMenusLoginLayout,usePage().props.menu_active.id)
                }];
            }
        );

        return {
            menus
        };
    }
}

</script>

<style lang="scss" scoped></style>
