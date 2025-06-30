<template>
    <div class="col-12">
        <div class="card">
            <h5 class="text-center mb-4">Layout</h5>
            <div class="flex flex-wrap gap-4 justify-content-center">
                <div class="flex align-items-center">
                    <RadioButton v-model="form.layout" inputId="admin" name="layout" value="admin" @change="methods.change.layout" />
                    <label for="admin" class="ml-2"> <i class="pi pi-user"></i> Admin</label>
                </div>
                <div class="flex align-items-center">
                    <RadioButton v-model="form.layout" inputId="public" name="layout" value="public" @change="methods.change.layout" />
                    <label for="public" class="ml-2"><i class="pi pi-globe"></i> Public</label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <Toolbar :pt="{
                root: { style: { background: 'none', border: 'none', padding: '1.25rem 0 1.25rem 0' } }
            }">
                <template #end>
                    <Button label="Tambah" icon="pi pi-plus" @click="methods.click.openCloseModal(1, null, true)" severity="info" class="mr-2" />
                    <Button label="Simpan Menu" icon="pi pi-save" @click="methods.click.save" severity="success" />
                </template>
            </Toolbar>

            <div class="p-tree">
                <div class="p-tree-wrapper">
                    <TreeView :data="data.tree" />
                </div>
            </div>

            <Toolbar :pt="{
                root: { style: { background: 'none', border: 'none', padding: '1.25rem 0 1.25rem 0' } }
            }">
                <template #end>
                    <Button label="Tambah" icon="pi pi-plus" @click="methods.click.openCloseModal(1, null, true)" severity="info" class="mr-2" />
                    <Button label="Simpan Menu" icon="pi pi-save" @click="methods.click.save" severity="success" />
                </template>
            </Toolbar>

        </div>
    </div>
    <Toast />
    <DynamicDialog />
    <ConfirmDialog></ConfirmDialog>
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';

import { toRaw, markRaw, watch, ref, reactive, computed, onMounted, defineAsyncComponent, inject, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

import { usePage } from '@inertiajs/vue3';

import TreeView from "./TreeView.vue";
const ModalFormMenu = defineAsyncComponent(() => import('./ModalFormMenu.vue'));

export default {
    layout: AppLayout,
    components: {
        TreeView,
        ModalFormMenu
    },
    props: {
        menus: Array,
    },
    setup(props) {
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const data = reactive({
            tree: JSON.parse(JSON.stringify(props.menus)),
            deleteMenu: [],
        });

        const fn = {
            flattenProcess: (arr, parentOrder = 0, currentOrder = { count: 0 }) => {
                const flattenedArray = [];

                for (const item of arr) {
                    const urutan = ++currentOrder.count;
                    const urutan_parent = parentOrder;

                    flattenedArray.push({
                        ...item,
                        urutan,
                        urutan_parent,
                        is_parent: (item.items.length != 0 ? 1 : 0)
                    });

                    if (item.items.length > 0) {
                        const nestedItems = fn.flattenProcess(item.items, urutan, currentOrder);
                        flattenedArray.push(...nestedItems);
                    }
                }

                return flattenedArray;
            },
        };

        watch(
            () => props.menus,
            (newVal) => {
                data.tree = JSON.parse(JSON.stringify(newVal));
            }
        );
        // onMounted(() => {
        // });

        const methods = {
            change: {
                layout: () => {
                    // router.get(route('menu'), {
                    router.get('./menu', {
                        layout: form.layout
                    }, {
                        preserveState: true,
                    });
                }
            },
            click: {
                openCloseModal: (statusModal, dataModal) => {

                    globalVariable.$dialog.open(ModalFormMenu, {
                        props: {
                            showHeader: false,
                            style: {
                                width: '50vw',
                            },
                            breakpoints: {
                                '960px': '75vw',
                                '640px': '90vw'
                            },
                            modal: true,
                            contentClass: 'p-dialog',
                            contentStyle: 'padding:0 !important;max-height:100%;overflow-y:unset;'
                        },
                        data: {
                            statusModal: statusModal,
                            layout: form.layout,
                            dataModal: dataModal
                        },
                        onClose: (options) => {
                            const dataResultModal = options.data;
                            if (dataResultModal) {
                                data.tree.push(dataResultModal);
                            }
                        }
                    });
                },
                save: () => {
                    router.post('./menu/storeMenu', {
                        data: fn.flattenProcess(data.tree)
                    }, {
                        onSuccess: (result) => {
                            globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan Menu', life: 3000 });
                        },
                        onError: (error) => console.log(error)
                    });

                }
            }
        }

        const form = reactive({
            layout: (new URL(document.location)).searchParams.get('layout') ?? "admin"
        });

        return {
            form,
            data,
            methods
        };
    }
};


</script>
<style lang="scss" scoped>
.p-tree {
    border: none;
}
</style>