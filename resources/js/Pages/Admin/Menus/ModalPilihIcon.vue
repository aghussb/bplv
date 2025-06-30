<template>
    <div class="p-dialog-header">
        <span class="p-dialog-title">Pilih Icon</span>
        <div class="p-dialog-header-icons">
            <button class="p-dialog-header-icon p-dialog-header-close p-link" type="button" @click="methods.click.maximizedMinimized">
                <i :class="{ 'pi pi-window-minimize': status.icon.maximizedMinimized, 'pi pi-window-maximize': !status.icon.maximizedMinimized }"></i>
                <span class="p-ink"></span>
            </button>
            <button class="p-dialog-header-icon p-dialog-header-close p-link" type="button" @click="this.thisInject.close()">
                <i class="pi pi-times"></i>
                <span class="p-ink"></span>
            </button>
        </div>
    </div>

    <div class="p-dialog-content" style="overflow: unset;max-height: 412px;">
        <Skeleton height="3rem" class="mb-2" v-if="!status.load"></Skeleton>
        <div class="grid mb-3 mt-3" v-if="status.load">
            <div class="col-12 mb-2">
                <span class="p-input-icon-left w-full">
                    <i class="pi pi-search" />
                    <InputText v-model="form.filter" class="w-full" placeholder="Cari Icon" />
                </span>
            </div>
        </div>

        <div class="grid icons-list p-3" v-if="!status.load">
            <div class="col-6 sm:col-4 lg:col-3 xl:col-2 pb-5 block" v-for="index in (6 * 3)">
                <Skeleton size="3rem" class="mb-2 m-auto"></Skeleton>
                <Skeleton width="5rem" class="mb-2 m-auto"></Skeleton>
            </div>
        </div>

        <div class="flex-auto" v-if="status.load">
            <ScrollPanel style="width: 100%; height: 300px" class="custombar2">
                <div class="grid icons-list text-center p-3">
                    <Button class="col-6 sm:col-4 lg:col-3 xl:col-2 pb-5 block" v-for="icon of computedIcons" :key="icon.properties.name" text @click="this.thisInject.close({ icon: icon.properties.name })">
                        <i :class="'text-2xl mb-2 pi ' + icon.properties.name"></i>
                        <div class="text-color-secondary">{{ icon.properties.name }}</div>
                    </Button>
                </div>
            </ScrollPanel>
        </div>
    </div>

    <div class="p-dialog-footer">
        <Button label="Tutup" icon="pi pi-times" @click="this.thisInject.close()" text />
    </div>
</template>

<script>
import { reactive, onMounted, computed, inject } from 'vue';
export default {
    setup() {
        const thisInject = inject('dialogRef');
        const status = reactive({
            icon: {
                maximizedMinimized: false,
            },
            load: false
        });

        const data = reactive({
            icons: null,
        });

        const form = reactive({
            filter: null
        });

        const computedIcons = computed(() => {
            if (form.filter) return data.icons.filter((icon) => icon.properties.name.indexOf(form.filter.toLowerCase()) > -1);
            else return data.icons;
        });

        onMounted(() => {
            axios.create({
                headers: { 'Cache-Control': 'no-cache' },
            })
                .get(window.origin+'/data/icons.json')
                .then((result) => {
                    let iconsData = result.data.icons.filter((value) => {
                        return value.icon.tags.indexOf('deprecate') === -1;
                    });
                    iconsData.sort((icon1, icon2) => {
                        if (icon1.properties.name < icon2.properties.name) return -1;
                        else if (icon1.properties.name > icon2.properties.name) return 1;
                        else return 0;
                    });

                    data.icons = iconsData;
                    status.load = true;
                })
                .catch((error) => console.log(error));

            // iconService.getIcons().then((result) => {
            //     let iconsData = result.data.icons.filter((value) => {
            //         return value.icon.tags.indexOf('deprecate') === -1;
            //     });
            //     iconsData.sort((icon1, icon2) => {
            //         if (icon1.properties.name < icon2.properties.name) return -1;
            //         else if (icon1.properties.name > icon2.properties.name) return 1;
            //         else return 0;
            //     });

            //     data.icons = iconsData;
            //     status.load = true;
            // });
        });

        const methods = {
            click: {
                maximizedMinimized: () => {
                    if (document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length - 1].classList.contains("p-dialog-maximized")) {
                        document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length - 1].classList.remove("p-dialog-maximized");
                        status.icon.maximizedMinimized = false;
                    }
                    else {
                        status.icon.maximizedMinimized = true;
                        document.getElementsByClassName('p-dialog p-component')[document.getElementsByClassName('p-dialog p-component').length - 1].classList.add("p-dialog-maximized");
                    }
                }
            }
        }

        return {
            computedIcons,
            thisInject,
            data,
            form,
            status,
            methods,
        }
    }
};
</script>

<style lang="scss" scoped>
.icons-list {
    i {
        color: var(--text-color-secondary);
    }
}

::v-deep(.p-scrollpanel.custombar1 .p-scrollpanel-wrapper) {
    border-right: 10px solid var(--surface-ground);
}

::v-deep(.p-scrollpanel.custombar1 .p-scrollpanel-bar) {
    background-color: var(--primary-300);
    opacity: 1;
    transition: background-color 0.3s;
}

::v-deep(.p-scrollpanel.custombar1 .p-scrollpanel-bar:hover) {
    background-color: var(--primary-400);
}

::v-deep(.p-scrollpanel.custombar2 .p-scrollpanel-wrapper) {
    border-right: 10px solid var(--surface-50);
    border-bottom: 10px solid var(--surface-50);
}

::v-deep(.p-scrollpanel.custombar2 .p-scrollpanel-bar) {
    background-color: var(--surface-300);
    border-radius: 0;
    opacity: 1;
    transition: background-color 0.3s;
}
</style>
