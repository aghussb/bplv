<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h1>{{ this.$page.props.title}}</h1>
                    <!-- <p>InputText is an extension to standard input element with theming.</p> -->
                </div>
                <Toolbar :pt="{
                    root: { style: { background: 'none', border: 'none', padding: '1.25rem 0 1.25rem 0' } }
                }">
                    <template #start>
                        <form @submit.prevent="methods.submit.cari">
                            <div class="p-inputgroup flex-1">
                                <InputText v-model="form.cari" placeholder="Cari Role" />
                                <Button icon="pi pi-search" severity="info" @click="methods.submit.cari" />
                            </div>
                        </form>
                    </template>
                    
                    <template #end>
                        <Button label="Tambah" icon="fa fa-plus" severity="success" @click="methods.click.openCloseModal(null)" />
                        <!-- <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="form.cari" placeholder="Cari Role" />
                        </span> -->
                    </template>
                </Toolbar>

                <DataTable :value="data.tables.data" showGridlines tableStyle="min-width: 50rem">
                    <template #empty>
                        <div class="text-center">Tidak ada data</div>
                    </template>
                    <Column style="text-align: center;" field="data">
                        <template #header> <span class="flex-1 text-center">No.</span> </template>
                        <template #body="{ frozenRow, index }">
                            {{ data.tables.currentPage > 1 ? (data.tables.perPage * (data.tables.currentPage - 1)) + index + 1 : index + 1 }}
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Role</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.name }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Menu Layout</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.layout }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Action</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="flex justify-content-center flex-wrap gap-3">
                                <Button icon="fa fa-pencil" severity="warning" v-tooltip="'Atur Hak Akses'" @click="methods.click.openCloseModal(data)" />
                                <Button icon="fa fa-trash-can" severity="danger" v-tooltip="'Hapus'" @click="methods.click.delete(data)" />
                            </div>
                        </template>
                    </Column>
                </DataTable>
                <Paginator :rows="data.tables.perPage" :totalRecords="data.tables.total" @page="methods.click.page" :pageLinkSize="10" :first="data.tables.currentPage - 1">
                    <template #start="slotProps">
                        Total : {{ data.tables.total }}
                    </template>
                    <!-- <template #firstpagelinkicon>
                        <div>
                            qwd
                        </div>
                    </template> -->
                </Paginator>
            </div>
        </div>
    </div>
    <Toast />
    <DynamicDialog />
    <ConfirmDialog></ConfirmDialog>
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
const ModalFormRole = defineAsyncComponent(() => import('./ModalFormRole.vue'));
import { toRaw, markRaw, watch, ref, reactive, watchEffect, computed, onMounted, defineAsyncComponent, inject, toRefs, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

import { usePage } from '@inertiajs/vue3';

export default {
    layout: AppLayout,
    components: {
    },
    props: {
        roles: Object,
        menus: Object,
    },
    setup(props) {
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const fn = {
            reloadPageWithParameter: (params) => {
                router.get('./role', (JSON.parse(JSON.stringify(params), (key, value) => value === null || value === '' ? undefined : value)));
            }
        };

        const data = reactive({
            tables: {
                data: props.roles.data,
                currentPage: props.roles.current_page,
                perPage: props.roles.per_page,
                total: props.roles.total
            }
        });

        const form = reactive({
            cari: (new URL(document.location)).searchParams.get('cari')
        });

        const parameters = reactive({
            page: (new URL(document.location)).searchParams.get('page'),
            cari: (new URL(document.location)).searchParams.get('cari'),
        });

        // console.log(toRaw(parameters));

        watch(
            () => props.roles,
            (newVal) => {
                // data.tables.data = newVal.data;
                data.tables = {
                    data: newVal.data,
                    currentPage: newVal.current_page,
                    perPage: newVal.per_page,
                    total: newVal.total
                };
            }
        );

        watch(
            parameters,
            (newVal) => {
                fn.reloadPageWithParameter(newVal);
            }
        );

        // onMounted(() => {
        // });

        const methods = {
            submit: {
                cari: () => {
                    parameters.cari = form.cari.toLowerCase();
                }
            },
            click: {
                openCloseModal: (dataModal) => {
                    // console.log(toRaw(dataModal));
                    globalVariable.$dialog.open(ModalFormRole, {
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
                            statusModal: dataModal == null ? false : true,
                            dataModal: dataModal ?? null,
                            dataMenu: props.menus
                        },
                        onClose: (options) => {
                            const dataResultModal = options.data;

                            if (dataResultModal) {

                            }
                        }
                    });
                },
                page: ($event) => {
                    if (((new URL(document.location)).searchParams.get('page') == null && $event.page != 0) || (new URL(document.location)).searchParams.get('page') != null && parseInt((new URL(document.location)).searchParams.get('page')) - 1 != $event.page) {
                        parameters.page = $event.page + 1;
                    }
                },
                delete: (dataRole) => {
                    globalVariable.$confirm.require({
                        message: 'Apakah yakin ingin menghapus role ini?',
                        header: 'Konfirmasi',
                        icon: 'pi pi-exclamation-triangle',
                        acceptLabel: 'Ya',
                        rejectLabel: 'Tidak',
                        accept: () => {
                            router.get('./role/deleteRole', {
                                id:dataRole.id
                            });
                            globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Hapus Role', life: 3000 });
                            // globalVariable.$toast.add({ severity: 'info', summary: 'Confirmed', detail: 'You have accepted', life: 3000 });
                        },
                        // reject: () => {
                        // globalVariable.$toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
                        // }
                    });
                }
            }
        }

        return {
            form,
            parameters,
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