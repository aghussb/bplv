export class Upload {
    constructor(id) {
        this.id = id;
    }

    information() {
        return axios.get(window.origin+"/core/upload/information", {
            params: {
                id: this.id
            }
        }).then(response => {
            if (response.data.success) {
                return response.data.data;
            } else {
                console.log("Error Get Information");
                return {};
            }
        });
    }

    check(dataUpload) {
        dataUpload.append('id_upload_configurations', this.id);
        return axios.post(window.origin+"/core/upload/check", dataUpload,{ headers: { "Content-Type": "multipart/form-data" } }).then(response => {
            if (response.data.success) {
                return response.data.data;
            }
        }).catch((error) => {
            return error.response.data.message;
        });

    }
}

export class Convert {
    constructor(data) {
        this.data = data;
    }

    formatFileSize() {
        var decimals = 2;
        if (!+this.data) return '0 Bytes'

        const k = 1024
        const dm = decimals < 0 ? 0 : decimals
        const sizes = ['Bytes', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb']

        const i = Math.floor(Math.log(this.data) / Math.log(k))

        return `${parseFloat((this.data / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
    }
}

// axios.post('/apps/transactions/searchProduct', {
//     //send data "barcode"
//     barcode: barcode.value

// }).then(response => {
//     if(response.data.success) {

//         //assign response to state "product"
//         product.value = response.data.data;
//     } else {

//         //set state "product" to empty object
//         product.value = {};
//     }
// });