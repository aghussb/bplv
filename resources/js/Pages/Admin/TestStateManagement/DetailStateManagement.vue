<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h4>Test Detail Data</h4>
                </div>

                <DataTable :value="data" showGridlines tableStyle="min-width: 50rem">
                    <template #empty>
                        <div class="text-center">Tidak ada data</div>
                    </template>
                    <Column style="text-align: center;" field="data">
                        <template #header> <span class="flex-1 text-center">Angka 1</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <span>{{ data.angka1 }}</span>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Angka 2</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.angka2 }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Hasil</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.angka1 + data.angka2 }}</span>
                            </div>
                        </template>
                    </Column>
                </DataTable>

                <Button label="Kembali" @click="methods.click.back" severity="danger" icon="fa fa-reply" class="mt-4" />
            </div>
        </div>
    </div>
    <Toast />
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
import { onMounted } from 'vue';
import { router } from '@inertiajs/vue3'

import { usePerhitunganStore } from "./stores/perhitungan";
import { storeToRefs } from "pinia";

export default {
    layout: AppLayout,
    setup(props) {

        const { dataPerhitungan } = storeToRefs(usePerhitunganStore());
        const { replacedPerhitungan } = usePerhitunganStore();

        const fn = {
            back: () => {
                router.get("./", {}, {
                    preserveState: true,
                    onBefore: () => {
                        replacedPerhitungan();
                    },
                });
            }
        }


        onMounted(() => {
            if (dataPerhitungan.value.length == 0) {
                fn.back();
            }
        });


        const data = dataPerhitungan.value;

        const methods = {
            click: {
                back: () => {
                    fn.back();
                }
            }
        };

        return {
            methods,
            data,
        };
    }
};


</script>