<template>
    <div class="grid">
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h4>Test Realtime Chat</h4>
                </div>
                <div class="flex flex-wrap gap-4 justify-content-center">
                    <div class="flex align-items-center">
                        <RadioButton v-model="form.user" inputId="user1" name="user" value="1" />
                        <label for="user1" class="ml-2"> <i class="pi pi-user"></i> User 1</label>
                    </div>
                    <div class="flex align-items-center">
                        <RadioButton v-model="form.user" inputId="user2" name="user" value="2" />
                        <label for="user2" class="ml-2"><i class="pi pi-user"></i> User 2</label>
                    </div>
                </div>
                <div class="grid mt-4">
                    <!-- data -->
                    <div class="col-12" v-for="(row, index) of data.chatRoom" :key="index">
                        <div class="flex justify-content-end" v-if="row.user == form.user">
                            <div class="bg-primary text-white border-round p-2 w-fit">
                                {{ row.chat }}
                            </div>
                        </div>
                        <div class="bg-green-600 text-white border-round p-2 w-fit" v-else>
                            {{ row.chat }}
                        </div>
                    </div>
                    <!-- <div class="col-12">
                    </div> -->
                </div>
                <form @submit.prevent="methods.submit.send">
                    <div class="formgrid grid">
                        <div class="field col-12">
                            <span class="p-input-icon-right w-full">
                                <i class="fa-regular fa-paper-plane"></i>
                                <InputText type="text" v-model="form.chat" placeholder="Kirim" class="w-full" />
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <Toast />
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
import { toRaw, markRaw, watch, ref, reactive, watchEffect, computed, onMounted, onBeforeUnmount, defineAsyncComponent, inject, toRefs, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

import { usePage } from '@inertiajs/vue3';

export default {
    layout: AppLayout,
    setup(props) {
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const form = reactive({
            user: null,
            chat: null
        });

        const data = reactive({
            chatRoom: []
        });

        // window.Echo.channel('chat-channel')
        //     .listen('.ChatEvent', (resultEcho) => {
        //         // console.log(resultEcho);
        //         data.chatRoom.push(resultEcho);
        //         form.chat = null;
        //     });

        const methods = {
            submit: {
                send: () => {

                    if (form.user == null) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'User belum dipilih', life: 3000 });
                        return
                    }

                    if (form.chat == null || form.chat == '') {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Chat belum diisi', life: 3000 });
                        return
                    }

                    axios.post("./testRealtime/sendChatRealtime", {
                        form: form
                    })
                        .then((result) => {
                            // console.log(result);
                        })
                        .catch((error) => {
                            if (error.response.status == 422) {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: Object.values(error.response.data.errors)[0][0], life: 3000 });
                            }
                            else {
                                console.log(error);
                            }
                        });
                }
            }
        }

        return {
            data,
            form,
            methods,
        };
    }
};
</script>
<style lang="scss" scoped></style>