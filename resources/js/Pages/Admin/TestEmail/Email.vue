<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h4>Test Email</h4>
                </div>
                <Button label="Kirim Email" @click="methods.click.send" severity="success"/>
            </div>
        </div>
    </div>
    <Toast />
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
import { toRaw, markRaw, watch, ref, reactive, watchEffect, computed, onMounted, defineAsyncComponent, inject, toRefs, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

export default {
    layout: AppLayout,
    setup(props) {
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const methods = {
            click:{
                send:() => {
                    router.get("./testEmail/testSendEmail", {}, {
                        onSuccess: () => {
                            globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Kirim Email', life: 3000 });
                        },
                        onError: (error) => {
                            globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: Object.values(error)[0], life: 3000 });
                        }
                    });
                }
            }
        }

        return {
            methods,
        };
    }
};


</script>
<style lang="scss" scoped>

</style>