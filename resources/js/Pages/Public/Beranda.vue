<template>
    <!-- untuk layout normal bukan landing -->
    <div class="grid">
            <div class="col-8">
                <div class="grid">
                    <div class="col-6">
                        <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                            6
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                            6
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                            12
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                    4
                </div>
            </div>
            <div class="col-12">
                <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                    <h1>{{ $t('title.config') }}</h1>
                </div>
            </div>
        </div>

    <!-- untuk landing -->
    <!-- <div id="highlights" class="py-4 px-4 lg:px-8 mx-0 my-6 lg:mx-8">
        <div class="grid">
            <div class="col-8">
                <div class="grid">
                    <div class="col-6">
                        <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                            6
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                            6
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                            12
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="text-center p-3 border-round-sm bg-orange-500 font-bold text-white">
                    4
                </div>
            </div>
        </div>
    </div> -->
</template>

<script>
import AppLayout from '@/Layouts/Public/AppLayout.vue';
import { useLayout } from '@/Layouts/Public/composables/layout';
// import { useLayout } from '@/Layouts/Landing/composables/layout';
// import AppLayout from '@/Layouts/Landing/AppLayout.vue';

import { toRaw, markRaw, watch, ref, reactive, computed, onMounted, defineAsyncComponent, inject, getCurrentInstance } from 'vue';
// import ProductService from '@/Services/ProductService';

export default {
    layout: AppLayout,
    setup() {
        const { isDarkTheme } = useLayout();

        // const products = ref(null);
        const lineData = reactive({
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [
                {
                    label: 'First Dataset',
                    data: [65, 59, 80, 81, 56, 55, 40],
                    fill: false,
                    backgroundColor: '#2f4860',
                    borderColor: '#2f4860',
                    tension: 0.4
                },
                {
                    label: 'Second Dataset',
                    data: [28, 48, 40, 19, 86, 27, 90],
                    fill: false,
                    backgroundColor: '#00bb7e',
                    borderColor: '#00bb7e',
                    tension: 0.4
                }
            ]
        });
        const items = ref([
            { label: 'Add New', icon: 'pi pi-fw pi-plus' },
            { label: 'Remove', icon: 'pi pi-fw pi-minus' }
        ]);
        const lineOptions = ref(null);
        // const productService = new ProductService();

        // onMounted(async () => {
        //     const data = await productService.getProductsSmall();
        //     products.value = data;
        // });

        const formatCurrency = (value) => {
            return value.toLocaleString('en-US', { style: 'currency', currency: 'USD' });
        };

        const applyLightTheme = () => {
            lineOptions.value = {
                plugins: {
                    legend: {
                        labels: {
                            color: '#495057'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#495057'
                        },
                        grid: {
                            color: '#ebedef'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#495057'
                        },
                        grid: {
                            color: '#ebedef'
                        }
                    }
                }
            };
        };

        const applyDarkTheme = () => {
            lineOptions.value = {
                plugins: {
                    legend: {
                        labels: {
                            color: '#ebedef'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: '#ebedef'
                        },
                        grid: {
                            color: 'rgba(160, 167, 181, .3)'
                        }
                    },
                    y: {
                        ticks: {
                            color: '#ebedef'
                        },
                        grid: {
                            color: 'rgba(160, 167, 181, .3)'
                        }
                    }
                }
            };
        };

        watch(
            isDarkTheme,
            (val) => {
                if (val) {
                    applyDarkTheme();
                } else {
                    applyLightTheme();
                }
            },
            { immediate: true }
        );

        return {
            // products,
            lineData,
            items,
            lineOptions,
            formatCurrency
        };
    }
};


</script>