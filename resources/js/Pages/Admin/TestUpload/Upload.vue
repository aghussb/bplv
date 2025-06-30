<template>
    <div class="grid">
        <!-- <div class="col-12">
            <div class="card">
                <span class="button-set">
                    <a href="#"><Button label="Delete" severity="warning" icon="pi pi-trash" /></a>
                    <Button label="Save" icon="pi pi-check" />
                </span>
            </div>
        </div> -->

        <div class="col-12">
            <div class="card">

                <div class="doc-intro">
                    <h4>Upload Single Basic</h4>
                </div>
                <small>{{ data.uploadSingleBasic.information.ekstensi }} / Maks : {{ data.uploadSingleBasic.information.ukuran_tampil }}</small>
                <FileUpload mode="basic" name="files[]" :accept="data.uploadSingleBasic.information.ekstensi" :max-file-size="(data.uploadSingleBasic.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="Upload File" @uploader="methods.fileupload.uploadSingleBasic.uploader" @select="methods.fileupload.uploadSingleBasic.select" />
                <br>

                <div v-if="data.dataSingleBasic.length != 0" class="flex gap-1">
                    <a :href="data.dataSingleBasic[0].file1_download" target="_blank" download :title="data.dataSingleBasic[0].file1_download">
                        <Button :label="'Download (' + data.dataSingleBasic[0].file1 + ')'" severity="primary" :href="data.dataSingleBasic[0].file1_download" />
                    </a>
                    <Button label="Hapus Gambar" severity="danger" @click="methods.fileupload.uploadSingleBasic.delete" />
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h4>Upload Single Input</h4>
                </div>
                <small>{{ data.uploadSingleInput.information.ekstensi }} / Maks : {{ data.uploadSingleInput.information.ukuran_tampil }}</small>
                <div class="p-inputgroup flex-1">
                    <InputText placeholder="File Name" disabled id="file_name" :value="data.dataSingleInput[0] != undefined ? data.dataSingleInput[0].file1 : ''" />
                    <FileUpload mode="basic" name="files[]" :auto="true" class="border-noround-left" :accept="data.uploadSingleInput.information.ekstensi" :max-file-size="(data.uploadSingleInput.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="Upload File" @select="methods.fileupload.uploadSingleInput.select" />
                </div>
                <br>
                <div class="flex gap-1">
                    <Button label="Simpan" severity="success" @click="methods.fileupload.uploadSingleInput.click" />
                    <a :href="data.dataSingleInput[0].file1_download" target="_blank" download :title="data.dataSingleInput[0].file1_download" v-if="data.dataSingleInput.length != 0">
                        <Button :label="'Download ' + data.dataSingleInput[0].file1" severity="primary" />
                    </a>
                    <Button label="Hapus Gambar" severity="danger" v-if="data.dataSingleInput.length != 0" @click="methods.fileupload.uploadSingleInput.delete" />
                </div>

            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h4>Upload Single in Multiple Data (Table)</h4>
                </div>
                <small>{{ data.uploadSingleInMultipleData.information.ekstensi }} / Maks : {{ data.uploadSingleInMultipleData.information.ukuran_tampil }}</small>
                <!-- :auto="true" -->
                <!-- <div class="grid">
                    <div class="col-6">
                        <div class="p-inputgroup flex-1">
                            <InputText placeholder="File Name" disabled id="file_name_multiple" />
                            <FileUpload mode="basic" name="files[]" :auto="true" class="border-noround-left" :accept="data.uploadSingleInMultipleData.information.ekstensi" :max-file-size="(data.uploadSingleInMultipleData.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="Upload File" @select="methods.fileupload.uploadSingleInMultipleData.select" />
                        </div>
                    </div>
                    <div class="col-6">
                        <Button label="Tambah" severity="success" @click="methods.fileupload.uploadSingleInMultipleData.tambah"/>
                    </div>
                </div> -->
                <FileUpload ref="refFileUploadSingleInMultipleData" mode="basic" name="files[]" :accept="data.uploadSingleInMultipleData.information.ekstensi" :max-file-size="(data.uploadSingleInMultipleData.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="Upload image" @uploader="methods.fileupload.uploadSingleInMultipleData.uploader" @select="methods.fileupload.uploadSingleInMultipleData.select" />
                <br>
                <DataTable :value="form.fileUploadSingleInMultipleData" showGridlines tableStyle="min-width: 50rem">
                    <template #empty>
                        <div class="text-center">Tidak ada data</div>
                    </template>
                    <Column style="text-align: center;" field="data">
                        <template #header> <span class="flex-1 text-center">No.</span> </template>
                        <template #body="{ frozenRow, index }">
                            {{ index + 1 }}
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Name</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.name ?? data.file1 }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Size</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.size }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Type</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <span>{{ data.type }}</span>
                            </div>
                        </template>
                    </Column>
                    <Column field="data">
                        <template #header> <span class="flex-1 text-center">Aksi</span> </template>
                        <template #body="{ data, frozenRow, index }">
                            <div class="text-center">
                                <Button label="Hapus" severity="danger" @click="methods.fileupload.uploadSingleInMultipleData.delete(index, data)" />
                            </div>
                        </template>
                    </Column>
                </DataTable>
                <br>
                <div class="flex gap-1">
                    <Button label="Simpan" severity="success" @click="methods.fileupload.uploadSingleInMultipleData.click" />
                    <Button label="Jadikan Satu File Zip" severity="info" @click="methods.fileupload.uploadSingleInMultipleData.zip" />
                </div>
                <!-- <FileUpload mode="basic" name="demo[]" url="./upload.php" accept="image/*" :maxFileSize="1000000" @upload="onUpload" /> -->

            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="doc-intro">
                    <h4>Upload Single Object</h4>
                </div>
                <!-- :auto="true" -->
                <div class="formgrid grid">
                    <div class="field col-4">
                        <label for="role">test 1 </label>
                        <br>
                        <small>{{ data.uploadSingleObject1.information.ekstensi }} / Maks : {{ data.uploadSingleObject1.information.ukuran_tampil }}</small>
                        <div class="p-inputgroup flex-1">
                            <InputText placeholder="File Name" disabled id="file_name_object1" :value="data.dataSingleObject[0] != undefined ? data.dataSingleObject[0].file1 : ''" />
                            <FileUpload mode="basic" name="files[]" :auto="true" class="border-noround-left" :accept="data.uploadSingleObject1.information.ekstensi" :max-file-size="(data.uploadSingleObject1.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="1" @select="methods.fileupload.uploadSingleObject.select1" />
                        </div>
                        <!-- <FileUpload mode="basic" name="files[]" :accept="data.uploadSingleObject1.information.ekstensi" :max-file-size="(data.uploadSingleObject1.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="Upload image" @uploader="methods.fileupload.uploadSingleObject" /> -->
                    </div>
                    <div class="field col-4">
                        <label for="role">test 2 </label>
                        <br>
                        <small>{{ data.uploadSingleObject2.information.ekstensi }} / Maks : {{ data.uploadSingleObject2.information.ukuran_tampil }}</small>
                        <div class="p-inputgroup flex-1">
                            <InputText placeholder="File Name" disabled id="file_name_object2" :value="data.dataSingleObject[0] != undefined ? data.dataSingleObject[0].file2 : ''" />
                            <FileUpload mode="basic" name="files[]" :auto="true" class="border-noround-left" :accept="data.uploadSingleObject2.information.ekstensi" :max-file-size="(data.uploadSingleObject2.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="2" @select="methods.fileupload.uploadSingleObject.select2" />
                        </div>
                        <!-- <FileUpload mode="basic" name="files[]" :accept="data.uploadSingleObject2.information.ekstensi" :max-file-size="(data.uploadSingleObject2.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="Upload image" @uploader="methods.fileupload.uploadSingleObject" /> -->
                    </div>
                    <div class="field col-4">
                        <label for="role">test 3 </label>
                        <br>
                        <small>{{ data.uploadSingleObject3.information.ekstensi }} / Maks : {{ data.uploadSingleObject3.information.ukuran_tampil }}</small>
                        <div class="p-inputgroup flex-1">
                            <InputText placeholder="File Name" disabled id="file_name_object3" :value="data.dataSingleObject[0] != undefined ? data.dataSingleObject[0].file3 : ''" />
                            <FileUpload mode="basic" name="files[]" :auto="true" class="border-noround-left" :accept="data.uploadSingleObject3.information.ekstensi" :max-file-size="(data.uploadSingleObject3.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="3" @select="methods.fileupload.uploadSingleObject.select3" />
                        </div>
                        <!-- <FileUpload mode="basic" name="files[]" :accept="data.uploadSingleObject3.information.ekstensi" :max-file-size="(data.uploadSingleObject3.information.ukuran / 1.024) * 1000" :custom-upload="true" choose-label="Upload image" @uploader="methods.fileupload.uploadSingleObject" /> -->
                    </div>
                </div>
                <br>
                <Button label="Simpan" severity="success" @click="methods.fileupload.uploadSingleObject.click" />
            </div>
        </div>
    </div>
    <Toast />
