<template>
    <div class="p-dialog-header">
        <span class="p-dialog-title">{{ (!this.thisInject.data.statusModal) ? "Tambah " : "Ubah " }} Upload Configuration</span>
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
                    <label for="role">Menu </label>
                    <Dropdown :options="this.thisInject.data.dataMenu" showClear v-model="form.menu_id" filter optionLabel="label" optionValue="id" placeholder="Pilih Menu">
                        <!-- <template #value="slotProps">
                            <div v-if="slotProps.value" class="flex align-items-center">
                                <div>{{ slotProps.value.label }}</div>
                            </div>
                            <span v-else>
                                {{ slotProps.placeholder }}
                            </span>
                        </template> -->
                        <template #option="slotProps">
                            <div class="flex align-items-center">
                                <div>{{ slotProps.option.label }}</div>
                            </div>
                        </template>
                    </Dropdown>
                </div>
                <div class="field col-12">
                    <label for="keterangan">Keterangan <small class="text-red-500">*</small> </label>
                    <Textarea v-model="form.keterangan" placeholder="Keterangan" id="keterangan" />
                </div>
                <div class="field col-12">
                    <label>Ekstensi <small class="text-red-500">*</small> </label>
                    <span class="p-input-icon-left">
                        <i class="pi pi-search" style="top:40%" />
                        <!-- v-model="form.ekstensi" -->
                        <InputText type="text" v-model="filter.ekstensi" class="mb-3" placeholder="Cari Ekstensi" />
                    </span>
                    <div class="grid">
                        <div v-for="value in computedEkstensi" :key="value" class="flex align-content-center col-2 p-2 gap-2">
                            <Checkbox v-model="form.ekstensi" :inputId="value" name="category" :value="value" />
                            <label :for="value">{{ value }}</label>
                        </div>
                    </div>
                </div>
                <div class="field col-12">
                    <label for="role">Ukuran <small>(Kb)</small> <small class="text-red-500">*</small> </label>
                    <div class="p-inputgroup">
                        <InputMask v-model="form.ukuran" mask="999999999" :autoClear="false" slotChar="" placeholder="Ukuran" @update:modelValue="methods.updateModelValue.ukuran" />
                        <span class="p-inputgroup-addon w-10rem">{{ data.ukuran || "0 KB" }}</span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="p-dialog-footer">
        <Button type="button" label="Batal" icon="pi pi-times" @click="this.thisInject.close()" text></Button>
        <Button type="button" label="Simpan" icon="pi pi-check" @click="methods.click.save"></Button>
    </div>
</template>

<script>
import { defineEmits, toRaw, ref, reactive, watch, onMounted, computed, inject, defineAsyncComponent, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'
import { Convert } from '@/utils';

export default {
    setup(props) {
        const thisInject = inject('dialogRef');
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const data = reactive({
            ekstensi: thisInject.value.data.dataEkstensi,
            ukuran: null
        });

        const filter = reactive({
            ekstensi: null
        });

        const form = reactive({
            menu_id: null,
            keterangan: null,
            ekstensi: [],
            ukuran: null
        });

        if (thisInject.value.data.statusModal) {
            var objForm = {...thisInject.value.data.dataModal};
            objForm.ekstensi = objForm.ekstensi_value;
            data.ukuran = objForm.ukuran_tampil;  
            Object.assign(form, objForm);
        }

        const methods = {
            updateModelValue: {
                ukuran: () => {
                    data.ukuran = new Convert(form.ukuran * (1024)).formatFileSize();
                }
            },
            click: {
                save: () => {
                    if (form.keterangan == null || form.keterangan == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Keterangan belum diisi', life: 3000 });
                        return
                    }

                    if (form.ekstensi.length == 0) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Ekstensi belum dipilih', life: 3000 });
                        return
                    }

                    if (parseInt(form.ukuran) == null || parseInt(form.ukuran) == 0 || form.ukuran == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Ukuran belum diisi', life: 3000 });
                        return
                    }

                    router.post("./upload/storeUpload", {
                        form: form,
                    }, {
                        onSuccess: () => {
                            globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan Upload', life: 3000 });
                            thisInject.value.close(true);
                        },
                        onError: (error) => {
                            globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: Object.values(error)[0], life: 3000 });
                        }
                    });

                },
            },
        };

        const computedEkstensi = computed(() => {
            if (filter.ekstensi) return data.ekstensi.filter((value) => value.indexOf(filter.ekstensi.toLowerCase()) > -1);
            else return data.ekstensi;
        });

        return {
            computedEkstensi,
            thisInject,
            data,
            form,
            methods,
            filter,
        }
    }
};
</script>