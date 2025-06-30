<template>
    <div class="p-dialog-header">
        <span class="p-dialog-title">{{ (!this.thisInject.data.statusModal) ? "Tambah " : "Ubah " }} User</span>
        <div class="p-dialog-header-icons">
            <button class="p-dialog-header-icon p-dialog-header-close p-link" type="button" @click="this.thisInject.close()">
                <i class="pi pi-times"></i>
                <span class="p-ink"></span>
            </button>
        </div>
    </div>

    <div class="p-dialog-content" style="overflow: auto;max-height: 412px;">
        <div class="p-fluid">
            <div class="formgrid grid">
                <div class="field col-12">
                    <label for="nama">Nama <small class="text-red-500">*</small></label>
                    <InputText id="nama" type="text" placeholder="Nama" v-model="form.name" />
                </div>
                <div class="field col-6">
                    <label for="email">Email <small class="text-red-500">*</small></label>
                    <InputText id="email" type="email" placeholder="Email" v-model="form.email" />
                </div>
                <div class="field col-6">
                    <label for="password">Roles <small class="text-red-500">*</small></label>
                    <MultiSelect v-model="form.roles" :options="this.thisInject.data.dataRoles" filter optionLabel="name" optionValue="name" placeholder="Pilih Roles">
                        <template #option="slotProps">
                            <div class="flex align-items-center">
                                <div>{{ slotProps.option.name }} - {{ slotProps.option.layout }}</div>
                            </div>
                        </template>
                    </MultiSelect>
                </div>
                <div class="field col-6">
                    <label for="password">Password <small class="text-red-500">*</small></label>
                    <Password v-model="form.password" :feedback="false" toggleMask placeholder="Password" />
                </div>
                <div class="field col-6">
                    <label for="password">Konfirmasi Password <small class="text-red-500">*</small></label>
                    <Password v-model="form.password_confirmation" :feedback="false" toggleMask placeholder="Konfirmasi Password" />
                </div>
            </div>
        </div>
    </div>

    <div class="p-dialog-footer">
        <Button type="button" label="Batal" icon="pi pi-times" @click="this.thisInject.close()" text></Button>
        <Button type="button" label="Simpan" icon="pi pi-check" @click="methods.click.save"></Button>
    </div>

    <ConfirmDialog group="templating">
        <template #message="slotProps">
            <div class="grid" style="width: 450px;">
                <div class="flex align-content-center col-12 justify-content-center">
                    <h4 class="pl-2">{{ slotProps.message.message }}</h4>
                </div>
                <div class="col-12">
                    <InlineMessage severity="warn" class="border-primary w-full justify-content-start">
                        <div class="flex align-items-center">
                            <div class="ml-2">Jika ya maka user akan otomatis <b>logout</b> atau <b>keluar</b>.</div>
                        </div>
                    </InlineMessage>
                </div>
            </div>
        </template>
    </ConfirmDialog>

</template>

<script>
import { defineEmits, toRaw, ref, reactive, watch, onMounted, computed, inject, defineAsyncComponent, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

export default {
    setup(props) {
        const thisInject = inject('dialogRef');
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const fn = {
            saveUser:(statusLogout) => {
                router.post("./user/storeUser", {
                        form: form,
                    }, {
                        onSuccess: () => {
                            globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan User', life: 3000 });
                            if (statusLogout) {
                                router.post("/logout");
                            }
                            thisInject.value.close(statusLogout);
                        },
                        onError: (error) => {
                            globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: Object.values(error)[0], life: 3000 });
                        }
                    });
            }
        };

        const form = reactive({
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
            roles: []
        });

        if (thisInject.value.data.statusModal) {
            Object.assign(form, thisInject.value.data.dataModal);
        }
        
        const methods = {
            click: {
                save: () => {
                    
                    if (form.name == null || form.name == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Nama belum diisi', life: 3000 });
                        return
                    }

                    if (form.email == null || form.email == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Email belum diisi', life: 3000 });
                        return
                    }

                    if (form.roles.length == 0) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Roles belum dipilih', life: 3000 });
                        return
                    }

                    if (form.password == null || form.password == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Password belum diisi', life: 3000 });
                        return
                    }

                    if (form.password_confirmation == null || form.password_confirmation == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Konfirmasi Password belum diisi', life: 3000 });
                        return
                    }

                    if (form.password != form.password_confirmation) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Password dan Konfirmasi Password tidak sama', life: 3000 });
                        return
                    }

                    if (thisInject.value.data.statusModal) {
                        if (thisInject.value.data.dataModal.id == usePage().props.auth.user.id) {
                            globalVariable.$confirm.require({
                                group: 'templating',
                                message: 'Apakah yakin ingin mengubah data?',
                                header: 'Konfirmasi',
                                // icon: 'pi pi-exclamation-triangle',
                                acceptLabel: 'Ya',
                                rejectLabel: 'Tidak',
                                accept: () => {
                                    fn.saveUser(true);
                                },
                            });
                        }
                        else {
                            fn.saveUser(false);
                        }
                    }
                    else {
                        fn.saveUser(false);
                    }

                },
            },
        };

        return {
            thisInject,
            // data,
            form,
            methods,
        }
    }
};
</script>