</template>

<script>
import AppLayout from '@/Layouts/Admin/AppLayout.vue';
import { toRaw, markRaw, watch, ref, reactive, watchEffect, computed, onMounted, defineAsyncComponent, inject, toRefs, getCurrentInstance } from 'vue';
import { router } from '@inertiajs/vue3'

import { usePage } from '@inertiajs/vue3';
import { Upload } from '@/utils';

export default {
    layout: AppLayout,
    components: {
    },
    props: {
        data: Array,
    },
    setup(props) {
        const globalVariable = getCurrentInstance().appContext.config.globalProperties;

        let idUploadConfigurations = {
            singleBasic: 1,
            singleInput: 2,
            singleInMultipleData: 3,
            singleObject1: 4,
            singleObject2: 5,
            singleObject3: 6,
        };

        const data = reactive({
            dataSingleBasic: props.data.filter(item => item.type == "Single Basic"),
            uploadSingleBasic: {
                init: new Upload(idUploadConfigurations.singleBasic),
                information: {},
            },
            dataSingleInput: props.data.filter(item => item.type == "Single Input"),
            uploadSingleInput: {
                init: new Upload(idUploadConfigurations.singleInput),
                information: {},
            },
            dataSingleInMultipleData: props.data.filter(item => item.type == "Single in Multiple Data"),
            uploadSingleInMultipleData: {
                init: new Upload(idUploadConfigurations.singleInMultipleData),
                information: {},
            },
            dataSingleObject: props.data.filter(item => item.type == "Single Object"),
            uploadSingleObject1: {
                init: new Upload(idUploadConfigurations.singleObject1),
                information: {},
            },
            uploadSingleObject2: {
                init: new Upload(idUploadConfigurations.singleObject2),
                information: {},
            },
            uploadSingleObject3: {
                init: new Upload(idUploadConfigurations.singleObject3),
                information: {},
            },
        });

        const form = reactive({
            fileUploadSingleBasic: [],
            fileUploadSingleInput: [],
            fileUploadSingleInMultipleData: props.data.filter(item => item.type == "Single in Multiple Data"),
            fileUploadSingleInMultipleDataHapus: [],
            fileUploadSingleObject: {
                satu: [],
                dua: [],
                tiga: [],
            },
        });

        watch(
            () => props.data,
            (newVal) => {
                data.dataSingleBasic = newVal.filter(item => item.type == "Single Basic");
                data.dataSingleInput = newVal.filter(item => item.type == "Single Input");
                form.fileUploadSingleInMultipleData = newVal.filter(item => item.type == "Single in Multiple Data");
                data.dataSingleObject = newVal.filter(item => item.type == "Single Object");
            }
        );

        onMounted(async () => {
            data.uploadSingleBasic.information = await data.uploadSingleBasic.init.information();
            data.uploadSingleInput.information = await data.uploadSingleInput.init.information();
            data.uploadSingleInMultipleData.information = await data.uploadSingleInMultipleData.init.information();
            data.uploadSingleObject1.information = await data.uploadSingleObject1.init.information();
            data.uploadSingleObject2.information = await data.uploadSingleObject2.init.information();
            data.uploadSingleObject3.information = await data.uploadSingleObject3.init.information();
        });


        const refFileUploadSingleInMultipleData = ref(null);

        const methods = {
            fileupload: {
                uploadSingleBasic: {
                    select: (event) => {
                        let dataFiles = new FormData();
                        dataFiles.append('files[]', event.originalEvent.target.files[0]);

                        data.uploadSingleBasic.init.check(dataFiles).then((value) => {
                            if (value === true) {
                                form.fileUploadSingleBasic.push(event.files[0]);
                            }
                            else {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: value, life: 3000 });
                            }
                        });
                    },
                    uploader: () => {
                        router.post("./testUpload/storeUpload",
                            {
                                data: data.dataSingleBasic[0],
                                type: "Single Basic",
                                files: form.fileUploadSingleBasic,
                                id_file_configurations: idUploadConfigurations.singleBasic,
                            }
                            , {
                                forceFormData: true,
                                preserveState: true,
                                onSuccess: () => {
                                    globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan', life: 3000 });
                                    form.fileUploadSingleBasic = [];
                                },
                                onError: (error) => {
                                    globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: error[Object.keys(error)[0]], life: 3000 });
                                    return;
                                }
                            });
                    },
                    delete: () => {
                        router.post('./testUpload/deleteUpload', {
                            data: data.dataSingleBasic[0],
                            type: "Single Basic",
                        }, {
                            onSuccess: () => {
                                globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Hapus', life: 3000 });
                            },
                        });
                    }
                },
                uploadSingleInput: {
                    select: (event) => {
                        let dataFiles = new FormData();
                        dataFiles.append('files[]', event.originalEvent.target.files[0]);

                        data.uploadSingleInput.init.check(dataFiles).then((value) => {
                            if (value === true) {
                                document.getElementById("file_name").value = event.files[0].name;
                                form.fileUploadSingleInput.push(event.files[0]);
                            }
                            else {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: value, life: 3000 });
                            }
                        });
                    },
                    click: () => {
                        router.post("./testUpload/storeUpload",
                            {
                                data: data.dataSingleInput[0],
                                type: "Single Input",
                                files: form.fileUploadSingleInput,
                                id_file_configurations: idUploadConfigurations.singleInput,
                            }
                            , {
                                forceFormData: true,
                                preserveState: true,
                                onSuccess: () => {
                                    globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan', life: 3000 });
                                    form.fileUploadSingleInput = [];
                                },
                                onError: (error) => {
                                    globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: error[Object.keys(error)[0]], life: 3000 });
                                    return;
                                }
                            });
                    },
                    delete: () => {
                        router.post('./testUpload/deleteUpload', {
                            data: data.dataSingleInput[0],
                            type: "Single Input",
                        }, {
                            onSuccess: () => {
                                globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Hapus', life: 3000 });
                            },
                        });
                    }
                },
                uploadSingleInMultipleData: {
                    select: (event) => {
                        let dataFiles = new FormData();
                        dataFiles.append('files[]', event.originalEvent.target.files[0]);

                        var statusDuplicate = form.fileUploadSingleInMultipleData.filter(item => {
                            return item.name == event.originalEvent.target.files[0].name;
                        })

                        if (statusDuplicate.length != 0) {
                            globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: "Data Sudah Ada", life: 3000 });
                            refFileUploadSingleInMultipleData.value.clear();
                            return;
                        }

                        data.uploadSingleInMultipleData.init.check(dataFiles).then((value) => {
                            if (!(value === true)) {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: value, life: 3000 });
                                return;
                            }
                        });

                    },
                    uploader: (event) => {
                        form.fileUploadSingleInMultipleData.push(event.files[0]);
                    },
                    click: () => {
                        router.post("./testUpload/storeUpload",
                            {
                                type: "Single in Multiple Data",
                                data_file: form.fileUploadSingleInMultipleData,
                                data_hapus: form.fileUploadSingleInMultipleDataHapus,
                                id_file_configurations: idUploadConfigurations.singleInMultipleData,
                            }
                            , {
                                forceFormData: true,
                                preserveState: true,
                                onSuccess: () => {
                                    globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan', life: 3000 });
                                },
                                onError: (error) => {
                                    globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: error[Object.keys(error)[0]], life: 3000 });
                                    return;
                                }
                            });
                    },
                    delete: (index, data) => {
                        if (data.id != undefined) {
                            form.fileUploadSingleInMultipleDataHapus.push(data);
                        }

                        form.fileUploadSingleInMultipleData.splice(index, 1);
                        // router.post('./testUpload/deleteUpload', {
                        //     data: data.dataSingleBasic[0],
                        //     type: "Single Basic",
                        // }, {
                        //     onSuccess: () => {
                        //         globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Hapus', life: 3000 });
                        //     },
                        // });
                    },
                    zip: () => {
                        router.post("./testUpload/zippedFiles",
                            {
                                data_file: form.fileUploadSingleInMultipleData,
                            }
                            , {
                                onSuccess: () => {
                                    globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Jadikan Zip', life: 3000 });
                                },
                                onError: (error) => {
                                    globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: error[Object.keys(error)[0]], life: 3000 });
                                    return;
                                }
                            });
                    }
                },
                uploadSingleObject: {
                    select1: (event) => {
                        let dataFiles = new FormData();
                        dataFiles.append('files[]', event.originalEvent.target.files[0]);

                        data.uploadSingleObject1.init.check(dataFiles).then((value) => {
                            if (value === true) {
                                document.getElementById("file_name_object1").value = event.files[0].name;
                                form.fileUploadSingleObject.satu.push(event.files[0]);
                            }
                            else {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: value, life: 3000 });
                            }
                        });
                    },
                    select2: (event) => {
                        let dataFiles = new FormData();
                        dataFiles.append('files[]', event.originalEvent.target.files[0]);

                        data.uploadSingleObject2.init.check(dataFiles).then((value) => {
                            if (value === true) {
                                document.getElementById("file_name_object2").value = event.files[0].name;
                                form.fileUploadSingleObject.dua.push(event.files[0]);
                            }
                            else {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: value, life: 3000 });
                            }
                        });
                    },
                    select3: (event) => {
                        let dataFiles = new FormData();
                        dataFiles.append('files[]', event.originalEvent.target.files[0]);

                        data.uploadSingleObject3.init.check(dataFiles).then((value) => {
                            if (value === true) {
                                document.getElementById("file_name_object3").value = event.files[0].name;
                                form.fileUploadSingleObject.tiga.push(event.files[0]);
                            }
                            else {
                                globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: value, life: 3000 });
                            }
                        });
                    },
                    click: () => {
                        router.post("./testUpload/storeUpload",
                            {

                                type: "Single Object",
                                files: form.fileUploadSingleObject,
                                id_file_configurations_object1: idUploadConfigurations.singleObject1,
                                id_file_configurations_object2: idUploadConfigurations.singleObject2,
                                id_file_configurations_object3: idUploadConfigurations.singleObject3,
                                data: data.dataSingleObject[0],
                            }
                            , {
                                forceFormData: true,
                                preserveState: true,
                                onSuccess: () => {
                                    globalVariable.$toast.add({ severity: 'success', summary: 'SUKSES', detail: 'Berhasil Simpan', life: 3000 });
                                    form.fileUploadSingleObject = {
                                        satu: [],
                                        dua: [],
                                        tiga: [],
                                    };
                                },
                                onError: (error) => {
                                    globalVariable.$toast.add({ severity: 'error', summary: 'PERHATIAN', detail: error[Object.keys(error)[0]], life: 3000 });
                                    return;
                                }
                            });
                    }
                },
            }

        }

        return {
            refFileUploadSingleInMultipleData,
            form,
            methods,
            data
        };
    }
};


</script>
<style lang="scss" scoped>
.p-fileupload :deep(.p-message) {
    display: none;
}

// Memperbaiki bug button set
.button-set :deep(.p-button:first-child:not(:only-child)) {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.button-set :deep(.p-button:last-child:not(:only-child)) {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.button-set :deep(.p-button:not(:first-child):not(:last-child)) {
  border-radius: 0 !important;
}

.button-set a:not(:first-child):not(:last-child) .p-button {
  border-radius: 0 !important;
}

.button-set a:first-child:not(:only-child) .p-button {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}

.button-set a:last-child:not(:only-child) .p-button {
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
}

</style>