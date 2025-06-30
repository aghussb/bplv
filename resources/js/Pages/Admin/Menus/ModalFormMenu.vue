<template>
    <div class="p-dialog-header">
        <span class="p-dialog-title">{{ (this.thisInject.data.statusModal === 1) ? "Tambah Menu" : ((this.thisInject.data.statusModal === 2) ? "Tambah Sub Menu" : "Ubah Menu") }}</span>
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
                    <label for="icon">Icon <small class="text-red-500">*</small></label>
                    <div class="text-center">
                        <i :class="'text-8xl mb-3 text-color-secondary ' + form.icon"></i>
                    </div>
                    <div class="p-inputgroup flex-1">
                        <InputText id="icon" placeholder="Icon" v-model="form.icon" disabled />
                        <Button label="Pilih Icon" icon="pi pi-search" severity="success" @click="methods.click.openModalIcon" />
                    </div>
                </div>
                <div class="field col-12">
                    <label for="label">Label <small class="text-red-500">*</small></label>
                    <InputText id="label" type="text" placeholder="Label" v-model="form.label" />
                </div>
                <div class="field col-12">
                    <label for="routeName">Route Name</label>
                    <InputText id="routeName" type="text" placeholder="Route Name" v-model="form.route_name" />
                </div>
                <div class="field col-12">
                    <label for="function">
                        Function
                    </label>
                    <Button icon="pi pi-info-circle" size="small" class="px-2 py-0 mb-2 ml-2 w-auto" severity="info" text @click="methods.click.toggleFunction" />
                    <OverlayPanel ref="overlayPanelFunction">
                        Jika diisi maka menu akan menjadi button yang akan memanggil isi dari <b>Function</b> tersebut.
                    </OverlayPanel>
                    <InputText id="function" type="text" placeholder="Function" v-model="form.function" />
                </div>
                <div class="field flex flex-column gap-2 col-12">
                    <div>Apakah perlu Hak Akses? <small class="text-red-500">*</small> </div>
                    <div class="flex flex-wrap gap-3">
                        <div class="flex align-items-center">
                            <RadioButton v-model="form.is_permission" inputId="hakAksesYa" name="is_permission" value="1" />
                            <label for="hakAksesYa" class="ml-2">Ya</label>
                        </div>
                        <div class="flex align-items-center">
                            <RadioButton v-model="form.is_permission" inputId="hakAksesTidak" name="is_permission" value="0" />
                            <label for="hakAksesTidak" class="ml-2">Tidak</label>
                        </div>
                    </div>
                </div>
                <div class="field col-12">
                    <label for="jenisLayout">Jenis Config <small class="text-red-500">*</small></label>
                    <Dropdown v-model="form.config" :disabled="status.disabled.config" :options="computedConfig" placeholder="Pilih Config" class="w-full" />
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
import ModalPilihIcon from './ModalPilihIcon.vue';

export default {
    setup(props) {
        const thisInject = inject('dialogRef');
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        const data = reactive({
            config: ['Sebelum Login', 'Selalu Tampil', 'Setelah Login'],
        });

        const status = reactive({
            disabled: {
                config: true
            }
        });

        const form = reactive({
            icon: null,
            label: null,
            route_name: null,
            function: null,
            is_permission: null,
            config: null
        });

        if (thisInject.value.data.dataModal != null && thisInject.value.data.statusModal == 3) {
            Object.assign(form, thisInject.value.data.dataModal);
        }

        const overlayPanelFunction = ref(null);

        const methods = {
            click: {
                openModalIcon: () => {
                    globalVariable.$dialog.open(ModalPilihIcon, {
                        props: {
                            showHeader: false,
                            style: {
                                width: '50vw',
                            },
                            modal: true,
                            contentClass: 'p-dialog',
                            contentStyle: 'padding:0 !important;max-height:100%;overflow-y:unset;'
                        },
                        onClose: (options) => {
                            const data = options.data;
                            if (data) {
                                form.icon = "pi " + data.icon;
                            }
                        }
                    });
                },
                save: () => {
                    if (form.icon == null) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Icon belum dipilih', life: 3000 });
                        return
                    }

                    if (form.label == null || form.label == "") {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Label belum diisi', life: 3000 });
                        return
                    }

                    if (form.is_permission == null) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Hak Akses belum dipilih', life: 3000 });
                        return
                    }

                    if (form.config == null) {
                        globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: 'Jenis Config belum dipilih', life: 3000 });
                        return
                    }

                    // axios.post(route("checkValidationMenu"), {
                    axios.post("./menu/checkValidationMenu", {
                        form: form
                    })
                        .then((result) => {
                            // console.log(result);
                            var objectResult = {
                                ...form,
                                layout: thisInject.value.data.layout,
                                items: (thisInject.value.data.dataModal != null && thisInject.value.data.statusModal == 3) ? thisInject.value.data.dataModal.items : []
                            }

                            thisInject.value.close(objectResult);
                        })
                        .catch((error) => {
                            if (error.response.status == 422) {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: Object.values(error.response.data.errors)[0][0], life: 3000 });
                            }
                            else {
                                console.log(error);
                            }
                        });

                },
                toggleFunction: (event) => {
                    overlayPanelFunction.value.toggle(event);
                }
            }
        };

        const computedConfig = computed(() => {
            if (form.is_permission === "1" || form.is_permission === "0") {
                if (form.is_permission === "1") {
                    var indexArray = [2];
                    form.config = "Setelah Login";
                    status.disabled.config = true;
                }
                else if (form.is_permission === "0") {
                    var indexArray = [0, 1];
                    status.disabled.config = false;
                }

                return data.config.filter((_, index) => indexArray.includes(index));
            }
            else {
                status.disabled.config = true;
                return data.config;
            }
        });

        return {
            thisInject,
            computedConfig,
            overlayPanelFunction,
            data,
            status,
            form,
            methods,
        }
    }
};
</script>