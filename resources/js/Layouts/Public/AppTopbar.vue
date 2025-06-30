<template>
    <div class="card fixed z-2 w-screen p-2" style="margin-top: 68px;">
        <!-- style="background-color: aqua;" -->
        <Menubar :model="menus" :pt="{
            root: { style: { border: 'none !important', 'border-radius': '0px', background: 'transparent' } }
        }" class="menubar-normal">
            <template #item="itemProps">
                <span class="p-menuitem-link" v-if="itemProps.item.items.length != 0" :class="[{ 'active-menu': checkActiveMenu(itemProps) && itemProps.root }]">
                    <span class="root-menu p-menuitem-icon" :class="itemProps.item.icon"></span>
                    <span class="root-menu p-menuitem-text">{{ itemProps.item.label }}</span>
                    <svg v-if="itemProps.item.items && itemProps.item.items.length > 0" width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="p-icon p-submenu-icon root-menu" aria-hidden="true">
                        <path d="M3.58659 4.5007C3.68513 4.50023 3.78277 4.51945 3.87379 4.55723C3.9648 4.59501 4.04735 4.65058 4.11659 4.7207L7.11659 7.7207L10.1166 4.7207C10.2619 4.65055 10.4259 4.62911 10.5843 4.65956C10.7427 4.69002 10.8871 4.77074 10.996 4.88976C11.1049 5.00877 11.1726 5.15973 11.1889 5.32022C11.2052 5.48072 11.1693 5.6422 11.0866 5.7807L7.58659 9.2807C7.44597 9.42115 7.25534 9.50004 7.05659 9.50004C6.85784 9.50004 6.66722 9.42115 6.52659 9.2807L3.02659 5.7807C2.88614 5.64007 2.80725 5.44945 2.80725 5.2507C2.80725 5.05195 2.88614 4.86132 3.02659 4.7207C3.09932 4.64685 3.18675 4.58911 3.28322 4.55121C3.37969 4.51331 3.48305 4.4961 3.58659 4.5007Z" fill="currentColor"></path>
                    </svg>
                    <span class="p-icon p-submenu-icon root-menu" style="transform: unset !important;" :class="'pi pi-check'"></span>
                </span>

                <Link :class="[{ 'active-menu': !itemProps.item.parent_id && itemProps.item.id == $page.props.menu_active.id }]" class="p-menuitem-link" v-if="itemProps.item.items.length == 0 && itemProps.item.function == null" :href="itemProps.item.route_name != null ? itemProps.item.url : ''">
                    <!-- {{ itemProps.item.route_name }} -->
                <span class="p-menuitem-icon" :class="itemProps.item.icon"></span>
                <span class="p-menuitem-text">{{ itemProps.item.label }}</span>
                <span class="p-icon p-submenu-icon" style="transform: unset !important;" :class="'pi pi-check'"></span>
                </Link>

                <span class="p-menuitem-link" v-if="itemProps.item.items.length == 0 && itemProps.item.function != null">
                <span class="p-menuitem-icon" :class="itemProps.item.icon"></span>
                <span class="p-menuitem-text">{{ itemProps.item.label }}</span>
                <span class="p-icon p-submenu-icon" style="transform: unset !important;" :class="'pi pi-check'"></span>
                </span>

            </template>
        </Menubar>
        <!-- <div class="p-menubar p-component">
            <ul class="p-menubar-root-list">
                <template v-for="(item, i) in menus" :key="item">
                    <app-menu-item :item="item" :index="i"></app-menu-item>
                </template>
            </ul>
        </div> -->
    </div>
</template>
<script>
import { toRaw, watch, ref, reactive, computed, onBeforeMount, onMounted } from 'vue'
import { usePage } from "@inertiajs/vue3";

export default {
    // watch: {
    //     '$page.url': function (newUrl, oldUrl) {
    //         this.menus = [{
    //             items: usePage().props.menus.admin
    //         }];
    //     }
    // },
    setup(props) {
        const processArrayWithCommand = function (arr) {
            arr.map((item, key) => {
                if (item.function !== null) {
                    arr[key].command = () => {
                        eval(item.function);
                    }
                }
                if (item.items != undefined) {
                    processArrayWithCommand(item.items);
                }
            });
        }


        const dataMenusLayoutPublic = JSON.parse(JSON.stringify(usePage().props.sidebar_menus.public));
        processArrayWithCommand(dataMenusLayoutPublic);

        const menus = ref(dataMenusLayoutPublic);

        // document.getElementsByClassName("p-menuitem-content")[0].style.background = 'red';

        // const menus = ref([ { label: 'File2', icon: 'pi pi-fw pi-file', items: [ { label: 'Bookmark', icon: 'pi pi-fw pi-bookmark',items:[] }, { label: 'Video', icon: 'pi pi-fw pi-video',items:[] }, ] }, { label: 'File', icon: 'pi pi-fw pi-file', items: [ { label: 'Bookmark', icon: 'pi pi-fw pi-bookmark',items:[] }, { label: 'Video', icon: 'pi pi-fw pi-video',items:[] }, ] },{ label: 'Filesss', icon: 'pi pi-fw pi-file', items: [ ] } ]);

        watch(
            () => usePage().props.sidebar_menus.public,
            (newVal) => {
                const dataMenusLayoutPublic = JSON.parse(JSON.stringify(newVal));
                processArrayWithCommand(dataMenusLayoutPublic);
                menus.value = dataMenusLayoutPublic;
            }
        );

        onMounted(() => {
            // console.log(toRaw(usePage().props));
        });

        const fn = {
            cekIdDalamArray: (array, targetId) => {
                // Iterasi melalui setiap elemen dalam array
                for (let i = 0; i < array.length; i++) {
                    const item = array[i];

                    // Memeriksa apakah id pada elemen saat ini sama dengan targetId
                    if (item.id === targetId) {
                        return true; // Jika cocok, mengembalikan true
                    }

                    // Jika elemen memiliki properti 'items' dan 'items' adalah array
                    if (item.items && Array.isArray(item.items)) {
                        // Rekursi untuk memeriksa array yang bersarang
                        if (fn.cekIdDalamArray(item.items, targetId)) {
                            return true; // Jika id ditemukan dalam array yang bersarang, mengembalikan true
                        }
                    }
                }

                return false; // Mengembalikan false jika tidak ada id yang cocok
            }
        }

        const checkActiveMenu = (data) => {
            if (data.root) {
                return fn.cekIdDalamArray(data.item.items, usePage().props.menu_active.id);
            }
            else {
                return false
            }
        }

        return {
            checkActiveMenu,
            menus
        };
    }
}

</script>

<style lang="scss" scoped>
.card {
    padding: unset;
    border-radius: 0px;
    border: none;
    box-shadow: 0px 3px 5px rgba(0, 0, 0, 0.02), 0px 0px 2px rgba(0, 0, 0, 0.05), 0px 1px 4px rgba(0, 0, 0, 0.08);
}

// untuk mencegah bug menubar jika menggunakan slots item
.menubar-normal :deep(.p-menuitem-content) {
    background: transparent !important;
}

.menubar-normal :deep(.p-menuitem-content:hover) {
    color: #495057 !important;
    background: #e9ecef !important;
}

// style="color: #4338CA !important; background: #EEF2FF !important; transition: box-shadow 0.2s; border-radius: 6px;"
.active-menu {
    background: #EEF2FF !important;
    transition: box-shadow 0.2s;
    border-radius: 6px;

    &:deep(.root-menu) {
        color: #4338CA !important;
    }

      &:deep(span) {
        color: #4338CA !important;
      }
}
</style>
