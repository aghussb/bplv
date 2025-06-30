<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h4>Test State Management</h4>
                </div>

                <div v-for="row of data" :key="row.id" class="flex gap-1">
                    <Checkbox v-model="selectedData" :inputId="row.id" name="category" class="mb-2" :value="row" />
                    <label :for="row.id">{{ row.angka1 }} + {{ row.angka2 }}</label>
                </div>

                <Button label="Cek Hasil" @click="methods.click.send" severity="success" class="mt-4"/>

            </div>
        </div>
    </div>
    <Toast />
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
import { ref, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

import { usePerhitunganStore } from "./stores/perhitungan";

export default {
    layout: AppLayout,
    setup(props) {
        const { replacedPerhitungan } = usePerhitunganStore();

        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const methods = {
            click: {
                send: () => {
                    if (selectedData.value.length == 0) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Perhitungan belum dipilih', life: 3000 });
                        return;
                    }

                    router.get("./testStateManagement/detail", {}, {
                        preserveState: true,
                        onBefore: () => {
                            replacedPerhitungan(selectedData.value);
                        },
                    });
                }
            }
        }

        const data = ref([
            { id: "index-1", angka1: 9, angka2: 3, },
            { id: "index-2", angka1: 8, angka2: 7, },
            { id: "index-3", angka1: 23, angka2: 3, },
            { id: "index-4", angka1: 15, angka2: 2, },
            { id: "index-5", angka1: 12, angka2: 6, },
        ]);

        const selectedData = ref([]);

        return {
            data,
            selectedData,
            methods,
        };
    }
};


</script>