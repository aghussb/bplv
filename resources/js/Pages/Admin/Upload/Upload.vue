<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h1>{{ this.$page.props.title }}</h1>
                    <!-- <p>InputText is an extension to standard input element with theming.</p> -->
                </div>
                <Toolbar :pt="{
                    root: { style: { background: 'none', border: 'none', padding: '1.25rem 0 1.25rem 0' } }
                }">
                    <template #start>
                        <form @submit.prevent="methods.submit.cari">
                            <div class="p-inputgroup flex-1">
                                <InputText v-model="form.cari" placeholder="Cari Keterangan" />
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
                    <Column style="text-align: center;" field="data">
                        <template #header> <span class="flex-1 text-center">ID</span> </template>
                        <template #body="{ data,frozenRow, index }">
                            {{ data.id }}
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Keterangan</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.keterangan }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Menu</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.menus != null ? data.menus.label : "-" }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Ekstensi</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.ekstensi }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Ukuran</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.ukuran_tampil }}</span>
                            </div>
                        </template>
                    </Column>

                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Action</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="flex justify-content-center flex-wrap gap-3">
                                <Button icon="fa-solid fa-pen-to-square" severity="info" v-tooltip="'Ubah'" @click="methods.click.openCloseModal(data)" />
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
const ModalFormUpload = defineAsyncComponent(() => import('./ModalFormUpload.vue'));
import { toRaw, markRaw, watch, ref, reactive, watchEffect, computed, onMounted, defineAsyncComponent, inject, toRefs, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

import { usePage } from '@inertiajs/vue3';
import { Convert } from '@/utils';

export default {
    layout: AppLayout,
    components: {
    },
    props: {
        uploads: Object,
        ekstensi: Array,
        menus: Array,
    },
    setup(props) {
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const fn = {
            reloadPageWithParameter: (params) => {
                router.get('./upload', (JSON.parse(JSON.stringify(params), (key, value) => value === null || value === '' ? undefined : value)));
            }
        };

        const data = reactive({
            tables: {
                data: props.uploads.data,
                currentPage: props.uploads.current_page,
                perPage: props.uploads.per_page,
                total: props.uploads.total
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
            () => props.uploads,
            (newVal) => {
                data.tables = {
                    data: newVal.data,
                    currentPage: newVal.current_page,
                    perPage: newVal.per_page,
                    total: newVal.total
                };

                data.tables.data.forEach(value => {
                    value.ukuran_tampil = new Convert(value.ukuran * (1024)).formatFileSize();
                });
            }
        );

        watch(
            parameters,
            (newVal) => {
                fn.reloadPageWithParameter(newVal);
            }
        );

        onMounted(() => {
            data.tables.data.forEach(value => {
                value.ukuran_tampil = new Convert(value.ukuran * (1024)).formatFileSize();
            });
        });

        const methods = {
            submit: {
                cari: () => {
                    parameters.cari = form.cari.toLowerCase();
                }
            },
            click: {
                openCloseModal: (dataModal) => {
                    // console.log(toRaw(dataModal));
                    globalVariable.$dialog.open(ModalFormUpload, {
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
                            dataMenu: props.menus,
                            dataEkstensi: props.ekstensi,
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
                delete: (dataUpload) => {
                    globalVariable.$confirm.require({
                        message: 'Apakah yakin ingin menghapus data ini?',
                        header: 'Konfirmasi',
                        icon: 'pi pi-exclamation-triangle',
                        acceptLabel: 'Ya',
                        rejectLabel: 'Tidak',
                        accept: () => {
                            router.get('./upload/deleteUpload', {
                                id: dataUpload.id
                            });
                            globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Hapus Data', life: 3000 });
                        },
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